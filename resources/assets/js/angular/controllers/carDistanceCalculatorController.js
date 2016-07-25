var myModule = angular.module('carDistanceCalculatorController', []);

myModule.controller('CarDistanceCalculatorController', ['ApiService', '$scope', 'HelperService', function (api, $scope, helper) {
    // Predefine scope variables
    $scope.cars = {
        raw: [],
        multiSelect: {
            title: 'Cars list',
            filterPlaceHolder: 'Start typing to filter the lists below.',
            labelAll: 'All Cars',
            labelSelected: 'Selected Cars',
            helpMessage: ' Click cars to transfer them between fields.',
            /* List filter*/
            orderProperty: 'name',
            /* All items*/
            items: [],
            /* Pre-selected items */
            selectedItems: []
        }
    };
    $scope.selectedCars = [];
    $scope.dateFromMoment = {};
    $scope.dateToMoment = {};
    $scope.dateFrom = '';
    $scope.dateTo = '';
    $scope.calculatedDestinationHtml = `Here you'll see the results...`;
    $scope.loading = false;
    $scope.requestType = true;

    /**
     * First load handler
     */
    $scope.handleFirstLoad = () => {
        $scope.getCarsList();
    };

    /**
     * Get cars list through AJAX
     */
    $scope.getCarsList = function () {
        api.getCarsList(function (data) {
            $scope.cars.raw = data;
            console.log($scope.cars.raw);

            setTimeout(function () {
                $scope.generateCarsMultiSelectData();
                $scope.$apply();
            }, 0);
        });
    };

    /**
     * Generate cars multi select data
     * Formula:
     * {
     *  id: imei number,
     *  name: generated name
    *  }
     */
    $scope.generateCarsMultiSelectData = () => {
        let data = [];

        angular.forEach($scope.cars.raw, (car, imei) => {
            data.push({
                id: imei,
                name: $scope.generateCarName(car)
            });
        });
        $scope.cars.multiSelect.items = data;
    };

    /**
     * Calculate selected cars distances and total distance
     * There is a possibility to select request type
     * 1. Calculate cars distances one by one using a separate request for each of them. (This helps to see that something is happening)
     * 2. Calculate all cars distances at once using one request.
     */
    $scope.calcDistance = () => {

        // Prevent several calculation requests if it's already started
        if ($scope.loading) {
            return false;
        }

        // Get data
        let selectedCars = $scope.cars.multiSelect.selectedItems;
        let dateFrom = $scope.dateFrom;
        let dateTo = $scope.dateTo;

        // Validate
        let validation = $scope.validate(selectedCars, dateFrom, dateTo);

        if (!validation.result) {
            alert(validation.msg);
            return false;
        }
        $scope.loading = true;

        // Reset results
        $scope.calculatedDestinationHtml = ``;

        $scope.requestType ?
            $scope.calcDestinationOneByOne(selectedCars, dateFrom, dateTo)
            :
            $scope.calcDestinationAllAtOnce(selectedCars, dateFrom, dateTo);
    };

    /**
     * @param {array} selectedCars
     * @param {string} dateFrom
     * @param {string} dateTo
     */
    $scope.calcDestinationOneByOne = (selectedCars, dateFrom, dateTo) => {
        // Predefine variables
        let processed = 0;
        let totalDistance = 0;

        angular.forEach(selectedCars, (car) => {
            if ($scope.loading) {
                api.calcCarsTotalDistance([car.id], dateFrom, dateTo, (data) => {
                    if (!data) {
                        $scope.loading = false;
                    }
                    $scope.printCarDistanceData(car, data);
                    totalDistance += data.cars[car.id].distance;
                    processed++;

                    if (selectedCars.length == processed) {
                        $scope.printTotalDistance(data.totalDistance);
                        $scope.loading = false;
                    }
                });
            }
        });
    };

    /**
     * @param {array} selectedCars
     * @param {string} dateFrom
     * @param {string} dateTo
     */
    $scope.calcDestinationAllAtOnce = (selectedCars, dateFrom, dateTo) => {
        // Predefine variables
        let cars = [];

        angular.forEach(selectedCars, (car) => {
            cars.push(car.id);
        });

        api.calcCarsTotalDistance(cars, dateFrom, dateTo, (data) => {
            angular.forEach(selectedCars, (car) => {
                $scope.printCarDistanceData(car, data);
            });
            $scope.printTotalDistance(data.totalDistance);
            $scope.loading = false;
        });
    };

    /**
     * @param {Object} car
     * @returns {string}
     */
    $scope.generateCarName = (car) => {
        let str = ``;

        if (car.manufacturer) {
            str += `${car.manufacturer} `;
        }

        if (car.model) {
            str += `${car.model} `;
        }

        if (car.plate_nr) {
            str += `(${car.plate_nr})`;
        }
        return str;
    };

    /**
     * @param {array} selectedCars
     * @param {string} dateFrom
     * @param {string} dateTo
     * @returns {{result: boolean, msg: string}}
     */
    $scope.validate = (selectedCars, dateFrom, dateTo) => {
        let msg = '';

        if (selectedCars.length <= 0) {
            msg = `Please select at least one car`;
        }

        if (msg == '' && dateFrom == '') {
            msg = `Please select date from`;
        }

        if (msg == '' && dateTo == '') {
            msg = `Please select date to`;
        }

        return {
            result: msg == '',
            msg: msg
        };
    };

    /**
     * @param {Object} car
     * @param {array} data
     * @returns {boolean}
     */
    $scope.printCarDistanceData = (car, data) => {
        if (typeof data.cars[car.id] == 'undefined') {
            return false;
        }

        let carData = data.cars[car.id];
        $scope.calculatedDestinationHtml += `${car.name} \n`;
        $scope.calculatedDestinationHtml += `Distance: ${carData.distance} Km \n`;
        $scope.calculatedDestinationHtml += `Accuracy: ${carData.distance_calc_accuracy_percentage} % \n`;
        $scope.calculatedDestinationHtml += `Failed spots: ${carData.failed_spots} \n`;
        $scope.calculatedDestinationHtml += `Processed spots: ${carData.processed_spots} \n`;
        $scope.calculatedDestinationHtml += `--------------------------------------------------------\n`;
    };

    /**
     * @param {float} totalDistance
     */
    $scope.printTotalDistance = (totalDistance) => {
        $scope.calculatedDestinationHtml += `Total distance: ${totalDistance} Km`;
    };

    /**
     * Change date format to desired
     */
    $scope.regenerateDates = () => {
        var formatDate = "YYYY-MM-DD HH:mm:ss";
        $scope.dateFrom = moment($scope.dateFromMoment).format(formatDate);
        $scope.dateTo = moment($scope.dateToMoment).format(formatDate);
    };

    $scope.handleFirstLoad();
}]);
