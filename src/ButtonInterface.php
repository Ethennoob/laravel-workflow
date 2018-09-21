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

    //设置角色
    public function setRole(array $role);

    //保存
    public function save(array $data);

    //提交
    public function submit(array $data,callable $callback);

    //撤回
    public function recall(callable $callback);

    //退回
    public function goback(callable $callback);

    //取消
    public function cancel(callable $callback);

    //催办
    public function reminder(callable $callback);

    //转发
    public function forward();

    //代理
    public function proxy();
}