<?php

namespace Lingxiang\Workflow;

<<<<<<< HEAD
use Lingxiang\Workflow\Common\WorkflowException;

class TaskButtonAuth extends TaskConfig
{
    private $auth_role;

    public function setAuthRole($auth_role)
    {
        $this->auth_role = $auth_role;

        return $this;
    }

    public function taskConfigStoreMany($data)
    {
        //  参数格式如下
//        $data = [
//            [
//                5 => [ //  5表示节点
//                    0 => [
//                        'direaction_node' => 10,
//                        'button_id' => 1
//                    ],
//                    1 => [
//                        'direaction_node' => 10,
//                        'button_id' => 2
//                    ],
//                    2 => [
//                        'direaction_node' => 20,
//                        'button_id' => 1
//                    ],
//                ],
//            ],
//        ];

        if (!is_array($data)) {
            throw new WorkflowException('添加参数必须为数组');
        }

        $insert_data = [];
        foreach ($data as $k => $v) {

            foreach ($v as $node_k => $node_v) {

                foreach ($node_v as $item) {

                    $button_limit_ids = \DB::table('task_node_direction')
                        ->select(['id','button_id'])
                        ->where('is_on', 1)
                        ->groupBy('button_id')
                        ->get()
                        ->pluck('button_id')
                        ->toArray();

                    $direction_node = $node_k;

                    if (in_array($item['button_id'], $button_limit_ids)) {
                        $task_node_direction = \DB::table('task_node_direction')
                            ->select(['id','node','direction_node'])
                            ->where('is_on', 1)
                            ->where('task_type', $this->task_type)
                            ->where('node', $node_k)
                            ->where('button_id', $item['button_id'])
                            ->first();

                        $direction_node = $task_node_direction->direction_node;
                    }

                    array_push($insert_data, [
                        'task_type' => $this->task_type,
                        'node' => $node_k,
                        'button_id' => $item['button_id'],
                        'auth_role' => $this->auth_role,
                        'direction_node' => $direction_node,
                        'created_at' => $this->time,
                        'updated_at' => $this->time,
                        'is_on' => 1
                    ]);
                }
            }
        }

        foreach ($insert_data as $id_k => $id_v) {
            $res = $this->table->insert($id_v);
            if (!$res) {
                \DB::rollBack();
                return false;
            }
        }

        return true;
    }
=======
class TaskButtonAuth extends TaskConfig
{
    //
>>>>>>> 898065c11dc21184310fef1d6c359bb7744202e9
}
