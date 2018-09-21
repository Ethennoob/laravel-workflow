<?php

namespace Lingxiang\Workflow;

use Lingxiang\Workflow\Common\WorkflowException;

final class TaskConfigFactory
{
    public static function createObject($table)
    {
        switch ($table) {
            case 'task_button_auth':
                return new \Lingxiang\Workflow\TaskButtonAuth($table);

            case 'task_node':
                return new \Lingxiang\Workflow\TaskNode($table);

            case 'task_button':
                return new \Lingxiang\Workflow\TaskButton($table);

            case 'task_type':
                return new \Lingxiang\Workflow\TaskTypeAuth($table);

            case 'task_role':
                return new \Lingxiang\Workflow\TaskRole($table);

            case 'task_role_auth':
                return new \Lingxiang\Workflow\TaskRoleAuth($table);

            default :
                throw new WorkflowException('请选择任务配置数据table');
        }
    }
}