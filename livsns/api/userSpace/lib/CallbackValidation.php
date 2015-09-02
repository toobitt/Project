<?php

namespace Sugar;
/**
 * 验证回调参数签名是否合法
 * Class CallbackValidation
 * @package Sugar
 */

class CallbackValidation {

    public $params = array();
    /**
     * @var \Sugar\AvPretreatment
     */
    public $sugar;

    public function __construct($avPretreatment)
    {
         $this->setParamsByPost(array(
            'bucket_name',
            'status_code',
            'path',
            'task_id',
            'info',
         	'description',
            'signature',
        ));

        if($avPretreatment instanceof AvPretreatment) {
            $this->sugar = $avPretreatment;
        } else {
            throw new \Exception('需要一个 AvPretreatment 实例');
        }
    }

    protected function getParamFromPost($key)
    {
        //return isset($_POST[$key]) ? trim($_POST[$key]) : null;
        if(isset($_POST[$key]))
        {
        	if(is_array($_POST[$key]))
        	{
        		return implode('', $_POST[$key]);
        	}
        	else
        	{
        		return trim($_POST[$key]);
        	}
        }
        return null;
    }

    protected function setParamsByPost($keys)
    {
        $this->params = array();
        foreach($keys as $key) {
            $value = $this->getParamFromPost($key);
            if($value !== null) {
                $this->params[$key] = $value;
            }
        }
    }

    /**
     * 验证回调参数的签名是否合法
     * @return bool
     */
    public function verifySign()
    {
        $data = $this->params;
        if(isset($data['signature'])) {
            unset($data['signature']);
            return $this->params['signature'] === $this->sugar->createSign($data);
        }

        if(isset($data['non_signature'])) {
            unset($data['non_signature']);
            return $this->params['non_signature'] === $this->sugar->createSignWithoutOperator($data);
        }

        return false;
    }
}