<?php

namespace App\Service;
use App\Service\Log;

class Weather
{
    private $_appKey;
    private $_appSecret;
    private $_appCode;

    public function __construct()
    {
        $this -> _appKey = config('app')['weather']['AppKey'];
        $this -> _appSecret = config('app')['weather']['AppSecret'];
        $this -> _appCode = config('app')['weather']['AppCode'];
    }
    /**
     * @name 获取天气详情
     * $param string $city 城市天气代号（city,cityid,citycode三者任选其一）
     * $param string $cityCode
     * $param string $cityId
     * $param string $ip
     * $param string $location //经纬度 纬度在前，,分割 如：39.983424,116.322987
     * @return boolean|array
     *
     * @author peijiyang
     * @date 2017-09-26
     * */
    public function getWeather($city = '', $cityCode = '', $cityId = '', $ip = '', $location = '')
    {
        $getUrl = 'http://jisutianqi.market.alicloudapi.com/weather/query';
        $params = [];
        if ($city) $params['city'] = $city;
        if ($city) $params['citycode'] = $cityCode;
        if ($city) $params['cityid'] = $cityId;
        if ($city) $params['ip'] = $ip;
        if ($city) $params['location'] = $location;
        if (empty($params)) return ['code' => 201, 'message' => '请求参数不能为空'];
        $res = $this -> curl($getUrl, $params);
        Log::write('-----------获取天气信息-----------', [$res], 'weather');
        if ($res['status'] == 0 && $res['msg'] == 'ok') {
            return $res['result'];
        }
        Log::write('-----------获取天气信息失败-----------', [$res], 'weather');
        return false;
    }
    /**
     * @name 获取天气详情
     * @return boolean|array
     * @author peijiyang
     * @date 2017-09-26
     * */
    public function getCity()
    {
        $getUrl = 'http://jisutianqi.market.alicloudapi.com/weather/city';
        $res = $this -> curl($getUrl);
        if ($res['status'] == 0 && $res['msg'] == 'ok') {
            /*
             *  [{
                    "cityid":"150",
                    "parentid":"10",
                    "citycode":"101180801",
                    "city":"开封"
                }]
             * */
            return $res['result'];
        }
        Log::write('-----------获取城市信息失败-----------', [$res], 'weather');
        return false;
    }

    /**
     * @name 请求处理
     * @param string $url 请求的url
     * @param boolean|array $params 请求参数
     * @param int $ispost 0 get  1 post
     * @param int $https 0 http 1 https
     * @param array $header 头部信息
     * @return false|array
     *
     * @author peijiyang
     * @date 2017-09-12
     * */
    private function curl($url, $params = false, $ispost = 0, $https = 0, $header = [])
    {
        $httpInfo = array();
        // 简单的 appcode 验证
        $header= ["Authorization:APPCODE " . $this -> _appCode];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        // 返回 response_header, 该选项非常重要,如果不为 true, 只会获得响应的正文
        curl_setopt($ch, CURLOPT_HEADER, true);
        // 是否不需要响应的正文,为了节省带宽及时间,在只需要响应头的情况下可以不要正文
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        }
        if ($ispost) {
            if (is_array($params)) {
                $param = '';
                foreach ($params as $key => $value) {
                    $param .= $key . '=' . $value . '&';
                }
                $params = trim($param, '&');
            }
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {

            if ($params) {
                if (is_array($params)) {
                    $params = http_build_query($params);
                }
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        $response = curl_exec($ch);
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        // 获得响应结果里的：头大小
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        // 根据头大小去获取头信息内容
        //$header = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        curl_close($ch);
        return json_decode($body, true);
    }

}