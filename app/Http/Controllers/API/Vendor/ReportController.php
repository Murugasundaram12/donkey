<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Get Vendor Analytics & Reports (Delegates to EarningsReportController)
     */
    public function index(Request $request)
    {
        return (new EarningsReportController())->index($request);
    }
}
