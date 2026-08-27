<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TenantController extends Controller
{
    private $cscApiKey = '136b81a1ddecfa599361f165fd9cf5a2da3dae84ed88469e3b888d8d5cf814fc';

    /**
     * Render the main dealer registration sheet view.
     */
    public function index(Request $request)
    {
        // 1. Unpack pre-selected plan parameters directly from the incoming URL request query
        $activePlan = $request->get('plan', 'free');

        // 2. Fetch primary state layers safely from secure external channels
        $statesList = [];
        try {
            $response = Http::withHeaders([
                'X-CSCAPI-KEY' => $this->cscApiKey
            ])->get('https://countrystatecity.in');

            if ($response->successful()) {
                $statesList = collect($response->json())->sortBy('name')->toArray();
            }
        } catch (\Exception $e) {
            Log::error('Location API channel handshake failure: ' . $e->getMessage());
        }

        return view('frontend.become-partner', compact('activePlan', 'statesList'));
    }

    /**
     * Local Proxy Endpoint to pull municipal listings based on dynamic selections.
     */
    public function getCities($stateCode)
    {
        try {
            $response = Http::withHeaders([
                'X-CSCAPI-KEY' => $this->cscApiKey
            ])->get("https://countrystatecity.in/{$stateCode}/cities");

            if ($response->successful()) {
                $citiesList = collect($response->json())->sortBy('name')->map(function($city) {
                    return ['name' => $city['name']];
                })->values();

                if ($citiesList->isEmpty()) {
                    $citiesList = collect([['name' => 'Other']]);
                }

                return response()->json(['success' => true, 'data' => $citiesList]);
            }
        } catch (\Exception $e) {
            Log::error('Municipal pipeline extraction failure: ' . $e->getMessage());
        }

        return response()->json(['success' => false, 'message' => 'Unable to pull regional data channels.'], 500);
    }

    /**
     * Process inbound dealer registration form arrays natively.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fullName'             => ['required', 'string', 'max:255'],
            'phoneNo'              => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'emailAddr'            => ['nullable', 'email', 'max:255'],
            'webUrl'               => ['nullable', 'url', 'max:255'],
            'stateCode'            => ['required', 'string'],
            'cityName'             => ['required', 'string'],
            'selected_plan'        => ['required', 'string', 'in:free,pkg2,pkg3'],
        ], [
            'phoneNo.regex' => 'Please provide a valid 10-digit primary mobile phone number contact record.'
        ]);

        try {
            // 1. Build company fallback email strings if missing
            $email = $request->input('emailAddr');
            if (empty($email)) {
                $cleanName = strtolower(preg_replace('/[^a-z0-9]/', '', $request->input('fullName')));
                $email = $cleanName . '@autoos360.com';
            }

            // 2. Resolve plan tokens to database key integer values
            $planMappingId = match($request->input('selected_plan')) {
                'pkg2'  => 2,
                'pkg3'  => 3,
                default => 1,
            };

            // 3. Automated collision-proof slug layout string generation
            $baseSlug = Str::slug($request->input('fullName'));
            $finalSlug = $baseSlug;
            $counter = 1;
            while (Tenant::where('slug', $finalSlug)->exists()) {
                $finalSlug = $baseSlug . '-' . $counter;
                $counter++;
            }

            // 4. Commit tenant registration records into storage tables
            $tenant = Tenant::create([
                'uuid'                 => (string) Str::uuid(),
                'name'                 => $request->input('fullName'),
                'slug'                 => $finalSlug,
                'email'                => $email,
                'phone'                => $request->input('phoneNo'),
                'website'              => $request->input('webUrl'),
                'country'              => 'India',
                'state'                => $request->input('stateNameHidden'), // Passed dynamically via JS on selection
                'city'                 => $request->input('cityName'),
                'subscription_plan_id' => $planMappingId,
                'status'               => 'inactive',
                'timezone'             => 'Asia/Kolkata',
                'currency'             => 'INR',
            ]);

            return redirect()->back()->with('success', "Congratulations! Dealer portal registered successfully. Your portal slug identifier is: {$tenant->slug}");

        } catch (\Exception $e) {
            Log::error('Tenant storage pipeline breakdown: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Internal server error occurred while handling registration profiles.');
        }
    }
}
