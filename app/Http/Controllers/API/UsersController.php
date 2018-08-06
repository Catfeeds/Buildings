<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;

class UsersController extends APIBaseController
{
    // 用户信息
    public function show($id)
    {
        if (empty($user = Auth::guard('api')->user())) {
            return $this->sendError('登录账户异常', 401);
        }

        return $this->sendResponse($user->toArray(),'用户信息获取成功');
    }
}
