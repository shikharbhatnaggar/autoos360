<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Compile homepage content with fully pre-calculated parameters.
     */
    public function index()
    {
        // 1. ASYNC BODY TYPE BLOCK: Fetch real live available totals
        $rawBodyCounts = Vehicle::select('body_type', DB::raw('count(*) as total'))
            ->where('status', 'in_stock')
            ->groupBy('body_type')
            ->get();

        $countsMap = $rawBodyCounts->pluck('total', 'body_type')->toArray();
        $totalAllCars = array_sum($countsMap);

        $bodyTypes = [
            ['name' => 'Hatchback',       'art' => 'Hatchback',       'href' => url('/listing?view=all&body=Hatchback'), 'n' => $countsMap['Hatchback'] ?? 0],
            ['name' => 'Sedan',           'art' => 'Sedan',           'href' => url('/listing?view=all&body=Sedan'),     'n' => $countsMap['Sedan'] ?? 0],
            ['name' => 'SUV',             'art' => 'SUV',             'href' => url('/listing?view=all&body=SUV'),       'n' => $countsMap['SUV'] ?? 0],
            ['name' => 'Mini commercial', 'art' => 'Mini Commercial', 'href' => url('/listing?view=all&section=commercial'),         'n' => $countsMap['MUV'] ?? 0],
            ['name' => 'Electric',        'art' => 'Electric',        'href' => url('/listing?view=all&fuel=Electric'),  'n' => $countsMap['Electric'] ?? 0],
            ['name' => 'All cars',        'art' => 'All',             'href' => url('/listing?view=all'),                'n' => $totalAllCars]
        ];

        // 2. SECURED FEED CAROUSELS: Pre-hydrate net rates from active purchase sheets
        $featured = $this->hydrateVehiclesList(Vehicle::where('is_featured', 1)->where('status', 'in_stock')->take(10)->get());
        $newArrivals = $this->hydrateVehiclesList(Vehicle::where('is_new_arrival', 1)->where('status', 'in_stock')->take(10)->get());
        $commercial = $this->hydrateVehiclesList(Vehicle::where('is_commercial', 1)->where('status', 'in_stock')->take(10)->get());

        // 3. PROMOTIONAL HERO BANNERS ASSIGNMENTS: Match your legacy logic defaults
        $cheapestCar = Vehicle::with(['purchase'])->where('status', 'in_stock')->get()->sortBy(function($car) {
            return $car->purchase ? $car->purchase->net_rate : 450000;
        })->first();

        $heroEmi = 13000; // Standby monthly fallback
        if ($cheapestCar && $cheapestCar->purchase) {
            // Programmatic benchmark approximation formula mimicking legacy metrics scripts
            $heroEmi = round((($cheapestCar->purchase->net_rate) * 0.15) / 12); 
        }

        return view('frontend.index', compact('bodyTypes', 'featured', 'newArrivals', 'commercial', 'totalAllCars', 'heroEmi'));
    }

    /**
     * Map image string arrays and financial rates onto collection rows.
     */
    private function hydrateVehiclesList($collection)
    {
        return $collection->map(function ($car) {
            // Handle native purchase constraints
            $car->price = $car->purchase ? (float) $car->purchase->net_rate : 450000.00;
            
            // Extract pristine SEO clean target parameter layout links matching your .htaccess rule
            $cleanSlug = strtolower(trim(preg_replace('/[^a-z0-9\s-]/', '', $car->model)));
            // $cleanSlug = preg_replace('/\s+/', '-', $cleanSlug);
            // $car->seo_url = url("/vehicle/{$cleanSlug}-{$car->id}");
            $cleanSlug = Str::slug($car->model); 
            $car->seo_url = url("/vehicle/{$cleanSlug}-{$car->id}");

            // Compile dynamic storage pathways out of local assets column strings array maps
            $images = $car->vehicle_images ?? [];
            if (is_string($images)) {
                $images = json_decode($images, true) ?? [];
            }
            $primaryImg = collect($images)->firstWhere('is_primary', true) ?? collect($images)->first();
            // $car->display_image = $primaryImg ? url('/storage/' . ($primaryImg['path'] ?? $primaryImg)) : url('/frontend/images/placeholder-car.jpg');
            $car->display_image = 'https://shikharbhatnaggar.github.io/publicautoos360/'.$primaryImg['path'];
            return $car;
        });
    }


    public function listing(Request $request)
    {
        // 1. Core Base Context Views Setup (Preserving listing.js legacy specs)
        $view = $request->get('view', 'all');
        $views = [
            'all'        => ['title' => 'All cars', 'sub' => 'Every inspected vehicle currently in stock.'],
            'new'        => ['title' => 'New arrivals', 'sub' => 'Freshly inspected this fortnight — these move quickly.'],
            'featured'   => ['title' => 'Featured cars', 'sub' => 'Low-owner, low-kilometre picks our inspectors rated highest.'],
            'commercial' => ['title' => 'Mini commercial vehicles', 'sub' => 'Seven-seat MUVs built for fleet duty and long-route work.']
        ];
        $cfg = $views[$view] ?? $views['all'];

        // 2. Global Pool Boundary Calculations using the correct vehicle_purchases relationship table
        $globalMinPrice = (float) DB::table('vehicle_purchases')->min('net_rate') ?: 100000;
        $globalMaxPrice = (float) DB::table('vehicle_purchases')->max('net_rate') ?: 2000000;
        
        // Round global limits cleanly by 25k increments matching your original JS engine script
        $priceFloor = floor($globalMinPrice / 25000) * 25000;
        $priceCeil = ceil($globalMaxPrice / 25000) * 25000;

        // 3. Dynamic Filtering Builder Instance using the internal scope helper
        // $query = Vehicle::with(['purchase'])->inStock();
        $query = Vehicle::with(['purchase', 'tenant'])->inStock();

        // Apply Global View Constraints
        if ($view === 'new') {
            $query->where('is_new_arrival', 1);
        } elseif ($view === 'featured') {
            $query->where('is_featured', 1);
        } elseif ($view === 'commercial') {
            $query->where(function($q) {
                $q->where('body_type', 'MUV')->orWhere('is_commercial', 1);
            });
        }

        // Apply Client Component Form Inputs
        if ($request->filled('city')) {
            $query->whereHas('tenant', function($q) use ($request) {
                $q->whereIn('city', (array) $request->input('city'));
            });
        }    
        if ($request->filled('body')) {
            $query->whereIn('body_type', (array) $request->input('body'));
        }
        if ($request->filled('fuel')) {
            $query->whereIn('fuel_type', (array) $request->input('fuel'));
        }
        if ($request->filled('transmission')) {
            $query->whereIn('transmission', (array) $request->input('transmission'));
        }
        if ($request->filled('ownership')) {
            $query->whereIn('ownership', (array) $request->input('ownership'));
        }
        if ($request->filled('year')) {
            $query->whereIn('make_year', (array) $request->input('year'));
        }
        
        // Corrected relationship subquery targeting the vehicle_purchases relation scheme
        $maxPriceInput = $request->get('max_price', $priceCeil);
        $query->whereHas('purchase', function($q) use ($maxPriceInput) {
            $q->where('net_rate', '<=', $maxPriceInput);
        });
        
        // 4. Extraction of Dynamic Option Multi-Counts for Active Checkboxes
        $currentStockPool = $query->get()->map(function($car) {
            // 🌟 FIXED: Added index [0] to extract a flat string instead of an array object
            $car->brand = explode(' ', $car->model)[0] ?? 'Other';
            $car->price = $car->purchase ? (float) $car->purchase->net_rate : 450000;
            return $car;
        });
        // dd($currentStockPool->pluck('tenant'));
        // Apply brand text constraints onto the count generator pool layout array
        if ($request->filled('make')) {
            $currentStockPool = $currentStockPool->filter(function($car) use ($request) {
                return in_array($car->brand, (array) $request->input('make'));
            });
            
            // Also apply the brand search array filter to the main execution builder query
            $query->where(function($q) use ($request) {
                foreach ((array) $request->input('make') as $brandItem) {
                    $q->orWhere('model', 'LIKE', $brandItem . '%');
                }
            });
        }

        // 🌟 ROBUST CITY EXTRACTION VIA EXPLICIT MAP EXTRACTION CLOSURES
        // This safely checks the loaded array relation data and skips dot-notation dependency errors
        $cityCounts = $currentStockPool->map(function($car) {
            if ($car->tenant) {
                // If it behaves like an array, handle it safely; otherwise use object property lookup
                return is_array($car->tenant) ? ($car->tenant['city'] ?? null) : $car->tenant->city;
            }
            return null;
        })
        ->filter()             // Clear empty/null records from our list tracking grid
        ->map(function($city) {
            return trim($city); // Standardize string spacing layout
        })
        ->countBy()            // Accumulate unique counts match parameters
        ->sortKeys();          // Order array alphabetically from A-Z

        $filterCounts = [
            'city'         => $cityCounts, // Injecting verified counts list seamlessly
            'body'         => $currentStockPool->pluck('body_type')->countBy(),
            'brand'        => $currentStockPool->pluck('brand')->countBy(),
            'make_year'    => $currentStockPool->pluck('make_year')->countBy()->sortKeysDesc(),
            'fuel_type'    => $currentStockPool->pluck('fuel_type')->countBy(),
            'transmission' => $currentStockPool->pluck('transmission')->countBy(),
            'ownership'    => $currentStockPool->pluck('ownership')->countBy(),
        ];
        
        // 5. Corrected Server-Side SORTS Matrix Mapping utilizing vehicle_purchases 
        $sort = $request->get('sort', 'new');
        switch ($sort) {
            case 'price-asc':
                // Safe subquery join to order by external rates without corrupting Eloquent fields
                $query->orderBy(
                    DB::table('vehicle_purchases')
                        ->select('net_rate')
                        ->whereColumn('vehicle_purchases.vehicle_id', 'vehicles.id')
                        ->limit(1), 
                    'asc'
                );
                break;
            case 'price-desc':
                $query->orderBy(
                    DB::table('vehicle_purchases')
                        ->select('net_rate')
                        ->whereColumn('vehicle_purchases.vehicle_id', 'vehicles.id')
                        ->limit(1), 
                    'desc'
                );
                break;
            case 'km-asc':
                $query->orderBy('km_driven', 'asc');
                break;
            case 'year-desc':
                $query->orderBy('make_year', 'desc')->orderBy('km_driven', 'asc');
                break;
            case 'new':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // 6. Native Server Pagination (Replaces client-side "load more" splits)
        $paginator = $query->paginate(9)->withQueryString();

        // Hydrate paginated collections with pricing context, image pathways, and SEO slugs
        $vehicles = $paginator->setCollection(
            $this->hydrateVehiclesList($paginator->getCollection())
        );
        
        return view('frontend.listing', compact(
            'cfg', 'view', 'vehicles', 'sort', 'filterCounts', 
            'priceFloor', 'priceCeil', 'maxPriceInput'
        ));
    }


        /**
     * Server-Side Rendered (SSR) Vehicle Showroom Details Pipeline.
     */
    public function showSingle($slug, $id)
    {
        // 1. Fetch live stock matching the unique identifier pattern
        $vehicle = Vehicle::with(['purchase'])->where('status', 'in_stock')->find($id);

        if (!$vehicle) {
            return response()->view('frontend.errors.vehicle-not-found', [], 404);
        }

        // 2. Pre-hydrate financial net rates data signatures
        $vehicle->price = $vehicle->purchase ? (float) $vehicle->purchase->net_rate : 545000.00;
        
        // Match legacy program emi calculation matrix logic from index file
        $indicativeEmi = round(($vehicle->price * 0.15) / 12);
        if ($indicativeEmi < 13000) {
            $indicativeEmi = 13000; // Standby monthly floor threshold limit
        }

        // 3. Format measurement values cleanly on the server layer
        $vehicle->formatted_km = number_format($vehicle->km_driven) . ' km';
        $vehicle->formatted_price = '₹' . number_format($vehicle->price);

        // 4. Decode metadata columns and unpack local asset configurations
        $images = $vehicle->vehicle_images ?? [];
        if (is_string($images)) {
            $images = json_decode($images, true) ?? [];
        }
        
        $vehicle->images_collection = collect($images)->map(function ($img) {
            return [
                'path' => 'https://shikharbhatnaggar.github.io/publicautoos360/'.$img['path'], //url('/storage/' . ($img['path'] ?? $img)),
                'type' => ucfirst($img['type'] ?? 'General'),
                'is_primary' => !empty($img['is_primary'])
            ];
        });

        $vehicle->primary_image_path = optional($vehicle->images_collection->firstWhere('is_primary', true))['path'] 
            ?? optional($vehicle->images_collection->first())['path'] 
            ?? url('/frontend/images/placeholder-car.jpg');

        // 5. Setup context mapping structures for navigation tracks
        $crumbList = [
            'text' => ($vehicle->body_type === 'MUV') ? 'Mini commercial' : $vehicle->body_type . 's',
            'href' => ($vehicle->body_type === 'MUV') ? url('/listing/commercial') : url('/listing/all?body[]=' . urlencode($vehicle->body_type))
        ];

        return view('frontend.showsingle', compact('vehicle', 'indicativeEmi', 'crumbList'));
    }


        /**
     * Display the SSR Test Drive Request form with pre-loaded car context.
     */
    public function bookTestDrive(Request $request)
    {
        $vehicleId = $request->get('id');

        if (!$vehicleId) {
            return redirect()->route('frontend.vehicles.listing')->with('error', 'Invalid access parameters.');
        }

        // Fetch live vehicle data mapping relationships
        $v = Vehicle::with(['purchase'])->where('status', 'in_stock')->find($vehicleId);

        if (!$v) {
            return redirect()->route('frontend.vehicles.listing')->with('error', 'Vehicle is no longer available.');
        }

        // Hydrate data fields matching single vehicle layout models logic
        $v->price = $v->purchase ? (float) $v->purchase->net_rate : 545000.00;
        
        $cleanSlug = strtolower(trim(preg_replace('/[^a-z0-9\s-]/', '', $v->model)));
        $cleanSlug = preg_replace('/\s+/', '-', $cleanSlug);
        $v->seo_url = url("/vehicle/{$cleanSlug}-{$v->id}");

        $images = $v->vehicle_images ?? [];
        if (is_string($images)) {
            $images = json_decode($images, true) ?? [];
        }
        $primaryImg = collect($images)->firstWhere('is_primary', true) ?? collect($images)->first();
        // $v->display_image = $primaryImg ? url('/storage/' . ($primaryImg['path'] ?? $primaryImg)) : url('/frontend/images/placeholder-car.jpg');
        $v->display_image = 'https://shikharbhatnaggar.github.io/publicautoos360/'.$primaryImg['path'];

        return view('frontend.book-test-drive', compact('v'));
    }

    /**
     * Process inbound form lead entry submissions with secure validation.
     */
    public function submitTestDrive(Request $request)
    {
        $request->validate([
            'vehicle_id'  => ['required', 'integer', 'exists:vehicles,id'],
            'fullName'    => ['required', 'string', 'max:255'],
            'mobileNo'    => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'emailAddr'   => ['nullable', 'email', 'max:255'],
            'buyerMsg'    => ['nullable', 'string', 'max:1000']
        ], [
            'mobileNo.regex' => 'Please enter a valid 10-digit mobile number.'
        ]);

        try {
            $vehicle = Vehicle::findOrFail($request->input('vehicle_id'));
            
            // Build dynamic SEO URL structure on the backend layer safely
            $cleanSlug = strtolower(trim(preg_replace('/[^a-z0-9\s-]/', '', $vehicle->model)));
            $cleanSlug = preg_replace('/\s+/', '-', $cleanSlug);
            $generatedVehicleUrl = url("/vehicle/{$cleanSlug}-{$vehicle->id}");

            \App\Models\Lead::create([
                'tenant_id'   => $vehicle->tenant_id,
                'vehicle_id'  => $vehicle->id,
                'name'        => $request->input('fullName'),
                'mobile'      => $request->input('mobileNo'),
                'email'       => $request->input('emailAddr'),
                'vehicle_url' => $generatedVehicleUrl,
                'message'     => $request->input('buyerMsg'),
                'status'      => 'new'
            ]);

            return redirect()->back()->with('success', 'Test drive requested successfully! The dealer will contact you shortly.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An error occurred while processing your request.');
        }
    }
    
    public function vehicleInShowroom(Request $request, $slug, $id)
    {
        // 1. Fetch the single target vehicle with its multi-tenant profile mappings
        $vehicle = Vehicle::with(['purchase', 'tenant'])->findOrFail($id);

        // 2. Perform SEO routing parameter slug validation checks
        $expectedSlug = Str::slug($vehicle->model);
        if ($slug !== $expectedSlug) {
            return redirect()->route('frontend.showroom.scan', ['slug' => $expectedSlug, 'id' => $vehicle->id]);
        }

        // 3. Process the entire vehicle gallery array matrix instead of truncating it
        $rawImages = collect($vehicle->vehicle_images);
        
        // Sort array elements to prioritize your designated primary photo first
        $sortedImages = $rawImages->sortByDesc(function ($img) {
            return is_array($img) ? ($img['is_primary'] ?? false) : ($img->is_primary ?? false);
        });

        $sliderImagesList = [];
        foreach ($sortedImages as $img) {
            $path = is_array($img) ? ($img['path'] ?? '') : ($img->path ?? '');
            if (!empty($path)) {
                // $sliderImagesList[] = asset('storage/' . $path);
                $sliderImagesList[] = 'https://shikharbhatnaggar.github.io/publicautoos360/'.$path;
            }
        }

        // Fallback gracefully if the image array column is empty
        if (empty($sliderImagesList)) {
            $sliderImagesList[] = asset('images/default-car.jpg');
        }

        // 4. Bind parsed string properties safely back to the parent model entity instance
        $vehicle->slider_images = $sliderImagesList;
        $vehicle->display_price = $vehicle->purchase 
            ? '₹' . number_format($vehicle->purchase->net_rate) 
            : 'Contact Dealer';

        return view('frontend.vehicle-in-showroom', compact('vehicle'));
    }

}
