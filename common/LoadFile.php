<?php
/**
 * Created by PhpStorm.
 * User: zhangxian
 * Date: 15/7/8
 * Time: 上午10:20
 */
/*
 * @模板加载view
 * @方法加载method
 *
 */
class LoadFile
{
/*
 * @params $filename 模板名称，$data传递参数的二维数组。
 * @后端参数变量必须位于数组中，且变量名位键名，变量值为键值。
 */
    public function view($filename,$data = '')
    {
        if(file_exists(VIEW_DIR.$filename))
        {
            if(is_array($data) && count(array_filter($data)) > 0)
            {
                extract($data);
            }
            include_once(VIEW_DIR.$filename);
        }else
        {
            ShowError('LoadError: '.VIEW_DIR.$filename.' was not found on this server ');
        }
    }

/* @加载自定义类方法
 * @ $methodname 类文件名
 * @ $return object
 */
    public function method($methodname)
    {
        if(trim($methodname))
        {
            if (file_exists(COMMON_DIR . 'methods/' . $methodname . '.class.php'))
            {
                include_once(COMMON_DIR . 'methods/' . $methodname . '.class.php');
                return new $methodname();
            }else
            {
                ShowError('LoadError: ' . $methodname . '.class.php not exists!');
            }
        }else
        {
            ShowError('LoadError: Method Is Null!');
        }
    }
}