<?php
/**
 * 阿里云短信发送类
 * User: peijiyang
 * Date: 2017/9/25 0025
 * Time: 15:07
 */
namespace App\Service;

use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\Regions\EndpointConfig;
use Aliyun\Core\Config;
use App\Service\Log;
// 加载区域结点配置
Config::load();
class AliSMS
{
    private $_key;
    private $_sec;
    private $_sign_name;//短信签名
    private $_product = 'Dysmsapi';////短信API产品名（短信产品名固定，无需修改）
    private $_api_domain = 'dysmsapi.aliyuncs.com';////短信API产品域名（接口地址固定，无需修改）
    private $_region = 'cn-hangzhou';//暂时不支持多Region[地址]（目前仅支持cn-hangzhou请勿修改）
    private $_endPointName='cn-hangzhou';
    private $_acsClient;
    public function __construct()
    {
        $this -> _key = config('app')['ali_sms']['key'];
        $this -> _sec = config('app')['ali_sms']['sec'];
        $this -> _sign_name = config('app')['ali_sms']['sign_name'];
        // 初始化用户Profile实例
        $profile = DefaultProfile::getProfile($this -> _region, $this -> _key, $this -> _sec);
        // 增加服务结点
        DefaultProfile::addEndpoint($this -> _endPointName, $this -> _region, $this -> _product, $this -> _api_domain);
        // 初始化AcsClient用于发起请求
        $this-> _acsClient = new DefaultAcsClient($profile);
    }
    /**
     * @name 发送短信
     * @param string $phone
     * @param string $code 短信模板id
     * @param array $message 短信内容
     *
     * @return
     * @author peijiyang
     * @date 2017-09-25
     * */
    public function send($phone, $code, $message)
    {
        $message = json_encode($message);
        $request = new SendSmsRequest;
        //必填-短信接收号码。支持以逗号分隔的形式进行批量调用，批量上限为1000个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
        $request->setPhoneNumbers($phone);
        //必填-短信签名
        $request->setSignName($this -> _sign_name);
        //必填-短信模板Code
        $request->setTemplateCode($code);
        //选填-假如模板中存在变量需要替换则为必填(JSON格式),友情提示:如果JSON中需要带换行符,请参照标准的JSON协议对换行符的要求,比如短信内容中包含\r\n的情况在JSON中需要表示成\\r\\n,否则会导致JSON在服务端解析失败
        $request->setTemplateParam($message);
        //选填-发送短信流水号
        $rand = mt_rand(100000, 999999);
        $request->setOutId($rand);
        //发起访问请求
        $acsResponse = $this-> _acsClient->getAcsResponse($request);
        Log::write('-----------短信发送服务------------', [$acsResponse, '---send_phone----'.$phone], 'sms');
        return $acsResponse;
    }
}