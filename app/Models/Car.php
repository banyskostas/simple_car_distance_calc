<?php
namespace App\Models;

use App\Components\Traits\CarTrait;
use App\Components\Traits\HelperTrait;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;

/**
 * Class Spot
 * @package Models
 *
 * @property int _id
 * @property array tracker
 * @property string plate_nr
 * @property int odometer
 * @property string car_type_id
 * @property int fuelType
 * @property int fuelConsumption
 * @property int max_speed
 * @property int max_idle
 * @property datetime updated_at
 * @property datetime created_at
 * @property array user_ids
 * @property array driver_ids
 * @property string current_driver
 * @property int model
 * @property array in_pois
 * @property string rf_id_authorization_type
 */
class Car extends Moloquent
{
    protected $collection = 'car';

    /**
     * #Note simple hasMany relation is not possible because unique car number is IMEI and it's in array on attribute
     * "tracker" and "tracker.imei" doesn't work as local_key on hasMany function
     *
     * @param string|null $timeFrom
     * @param string|null $timeTo
     * @return Collection
     */
    public function getSpots($timeFrom = null, $timeTo = null)
    {
        $collection = new Collection();
        $imei = $this->tracker['imei'];

        if (!isset($imei)) {
            return $collection;
        }

        $q = Spot::select('imei', 'loc')->where('imei', '=', $imei)->orderBy('time');

        if (isset($timeFrom)) {
            $q->where('time', '>=', new DateTime($timeFrom));
        }

        if (isset($timeTo)) {
            $q->where('time', '<=', new DateTime($timeTo));
        }

        // Reset variables
        $distance = 0;
        $oldLat = null;
        $oldLong = null;

        $q->chunk(10000, function ($spots) use (&$distance, &$oldLat, &$oldLong) {

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
                $distance += HelperTrait::calcDistanceBetweenCoordinates($oldLat, $oldLong, $spot->loc[0], $spot->loc[1]);
//                $distance++;
                $oldLat = $lat;
                $oldLong = $long;
            }
        });
dd($distance);
        return $collection;
    }
}