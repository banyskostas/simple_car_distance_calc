@extends('...main')

@section('title', 'Car distance calculator')

@section('content')
<div id="carDistanceCalculator" ng-controller="CarDistanceCalculatorController">
    <div class="row">
        <!-- DESCRIPTION -->
        <div class="col-md-12">
            <h2>Description</h2>
            <p>
                App that allows you to select cars and calculate the distance they have
                driven in selected date range.
            </p>
        </div>
    </div>

    <div class="row">
        <!-- TECHNOLOGIES -->
        <div class="col-xs-12 col-md-6">
            <h3>Technologies</h3>
            <ul>
                <li>PHP 5.5</li>
                <li>Laravel 5.2</li>
                <li>MongoDB 3.2.8</li>
                <li>AngularJS 1.5.8</li>
                <li>Bootstrap 3.3.6</li>
                <li>jQuery 2.2.4</li>
            </ul>
        </div>

        <!-- LARAVEL PACKAGES -->
        <div class="col-xs-12 col-md-6">
            <h3>Laravel packages</h3>
            <ul>
                <li>jenssegers/mongodb 3.0</li>
            </ul>
        </div>
    </div>

    <div class="row">
        <!-- USAGE -->
        <div class="col-md-12">
            <h2>Usage</h2>
            <p>
                In order to calculate the distance you need to <mark>select a car</mark> or several cars you want then <mark>select the date range</mark> and <mark>click "Calculate"</mark>
            </p>
        </div>
    </div>

    <!-- BEGIN DEMO -->
    <div class="row">
        <div class="col-md-12">
            <h2>Demo</h2>
            <dualmultiselect options="cars['multiSelect']"> </dualmultiselect>
        </div>
    </div>

    <!-- BEGIN DATE TIME RANGE PICKER -->
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <h4>Date from</h4>
            <div class="dropdown">
                <a class="dropdown-toggle" id="dropdown2" role="button" data-toggle="dropdown" data-target="#" href="#">
                    <div class="input-group"><input type="text" class="form-control" data-ng-model="dateFrom"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                    <datetimepicker data-ng-model="dateFromMoment" ng-change="regenerateDates()" data-datetimepicker-config="{ dropdownSelector: '#dropdown2' }"/>
                </ul>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <h4>Date to</h4>
            <div class="dropdown">
                <a class="dropdown-toggle" id="dropdown3" role="button" data-toggle="dropdown" data-target="#" href="#">
                    <div class="input-group"><input type="text" class="form-control" data-ng-model="dateTo"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                    <datetimepicker data-ng-model="dateToMoment" ng-change="regenerateDates()" data-datetimepicker-config="{ dropdownSelector: '#dropdown3' }"/>
                </ul>
            </div>
        </div>
    </div>
    <!-- END DATE TIME RANGE PICKER -->

    <!-- BEGIN CALCULATE -->
    <div class="row">
        <div class="calculate col-md-12">
            <button class="btn btn-lg btn-primary pull-right" ng-class="loading ? 'disabled' : ''" ng-click="calcDistance()" ngDisabled="loading">
                Calculate
            </button>
            <div class="checkbox pull-right">
                <label>
                  <input type="checkbox" ng-model="requestType" /> Request one by one
                </label>
            </div>
            <i ng-show="loading" class="fa fa-spin fa-spinner pull-right"></i>
        </div>
    </div>
    <!-- END CALCULATE -->

    <!-- BEGIN RESULTS -->
    <div class="row">
        <div class="col-md-12">
            <h2>Results</h2>
            <pre>@{{calculatedDestinationHtml}}</pre>
            <div class="alert alert-warning" role="alert">
                <ul>
                    <li>
                        <span><b>Spots</b> - data with coordinates information sent by GPS in different time intervals from car.</span>
                    </li>
                    <li>
                        <span><b>Accuracy</b> - it's calculated by spots total and failed spots.</span>
                    </li>
                    <li>
                        <span><b>Failed spots</b> - spots that has corrupted, missing data or couldn't be calculated by our coordinates distance calculator.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END RESULTS -->
    <!-- END DEMO -->
</div>
@endsection