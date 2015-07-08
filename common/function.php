<?php
/**
 * Created by PhpStorm.
 * User: zhangxian
 * Date: 15/7/8
 * Time: 上午8:54
 */

/*
 * 页面跳转
 */
function JumpTo($address,$message = 'ERROR_OCCURED')
{
    if($address)
    {
        Header('Location:'.$address);
    }
}
/*
 * 报错
 */
function showError($error)
{
    echo $error;
    die;
}