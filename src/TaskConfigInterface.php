<?php

namespace Lingxiang\Workflow;


interface TaskConfigInterface
{

    //  所有任务配置数据
    public function taskConfigGetList($data, $is_paginate, $per_page);

    //  一条任务配置数据
    public function taskConfigGetOne($id);

    //  新增任务配置数据
    public function taskConfigStore($data);

    //  修改任务配置数据
    public function taskConfigUpdate($data, $id);

    //  删除任务配置数据(is_on字段置0)
    public function taskConfigDelete($id);

}