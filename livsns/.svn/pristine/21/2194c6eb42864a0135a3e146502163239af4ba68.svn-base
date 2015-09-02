<?php

define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID', 'publishcontent');
require_once(ROOT_PATH . "global.php");
require_once(CUR_CONF_PATH . "lib/functions.php");
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');

class content_upgradeApi extends adminBase
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

    /**
     * 第一步：创建表：table_create
     * 第二步：更改数据结构：content_change
     * 第三步：数据插入到xunsearch中：content_insert_xs
     * 第四步：更改表结构：table_change
     * 
     * */
    public function content_change()
    {
        $client_type    = empty($this->input['client_type']) ? 2 : $this->input['client_type'];
        $tmp            = $content_column = $column_tmp     = $column_ids_arr = array();

        $sql  = "SELECT * FROM " . DB_PREFIX . "content_relation WHERE client_type=" . $client_type . " ORDER BY id";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            if ($row['id'] && $row['client_type'])
            {
                $content_relations[$row['id']] = $row['column_id'];

                $content_column[$row['content_id']][$row['column_id']] = array(
                    'id' => $row['column_id'],
                    'column_url' => '',
                    'name' => $row['column_name'],
                    'relation_id' => $row['id'],
                    'content_url' => $row['content_url'],
                );
            }
            $column_ids_arr[$row['content_id']][$row['column_id']] = $row['column_id'];
            $column_ids[$row['column_id']]                         = $row['column_id'];
        }
        if ($column_ids)
        {
            $columns_data = $this->column->get_column_by_id(' id,column_url,support_client ', implode(',', $column_ids), 'id');
            foreach ($content_column as $k => $v)
            {
                foreach ($v as $kk => $vv)
                {
                    if (!empty($columns_data[$kk]))
                    {
                        $content_column[$k][$kk]['column_url'] = $columns_data[$kk]['column_url'];
                    }
                }
                $column_tmp[] = "('" . $k . "','" . (empty($column_ids_arr[$k]) ? '' : implode(',', $column_ids_arr[$k])) . "','" . serialize($content_column[$k]) . "')";
            }
        }

        if ($content_relations)
        {
            foreach ($content_relations as $k => $v)
            {
                if (!empty($columns_data[$v]['support_client']))
                {
                    foreach (explode(',', $columns_data[$v]['support_client']) as $kk => $vv)
                    {
                        $tmp[] = "('" . $k . "','" . $vv . "')";
                    }
                }
            }
            if ($tmp)
            {
                $sql = "INSERT INTO " . DB_PREFIX . "content_client_relation(relation_id,client_type) VALUES" . implode(',', $tmp);
                $this->db->query($sql);
            }
        }

        if ($column_tmp)
        {
            $sql = "INSERT INTO " . DB_PREFIX . "content_columns(content_id,column_ids,column_datas) VALUES" . implode(',', $column_tmp);
            $this->db->query($sql);
        }
        echo '数据已转换' . '<br>';
    }

    public function content_inxs()
    {
        set_time_limit(0);
        for ($i = 1; $i < 80; $i++)
        {
            $offset = ($i - 1) * 2000;
            $this->content_insert_xs($offset);
        }
    }
    
    //迅搜数据重建方法
    public function content_insert_xs($offset = '')
    {
        $sql  = "SELECT c.*,r.*,c.id as id,c.column_id as column_id FROM " . DB_PREFIX . "content_relation r left join " . DB_PREFIX . "content c on r.content_id=c.id group by r.content_id ORDER BY r.id ";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $ret[$row['id']] = $row;
            $content_ids[]   = $row['id'];
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
        echo "数据已全部添加到xunsearch" . '<br>';
    }

    public function xs_clean()
    {
        $this->xs_index('', 'search_config_publish_content', 'clean');
        echo "数据已清";
        exit;
    }

    public function table_create()
    {
        $sql       = "SHOW TABLES LIKE '" . DB_PREFIX . "content_client_relation'";
        $table_num = $this->db->fetch_all($sql);
        if (count($table_num) > 0)
        {
            exit('liv_content_client_relation表已存在');
        }

        $sql = "CREATE TABLE `" . DB_PREFIX . "content_client_relation` (
			`id` int(10) NOT NULL AUTO_INCREMENT,
			`relation_id` int(10) DEFAULT NULL,
			`client_type` int(10) DEFAULT NULL,
			PRIMARY KEY (`id`),
			KEY `relation_id` (`relation_id`),
			KEY `client_type` (`client_type`)
			) ENGINE=MyISAM AUTO_INCREMENT=203 DEFAULT CHARSET=utf8;";

        if ($this->db->query($sql) != 1)
        {
            exit('liv_content_client_relation表创建失败');
        }
        else
        {
            echo "liv_content_client_relation已创建" . '<br>';
        }

        $sql       = "SHOW TABLES LIKE '" . DB_PREFIX . "content_columns'";
        $table_num = $this->db->fetch_all($sql);
        if (count($table_num) > 0)
        {
            exit('liv_content_columns表已存在');
        }
        $sql = "CREATE TABLE `" . DB_PREFIX . "content_columns` (
			`content_id` int(10) NOT NULL,
			`column_ids` text,
			`column_datas` text,
			PRIMARY KEY (`content_id`),
			KEY `content_id` (`content_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        if ($this->db->query($sql) != 1)
        {
            exit('liv_content_columns表创建失败');
        }
        else
        {
            echo "liv_content_columns已创建" . '<br>';
        }

        $sql = "ALTER TABLE " . DB_PREFIX . "content_relation ADD column_url varchar(500) null";
        $this->db->query($sql);
        echo "liv_content_relation 字段column_url已添加" . '<br>';
    }

    public function table_change()
    {
        $sql = "ALTER TABLE " . DB_PREFIX . "content_relation DROP COLUMN client_type";
        $this->db->query($sql);
        echo "liv_content_relation表client_type字段已删除" . '<br>';
//		$sql = "ALTER TABLE ".DB_PREFIX."content_client_relation2 ADD column_url varchar(500) null";
//		$this->db->query($sql);
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

    public function content_video_record()
    {
        "INSERT INTO liv_content_video_record(site_id,bundle_id,module_id,struct_id,content_fromid,content_id) SELECT c.site_id,c.bundle_id,c.module_id,c.struct_id,c.content_fromid,c.id FROM liv_content c;update liv_content_video_record set opration='add',update_time=unix_timestamp();";
    }

    /**
     * 插入百度视频收录  
     * */
    public function insert_content_video_record()
    {
        $all  = array();
        $i    = $j    = 1;
        $sql  = "SELECT c.*,r.site_id,r.column_id,r.column_name,cr.client_type,r.id as rid,r.order_id,r.file_name,r.weight as weight FROM " . DB_PREFIX . "content_client_relation cr LEFT JOIN " . DB_PREFIX . "content_relation r ON cr.relation_id=r.id  LEFT JOIN " . DB_PREFIX . "content c on r.content_id=c.id ";
        $sql .= " WHERE cr.client_type=2 AND c.is_have_video=1 ORDER BY cr.id";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $content_data      = array(
                'title' => $row['title'],
                'keywords' => $row['keyword'],
                'brief' => $row['brief'],
                'indexpic' => $row['indexpic'] ? unserialize($row['indexpic']) : array(),
                'video' => $row['video'] ? unserialize($row['video']) : array(),
                'file_name' => $row['file_name'],
                'create_time' => $row['create_time'],
            );
            $video_record_data = array(
                'site_id' => $row['site_id'],
                'column_id' => $row['column_id'],
                'bundle_id' => $row['bundle_id'],
                'module_id' => $row['module_id'],
                'struct_id' => $row['struct_id'],
                'relation_id' => $row['rid'],
                'opration' => 'add',
                'content_data' => serialize($content_data),
                'update_time' => TIMENOW,
            );
            if ($i % 20 == 0)
            {
                $j++;
            }
            $all[$j][] = $video_record_data;
            $i++;
        }
        if ($all)
        {
            foreach ($all as $k => $v)
            {
                $tmp = array();
                if (is_array($v))
                {
                    foreach ($v as $kk => $vv)
                    {
                        $tag = $str = '';
                        $str .= "(";
                        foreach ($vv as $kkk => $vvv)
                        {
                            $str .= $tag . "'" . $vvv . "'";
                            $tag = ',';
                        }
                        $str .= ")";
                        $tmp[] = $str;
                    }
                }
                $sql = "INSERT INTO " . DB_PREFIX . "content_video_record(site_id,column_id,bundle_id,module_id,struct_id,relation_id,opration,content_data,update_time) values" . implode(',', $tmp);
                $this->db->query($sql);
            }
        }
        echo "添加成功";
    }

    public function rebuild_order()
    {
        $sql  = "select id,publish_time from " . DB_PREFIX . "content_relation order by publish_time asc";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $ret[] = $row;
        }
        foreach ($ret as $k => $v)
        {
            $sql = "update " . DB_PREFIX . "content_relation set order_id_c=" . ($k + 1) . " where id=" . $v['id'];
            $this->db->query($sql);
        }
    }

    public function _do()
    {
        set_time_limit(0);
        while (true)
        {
            file_get_contents("http://10.0.1.40/livsns/api/publishplan/cron/publish.php?appid=55&appkey=GLtPX7N7ijwb83wupXuIrEl1YvIeBbm7");
            file_get_contents("http://10.0.1.40/livsns/api/publishcontent/cron/content_set.php?appid=55&appkey=GLtPX7N7ijwb83wupXuIrEl1YvIeBbm7");
            file_get_contents("http://10.0.1.40/livsns/api/mkpublish/cron/mkpublish.php?appid=55&appkey=GLtPX7N7ijwb83wupXuIrEl1YvIeBbm7");
        }
    }

    public function update_column_content_num()
    {
        set_time_limit(0);
        $sql  = "select * from " . DB_PREFIX . "column order by id";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $sql1 = "select count(*) as total from " . DB_PREFIX . "content_relation where column_id=" . $row['id'];
            $c    = $this->db->query_first($sql1);
            $sql2 = "update " . DB_PREFIX . "column set content_num=" . intval($c['total']) . " where id=" . $row['id'];
            $this->db->query($sql2);
        }
    }
    /**
    //方案1
    public function content_up1()
    {
        set_time_limit(0);
        $sql  = "select *,cr.weight as weight,cr.order_id as order_id,cr.publish_time as publish_time,cr.client_type as client_type,cr.id as id,cr.relation_id as rid,
                r.column_id as column_id,r.site_id as site_id,r.column_name as column_name,
                c.id as cid,c.column_id as ccolumn_id
                from liv_content_client_relation cr left join liv_content_relation r on cr.relation_id=r.id left join liv_content c on r.content_id=c.id order by r.id";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $content_relation = array();
            $content          = array();
            $content_relation = array(
                'id' => $row['id'],
                'order_id' => $row['order_id'],
                'relation_id' => $row['rid'],
                'content_id' => $row['cid'],
                'client_type' => $row['client_type'],
                'site_id' => $row['site_id'],
                'column_id' => $row['column_id'],
                'column_name' => $row['column_name'],
                'bundle_id' => $row['bundle_id'],
                'module_id' => $row['module_id'],
                'struct_id' => $row['struct_id'],
                'weight' => $row['weight'],
                'keywords_unicode' => $row['keywords_unicode'],
                'title_unicode' => $row['title_unicode'],
                'is_have_indexpic' => $row['is_have_indexpic'],
                'is_have_video' => $row['is_have_video'],
                'column_url' => $row['column_url'],
                'file_dir' => $row['file_dir'],
                'file_custom_filename' => $row['file_custom_filename'],
                'file_name' => $row['file_name'],
                'file_domain' => $row['file_domain'],
                'publish_time' => $row['publish_time'],
                'create_time' => $row['create_time'],
                'verify_time' => $row['verify_time'],
                'status' => $row['status'],
                'is_complete' => $row['is_complete'],
            ); //27
            self::insert('m2o_content_relation_1', $content_relation);

            $content = array(
                'id' => $row['id'],
                'plan_set_id' => $row['plan_set_id'],
                'expand_id' => $row['expand_id'],
                'bundle_id' => $row['bundle_id'],
                'module_id' => $row['module_id'],
                'struct_id' => $row['struct_id'],
                'content_fromid' => $row['content_fromid'],
                'column_id' => $row['ccolumn_id'],
                'title' => $row['title'],
                'subtitle' => $row['subtitle'],
                'tcolor' => $row['tcolor'],
                'isbold' => $row['isbold'],
                'isitalic' => $row['isitalic'],
                'brief' => $row['brief'],
                'keywords' => $row['keywords'],
                'indexpic' => $row['indexpic'],
                'video' => $row['video'],
                'outlink' => $row['outlink'],
                'use_maincolumn' => $row['use_maincolumn'],
                'childs_data' => $row['childs_data'],
                'child_num' => $row['child_num'],
                'ip' => $row['ip'],
                'appid' => $row['appid'],
                'appname' => $row['appname'],
                'publish_user' => $row['publish_user'],
                'create_user' => $row['create_user'],
                'verify_user' => $row['verify_user'],
                'template_sign' => $row['template_sign'],
            ); //28
            //self::insert('m2o_content_1',$content);
        }
    }
     */
    
    public function content_up2_step1()
    {
        //建表
        $sql = "
CREATE TABLE `" . DB_PREFIX . "content_new`(
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `plan_set_id` int(10) NOT NULL,
  `expand_id` int(10) NOT NULL,
  `bundle_id` char(30) NOT NULL,
  `module_id` char(30) NOT NULL,
  `struct_id` char(30) NOT NULL,
  `content_fromid` int(10) NOT NULL,
  `site_id` int(10) NOT NULL,
  `column_id` int(10) NOT NULL,
  `title` varchar(300) NOT NULL,
  `subtitle` varchar(300) NOT NULL,
  `tcolor` char(15) NOT NULL,
  `isbold` tinyint(1) NOT NULL,
  `isitalic` tinyint(1) NOT NULL,
  `brief` varchar(1000) NOT NULL,
  `keywords` varchar(300) NOT NULL,
  `indexpic` varchar(300) NOT NULL,
  `video` varchar(300) NOT NULL,
  `outlink` varchar(200) NOT NULL,
  `source` varchar(200) NOT NULL,
  `use_maincolumn` tinyint(1) NOT NULL,
  `childs_data` text NOT NULL,
  `child_num` int(10) NOT NULL,
  `ip` char(16) NOT NULL,
  `appid` int(10) NOT NULL,
  `appname` varchar(60) NOT NULL,
  `publish_user` varchar(60) NOT NULL,
  `create_user` varchar(60) NOT NULL,
  `verify_user` varchar(60) NOT NULL,
  `template_sign` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `expand_id` (`expand_id`),
  KEY `bms` (`bundle_id`,`module_id`,`struct_id`),
  KEY `content_fromid` (`content_fromid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

";
        $this->db->query($sql);
        echo 'content_new表已创建' . str_repeat(' ', 4096);
        echo "<br>";
        ob_flush();
        $sql = "
CREATE TABLE " . DB_PREFIX . "content_client_relation_new (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `relation_id` int(10) NOT NULL,
  `client_type` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `relation_id` (`relation_id`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
        $this->db->query($sql);
        echo 'content_client_relation_new表已创建' . str_repeat(' ', 4096);
        echo "<br>";
        ob_flush();
        $sql = "CREATE TABLE " . DB_PREFIX . "content_relation_new (`id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `content_id` int(10) NOT NULL,
  `site_id` int(4) NOT NULL,
  `column_id` int(10) NOT NULL,
  `column_name` varchar(60) NOT NULL,
  `bundle_id` char(30) NOT NULL,
  `module_id` char(30) NOT NULL,
  `struct_id` char(30) NOT NULL,
  `content_fromid` int(10) NOT NULL,
  `weight` int(4) NOT NULL,
  `is_have_indexpic` tinyint(1) NOT NULL,
  `is_have_video` tinyint(1) NOT NULL,
  `keywords_unicode` varchar(500) NOT NULL,
  `title_unicode` varchar(400) NOT NULL,
  `share_num` int(10) NOT NULL,
  `comment_num` int(10) NOT NULL,
  `click_num` int(10) NOT NULL,
  `file_dir` varchar(200) NOT NULL,
  `file_custom_filename` varchar(60) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `file_domain` varchar(60) NOT NULL,
  `publish_time` int(10) NOT NULL,
  `publish_user` char(60) NOT NULL,
  `create_time` int(10) NOT NULL,
  `create_user` char(60) NOT NULL,
  `verify_time` int(10) NOT NULL,
  `verify_user` char(60) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_complete` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `is_have_indexpic` (`is_have_indexpic`),
  KEY `is_have_video` (`is_have_video`),
  KEY `order_id` (`order_id`),
  KEY `site_id` (`site_id`) USING BTREE,
  KEY `column_id` (`column_id`),
  KEY `weight` (`weight`),
  KEY `create_time` (`create_time`),
  KEY `publish_time` (`publish_time`),
  KEY `content_id` (`content_id`),
  KEY `bms` (`bundle_id`,`module_id`,`struct_id`),
  KEY `content_fromid` (`content_fromid`),
  KEY `status` (`status`),
  FULLTEXT KEY `keywords_unicode` (`keywords_unicode`),
  FULLTEXT KEY `title_unicode` (`title_unicode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        $this->db->query($sql);
        echo 'content_relation_new表已创建' . str_repeat(' ', 4096);
        echo "<br>";
        ob_flush();

    }
    
    public function content_up2_step2()
    {
        $rid_exist = $cid_exist = array();
        //查询已经存在的rid
        $sql = "select id from ".DB_PREFIX."content_relation_new";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $rid_exist[$row['id']] = $row['id'];
        }
        
        //查询已经存在的cid
        $sql = "select id from ".DB_PREFIX."content_new";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $cid_exist[$row['id']] = $row['id'];
        }
        
        //拷贝数据
        $sql  = "select r.*,c.*,cr.weight as weight,cr.order_id as order_id,cr.publish_time as publish_time,cr.client_type as client_type,cr.id as id,cr.relation_id as rid,
                r.column_id as column_id,r.site_id as site_id,r.column_name as column_name,
                c.id as cid,c.column_id as ccolumn_id,c.site_id as ssite_id
                from " . DB_PREFIX . "content_client_relation cr left join " . DB_PREFIX . "content_relation r on cr.relation_id=r.id left join " . DB_PREFIX . "content c on r.content_id=c.id ";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            if (!$row['cid'] || !$row['rid'])
            {
                continue;
            }
            $content_client_relation = array();
            $content_relation        = array();
            $content                 = array();

            $content_client_relation = array(
                'id' => $row['id'],
                'relation_id' => $row['rid'],
                'client_type' => $row['client_type'],
            );
            self::insert(DB_PREFIX . 'content_client_relation_new', $content_client_relation);


            if (!$rid_exist[$row['rid']])
            {
                if (!empty($data['title']))
                {
                    //$row['title_unicode'] = $this->get_titleResult($row['title']);
                }
                $content_relation       = array(
                    'id' => $row['rid'],
                    'order_id' => $row['order_id'],
                    'content_id' => $row['cid'],
                    'site_id' => $row['site_id'],
                    'column_id' => $row['column_id'],
                    'column_name' => $row['column_name'],
                    'bundle_id' => $row['bundle_id'],
                    'module_id' => $row['module_id'],
                    'struct_id' => $row['struct_id'],
                    'content_fromid' => $row['content_fromid'],
                    'weight' => $row['weight'],
                    'keywords_unicode' => $row['keywords_unicode'],
                    'title_unicode' => $row['title_unicode'],
                    'is_have_indexpic' => $row['is_have_indexpic'],
                    'is_have_video' => $row['is_have_video'],
                    'share_num' => $row['share_num'],
                    'click_num' => $row['click_num'],
                    'comment_num' => $row['comment_num'],
                    'file_dir' => $row['file_dir'],
                    'file_custom_filename' => $row['file_custom_filename'],
                    'file_name' => $row['file_name'],
                    'file_domain' => $row['file_domain'],
                    'publish_time' => $row['publish_time'],
                    'create_time' => $row['create_time'],
                    'verify_time' => $row['verify_time'],
                    'publish_user' => $row['publish_user'],
                    'create_user' => $row['create_user'],
                    'verify_user' => $row['verify_user'],
                    'status' => 1,
                    'is_complete' => 1,
                ); //27
                self::insert(DB_PREFIX . 'content_relation_new', $content_relation);
                $rid_exist[$row['rid']] = $row['rid'];
            }

            if (!$cid_exist[$row['cid']])
            {
                $content                = array(
                    'id' => $row['cid'],
                    'plan_set_id' => $row['plan_set_id'],
                    'expand_id' => $row['expand_id'],
                    'bundle_id' => $row['bundle_id'],
                    'module_id' => $row['module_id'],
                    'struct_id' => $row['struct_id'],
                    'content_fromid' => $row['content_fromid'],
                    'site_id' => $row['ssite_id'],
                    'column_id' => $row['ccolumn_id'],
                    'title' => $row['title'],
                    'subtitle' => $row['subtitle'],
                    'tcolor' => $row['tcolor'],
                    'isbold' => $row['isbold'],
                    'isitalic' => $row['isitalic'],
                    'brief' => $row['brief'],
                    'keywords' => $row['keywords'],
                    'indexpic' => $row['indexpic'],
                    'video' => $row['video'],
                    'outlink' => $row['outlink'],
                    'use_maincolumn' => $row['use_maincolumn'],
                    'childs_data' => $row['childs_data'],
                    'child_num' => $row['child_num'],
                    'ip' => $row['ip'],
                    'appid' => $row['appid'],
                    'appname' => $row['appname'],
                    'publish_user' => $row['publish_user'],
                    'create_user' => $row['create_user'],
                    'verify_user' => $row['verify_user'],
                    'template_sign' => $row['template_sign'],
                ); //28
                self::insert(DB_PREFIX . 'content_new', $content);
                $cid_exist[$row['cid']] = $row['cid'];
            }
        }
        echo '数据导入成功' . str_repeat(' ', 4096);
        echo "<br>";
        ob_flush();
    }
    
    public function content_up2_step3()
    {
        //换表
        $sql = "alter table " . DB_PREFIX . "content rename " . DB_PREFIX . "content_old";
        $this->db->query($sql);
        $sql = "alter table " . DB_PREFIX . "content_relation rename " . DB_PREFIX . "content_relation_old";
        $this->db->query($sql);
        $sql = "alter table " . DB_PREFIX . "content_client_relation rename " . DB_PREFIX . "content_client_relation_old";
        $this->db->query($sql);
        echo '重命名旧表成功' . str_repeat(' ', 4096);
        echo "<br>";
        ob_flush();
        $sql = "alter table " . DB_PREFIX . "content_new rename " . DB_PREFIX . "content";
        $this->db->query($sql);
        $sql = "alter table " . DB_PREFIX . "content_relation_new rename " . DB_PREFIX . "content_relation";
        $this->db->query($sql);
        $sql = "alter table " . DB_PREFIX . "content_client_relation_new rename " . DB_PREFIX . "content_client_relation";
        $this->db->query($sql);
        echo '重命名新表成功' . str_repeat(' ', 4096);
        echo "<br>";
        $sql = "OPTIMIZE TABLE " . DB_PREFIX . "content_client_relation ";
        $this->db->query($sql);
        echo '优化content_client_relation表成功' . str_repeat(' ', 4096);
        echo "<br>";
        $sql = "OPTIMIZE TABLE " . DB_PREFIX . "content_relation ";
        $this->db->query($sql);
        echo '优化content_relation表成功' . str_repeat(' ', 4096);
        echo "<br>";
        $sql = "OPTIMIZE TABLE " . DB_PREFIX . "content ";
        $this->db->query($sql);
        echo '优化content表成功' . str_repeat(' ', 4096);
        echo "<br>";
        ob_flush();
    }
    
    public function content_up2_step3_back()
    {
        //换表
        $sql = "alter table " . DB_PREFIX . "content rename " . DB_PREFIX . "content_new";
        $this->db->query($sql);
        $sql = "alter table " . DB_PREFIX . "content_relation rename " . DB_PREFIX . "content_relation_new";
        $this->db->query($sql);
        $sql = "alter table " . DB_PREFIX . "content_client_relation rename " . DB_PREFIX . "content_client_relation_new";
        $this->db->query($sql);
        echo '重命名旧表成功' . str_repeat(' ', 4096);
        echo "<br>";
        ob_flush();
        $sql = "alter table " . DB_PREFIX . "content_old rename " . DB_PREFIX . "content";
        $this->db->query($sql);
        $sql = "alter table " . DB_PREFIX . "content_relation_old rename " . DB_PREFIX . "content_relation";
        $this->db->query($sql);
        $sql = "alter table " . DB_PREFIX . "content_client_relation_old rename " . DB_PREFIX . "content_client_relation";
        $this->db->query($sql);
        echo '重命名新表成功' . str_repeat(' ', 4096);
        echo "<br>";
        ob_flush();
    }
    
    //升级发布库需要执行的方法
    public function content_up2()
    {
        set_time_limit(0);
        ob_start();
        $this->content_up2_step1();
        $this->content_up2_step2();
        //$this->content_up2_step3();
    }

    public function insert($table, $data)
    {
        $sql = "INSERT INTO " . $table . " SET ";

        if (is_array($data))
        {
            $sql_extra = $space     = ' ';
            foreach ($data as $k => $v)
            {
                $sql_extra .=$space . $k . "='" . $v . "'";
                $space = ',';
            }
            $sql .=$sql_extra;
        }
        else
        {
            $sql .= $data;
        }
        $this->db->query($sql);
        return $this->db->insert_id();
    }
    
    public function update_title_unicode()
    {
        $sql = "select id,title from ".DB_PREFIX."content ";
        $info = $this->db->query($sql);
        while($row = $this->db->fetch_array($info))
        {
            $title_unicode = $this->get_titleResult($row['title']);
            $sql1 = "update ".DB_PREFIX."content_relation set title_unicode='".$title_unicode."' where content_id=".$row['id'];
            $this->db->query($sql1);
        }
    }
    
    public function update_title_unicode1()
    {
        set_time_limit(0);
        $offset = intval($this->input['offset']);
        $count=10000;
        $rd = array();
        $sql = "select r.id,c.id as cid,c.title from ".DB_PREFIX."content_relation r left join ".DB_PREFIX."content c on r.content_id=c.id where r.title_unicode='' order by r.id limit $offset,$count ";
        $info = $this->db->query($sql);
        while($row = $this->db->fetch_array($info))
        {
            $rd[] = $row;
        }
        foreach($rd as $k=>$row)
        {
            $title_unicode = $this->get_titleResult($row['title']);
            $sql1 = "update ".DB_PREFIX."content_relation set title_unicode='".$title_unicode."' where content_id=".$row['cid'];
            $this->db->query($sql1);
        }
    }
    
    public function update_title_pinyin()
    {
        set_time_limit(0);
        $offset = intval($this->input['offset']);
        $count=10000;
        $rd = array();
        $sql = "select r.id,c.id as cid,c.title from ".DB_PREFIX."content_relation r left join ".DB_PREFIX."content c on r.content_id=c.id where r.title_unicode='' order by r.id limit $offset,$count ";
        $info = $this->db->query($sql);
        while($row = $this->db->fetch_array($info))
        {
            $rd[] = $row;
        }
        foreach($rd as $k=>$row)
        {
            $title_unicode = $this->get_titleResult($row['title']);
            $title_pinyin_str = get_spell_title($row['title']);
            $sql1 = "update ".DB_PREFIX."content_relation set title_pinyin='".$title_pinyin_str."' where content_id=".$row['cid'];
            $this->db->query($sql1);
        }
    }

    /**
     * 空方法
     * @name unknow
     * @access public
     * @author repheal
     * @category hogesoft
     * @copyright 	ho	gesoft
     */
    function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }

}

$out    = new content_upgradeApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'all';
}
$out->$action();
?>
