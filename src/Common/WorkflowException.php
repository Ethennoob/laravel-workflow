<?php

namespace Lingxiang\Workflow\Common;


class WorkflowException extends \Exception
{
    /**
     * 获取异常错误信息
     * @return string
     * @author helei
     */
    public function errorMessage()
    {
        return $this->getMessage();
    }
}