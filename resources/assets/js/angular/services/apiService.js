var myModule = angular.module('apiService', []);

myModule.service('ApiService', ['$http', function ($http) {

        /**
         * Class ApiService
         */
        class ApiService {

            /**
             * @returns null|Object
             */
            getCarsList(callback) {
                $http.get(this.getLocation() + `getCars`)
                    .success( (response) => {
                        if (response.success) {
                            if (typeof callback == "function") {
                                return callback(response.data);
                            }
                            return response.data;
                        } else {
                            alert(response.msg);

                            if (typeof callback == "function") {
                                return callback(false);
                            }
                        }
                        return false;
                    });
            }

            /**
             * @returns null|Object
             */
            calcCarsTotalDistance(cars, dateFrom, dateTo, callback) {
                $http.post(
                    this.getLocation() + `calcCarsTotalDistance/${dateFrom}/${dateTo}`,
                    {
                        'cars': JSON.stringify(cars)
                    })
                    .success((response) => {
                        if (response.success) {
                            if (typeof callback == "function") {
                                return callback(response.data);
                            }
                            return response.data;
                        } else {
                            alert(response.msg);

                            if (typeof callback == "function") {
                                return callback(false);
                            }
                        }
                        return false;
                    });
            }

            /**
             * @returns {string|*}
             */
            getLocation() {
                if (!location.origin)
                    location.origin = location.protocol + "//" + location.host;

                return location.origin + `/`;
            }
        }
        return new ApiService;
    }]);
