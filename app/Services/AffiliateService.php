<?php
namespace App\Services;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class AffiliateService
{
    /**
     * Get the matching affiliates within 100km of the given GPS coordinates.
     *
     * @param float $lat Latitude of the reference point.
     * @param float $lng Longitude of the reference point.
     * @return \Illuminate\Support\Collection Collection of matching affiliates.
     */
    public function getMatchingAffiliates($filePath)
    {
        // Read the contents of affiliates.txt
        $affiliates = file($filePath, FILE_IGNORE_NEW_LINES);

        if ($affiliates === false) {
            throw new \RuntimeException("Error reading affiliates.txt file.");
        }

        // Dublin office GPS coordinates
        $dublinLat = config('constants.DUBLIN_LAT');
        $dublinLng = config('constants.DUBLIN_LAG');

        // Array to store matching affiliates
        $matchingAffiliates = [];
        $index = 0;

        foreach ($affiliates as $affiliateData) {
            try {
                // Parse each line of the file as JSON data
                $affiliate = json_decode($affiliateData, true);

                if (!is_array($affiliate)) {
                    throw new \RuntimeException("Invalid JSON data");
                }

                // Check if the required fields are present in the JSON data
                if (!isset($affiliate['affiliate_id'], $affiliate['name'], $affiliate['latitude'], $affiliate['longitude'])) {
                    throw new \RuntimeException("Missing required fields in JSON data");
                }

                // Calculate the distance between Dublin and the affiliate using the Haversine formula
                $distance = haversineDistance($dublinLat, $dublinLng, $affiliate['latitude'], $affiliate['longitude']);

                // Check if the distance is within 100km
                if ($distance <= 100) {
                    $matchingAffiliates[$index]['affiliate_id'] = $affiliate['affiliate_id'];
                    $matchingAffiliates[$index]['name'] = $affiliate['name'];
                    $matchingAffiliates[$index]['latitude'] = $affiliate['latitude'];
                    $matchingAffiliates[$index]['longitude'] = $affiliate['longitude'];
                    $matchingAffiliates[$index]['distance'] = $distance;
                    $index++;
                }
            } catch (\Exception $e) {
                // Log any exceptions encountered while processing the JSON data
                \Log::error("Exception while processing JSON data: {$e->getMessage()}");
            }
        }

        // Sort matching affiliates by ID (ascending)
        if (count($matchingAffiliates) > 0) {
            $key_values = array_column($matchingAffiliates, 'affiliate_id');
            array_multisort($key_values, SORT_ASC, $matchingAffiliates);

            // Convert the array to a collection
            $collection = collect($matchingAffiliates);

            $currentPage = Paginator::resolveCurrentPage();
            $perPage = config('constants.PER_PAGE');
            $matchingAffiliates = new LengthAwarePaginator(
                $collection->forPage($currentPage, $perPage),
                $collection->count(),
                $perPage,
                $currentPage,
                ['path' => Paginator::resolveCurrentPath()]
            );
        }

        return $matchingAffiliates;
    }
}
