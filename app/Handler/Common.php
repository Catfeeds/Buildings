<?php
/**
 * 公共方法
 * User: 罗振
 * Date: 2018/3/12
 * Time: 下午3:43
 */
namespace App\Handler;

use Qiniu\Storage\UploadManager;
use Ramsey\Uuid\Uuid;

/**
 * Class Common
 * 常用工具类
 * @package App\Tools
 */
class Common
{
    // 获取guid
    public static function getUuid()
    {
        $uuid1 = Uuid::uuid1();
        return $uuid1->getHex();
    }

    // 获取七牛token
    public static function getToken($accessKey = null, $secretKey = null, $bucket = null)
    {
        if (empty($accessKey)) {
            $accessKey = config('setting.qiniu_access_key');
        }
        if (empty($secretKey)) {
            $secretKey = config('setting.qiniu_secret_key');
        }
        if (empty($bucket)) {
            $bucket = config('setting.qiniu_bucket');
        }
        // 构建鉴权对象
        $auth = new \Qiniu\Auth($accessKey, $secretKey);

        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        return $token;
    }

    // 七牛上传图片
    public static function QiniuUpload($filePath, $key)
    {
        //获得token
        $token = self::getToken();

        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        $res = ['status' => true, 'url' => config('setting.qiniu_url') . $key];

        if (!$err == null) return ['status' => false, 'msg' => $err];

        return $res;
    }
}