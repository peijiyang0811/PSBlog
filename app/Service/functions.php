<?php
/**
 * @name 密码加密
 * @param string $password 待加密的字符串
 * @param string $key 加密密文
 * @param integer $type 1 PHP加密 2 JS加密后的字符串
 * @return string $encrypt
 *
 * @author peijiyang
 * @date 2017-08-25
 * */
function passwordEncrypt(string $password, int $type = 1, string $key = '') : string {
    if (empty($password)) return false;
    $key = empty($key) ? config('app.key') : $key;
    $encrypt = '';
    switch (intval($type)) {
        case 1:
            $encrypt = strtoupper(strrev(md5(sha1(md5($password)) . $key)));
            break;
        case 2:
            $encrypt = strtoupper(strrev($password));
            break;
        default:
            $encrypt = strtoupper(strrev(md5(sha1(md5($password)) . $key)));
            break;
    }
    return $encrypt;
}
/**
 * @name 根据ip地址获取地理位置
 * @param string $ip ip地址127.0.0.1
 * @return string $area 中国-河南-郑州
 *
 * @auth peijiyang<peijiyang@psfmaily.cn>
 * @date 2017-08-23
 */
function getAreaByIp(string $ip) : string {
    $searchURL = 'http://ip.taobao.com/service/getIpInfo.php';
    $result = curl($searchURL, ['ip' => $ip], 0, 0);
    //{"code":0,"data":{"country":"\u4e2d\u56fd","country_id":"CN","area":"\u534e\u5317","area_id":"100000","region":"\u6cb3\u5317\u7701","region_id":"130000","city":"\u5f20\u5bb6\u53e3\u5e02","city_id":"130700","county":"","county_id":"-1","isp":"\u963f\u91cc\u5df4\u5df4","isp_id":"100098","ip":"47.92.29.199"}}
    if ($result['body']['code'] == 1) $area = '未知地址';
    return  $result['body']['data']['country'].'-'.$result['body']['data']['area'].'-'.$result['body']['data']['region'].'-'.$result['body']['data']['city'].'-'.$result['body']['data']['county'];
}
/**
   @name 远程请求
 * @param $url请求网址

 * @param array $params 请求参数

 * @param int $ispost 请求方式 0 get 方式  1 post 方式

 * @param int $https https协议 0 http 协议 1 https 协议
 *
 * @param array $https header 头部信息

 * @return bool|mixed

 */
function curl($url, $params = false, $ispost = 0, $https = 0, $header = [])
{
    $httpInfo = array();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
    curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
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
    $responseHeader = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);// 状态码
    //$httpInfo = array_merge($httpInfo, curl_getinfo($ch));// http 请求的详细信息
    curl_close($ch);
    return ['body' => json_decode($body, true), 'code' => $httpCode, 'header' => $responseHeader];
}
/**
 * 验证手机号是否正确
 *
 * @author peijiyang
 * @param number $mobile
 * @return mixed boolean|string
 *
 * @author peijiyang
 * @date 2017-08-24
 */
function isMobile(string $mobile){
    /*
     *  移动号段：134 135 136 137 138 139 147 150 151 152 157 158 159 172 178 182 183 184 187 188
     *  联通号段：130 131 132 145 155 156 171 175 176 185 186
     *  电信号段：133 149 153 173 177 180 181 189
     *  虚拟运营商: 170 171
     * 199 198 166
     * 13 0 1 2 3 4 5 6 7 8 9 [0-9]
     * 14 4 7 9 [4,7,9]
     * 15 0 1 2 3 5 6 7 8 9 [^4]
     * 17 0 1 2 3 5 6 7 8 [^4|^9]
     * 18 0 1 2 3 4 5 6 7 8 9 [0-9]
     * 19 6 8 9
     * */
    $len = strlen($mobile);
    $phone = '';
    for ($i = 0;$i < $len; $i++) {
        if ($mobile[$i] != ' ') $phone .= $mobile[$i];
    }
    if (!is_numeric($phone)) {
        return false;
    }
    return preg_match('/^13[\d]{9}$|^14[4,7,9]{1}\d{8}$|^15[^4]{1}\d{8}$|^166\d{8}$|^17[^4|^9]{1}\d{8}$|^18[\d]{9}$|^19[8,9]{1}\d{8}$/', $phone) ? $phone : false;
}
/**
 * @name 格式化时间戳
 * @param int $time 时间戳
 * @return string $text 10分钟前
 *
 * @author peijiyang
 * @date 2017-08-24
 */
