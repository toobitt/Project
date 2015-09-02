<?php
/**
 * 在cron目录下执行
 */
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH . 'global.php');
require_once(CUR_CONF_PATH . 'lib/functions.php');
define('MOD_UNIQUEID', 'publishcontent'); //模块标识
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
class content_insert_xsApi extends adminBase
{

    public function __construct()
    {
        parent::__construct();
        $this->pub_config = new publishconfig();
        include(CUR_CONF_PATH . 'lib/content.class.php');
        $this->obj        = new content();
        include_once(CUR_CONF_PATH . 'lib/column.class.php');
        $this->column     = new column();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
    
    public function initcron()
    {
        $array = array(
            'mod_uniqueid' => MOD_UNIQUEID,
            'name' => '迅搜数据导入',
            'brief' => '迅搜数据导入',
            'space' => '2', //运行时间间隔，单位秒
            'is_use' => 1, //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }

    public function show()
    {
        exit;
        $do = true;
        $offset = 0;
        $count = 100;
        $offsetstr = @file_get_contents(CUR_CONF_PATH.'cache/xs_offset.txt');
        if($offsetstr)
        {
            $offsetarr = unserialize($offsetstr);
            if($offsetarr['do']=='none')
            {
                echo '没有可执行导入的迅搜内容';
                exit;
            }
            $offset = intval($offsetarr['offset'])+$count;
        }
        
        $offsetnew['offset'] = $offset;
        $offsetnew['do'] = 'ing';
        file_put_contents(CUR_CONF_PATH.'cache/xs_offset.txt',  serialize($offsetnew));
        
        $insertresult = $this->content_insert_xs($offset,$count);
        if(!$insertresult)
        {
            $offsetnew['do'] = 'none';
            file_put_contents(CUR_CONF_PATH.'cache/xs_offset.txt',  serialize($offsetnew));
        }
    }
    
    //迅搜数据重建方法
    public function content_insert_xs($offset = 0,$count=100)
    {
        $sql  = "SELECT c.*,r.*,c.id as id,c.column_id as column_id FROM " . DB_PREFIX . "content_relation r left join " . DB_PREFIX . "content c on r.content_id=c.id group by r.content_id ORDER BY r.content_id ";
        $sql  .= " LIMIT $offset,$count";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $ret[$row['id']] = $row;
            $content_ids[]   = $row['id'];
            $title_unicode = $this->get_titleResult($row['title']);
            $sql1 = "update ".DB_PREFIX."content_relation set title_unicode='".$title_unicode."' where content_id=".$row['id'];
            $this->db->query($sql1);
        }

        if ($content_ids)
        {
            $sql  = "SELECT * FROM " . DB_PREFIX . "content_columns WHERE content_id in (" . implode(',', $content_ids) . ")";
            $info = $this->db->query($sql);
            while ($row  = $this->db->fetch_array($info))
            {
                $content_columns[$row['content_id']] = $row;
                if ($row['column_ids'])
                {
                    $column_id              = intval($row['column_ids']);
                    $column_ids[$column_id] = $column_id;
                }
            }
            if ($column_ids)
            {
                $column_details = $this->column->get_column_by_id(' id,name ', implode(',', $column_ids), 'id');
            }

            foreach ($ret as $k => $v)
            {
                if (empty($content_columns[$k]))
                {
                    $content_columns[$k]['column_ids']   = '';
                    $content_columns[$k]['column_datas'] = '';
                }
                $this->opration_xunsearch($v, $content_columns[$k], 'add', $column_details);
            }
        }
        else
        {
            return false;
        }
        return true;
    }

    public function xs_clean()
    {
        $this->xs_index('', 'search_config_publish_content', 'clean');
        @unlink(CUR_CONF_PATH.'cache/xs_offset.txt');
        echo "数据已清";
        exit;
    }

    public function opration_xunsearch($data, $content_columns, $opration, $column_details)
    {
        $column_id = intval($content_columns['column_ids']);
        $xundata   = array(
            'id' => empty($data['content_id']) ? $data['id'] : $data['content_id'],
            'title' => $data['title'],
            'subtitle' => $data['subtitle'],
            'content' => $data['content'],
            'bundle_id' => $data['bundle_id'],
            'module_id' => $data['module_id'],
            'struct_id' => $data['struct_id'],
            'site_id' => $data['site_id'],
            'column_name' => $column_details[$column_id] ? $column_details[$column_id]['name'] : '',
            'column_ids' => $content_columns['column_ids'],
            'column_datas' => $content_columns['column_datas'],
            'expand_id' => $data['expand_id'],
            'content_fromid' => $data['content_fromid'],
//			'client_type' => $content_client,
            'is_have_indexpic' => $data['is_have_indexpic'],
            'is_have_video' => $data['is_have_video'],
//			'weight' => $data['weight'],
            'share_num' => $data['share_num'],
            'comment_num' => $data['comment_num'],
            'click_num' => $data['click_num'],
            'publish_time' => $data['publish_time'],
            'create_time' => $data['create_time'],
            'verify_time' => $data['verify_time'],
//			'column_name' => $data['column_name'],
            'publish_user' => $data['publish_user'],
            'create_user' => $data['create_user'],
            'verify_user' => $data['verify_user'],
            'outlink' => $data['outlink'],
            'ip' => $data['ip'],
            'video' => $data['video'],
            'indexpic' => $data['indexpic'],
//			'filepath' => $crdata['filepath'],
            'brief' => $data['brief'],
            'keywords' => $data['keywords'],
        );
        $this->xs_index($xundata, 'search_config_publish_content', $opration);
        echo $xundata['id'] . ' ' . str_repeat(' ', 4096);
        ob_flush();
    }

    function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }

}

$out    = new content_insert_xsApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>
