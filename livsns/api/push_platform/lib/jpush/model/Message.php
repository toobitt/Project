<?php

/**
 * 自定义信息
 */
class Message 
{
    public $msg_content;
    public $title;
    public $content_type;
    public $extras;

    public function toJSON() 
    {
        $rs = array();
        
        if (is_null($this->msg_content) === false && strlen(trim($this->msg_content)) > 0) 
        {
            $rs["msg_content"] = $this->msg_content;
        }
        
        if (is_null($this->title) === false && strlen(trim($this->title)) > 0) 
        {
            $rs["title"] = $this->title;
        }
        
        if (is_null($this->content_type) === false && strlen(trim($this->content_type)) > 0) 
        {
            $rs["content_type"] = $this->content_type;
        }
        
        if (is_null($this->extras) === false) 
        {
            $rs["extras"] = $this->extras;
        }
        return $rs;
    }
} 