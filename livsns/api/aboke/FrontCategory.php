<?php
/*******************************************************************
 * filename :CDN.php
 * Created  :2013年8月9日,Writen by scala
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 *
 ******************************************************************/
require ('./global.php');
define('MOD_UNIQUEID', 'aboke');
require_once CUR_CONF_PATH . 'lib/Core.class.php';
class FrontCategoryAPI extends  outerReadBase {
    private $obj = null;
    private $tbname = 'cate';
    public function __construct() {
        parent::__construct();
        $this -> obj = new Core();
    }

    public function detail() {
     	$id = intval($this -> input['id']);
        if (!$id) {
            $this -> errorOutput(NO_ID);
        }

        //该分类为用户分类
        $cond = " WHERE 1 AND `id`=$id ";

        if(isset($this->input['type']))
        {
            $cond .= "AND `type`=".intval($this->input['type']);
        }
        
        $info = $this -> obj -> detail($this -> tbname, $cond);

        if (!$info) {
            $this -> errorOutput(NO_DATA_EXIST);
        }
        $this -> addItem($info);
        $this -> output();
    }

    public function show() {
    	$condition = $this -> get_condition();
        $offset = $this -> input['offset'] ? $this -> input['offset'] : 0;
        $count = $this -> input['count'] ? intval($this -> input['count']) : 20;
        $cond .= " AND ".DB_PREFIX.$this->tbname."user_id = ".$this->user['user_id'] ;
        $data_limit = $condition . ' order  by sort_id desc, id desc LIMIT ' . $offset . ' , ' . $count;
       
       // $datas = $this -> obj -> show($this -> tbname, $data_limit, $fields = '*');

        $query = "
                SELECT ".DB_PREFIX."$this->tbname.*, ".DB_PREFIX."cate_mark.name AS cate_mark_name,".DB_PREFIX."cate_mark.mark_type AS cate_mark_type
                FROM ".DB_PREFIX."$this->tbname
                LEFT JOIN `".DB_PREFIX."cate_mark` 
                ON ".DB_PREFIX."$this->tbname.cate_mark_id=".DB_PREFIX."cate_mark.id
                   ";
        $datas = $this->obj->query($query.$data_limit);
        
        foreach ($datas as $k => $v) {
            $this -> addItem($v);

        }
        $this -> output();
    }

    public function count() {
        $condition = $this -> get_condition();
        $info = $this -> obj -> count($this -> tbname, $condition);
        echo json_encode($info);
    }

    public function index() {

    }

    private function get_condition() {
        //只显示用户自定义的分类
        $cond = " WHERE 1 ";
        
        if(intval($this->input['type']))
        {
        	$type = intval($this->input['type']);
        	if($type == 2)
        	{
        		$cond .= " AND (".DB_PREFIX.$this->tbname.".`user_id`=".$this->user['user_id'] ." AND ".DB_PREFIX.$this->tbname.".`type`=".$type.")";
        	}
        	elseif($type == 3)
        	{
        		$cond .= " AND (".DB_PREFIX.$this->tbname.".`user_id`=".$this->user['user_id'] ." OR ".DB_PREFIX.$this->tbname.".`type`=1)" ;
        	}
        	else {
            	$cond .= " AND ".DB_PREFIX.$this->tbname.".`type`=".$type;
        	}
        }

        if (isset($this -> input['state'])) {
            $cond .= " AND ".DB_PREFIX.$this->tbname.".`state`=".intval($this -> input['state']);
        }
        if(isset($this->input['name']))
        {
            $cond .= " AND ".DB_PREFIX.$this->tbname.".`name` like '%" .trim($this->input['name'])."%'" ;
        }
        // if(isset($this->input['is_menu']))
        // {
            // $cond .= " and `is_menu`=".intval($this->input['is_menu']);
        // }
        return $cond;
    }
    
    public function get_menu()
    {
        $condition = "WHERE `is_menu`=1";
        $offset = $this -> input['offset'] ? $this -> input['offset'] : 0;
        $count = $this -> input['count'] ? intval($this -> input['count']) : 20;
        $data_limit = $condition . ' order by id desc LIMIT ' . $offset . ' , ' . $count;
        $datas = $this -> obj -> show($this -> tbname, $data_limit, $fields = '*');

        foreach ($datas as $k => $v) {
            $this -> addItem($v);

        }
        $this -> output();
    }

    public function unknow() {
        $this -> errorOutput(NO_ACTION);
        
    }

}

$out = new FrontCategoryAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out -> $action();
?>
