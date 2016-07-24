@extends('...main')

@section('title', 'Car distance calculator')

@section('content')
<div ng-controller="CarDistanceCalculatorController">
    <div class="row">
        <div class="col-md-12">
            <dualmultiselect options="cars['multiSelect']"> </dualmultiselect>
        </div>
        <div class="col-md-12">
            <button ng-click="calcDestination()" class="btn btn-lg btn-primary pull-right">Calculate destination</button>
        </div>
    </div>
</div>
@endsection