<?php
/**
 * 价格计算基类
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/20
 * Time: 10:03
 */

abstract class HgCash
{
    public function __construct()
    {
    }

    abstract public function getReturn($price);

    public function __get($name)
    {
        return $this->$name;
    }
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __destruct()
    {
    }
}