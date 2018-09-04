<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Token;

class LoginService
{
    // 申请密码token
    public function applyPasswordToken(
        $name,
        $password,
        $client_id = null,
        $client_secret = null,
        $scope = ''
    )
    {
        try {
            $http = new Client();
            $result = $http->post(url('/oauth/token'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'username' => $name,
                    'password' => $password,
                    'scope' => $scope??config('passport.default.scope'),
                    'client_id' => $client_id??config('passport.default.client_id'),
                    'client_secret' => $client_secret??config('passport.default.client_secret')
                ],
            ]);
        } catch (\Exception $e) {
            $error = explode("\n", $e->getMessage())[1];
            if ($error[strlen($error) - 1] != '}') {
                $error = $error . '"}';
            }

            if (empty(json_decode($error))) return [
                'status' => false,
                'message' => '服务器异常，请联系管理员'
            ];

            switch (json_decode($error)->message) {
                case 'The user credentials were incorrect.':
                    $resultData = '用户名或密码错误！';
                    break;
                case 'Client authentication failed':
                case 'The requested scope is invalid, unknown, or malformed':
                    $resultData = '客户端出错，请重新下载！';
                    break;
                default:
                    $resultData = '未知错误，请联系管理员！';
                    break;
            }
            return [
                'status' => false,
                'message' => $resultData
            ];
        }

        return ['success' => true, 'token' => json_decode((string)$result->getBody(), true)['access_token']];
    }

    // 退出登录
    public function logout($guard)
    {
        $user = Auth::guard($guard)->user();
        if (empty($user)) return ['status' => false, 'message' => '暂未登录'];

        $accessToken = $user->access_token;
        $token = Token::find($accessToken);
        if (empty($token)) return ['status' => false, 'message' => '暂无有效令牌'];

        if (!empty($token->delete())) {
            return ['status' => true, 'message' => '退出成功'];
        } else {
            return ['status' => true, 'message' => '退出失败'];
        }
    }
}