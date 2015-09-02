<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - Zhoujiafei
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-8-18
 * @encoding    UTF-8
 * @description 公共函数类
 **************************************************************************/
class Common
{
    /**
     * 生成目录结构
     *
     * @access public
     * @param  无
     * @return array
     */
    public static function buildDirStruct($user_id = '')
    {
        $dirNames  = TIMENOW . hg_rand_num(2);
        $dir = date('Y/m/d/',TIMENOW);
        if($user_id)
        {
            $dir = $user_id . '/' . $dir;
        }
        else
        {
            $dir = 'system/' . $dir;//系统图标
        }
        return array($dirNames,$dir);
    }
}