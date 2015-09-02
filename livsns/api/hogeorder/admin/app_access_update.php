<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/12/2
 * Time: 下午3:07
 */
require_once('global.php');
define(SCRIPT_NAME, 'AppAccessUpdateApi');
define('MOD_UNIQUEID','app_access');
class AppAccessUpdateApi extends adminUpdateBase
{
    public function __construct()
    {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/app_access.class.php');
        $this->obj = new AppAccess();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function create()
    {
        $title = trim($this->input['title']);
        $app_uniqueid = trim($this->input['bundle']);
        if (!$title)
        {
            $this->errorOutput('NO TITLE');
        }

        if(!$app_uniqueid)
        {
            $this->errorOutput('NO APP_UNIQUEID');
        }

        $sql = "SELECT id FROM ".DB_PREFIX."app_access WHERE app_uniqueid = '".$app_uniqueid."'";
        if ($this->db->query_first($sql))
        {
            $this->errorOutput('应用标识已存在');
        }

        $data = array(
            'title'        => $title,
            'app_uniqueid' => $app_uniqueid,
            'host'         => $this->input['host'],
            'dir'          => $this->input['dir'],
            'request_file' => $this->input['request_file'],
            'token'        => $this->input['token'],
            'trade_expire_time' => $this->input['trade_expire_time'],
            'pay_type'     => implode(',', $this->input['pay_type']),
            'user_id'      => $this->user['user_id'],
            'user_name'    => $this->user['user_name'],
            'create_time'  => TIMENOW,
            'update_time'  => TIMENOW,
        );

        $insert_id = $this->obj->create($data);
        if ( $insert_id)
        {

//            $app = $this->obj->detail($insert_id);
//            //写入缓存
//            include_once(ROOT_PATH . 'lib/class/cache/cache.class.php');
//            $cache_factory = cache_factory::get_instance($this->settings['cache_type_config']);
//            $cache_driver = $cache_factory->get_cache_driver('file');
//            $cache_id = md5('app_access_' . $app['app_uniqueid']);
//            $cache_driver->set($cache_id, $app);
//            //写入缓存

            $this->addItem('success');
            $this->output();
        }
        else
        {
            $this->errorOutput('FAIL');
        }
    }

    public function update()
    {
        $id = intval(trim($this->input['id']));
        $title = trim($this->input['title']);
        $app_uniqueid = trim($this->input['bundle']);
        if (!$id)
        {
            $this->errorOutput('NO ID');
        }
        if (!$title)
        {
            $this->errorOutput('NO TITLE');
        }
        if(!$app_uniqueid)
        {
            $this->errorOutput('NO APP_UNIQUEID');
        }

        $sql = "SELECT id FROM ".DB_PREFIX."app_access WHERE app_uniqueid = '".$app_uniqueid."' AND id !=" . $id;
        if ($this->db->query_first($sql))
        {
            $this->errorOutput('应用标识已存在');
        }

        $data = array(
            'title'        => $title,
            'app_uniqueid' => $app_uniqueid,
            'host'         => $this->input['host'],
            'dir'          => $this->input['dir'],
            'request_file' => $this->input['request_file'],
            'token'        => $this->input['token'],
            'trade_expire_time' => $this->input['trade_expire_time'],
            'pay_type'     => implode(',', $this->input['pay_type']),
            'update_time'  => TIMENOW,
        );

        if ($this->obj->update($data, ' id='.$id) )
        {

//            $app = $this->obj->detail($id);
//            //写入缓存
//            include_once(ROOT_PATH . 'lib/class/cache/cache.class.php');
//            $cache_factory = cache_factory::get_instance();
//            $cache_type = $this->settings['cache_type'] ? $this->settings['cache_type'] : 'file';
//            $cache_driver = $cache_factory->get_cache_driver($cache_type);
//            $cache_id = md5('app_access_' . $app['app_uniqueid']);
//            $cache_driver->set($cache_id, $app);
//            //写入缓存

            $this->addItem('success');
            $this->output();
        }
        else
        {
            $this->errorOutput('FAIL');
        }
    }

    public function delete()
    {
        $ids = trim($this->input['id']);
        $ids = is_array($ids) ? implode(',', $ids) : $ids;
        if(!$ids)
        {
            $this->errorOutput('NO ID');
        }
        if ($this->obj->delete($ids))
        {
            $this->addItem($ids);
            $this->output();
        }
        else
        {
            $this->errorOutput('删除失败');
        }
    }

    public function sort(){}

    public function publish(){}

    public function audit()
    {
        $id = urldecode($this->input['id']);
        if(!$id)
        {
            $this->errorOutput("NO ID");
        }
        $idArr = explode(',',$id);
        if(intval($this->input['audit']) == 1)
        {
            $this->obj->update(array('status' => 1), " id IN({$id})");
            $return = array('status' => 1,'id'=> $idArr);
        }
        else if(intval($this->input['audit']) == 0)
        {
            $this->obj->update(array('status' => 2), " id IN({$id})");
            $return = array('status' =>2,'id' => $idArr);
        }
        $this->addItem($return);
        $this->output();
    }
}
require_once ROOT_PATH . 'excute.php';