<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CountryCityService;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class CountryCityController extends Controller
{
    use ApiTrait;

    private $countryCityService;

    public function __construct(CountryCityService $countryCityService)
    {
        $this->countryCityService = $countryCityService;
    }

    /**
     * Get a list of countries.
     */
    public function getCountries()
    {
        try {
            $countries = $this->countryCityService->fetchCountries();
            return response()->json($countries);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get a list of cities for a given country code.
     */
    public function getCities(Request $request, $countryCode)
    {
        try {
            $cities = $this->countryCityService->fetchCities($countryCode);
            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
