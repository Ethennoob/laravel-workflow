<?php
/**
 * Created by PhpStorm.
 * User: 凌翔
 * Date: 2018/9/3
 * Time: 下午12:22
 */

namespace Lingxiang\Workflow;

use Lingxiang\Workflow\Common\WorkflowException;
use Lingxiang\Workflow\Common\Log;

/**
 * 任务实例
 * Class Task
 * @package Lingxiang\Workflow
 */
class Task extends Operation
{
    public $id;
    public $type;   // 任务类型个人资料收集，定校，文案  1 2 3
    public $customer_id;
    public $contact_id;
    public $node;
    public $owner_id;
    public $owner_type;
    public $user_limit_time;
    public $time;
    public $errorMsg;
    //...

    public function __construct()
    {
        $this->time = time();
    }

    public function info($id)
    {
        //查询任务实例
        $data = \DB::table('task')->find($id);

        if (empty($data)){
            throw new WorkflowException('任务不存在');
        }

        $this->id = $id;

        //查询任务类型信息
        $task_type = \DB::table('task_type')->find($data->type);

        if (empty($task_type)){
            throw new WorkflowException('任务错误');
        }

        $data->type_name = $task_type->name;
        $data->instruction = $task_type->instruction;

        return $data;
    }

    /**
     * 设置任务类型
     * @param $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * 设置任务客户id
     * @param $customer_id
     * @return $this
     */
    public function setCustomerId($customer_id)
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    /**
     * 设置任务合同id
     * @param $contact_id
     * @return $this
     */
    public function setContractId($contact_id)
    {
        $this->contact_id = $contact_id;

        return $this;
    }

    /**
     * 设置任务节点状态
     * @param int $node
     * @return $this
     */
    public function setNode($node = 5)
    {
        $this->node = $node;

        return $this;
    }

    /**
     * 设置任务来源id
     * @param $owner_id
     * @return $this
     */
    public function setOwnerId($owner_id)
    {
        $this->owner_id = $owner_id;

        return $this;
    }

    /**
     * 设置任务来源类型
     * 1客户2顾问
     * @param $owner_type
     * @return $this
     */
    public function setOwnerType($owner_type)
    {
        $this->owner_type = $owner_type;

        return $this;
    }

    /**
     * 设置该任务环节用户的完成截止时间
     * @param $user_limit_time
     * @return $this
     */
    public function setUserLimitTime($user_limit_time)
    {
        $this->user_limit_time = $user_limit_time;

        return $this;
    }

    /**
     * 生成任务实例
     * @return int 任务id
     * @throws WorkflowException
     */
    public function create(){
        //查询该任务类型的完成时间
        $task_type = \DB::table('task_type')->find($this->type);

        if (empty($task_type)){
            throw new WorkflowException('任务类型错误!');
        }

        //计算完成时间
        $complete_time = time() + 86400 * $task_type->deadline;

        $data = [
            'type' => $this->type,
            'node' => $this->node,
            'owner_id' => $this->owner_id,
            'owner_type' => $this->owner_type,
            'user_limit_time' => isset($this->user_limit_time)?$this->user_limit_time:$complete_time,
            'complete_time' => $complete_time,
            'created_at' => $this->time,
            'updated_at' => $this->time,
        ];

        //写入task任务实例表
        $task_id = \DB::table('task')->insertGetId($data);

        $data['id'] = $task_id;
        Log::write($data,'任务实例生成');

        return $task_id;
    }

    public function update()
    {

    }

    /**
     * 生成配置对象实例
     *
     * @param $table
     * @return TaskButton|TaskRole|TaskRoleAuth|TaskTypeAuth
     * @throws WorkflowException
     */
    public function getTaskConfig($table)
    {
        $task_config = \Lingxiang\Workflow\TaskConfigFactory::createObject($table);

        return $task_config;
    }


    /**
     * 获取当前节点当前角色所拥有的按钮
     *
     * @param $task_id
     * @param $role_id
     * @return \Illuminate\Support\Collection
     * @throws WorkflowException
     */
    public function getButtons($task_id, $role_id)
    {
        $task =\DB::table('task')
            ->select(['id','node'])
            ->where('is_on', 1)
            ->find($task_id);
        if (!$task) {
            throw new WorkflowException('任务不存在');
        }

        $task_button_auth =\DB::table('task_button_auth')
            ->select(['id','button_id'])
            ->where('is_on', 1)
            ->where('node', $task->node)
            ->where('auth_role', $role_id)
            ->get();

        $task_button_auth->each(function ($item) {

            $task_button = \App\Model\TaskButtonModel::select(['id','type'])
                ->where('is_on', 1)
                ->find($item->button_id);

            $item->button_name = $task_button->type;
        });

        return $task_button_auth;
    }
}
