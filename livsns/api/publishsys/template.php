<?php

define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH . 'global.php');
require(CUR_CONF_PATH . "lib/functions.php");
define('MOD_UNIQUEID', 'template'); //模块标识

class template extends adminBase
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
        $condition  = $this->get_condition();
        $offset     = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count      = $this->input['count'] ? intval($this->input['count']) : 20;
        $data_limit = ' LIMIT ' . $offset . ', ' . $count;
        $sql        = "SELECT id, site_id, sort_id, title, pic, sign, template_style, app_uniqueid, file_name, file_path, client
				FROM " . DB_PREFIX . "templates WHERE 1 " . $condition . $data_limit;
        $q          = $this->db->query($sql);
        while ($row        = $this->db->fetch_array($q))
        {
            $row['pic'] = json_decode($row['pic'], 1);
            $row['pic'] = $row['pic'] ? $row['pic'] : array();
            if (is_array($row['pic']) && count($row['pic']) > 0)
            {
                foreach ($row['pic'] as $k => $v)
                {
                    if ($v)
                    {
                        $row['pic'][$k] = array(
                            'host' => $v['host'],
                            'dir' => $v['dir'],
                            'filepath' => $v['filepath'],
                            'filename' => $v['filename'],
                        );
                    }
                }
            }
            $ret[] = $row;
        }
        $this->addItem($ret);
        $this->output();
    }

    private function get_condition()
    {
        $condition = '';
        if (isset($this->input['app_uniqueid']))
        {
            $condition = "  AND app_uniqueid = '" . $this->input['app_uniqueid'] . "'";
        }
        if ($this->input['tag'])
        {
            $condition = "  AND tag = " . $this->input['tag'];
        }
        return $condition;
    }

	public function get_template_tag()
    {
    	$offset     = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count      = $this->input['count'] ? intval($this->input['count']) : 20;
        $data_limit = ' LIMIT ' . $offset . ', ' . $count;
        
        $sql  = "select * from " . DB_PREFIX . "template_tag where 1" .$data_limit;
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $data[] = $row;
        }
        $this->addItem($data);
        $this->output();
    }
    
    public function get_template_info()
    {
    	$template_id	= $this->input['template_id'] ;
        
        $sql  = "select * from " . DB_PREFIX . "templates where id = " .$template_id;
        $info = $this->db->query_first($sql);
        
        $this->addItem($info);
        $this->output();
    }
    
    function index()
    {
        $sql  = "select id,mode_sign from " . DB_PREFIX . "cell_mode where mode_sign!=''";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $data[$row['id']] = $row['mode_sign'];
        }
        var_export($data);
    }

    function index2()
    {
        $data = array(1 => 'b_1', 22 => 'b_22', 35 => 'a_35', 28 => 'b_28', 25 => 'b_25', 32 => 'a_32', 37 => 'a_37', 38 => 'a_38', 40 => 'a_40', 106 => 'c_106', 187 => 'c_187', 61 => 'a_61', 83 => 'a_83', 96 => 'a_96', 121 => 'c_121', 120 => 'c_120', 127 => 'c_127', 129 => 'c_129', 143 => 'a_143', 155 => 'c_155', 193 => 'a_193',);
        $sql  = "select id,cell_mode from " . DB_PREFIX . "layout_cell ";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $sign = $data[$row['cell_mode']];
            if (!$sign)
            {
                continue;
            }
            $sql1   = "select id from " . DB_PREFIX . "cell_mode where mode_sign='" . $sign . "'";
            $detail = $this->db->query_first($sql1);
            if ($detail)
            {
                $sql2 = "update " . DB_PREFIX . "layout_cell set cell_mode=" . $detail['id'] . " where id=" . $row['id'];
                $b[]  = $sql2;
                //$this->db->query($sql2);
            }
        }
    }

    function index3()
    {
        $data = array(1 => 'b_1', 22 => 'b_22', 35 => 'a_35', 28 => 'b_28', 25 => 'b_25', 32 => 'a_32', 37 => 'a_37', 38 => 'a_38', 40 => 'a_40', 106 => 'c_106', 187 => 'c_187', 61 => 'a_61', 83 => 'a_83', 96 => 'a_96', 121 => 'c_121', 120 => 'c_120', 127 => 'c_127', 129 => 'c_129', 143 => 'a_143', 155 => 'c_155', 193 => 'a_193',);
        $sql  = "select id,cell_mode from " . DB_PREFIX . "cell where template_id>450";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $sign = $data[$row['cell_mode']];
            if (!$sign)
            {
                continue;
            }
            $sql1   = "select id from " . DB_PREFIX . "cell_mode where mode_sign='" . $sign . "'";
            $detail = $this->db->query_first($sql1);
            if ($detail)
            {
                $sql2 = "update " . DB_PREFIX . "cell set cell_mode=" . $detail['id'] . " where id=" . $row['id'];
                $this->db->query($sql2);
            }
        }
    }

    function index4()
    {
        $sql  = "select * from " . DB_PREFIX . "deploy_template order by id";
        $info = $this->db->query($sql);
        $k    = 0;
        while ($row  = $this->db->fetch_array($info))
        {
            if ($r[$row['site_id']][$row['page_id']][$row['page_data_id']])
            {
                $ret[$re[$row['site_id']][$row['page_id']][$row['page_data_id']]][] = $row;
            }
            else
            {
                $ret[$k][]                                                   = $row;
                $re[$row['site_id']][$row['page_id']][$row['page_data_id']]  = $k;
                $r[$row['site_id']][$row['page_id']][$row['page_data_id']][] = $row;
                $k++;
            }
        }
        foreach ($ret as $k => $v)
        {
            foreach ($v as $kk => $vv)
            {
                $sql = "update " . DB_PREFIX . "deploy_template set group_id=" . ($k + 1) . " where id=" . $vv['id'];
                $this->db->query($sql);
            }
        }
    }

    public function ds_sign()
    {
        $sql  = "select id from " . DB_PREFIX . "cell";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $sign = substr(md5($row['id']), 0, 13);
            $sql  = "update " . DB_PREFIX . "cell set sign='" . $sign . "' where id=" . $row['id'];
            $this->db->query($sql);
        }
    }

    public function get_mode_sign()
    {
        $sql  = "select id,sign,title from " . DB_PREFIX . "cell_mode";
        $info = $this->db->fetch_all($sql);
        var_export($info);
    }

    public function update_mode_sign()
    {
        $mode = array(
            0 =>
            array(
                'id' => '1',
                'sign' => '528aca4cca3e9',
                'title' => '文字列表默认样式',
            ),
            1 =>
            array(
                'id' => '22',
                'sign' => 'b6d767d2f8ed5',
                'title' => '栏目文字列表样式',
            ),
            2 =>
            array(
                'id' => '35',
                'sign' => '1c383cd30b7c2',
                'title' => '图片(浮动)标题简介样式',
            ),
            3 =>
            array(
                'id' => '28',
                'sign' => '33e75ff09dd60',
                'title' => '标题日期列表样式',
            ),
            4 =>
            array(
                'id' => '25',
                'sign' => '8e296a067a375',
                'title' => '标题简介样式',
            ),
            5 =>
            array(
                'id' => '27',
                'sign' => '02e74f10e0327',
                'title' => '栏目列表默认样式',
            ),
            6 =>
            array(
                'id' => '29',
                'sign' => '6ea9ab1baa0ef',
                'title' => '文章分页列表样式',
            ),
            7 =>
            array(
                'id' => '30',
                'sign' => '34173cb38f07f',
                'title' => '父级子级导航样式',
            ),
            8 =>
            array(
                'id' => '31',
                'sign' => 'c16a5320fa475',
                'title' => '父级子级栏目样式',
            ),
            9 =>
            array(
                'id' => '32',
                'sign' => '6364d3f0f495b',
                'title' => '图片列表样式',
            ),
            10 =>
            array(
                'id' => '103',
                'sign' => '6974ce5ac6606',
                'title' => '三列广告样式',
            ),
            11 =>
            array(
                'id' => '34',
                'sign' => '52944110cbcf5',
                'title' => '文章正文样式',
            ),
            12 =>
            array(
                'id' => '37',
                'sign' => 'a5bfc9e07964f',
                'title' => '图片标题（遮罩定位）简介',
            ),
            13 =>
            array(
                'id' => '38',
                'sign' => 'a5771bce93e20',
                'title' => '标题简介轮转图',
            ),
            14 =>
            array(
                'id' => '39',
                'sign' => 'd67d8ab4f4c10',
                'title' => '栏目导航样式',
            ),
            15 =>
            array(
                'id' => '40',
                'sign' => 'd645920e395fe',
                'title' => '整列滚动图片列表样式',
            ),
            16 =>
            array(
                'id' => '81',
                'sign' => '43ec517d68b6e',
                'title' => '选项卡栏目图片列表样式',
            ),
            17 =>
            array(
                'id' => '53',
                'sign' => 'd82c8d1619ad8',
                'title' => '文字列表样式ul-li-a(x)',
            ),
            18 =>
            array(
                'id' => '102',
                'sign' => '529c39f4734d9',
                'title' => '版权信息',
            ),
            19 =>
            array(
                'id' => '104',
                'sign' => '52898c7de8e05',
                'title' => '当前位置默认样式',
            ),
            20 =>
            array(
                'id' => '105',
                'sign' => '65b9eea6e1cc6',
                'title' => '图片分页列表样式',
            ),
            21 =>
            array(
                'id' => '106',
                'sign' => 'f0935e4cd5920',
                'title' => '点播播放器',
            ),
            22 =>
            array(
                'id' => '185',
                'sign' => 'eecca5b6365d9',
                'title' => '专题会议顶部样式',
            ),
            23 =>
            array(
                'id' => '186',
                'sign' => '9872ed9fc22fc',
                'title' => '专题会议顶部样式之联系我们',
            ),
            24 =>
            array(
                'id' => '187',
                'sign' => '529f42342618e',
                'title' => '专题头图样式',
            ),
            25 =>
            array(
                'id' => '54',
                'sign' => 'a684eceee76fc',
                'title' => '栏目标题列表样式ul-li-a(x)',
            ),
            26 =>
            array(
                'id' => '61',
                'sign' => '7f39f8317fbdb',
                'title' => '图片标题简介样式',
            ),
            27 =>
            array(
                'id' => '82',
                'sign' => '9778d5d219c50',
                'title' => '栏目名称样式',
            ),
            28 =>
            array(
                'id' => '83',
                'sign' => 'fe9fc289c3ff0',
                'title' => '图片标题定位',
            ),
            29 =>
            array(
                'id' => '84',
                'sign' => '52a294b647048',
                'title' => '标题图片(左)简介(右)',
            ),
            30 =>
            array(
                'id' => '85',
                'sign' => '3ef815416f775',
                'title' => '调查投票列表样式',
            ),
            31 =>
            array(
                'id' => '98',
                'sign' => 'ed3d2c21991e3',
                'title' => '图片简介列表样式',
            ),
            32 =>
            array(
                'id' => '96',
                'sign' => '26657d5ff9020',
                'title' => '图片轮转带缩略图',
            ),
            33 =>
            array(
                'id' => '99',
                'sign' => 'ac627ab1ccbdb',
                'title' => '两列广告样式',
            ),
            34 =>
            array(
                'id' => '100',
                'sign' => 'f899139df5e10',
                'title' => '栏目示意图样式',
            ),
            35 =>
            array(
                'id' => '101',
                'sign' => '528afa71646e6',
                'title' => '系统搜索样式',
            ),
            36 =>
            array(
                'id' => '107',
                'sign' => 'a97da629b098b',
                'title' => '直播播放器样式',
            ),
            37 =>
            array(
                'id' => '108',
                'sign' => 'a3c65c2974270',
                'title' => '直播通用静态导航样式-参考',
            ),
            38 =>
            array(
                'id' => '109',
                'sign' => '529da8a763af6',
                'title' => '直播节目表样式',
            ),
            39 =>
            array(
                'id' => '110',
                'sign' => '5f93f983524de',
                'title' => '原系统图集',
            ),
            40 =>
            array(
                'id' => '113',
                'sign' => '73278a4a86960',
                'title' => '点播播放器样式',
            ),
            41 =>
            array(
                'id' => '111',
                'sign' => '698d51a19d8a1',
                'title' => '图片标题焦点图轮转',
            ),
            42 =>
            array(
                'id' => '121',
                'sign' => '4c56ff4ce4aaf',
                'title' => '评论表单样式',
            ),
            43 =>
            array(
                'id' => '114',
                'sign' => '5fd0b37cd7dbb',
                'title' => '视频信息',
            ),
            44 =>
            array(
                'id' => '115',
                'sign' => '2b44928ae11fb',
                'title' => '     栏目-标题样式',
            ),
            45 =>
            array(
                'id' => '118',
                'sign' => '5ef059938ba79',
                'title' => '图片标题简介导演库',
            ),
            46 =>
            array(
                'id' => '120',
                'sign' => 'da4fb5c6e93e7',
                'title' => '评论分页列表样式',
            ),
            47 =>
            array(
                'id' => '122',
                'sign' => 'a0a080f42e6f1',
                'title' => '用户登录默认样式',
            ),
            48 =>
            array(
                'id' => '123',
                'sign' => '529ec18d2858b',
                'title' => '检索结果样式-废',
            ),
            49 =>
            array(
                'id' => '127',
                'sign' => 'ec5decca5ed3d',
                'title' => '评论列表样式',
            ),
            50 =>
            array(
                'id' => '129',
                'sign' => 'd1f491a404d68',
                'title' => '单个文章正文',
            ),
            51 =>
            array(
                'id' => '143',
                'sign' => '903ce9225fca3',
                'title' => '单个图集样式',
            ),
            52 =>
            array(
                'id' => '184',
                'sign' => '6cdd60ea0045e',
                'title' => '点播播放器样式-普通',
            ),
            53 =>
            array(
                'id' => '253',
                'sign' => '52a2a8ebe9c5a',
                'title' => '文章检索分页列表样式1',
            ),
            54 =>
            array(
                'id' => '133',
                'sign' => '9fc3d7152ba93',
                'title' => '图集样式',
            ),
            55 =>
            array(
                'id' => '252',
                'sign' => '03c6b06952c75',
                'title' => '图片变焦收缩样式x',
            ),
            56 =>
            array(
                'id' => '154',
                'sign' => '1d7f7abc18fcb',
                'title' => '标题图片简介第一条',
            ),
            57 =>
            array(
                'id' => '335',
                'sign' => 'f9b902fc3289a',
                'title' => 'AH报名表单样式',
            ),
            58 =>
            array(
                'id' => '155',
                'sign' => '2a79ea27c279e',
                'title' => '专题导读',
            ),
            59 =>
            array(
                'id' => '146',
                'sign' => '528afa8807059',
                'title' => '搜索样式带类型',
            ),
            60 =>
            array(
                'id' => '159',
                'sign' => '140f6969d5213',
                'title' => '图片列表样式-精简版',
            ),
            61 =>
            array(
                'id' => '192',
                'sign' => '58a2fc6ed39fd',
                'title' => '关键字旋转样式',
            ),
            62 =>
            array(
                'id' => '193',
                'sign' => 'bd686fd640be9',
                'title' => '图片变焦收缩样式',
            ),
            63 =>
            array(
                'id' => '254',
                'sign' => 'c52f1bd66cc19',
                'title' => '专题正文样式',
            ),
            64 =>
            array(
                'id' => '330',
                'sign' => '529ebf6d658f3',
                'title' => '检索条件',
            ),
            65 =>
            array(
                'id' => '333',
                'sign' => '310dcbbf4cce6',
                'title' => '点播复制样式',
            ),
            66 =>
            array(
                'id' => '343',
                'sign' => '528afac5785eb',
                'title' => '文章检索分页列表样式',
            ),
            67 =>
            array(
                'id' => '349',
                'sign' => '52983886f0ac0',
                'title' => 'qk_列表样式',
            ),
            68 =>
            array(
                'id' => '360',
                'sign' => 'e7b24b112a44f',
                'title' => '视频直播整体样式',
            ),
            69 =>
            array(
                'id' => '362',
                'sign' => '52a149debcfaa',
                'title' => '评论表单样式带登陆',
            ),
            70 =>
            array(
                'id' => '363',
                'sign' => '00411460f7c92',
                'title' => '广告位',
            ),
            71 =>
            array(
                'id' => '364',
                'sign' => '528c7931859f8',
                'title' => '瀑布流分页样式',
            ),
            72 =>
            array(
                'id' => '412',
                'sign' => '52a2a94dc8d48',
                'title' => '腾讯科技图片列表样式',
            ),
            73 =>
            array(
                'id' => '413',
                'sign' => '0deb1c5481430',
                'title' => '腾讯科技-CEO-STYLE',
            ),
            74 =>
            array(
                'id' => '398',
                'sign' => '528c28c6ae7ff',
                'title' => '电视剧集列表样式-播放器页',
            ),
            75 =>
            array(
                'id' => '399',
                'sign' => '352fe25daf686',
                'title' => '腾讯科技广告1-FLASH',
            ),
            76 =>
            array(
                'id' => '400',
                'sign' => '18d8042386b79',
                'title' => '腾讯科技首页-LOGO',
            ),
            77 =>
            array(
                'id' => '401',
                'sign' => '816b112c6105b',
                'title' => '腾讯科技-合作伙伴LINK',
            ),
            78 =>
            array(
                'id' => '402',
                'sign' => '69cb3ea317a32',
                'title' => '腾讯底部版权',
            ),
            79 =>
            array(
                'id' => '403',
                'sign' => 'bbf94b34eb322',
                'title' => '腾讯广告2',
            ),
            80 =>
            array(
                'id' => '404',
                'sign' => '4f4adcbf8c6f6',
                'title' => '腾讯5头条图文混编',
            ),
            81 =>
            array(
                'id' => '405',
                'sign' => 'bbcbff5c1f1de',
                'title' => '腾讯六条列式，新闻',
            ),
            82 =>
            array(
                'id' => '406',
                'sign' => '8cb22bdd0b7ba',
                'title' => '腾讯分页',
            ),
            83 =>
            array(
                'id' => '407',
                'sign' => 'f4f6dce2f3a0f',
                'title' => '腾讯-人物志',
            ),
            84 =>
            array(
                'id' => '408',
                'sign' => '0d0fd7c6e093f',
                'title' => '腾讯-数码控',
            ),
            85 =>
            array(
                'id' => '409',
                'sign' => 'a96b65a721e56',
                'title' => '腾讯-趋势报告',
            ),
            86 =>
            array(
                'id' => '410',
                'sign' => '1068c6e4c8051',
                'title' => '腾讯科技-数码控-文字新闻',
            ),
            87 =>
            array(
                'id' => '421',
                'sign' => '528ad037a6b3f',
                'title' => '文字列表样式类型自适应',
            ),
            88 =>
            array(
                'id' => '422',
                'sign' => '5295a2845b6e8',
                'title' => '1安徽广告HTML静态样式1',
            ),
            89 =>
            array(
                'id' => '423',
                'sign' => '5295b3ea932fb',
                'title' => '面包屑(当前位置)导航',
            ),
            90 =>
            array(
                'id' => '424',
                'sign' => '529c23d5c01b8',
                'title' => '图片简介列表样式不带评论',
            ),
            91 =>
            array(
                'id' => '425',
                'sign' => '529d413b69817',
                'title' => '爆料样式',
            ),
            92 =>
            array(
                'id' => '426',
                'sign' => '529ecd02d67d7',
                'title' => '安徽检索导航',
            ),
            93 =>
            array(
                'id' => '427',
                'sign' => '52a021643c0c4',
                'title' => '杂志正文样式',
            ),
            94 =>
            array(
                'id' => '430',
                'sign' => '52a13fd95d332',
                'title' => '杂志封面样式',
            ),
            95 =>
            array(
                'id' => '428',
                'sign' => '529ff280c0783',
                'title' => '厚建版权信息',
            ),
            96 =>
            array(
                'id' => '429',
                'sign' => '52a141d4b3b6f',
                'title' => '杂志数据源测试',
            ),
            97 =>
            array(
                'id' => '431',
                'sign' => '52a1680af07d2',
                'title' => '杂志列表',
            ),
            98 =>
            array(
                'id' => '432',
                'sign' => '52a14a2981734',
                'title' => ' 登陆注册连接',
            ),
            99 =>
            array(
                'id' => '434',
                'sign' => '52a16d37bf4cc',
                'title' => '期刊号',
            ),
        );

        foreach ($mode as $k => $v)
        {
            $sql  = "select id from " . DB_PREFIX . "cell_mode where trim(title)='" . trim($v['title']) . "'";
            $info = $this->db->query_first($sql);
            if ($info)
            {
                $sql = "update " . DB_PREFIX . "cell_mode set sign='" . $v['sign'] . "' where id=" . $info['id'];
                $this->db->query($sql);
            }
        }
    }
    
    public function get_mode_css_sign()
    {    		
        $sql  = "select id,sign from " . DB_PREFIX . "cell_mode WHERE 1";
        $info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$ret[$row['id']] = $row['sign'];
		}
		
		$sqll  = "select sign,mode_id,title from " . DB_PREFIX . "cell_mode_code WHERE sign!='' AND type = 'css'";
        $info_ = $this->db->query($sqll);
		while($row_ = $this->db->fetch_array($info_))
		{
			if($ret[$row_['mode_id']])
			{
				$arr[$ret[$row_['mode_id']]][$row_['title']] = $row_['sign'];
			}
		}
		//file_put_contents('0q',var_export($arr,1));
    }
    
    public function update_mode_css_sign()
    {
    	$arr = array (
		  '528aca4cca3e9' => 
		  array (
		    '主类名' => '14bfa6bb14875',
		    '多行多列' => 'ad61ab143223e',
		    '多行加粗' => '84d9ee44e457d',
		    '一行加粗' => '3644a684f98ea',
		    '一行x列' => '69adc1e107f7f',
		    '多行单列居中' => '6f3ef77ac0e36',
		    '哈哈' => 'bcbe3365e6ac9',
		    '多行浮动' => '5265d93d29430',
		  ),
		  'b6d767d2f8ed5' => 
		  array (
		    '默认样式' => 'e2c420d928d4b',
		    '多行多列' => 'd09bf41544a33',
		    '样式栏目不加粗' => '979d472a84804',
		  ),
		  '8e296a067a375' => 
		  array (
		    '主css' => 'd2ddea18f0066',
		    '标题可加粗居中' => '63923f49e5241',
		  ),
		  '33e75ff09dd60' => 
		  array (
		    '日期跟随' => '28dd2c7955ce9',
		    '默认样式' => '821fa74b50ba3',
		  ),
		  '6ea9ab1baa0ef' => 
		  array (
		    '主类名' => 'd1fe173d08e95',
		  ),
		  '02e74f10e0327' => 
		  array (
		    'acasd' => '43ec517d68b6e',
		    '默认类名' => '9778d5d219c50',
		    '列固定宽度' => '63dc7ed1010d3',
		  ),
		  '34173cb38f07f' => 
		  array (
		    '默认样式' => '68d30a9594728',
		  ),
		  '1c383cd30b7c2' => 
		  array (
		    '多列样式' => '92cc227532d17',
		    '默认样式' => '55a7cf9c71f1c',
		    '' => 'b1a59b315fc9a',
		  ),
		  '6364d3f0f495b' => 
		  array (
		    '默认样式' => '93db85ed909c1',
		    '图片左浮动' => '7eabe3a1649ff',
		    '有图无标题' => '45fbc6d3e05eb',
		    'No1排行' => '6c9882bbac1c7',
		    'ul_100比' => 'f718499c1c8ce',
		    '定制视频图标' => '36660e59856b4',
		    '标题有背景' => 'eda80a3d5b344',
		  ),
		  'c16a5320fa475' => 
		  array (
		    '默认样式' => '2a38a4a9316c4',
		  ),
		  '52a2f773666c7' => 
		  array (
		    '默认类' => '8613985ec49eb',
		  ),
		  'a5bfc9e07964f' => 
		  array (
		    '主css' => '26657d5ff9020',
		  ),
		  'a5771bce93e20' => 
		  array (
		    '定制遮罩' => '2bb232c0b13c7',
		    '默认样式' => 'ed3d2c21991e3',
		    '数字图片切换' => '38db3aed920cf',
		    '定制遮罩图层' => '53fde96fcc4b4',
		  ),
		  'd67d8ab4f4c10' => 
		  array (
		    '主css' => 'f899139df5e10',
		    '热剧' => '07cdfd23373b1',
		    '首页不特殊' => '303ed4c69846a',
		  ),
		  'd645920e395fe' => 
		  array (
		    '主css' => 'ec8956637a997',
		  ),
		  'd82c8d1619ad8' => 
		  array (
		    '主类名' => '65ded5353c5ee',
		  ),
		  'a684eceee76fc' => 
		  array (
		    ' 主类名' => '02522a2b2726f',
		    '栏目标题不加粗' => '757b505cfd34c',
		  ),
		  '7f39f8317fbdb' => 
		  array (
		    '主css' => 'a8baa56554f96',
		    '标题有背景css' => '0e65972dce68d',
		    '多行多列' => 'e96ed478dab85',
		    '图片标题（加背景）' => '060ad92489947',
		    '标题加粗居中' => 'c24cd76e1ce41',
		  ),
		  '43ec517d68b6e' => 
		  array (
		    '主类名' => 'e2c0be24560d7',
		  ),
		  'fe9fc289c3ff0' => 
		  array (
		    '定制视频图标' => 'b1d10e7bafa44',
		    '默认样式' => '38913e1d6a7b9',
		  ),
		  '52a294b647048' => 
		  array (
		    '多行多列' => '7380ad8a67322',
		    '默认样式' => '1534b76d325a8',
		    '多行' => '539fd53b59e3b',
		  ),
		  '9778d5d219c50' => 
		  array (
		    '主css' => 'eae27d77ca20d',
		    '栏目名称隐藏' => '8f121ce07d747',
		  ),
		  '5f93f983524de' => 
		  array (
		    '主类名' => '918317b57931b',
		    '' => '16a5cdae362b8',
		  ),
		  'a97da629b098b' => 
		  array (
		    '' => 'd947bf06a885d',
		  ),
		  '3ef815416f775' => 
		  array (
		    '默认样式' => '3b8a614226a95',
		  ),
		  '26657d5ff9020' => 
		  array (
		    '左右布局' => 'f340f1b1f65b6',
		    '多行' => 'e4a6222cdb5b3',
		    '上下布局' => 'f3f27a3247366',
		  ),
		  '52a2ebdc73746' => 
		  array (
		    '主css ' => '621bf66ddb7c9',
		    '多行' => '077e29b11be80',
		  ),
		  'ac627ab1ccbdb' => 
		  array (
		    '指定宽度' => '03c6b06952c75',
		  ),
		  '528afa71646e6' => 
		  array (
		    '主样式' => '502e4a16930e4',
		  ),
		  'f899139df5e10' => 
		  array (
		    '主类名' => 'fe131d7f5a6b3',
		  ),
		  '529c39f4734d9' => 
		  array (
		    '默认样式' => 'a4f23670e1833',
		  ),
		  '140f6969d5213' => 
		  array (
		    '列背景' => '5266278612697',
		    'ss' => 'f5f8590cd58a5',
		    '' => '8bf1211fd4b7b',
		    '复制34' => '28f0b864598a1',
		    '新增' => '8efb100a295c0',
		    '已有' => 'd9fc5b73a8d78',
		    '复制的' => 'c86a7ee3d8ef0',
		    '复制' => '5a4b25aaed25c',
		    '主类名' => '1651cf0d2f737',
		    12 => 'b534ba68236ba',
		  ),
		  '6974ce5ac6606' => 
		  array (
		    '指定宽度' => 'd6baf65e0b240',
		    '默认样式' => '526e1acc0ba36',
		  ),
		  '52898c7de8e05' => 
		  array (
		    '主类名' => 'f7664060cc52b',
		  ),
		  '65b9eea6e1cc6' => 
		  array (
		    '主类名' => '39059724f73a9',
		  ),
		  'f0935e4cd5920' => 
		  array (
		    '' => '7a614fd06c325',
		  ),
		  'a3c65c2974270' => 
		  array (
		    '' => '20f07591c6fcb',
		  ),
		  '529da8a763af6' => 
		  array (
		    '' => '92c8c96e4c371',
		  ),
		  '698d51a19d8a1' => 
		  array (
		    '默认类' => '839ab46820b52',
		  ),
		  '5fd0b37cd7dbb' => 
		  array (
		    '主css' => '49182f81e6a13',
		  ),
		  '2b44928ae11fb' => 
		  array (
		    '主css' => '9fd81843ad7f2',
		  ),
		  '73278a4a86960' => 
		  array (
		    '' => '53c3bce66e43b',
		  ),
		  '5ef059938ba79' => 
		  array (
		    '主css' => '11b9842e0a271',
		    '关键字有背景' => '37bc2f75bf1bc',
		    '多行多列' => '496e05e1aea0a',
		    '图片标题（加背景）' => 'b2eb734903575',
		  ),
		  'da4fb5c6e93e7' => 
		  array (
		    '默认样式' => '06eb61b839a0c',
		  ),
		  '529ec18d2858b' => 
		  array (
		    '默认样式' => '3fe94a002317b',
		  ),
		  '4c56ff4ce4aaf' => 
		  array (
		    '默认样式' => '950a4152c2b4a',
		  ),
		  'a0a080f42e6f1' => 
		  array (
		    '默认类' => '758874998f5bd',
		    '等宽布局' => '55b37c5c270e5',
		  ),
		  'ec5decca5ed3d' => 
		  array (
		    '默认样式' => 'f2fc990265c71',
		  ),
		  'd1f491a404d68' => 
		  array (
		    '默认类名' => 'b83aac23b9528',
		  ),
		  '9fc3d7152ba93' => 
		  array (
		    '' => '310dcbbf4cce6',
		  ),
		  '903ce9225fca3' => 
		  array (
		    '主类名' => 'f73b76ce8949f',
		  ),
		  '03c6b06952c75' => 
		  array (
		    '主CSS' => '8c7bbbba95c10',
		  ),
		  '52a149debcfaa' => 
		  array (
		    '一行x列' => '69adc1e107f7f',
		    '多行多列' => 'ad61ab143223e',
		    '多行加粗' => '84d9ee44e457d',
		    '主类名' => '14bfa6bb14875',
		    '多行单列居中' => '6f3ef77ac0e36',
		    '哈哈' => 'bcbe3365e6ac9',
		    '一行加粗' => '3644a684f98ea',
		  ),
		  '1d7f7abc18fcb' => 
		  array (
		    '' => '9be40cee5b0ee',
		  ),
		  '2a79ea27c279e' => 
		  array (
		    '' => '39461a19e9edd',
		  ),
		  '6cdd60ea0045e' => 
		  array (
		    '主css' => '3cf166c6b73f0',
		    '标题可加粗居中' => 'cee631121c2ec',
		  ),
		  '58a2fc6ed39fd' => 
		  array (
		    '主CSS' => '63538fe6ef330',
		  ),
		  'bd686fd640be9' => 
		  array (
		    '主CSS' => '07563a3fe3bbe',
		  ),
		  '52a2a8ebe9c5a' => 
		  array (
		    '主类名' => '0ff39bbbf981a',
		  ),
		  '528afa8807059' => 
		  array (
		    '' => '291597a100aad',
		  ),
		  'f9b902fc3289a' => 
		  array (
		    '默认css' => 'f9a40a4780f5e',
		  ),
		  '528afac5785eb' => 
		  array (
		    '主类名' => '5243dd70df6e4',
		  ),
		  '52983886f0ac0' => 
		  array (
		    '主类名' => '5248ee6fdb7be',
		  ),
		  '528c7931859f8' => 
		  array (
		    '默认样式' => '526a327998823',
		    '图片左浮动' => '526a327998bac',
		    '有图无标题' => '526a327998dce',
		    '定制视频图标' => '526a327998ff2',
		    '标题有背景' => '526a32799922b',
		    'cessssssss' => '5272029da0d50',
		    'ffggdgddgd' => '527202b9f11be',
		  ),
		  '5295b3ea932fb' => 
		  array (
		    '默认样式' => '5295b384084ab',
		  ),
		  '52a2eeeed3a6f' => 
		  array (
		    '主css' => '529c23645e011',
		  ),
		  'e7b24b112a44f' => 
		  array (
		    '默认样式' => '526ddc750eaa8',
		  ),
		  '52a2a94dc8d48' => 
		  array (
		    '默认样式' => '52721cd3b18fe',
		  ),
		  '52a021643c0c4' => 
		  array (
		    '默认样式' => '529fe93a2b9a5',
		    '主css' => '529fe93a2b6ee',
		  ),
		  '52a141d4b3b6f' => 
		  array (
		    '默认样式' => '529ff1179f26d',
		  ),
		  '52a2f4b6abc10' => 
		  array (
		    '默认样式' => '529fef8a521c4',
		  ),
		  '52a13fd95d332' => 
		  array (
		    '默认样式' => '52a0197dddf2c',
		  ),
		  '529ebf6d658f3' => 
		  array (
		    '' => '529e97fad0327',
		  ),
		  '529ecd02d67d7' => 
		  array (
		    '' => '529ec71dd7286',
		  ),
		  '528ad037a6b3f' => 
		  array (
		    '主类名' => '528aca6c04973',
		    '多行多列' => '528aca6c04b9a',
		    '多行加粗' => '528aca6c04e38',
		    '多行单列居中' => '528aca6c0506a',
		    '多行浮动' => '528aca6c05236',
		  ),
		  '528c28c6ae7ff' => 
		  array (
		    '' => '528c254591383',
		  ),
		  '529d413b69817' => 
		  array (
		    '' => '529d413b8695c',
		  ),
		);
		
		$sql  = "select a.sign,b.title,a.id from " . DB_PREFIX . "cell_mode a" .
    			" LEFT JOIN  " . DB_PREFIX . "cell_mode_code b ON b.mode_id = a.id WHERE 1";
        $info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			if($row['sign'])
			{
				$ret[$row['sign']][] = $row['title'];
				$mode_id[$row['sign']] = $row['id'];
			}
		}
		
		foreach ($arr as $k => $v)
        {
        	if($ret[$k])
        	{
        		 if(count($v)==1 && count($ret[$k]) ==1)
        		 {
        		 	foreach($v as $ke =>$va)
        		 	{
        		 		if(!$ke && !$ret[$k][0])
        		 		{
        		 			$sql_ = "update " . DB_PREFIX . "cell_mode_code set sign='" . $va . "' where title='" . $ret[$k][0]."'  AND mode_id = ".$mode_id[$k];
                	 		$this->db->query($sql_);
        		 		}
        		 	}
        		 }
        		 else
        		 {
        		 	foreach($ret[$k] as $key=>$val)
        		 	{
        		 		if($val && $v[$val]  && $val==$v[$val])
        		 		{
        		 			$sql_ = "update " . DB_PREFIX . "cell_mode_code set sign='" . $v[$val] . "' where title='" . $val."'  AND mode_id = ".$mode_id[$k];
                	 		$this->db->query($sql_);
        		 		}
        		 	}
        		 }
        	}
        }
    }
    
    public function ttt()
    {
        include('cache/datasource/120.php');
        $ds_120 = new ds_120();
        $r = $ds_120->show(array());
        print_r($r);
    }

}

$out    = new template();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>
