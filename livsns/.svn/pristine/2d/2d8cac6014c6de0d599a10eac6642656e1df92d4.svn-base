<?php

class PushPayload 
{
    public $platform;
    public $audience;
    public $notification;
    public $message;
    public $options;

    
    public function toJSON() 
    {
        $rs = array();
        
        //推送给哪些系统
        if (is_null($this->platform) === false) 
        {
            $rs["platform"] = $this->platform->toJSON();
        } 
        else 
        {
            $rs["platform"] = "all";
        }
        
        //设置标签
        if (is_null($this->audience) === false) 
        {
            $rs["audience"] = $this->audience->toJSON();
        } 
        else 
        {
            $rs["audience"] = "all";
        }
        
        //设置通知内容
        if (is_null($this->notification) === false) 
        {
            $rs["notification"] = $this->notification->toJSON();
        }
        
        //设备消息内同
        if (is_null($this->message) === false) 
        {
            $rs["message"] = $this->message->toJSON();
        }
        
        //设置选项
        if (is_null($this->options) === false) 
        {
            $rs["options"] = $this->options->toJSON();
        }
        return $rs;

    }

}
?>