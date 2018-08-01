<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers:X-Token,Content-Type,Authorization,safeString');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

Route::group(['namespace' => 'API'], function () {
    // 七牛token
    Route::get('/get_qi_niu_token', 'CitiesController@token');

    // 城市
    Route::resource('/cities', 'CitiesController');
    // 获取所有城市
    Route::get('/get_all_city', 'CitiesController@getAllCity');
    // 区域
    Route::resource('/areas', 'AreasController');
    // 获取所有区域
    Route::get('/get_all_area', 'AreasController@getAllArea');
    // 所有区的下拉数据
    Route::get('/areas_select', 'AreasController@areasSelect');
    // 商圈
    Route::resource('/blocks', 'BlocksController');
    // 某区域下拉数据
    Route::get('/blocks_select', 'BlocksController@blocksSelect');
    // 获取所有商圈基础地理位置
    Route::get('block_locations', 'BlocksController@blockLocations');
    // 获取所有商圈
    Route::get('all_building_blocks', 'BlocksController@allBuildingBlock');
    // 商圈添加推荐
    Route::post('add_recommend/{guid}', 'BlocksController@addRecommend');
    // 楼盘
    Route::resource('/buildings', 'BuildingsController');
    // 楼盘下拉
    Route::get('/buildings_select', 'BuildingsController@buildingSelect');
    // 添加楼盘标签
    Route::post('add_building_label', 'BuildingsController@addBuildingLabel');
    // 楼盘搜索
    Route::get('/building_search_select', 'BuildingsController@buildingSearchSelect');
    // 楼盘特色下拉数据
    Route::get('building_feature_list', 'BuildingsController@buildingFeatureList');
    // 楼盘特色
    Route::resource('/building_features', 'BuildingFeaturesController');

    // 修改某个楼座的名称
    Route::post('/change_name_unit/{building_block}', 'BuildingBlocksController@changeNameUnit');
    // 添加楼座（名称、楼盘）
    Route::post('/add_name_unit', 'BuildingBlockController@addNameUnit');
    // 补充楼座信息
    Route::post('/add_block_info/{building_block}', 'BuildingBlockController@addBlockInfo');



});

