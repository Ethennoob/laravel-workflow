<?php
namespace Lingxiang\Workflow\Common;

class Tools
{
    /**
     * 检查目录且生成对应的目录
     * @param $path
     * @return bool
     */
    public static function dir_exists($path)
    {
        $f = true;
        if (file_exists($path) == false) {//创建目录
            if (mkdir($path, 0777, true) == false)
                $f = false;
            else if (chmod($path, 0777) == false)
                $f = false;
        }

        return $f;
    }

}