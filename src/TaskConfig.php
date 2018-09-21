<?php

namespace Lingxiang\Workflow;

use Lingxiang\Workflow\Common\WorkflowException;

abstract class TaskConfig implements TaskConfigInterface
{
    protected $table;

    protected $check_table;

    protected $time;

<<<<<<< HEAD
    protected $task_type;

=======
>>>>>>> 898065c11dc21184310fef1d6c359bb7744202e9
    public function __construct($table)
    {
        $this->table = \DB::table($table);

        $this->check_table = \DB::table($table);

        $this->time = time();
    }


    /**
<<<<<<< HEAD
     * 设置任务类型
     *
     * @param $task_type
     * @return $this
     */
    public function setTaskType($task_type)
    {
        $this->task_type = $task_type;

        return $this;
    }

    /**
=======
>>>>>>> 898065c11dc21184310fef1d6c359bb7744202e9
     * 所有任务配置数据
     *
     * @param array $data
     * @param bool $is_paginate
     * @param int $per_page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function taskConfigGetList($data = [], $is_paginate = false, $per_page = 15)
    {
        if (isset($data)) {
            $this->setWhereData($data, $this->table);
        }

        $this->table->where('is_on', 1);

        $list = $is_paginate ? $this->table->paginate($per_page) : $this->table->get();

        return $list;
    }


    /**
     * 一条任务配置数据
     *
     * @param $id
     * @return mixed|static
     * @throws WorkflowException
     */
    public function taskConfigGetOne($id)
    {
        $data = $this->table->where('is_on', 1)->find($id);
        if (!$data) {
            throw new WorkflowException('查看的记录不存在');
        }

        return $data;
    }


    /**
     * 新增任务配置数据
     *
     * @param $data
     * @return \Illuminate\Database\Query\Builder|int
     * @throws WorkflowException
     */
    public function taskConfigStore($data)
    {
        if (!is_array($data)) {
            throw new WorkflowException('添加参数必须为数组');
        }

        $data = array_merge($data, [
            'created_at' => $this->time,
            'updated_at' => $this->time,
            'is_on' => 1
        ]);

        $insert_id = $this->table->insertGetId($data);

        return $insert_id; //  返回自增id
    }


    /**
     * 修改任务配置数据
     *
     * @param $data
     * @param $id
     * @return int
     * @throws WorkflowException
     */
    public function taskConfigUpdate($data, $id)
    {
        if (!is_array($data)) {
            throw new WorkflowException('添加参数必须为数组');
        }

        $this->check_table = $this->check_table->where('is_on', 1)->find($id);
        if (!$this->check_table) {
            throw new WorkflowException('修改的记录不存在');
        }

        $data = array_merge($data, [
            'updated_at' => $this->time
        ]);

        $res = $this->table->where('id', $id)->update($data);

        return $res; //  返回 1:成功 0:失败
    }


    /**
     * 删除任务配置数据(is_on字段置0)
     *
     * @param $id
     * @return int
     * @throws WorkflowException
     */
    public function taskConfigDelete($id)
    {
        $this->check_table = $this->check_table->where('is_on', 1)->find($id);
        if (!$this->check_table) {
            throw new WorkflowException('删除的记录不存在');
        }

        $data = [
            'updated_at' => $this->time,
            'is_on' => 0
        ];

        $res = $this->table->where('id', $id)->update($data);

        return $res; //  返回 1:成功 0:失败
    }


    /**
     * 设置where查询(有无返回值均可)
     *
     * @param $data
     * @param $table
     * @return mixed
     */
    public function setWhereData($data, &$table)
    {
        foreach ($data as $k => $v) {
            $table->where($k, $v);
        }

        return $table;
    }
}