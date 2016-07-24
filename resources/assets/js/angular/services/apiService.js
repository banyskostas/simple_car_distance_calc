var myModule = angular.module('apiService', []);

myModule.service('ApiService', function () {

        /**
         * Class ApiService
         */
        class ApiService {

            /**
             * @returns null|Object
             */
            getCarsList(callback) {
                $.ajax({
                    dataType: "json",
                    url: this.getLocation() + `getCars`,
                    success: (response) => {
                        if (response.success) {
                            if (typeof callback == "function") {
                                return callback(response.data);
                            }
                            return response.data;
                        } else {
                            alert(response.msg);
                        }
                        return null;
                    }
                });
            }

            /**
             * @returns null|Object
             */
            calcCarsTotalDistance(cars, dateFrom, dateTo, callback) {
                $.ajax({
                    method: 'POST',
                    dataType: "json",
                    url: this.getLocation() + `calcCarsTotalDistance/${dateFrom}/${dateTo}`,
                    data: {
                        'cars': JSON.stringify(cars)
                    },
                    success: (response) => {
                        if (response.success) {
                            if (typeof callback == "function") {
                                return callback(response.data);
                            }
                            return response.data;
                        } else {
                            alert(response.msg);
                        }
                        return null;
                    }
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
    });