function formatDate($time = NULL) : string {
    $text = '';
    $time = $time === NULL || $time > time() ? time() : intval($time);
    $t = time() - $time; //时间差 （秒）
    $y = date('Y', $time)-date('Y', time());//是否跨年
    if ($t == 0) {
        $text = '刚刚';
    } else if ($t < 60) {
        $text = $t . '秒前';// 一分钟内
    } else if ($t < 60 * 60) {
        $text = floor($t / 60) . '分钟前';
    } else if ($t < 60 * 60 * 24) {
        $text = floor($t / (60 * 60)) . '小时前';// 一天内
    } else if ($t < 60 * 60 * 24 * 3) {
        //昨天和前天
        $text = floor($t/(60*60*24)) ==1 ?'昨天 ' . date('H:i', $time) : '前天 ' . date('H:i', $time) ;
    } else if ($t < 60 * 60 * 24 * 30) {
        $text = date('m月d日 H:i', $time);//一个月内
    } else if ($t < 60 * 60 * 24 * 365 && $y == 0) {
        $text = date('m月d日', $time);//一年内
    } else {
        $text = date('Y-m-d H:i', $time); //一年以前
    }
    return $text;
}
/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function zhSubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) :string {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
        if(false === $slice) {
            $slice = '';
        }
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice.'...' : $slice;
}

/**
 * @name 输出安全的html
 * @param string $text
 * @param string $tags 制定过滤某个标签 a|table
 * @return string
 *
 * @date 2017-08-24
 * */
function safeHtml($text, $tags = null) {
    $text	=	trim($text);
    //完全过滤注释
    $text	=	preg_replace('/<!--?.*-->/','',$text);
    //完全过滤动态代码
    $text	=	preg_replace('/<\?|\?'.'>/','',$text);
    //完全过滤js
    $text	=	preg_replace('/<script?.*\/script>/','',$text);

    $text	=	str_replace('[','&#091;',$text);
    $text	=	str_replace(']','&#093;',$text);
    $text	=	str_replace('|','&#124;',$text);
    //过滤换行符
    //$text	=	preg_replace('/\r?\n/','',$text);
    //br
    $text	=	preg_replace('/<br(\s\/)?'.'>/i','[br]',$text);
    $text	=	preg_replace('/<br(\s)?(\/)?'.'>/i','[br/]',$text);
    $text	=	preg_replace('/<p(\s\/)?'.'>/i','[p]',$text);
    $text	=	preg_replace('/(\[br\]\s*){10,}/i','[br]',$text);
    //过滤危险的属性，如：过滤on事件lang js
    while(preg_match('/(<[^><]+)( lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i',$text,$mat)){
        $text=str_replace($mat[0],$mat[1],$text);
    }
    while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
        $text=str_replace($mat[0],$mat[1].$mat[3],$text);
    }
    if(empty($tags)) {
        $tags = 'table|td|th|tr|i|b|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt|a|h1|h2|h3|h4|h5|h6|pre';
    }
    //允许的HTML标签
    $text	=	preg_replace('/<('.$tags.')([^><\[\]]*)>/i','[\1\2]',$text);
    $text = preg_replace('/<\/('.$tags.')>/Ui','[/\1]',$text);
    //过滤多余html
    $text	=	preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml)[^><]*>/i','',$text);
    //过滤合法的html标签
    while(preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i',$text,$mat)){
        $text=str_replace($mat[0],str_replace('>',']',str_replace('<','[',$mat[0])),$text);
    }
    //转换引号
    while(preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i',$text,$mat)){
        $text=str_replace($mat[0],$mat[1].'|'.$mat[3].'|'.$mat[4],$text);
    }
    //过滤错误的单个引号
    while(preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i',$text,$mat)){
        $text=str_replace($mat[0],str_replace($mat[1],'',$mat[0]),$text);
    }
    //转换其它所有不合法的 < >
    $text	=	str_replace('<','&lt;',$text);
    $text	=	str_replace('>','&gt;',$text);
    $text	=	str_replace('"','&quot;',$text);
    //反转换
    $text	=	str_replace('[','<',$text);
    $text	=	str_replace(']','>',$text);
    $text	=	str_replace('|','"',$text);
    //过滤多余空格
    //$text	=	str_replace('  ',' ',$text);
    return $text;
}
/*
|--------------------------------------------------------------------------
| 去除不安全的标签,防止XSS攻击
|--------------------------------------------------------------------------
| @param string $val
| @return string
|
| @date 2017-08-24
*/
function removeXss($val) {
    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
        $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
    }
    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);
    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(&#0{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }
    return $val;
}
/**
 * @name 获取客户端真实ip
 * @param
 * @return mixed
 *
 * @date 2017-08-24
 * */
function getClientIp() {
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
    else
        if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else
            if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
                $ip = getenv("REMOTE_ADDR");
            else
                if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
                    $ip = $_SERVER['REMOTE_ADDR'];
                else
                    $ip = "unknown";
    return ($ip);
}
/**
 * @name 加密一个数组
 * @param array $params
 * @return string
 *
 * @date 2017-08-24
 */
function auth_encode($params = array()) {
    $string = implode("\n", $params);
    return authCode($string, 'ENCODE');
}
/**
 * @name 解密一个数组加密字符串
 * @param string $string
 * @return array 解密以后是一个 索引数组 eg:['aa'=>123] 加密成一个字符串,在解密后的结果是 [0=>123]
 *
 * @date 2017-08-24
 */
function auth_decode($string) {
    return explode("\n", authCode($string, 'DECODE'));
}

