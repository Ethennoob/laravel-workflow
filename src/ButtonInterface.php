<?php
/**
 * Created by PhpStorm.
 * User: 凌翔
 * Date: 2018/8/28
 * Time: 下午7:35
 */

namespace Lingxiang\Workflow;


interface ButtonInterface
{
    //设置任务id
    public function setTaskId($id);

    //保存
    public function save();

    //提交
    public function submit();

    //撤回
    public function recall();

    //退回
    public function goback();

    //取消
    public function cancel();

    //完结
    public function alldone();
}