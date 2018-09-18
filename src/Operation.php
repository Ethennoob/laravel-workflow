<?php
/**
 * Created by PhpStorm.
 * User: lingxiang
 * Date: 2018/8/28
 * Time: 下午7:01
 */

namespace Lingxiang\Workflow;

use Lingxiang\Workflow\Common\Log;

class Operation implements ButtonInterface
{
    protected $task_id;

    public function setTaskId($id)
    {
        $this->task_id = $id;

        return $this;
    }

    public function save()
    {
        $list = array('name'=>'测试保存');
        Log::write($list,'保存');
        return true;
    }

    public function submit($data = [])
    {
        // TODO: Implement submit() method.
    }

    public function recall()
    {
        // TODO: Implement recall() method.
    }

    public function goback()
    {
        // TODO: Implement goback() method.
    }

    public function cancel()
    {
        // TODO: Implement cancel() method.
    }

    public function alldone()
    {
        // TODO: Implement alldone() method.
    }

}