/**
 * 加密解密字符串
 *
 * @param mixed $string
 * @param string $operation ENCODE|DECODE
 * @param string $key
 * @param integer $expiry
 * @return mixed
 *
 * @date 2017-08-24
 */
function authCode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    $key = '$%^&*()__)yoijsfs';
    $ckey_length = 4;
    $key = md5($key);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}
/**
 * 对查询结果集进行排序
 * @access public
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param string $sortby 排序类型 asc正向排序 desc逆向排序 nat自然排序
 * @return array|boolean
 *
 * @date 2017-08-24
 */
function list_sort_by($list,$field, $sortby='asc') {
    if(is_array($list)){
        $refer = $resultSet = array();
        foreach ($list as $i => $data)
            $refer[$i] = &$data[$field];
        switch ($sortby) {
            case 'asc': // 正向排序
                asort($refer);
                break;
            case 'desc':// 逆向排序
                arsort($refer);
                break;
            case 'nat': // 自然排序
                natcasesort($refer);
                break;
        }
        foreach ( $refer as $key=> $val)
            $resultSet[] = &$list[$key];
        return $resultSet;
    }
    return false;
}
/**
 * 在数据列表中搜索
 * @access public
 * @param array $list 数据列表
 * @param mixed $condition 查询条件 支持 array('name'=>$value) 或者 name=$value
 * @return array
 *
 * @date 2017-08-24
 */
function listSearch($list,$condition) {
    if(is_string($condition))
        parse_str($condition,$condition);
    // 返回的结果集合
    $resultSet = array();
    foreach ($list as $key=>$data){
        $find   =   false;
        foreach ($condition as $field=>$value){
            if(isset($data[$field])) {
                if(0 === strpos($value,'/')) {
                    $find   =   preg_match($value,$data[$field]);
                }elseif($data[$field]==$value){
                    $find = true;
                }
            }
        }
        if($find)
            $resultSet[]     =   &$list[$key];
    }
    return $resultSet;
}
/**
 * @name 无限层级的生成树方法,代替递归方式，仅两次循环遍历，复杂度2n
 * @param array $data 二维数组
 * @param string $parent_index 父级字段名称
 * @param string $data_index 主键字段名称
 * @param string $child_name 名称
 * @return mixed boolean|array
 *
 * @date 2017-08-24
 **/
function listToTree(array $data, $parent_index = 'parent_id', $data_index = 'id', $child_name = 'child') {
    $items = [];
    foreach ($data as $d){
        $items[$d[$data_index]] = $d;
        if(!isset($d[$parent_index]) || !isset($d[$data_index]) || isset($d[$child_name])){
            return false;
        }
    }
    $tree = [];
    $n=0;
    foreach($items as $item){
        if(isset($items[$item[$parent_index]])){
            $items[$item[$parent_index]][$child_name][] = &$items[$item[$data_index]];
        }else{
            $tree[$n++] = &$items[$item[$data_index]];
        }
    }
    return $tree;
}

/**
 * @name 自动转换字符集,支持数组转换
 * @param string $fContents
 * @param string $from 源编码
 * @param string $to 期望编码
 * @return string
 *
 * @date 2017-08-24
 **/
function autoCharset($fContents, $from='gbk', $to='utf-8') :string {
    $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
    $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
    if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
        //如果编码相同或者非字符串标量则不转换
        return $fContents;
    }
    if (is_string($fContents)) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($fContents, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $fContents);
        } else {
            return $fContents;
        }
    } elseif (is_array($fContents)) {
        foreach ($fContents as $key => $val) {
            $_key = auto_charset($key, $from, $to);
            $fContents[$_key] = auto_charset($val, $from, $to);
            if ($key != $_key)
                unset($fContents[$key]);
        }
        return $fContents;
    }
    else {
        return $fContents;
    }
}
/**
 * @name 传递百度编辑器ueditor生成的内容获取其中图片的路径
 * @param  string $str 含有图片链接的字符串
 * @return array       匹配的图片数组
 *
 * @date 2017-08-24
 */
function getUeditorImagePath($str){
    $preg='/\/Upload\/image\/u(m)?editor\/\d*\/\d*\.[jpg|jpeg|png|bmp]*/i';
    preg_match_all($preg, $str,$data);
    return current($data);
}
/**
 * 将utf-16的emoji表情转为utf8文字形
 * @param  string $str 需要转的字符串
 * @return string      转完成后的字符串
 *
 * @date 2017-08-24
 */
