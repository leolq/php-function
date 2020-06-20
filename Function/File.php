<?php

/**
 * Notes: 获取文件扩展名
 * User: Johnson
 * Date: 2020/5/5
 * Time: 16:42
 * @param $file
 * @return bool|string
 */
function file_extension($file)
{
    return strtolower(substr($file, strripos($file, ".") + 1));
}

/**
 * Notes: 文件分段下载
 * User: Johnson
 * Date: 2020/6/13
 * Time: 10:04
 * @param string $path
 * @param int $buffer
 * @param array[] $allow_ext
 * @return bool
 */
function file_download($path, $buffer = 1024, $allow_ext = ['jpg', 'png', 'jpeg', 'gif', 'zip'])
{
    if (!is_file($path) && !is_readable($path)) {
        return false;
    }
    if (!in_array(file_extension($path), $allow_ext)) {
        return false;
    }
    $filesize = filesize($path);

    header("Content-Type:application/octet-stream");
    header("Accept-Ranges:bytes");
    header("Content-Length:{$filesize}");
    header('Content-Disposition:attachment; filename=' . basename($path));

    $handle = fopen($path, 'rb');
    while (!feof($handle)) {
        echo fread($handle, $buffer);
    }
    fclose($handle);
    exit;
}

/**
 * Notes: 获取目录下所有文件
 * User: Johnson
 * Date: 2020/5/5
 * Time: 17:07
 * @param $path
 * @param $files
 */
function get_all_files($path, &$files)
{
    if (is_dir($path)) {
        $dp = dir($path);
        while ($file = $dp->read()) {
            if ($file != "." && $file != "..") {
                get_all_files($path . "/" . $file, $files);
            }
        }
        $dp->close();
    }
    if (is_file($path)) {
        $files[] = $path;
    }
}

/**
 * Notes: 获取目录下所有文件，非递归实现
 * User: Johnson
 * Date: 2020/5/5
 * Time: 19:58
 * @param $dir
 * @return array
 */
function get_all_files2($dir)
{
    if (!is_dir($dir)) return array();
    $dir = rtrim(str_replace('\\', '/', $dir), '/') . '/';

    $dirs = array($dir);
    $rt = array();
    do {
        $dir = array_pop($dirs);
        $tmp = scandir($dir);
        foreach ($tmp as $f) {
            if ($f == '.' || $f == '..') {
                continue;
            }

            $path = $dir . $f;
            if (is_dir($path)) {
                array_push($dirs, $path . '/');
            } elseif (is_file($path)) {
                $rt [] = $path;
            }
        }
    } while ($dirs);
    return $rt;
}

/**
 * Notes: 获取目录大小
 * User: Johnson
 * Date: 2020/5/5
 * Time: 17:43
 * @param $dir
 * @return false|int
 */
function get_dir_size($dir)
{
    $size = 0;
    if (is_dir($dir)) {
        $dh = opendir($dir);
        while (false !== ($file = @readdir($dh))) {
            if ($file != '.' and $file != '..') {
                $path = $dir . '/' . $file;
                if (is_dir($path)) {
                    $size += get_dir_size($path);
                } elseif (is_file($path)) {
                    $size += filesize($path);
                }
            }
        }
        closedir($dh);
    }
    return $size;
}

/**
 * Notes: 格式化文件大小
 * User: Johnson
 * Date: 2020/5/5
 * Time: 17:22
 * @param $size
 * @param int $type 0:自动 1:字节 2:KB 3:MB 4:GB 5:TB
 * @return string
 */
function file_size_format($size, $type = 0)
{
    if ($type == 0) {
        if ($size > pow(2, 40)) {
            $unit = "TB";
            $size = $size / pow(2, 40);
        } else if ($size > pow(2, 30)) {
            $unit = "GB";
            $size = $size / pow(2, 30);
        } else if ($size > pow(2, 20)) {
            $unit = "MB";
            $size = $size / pow(2, 20);
        } else if ($size > pow(2, 10)) {
            $unit = "KB";
            $size = $size / pow(2, 10);
        } else {
            $unit = "bytes";
        }
    } else {
        switch ($type) {
            case 1:
            default:
                $unit = "bytes";
                break;

            case 2:
                $unit = "KB";
                $size = $size / pow(2, 10);
                break;

            case 3:
                $unit = "MB";
                $size = $size / pow(2, 20);
                break;

            case 4:
                $unit = "GB";
                $size = $size / pow(2, 30);
                break;

            case 5:
                $unit = "TB";
                $size = $size / pow(2, 40);
                break;
        }
    }

    return round($size, 2) . $unit;
}

/**
 * Notes: 删除文件夹及文件
 * User: Johnson
 * Date: 2020/5/5
 * Time: 18:37
 * @param $dir
 * @return bool
 */
function delete_dir($dir)
{
    if (!is_dir($dir)) {
        return false;
    }
    if ($handle = opendir("$dir")) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                if (is_dir("$dir/$item")) {
                    delete_dir("$dir/$item");
                } else {
                    unlink("$dir/$item");
                }
            }
        }
        closedir($handle);
        rmdir($dir);
    }
    return true;
}