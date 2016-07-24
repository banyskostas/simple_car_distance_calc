var myModule = angular.module('helperService', []);

myModule.service('HelperService', function () {

        /**
         * Class HelperService
         */
        class HelperService {

            /**
             * @param {int} str
             * @returns {string}
             */
                getTime(str) {
                var sec_num = parseInt(str, 10); // don't forget the second param
                var hours = Math.floor(sec_num / 3600);
                var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
                var seconds = sec_num - (hours * 3600) - (minutes * 60);
                if (hours < 10) {
                    hours = "0" + hours;
                }
                if (minutes < 10) {
                    minutes = "0" + minutes;
                }
                if (seconds < 10) {
                    seconds = "0" + seconds;
                }
                return hours + ':' + minutes + ':' + seconds;
            }

            /**
             * @param {string} needle
             * @param {array} haystack
             * @returns {boolean}
             */
                inArray(needle, haystack) {
                var length = haystack.length;
                for (var i = 0; i < length; i++) {
                    if (haystack[i] == needle) return true;
                }
                return false;
            }

            /**
             * @param {string} str
             * @param {int} maxLength
             * @returns {string}
             */
                customSubstring(str, maxLength) {
                if (str.length > maxLength) {
                    var int = str.length - maxLength + 3;
                    str = str.substring(0, str.length - int) + '...';
                }
                return str;
            }
        }

        return new HelperService;
    });