function escapeEmojiDecode($str) : string {
    $regex = '/\\\u([dD][89abAB][\da-fA-F]{2})\\\u([dD][c-fC-F][\da-fA-F]{2})|\\\u([\da-fA-F]{4})/sx';
    return preg_replace_callback($regex, function($matches) {
        if (isset($matches[3])) {
            $cp = hexdec($matches[3]);
        } else {
            $lead = hexdec($matches[1]);
            $trail = hexdec($matches[2]);
            $cp = ($lead << 10) + $trail + 0x10000 - (0xD800 << 10) - 0xDC00;
        }

        if ($cp > 0xD7FF && 0xE000 > $cp) {
            $cp = 0xFFFD;
        }
        if ($cp < 0x80) {
            return chr($cp);
        } else if ($cp < 0xA0) {
            return chr(0xC0 | $cp >> 6).chr(0x80 | $cp & 0x3F);
        }
        $result =  html_entity_decode('&#'.$cp.';');
        return $result;
    }, $str);
}
/**
 * 删除指定的标签和内容
 * @param array  $tags 需要删除的标签数组
 * @param string $str 数据源
 * @param boolean  $content 是否删除标签内的内容 默认为false保留内容  true不保留内容
 * @return string
 *
 * @date 2017-08-24
 */
function stripHtmlTags ($tags, $str, $content = false) {
    $html=array();
    foreach ($tags as $tag) {
        if($content){
            $html[]='/(<'.$tag.'.*?>[\s|\S]*?<\/'.$tag.'>)/';
        }else{
            $html[]="/(<(?:\/".$tag."|".$tag.")[^>]*>)/i";
        }
    }
    $data=preg_replace($html, '', $str);
    return $data;
}
/**
 * @name 判断是不是中国固话形式
 * @param string $tel 固话号
 * @return string|boolean
 *
 * @author peijiyang
 * @date 2017-09-04
 */
function isTel(string $tel) {
    if (empty($tel)) return false;
    $patter = '/^(0(10|21|22|23|[1-9][0-9]{2})(-|))?[0-9]{7,8}$/';
    return preg_match($patter, $tel) ? $tel : false;
}
/**
 * @name 去重二维数组中指定的下标
 * @param array $arr 待去重复的数组
 * @param string $key 键命
 * @return array
 *
 * @author peijiyang
 * @date 2017-09-04
 */
function array_unset_by_key(array $arr, string $key) : array {
    //建立一个目标数组
    $res = array();
    foreach ($arr as $value) {
        //查看有没有重复项
        if (isset($res[$value[$key]])) {
            //有：销毁
            unset($value[$key]);
        } else {
            $res[$value[$key]] = $value;
        }
    }
    return array_values($res);
}
/**
 * @name 获取浏览器及版本信息
 * @return string
 *
 * @author peijiyang
 * @date 2017-09-04
 * */
function getAgentInfo() : string {
    // Mozilla/5.0 (Windows NT 6.1; Win64; x64)
    // AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36
    $agent  = $_SERVER['HTTP_USER_AGENT'];
    $browser  = '';
    $browser_ver  = '';
    if (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs)) {
        $browser  = 'OmniWeb';
        $browser_ver   = $regs[2];
    }

    if (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs)) {
        $browser  = 'Netscape';
        $browser_ver   = $regs[2];
    }

    if (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
        $browser  = 'Safari';
        $browser_ver   = $regs[1];
    }

    if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
        $browser  = 'Internet Explorer';
        $browser_ver   = $regs[1];
    }

    if (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) {
        $browser  = 'Opera';
        $browser_ver   = $regs[1];
    }

    if (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs)) {
        $browser  = '(Internet Explorer ' .$browser_ver. ') NetCaptor';
        $browser_ver   = $regs[1];
    }

    if (preg_match('/Maxthon/i', $agent, $regs)) {
        $browser  = '(Internet Explorer ' .$browser_ver. ') Maxthon';
        $browser_ver   = '';
    }
    if (preg_match('/360SE/i', $agent, $regs)) {
        $browser       = '(Internet Explorer ' .$browser_ver. ') 360SE';
        $browser_ver   = '';
    }
    if (preg_match('/SE 2.x/i', $agent, $regs)) {
        $browser       = '(Internet Explorer ' .$browser_ver. ') 搜狗';
        $browser_ver   = '';
    }

    if (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
        $browser  = 'FireFox';
        $browser_ver   = $regs[1];
    }

    if (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs)) {
        $browser  = 'Lynx';
        $browser_ver   = $regs[1];
    }

    if (stripos($agent, "Edge") > 0) {
        //win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配
        preg_match("/Edge\/([\d\.]+)/", $agent, $Edge);
        $browser = 'Win 10 Edge';
        $browser_ver = $Edge[1];
    }

    if(preg_match('/Chrome\/([^\s]+)/i', $agent, $regs)){
        $browser  = 'Chrome';
        $browser_ver   = $regs[1];

    }

    $bro = 'unknow';
    if ($browser != '')$bro = $browser . '(' . $browser_ver . ')';
    return $bro;
}

/*
 *  可以使用这个接口获取手机品牌与型号:http://ua.qintech.net/ualookup
    参数：
    ua：自定义 -->浏览器完整的User-Agent信息
    format：json/js -->返回的数据格式
    charset：utf-8(utf8)/gb2312/gbk -->返回的数据格式，默认为utf-8
    {
        "code":1,
        "device":{"type":"1"},
        "os":{"system":"Windows 7"},
        "browser":{"name":"Chrome","version":"60.0.3112.113"},
        "ip":{
                "country":"中国",
                "country_id":"CN",
                "area":"华中",
                "area_id":"400000",
                "region":"河南省",
                "region_id":"410000",
                "city":"郑州市",
                "city_id":"410100",
                "county":"",
                "county_id":"-1",
                "isp":"联通",
                "isp_id":"100026",
                "ip":"115.60.191.234",
                "location":"河南省郑州市 联通"
        }
    }
 *
 * */

