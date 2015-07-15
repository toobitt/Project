<?php
/**
 * Created by PhpStorm.
 * User: zhangxian
 * Date: 15/7/8
 * Time: 上午8:54
 */

/*
 * 报错
 */
function ShowError($error,$url = "")
{
    if($error)
    {
        Header("Location:".BASE_URL."/common/error/generalError.php?error=$error&url=$url");
    }else
    {
        echo 'Error Info Is Null!';
        exit;
    }
}