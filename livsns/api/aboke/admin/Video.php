<?php
/*******************************************************************
 * filename :Video.php
 * Created  :2013.09.22 Writen by scala 
 * 
 ******************************************************************/
define('MOD_UNIQUEID', 'aboke'); //模块标识
require ('global.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class Video extends  adminReadBase
{
    
    private $obj=null;
    private $tbname = 'video';
    public function __construct()
    {
        parent::__construct();
        $this->obj = new Core();
    }
    public function detail()
    {
        $id = intval($this->input['id']);
        
        if(!$id)
        {
            $this->errorOutput(NO_ID);
        }
        $data_limit = 'where id='.$id;
        $info = $this->obj->detail($this->tbname,$data_limit);
        
        if(!$info)
        {
            $this->errorOutput(NO_DATA_EXIST);
        }
            
        //初始视频信息，默认没有视频信息
        $info['info'] = false;
        
        if($info['state'])
        {
            $videoinfo = $this->get_video_moreinfo($info['video_id']);
            $info['info'] = $videoinfo[0];
        }

        $this->addItem($info);
        $this->output();

    }
    
    
    public function show()
    {
        $condition = $this->get_condition();
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;          
        $count = $this->input['count'] ? intval($this->input['count']) : 20;                    
        $data_limit = $condition.' order by id desc LIMIT ' . $offset . ' , ' . $count;     
        $datas = $this->obj->show($this->tbname,$data_limit,$fields='*');
        
        $cate = $this->obj->show('cate',' where 1',' * ');
        $cates = array();
        foreach ($cate as $key => $value){ 
            $cates[$value['id']]['id'] = $value['id'];
            $cates[$value['id']]['name'] = $value['name'];
            $cates[$value['id']]['type'] = $value['type'];
            if($value['type']==1)
            {
                $admin_cates[$value['id']]['id'] = $value['id'];
                $admin_cates[$value['id']]['name'] = $value['name'];
                $admin_cates[$value['id']]['type'] = $value['type'];
            }
        }
        $video_ids = "";
        foreach($datas as $k=>$v)
        {
            $video_ids .= $v['video_id'].",";
        }
        $video_ids = substr($video_ids, 0,-1);
        $video_details = $this->get_videos($video_ids);
        
        foreach($datas as $k=>$v)
        {
            $v['video_detail'] = $video_details[0][$v['video_id']];
            $v['cate_name'] = $cate[$v['cate_id']]['name'];
            $v['admin_cate_name'] = $cate[$v['admin_cate_id']]['name'];
            $v['cate_info'] = $admin_cates;
            $this->addItem($v);
        }
        
        
        $this->output();
    }

    public function get_videos($ids)
    {
        $return = array();
        if($ids)
        {
            $this->create_curl_obj('livmedia');
            $params['id'] = $ids;
            $params['a'] = 'get_videos';
            $params['r'] = 'vod';
            $return = $this->get_common_datas($params);
            return $return;
        }
        return $return;
    }
    
    public function get_admin_cate()
    {
        $cate = $this->obj->show('cate',' where 1',' * ');
        $cates = array();

        foreach ($cate as $key => $value) {
            if($value['type']==1)
            {
                $cates[$value['id']]['id'] = $value['id'];
                $cates[$value['id']]['name'] = $value['name'];
                $cates[$value['id']]['type'] = 1;
            }
        }
        $this->addItem($cates);
        $this->output();
    }
    
    private function get_video_moreinfo($ids)
    {
        $return = array();
        if($ids)
        {
            $this->create_curl_obj('livmedia');
            $params['id'] = $ids;
            $params['a'] = 'detail';
            $params['r'] = 'vod';
            $return = $this->get_common_datas($params);
            return $return;
        }
        return $return;
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
        $cond = " where 1 ";
        
        //1系统默认,2用户自定义 
        if(isset($this->input['type']))
        {
            $cond .= ' and type='.intval($this->input['type']);
        }
        
        
        if(isset($this->input['cate_id']))
        {
            $cond .= ' and cate_id='.intval($this->input['cate_id']);           
        }
        
        //审核状态
        if(isset($this->input['state']))
        {
            $cond .= ' and state='.intval($this->input['state']);
        }
        
        if(isset($this->input['user_id']))
        {
            $cond .= ' and user_id='.intval($this->input['user_id']);
        }
        
        if(isset($this->input['k']))
        {
            $cond .= ' AND title LIKE "%'.trim(urldecode($this->input['k'])).'%"';
        }
        
        //系统分类
        if(isset($this->input['admin_cate_id']))
        {
            if(!$this->input['admin_cate_id']) {
                $cond .= " and admin_cate_id>0 ";
            }
            else {
                $cond .= " and admin_cate_id =".$this->input['admin_cate_id'];
            }
        }
        
        
        if($this->input['date_search'])
        {
            $today = strtotime(date('Y-m-d'));
            $tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
            switch(intval($this->input['date_search']))
            {
                case 2://昨天的数据
                    $yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
                    $condition .= " AND  create_time > ".$yesterday." AND create_time < ".$today;
                    break;
                case 3://今天的数据
                    $condition .= " AND  create_time > ".$today." AND create_time < ".$tomorrow;
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
                    $condition .= " AND  create_time > ".$last_threeday." AND create_time < ".$tomorrow;
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
                    $condition .= " AND  create_time > ".$last_sevenday." AND create_time < ".$tomorrow;
                    break;
                default://所有时间段
                    break;
            }
        }
        

        return $cond;
    }
    
    /**
     * 创建curl
     */
    public function create_curl_obj($app_name)
    {
        $key        = 'App_'.$app_name;
        global $gGlobalConfig;
        if(!$gGlobalConfig[$key])
        {
            return false;
        }
        $this->curl = new curl($gGlobalConfig[$key]['host'], $gGlobalConfig[$key]['dir']);
    }
    
    /**
     * 解析curl数据
     */
    public function get_common_datas($params)
    {
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        foreach($params as $key=>$val)
        {
            if($key!='r')
            {
                $this->curl->addRequestData($key,$val);
            }
            else
            {
                return $this->curl->request($val.".php");
            }
        }
    }   
    public function unknow()
    {
        $this->errorOutput(NO_ACTION);
    }
}

$out = new Video();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out-> $action ();
?>
