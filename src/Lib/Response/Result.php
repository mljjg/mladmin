<?php
/**
 * Created by PhpStorm.
 * User: jjg
 * Date: 2018-12-29
 * Time: 16:50
 */

namespace Ml\Response;


class Result
{

    /**
     * 是否成功
     * @var bool
     */
    private $success = true;

    /**
     * @var int
     */
    private $code = 100000;
    /**
     * 业务信息
     * @var string
     */
    private $message = '操作成功';

    /**
     * 返回数据
     * @var null
     */
    private $model = null;

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success)
    {
        $this->success = $success;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param null $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }


    /**
     * the Response content must be a string or object implementing __toString()
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->toArray());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'success' => $this->success,
            'model' => $this->model,
        ];
    }

    /**
     * @param $code
     * @param $msg
     * @return Result
     */
    public function failed($msg = null, $code = null)
    {
        $this->code = $code ? $code : 100001;
        $this->message = $msg ?: $this->getMessage();
        $this->setSuccess(false);
        return $this;
    }

    /**
     * 成功
     * @param null $model
     * @param $msg
     * @return Result
     */
    public function succeed($model = null, $msg = null)
    {
        $this->setModel($model);
        $this->setSuccess(true);
        $this->message = $msg ?: $this->getMessage();
        return $this;
    }


}