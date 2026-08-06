<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct(protected ReportService $reportService) {}

    public function profitLoss(Request $request)
    {
        $data = $this->reportService->profitLoss(
            Auth::user(),
            $request->branch_id,
            $request->from,
            $request->to
        );

        return view('reports.profit_loss', $data);
    }

    public function stock(Request $request)
    {
        $data = $this->reportService->stockInHand(Auth::user(), $request->branch_id);

        return view('reports.stock', $data);
    }

    public function brokerCommissions(Request $request)
    {
        $data = $this->reportService->brokerCommissions(Auth::user(), $request->from, $request->to);

        return view('reports.broker_commissions', ['brokerData' => $data]);
    }
}
