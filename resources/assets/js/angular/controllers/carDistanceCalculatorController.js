var myModule = angular.module('carDistanceCalculatorController', []);

myModule.controller('CarDistanceCalculatorController', ['ApiService', '$scope', 'HelperService', function (api, $scope, helper) {
    $scope.cars = {
        raw: [],
        multiSelect: {
            title: 'Cars list',
            filterPlaceHolder: 'Start typing to filter the lists below.',
            labelAll: 'All Cars',
            labelSelected: 'Selected Cars',
            helpMessage: ' Click cars to transfer them between fields.',
            /* angular will use this to filter your lists */
            orderProperty: 'name',
            /* this contains the initial list of all items (i.e. the left side) */
            items: [],
            /* this list should be initialized as empty or with any pre-selected items */
            selectedItems: []
        }
    };
    $scope.selectedCars = [];

    $scope.getCarsList = function (callback) {
        api.getCarsList(function (data) {
            $scope.cars.raw = data;
            console.log($scope.cars.raw);
            $scope.$apply();

            setTimeout(function () {
                //$scope.$broadcast('dashboard-data-updated');
                $scope.generateCarsMultiSelectData();
                $scope.$apply();
            }, 0);

            if (typeof callback == 'function') {
                callback(data);
            }
        });
    };

    $scope.handleFirstLoad = () => {
        //$scope.showScreenLoader();

        $scope.getCarsList(function () {
            //$scope.hideScreenLoader();
        });
    };

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

    $scope.calcDestination = () => {
        // Predefine variables
        let cars = [];

        let selectedCars = $scope.cars.multiSelect.selectedItems;
        let dateFrom = '2015-01-01 00:00:00';
        let dateTo = '2015-12-31 00:00:00';

        angular.forEach(selectedCars, (car) => {
            cars.push(car.id);
        });

        api.calcCarsTotalDistance(cars, dateFrom, dateTo, (data) => {
            //$scope.cars.raw = data;
            console.log(data);
            $scope.$apply();

            setTimeout(function () {
                $scope.$apply();
            }, 0);

            if (typeof callback == 'function') {
                callback(data);
            }
        });
    };

    $scope.handleFirstLoad();
}]);
