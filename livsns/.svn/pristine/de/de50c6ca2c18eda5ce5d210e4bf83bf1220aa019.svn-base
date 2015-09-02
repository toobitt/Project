<?php

require('global.php');
define('MOD_UNIQUEID', 'webvod');

class webvodUpdateApi extends adminUpdateBase
{

    public function __construct()
    {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/webvod.class.php');
        $this->obj            = new webvod();
        include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
        $this->publish_column = new publishconfig();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    function update()
    {
        $title = $this->input['title'];
        if (!$title)
        {
            $this->errorOutput("请填写标题");
        }
        $sq       = "SELECT * FROM " . DB_PREFIX . "webvod  WHERE program_id = " . $this->input['program_id'];
        $pre_data = $this->db->query_first($sq);

        $info = array();
        $info = array(
            'program_id' => $this->input['program_id'],
            'title' => $title,
            'brief' => $this->input['brief'],
            'keywords' => $this->input['keywords'],
            'create_time' => TIMENOW,
        );

        //更新WEB视频信息
        $ret = $this->obj->update($info);

        //更新后发布
        $re = $this->obj->publish();

        $sq      = "SELECT * FROM " . DB_PREFIX . "webvod  WHERE program_id = " . $this->input['program_id'];
        $up_data = $this->db->query_first($sq);

        $this->addLogs('更新CUTV', $pre_data, $up_data, $title);

        $this->addItem($ret);
        $this->output();
    }

    /**
     *  获取WEB视频内容
     */
    public function get_webvod_content()
    {
        $id         = intval($this->input['from_id']);
        $offset     = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $num        = $this->input['num'] ? intval($this->input['num']) : 10;
        $data_limit = ' LIMIT ' . $offset . ' , ' . $num;
        $sql        = "select * from " . DB_PREFIX . "webvod  where program_id=" . $id . $data_limit;
        $info       = $this->db->query($sql);
        $sql_       = "SELECT * FROM " . DB_PREFIX . "webvodpic WHERE program_id = " . $id . " AND is_now = 1";
        $pic_info   = $this->db->query_first($sql_);
        if (!$pic_info)
        {
            $sqlstr   = "SELECT * FROM " . DB_PREFIX . "webvodpic WHERE program_id = " . $id;
            $pic_info = $this->db->query_first($sqlstr);
        }
        $indexpic = unserialize($pic_info['indexpic']);
        $ret      = array();
        while ($row      = $this->db->fetch_array($info))
        {
            $row['bundle_id']      = APP_UNIQUEID;
            $row['module_id']      = MOD_UNIQUEID;
            $row['struct_id']      = MOD_UNIQUEID;
            $row['struct_ast_id']  = '';
            $row['expand_id']      = '';
            $row['indexpic']       = $indexpic;
            $row['content_fromid'] = $row['program_id'];
            $row['brief']          = $row['brief'];
            $row['keywords']       = $row['keywords'];
            $row['ip']             = hg_getip();
            $row['user_id']        = $this->user['user_id'];
            $row['user_name']      = $this->user['user_name'];
            $row['video']          = array(
                'maid' => $row['maid'],
                'video_source' => $row['video_source'],
            );
            unset($row['program_id']);
            $ret[]                 = $row;
        }
        $this->addItem($ret);
        $this->output();
    }

    public function update_webvod_column_id()
    {
        $data = $this->input['data'];
        if (empty($data))
        {
            return false;
        }
        $sql = "select * from " . DB_PREFIX . "webvod where program_id = " . $data['from_id'];
        $ret = $this->db->query_first($sql);
        if ($ret['status'] != 1)
        {
            $sql = "update " . DB_PREFIX . "webvod set expand_id = 0, column_url = '' where program_id = " . $data['from_id'];
        }
        else
        {
            $column_id  = unserialize($ret['column_id']);    //发布栏目		
            $column_url = unserialize($ret['column_url']);    //栏目url，发布对比，有删除栏目则删除对于栏目url
            $url        = array();
            if (!empty($column_url) && is_array($column_url))
            {
                foreach ($column_url as $k => $v)
                {
                    if ($column_id[$k])
                    {
                        $url[$k] = $v;
                    }
                }
            }
            if (!empty($data['content_url']) && is_array($data['content_url']))
            {
                foreach ($data['content_url'] as $k => $v)
                {
                    $url[$k] = $v;
                }
            }
            $sql = "UPDATE " . DB_PREFIX . "webvod SET expand_id = " . $data['expand_id'] . ", column_url = '" . serialize($url) . "', pub_time = '" . TIMENOW . "' where program_id = " . $data['from_id'];
        }
        $this->db->query($sql);
        $this->addItem('true');
        $this->output();
    }

    /**
     * 即时发布
     * @param id  int   WEB视频id
     * @param column_id string  发布的栏目id
     */
    public function publish()
    {
        //检测是否具有管理权限
        if ($this->mNeedCheckIn && !$this->prms['publish'])
        {
            $this->errorOutput(NO_OPRATION_PRIVILEGE);
        }
        if (empty($this->input['id']))
        {
            $this->errorOutput('ID不能为空');
        }
        $ret = $this->obj->publish();
        if (empty($ret))
        {
            $this->errorOutput('发布失败');
        }
        else
        {
            $this->addItem($ret);
            $this->output();
        }
    }

    public function drag_order()
    {
        if (!$this->input['video_id'])
        {
            $this->errorOutput(NOID);
        }

        $ids       = explode(',', urldecode($this->input['video_id']));
        $order_ids = explode(',', urldecode($this->input['order_id']));

        foreach ($ids as $k => $v)
        {
            $sql = "UPDATE " . DB_PREFIX . "webvod  SET orderid = " . $order_ids[$k] . "  WHERE program_id = " . $v;
            $this->db->query($sql);
        }
        $this->addItem($ids);
        $this->output();
    }

    /**
     * 
     * 插入发布计划配置
     * 
     * @name		insert_plan_set
     * @access		public 
     * @author		gaoyuan
     * @category	hogesoft
     * @copyright	hogesoft
     * 
     */
    public function insert_plan_set()
    {
        $data = array(
            1 => array(
                'bundel_id' => 'webvod',
                'module_id' => 'webvod',
                'struct_id' => 'webvod',
                'name' => 'WEB视频',
                'host' => 'localhost',
                'path' => 'livsns/api/webvod/admin/',
                'filename' => 'webvod_publish.php',
                'action_get_content' => 'get_content',
                'action_insert_contentid' => 'update_content',
                'fid' => 0,
            ),
        );

        require_once ROOT_PATH . 'livsns/lib/class/publishplan.class.php';
        $plan = new publishplan();
        $ret  = $plan->insert_plan_set($data);

        //返回配置ID,某种方式更改

        $sql = "insert into " . DB_PREFIX . "settings (type,var_name,value,description,is_edit,is_open) values(2,'PUBLISH_SET_ID',$ret[1],'',1,1)";
        $this->db->query($sql);

        $this->addItem($ret);
        $this->output();
    }

    /**
     * 发布系统删除某条内容时执行改方法
     */
    public function publish_delete_callback()
    {
        $data = $this->input['data'];
        if (empty($data))
        {
            return false;
        }
        if ($data['is_delete_column'])   //只删除某一栏目中内容
        {
            $sql          = "SELECT column_id,column_url FROM " . DB_PREFIX . "webvod WHERE program_id = " . $data['from_id'];
            $ret          = $this->db->query_first($sql);
            $column_id    = unserialize($ret['column_id']);
            $column_url   = unserialize($ret['column_url']);
            $del_columnid = explode(',', $data['column_id']);
            if (is_array($del_columnid))
            {
                foreach ($del_columnid as $k => $v)
                {
                    unset($column_id[$v], $column_url[$v]);
                }
            }
            $sql = "UPDATE " . DB_PREFIX . "webvod SET expand_id = '" . addslashes(serialize($column_id)) . "', column_url = '" . addslashes(serialize($column_url)) . "' WHERE program_id = " . $data['from_id'];
            $this->db->query($sql);
        }
        else  //全部删除
        {
            $sql = "UPDATE " . DB_PREFIX . "webvod SET expand_id = '' AND column_id = '' AND column_url = '' WHERE program_id = " . $data['from_id'];
            $this->db->query($sql);
        }
        $this->addItem('true');
        $this->output();
    }

    function upload_pic()
    {
        require_once(ROOT_PATH . 'lib/class/material.class.php');
        $this->material = new material();
        if ($_FILES['Filedata'])
        {
            $file_name = $_FILES['Filedata']['name'];
            $file_type = strtolower(strrchr($file_name, "."));
            $ftypes    = $this->settings['pic_types'];
            if (!in_array($file_type, $ftypes))
            {
                $this->errorOutput("error_code");
            }

            $fileinfo = $this->material->addMaterial($_FILES); //插入图片服务器
        }
        $info   = array();
        $url    = $fileinfo['host'] . $fileinfo['dir'] . '40x30/' . $fileinfo['filepath'] . $fileinfo['filename'];
        $arr    = array(
            'host' => $fileinfo['host'],
            'dir' => $fileinfo['dir'],
            'filepath' => $fileinfo['filepath'],
            'filename' => $fileinfo['filename'],
            'imgwidth' => $fileinfo['imgwidth'],
            'imgheight' => $fileinfo['imgheight'],
        );
        $info   = array(
            'type' => $file_type,
            'url' => $fileinfo['url'],
            'status' => 1,
            'indexpic' => serialize($arr),
            'program_id ' => $this->input['program_id'],
        );
        $pic_id = $this->obj->upload_pic($info);
        $re[]   = $url;
        $re[]   = $pic_id;
        foreach ($re as $k => $v)
        {
            $this->addItem($v);
        }
        $this->output();
    }

    /**
     * 同步访问统计
     */
    function access_sync()
    {
        if (!$this->input['id'])
        {
            $this->errorOutput('NOID');
        }
        $data              = array();
        if ($this->input['click_num'])
            $data['click_num'] = intval($this->input['click_num']);
        if ($this->input['comm_num'])
            $data['comm_num']  = intval($this->input['comm_num']);
        if ($this->input['share_num'])
            $data['share_num'] = intval($this->input['share_num']);
        if ($this->input['down_num'])
            $data['down_num']  = intval($this->input['down_num']);
        $return            = $this->obj->access_sync($data, intval($this->input['id']));
        $this->addItem($return);
        $this->output();
    }

    function create()
    {
        
    }

    function audit()
    {
        
    }

    function sort()
    {
        
    }

    function delete()
    {
        
    }

    function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }

}

$out    = new webvodUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();
?>