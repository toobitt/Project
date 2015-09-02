<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/22
 * Time: 12:06
 */
$class = __CLASS__;
$obj = new $class();
$action = $_INPUT['a'];
if(!method_exists($obj, $action))
{
    $action = 'show';
}
$obj->$action();