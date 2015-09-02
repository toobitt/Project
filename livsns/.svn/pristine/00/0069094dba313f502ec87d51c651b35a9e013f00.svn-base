<?php

define('MOD_UNIQUEID', 'news_publish');
require_once('global.php');
require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
require(ROOT_PATH . 'frm/publish_interface.php');

class NewsPublish extends adminUpdateBase implements publish
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function create()
    {
        
    }

    public function update()
    {
        
    }

    public function delete()
    {
        
    }

    public function audit()
    {
        
    }

    public function sort()
    {
        
    }

    public function publish()
    {
        
    }

    function get_content()
    {
        $id         = intval($this->input['from_id']);
        $sort_id    = intval($this->input['sort_id']);
        $offset     = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $num        = $this->input['num'] ? intval($this->input['num']) : 10;
        $data_limit = ' LIMIT ' . $offset . ' , ' . $num;
        if (empty($sort_id))
        {
            $sql = "SELECT a.*,c.*,m.pic pic,m.imgwidth,m.imgheight FROM " . DB_PREFIX . "article a LEFT JOIN " . DB_PREFIX . "article_contentbody c ON a.id = c.articleid LEFT JOIN " . DB_PREFIX . "material m on a.indexpic = m.material_id WHERE 1 and a.id=" . $id . $data_limit;
        }
        else
        {
            $sql = "select a.*,c.*,m.pic pic,m.imgwidth,m.imgheight from " . DB_PREFIX . "article a left join " . DB_PREFIX . "article_contentbody c on a.id = c.articleid left join " . DB_PREFIX . "material m on a.indexpic = m.material_id where a.sort_id=" . $sort_id . $data_limit;
        }
        $info = $this->db->query($sql);
        $ret  = array();
        while ($row  = $this->db->fetch_array($info))
        {
            $row['bundle_id']      = APP_UNIQUEID;
            $row['module_id']      = MOD_UNIQUEID;
            $row['struct_id']      = 'article';
            $row['struct_ast_id']  = '';
            $row['expand_id']      = '';
            $row['content_fromid'] = $row['id'];
            $pic                   = array();
            $pic                   = unserialize($row['pic']);
            if (!empty($pic))
            {
                $pic['id']        = $row['indexpic'];
                $pic['imgwidth']  = $row['imgwidth'];
                $pic['imgheight'] = $row['imgheight'];
            }
            $row['indexpic']       = $pic;
            $row['ip']             = hg_getip();
            $row['user_id']        = $row['user_id'];
            $row['user_name']      = $row['user_name'];
            $row['block']          = $row['block'] ? unserialize($row['block']) : array();
            $row['special']        = $row['special'] ? unserialize($row['special']) : array();
            $row['comment_num']    = $row['comm_num'];
            unset($row['id']);
            $row['use_maincolumn'] = $this->settings['publish_main_url'];
            if($row['publish_id'])
            {
	            $row['use_maincolumn'] = 1;
            }
            $ret[]                 = $row;
        }
        $this->addItem($ret);
        $this->output();
    }

    /**
     * 更新内容expand_id,发布内容id
     *
     */
    function update_content()
    {
        $data = $this->input['data'];
        if (empty($data))
        {
            return false;
        }
        $sql = "SELECT * FROM " . DB_PREFIX . "article WHERE id = " . $data['from_id'];
        $ret = $this->db->query_first($sql);
        if ($ret['state'] != 1)
        {
            $sql = "UPDATE " . DB_PREFIX . "article SET expand_id = 0, column_url = '' WHERE id = " . $data['from_id'];
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
            $sql = "UPDATE " . DB_PREFIX . "article SET expand_id = " . $data['expand_id'] . ", column_url = '" . serialize($url) . "' where id = " . $data['from_id'];
        }
        $this->db->query($sql);
        if (empty($data['expand_id']))   //如果expand_id为空说明为打回状态，则更改字表expand_id字段
        {
            $sql = "UPDATE " . DB_PREFIX . "material SET expand_id = " . $data['expand_id'] . " WHERE cid =" . $data['from_id'];
            $this->db->query($sql);
        }
        $this->addItem('true');
        $this->output();
    }

    /**
     * 删除这条内容的发布
     *
     */
    function delete_publish()
    {
        $data = $this->input['data'];
        if (empty($data))
        {
            return false;
        }
        if ($data['is_delete_column'])   //只删除某一栏目中内容
        {
            $sql          = "SELECT column_id,column_url FROM " . DB_PREFIX . "article WHERE id = " . $data['from_id'];
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
            $sql = "UPDATE " . DB_PREFIX . "article SET column_id = '" . addslashes(serialize($column_id)) . "', column_url = '" . addslashes(serialize($column_url)) . "' WHERE id = " . $data['from_id'];
            $this->db->query($sql);
        }
        else  //全部删除
        {
            $sql = "UPDATE " . DB_PREFIX . "article 
					SET expand_id = '', column_id = '', column_url = '' 
					WHERE id = " . $data['from_id'];
            $this->db->query($sql);
            $sql = "UPDATE " . DB_PREFIX . "material SET expand_id = '' WHERE cid = " . $data['from_id'];
            $this->db->query($sql);
        }
        $this->addItem('true');
        $this->output();
    }
    
    //专题应用，添加文稿数据，回调
    function up_content()
    {
        $content_fromid = intval($this->input['data']['content_fromid']);
        if (!$content_fromid)
        {
            $this->errorOutput('NO_ID');
        }
        unset($this->input['data']['content_fromid']);
        if(!$this->input['data']['special'])
        {
            $this->errorOutput('NO_SPECIAL');
        }
        $special   = unserialize($this->input['data']['special']);
        $sq        = "SELECT * FROM " . DB_PREFIX . "article WHERE id = " . $content_fromid;
        $news_info = $this->db->query_first($sq);
        if ($news_info['special'])
        {
            $pub_special = unserialize($news_info['special']);
            if (is_array($pub_special))
            {
                foreach ($special as $k => $v)
                {
                	if($special[$k]['del'])
                	{
                		unset($pub_special[$k]);
                	}
                	else
                	{
                		$pub_special[$k] = $special[$k];
                	}
                }
            }
            else
            {
                $pub_special = $special;
            }
        }
        else
        {
            $pub_special = $special;
        }
        
        if($this->input['data']['delete_column_id'])
		{
			unset($pub_special[$this->input['data']['delete_column_id']]);
		}
        $data            = array();
        $data['special'] = serialize($pub_special);
        $sql             = "UPDATE " . DB_PREFIX . "article SET";

        $sql_extra = $space     = ' ';
        foreach ($data as $k => $v)
        {
            $sql_extra .=$space . $k . "='" . $v . "'";
            $space = ',';
        }
        $sql .=$sql_extra;
        $sql .= " WHERE id=" . $content_fromid;
        $this->db->query($sql);
        
        $news_info['special'] = $data['special'];
        publish_insert_query($news_info, 'update');
    }
    
    //区块应用，添加文稿数据，回调
    function update_block_content()
    {
        $block = ($this->input['data']['block']);
        $data = ($this->input['data']['data']);
        if (!$block || !$data || !is_array($data) || !is_array($block))
        {
            $this->errorOutput('NO_DATA');
        }
        foreach($data as $k=>$v)
        {
            $content_fromids[] = $v['content_fromid'];
        }
        if(!$content_fromids)
        {
            $this->errorOutput('NO_CONTENT_FROMID');
        }
        $sq        = "SELECT * FROM " . DB_PREFIX . "article WHERE id in (" . implode(',',$content_fromids).")";
        $info = $this->db->query($sq);
        while ($row  = $this->db->fetch_array($info))
        {
            $pub_block = $row['block'] ? unserialize($row['block']) : array();
            foreach ($block as $k => $v)
            {
                if ($block[$k]['del'])
                {
                    unset($pub_block[$k]);
                }
                else
                {
                    $pub_block[$k] = $block[$k];
                }
            }
            $pub_block = $pub_block?serialize($pub_block):'';
            $sql                = "UPDATE " . DB_PREFIX . "article SET block='$pub_block' WHERE id=" . $row['id'];
            $this->db->query($sql);
            $row['block'] = $pub_block;
            //同步发布库
            publish_insert_query($row, 'update');
        }
    }

    function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }

}

$out    = new NewsPublish();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();
?>