/**
 * @name 获得访客操作系统
 * @return string
 *
 * @author peijiyang
 * @date 2017-09-04
 */
function getOS() {
    // Mozilla/5.0 (Windows NT 6.1; Win64; x64)
    // AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36
    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/win/i', $agent) && strpos($agent, '95')){
            $os ='Windows 95';
        } elseif (preg_match('/win 9x/i', $agent) && strpos($agent, '4.90')){
            $os = 'Windows ME';
        } elseif (preg_match('/win/i', $agent) && preg_match('/98/',$agent)){
            $os ='Windows 98';
        } elseif (preg_match('/win/i', $agent) && preg_match('/nt 5.0/i',$agent)){
            $os = 'Windows 2000';
        } elseif (preg_match('/win/i', $agent) && preg_match('/nt 6.0/i',$agent)){
            $os= 'Windows Vista';
        } elseif (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i',$agent)){
            $os = 'Windows 7 X86';
            if (preg_match('/[x64|WOW64]/i', $agent)) $os = 'Windows 7 X64';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent)) {
            $os = 'Windows 8 X86';
            if (preg_match('/[x64|WOW64]/i', $agent)) $os = 'Windows 8 X64';
        } else if(preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent)) {
            $os = 'Windows 10 X86';
            if (preg_match('/[x64|WOW64]/i', $agent)) $os = 'Windows 10 X64';
        }elseif (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i',$agent)){
            $os = 'Windows XP';
        } elseif (preg_match('/win/i', $agent) && preg_match('/nt/i',$agent)){
            $os = 'Windows NT';
        } elseif (preg_match('/win/i', $agent) && preg_match('/32/',$agent)){
            $os = 'Windows 32';
        } elseif (preg_match('/linux/i', $agent)){
            $os = 'Linux';
        } elseif (preg_match('/uni/ix', $agent)){
            $os = 'Unix';
        } elseif (preg_match('/sun/i', $agent) && preg_match('/os/i',$agent)){
            $os = 'SunOS';
        } elseif (preg_match('/ibm/i', $agent) && preg_match('/os/i',$agent)){
            $os = 'IBM OS/2';
        } elseif (preg_match('/Mac/i', $agent) && preg_match('/PC/i',$agent)){
            $os = 'Macintosh';
        } elseif (preg_match('/PowerPC/i', $agent)){
            $os = 'PowerPC';
        } elseif (preg_match('/AIX/i', $agent)){
            $os = 'AIX';
        } elseif (preg_match('/HPUX/i', $agent)){
            $os = 'HPUX';
        } elseif (preg_match('/NetBSD/i', $agent)){
            $os = 'NetBSD';
        } elseif (preg_match('/BSD/i', $agent)){
            $os = 'BSD';
        } elseif (preg_match('/OSF1/i', $agent)){
            $os = 'OSF1';
        } elseif (preg_match('/IRIX/i', $agent)){
            $os = 'IRIX';
        } elseif (preg_match('/FreeBSD/i', $agent)){
            $os = 'FreeBSD';
        } elseif (preg_match('/teleport/i', $agent)) {
            $os = 'teleport';
        } else if (preg_match('/flashget/i', $agent)) {
            $os = 'flashget';
        } else if (preg_match('/webzip/i', $agent)) {
            $os = 'webzip';
        } else if (preg_match('/offline/i', $agent)){
            $os = 'offline';
        }else {
            $os = 'Unknown';
        }
    } else {
        $os = 'Unknown';
    }
    return $os;
}
/**
 * @name 获取服务器状态,仅用于linux
 * @return array
 *
 * @author peijiyang
 * @date 2017-09-05
 * */
