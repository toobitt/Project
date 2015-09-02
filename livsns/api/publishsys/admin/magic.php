<?php
require('global.php');
define('MOD_UNIQUEID', 'magic_view');
class MagicViewApi extends adminBase
{
    public function __construct() {
        $this->mPrmsMethods = array(
            'manage' => '管理',
            '_node' => array(
                'name' => '栏目',
                //'node_uniqueid' => 'cloumn_node',
            ),
        );
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/common.php');
    }

    public function __destruct() {
        parent::__destruct();
    } 

    public function show() {

        /*模板 权限验证预处理 start*/
        $need_auth = 0;
        //$auth_page_self存储授权页面本身、$auth_page_parents存储授权栏目父级页面
        $auth_site = $auth_site_self = $auth_page = $auth_column = $auth_page_self = $auth_page_parents = array();
        if ( $this->user['group_type'] > MAX_ADMIN_TYPE )
        {
            $need_auth = 1;
            $auth_node = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
            if ( (is_array($auth_node) ? implode(',',$auth_node) : $auth_node) == 1)
            {
                $need_auth = 0;  //1表示全选  不需要验证权限
            }
            $auth_node = is_array($auth_node) ? $auth_node : explode(',', $auth_node);
            if ($need_auth)
            {
                foreach ((array) $auth_node as $k => $v)
                {
                    switch ($v)
                    {
                        case strstr($v, "site") !== false :
                            $v = str_replace("site", "", $v);
                            $v = explode($this->settings['separator'], $v);
                            $auth_site[] = $auth_site_self[] = $v[0];
                            break;
                        case strstr($v, "page_id") !== false :
                            $v = str_replace("page_id", "", $v);
                            $v = explode($this->settings['separator'], $v);
                            $auth_site[] = $v[0];
                            $auth_page[] = $auth_page_self[] = $v[1];
                            break;
                        case strstr($v, "page_data_id") !== false:
                            $v = str_replace("page_data_id", "", $v);
                            $v = explode($this->settings['separator'], $v);
                            $auth_site[] = $v[0];
                            $auth_page[] = $auth_page_parents[] = $v[1];
                            $auth_column[$v[1]][] = $v[2];
                            break;
                        default:
                            break;
                    }
                }
            }
        }
        /*模板 权限验证预处理 end*/

        $through_auth = 0;
        $fid = ($this->input['_id']);
        if (strstr($fid, "site") !== false) {
            $fid     = str_replace('site', '', $fid);
            $fid     = explode($this->settings['separator'], $fid);
            $site_id = $fid[0];

            if ($need_auth)
            {
                //授权节点
                if ( in_array($site_id, $auth_site_self) )
                {
                    $through_auth = 1;
                }
            }
        }
        else if (strstr($fid, "page_id") !== false) {
            $fid     = str_replace('page_id', '', $fid);
            $fid     = explode($this->settings['separator'], $fid);
            $site_id = $fid[0];
            $page_id = $fid[1];
            if ($need_auth)
            {
                if ( in_array($page_id, $auth_page_self) || in_array($site_id, $auth_site_self) )
                {
                    $through_auth = 1;
                }
            }
        }
        else if (strstr($fid, "page_data_id") !== false) {
            $fid          = str_replace('page_data_id', '', $fid);
            $fid          = explode($this->settings['separator'], $fid);
            $site_id      = $fid[0];
            $page_id      = $fid[1];
            $page_data_id = $fid[2];
            if ($need_auth)
            {
                $auth_column = isset($auth_column[$page_id]) ? $auth_column[$page_id] : array() ;
                //授权节点本身或者孩子节点 显示
                if ( in_array($page_data_id, $auth_column)  || in_array($page_id, $auth_page_self) || in_array($site_id, $auth_site_self) )
                {
                    $through_auth = 1;
                }
                else
                {
                    $page_data = common::get_page_data($page_id, 0, 1, 0, '', $page_data_id);
                    foreach ((array)$page_data['page_data'] as $k => $v)
                    {
                        $auth_column_parents[$v['id']] = $v['parents'];
                    }
                    //栏目孩子节点显示
                    if(array_intersect(explode(',', $auth_column_parents[$page_data_id]), $auth_column))
                    {
                        $through_auth = 1;
                    }
                }
            }
        }
        $set_type = array();
        if ( $through_auth || !$need_auth )
        {
            $set_type = $this->settings['site_col_template'];
            include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
            $this->publishcontent = new publishcontent();
            if ($site_id && !$page_id && !$page_data_id) {
                //有内容，查出内容类型
                $content_type = $this->publishcontent->get_all_content_type();
                foreach ($content_type as $k => $v) {
                    $set_type[$v['id']] = $v['content_type'];
                }
            }
            else if ($page_id && !$page_data_id) {
                $page_info = common::get_page_by_id($page_id);
                $site_id   = $page_info['site_id'];
                if ($page_info['has_content']) {
                    //有内容，查出内容类型
                    $content_type = $this->publishcontent->get_all_content_type();
                    foreach ($content_type as $k => $v) {
                        $set_type[$v['id']] = $v['content_type'];
                    }
                }
            }
            else if ($page_data_id) {
                if (strstr($page_data_id, 'sort') !== false) {
                    $page_data_id = str_replace('sort', '', $page_data_id);
                    $set_type     = array();
                }
                else if (strstr($page_data_id, 'spe') !== false) {
                    $page_data_id = str_replace('spe', '', $page_data_id);
                    if (!class_exists('special')) {
                        include(ROOT_PATH . 'lib/class/special.class.php');
                    }
                    $special      = new special();
                    $specail_info = $special->detail($page_data_id);
                    $template_id  = $specail_info['template_sign'];
                    $set_type     = array('0' => '首页');
                }
                else if (strstr($page_data_id, 'col') !== false) {
                    $content_type = str_replace('col', '', $page_data_id);
                    if (!class_exists('special')) {
                        include(ROOT_PATH . 'lib/class/special.class.php');
                    }
                    $special             = new special();
                    $special_column_info = $special->special_column_info($content_type);
                    $page_data_id        = $special_column_info['special_id'];
                    $specail_info        = $special->detail($page_data_id);
                    $template_id         = $specail_info['template_sign'];
                    $set_type            = array($content_type => '首页');
                }
                else {
                    $page_info = common::get_page_by_id($page_id);
                    $site_id   = $page_info['site_id'];
                    if ($page_info['has_content']) {
                        //有内容，查出内容类型
                        $content_type = $this->publishcontent->get_all_content_type();
                        foreach ($content_type as $k => $v) {
                            $set_type[$v['id']] = $v['content_type'];
                        }
                    }
                }
            }
        }
        $result['set_type']     = $site_id ? $set_type : array();
        $result['site_id']      = $site_id;
        $result['page_id']      = $page_id;
        $result['page_data_id'] = $page_data_id;
        $result['template_id']  = $template_id;
        $this->addItem($result);
        $this->output();
    }

