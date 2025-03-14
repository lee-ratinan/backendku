<?php

/**
 * @param float $lat1
 * @param float $lon1
 * @param float $lat2
 * @param float $lon2
 * @param int $earthRadius
 * @return float|int
 */
function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2, int $earthRadius = 6371): float|int
{
    // Convert degrees to radians
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);
    // Haversine formula
    $deltaLat = $lat2 - $lat1;
    $deltaLon = $lon2 - $lon1;
    $a = sin($deltaLat / 2) ** 2 +
        cos($lat1) * cos($lat2) * sin($deltaLon / 2) ** 2;
    $c = 2 * asin(sqrt($a));
    // Distance in the specified unit (default is kilometers)
    return $earthRadius * $c;
}

/**
 * @param float $km
 * @return float
 */
function kmToMiles(float $km): float
{
    return $km / 1.609344;
}