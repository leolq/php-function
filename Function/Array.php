<?php

/**
 * Notes: 对象转数组
 * User: Johnson
 * Date: 2020/5/5
 * Time: 14:58
 * @param $object
 * @return false|array
 */
function object2array($object)
{
    if (!is_object($object)) {
        return false;
    }
    return json_decode(json_encode($object), true);
}

/**
 * Notes: 数组开头插入元素
 * User: Johnson
 * Date: 2020/5/5
 * Time: 17:56
 * @param $array
 * @param $value
 * @param null $key
 * @return array
 */
function array_prepend($array, $value, $key = null)
{
    if (is_null($key)) {
        array_unshift($array, $value);
    } else {
        $array = [$key => $value] + $array;
    }
    return $array;
}

/**
 * Notes: 获取随机数组元素
 * User: Johnson
 * Date: 2020/5/5
 * Time: 18:11
 * @param $array
 * @param null $number
 * @param bool $keep_key false:不保留键名 true:保留键名
 * @return array|mixed
 */
function array_random($array, $number = null, $keep_key = false)
{
    $requested = is_null($number) ? 1 : $number;
    $count = count($array);
    if ($requested > $count) {
        return $array;
    }

    if ($requested == 1) {
        return $array[array_rand($array)];
    }

    if ((int)$number === 0) {
        return [];
    }

    $keys = array_rand($array, $number);
    $results = [];
    foreach ($keys as $key) {
        if ($keep_key == false) {
            $results[] = $array[$key];
        } else {
            $results[$key] = $array[$key];
        }
    }

    return $results;
}

/**
 * Notes: xml转数组
 * User: Johnson
 * Date: 2020/5/5
 * Time: 18:43
 * @param $xml
 * @param bool $isfile
 * @return bool|mixed
 */
function xml2array($xml, $isfile = false)
{
    //禁止引用外部xml实体
    libxml_disable_entity_loader(true);
    if ($isfile) {
        if (!file_exists($xml)) {
            return false;
        }
        $xmlstr = file_get_contents($xml);
    } else {
        $xmlstr = $xml;
    }
    $result = json_decode(json_encode(simplexml_load_string($xmlstr, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $result;
}


/**
 * Notes: 数组转xml
 * User: Johnson
 * Date: 2020/5/5
 * Time: 18:56
 * @param $data
 * @param bool $root
 * @return string
 */
function array2xml($data, $root = true)
{
    $str = "";
    if ($root) $str .= "<xml>";
    foreach ($data as $key => $val) {
        //去掉key中的下标[]
        $key = preg_replace('/\[\d*\]/', '', $key);
        if (is_array($val)) {
            $child = array2xml($val, false);
            $str .= "<$key>$child</$key>";
        } else {
            $str .= "<$key><![CDATA[$val]]></$key>";
        }
    }
    if ($root) $str .= "</xml>";
    return $str;
}

/**
 * Notes: 数组转xml
 * User: Johnson
 * Date: 2020/5/5
 * Time: 19:49
 * @param $arr
 * @param int $dom
 * @param int $item
 * @return string
 */
function array2Xml_by_dom($arr, $dom = 0, $item = 0)
{
    if (!$dom) {
        $dom = new DOMDocument("1.0");
    }
    if (!$item) {
        $item = $dom->createElement("root");
        $dom->appendChild($item);
    }
    foreach ($arr as $key => $val) {
        $itemx = $dom->createElement(is_string($key) ? $key : "item");
        $item->appendChild($itemx);
        if (!is_array($val)) {
            $text = $dom->createCDATASection($val);
            $itemx->appendChild($text);
        } else {
            array2Xml_by_dom($val, $dom, $itemx);
        }
    }
    return $dom->saveXML();
}