function getSysStatus(){
    // -M 单位转为MB -b每两次屏幕信息刷新之间的时间间隔 -n 停止 2 每隔2妙
    $fp = popen('top -M -b -n 2 | grep -E "^(Cpu|Mem|Tasks|Swap)"',"r");//获取某一时刻系统cpu和内存使用情况
    $rs = "";
    while(!feof($fp)){
        $rs .= fread($fp,1024);
    }
    pclose($fp);
    /*
        0 Tasks: 104 total,   1 running, 103 sleeping,   0 stopped,   0 zombie
        1 Cpu(s):  0.2%us,  0.1%sy,  0.0%ni, 99.8%id,  0.0%wa,  0.0%hi,  0.0%si,  0.0%st
        2 Mem:   996.082M total,  925.785M used,   70.297M free,  167.562M buffers
        3 Swap:    0.000k total,    0.000k used,    0.000k free,  497.137M cached
        4 Tasks: 104 total,   1 running, 103 sleeping,   0 stopped,   0 zombie
        5 Cpu(s):  2.3%us,  1.7%sy,  0.0%ni, 96.0%id,  0.0%wa,  0.0%hi,  0.0%si,  0.0%st
        6 Mem:   996.082M total,  926.270M used,   69.812M free,  167.562M buffers
        7 Swap:    0.000k total,    0.000k used,    0.000k free,  497.137M cached

    * */
    /*
     *  4 Tasks: 104 total,   1 running, 103 sleeping,   0 stopped,   0 zombie
        5 Cpu(s):  2.3%us,  1.7%sy,  0.0%ni, 96.0%id,  0.0%wa,  0.0%hi,  0.0%si,  0.0%st
        6 Mem:   996.082M total,  926.270M used,   69.812M free,  167.562M buffers
        7 Swap:    0.000k total,    0.000k used,    0.000k free,  497.137M cached
     * */
    $data = [
        'cpu'           => [],
        'disk'          => [],
        'memory'        => [],
        'swap'          => [],
        'task'          => []
    ];
    $sys_info = explode("\n",$rs);
    $tast_info = explode(',',$sys_info[4]);//进程 数组
    $cpu_info = explode(',', $sys_info[5]);  //CPU占有量  数组
    $mem_info = explode(',', $sys_info[6]); //内存占有量 数组
    $swap_info = explode(',', $sys_info[7]); //内存占有量 数组
    /*
     * Swap:
            192772k total    交换区总量
            0k used    使用的交换区总量
            192772k free    空闲交换区总量
            123988k cached    缓冲的交换区总量,内存中的内容被换出到交换区，而后又被换入到内存，但使用过的交换区尚未被覆盖，该数值即为这些内容已存在于内存中的交换区的大小,相应的内存再次被换出时可不必再对交换区写入。
        7 Swap:    0.000k total,    0.000k used,    0.000k free,  497.137M cached
        Swap
            array (size=4)
                  0 => string 'Swap:    0.000k total' (length=21)
                  1 => string '    0.000k used' (length=15)
                  2 => string '    0.000k free' (length=15)
                  3 => string '  497.137M cached' (length=17)
     * */
    $data['swap']['total'] = trim(trim($swap_info[0], 'Swap:    '), 'k total');// 单位 k
    $data['swap']['total'] = formatDataSize(intval($data['swap']['total']) * 1024);//MB
    $data['swap']['used'] = trim(trim($swap_info[1], 'k used'));// 单位 k
    $data['swap']['used'] = formatDataSize(intval($data['swap']['used']) * 1024);//MB
    $data['swap']['free'] = trim(trim($swap_info[2], 'k used'));// 单位 k
    $data['swap']['free'] = formatDataSize(intval($data['swap']['free']) * 1024);//MB
    $data['swap']['cached'] = trim(trim($swap_info[3], ' cached'));// 单位 MB
    $data['swap']['usage'] = 0.00;
    if ($data['swap']['total'] > 0) $data['swap']['usage'] = (float) round(100 * $data['swap']['used'] / $data['swap']['total'],2);
    //正在运行的进程数
    /*
     *  total 进程总数
        running 正在运行的进程数
        sleeping 睡眠的进程数
        stopped 停止的进程数
        zombie 僵尸进程数
     *  task
            array(5) {
                [0]=> string(16) "Tasks: 104 total"
                [1]=> string(12) " 1 running"
                [2]=> string(13) " 103 sleeping"
                [3]=> string(12) " 0 stopped"
                [4]=> string(11) " 0 zombie"
            }
     * */
    $data['task']['total'] = trim(trim($tast_info[0],'Tasks: '), ' total');
    $data['task']['running'] = trim(trim($tast_info[1],' running'));
    $data['task']['sleeping'] = trim(trim($tast_info[2],' sleeping'));
    $data['task']['stopped'] = trim(trim($tast_info[3],' stopped'));
    $data['task']['zombie'] = trim(trim($tast_info[4],' zombie'));
    //CPU占有量 Cpu(s):  0.3%us,  0.0%sy,  0.0%ni, 99.7%id,  0.0%wa,  0.0%hi,  0.0%si,  0.0%st
    /*
     * Cpu(s):
            0.3%us 用户空间占用CPU百分比
            1.0%sy 内核空间占用CPU百分比
            0.0%ni 用户进程空间内改变过优先级的进程占用CPU百分比
            98.7%id 空闲CPU百分比
            0.0%wa 等待输入输出的CPU时间百分比
            0.0%hi：硬件CPU中断占用百分比
            0.0%si：软中断占用百分比
            0.0%st：虚拟机占用百分比
     * //cpu
            array(8) {
                [0]=> string(15) "Cpu(s): 2.0%us"
                [1]=> string(8) " 1.3%sy"
                [2]=> string(8) " 0.0%ni"
                [3]=> string(8) " 96.7%id"
                [4]=> string(8) " 0.0%wa"
                [5]=> string(8) " 0.0%hi"
                [6]=> string(8) " 0.0%si"
                [7]=> string(8) " 0.0%st"
            }
     * */
    $data['cpu']['us_usage'] = round(trim(trim($cpu_info[0],'Cpu(s): '),'%us'), 2);  //用户空间已使用百分比
    $data['cpu']['sys_usage'] = trim(trim($cpu_info[1],'%sy'));  //内核已使用百分比
    $data['cpu']['free_usage'] = trim(trim($cpu_info[3],'%id'));  //空闲百分比
    $data['cpu']['ni'] = trim(trim($cpu_info[2],'%ni'));
    $data['cpu']['wait_for_io'] = trim(trim($cpu_info[4],'%wa'));
    $data['cpu']['hi'] = trim(trim($cpu_info[5],'%hi'));
    $data['cpu']['si'] = trim(trim($cpu_info[6],'%si'));
    $data['cpu']['st'] = trim(trim($cpu_info[7],'%st'));
    //内存占有量
    /*
     * Mem:
            191272k total    物理内存总量
            173656k used    使用的物理内存总量
            17616k free    空闲内存总量
            22052k buffers    用作内核缓存的内存量
        // mem
            array(4) {
                [0]=> string(21) "Mem: 996.082M total"
                [1]=> string(15) " 930.711M used"
                [2]=> string(15) " 65.371M free"
                [3]=> string(18) " 166.883M buffers"
            }
     * */
    $data['memory']['total'] = trim(trim($mem_info[0],'Mem: '),'M total');// 单位 MB
    $data['memory']['used'] = trim($mem_info[1],'M used');
    $data['memory']['free'] = trim($mem_info[2],'M free');
    $data['memory']['buffers'] = trim($mem_info[2],'M buffers');
    $data['memory']['usage'] = round(100 * intval($data['memory']['used']) / intval($data['memory']['total']),2);  //百分比
    /*硬盘使用率 begin
        //disk
             array(6) {
                [0]=> string(9) "/dev/vda1"
                [1]=> string(3) "40G"
                [2]=> string(4) "5.2G"
                [3]=> string(3) "33G"
                [4]=> string(3) "14%"
                [5]=> string(2) "/ "
            }
    */
    $fp = popen('df -lh | grep -E "^(/)"',"r");
    // /dev/vda1        40G  5.2G   33G  14% /
    $rs = fread($fp,1024);
    pclose($fp);
    $rs = preg_replace("/\s{2,}/",' ',$rs);  //把多个空格换成 “_”
    $hd = explode(" ",$rs);
    $data['disk']['total'] = rtrim($hd[1], 'G'); //磁盘总空间大小 单位G
    $data['disk']['avail'] = rtrim($hd[3], 'G'); //磁盘可用空间大小 单位G
    $data['disk']['used'] = rtrim($hd[2], 'G');//磁盘已经使用空间大小 单位G
    $data['disk']['free'] = rtrim($hd[3], 'G');//磁盘剩余使用空间大小 单位G
    $data['disk']['usage'] = trim($hd[4],'%'); //挂载点 百分比
    //print_r($hd);
    /*硬盘使用率 end*/
    //检测时间
    $fp = popen("date +\"%Y-%m-%d %H:%M\"","r");
    $rs = fread($fp,1024);
    pclose($fp);
    $detection_time = trim($rs);
    return ['data' => $data, 'time' => $detection_time];
}
/**
 * @name 转换数据单位,递归,速度会略慢
 * @param number $b 原始单位
 * @param int $times 级别
 * @return string
 *
 * @author peijiyang
 * @date 2017-09-05
 * */
