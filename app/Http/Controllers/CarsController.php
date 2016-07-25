<?php
namespace App\Http\Controllers;

use App\Components\Exceptions\ValidationException;
use App\Components\Business\CarBusiness;
use App\Components\ExtendedClasses\ExException;
use App\Components\Traits\HelperTrait;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Psy\Util\Json;

/**
 * Class CarsController
 * @package App\Http\Controllers
 */
class CarsController extends Controller
{
    /**
     * @return \App\Models\Car[] (json)
     */
    public function getCarsList()
    {
        try {
            $cars = CarBusiness::getCars();
            return Response::json(['success' => true, 'data' => $cars]);

        } catch (Exception $e) {
            Log::error(ExException::generateMsg($e));
            return Response::json(['success' => false, 'msg' => ExException::UNSUCCESSFULL_REQUEST_MSG]);
        }
    }

    /**
     * Calculate one or more cars drive distance in the given date range
     * @param Request $request
     * @param string $dateFrom
     * @param string $dateTo
     * @return json
     */
    public function calcCarsTotalDistance(Request $request, $dateFrom, $dateTo)
    {
        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'cars' => 'required|json',
            ]);

            if ($validator->fails()) {
                throw new ValidationException(implode(' ', $validator->errors()->all()));
            }

            if (!HelperTrait::validateDate($dateFrom)) {
                throw new ValidationException('DateFrom is not a valid date');
            }

            if (!HelperTrait::validateDate($dateTo)) {
                throw new ValidationException('DateTo is not a valid date');
            }

            // Prepare data
            $cars = json_decode($request->input('cars'));

            $data = CarBusiness::calcCarsTotalDistance($cars, $dateFrom, $dateTo);

            if (!$data) {
                throw new exception("Car/cars total distance couldn't be calculated.");
            }
            return Response::json(['success' => true, 'data' => $data]);

        } catch (ValidationException $e) {
            return Response::json(['success' => false, 'msg' => $e->__toString()]);

        } catch (Exception $e) {
            Log::error(ExException::generateMsg($e));
            return Response::json(['success' => false, 'msg' => ExException::UNSUCCESSFULL_REQUEST_MSG]);
        }
    }
}
