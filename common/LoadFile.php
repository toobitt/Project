<?php
/**
 * Created by PhpStorm.
 * User: zhangxian
 * Date: 15/7/8
 * Time: 上午10:20
 */
/*
 * @加载前端模板
 * @params $filename 模板名称，$data传递参数的二维数组。
 * @后端参数变量必须位于数组中，且变量名位键名，变量值为键值。
 */
class LoadFile
{
    public function view($filename,$data)
    {

        if(file_exists(VIEW_DIR.$filename))
        {
            extract($data);
            include_once(VIEW_DIR.$filename);
            exit;
        }else
        {
            echo '404 page not found';
            exit;
        }
    }
}