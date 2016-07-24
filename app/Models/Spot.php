<?php
namespace App\Models;

use DateTime;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;

/**
 * Class Spot
 * @package Models
 *
 * @property int _id
 * @property array loc (TODO There seems to be old stuff or something so we need to handle different columns "lat", etc... too)
 * @property int alt
 * @property float speed
 * @property int power
 * @property string address
 * @property float course
 * @property bool valid
 * @property DateTime time (Saved as UTCDateTime (MongoDB date) on DB) (TODO also has different data type on some data)
 * @property string other
 * @property string imei
 *
 */
class Spot extends Moloquent
{
    protected $collection = 'spot';
    protected $dates = ['time'];
}