function formatSize($b, $times=0){
    if($b>1024){
        $temp=$b/1024;
        return formatSize($temp,$times+1);
    }else{
        $unit='B';
        switch($times){
            case '0':$unit='B';break;
            case '1':$unit='KB';break;
            case '2':$unit='MB';break;
            case '3':$unit='GB';break;
            case '4':$unit='TB';break;
            case '5':$unit='PB';break;
            case '6':$unit='EB';break;
            case '7':$unit='ZB';break;
            default: $unit='单位未知';
        }
        return sprintf('%.2f',$b).$unit;
    }
}
/**
 * @name 转换数据单位2
 * @param number $size 原始单位 B
 * @param int $digits 保留几位小数
 * @return string
 *
 * @author peijiyang
 * @date 2017-09-05
 * */
function formatDataSize($size, $digits=2){
    if ($size <= 0) return $size;
    $unit= array('', 'K', 'M', 'G', 'T', 'P', 'E', 'Z');
    $base= 1024;
    $i = floor(log($size, $base));
    $n = count($unit);
    if($i >= $n){
        $i= $n-1;
    }
    return round($size/pow($base, $i), $digits).' '. $unit[$i] . 'B';
}
/**
 * [filterBlank 去掉字符串两边空格]
 * @author         Shaowei Pu <542684913@qq.cn>
 * @CreateTime    2017-02-06T17:31:29+0800
 * @param    string $str [传入字符串]
 * @return   string     [返回trim后的]
 *      $str = 'jkgsdgsgsdgs gsdg gsd';
        echo filterBlank($str);
 */
