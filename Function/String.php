<?php

/**
 * Notes: 将utf8字符串编码转为gbk
 * User: Johnson
 * Date: 2020/5/5
 * Time: 14:15
 * @param $str
 * @return false|string
 */
function str_gbk($str)
{
    return iconv('utf-8', 'gbk', $str);
}

/**
 * Notes: 将gbk字符串编码转为utf-8
 * User: Johnson
 * Date: 2020/5/5
 * Time: 14:17
 * @param $str
 * @return false|string
 */
function str_utf8($str)
{
    return iconv('gbk', 'utf-8', $str);
}

/**
 * Notes: 获取随机字符串
 * User: Johnson
 * Date: 2020/5/5
 * Time: 16:02
 * @param int $length
 * @param string $type
 * @param int $convert -1:小写 0:大小写 1:大写
 * @return string
 */
function str_random($length = 6, $type = 'letter', $convert = 0)
{
    $config = [
        'number' => '1234567890',
        'letter' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'string' => 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789',
        'all' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    ];

    if (!isset($config[$type])) {
        $type = 'letter';
    }
    $string = $config[$type];

    $code = '';
    $strlen = strlen($string) - 1;
    for ($i = 0; $i < $length; $i++) {
        $code .= $string{mt_rand(0, $strlen)};
    }
    if ($convert != 0) {
        $code = ($convert > 0) ? strtoupper($code) : strtolower($code);
    }
    return $code;
}

/**
 * Notes: 字符串反转，支持中文
 * User: Johnson
 * Date: 2020/5/5
 * Time: 16:10
 * @param $str
 * @param string $encoding
 * @return string
 */
function str_rev($str, $encoding = 'utf-8')
{
    $newstr = '';
    $len = mb_strlen($str);
    for ($i = $len - 1; $i >= 0; $i--) {
        $newstr .= mb_substr($str, $i, 1, $encoding);
    }
    return $newstr;
}

/**
 * Notes: 格式化数字，默认保留2位小数，四舍五入
 * User: Johnson
 * Date: 2020/5/5
 * Time: 14:53
 * @param $num
 * @param int $decimals
 * @return string
 */
function price_format($num, $decimals = 2)
{
    return number_format($num, $decimals, '.', '');
}

/**
 * Notes: 替换手机号码中间四位数字
 * User: Johnson
 * Date: 2020/5/22
 * Time: 16:04
 * @param string $str
 * @return string
 */
function hide_phone($str)
{
    $resstr = substr_replace($str, '****', 3, 4);
    return $resstr;
}

/**
 * Notes: 验证手机号码是否匹配
 * User: Johnson
 * Date: 2020/5/22
 * Time: 17:04
 * @param string $phone
 * @return bool
 */
function preg_phone($phone)
{
    return preg_match('/^1[3-9]{2}\d{8}$/', $phone) ? true : false;
}

/**
 * Notes: 验证邮箱是否匹配
 * User: Johnson
 * Date: 2020/5/22
 * Time: 17:08
 * @param string $email
 * @return bool
 */
function preg_email($email)
{
    return preg_match("/^[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)*@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)*\.[a-z]{2,}$/", $email) ? true : false;
}

/**
 * Notes: utf8字符串转unicode编码
 * User: Johnson
 * Date: 2020/6/13
 * Time: 11:26
 * @param string $str
 * @return string
 */
function str_utf8_unicode_encode($str)
{
    $str = iconv('UTF-8', 'UCS-2BE', $str);
    $len = strlen($str);
    $unicode = '';
    for ($i = 0; $i < $len - 1; $i = $i + 2) {
        $c = $str[$i];
        $c2 = $str[$i + 1];
        if (ord($c) > 0) {
            $unicode .= '\u' . base_convert(ord($c), 10, 16) . str_pad(base_convert(ord($c2), 10, 16), 2, 0, STR_PAD_LEFT);
        } else {
            $unicode .= '\u' . str_pad(base_convert(ord($c2), 10, 16), 4, 0, STR_PAD_LEFT);
        }
    }
    return $unicode;
}

/**
 * Notes: unicode编码转utf8字符串
 * User: Johnson
 * Date: 2020/6/13
 * Time: 11:33
 * @param string $str
 * @return string
 */
function str_utf8_unicode_decode($str)
{
    $str = strtolower($str);
    $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
    preg_match_all($pattern, $str, $matches);
    if (!empty($matches)) {
        $decode = '';
        for ($j = 0; $j < count($matches[0]); $j++) {
            $res = $matches[0][$j];
            if (strpos($res, '\\u') === 0) {
                $code = base_convert(substr($res, 2, 2), 16, 10);
                $code2 = base_convert(substr($res, 4), 16, 10);
                $c = chr($code) . chr($code2);
                $c = iconv('UCS-2BE', 'UTF-8', $c);
                $decode .= $c;
            } else {
                $decode .= $res;
            }
        }
    }
    return $decode;
}




