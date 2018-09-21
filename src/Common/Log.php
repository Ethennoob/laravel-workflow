<?php

namespace Lingxiang\Workflow\Common;

use Lingxiang\Workflow\Common\Tools;

/**
 * Trait Log 日志类
 * @package Lingxiang\Workflow\Common
 */
trait Log
{
    /**
     * @param $notice_header 日志头
     * @param string $notice_body 日志体
     * @return bool
     */
    public static function write($notice_header,$notice_body=''){

        $notice_header = \GuzzleHttp\json_encode($notice_header,JSON_UNESCAPED_UNICODE);

        $backtrace = debug_backtrace();
        array_shift($backtrace);

        //获取调用日志类的方法和类名
        $module_name = explode('\\',$backtrace[0]['class'])[2].'/'.$backtrace[0]['function'];

        $path = storage_path('workflow');

        Tools::dir_exists($path);

        $date_str = date('Y-m-d');
        $date_fmt = date('Y-m-d H:i:s',time());
        $path = $path.'/'.$module_name.'/';

        Tools::dir_exists($path);

        $filepath = $path.$date_str.'.log';

        if(!$fp = @fopen($filepath, 'ab')){
            return false;
        }

        $message = $date_fmt.'::'.$notice_header.'::'.$notice_body."\n";

        flock($fp, LOCK_EX);
        fwrite($fp, $message);
        flock($fp, LOCK_UN);
        fclose($fp);
        @chmod($filepath, 0755);
        return true;
    }

}