<?php
/**
 * Created by PhpStorm.
 * User: lingxiang
 * Date: 2018/8/28
 * Time: 下午7:01
 */

namespace Lingxiang\Workflow;

use Lingxiang\Workflow\Common\WorkflowException;
use Lingxiang\Workflow\Common\Log;

class Operation implements ButtonInterface
{
    protected $task_id;
    protected $task_instance;
    protected $role_id_arr;
    protected $role;

    /**
     * 设置任务id
     * @param $id
     * @return $this
     */
    public function setTaskId($id)
    {
        $this->task_id = $id;

        $this->task_instance = \DB::table('task')->find($this->task_id);

        return $this;
    }

    /**
     * 设置操作者角色
     * @param array $role 一维数组
     * @return $this
     * @throws WorkflowException
     */
    public function setRole(array $role = array())
    {
        if (empty($role)){
            throw new WorkflowException('无权限操作');
        }

        $this->role = $role;

        $this->role_id_arr = \DB::table('task_role')->whereIn('role',$role)->get()
            ->map(function($item){
                return $item->id;
            })->toArray();

        return $this;
    }

    /**
     * 保存
     * @param array $data 保存的数据,供记录日志
     * @return bool
     */
    public function save(array $data = array())
    {
        Log::write($data,'保存，任务id:'.$this->task_id);
        return true;
    }

    /**
     * 提交
     * @param array $data
     * @param callable|null $callback 回调函数
     * @return bool
     * @throws WorkflowException
     */
    public function submit(array $data = [], callable $callback = null)
    {
        $button_id = 2;//定死

        // 查询该按钮是否有权限
        $direction_node = \DB::table('task_button_auth')->where('button_id',$button_id)
            ->whereIn('auth_role',$this->role_id_arr)
            ->where('node',$this->task_instance->node)
            ->value('direction_node');

        if (!$direction_node){
            throw new WorkflowException('无权限操作');
        }

        //将节点状态改变
        //\DB::table('task')->where('id',$this->task_id)->update([
        //    'node' => $direction_node,
        //    'updated_at' => $this->time
        //]);

        //查询该节点方向信息中的提醒消息角色id
        $notice = \DB::table('task_node_direction')->where('task_type',$this->task_instance->type)
            ->where('node',$this->task_instance->node)
            ->where('direction_node',$this->task_instance->direction_node)
            ->where('button_id',$button_id)
            ->first();

        if (!empty($notice) && !empty($notice->notice_role)){
            $notice_role = explode(';',$notice->notice_role);
        }else{
            $notice_role = [];
        }

        Log::write($this->role,'提交，任务id:'.$this->task_id);

        //回调
        if ($callback){
            call_user_func_array($callback,[$direction_node,$notice_role]);
        }

        return true;
    }

    /**
     * 撤回
     * @param callable|null $callback
     * @throws WorkflowException
     */
    public function recall(callable $callback = null)
    {
        $button_id = 5;//定死

        // 查询该按钮是否有权限
        $direction_node = \DB::table('task_button_auth')->where('button_id',$button_id)
            ->whereIn('auth_role',$this->role_id_arr)
            ->where('node',$this->task_instance->node)
            ->value('direction_node');

        if (!$direction_node){
            throw new WorkflowException('无权限操作');
        }

        //将节点状态改变
        \DB::table('task')->where('id',$this->task_id)->update([
            'node' => $direction_node,
            'updated_at' => $this->time
        ]);

        //查询该节点方向信息中的提醒消息角色id
        $notice = \DB::table('task_node_direction')->where('task_type',$this->task_instance->type)
            ->where('node',$this->task_instance->node)
            ->where('direction_node',$this->task_instance->direction_node)
            ->where('button_id',$button_id)
            ->first();

        if (!empty($notice) && !empty($notice->notice_role)){
            $notice_role = explode(';',$notice->notice_role);
        }else{
            $notice_role = [];
        }

        Log::write($this->role,'撤回，任务id:'.$this->task_id);

        //回调
        if ($callback){
            call_user_func($callback,[$direction_node,$notice_role]);
        }
    }

    /**
     * 退回
     * @param callable|null $callback
     * @throws WorkflowException
     */
    public function goback(callable $callback = null)
    {
        $button_id = 6;//定死

        // 查询该按钮是否有权限
        $direction_node = \DB::table('task_button_auth')->where('button_id',$button_id)
            ->whereIn('auth_role',$this->role_id_arr)
            ->where('node',$this->task_instance->node)
            ->value('direction_node');

        if (!$direction_node){
            throw new WorkflowException('无权限操作');
        }

        //将节点状态改变
        \DB::table('task')->where('id',$this->task_id)->update([
            'node' => $direction_node,
            'updated_at' => $this->time
        ]);

        //查询该节点方向信息中的提醒消息角色id
        $notice = \DB::table('task_node_direction')->where('task_type',$this->task_instance->type)
            ->where('node',$this->task_instance->node)
            ->where('direction_node',$this->task_instance->direction_node)
            ->where('button_id',$button_id)
            ->first();

        if (!empty($notice) && !empty($notice->notice_role)){
            $notice_role = explode(';',$notice->notice_role);
        }else{
            $notice_role = [];
        }

        Log::write($this->role,'退回，任务id:'.$this->task_id);

        //回调
        if ($callback){
            call_user_func($callback,[$direction_node,$notice_role]);
        }
    }

    /**
     * 取消
     * @param callable|null $callback
     * @throws WorkflowException
     */
    public function cancel(callable $callback = null)
    {
        $button_id = 6;//定死

        // 查询该按钮是否有权限
        $direction_node = \DB::table('task_button_auth')->where('button_id',$button_id)
            ->whereIn('auth_role',$this->role_id_arr)
            ->where('node',$this->task_instance->node)
            ->value('direction_node');

        if (!$direction_node){
            throw new WorkflowException('无权限操作');
        }

        //将节点状态改变
        \DB::table('task')->where('id',$this->task_id)->update([
            'node' => $direction_node,
            'updated_at' => $this->time
        ]);

        //查询该节点方向信息中的提醒消息角色id
        $notice = \DB::table('task_node_direction')->where('task_type',$this->task_instance->type)
            ->where('node',$this->task_instance->node)
            ->where('direction_node',$this->task_instance->direction_node)
            ->where('button_id',$button_id)
            ->first();

        if (!empty($notice) && !empty($notice->notice_role)){
            $notice_role = explode(';',$notice->notice_role);
        }else{
            $notice_role = [];
        }

        Log::write($this->role,'取消，任务id:'.$this->task_id);

        //回调
        if ($callback){
            call_user_func($callback,[$direction_node,$notice_role]);
        }
    }

    /**
     * 催办
     * @param callable|null $callback
     */
    public function reminder(callable $callback = null)
    {
        Log::write($this->role,'催办，任务id:'.$this->task_id);

        //回调
        if ($callback){
            call_user_func($callback);
        }
    }

    public function forward()
    {
        // TODO: Implement reminder() method.
    }

    public function proxy()
    {
        // TODO: Implement reminder() method.
    }

}