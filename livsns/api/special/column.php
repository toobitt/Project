<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID','special');
require_once(ROOT_PATH."global.php");
require_once(CUR_CONF_PATH."lib/functions.php");
class columnApi extends adminBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_column()
	{  
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$limit = ' LIMIT ' . $offset . ', ' . $count;
		$sql = "SELECT col.id,col.special_id,col.column_name,col.outlink,spe.column_url
				FROM ".DB_PREFIX."special_columns col 
				LEFT JOIN ".DB_PREFIX."special spe
				    ON col.special_id = spe.id 
				WHERE 1 " . $condition . $limit;
		$q = $this->db->query($sql);
		$ret = array();
        if (!class_exists('publishcontent')) {
            include(ROOT_PATH . 'lib/class/publishcontent.class.php');
        }
        $this->publishtcontent = new publishcontent();      
        $special_ids = $special_info = array();  
		while ($row = $this->db->fetch_array($q)) {
		    if (!$row['outlink']) {
    		    $row['column_url'] = $row['column_url'] ? unserialize($row['column_url']) : array();
                $content_id = array_pop($row['column_url']);
                if (!in_array($row['special_id'], $special_ids)) {
                    $special_info[$row['special_id']] = $this->publishtcontent->get_content_by_rid($content_id);  
                    $row['column_url'] = $special_info[$row['special_id']]['content_url']; 
                    $special_ids[] = $row['special_id'];
                }
                else {
                    $row['column_url'] = $special_info[$row['special_id']]['content_url'];
                }
                $row['column_url'] = explode('/', $row['column_url']);
                array_pop($row['column_url']);
                $row['column_url'] = implode('/', $row['column_url']);
                $row['column_url'] = $row['column_url'] ? rtrim($row['column_url'],'/') . '/' . $row['id'] . '_list.html' : '';
                if (!$row['column_url']) {
                    $row['column_url'] = COLURL . $row['id'];   
                } 
            }
            else {
                $row['column_url'] = $row['outlink'];
            }   
            $row['name'] = $row['column_name'];
            $row['is_last'] = 1;
			if ($this->input['need_count']) {
				$ret[] = $row;
			}
			else {
				$this->addItem($row);
			}
		}
		if ($this->input['need_count']) {
			$totalcount = $this->get_count();
			$this->addItem_withkey('total',$totalcount['total'] );
			$this->addItem_withkey('data',$ret );			
		}
		$this->output();			
	}
	
	public function get_count() 
	{
		$condition = $this->get_condition();
		$sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'special_columns WHERE 1 ' . $condition;
		return $this->db->query_first($sql);
	}
	
	private function get_condition()
	{
		$condition = '';
		if ($this->input['special_id']) {
			$condition .= ' AND col.special_id = ' . intval($this->input['special_id']);
		}
		
		if ($this->input['column_id'] && $this->input['column_id'] != 'special_column.id') {
			$column_id = urldecode($this->input['column_id']);
			$condition .= ' AND col.id IN (' . $column_id . ')';
		}
        $condition .= " ORDER BY col.order_id";
        if ($sort_type  = urldecode($this->input['descasc']))
        {
                $sort_type = $sort_type == 'ASC' ? ' ASC' : ' DESC';
                $condition .= $sort_type;
        }
        else {
            $condition .= ' DESC';
        }    
		return $condition;
	}
    
    /******获取专题栏目的url******/
    public function columnUrl() {
        $intColumnId = intval($this->input['column_id']);
        if (!$intColumnId) {
           $this->errorOutput('NO COLUMN_ID');   
        }
        $sql = "SELECT col.id,col.special_id,col.column_name,col.outlink,spe.column_url
                FROM ".DB_PREFIX."special_columns col 
                LEFT JOIN ".DB_PREFIX."special spe
                    ON col.special_id = spe.id 
                WHERE 1 AND col.id = " . $intColumnId;
        $arColumnInfo = $this->db->query_first($sql);
        if (!empty($arColumnInfo)) {
            if (!$arColumnInfo['outlink']) {
                $arColumnInfo['column_url'] = $arColumnInfo['column_url'] ? unserialize($arColumnInfo['column_url']) : array();
                $intContentId = array_pop($arColumnInfo['column_url']);
                if (!class_exists('publishcontent')) {
                    include(ROOT_PATH . 'lib/class/publishcontent.class.php');
                }
                $objPublishContent = new publishcontent();                      
                $arSpecialInfo = $objPublishContent->get_content_by_rid($intContentId);  
                $arColumnInfo['column_url'] = $arSpecialInfo['content_url']; 
                $arColumnInfo['column_url'] = explode('/', $arColumnInfo['column_url']);
                array_pop($arColumnInfo['column_url']);
                $arColumnInfo['column_url'] = implode('/', $arColumnInfo['column_url']);
                $arColumnInfo['column_url'] = $arColumnInfo['column_url'] ? rtrim($arColumnInfo['column_url'],'/') . '/' . $arColumnInfo['id'] . '_list.html' : '';
                if (!$arColumnInfo['column_url']) {
                    $arColumnInfo['column_url'] = COLURL . $arColumnInfo['id'];   
                } 
            }
            else {
                $arColumnInfo['column_url'] = $arColumnInfo['outlink'];
            }             
        }
        $this->addItem($arColumnInfo['column_url']);
        $this->output();    
    }
	
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new columnApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>
