<?php

Route::group(['prefix' => 'api', 'namespace' => 'Fourampers\LaravelOWM\Http\Controllers'], function() {

    Route::get('weather', 'LaravelOWMController@currentweather');

});