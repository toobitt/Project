<?php
class ArticleClass extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->mater = new material();
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->publish_column = new publishconfig();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	//查询期刊下的文章
	public function show($condition='',$orderby=' ORDER BY a.order_id  DESC',$offset=0,$count=10)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT a.*,l.name as sort_name,m.host,m.dir,m.filepath,m.filename FROM  '.DB_PREFIX.'article a 
		LEFT JOIN '.DB_PREFIX.'catalog l 
		ON a.group_id = l.id  
		LEFT JOIN '.DB_PREFIX.'material m 
		ON a.indexpic = m.material_id WHERE 1 '.$condition.$orderby.$limit;
		$q = $this->db->query($sql);
		$size = '40x30/';
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			$r['user_name'] = $r['user_name'] ? $r['user_name'] : '匿名用户'; 
			$r['sort_name'] = $r['sort_name'] ? $r['sort_name'] : '未分类';
			if($r['indexpic'])//索引图
			{
				//$r['indexpic_url'] = $this->getThumbById($r['indexpic']);
				$r['indexpic_url'] = hg_material_link($r['host'], $r['dir'], $r['filepath'], $r['filename'],$size);
			}
			
			$r['img_info'] = array(
				'host'=>$r['host'],
				'dir'=>$r['dir'],
				'filepath'=>$r['filepath'],
				'filename'=>$r['filename'],
			);
			switch ($r['state'])
			{
				case  1: $r['audit'] = '已审核';break;
				case  2: $r['audit'] = '已打回';break;
				default: $r['audit'] = '待审核';
			}
			$res[] = $r;
		}
		return $res;
	}
	//查询文章信息
	public function detail($id)
	{
		$sql = 'SELECT a.*,c.content,l.name as sort_name,i.issue as qishu FROM  '.DB_PREFIX.'article a 
		LEFT JOIN '.DB_PREFIX.'content c 
		ON a.id = c.article_id 
		LEFT JOIN '.DB_PREFIX.'catalog l
		ON a.group_id = l.id
		LEFT JOIN '.DB_PREFIX.'issue i
		ON a.issue_id = i.id WHERE a.id = '.$id;
		$r = $this->db->query_first($sql);
		$r['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
		if(!empty($r['indexpic']))
		{
			//查找索引图
			$r['indexpic_url'] = $this->getThumbById($r['indexpic'],$this->settings['default_index']);
		}
		else
		{
			$r['indexpic_url'] = '';
		}
		//发布栏目
        $column_id = unserialize($r['column_id'])?unserialize($r['column_id']):array();
        if (is_array($column_id))
        {
        	$r['column_id'] = implode(',', array_keys($column_id));
        }	
		$ret = $this->getMaterialById($id);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{	
		        switch($v['mark'])
			    {
					case 'img':
						if($v['host'] && $v['dir'] && $v['filepath'] && $v['filename'])
						{
							//将缩略图信息加入info数组
							$v['img_info'] = array(
								'host'=>$v['host'],
								'dir'=>$v['dir'],
								'filepath'=>$v['filepath'],
								'filename'=>$v['filename'],
							);
						}
						else 
						{
							$v['img_info'] = array();
						}
						//后台管理图片显示
						$v['path'] = $v['host'] . $v['dir'];
						$v['dir'] = $v['filepath'];
						$r['material'][] = $v;
						
						break;
					default:
						break;
			   }
			}
		}
		else 
		{
			$r['material'] = array() ;
		}
		return $r;
	}
	private function getMaterialById($cid)
	{	
		if(empty($cid))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "material WHERE cid=" . $cid . " AND isdel=1"; //1表示没删除
		$q = $this->db->query($sql);
		$info = array();
		while(false != ($ret = $this->db->fetch_array($q)))
		{
			if(empty($ret))
			{
				continue;
			}
			switch($ret['mark'])
			{
				case 'img':
					$info[$ret['material_id']] = $ret;
					$info[$ret['material_id']]['url'] = hg_material_link($ret['host'] ,$ret['dir'], $ret['filepath'], $ret['filename'],$this->settings['default_size']['label'] . '/');
					break;
				case 'doc':
					$info[$ret['material_id']] = $ret;
					$info[$ret['material_id']]['url'] = MATERIAL_TYPE_THUMB . 'doc.png';
					break;
				default:
					break;
			}
		}
		return $info;
	}
	private function getThumbById($id,$size = array())
	{
		if(empty($id))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "material WHERE 1 AND material_id=" . $id;
		$ret = $this->db->query_first($sql);
		if(empty($ret))
		{
			return false;
		}
		if($ret['mark'] != 'img')
		{
			return false;
		}
		$size = $size ? $size : $this->settings['small_size'];
		return hg_material_link($ret['host'] ,$ret['dir'], $ret['filepath'], $ret['filename'],$size['label'] . "/");
	}
	//上传图片
	public function upload()
	{
		if($_FILES['Filedata'])
		{			
			$material = $this->mater->addMaterial($_FILES,0,0,intval($this->input['water_config_id']));
			//图片信息返回后，更新内容标签
			if(!empty($material))
			{
				$sql = "REPLACE INTO " . DB_PREFIX ."material SET ";
				$material['material_id'] = $material['id'];
				//unset($material['bundle_id'], $material['mid'], $material['id'], $material['url'],$material['code']);
				$sql_extra = $space ='';
				
				$data = array(
					'material_id' 	=> $material['id'],
					'name'			=> $material['name'],
					'host'			=> $material['host'],
					'dir'			=> $material['dir'],
					'filepath'		=> $material['filepath'],
					'filename'		=> $material['filename'],
					'type'			=> $material['type'],
					'mark'			=> $material['mark'],
					'imgwidth'		=> $material['imgwidth'],
					'imgheight'		=> $material['imgheight'],
					'filesize'		=> $material['filesize'],
					'create_time'	=> $material['create_time'],
					'ip'			=> $material['ip'],
				);
				foreach($data as $k => $v)
				{
					$sql_extra .= $space . $k . "='" . $v . "'";
					$space = ',';
				}
				$sql = $sql . $sql_extra;
				$this->db->query($sql);
				
				$material['filesize'] = hg_bytes_to_size($material['filesize']);
				$return = array(
					'success'    => true,
					'id'         => $material['material_id'],
					'filename'   => $material['filename'] . '?' . hg_generate_user_salt(4),
					'name'       => $material['name'],
					'mark'       => $material['mark'],
					'type'       => $material['type'],
					'filesize'   => $material['filesize'],
					'path'       => $material['host'] . $material['dir'],
					'dir'        => $material['filepath'],
				);
			}
			else
			{
				$return = array(
					'success' => false,
					'error' => '文件上传失败',
				);
			}
			return $return;
		}
	}
	
	//图片本地化
	function img_local()
	{
		$url = urldecode($this->input['url']);
		$water_id = urldecode($this->input['water_id']);		//如果设置了水印则要传水印
		$material = $this->mater->localMaterial($url,0,0, $water_id); 	//调用图片服务器本地化接口
		if(!empty($material))
		{
			$url_arr = explode(',', $url);
			$info = array();
			foreach ($material as $k => $v)
			{
				if(!empty($v))
				{
					if(in_array($v['remote_url'], $url_arr))
					{
						$info[$v['remote_url']] = array('id' => $v['id'],'remote_url'=>$v['remote_url'],'path' => $v['host'].$v['dir'],'dir' => $v['filepath'],'filename' => $v['filename'],'error' => $v['error']);
					}
				}
			}
			$sql = "INSERT INTO " . DB_PREFIX ."material SET ";
			foreach($material as $key => $value)
			{
				if(!empty($value))
				{
					$value['material_id'] = $value['id'];
					unset($value['mid'],$value['id'],$value['bundle_id'],$value['code']);
					$sql_extra = $space ='';
					foreach($value as $k => $v)
					{
						$sql_extra .= $space . $k . "='" . $v . "'";
						$space=',';
					}
					$this->db->query($sql . $sql_extra);
				}
			}
		}
		return $info;
	}
	//上传水印图片
	public function upload_water()
	{
		if($_FILES['Filedata'])
		{
			if($_FILES['Filedata']['error'])
			{
				return false;
			}
			else 
			{
				$typetmp = explode('.',$_FILES['Filedata']['name']);
				$filetype = strtolower($typetmp[count($typetmp)-1]);
				$gMaterialType = $this->mater->check_cache();
				$type = '';
				if(!empty($gMaterialType))
				{
					foreach($gMaterialType as $k => $v)
					{
						if(in_array($filetype,$v))
						{
							$type = $k;
						}
					}
				}
				
				
				if($type != 'img')
				{
					return false;
				}
				$return = $this->mater->upload_water($_FILES['Filedata']);
				return $return;
			}
		}
	}

	//添加新的水印配置
	public function create_water_config()
	{
		$water_info = array(
			'config_name'=> urldecode($this->input['config_name']),
			'type' => intval($this->input['water_type']),
			'position' => intval($this->input['get_photo_waterpos']),
			'filename' => urldecode($this->input['water_filename']),
			'margin_x'=> urldecode($this->input['margin_x']),
			'margin_y'=> urldecode($this->input['margin_y']),
			'water_text' => urldecode($this->input['water_text']),
			'water_angle' => intval($this->input['water_angle']),
			'water_font' => urldecode($this->input['water_font']),
			'opacity' => urldecode($this->input['opacity']),
			'water_color' => urldecode($this->input['water_color']),
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip(),
			'user_name' => trim(urldecode($this->user['user_name'])),			
		);	
		$ret = $this->mater->create_water_config($water_info);
		if(!empty($ret))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//获取水印配置列表
	public function water_config_list()
	{
		$ret = $this->mater->water_config_list();
		return $ret;
	}
	//using 
	public function pic_water_list()
	{
		return $this->mater->waterSystem();
	}
	//using
	public function revolveImg()
	{
		$material_id = intval($this->input['material_id']);
		$direction = intval($this->input['direction']);
		$return = $this->mater->revolveImg($material_id, $direction);
		return $return;
	}
	//发布
	public function publish()
	{
		$id = intval($this->input['id']);
		
		//发布之前检测该条文章的状态，只有审核通过的才可以发布
		$sql = "SELECT * FROM ".DB_PREFIX."article WHERE id = ".$id;
	    $ret = $this->db->query_first($sql);	
	 
	     //获取栏目发布的id
		$column_id = urldecode($this->input['column_id']);
		$new_column_id = explode(',',$column_id);
		//通过id获取发布栏目名称
		$column_id = $this->publish_column->get_columnname_by_ids('id,name',$column_id);
		$column_id = serialize($column_id);
	    		
		$sql = "UPDATE " . DB_PREFIX ."article SET column_id = '". $column_id ."' WHERE id = " . $id;
		$this->db->query($sql);
		
		//查询该杂志是否发布，以及发布到哪个栏目下
		$ret['column_id'] = unserialize($ret['column_id']);
		
		//将之前的栏目id放入数组中，准备对比
		$old_column_id = array();
		if (is_array($ret['column_id']))
		{
			$old_column_id = array_keys($ret['column_id']);
		}
	 	if($ret['state'] == 1)
	 	{
			if (!empty($ret['expand_id']))
			{
				$del_column = array_diff($old_column_id,$new_column_id);
				
				if (!empty($del_column))
				{
					$this->publish_insert_query($id, 'delete',$del_column);
				}		
				$add_column = array_diff($new_column_id,$old_column_id);
				if (!empty($add_column))
				{
					$this->publish_insert_query($id, 'insert',$add_column);
				}
				$same_column = array_intersect($old_column_id,$new_column_id);
				if(!empty($same_column))
				{
					$this->publish_insert_query($id, 'update',$same_column);
				}
			}
			else 
			{
				$op = "insert";
				$this->publish_insert_query($id,$op);
			}
	 	}
		return true;	
	}
	/**
	*  发布系统，将内容传入发布队列
	*  
	*/	
	public function publish_insert_query($artcileId,$op,$column_id = array())
	{
		$id = intval($artcileId);
		if (empty($id) || empty($op))
		{
			return false;
		}
		$sql = "SELECT  *  FROM ".DB_PREFIX."article WHERE id = " . $id;
		$info = $this->db->query_first($sql);
		
		if (empty($column_id))
		{		
			$info['column_id'] = unserialize($info['column_id']);
			if(is_array($info['column_id']))
			{
				$column_id = array_keys($info['column_id']);
				$column_id = implode(',',$column_id);
			}			
		}
		else 
		{
			$column_id = implode(',',$column_id);
		}
 		require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
		$plan = new publishplan();
		$data = array(
			'set_id' 	=>	ARTICLE_PLAN_SET_ID,
			'from_id'   =>  $info['issue_id'],
			'class_id'	=> 	0,
			'column_id' => $column_id,
			'title'     =>  $info['title'],
			'action_type' => $op,
			'publish_time'  => TIMENOW,
			'publish_people' => urldecode($this->user['user_name']),
			'ip'   =>  hg_getip(),
		);
		//file_put_contents('2.txt', var_export($data,1));
		$ret = $plan->insert_queue($data);
		//file_put_contents('3.txt', var_export($ret,1));
		return $ret;
	}
	
	
	#############图文分离
	
	
 	public function content_manage($url, $dir, $content, $need_pages, $need_process, $need_separate = false)
    {
        $content = htmlspecialchars_decode($content);
        $pregreplace = array('<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r",'<script');
		$pregfind = array('&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', "\n", '&#036;', '','&#60;script');
		$content = str_replace($pregfind, $pregreplace, $content);
        if ($need_separate)
        {
            //图集，视频，投票处理
            $result['content_material_list'] = self::content_material_list($url, $dir, $content, $need_pages, $need_process);
        }
        preg_match_all('/<img.*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/is', $content, $match_mat);
        //图片处理
       // echo 111;	
		
        if ($need_separate)
        {
            self::content_pic($content, $match_mat);
        }
        
        $tmp = $need_process ? preg_replace('#<p[^>]*>#i', '<p>', $content) : $content;
        if ($need_pages)
        {
            $tmp = str_replace(' style="margin:0 auto;display:block;"', '', $tmp);
            preg_match_all('/<img[^>]*class=\"pagebg\"[^>]*>/i', $tmp, $match);
            if (empty($match[0]))
            {
                $tmp     = $need_process ? strip_tags($tmp, '<p><br><a><m2o_mark>') : $tmp;
                $pages[] = $tmp;
            }
            else
            {
                $page_total = count($match[0]);
                foreach ($match[0] as $k => $p)
                {
                    $pos          = strpos($tmp, $p);
                    $page_content = substr($tmp, 0, $pos);
                    $page_content = $need_process ? strip_tags($page_content, '<p><br><a><m2o_mark>') : $page_content;
                    if ($page_content)
                    {
                        $pages[] = $page_content;
                    }
                    $start = $pos + strlen($p);
                    $tmp   = substr($tmp, $start);
                    if ($k === $page_total - 1)
                    {
                        $page_content = $need_process ? strip_tags($tmp, '<p><br><a><m2o_mark>') : $tmp;
                        if ($page_content)
                        {
                            $pages[] = $page_content;
                        }
                    }
                }
            }
            $result['content'] = $need_process ? str_replace($match_mat[0], '', $pages) : $pages;
        }
        else
        {
            $tmp               = $need_process ? strip_tags($tmp, '<p><br><a><m2o_mark>') : $tmp;
            $result['content'] = $tmp;
        }
        
        $result['content'] = preg_replace('#<m2o_mark style="display:none">(.*?)</m2o_mark>#i', '<div m2o_mark="\\1" style="display:none"></div>', $result['content']);

        //处理图片
        $pic = array();
        if ($match_mat[1])
        {
            $i = 0;
            foreach ($match_mat[1] as $k => $v)
            {
                $ismatch = preg_match('/^(.*?)(material\/.*?img\/)([0-9]*[x|-][0-9]*)\/(\d{0,4}\/\d{0,2}\/)(.*?)$/is', $v, $match);
                if ($ismatch)
                {
                    $pics[$i]['pic']['host']       = $match[1];
                    $pics[$i]['pic']['dir']        = $match[2];
                    $pics[$i]['pic']['filepath']   = $match[4];
                    $pics[$i]['pic']['filename']   = $match[5];
                    $pics[$i]['pic']['is_outlink'] = 0;
                    $i++;
                }
                else
                {
                    if (strpos($v, 'http:') === 0)
                    {
                        $pics[$i]['pic']['host']       = '';
                        $pics[$i]['pic']['dir']        = '';
                        $pics[$i]['pic']['filepath']   = '';
                        $pics[$i]['pic']['filename']   = $v;
                        $pics[$i]['pic']['is_outlink'] = 1;
                        $i++;
                    }
                }
            }
        }
        $result['content_pics'] = $pics?$pics:array();
        return $result;
    }

    public function content_material_list($url, $dir, &$content, $need_pages, $need_process)
    {
        $content_material_list = array();
        preg_match_all('/<img[^>]class=[\'|\"]image-refer[\'|\"][^>]src=[\'|\"](.*?)[\'|\"].*?[\/]?>/is', $content, $mat_r1);
        preg_match_all('/<img[^>]src=[\'|\"](.*?)[\'|\"].*?class=[\'|\"]image-refer[\'|\"].*?[\/]?>/is', $content, $mat_r2);
        $mat_r                 = arrpreg($mat_r1, $mat_r2);
        if (!$mat_r[0] || !is_array($mat_r[0]))
        {
            return array();
        }
        foreach ($mat_r[0] as $k => $v)
        {
            if ($mat_r[1][$k])
            {
                $ex_arr    = explode('/', $mat_r[1][$k]);
                $re_ex_arr = array_reverse($ex_arr);
                $filename  = $re_ex_arr[0];
                $module    = $re_ex_arr[1];
                $app       = $re_ex_arr[2];

                $filename_arr    = explode('_', $filename);
                $re_filename_arr = array_reverse($filename_arr);
                $fileid          = intval($re_filename_arr[0]);
                unset($re_filename_arr[0]);
                if (empty($this->settings['App_' . $app]) || !$re_filename_arr)
                {
                    continue;
                }
                $curl   = new curl($this->settings['App_' . $app]['host'], $this->settings['App_' . $app]['dir']);
                $curl->setSubmitType('post');
                $curl->setReturnFormat('json');
                $curl->initPostData();
                $curl->addRequestData('id', $fileid);
                $curl->addRequestData('a', 'detail');
                $result = $curl->request(implode('_', array_reverse($re_filename_arr)) . '.php');

                if (is_array($result) && $result)
                {
                    $ret = $this->select_child($app, $result);
                }
                $content_material_list[$app . '_' . $fileid] = $ret;
                $find_arr[]                              = $v;
                $replace_arr[]                           = '<m2o_mark style="display:none">' . $app . '_' . $fileid . '</m2o_mark>';
            }
        }

        if ($find_arr && $replace_arr && $content)
        {
            $content = str_replace($find_arr, $replace_arr, $content);
        }
        return $content_material_list;
    }

    public function content_pic(&$content, $match_mat)
    {
        if (!is_array($match_mat[0]) || !$match_mat[0])
        {
            return false;
        }
        foreach ($match_mat[0] as $k => $v)
        {
            $find_arr[]    = $v;
            $replace_arr[] = '<m2o_mark style="display:none">pic_' . $k . '</m2o_mark>';
        }
        if ($find_arr && $replace_arr && $content)
        {
            $content = str_replace($find_arr, $replace_arr, $content);
        }
    }

    public function select_child($app, $result)
    {
        $ret = array();
        switch ($app)
        {
            case 'tuji':
                foreach ($result as $k => $v)
                {
                    $row['title']    = $v['title'];
                    $row['brief']    = $v['brief'];
                    $row['keywords'] = $v['keywords'];
                    $row['app']      = 'tuji';
                    if ($v['img_src'])
                    {
                        foreach ($v['img_src'] as $kk => $vv)
                        {
                            $ismatch = preg_match('/^(.*?)(material\/.*?img\/)([0-9]*[x|-][0-9]*)\/(\d{0,4}\/\d{0,2}\/)(.*?)$/is', $vv, $match);
                            if ($ismatch)
                            {
                                $row['img_src'][$kk]['host']     = $match[1];
                                $row['img_src'][$kk]['dir']      = $match[2];
                                $row['img_src'][$kk]['filepath'] = $match[4];
                                $row['img_src'][$kk]['filename'] = $match[5];
                            }
                        }
                    }
                    if ($v['column_url'])
                    {
                        $column_urlarr = @unserialize($v['column_url']);
                        if($column_urlarr)
                        {
                            foreach($column_urlarr as $kkk=>$vvv)
                            {
                                $row['relation_id'][] = array('column_id'=>$kkk,'id'=>$vvv);
                            }
                            $row['id'] = $row['relation_id'][0]['id'];
                        }
                    }
                    $ret = $row;
                }
                break;
            case 'livmedia':
                foreach ($result as $k => $v)
                {
                    $row['title']                = $v['title'];
                    $row['brief']                = $v['brief'];
                    $row['keywords']             = $v['keywords'];
                    $row['column_url']           = is_array($v['column_url']) ? $v['column_url'] : unserialize($v['column_url']);
                    //$v['video_filename'] = str_replace('.mp4','.m3u8',$v['video_filename']);
                    //$row['video_url'] = rtrim($v['hostwork'],'/').'/'.$v['video_path'].$v['video_filename'];
                    $row['video_url']            = $v['videoaddr']['default']['m3u8'];
                    $row['video_url_f4m']        = $v['videoaddr']['default']['f4m'];
                    $row['app']                  = 'livmedia';
                    $row['indexpic']['host']     = $v['img_info']['host'];
                    $row['indexpic']['dir']      = $v['img_info']['dir'];
                    $row['indexpic']['filepath'] = $v['img_info']['filepath'];
                    $row['indexpic']['filename'] = $v['img_info']['filename'];
                    if ($v['column_url'])
                    {
                        $column_urlarr = @unserialize($v['column_url']);
                        if($column_urlarr)
                        {
                            foreach($column_urlarr as $kkk=>$vvv)
                            {
                                $row['relation_id'][] = array('column_id'=>$kkk,'id'=>$vvv);
                            }
                            $row['id'] = $row['relation_id'][0]['id'];
                        }
                    }
                    $ret = $row;
                }
                break;
            case 'vote':
                foreach ($result as $k => $v)
                {
                    $row               = $v;
                    $row['column_url'] = is_array($v['column_url']) ? $v['column_url'] : unserialize($v['column_url']);
                    $row['column_id']  = is_array($v['column_id']) ? $v['column_id'] : unserialize($v['column_id']);
                    if ($v['column_url'])
                    {
                        $column_urlarr = @unserialize($v['column_url']);
                        if($column_urlarr)
                        {
                            foreach($column_urlarr as $kkk=>$vvv)
                            {
                                $row['relation_id'][] = array('column_id'=>$kkk,'id'=>$vvv);
                            }
                            $row['id'] = $row['relation_id'][0]['id'];
                        }
                    }
                    $ret = $row;
                }
                break;
        }
        return $ret;
    }
	
}