    /**
     * 魔力视图中切换页面
     */
    public function changeColumn() {
        $site_id      = $this->input['site_id'];
        $page_id      = $this->input['page_id'];
        $page_data_id = $this->input['page_data_id'];
        if (!$site_id) {
            $this->errorOutput('站点id为空');
        }
        include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
        $this->publishcontent = new publishcontent();
        $set_type             = $this->settings['site_col_template'];
        //有页面类型则取页面下栏目,没有取此站点下页面####		
        if ($page_id) {
            $page_data = common::get_page_data($page_id, 0, 300, $page_data_id);
            if (is_array($page_data['page_data']) && count($page_data['page_data']) > 0) {
                ####取出此页面的内容类型####
                if ($page_data['page_info']['has_content']) {
                    $types = $this->publishcontent->get_all_content_type();
                    if (is_array($types) && count($types) > 0) {
                        foreach ($types as $kk => $vv) {
                            $set_type[$vv['id']] = $vv['content_type'];
                        }
                    }
                }
                foreach ($page_data['page_data'] as $k => $v) {
                    $m = array(
                        'id' => $v['id'],
                        'title' => $v['name'],
                        'site_id' => $page_data['page_info']['site_id'],
                        'page_id' => $page_data['page_info']['id'],
                        'page_data_id' => $v['id'],
                        'content_types' => $set_type,
                        'is_last' => $v[$page_data['page_info']['last_field']],
                    );
                    $this->addItem($m);
                }
            }
        }
        else {
            $pages = common::get_page_manage($site_id);
            if (is_array($pages) && count($pages) > 0) {
                //内容类型
                foreach ($pages as $k => $v) {
                    if ($v['has_content']) {  //有内容，查出内容类型
                        $types = $this->publishcontent->get_all_content_type();
                        if (is_array($types) && count($types) > 0) {
                            foreach ($types as $kk => $vv) {
                                $set_type[$vv['id']] = $vv['content_type'];
                            }
                        }
                        $v['content_types'] = $set_type;
                    }
                    $m = array(
                        'id' => $v['id'],
                        'title' => $v['title'],
                        'site_id' => $v['site_id'],
                        'page_id' => $v['page_id'],
                        'page_data_id' => $v['page_data_id'],
                        'content_types' => $set_type,
                        'is_last' => 0,
                    );
                    $page_data = common::get_page_data($v['id'], 0, 1);
                    if (empty($page_data['page_data'])) {
                        $m['is_last'] = 1;
                    }
                    $this->addItem($m);
                }
            }
        }
        $this->output();
    }

