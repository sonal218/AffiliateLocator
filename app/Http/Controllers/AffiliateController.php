<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AffiliateService;

class AffiliateController extends Controller
{
    protected $affiliateService;

    public function __construct(AffiliateService $affiliateService)
    {
        $this->affiliateService = $affiliateService;
    }

    public function index()
    {
        // Get the matching affiliates within 100km of the Dublin office
        $filePath = storage_path('app/affiliates.txt');
        $matchingAffiliates = $this->affiliateService->getMatchingAffiliates($filePath);
        return view('affiliates.index', compact('matchingAffiliates'));
    }
}
