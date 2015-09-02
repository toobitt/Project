<?php
define('MOD_UNIQUEID','contribute');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/contribute_mode.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
class contribute extends outerReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new contribute_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id DESC,id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}

    /**
     * 获取会员自己的投稿.
     *
     * @param  int  $id
     * @return Response
     */
    public function get_member_content()
    {
        if($this->user['user_id'])
        {
            $member_id = $this->user['user_id'];
        }
        $this->check_request(array('member_id' => $member_id));
        $condition = ' AND member_id IN ('.$member_id.')';

        $offset = $this->input['offset'] ? $this->input['offset'] : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 10;
        $orderby = '  ORDER BY order_id DESC,id DESC ';
        $limit = ' LIMIT ' . $offset . ' , ' . $count;
        $ret = $this->mode->show($condition,$orderby,$limit);
        if(!empty($ret))
        {
            foreach($ret as $k => $v)
            {
                $this->addItem($v);
            }
            $this->output();
        }
    }

	/**
	 * Display the count resource.
	 *
	 * @param  condition
	 * @return Response
	 */
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * input your param.
	 *
	 * @param  param
	 * @return Response
	 */
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  name  LIKE "%'.trim(($this->input['k'])).'%"';
		}

        if($this->input['app_id'])
        {
            $condition .= " AND app_id IN (".($this->input['app_id']).")";
        }

        if($this->input['id'])
        {
            $condition .= " AND id IN (".($this->input['id']).")";
        }
		
		return $condition;
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function detail()
	{
		if($this->input['id'])
		{
			$ret = $this->mode->detail($this->input['id']);
            $ret['content'] = html_entity_decode(hg_clean_value($ret['content']));
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
	}

	/**
	 * 创建投稿.
	 *
     * column_id array('main' => 模块ID，'has_child'=> 1, 'child'=> 子栏目id ,'desc'=> 描述（模块名称>子栏目名称）)
	 * @param  condition
	 * @return Response
	 */
	public function create()
	{
        $title = trim($this->input['title']);
        $content = trim($this->input['content']);
        $column_id = $this->input['column_id'];
        $column_path = trim($this->input['column_path']);
        if($this->input['member_id'])
        {
            $member_id = $this->input['member_id'];
            $member_name = $this->input['member_name'];
        }
        elseif($this->user['user_id'])
        {
            $member_id = $this->user['user_id'];
            $member_name = $this->user['user_name'];
        }
        $app_id = intval($this->input['app_id']);
        if($_FILES['photos'])
        {
            $count = count($_FILES['photos']['name']);
            for($i=0; $i<$count; $i++)
            {
                foreach( $_FILES['photos'] AS $k =>$v)
                {
                    $photo[$k] =  $_FILES['photos'][$k][$i];
                }

                $contentImage[] = $this->uploadimg($photo);
            }
        }

        $data = array(
			'title' => $title,
            'content_image' => $contentImage[0] ? serialize($contentImage) : '',
            'column_id' => $column_id,
            'column_path'   => $column_path,
            'member_id' => $member_id,
            'member_name' => $member_name,
            'app_id' => $app_id,
            'create_time'=> TIMENOW,
		);
		$this->check_request($data);

		$vid = $this->mode->create($data);
        //创建内容表
        $this->mode->createContent(array('id' => $vid,'content' => $content));

		if($vid)
		{
			$data['id'] = $vid;
            $data['status_text'] = $this->settings['general_audit_status'][0];
            $data['content'] = hg_back_value(html_entity_decode(stripcslashes($content)));
            $data['content_image'] = $contentImage;
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
			$this->addItem($data);
			$this->output();
		}
	}

    private function check_request($request)
    {
        if(isset($request['title']) && empty($request['title']))
        {
            $this->errorOutput(NO_TITLE);
        }
        if(isset($request['content']) && empty($request['content']))
        {
            $this->errorOutput(NO_CONTENT);
        }
        if(isset($request['column_id']) && empty($request['column_id']))
        {
            $this->errorOutput(NO_COLUMN);
        }
        if(isset($request['column_path']) && empty($request['column_path']))
        {
            $this->errorOutput(NO_COLUMN_PATH);
        }
        if(isset($request['member_id']) && !$request['member_id'])
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
        if(isset($request['member_name']) && empty($request['member_name']))
        {
            $this->errorOutput(NO_MEMBER_NAME);
        }
        if(isset($request['app_id']) && !$request['app_id'])
        {
            $this->errorOutput(NO_APP_ID);
        }
    }
	

	/**
	 * Update the resource.
	 *
	 * @param  condition
	 * @return Response
	 */
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$update_data = array(
			'status' => $this->input['status'],
            'content_id' => $this->input['content_id']
		);
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			//$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}

    /**
     * Update the resource.
     *
     * @param  condition
     * @return Response
     */
    public function updateBycid()
    {
        if(!$this->input['cid'])
        {
            $this->errorOutput(NOID);
        }
        $cid = $this->input['cid'];
        $update_data = array();

        if(isset($this->input['content_id']))
        {
            $update_data['content_id'] = $this->input['content_id'];
        }

        if(isset($this->input['status']))
        {
            $update_data['status'] = $this->input['status'];
        }

        $ret = $this->mode->updateBycid($cid,$update_data);
        if($ret)
        {
            //$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
            $this->addItem('success');
            $this->output();
        }
    }


	/**
	 * Delete the resource.
	 *
	 * @param  condition
	 * @return Response
	 */
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}

        if($this->user['user_id'])
        {
            $member_id = $this->user['user_id'];
        }
        $this->check_request(array('member_id' => $member_id));

        //检测权限
        $info = $this->mode->detail($this->input['id']);
        if(empty($info))
        {
            $this->errorOutput(NOT_EXISTS_CONTENT);
        }
        if($info['member_id'] != $member_id)
        {
            $this->errorOutput(NO_PERMISSION);
        }
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}

    public function audit()
    {
        if(!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }
        $status = $this->input['status'];
        $ret = $this->mode->audit($this->input['id'],$status);
        if($ret)
        {
            //$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
            $this->addItem($ret);
            $this->output();
        }
    }

    public function auditBycid()
    {
        if(!$this->input['content_id'])
        {
            $this->errorOutput(NOID);
        }
        $status = $this->input['status'];
        $ret = $this->mode->auditBycid($this->input['content_id'],$status);
        if($ret)
        {
            //$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
            $this->addItem($ret);
            $this->output();
        }
    }

    /**
     * 上传图片
     * @param unknown $var_name
     * @return string
     */
    private function uploadimg($file)
    {
        //处理avatar图片
        if($file)
        {
            $_FILES['Filedata'] = $file;
            $material = new material();
            $img_info = $material->addMaterial($_FILES);
            if($img_info)
            {
                $avatar = array(
                    'host' 		=> $img_info['host'],
                    'dir' 		=> $img_info['dir'],
                    'filepath' 	=> $img_info['filepath'],
                    'filename' 	=> $img_info['filename'],
                    'width'		=> $img_info['imgwidth'],
                    'height'	=> $img_info['imgheight'],
                    'id'        => $img_info['id'],
                );
            }
        }
        return $avatar;
    }
}

$out = new contribute();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>