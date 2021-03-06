# php-function 收集整理积累的一些PHP常用自定义函数  
> **欢迎提供一些PHP好用的自定义函数**  
> **如有错误的地方欢迎指正**  
> **PHP版本：7.4**  
> **不定时更新**

***

## 导航
- [String相关](#String相关)
- [Array相关](#Array相关)
- [File相关](#File相关)
- [Common相关](#Common相关)

***

### String相关  

函数名称|说明  
-|-
str_gbk($str) | 将utf8字符串编码转为gbk  
str_utf8($str) | 将gbk字符串编码转为utf-8  
str_random($length = 6, $type = 'letter', $convert = 0) | 获取随机字符串  
str_rev($str, $encoding = 'utf-8') | 字符串反转，支持中文  
price_format($num, $decimals = 2) | 格式化数字，默认保留2位小数，四舍五入  
hide_phone($str) | 替换手机号码中间四位数字，13012341234 -> 130****1234
preg_phone($phone) | 验证手机号码是否匹配
preg_email($email) | 验证邮箱是否匹配
str_utf8_unicode_encode($str) | utf8字符串转unicode编码
str_utf8_unicode_decode($str) | unicode编码转utf8字符串


### Array相关  

函数名称|说明  
-|-
object2array($object) | 对象转数组  
array_prepend($array, $value, $key = null) | 数组开头插入元素  
array_random($array, $number = null, $keep_key = false) | 获取随机数组元素  
xml2array($xml, $isfile = false) | xml转数组  
array2xml($data, $root = true) | 数组转xml  
array2Xml_by_dom($arr, $dom = 0, $item = 0) |  数组转xml   
array_filter_reserve_num($arr) |  过滤数组空元素，不包括0  


### File相关

函数名称|说明  
-|-
file_extension($file) | 获取文件扩展名  
file_download($path, $buffer = 1024, $allow_ext = ['jpg', 'png', 'jpeg', 'gif', 'zip']) | 文件分段下载
get_all_files($path, &$files) | 获取目录下所有文件  
get_all_files2($dir) | 获取目录下所有文件，非递归实现  
get_dir_size($dir) | 获取目录大小  
file_size_format($size, $type = 0) | 格式化文件大小  
delete_dir($dir) | 删除文件夹及文件  


### Common相关

函数名称|说明  
-|-
dump($var) | 打印输出  
api_json_return($status, $msg, $data = []) | api josn输出  
real_ip() | 获取真实IP  
curl_post($url, $postData) | curl post请求  
curl_get($url, $postData) | curl get请求  
redirect($url, $time = 0, $msg = '') | 跳转






