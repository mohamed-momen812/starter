<?php

namespace App\Services;

use App\Traits\ApiTrait;
use Illuminate\Support\Facades\Http;

class CountryCityService
{

    use ApiTrait;

    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://restcountries.com/v3.1';
    }

    /**
     * Fetch all countries.
     */
    public function fetchCountries()
    {
        $response = Http::get("{$this->baseUrl}/all");

        if ($response->failed()) {
            return $this->responseJsonFailed('Failed to fetch countries');
        }

        return collect($response->json())->map(function ($country) {
            return [
                'code' => $country['cca2'],
                'name' => $country['name']['common'],
            ];
        });
    }

    /**
     * Fetch cities for a specific country (example uses GeoNames API).
     */
    public function fetchCities($countryCode)
    {
        $response = Http::get("http://api.geonames.org/searchJSON", [
            'country' => $countryCode,
            'featureClass' => 'P', // Indicates populated places
            'maxRows' => 50,
            'username' => env('GEONAMES_USERNAME'), // API Key
        ]);

        if ($response->failed()) {
            return $this->responseJsonFailed('Failed to fetch cities');
        }

        return collect($response->json()['geonames'])->map(fn($city) => $city['name']);
    }
}
