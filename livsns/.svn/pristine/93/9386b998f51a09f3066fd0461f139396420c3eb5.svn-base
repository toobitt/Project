<?php
/**
 * HOGE DingDone member_mycount_update.php
 *
 * 类方法的功能。。。
 *
 * @package DingDone
 * @author RDC3 - dxtan
 * @copyright Copyright (c) 2015, HOGE CO., LTD (http://hoge.cn/)
 * @since Version 0.0.1
 */

define('MOD_UNIQUEID','MycountUpdate');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_mycount_mode.php';
class MycountUpdate extends outerReadBase{
    private $mycount;
    public function __construct()
    {
        parent::__construct();
        $this->mycount = new member_mycount_mode();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
       
    }
    
    public function create()
    {
        $memberId = intval($this->input['member_id']);
        $action = $this->input['action'];
        $numbers = intval($this->input['numbers']);
        if($numbers < 0)
        {
            $numbers = 0;
        }
        if(!$action)
        {
            $this->errorOutput(NO_MARK);
        }
        if(!$memberId)
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
        
        $data = array(
                'uid' => $memberId,
        );
        $data[$action] = $numbers;
        $result = $this->mycount->create($data);  
        
        $this->addItem($result);
        $this->output();
    }
    
    public function update()
    {
        $memberId = intval($this->input['member_id']);
        $action = $this->input['action'];
        $numbers = intval($this->input['numbers']);
        if($numbers < 0)
        {
            $numbers = 0;
        }
        if(!$action)
        {
            $this->errorOutput(NO_MARK);
        }
        if(!$memberId)
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
        $data = array();
        $data[$action] = $numbers;
        $result = $this->mycount->update($memberId,$data);
        
        $this->addItem($result);
        $this->output();
    }
    

    public function detail(){}
    public function count(){}

}
$out = new MycountUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'show';
}
$out->$action();
?>