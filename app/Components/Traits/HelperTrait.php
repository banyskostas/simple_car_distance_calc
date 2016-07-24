<?php
namespace App\Components\Traits;
use DateTime;
use Exception;

/**
 * Class HelperTrait
 * @package App\Components\Traits
 */
trait HelperTrait
{
    /**
     * Units:
     * K - Kilometers,
     * N - Nautical Miles,
     * M - Miles
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @param string $unit
     * @return float
     * @throws Exception
     */
    public static function calcDistanceBetweenCoordinates($lat1, $lon1, $lat2, $lon2, $unit = 'K')
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        switch ($unit) {
            case "K":
                $float = ($miles * 1.609344);
                break;
            case "N":
                $float = ($miles * 0.8684);
                break;
            case "M":
                $float = $miles;
                break;
            default:
                throw new Exception("There's no matching unit");
        }
        return $float;
    }

    /**
     * @param $date
     * @param string $format
     * @return bool
     */
    public static function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}