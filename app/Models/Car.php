<?php
namespace App\Models;

use DateTime;
use Exception;
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
     * @return Collection|
     */
    public function getSpots($timeFrom = null, $timeTo = null)
    {
        $imei = $this->tracker['imei'];

        if (!isset($imei)) {
            return new Collection();
        }

        $q = Spot::select('loc')->where('imei', '=', $imei)->orderBy('time');

        if (isset($timeFrom)) {
            $q->where('time', '>=', new DateTime($timeFrom));
        }

        if (isset($timeTo)) {
            $q->where('time', '<=', new DateTime($timeTo));
        }

        /**
         * #NOTE eloquent chunk method is working slower than taking all the filtered data from mongoDB. One request
         * lasts ~200ms and it doesn't matter if we take 100 or 10 000 data rows, that's why we use simple Laravel
         * eloquent lists() method.
         */
        try {
            return $q->lists('loc');
        } catch (Exception $e) {
            /**
             * On operation failed because of too many data to take from MongoDB (lack of RAM) We try to use different
             * approach: chunk method, it's slower but works with data set exceeding allowed memory for MongoDB.
             */
            $arr = [];
            $q->chunk(5000, function ($spots) use (&$arr) {
                foreach ($spots as $spot) {
                    $arr[] = $spot->loc;
                }
            });
            return $arr;
        }
    }
}