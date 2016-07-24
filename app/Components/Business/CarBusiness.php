<?php
namespace App\Components\Business;

use App\Components\Traits\HelperTrait;
use App\Models\Car;
use App\Models\Spot;
use Exception;

/**
 * Class CarBusiness
 * @package App\Components\Traits
 */
class CarBusiness
{
    use HelperTrait;

    /**
     * Get cars and filter only the cars needed if
     * @param array $arr
     */
    public static function getCars(array $arr = [])
    {
        $q = Car::orderBy('plate_nr');

        if (count($arr) > 0) {
            $q->whereIn('tracker.imei', $arr);
        }
        return $q->get();
    }

    /**
     * @param array $carsArr
     * @param string $dateFrom
     * @param string $dateTo
     * @return array
     * @throws Exception;
     */
    public static function calcCarsTotalDistance(array $carsArr, $dateFrom, $dateTo)
    {
        // Predefined variables
        $arr = [
            'cars' => [],
            'totalDistance' => 0
        ];

        /** @var Car[] $cars */
        $cars = self::getCars($carsArr);

        if (!$cars) {
            return $arr;
        }

        foreach ($cars as $car) {
            // Reset variables
            $distance = 0;
            $oldLat = null;
            $oldLong = null;

            /** @var Spot[] $spots */
            $spots = $car->getSpots($dateFrom, $dateTo);

            foreach ($spots as $spot) {
                if (!isset($spot->loc) || !isset($spot->loc[0]) || !isset($spot->loc[1])) {
                    continue;
                }

                $lat = $spot->loc[0];
                $long = $spot->loc[1];

                if (!isset($oldLat) || !isset($oldLong)) {
                    $oldLat = $lat;
                    $oldLong = $long;
                    continue;
                }
                $distance += self::calcDistanceBetweenCoordinates($oldLat, $oldLong, $spot->loc[0], $spot->loc[1]);

                $oldLat = $lat;
                $oldLong = $long;
            }

            $arr['cars'][$car->_id] = [
//                'spots' => $spots->toArray(),
                'distance' => $distance
            ];

            $arr['totalDistance'] += $distance;
        }
        return $arr;
    }
}