    public function  searchCell() {   
  
        $intSiteId       = intval($this->input['intSiteId']);
        $intPageId       = intval($this->input['intPageId']);
        $intPageDataId   = intval($this->input['intPageDataId']);
        $intContentType  = intval($this->input['intContentType']);
        $intTemplateId   = intval($this->input['intTemplateId']);
        $blPreset        = intval($this->input['blPreset']);
        $intLayoutid     = intval($this->input['intLayoutId']);

        //快速专题、区块时不加权限     	
        if (($this->input['bs'] == 'k') || ($this->input['bs'] == 'q')) {  
            
        }
        else { 
        	if($this->user['group_type'] > MAX_ADMIN_TYPE)
    		{
    			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];
                $action = empty($action) ? array() : $action;
    			if(!in_array('manage',$action))
    			{
    			    $ret = array('login_error' => true);
                    echo json_encode($ret);exit;
                    $this->addItem($ret);
                    $this->output();
    			}
    		}
        }
		
        if (!class_exists('Magic')) {
            include(CUR_CONF_PATH . 'lib/magic.class.php');
        }
        $magic = new Magic($intSiteId, $intPageId, $intPageDataId, $intContentType, $intTemplateId, $blPreset, $intLayoutid);
        $ret = $magic->searchCell();
        if ($intTemplateId && (!$blPreset || $blPreset == 'false')) {
            //获取专题的布局
            $ret['id'] = $intTemplateId;
            $ret['special_id'] = $intPageDataId;
        }
        $this->addItem($ret);
        $this->output();    
    }
    
    public function preview() {
   
        //快速专题、区块时不加权限      
        if (($this->input['bs'] == 'k') || ($this->input['bs'] == 'q')) {  
            
        }
        else {    	
        	if($this->user['group_type'] > MAX_ADMIN_TYPE)
    		{
    			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];
                $action = empty($action) ? array() : $action;
    			if(!in_array('manage',$action))
    			{
    				$this->errorOutput("NO_PRIVILEGE");
    			}
    		}
        }
		
        $intSiteId       = intval($this->input['intSiteId']);
        $intPageId       = intval($this->input['intPageId']);
        $intPageDataId   = intval($this->input['intPageDataId']);
        $intContentType  = intval($this->input['intContentType']);
        $intTemplateId   = intval($this->input['intTemplateId']);
        $blPreset        = intval($this->input['blPreset']);
        $intLayoutid     = intval($this->input['intLayoutId']);
        if (!class_exists('Magic')) {
            include(CUR_CONF_PATH . 'lib/magic.class.php');
        }
        $magic = new Magic($intSiteId, $intPageId, $intPageDataId, $intContentType, $intTemplateId, $blPreset, $intLayoutid);
        $template = $magic->preview(); 
        $this->addItem($template);
        $this->output();        
    }
    
    public function getAllIcons()
    {
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $sql = "SELECT count(*) AS total FROM " .DB_PREFIX. "icons WHERE 1 AND user_id = " . intval($this->user['user_id']) . " AND user_name = '" . $this->user['user_name'] . "'";
        $total = $this->db->query_first($sql);
        $data_limit = " LIMIT " . $offset . ", " . $count;
        $sql = "SELECT * FROM " .DB_PREFIX."icons WHERE 1  AND user_id = " . intval($this->user['user_id']) . " AND user_name = '". $this->user['user_name'] ."' ORDER BY create_time DESC " . $data_limit;
        $q = $this->db->query($sql);
        $files = array();
        while ( $row = $this->db->fetch_array($q) )
        {
            $row['create_time_show'] = date('Y-m-d H:i:s', $row['create_time']);
            $row['update_time_show'] = date('Y-m-d H:i:s', $row['update_time']);
            $row['real_url'] = ICON_URL . $row['filepath'] . $row['filename'];
            $row['url'] = '<MATEURL>' . $row['filepath'] . $row['filename'];
            $files[] = $row;
        }
        $ret = array(
            'total' => $total['total'],
            'data'  => $files,
        );
        $this->addItem($ret);
        $this->output();
    } 

    public function cellPreview () {
        $intCellId = intval($this->input['intCellId']);
        $arData = $this->input['arData'];
        if (!$intCellId) {
            $this->errorOutput('NO CELLID');
        }
        if (!class_exists('cell')) {
            include (CUR_CONF_PATH . 'lib/cell.class.php');
        }
        $objCell = new cell();
        $arCellInfo = $objCell->detail(' AND id = ' . $intCellId); 
        if (!class_exists('Magic')) {
            include(CUR_CONF_PATH . 'lib/magic.class.php');
        }
        $objMagic = new Magic();        
        $arCellInfo = $objMagic->cellProcess($arCellInfo, 'false', $arData); 
        $this->addItem($arCellInfo);
        $this->output();                 
    }   
}

$out    = new MagicViewApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'show';
}
$out->$action();
?>
