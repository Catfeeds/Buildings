<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\LoginRequest;
use App\Services\LoginService;

class LoginController extends APIBaseController
{
    // 账号密码登录
    public function store(
        LoginRequest $request,
        LoginService $loginService
    )
    {
        $passport = $loginService->applyPasswordToken($request->name, $request->password);
        if (empty($passport['success'])) return $this->sendError($passport['message']);
        return $this->sendResponse(['status' => true, 'token' => $passport['token']], '获取token成功！');
    }

    // 退出登录
    public function logout(
        LoginService $loginService
    )
    {
        $result = $loginService->logout('api');
        return $this->sendResponse($result, '退出成功！');
    }
}
