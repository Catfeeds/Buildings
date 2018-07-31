<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers:X-Token,Content-Type,Authorization,safeString');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

Route::group(['namespace' => 'API'], function () {
    // 城市
    Route::resource('/cities', 'CitiesController');
    // 获取所有城市
    Route::get('/get_all_city', 'CitiesController@getAllCity');
    // 区域
    Route::resource('/areas', 'AreasController');
    // 获取所有区域
    Route::resource('/get_all_area', 'AreasController@getAllArea');
    // 商圈
    Route::resource('/blocks', 'BlocksController');
    // 楼盘
    Route::resource('/buildings', 'BuildingsController');
    // 楼盘特色
    Route::resource('/building_features', 'BuildingFeaturesController');
});
