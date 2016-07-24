<?php
namespace App\Components\Business;

use App\Components\Traits\HelperTrait;
use App\Models\Car;
use App\Models\Spot;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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
     * @returns array
     */
    public static function getCars(array $arr = [])
    {
        // Predefine variables
        $cars = [];

        $q = Car::orderBy('plate_nr');

        if (count($arr) > 0) {
            $q->whereIn('tracker.imei', $arr);
        }
        $carsRaw = $q->get();

        foreach ($carsRaw as $car) {
            $imei = $car->tracker['imei'];

            if (array_key_exists($imei, $cars)) {
                continue;
            }
            $cars[$imei] = $car;
        }
        return $cars;
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

        foreach ($cars as $imei => $car) {
            // Reset variables
            $distance = 0;
            $oldLat = null;
            $oldLong = null;
            $failedSpots = 0;

            // Prevent from calculating already processed car
            if (array_key_exists($imei, $arr['cars'])) {
                continue;
            }

            /** @var Spot[] $spots */
            $spots = $car->getSpots($dateFrom, $dateTo);

            foreach ($spots as $spot) {

                if (!isset($spot[0]) || !isset($spot[1])) {
                    continue;
                }

                $lat = (float)$spot[0];
                $long = (float)$spot[1];

                if (!is_numeric($lat) || !is_numeric($long) || $lat == 0 || $long == 0) {
                    continue;
                }

                if (!isset($oldLat) || !isset($oldLong)) {
                    $oldLat = $lat;
                    $oldLong = $long;
                    continue;
                }

                $calcDistance = self::calcDistanceBetweenCoordinates($oldLat, $oldLong, $lat, $long);

                if (is_nan($calcDistance)) {
                    $failedSpots++;
                    continue;
                }
                $distance += $calcDistance;

                $oldLat = $lat;
                $oldLong = $long;
            }

            $totalSpots = count($spots);

            $arr['cars'][$imei] = [
                'processed_spots' => $totalSpots,
                'failed_spots' => $failedSpots,
                'distance_calc_accuracy_percentage' => self::calcAccuracy($totalSpots, $failedSpots),
                'distance' => $distance
            ];

            $arr['totalDistance'] += $distance;
        }
        return $arr;
    }

    /**
     * @param int $total
     * @param int $failed
     * @return float
     */
    private static function calcAccuracy($total, $failed)
    {
        return $failed > 0 ? round(100 - ($failed * 100 / $total), 2) : 100;
    }
}