<?php

if (!function_exists('haversineDistance')) {
    /**
     * Calculate the Haversine distance between two sets of coordinates in kilometers.
     *
     * @param float $lat1 Latitude of the first point.
     * @param float $lng1 Longitude of the first point.
     * @param float $lat2 Latitude of the second point.
     * @param float $lng2 Longitude of the second point.
     * @return float Distance in kilometers.
     */
    function haversineDistance($lat1, $lng1, $lat2, $lng2)
    {
        // Earth's radius in kilometers
        $earthRadius = 6371;

        // Convert degrees to radians
        $lat1Rad = deg2rad($lat1);
        $lng1Rad = deg2rad($lng1);
        $lat2Rad = deg2rad($lat2);
        $lng2Rad = deg2rad($lng2);

        // Calculate the differences between the latitudes and longitudes
        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLng = $lng2Rad - $lng1Rad;

        // Calculate the distance using the Haversine formula
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($deltaLng / 2) * sin($deltaLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }
}
