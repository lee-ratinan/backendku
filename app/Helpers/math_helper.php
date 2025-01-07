<?php

/**
 * @param $lat1
 * @param $lon1
 * @param $lat2
 * @param $lon2
 * @param $earthRadius
 * @return float|int
 */
function calculateDistance($lat1, $lon1, $lat2, $lon2, $earthRadius = 6371) {
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