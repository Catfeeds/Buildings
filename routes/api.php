<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers:X-Token,Content-Type,Authorization,safeString');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

Route::group(['namespace' => 'API'], function () {
    // 城市
    Route::resource('/cities', 'CitiesController');
    // 区域
    Route::resource('/areas', 'AreasController');
    // 商圈
    Route::resource('/blocks', 'BlocksController');
    // 楼盘
    Route::resource('/buildings', 'BuildingsController');
    // 楼盘特色
    Route::resource('/building_features', 'BuildingFeaturesController');
});