function filterBlank( $str ) {
    mb_regex_encoding('utf-8');
    $str = mb_ereg_replace('　', '', $str);
    return trim($str);
}

function get_device_type() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];//返回手机系统、型号信息
    $type = 4;
    if(stristr($user_agent,'Android')) {//返回值中是否有Android这个关键字
        //echo '你的手机是：Android系统';
        $type = 2;
    }else if(stristr($user_agent,'iPhone')){
        //echo '你的手机是：IOS系统';
        $type = 3;
    }else{
        $type = 1;
    }
    return $type;
}

//PHP中对汉字进行UNICODE完整的编码和解码
/**
 * 将内容进行UNICODE编码，如中文字符串"图片" 转为"\u56fe\u7247"
 * @param string $name 要转换的中文字符串
 * @param string $in_charset 输入中文编码，默认为uft8
 * @param string $out_charset 输出unicode编码，'UCS-2BE'或'UCS-2LE'
 * Linux 服务器上 UCS-2 编码方式与 Winodws 不一致，linux编码为UCS-2BE，windows为UCS-2LE，即big-endian和little-endian
 * @return string
 */
function unicode_encode($name,$in_charset='UTF-8',$out_charset='UCS-2BE')
{
    $name = iconv($in_charset, $out_charset, $name);
    $len = strlen($name);
    $str = '';
    for ($i = 0; $i < $len - 1; $i = $i + 2){
        $c = $name[$i];
        $c2 = $name[$i + 1];
        if (ord($c) > 0){    // 两个字节的文字
            $str .= '\u'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);
        }
        else{
            $str .= $c2;
        }
    }
    return $str;
}

/**
 * 将UNICODE编码后的内容进行解码，如unicode字符串："\u56fe\u7247" 转为"图片"
 * @param string $name 要转换的unicode字符串
 * @param string $out_charset 输出编码，默认为utf8
 * @param string $in_charset 输入unicode编码，Linux 服务器上 UCS-2 编码方式与 Winodws 不一致，
 * linux编码为UCS-2BE，windows为UCS-2LE，即big-endian和little-endian
 * @return string
 */
function unicode_decode($name,$out_charset='UTF-8',$in_charset='UCS-2BE')
{
    // 转换编码，将Unicode编码转换成可以浏览的utf-8编码
    $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
    preg_match_all($pattern, $name, $matches);
    if (!empty($matches)){
        $name = '';
        for ($j = 0; $j < count($matches[0]); $j++){
            $str = $matches[0][$j];
            if (strpos($str, '\\u') === 0){
                $code = base_convert(substr($str, 2, 2), 16, 10);
                $code2 = base_convert(substr($str, 4), 16, 10);
                $c = chr($code).chr($code2);
                $c = iconv($in_charset, $out_charset, $c);
                $name .= $c;
            }
            else{
                $name .= $str;
            }
        }
    }
    return $name;
}

/**
 * $str 原始中文字符串
 * $encoding 原始字符串的编码，默认utf-8
 * $prefix 编码后的前缀，默认"&#"
 * $postfix 编码后的后缀，默认";"
 */
function unicode_encode2($str, $encoding = 'utf-8', $prefix = '&#', $postfix = ';') {
    //将字符串拆分
    $str = iconv("UTF-8", "gb2312", $str);
    $cind = 0;
    $arr_cont = array();
    for ($i = 0; $i < strlen($str); $i++) {
        if (strlen(substr($str, $cind, 1)) > 0) {
            if (ord(substr($str, $cind, 1)) < 0xA1) { //如果为英文则取1个字节
                array_push($arr_cont, substr($str, $cind, 1));
                $cind++;
            } else {
                array_push($arr_cont, substr($str, $cind, 2));
                $cind+=2;
            }
        }
    }
    foreach ($arr_cont as &$row) {
        $row = iconv("gb2312", "UTF-8", $row);
    }
    //转换Unicode码
    $unicodestr = '';
    foreach ($arr_cont as $key => $value) {
        $unicodestr.= $prefix . base_convert(bin2hex(iconv('utf-8', 'UCS-4', $value)), 16, 10) .$postfix;
    }
    return $unicodestr;
}
/**
 * $str Unicode编码后的字符串
 * $decoding 原始字符串的编码，默认utf-8
 * $prefix 编码字符串的前缀，默认"&#"
 * $postfix 编码字符串的后缀，默认";"
 */
function unicode_decode2($unistr, $encoding = 'utf-8', $prefix = '&#', $postfix = ';') {
    $arruni = explode($prefix, $unistr);
    $unistr = '';
    for ($i = 1, $len = count($arruni); $i < $len; $i++) {
        if (strlen($postfix) > 0) {
            $arruni[$i] = substr($arruni[$i], 0, strlen($arruni[$i]) - strlen($postfix));
        }
        $temp = intval($arruni[$i]);
        $unistr .= ($temp < 256) ? chr(0) . chr($temp) : chr($temp / 256) . chr($temp % 256);
    }
    return iconv('UCS-2', $encoding, $unistr);
}
