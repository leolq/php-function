<?php

/**
 * Notes: 打印输出
 * User: Johnson
 * Date: 2020/5/5
 * Time: 14:20
 * @param $var
 */
function dump($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

/**
 * Notes: api josn输出
 * User: Johnson
 * Date: 2020/5/5
 * Time: 14:27
 * @param $status
 * @param $msg
 * @param array $data
 */
function api_json_return($status, $msg, $data = [])
{
    $return = [
        'status' => $status,
        'msg' => $msg,
    ];
    if ($data) {
        $return['data'] = $data;
    }
    echo json_encode($return);
    die;
}

/**
 * Notes: 获取真实IP
 * User: Johnson
 * Date: 2020/5/5
 * Time: 15:17
 * @return string
 */
function real_ip()
{
    static $realip = null;

    if ($realip !== null) {
        return $realip;
    }

    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
            foreach ($arr as $ip) {
                $ip = trim($ip);
                if ($ip != 'unknown') {
                    $realip = $ip;
                    break;
                }
            }
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            if (isset($_SERVER['REMOTE_ADDR'])) {
                $realip = $_SERVER['REMOTE_ADDR'];
            } else {
                $realip = '0.0.0.0';
            }
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $realip = getenv('HTTP_CLIENT_IP');
        } else {
            $realip = getenv('REMOTE_ADDR');
        }
    }

    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
    $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

    return $realip;
}

/**
 * Notes: curl post请求
 * User: Johnson
 * Date: 2020/5/5
 * Time: 17:49
 * @param $url
 * @param $postData
 * @return bool|string
 */
function curl_post($url, $postData)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_HEADER, false);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}

/**
 * Notes: curl get请求
 * User: Johnson
 * Date: 2020/5/5
 * Time: 17:49
 * @param $url
 * @param $postData
 * @return bool|string
 */
function curl_get($url, $postData)
{
    $uri = $url.'?'.urldecode(http_build_query($postData));

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $uri);
    curl_setopt($curl, CURLOPT_HEADER, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_HEADER, false);

    $data = curl_exec($curl);

    curl_close($curl);
    return $data;
}

/**
 * Notes: 跳转
 * User: Johnson
 * Date: 2020/6/13
 * Time: 14:34
 * @param string $url
 * @param int $time
 * @param string $msg
 */
function redirect($url, $time = 0, $msg = ''){
    $str = <<<EOF
            <html lang="en">
                <head>
                    %s
                    <title>跳转</title>
                    <style>
                        .tips {
                            text-align:center;
                        }
                    </style>
                </head>
                <body>
                    <div>
                        <div class="tips">
                            {$msg}
                        </div>
                        <div class="tips">
                            系统将在<span style="color:red" id="time-num">{$time}</span>秒之后自动跳转到<a href="{$url}">{$url}...</a>
                        </div>
                    </div>
                </body>
                <script>
                    let time = {$time};
                    let countDown = setInterval(function(){
                        time -= 1;
                        if( time <= 0 ){
                            clearInterval(countDown);
                        }
                        document.getElementById("time-num").innerHTML = time
                    }, 1000);
                </script>
            </html>
EOF;
    if(headers_sent()){
        echo sprintf($str, "<meta http-equiv='Refresh' content='{$time};URL={$url}'>");
    }else{
        header("Refresh:{$time};url={$url}");
        if($time > 0) echo sprintf($str, "");
    }
}