<?php

define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH . 'global.php');
define('MOD_UNIQUEID', 'block'); //模块标识
require_once(ROOT_PATH . 'lib/class/data_source.class.php');

class block_contentApi extends cronBase
{

    /**
     * 构造函数
     * @author repheal
     * @category hogesoft
     * @copyright hogesoft
     * @include site.class.php
     */
    public function __construct()
    {
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/block.class.php');
        include(CUR_CONF_PATH . 'lib/block_set.class.php');
        $this->obj         = new block();
        $this->block_set   = new block_set();
        $this->data_source = new dataSource();
        include(ROOT_PATH.'lib/class/publishplan.class.php');
        $this->pub_plan = new publishplan();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function initcron()
    {
        $array = array(
            'mod_uniqueid' => MOD_UNIQUEID,
            'name' => '区块内容更新',
            'brief' => '区块内容更新',
            'space' => '30', //运行时间间隔，单位秒
            'is_use' => 1, //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }

    public function show()
    {
        set_time_limit(0);
        //取自动更新的区块(每次只取一个区块)
        $update_block = $this->obj->get_block_by_condition(' AND update_type=1 AND next_update_time<=' . TIMENOW . ' ORDER BY next_update_time ASC limit 10', true);
        if (empty($update_block))
        {
            echo "没有可更新的区块";
            exit;
        }
        include_once(CUR_CONF_PATH . 'lib/cache.class.php');
        $cache = new Cache();
        foreach ($update_block as $k => $v)
        {
            //更改区块下次更新时间
            $update_data = array('last_update_time' => TIMENOW, 'next_update_time' => $v['update_time'] + TIMENOW);
            $this->obj->update($update_data, $v['id']);
        }
        foreach ($update_block as $k => $v)
        {
            $new_content_idarr = array();
            /*             * 更新区块每行内容 */
            //根据数据源设置的参数取内容
            $v['datasource_argument']['site_id'] = $v['datasource_argument']['site_id']?$v['datasource_argument']['site_id']:1;
            $v['datasource_argument']['client_type'] = 2;
            $content_data = $this->data_source->get_content_by_datasource($v['datasource_id'], $v['datasource_argument']);
            if (empty($content_data))
            {
                echo "没有要更新的内容";
                continue;
            }

            foreach ($content_data as $kkk => $vvv)
            {
                $new_content_idarr[$vvv['id']] = $vvv['id'];
            }
            
            //查询出这个区块的现有所有内容id
            $new_content_idstr = implode(',', $new_content_idarr);
            $contentidarr      = array();
            if ($new_content_idstr)
            {
                $contentidarr = $this->block_set->get_content_by_content_ids($v['id'], $new_content_idstr);
            }
            
            //满足什么条件进行内容添加
            foreach ($content_data as $kkk => $vvv)
            {
                if (!$contentidarr[$vvv['id']])
                {
                    if(!$new_content_idarr[$vvv['id']])
                    {
                        continue;
                    }
                    //插入到区块内容表中,插入到最新行
                    $newdata = array(
                        'block_id' => $v['id'],
                        'line' => 1,
                        'cid' => $vvv['cid'],
                        'content_id' => $vvv['id'],
                        'content_fromid' => $vvv['content_fromid'],
                        'bundle_id' => $vvv['bundle_id'],
                        'module_id' => $vvv['module_id'],
                        'title' => $vvv['title'],
                        'brief' => $vvv['brief'],
                        'outlink' => $vvv['outlink'],
                        'indexpic' => serialize($vvv['indexpic']),
                        'child_line' => 1,
                    );
                    $this->block_set->insert_child_content($newdata, true);
                    
                    //回调各自系统
                    $arr['block'] = array($v['id']=>array('id'=>$v['id'],'name'=>$v['name']));
                    $this->pub_plan->update_block_content($arr,$vvv['content_fromid'],$vvv['bundle_id'],$vvv['module_id']);
                
                    $new_content_idarr[$vvv['id']] = false;
                }
            }

            $cache->initialize(BLOCK_CACHE);
            $cache->delete($v['id']);
        }
    }

}

$out    = new block_contentApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>
