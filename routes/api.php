<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers:X-Token,Content-Type,Authorization,safeString');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

Route::group(['namespace' => 'API'], function () {
    Route::get('get_all_select', 'CitiesController@getAllSelect');

    // 登录
    Route::resource('/login', 'LoginController');

    Route::group(['middleware' => ['auth:api']], function () {
        // 退出
        Route::post('logout', 'LoginController@logout');
        // 用户
        Route::resource('/users', 'UsersController');
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
        // 楼盘
        Route::resource('/buildings', 'BuildingsController');
        // 楼盘下拉
        Route::get('/buildings_select', 'BuildingsController@buildingSelect');
        // 楼盘搜索
        Route::get('/building_search_select', 'BuildingsController@buildingSearchSelect');

        // 拿到楼盘下的所有楼座
        Route::resource('/building_blocks', 'BuildingBlocksController');

        // 楼座分页列表
        Route::get('/building_blocks_list', 'BuildingBlocksController@allBlocks');
        // 修改某个楼座的名称
        Route::post('/change_name_unit/{building_block}', 'BuildingBlocksController@changeNameUnit');
        // 添加楼座（名称、楼盘）
        Route::post('/add_name_unit', 'BuildingBlocksController@addNameUnit');
        // 补充楼座信息
        Route::post('/add_block_info/{building_block}', 'BuildingBlocksController@addBlockInfo');

        // 通过楼座获取城市
        Route::get('/adopt_building_block_get_city', 'BuildingBlocksController@adoptBuildingBlockGetCity');

        // 关键字管理
        Route::resource('/building_keywords', 'BuildingKeywordsController');

    });

    // 安全验证
    Route::group(['middleware' => 'safe.validate'], function () {
        // 所有下拉

        // 所有商圈
        Route::get('all_block','BlocksController@allBlock');

        // 所有楼座
        Route::get('all_building','BuildingsController@allBuilding');

        // 所有的楼座下拉数据
        Route::get('/building_blocks_all', 'BuildingBlocksController@buildingBlocksSelect');



    });
    //
    Route::get('/get_building_block', 'CitiesController@getBuildingBlock');


    // 获取公司所在区域
    Route::get('get_company_area','AreasController@getCompanyArea');
});
