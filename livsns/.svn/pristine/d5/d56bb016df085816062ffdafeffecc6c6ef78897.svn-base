<?php
/*******************************************************************
 * filename :Consignee.php
 * 收货人
 * Created  :2014年3月18日,Writen by gaoyuan
 *
 ******************************************************************/
require('./global.php');
define('MOD_UNIQUEID','pay_consignee');
require_once CUR_CONF_PATH . 'core/Core.class.php';
class ContactAPI extends  outerReadBase
{
    private $obj=null;
    private $tbname = 'order_contact';
    public function __construct()
    {
        parent::__construct();
        $this->obj = new Core();
    }

    //查看收货人信息详情
    public function detail()
    {
    }


    //查看收货人信息列表
    public function show()
    {
        //$condition = $this->get_condition();
        $condition = '';
        $user_id = intval($this->user['user_id']);
        if($user_id)
        {
            $condition .= ' and user_id='.$user_id;
            $datas = array();
            $offset = $this->input['offset'] ? $this->input['offset'] : 0;
            $count = $this->input['count'] ? intval($this->input['count']) : 20;
            $data_limit = $condition.' order by id desc LIMIT ' . $offset . ' , ' . $count;

            $sql = "SELECT *
	                  FROM ".DB_PREFIX."$this->tbname
	                  WHERE 1 ".$data_limit;

            $datas = $this->obj->query($sql);
            if($datas && is_array($datas))
            {
                foreach($datas as $k=>$v)
                {
                    $this->addItem($v);
                }
            }
            else
            {
                $arr = array();
                $this->addItem($arr);
            }
        }
        else
        {
            $arr = array();
            $this->addItem($arr);
        }

        $this->output();
    }

    public function count()
    {
        $condition = $this->get_condition();
        $info = $this->obj->count($this->tbname,$condition);
        echo json_encode($info);
    }


    public function index()
    {

    }

    private function get_condition()
    {
        //只显示用户自定义的分类    
        $condition = "  WHERE 1  and state=1";
        if(isset($this->user['user_id']))
        {
            $condition .= ' and user_id='.intval($this->user['user_id']);
        }

        return $condition;
    }

    public function unknow()
    {
        $this->errorOutput(NO_ACTION);
    }
}

$out = new ContactAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out-> $action();
?>
