<?php
require('global.php');
define('MOD_UNIQUEID','layout');
class layoutApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/common.php');
		include(CUR_CONF_PATH . 'lib/layout.class.php');
		$this->layout = new layout();
		require_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->material = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	public function show()
	{
//		if($this->user['group_type'] > MAX_ADMIN_TYPE)
//		{
//			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
//			if(!in_array('layout',$action))
//			{
//				$this->errorOutput("NO_PRIVILEGE");
//			}
//		}
		
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']  ? intval($this->input['count']) : 20;
		$data_limit = " LIMIT " . $offset . ", " . $count;
		$field = 'id,node_id,title,indexpic,create_time,user_id,user_name,status,sign';
		$ret = $this->layout->show($condition . $data_limit, $field);
		if (is_array($ret) && count($ret) > 0 ) {
			foreach ($ret as $key => $value) {
				$this->addItem($value);
			}
		}
		$this->output();
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		if ($id) {
			$data_limit = " AND id = " . $id;
		}
		else {
			$data_limit = " LIMIT 1";
		}
		$info = $this->layout->detail($data_limit);
		if ($info) {
			$info['indexpic'] = $info['indexpic'] ? unserialize($info['indexpic']) : '';
		}
		$this->addItem($info);
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$total = $this->layout->count($condition);
		echo json_encode($total);
	}	
	
	private function get_condition()
	{
		$condition = '';
		if($this->input['sign'])
		{	
			$condition .=" AND sign !=''";
		}
		$condition .=' ORDER  BY id DESC';
		return $condition;	
	}
		
	//发布带标识的布局
	public function export_layout()
	{
		$lid_arr = $cid_arr = $da_arr = $ccid_arr = $cell_sign = $dataso_sign = $lout_cell = array();
		
		$signs_str = implode('","', explode(',', urldecode($this->input['sign'])));
       	$sql       = 'select 	*  from  ' . DB_PREFIX . 'layout  WHERE original_id =0 AND sign IN("' . $signs_str . '")';
		$mq = $this->db->query($sql);
		while($rm = $this->db->fetch_array($mq))
		{
			$linfo[$rm['id']] = $rm;
			$lid_arr[] = $rm['id'];
		}
		$lid_str = implode(',',$lid_arr);
		//exit;
		if($lid_str)
		{
			$sql_ = "select *  from " . DB_PREFIX . "layout_cell  where layout_id in ( ".$lid_str.")";
			$qq = $this->db->query($sql_);
			while($r = $this->db->fetch_array($qq))
			{
				//$lout_cell[$r['layout_id']][$r['sign']] = $r;
				$lout[] = $r;
				if($r['cell_mode'])
				{
					$cid_arr[] = $r['cell_mode'];
				}
				if($r['data_source'])
				{
					$da_arr[] = $r['data_source'];
				}
				if($r['css_id'])
				{
					$ccid_arr[] = $r['css_id'];
				}
			}
		}
		$cid_str = implode(',',$cid_arr);
		$da_str = implode(',',$da_arr);
		$ccid_str = implode(',',$ccid_arr);
		if($cid_str)
		{
			$sqlc = "select id,sign  from " . DB_PREFIX . "cell_mode  where id in ( ".$cid_str.")";	
			$qc = $this->db->query($sqlc);
			while($rc = $this->db->fetch_array($qc))
			{
				if($rc['sign'])
				{
					$cell_sign[$rc['id']] = $rc['sign'];
				}
			}
		}
		
		if($da_str)
		{
			$sqld = "select id,sign  from " . DB_PREFIX . "data_source  where id in ( ".$da_str.")";
			$qd = $this->db->query($sqld);
			while($rd = $this->db->fetch_array($qd))
			{
				if($rd['sign'])
				{
					$dataso_sign[$rd['id']] = $rd['sign'];
				}
			}
		}
		
		if($ccid_str)
		{
			$sqlcd = "select id,sign  from " . DB_PREFIX . "cell_mode_code   where id in ( ".$ccid_str.")";
			$qcd = $this->db->query($sqlcd);
			while($rcd = $this->db->fetch_array($qcd))
			{
				if($rcd['sign'])
				{
					$cc_sign[$rcd['id']] = $rcd['sign'];
				}
			}
		}
		
		if($lout && is_array($lout))
		{
			foreach($lout as $kk => $vv)
			{
				if($vv['cell_mode'] && $cell_sign[$vv['cell_mode']])
				{
					$vv['csign'] = $cell_sign[$vv['cell_mode']];
				}
				if($vv['data_source'] && $dataso_sign[$vv['data_source']])
				{
					$vv['dsign'] = $dataso_sign[$vv['data_source']];
				}
				if($vv['css_id'] && $cc_sign[$vv['css_id']])
				{
					$vv['ccsign'] = $cc_sign[$vv['css_id']];
				}
				$lout_cell[$vv['layout_id']][$vv['sign']] = $vv;
			}			
		}
		
		$host  = $this->settings['App_appstore']['host'];
        $dir   = $this->settings['App_appstore']['dir'];
        $curl  = new curl($host, $dir);
		$curl->setSubmitType('post');
        $curl->initPostData();
        $curl->addRequestData('a', 'publish_version');
        
		if($linfo && is_array($linfo))
		{
			foreach($linfo as $k=>$v)
			{
				$liv_layout = array(
		            'lout_info'				=>		$v,
					'lout_cell'				=>		$lout_cell[$k],
        			);
        		$loutinfo_str = serialize($liv_layout);
        		$curl->addRequestData('sign', $v['sign']);
        		$curl->addRequestData('title', $v['title']);
        		$curl->addRequestData('data', $loutinfo_str);
        		$curl->addRequestData('html', '1');
		        $curl->addRequestData('type', '4');
		      	$layout_info = $curl->request('pub_template.php');
			}
		}
	}
	
	//导入布局
	public function import_loutinfo()	
	{
		$file = $this->input['file'];
    	$sign_ar = array_keys($file);
    	$signs_str = implode('","',$sign_ar);
    	$mid_arr = array();
    	if($signs_str)
    	{
    		$sql   = 'select *  from  ' . DB_PREFIX . 'layout  WHERE sign IN("' . $signs_str . '") AND original_id = 0' ;
	        $mq    = $this->db->query($sql);
	        while ($rm   = $this->db->fetch_array($mq))
	        {
	            if ($rm['sign'])
	            {
	                $lsign[$rm['sign']] = $rm['id'];
	                $mid_arr[] = $rm['id'];
	            }
	        }
	        $mid_str = implode(',',$mid_arr);
    	}
        if($mid_str)
    	{
	        $sql_ = "select *  from " . DB_PREFIX . "layout_cell   where layout_id in ( ".$mid_str.")";	
			$qq = $this->db->query($sql_);
			while($r = $this->db->fetch_array($qq))
			{
				$moco[$r['layout_id']][] = $r['sign'];
				$mosign[$r['layout_id']][$r['sign']] = $r['id'];
			}
    	}
    	
    	$msql = "select id,sign  from " . DB_PREFIX . "cell_mode where sign !=''";	
		$mq = $this->db->query($msql);
		while($mr = $this->db->fetch_array($mq))
		{
			$mode_arr[$mr['sign']] = $mr['id'];
		}
		
		$dsql = "select id,sign  from " . DB_PREFIX . "data_source   where sign !=''";	
		$dqq = $this->db->query($dsql);
		while($dr = $this->db->fetch_array($dqq))
		{
			$das_arr[$dr['sign']] = $dr['id'];
		}
			
		$csql = "select id,sign  from " . DB_PREFIX . "cell_mode_code   where sign !=''  AND type='css' ";		
		$csqq = $this->db->query($csql);
		while($csr = $this->db->fetch_array($csqq))
		{
			$css_arr[$csr['sign']] = $csr['id'];
		}
			
        if($file && is_array($file))
		{
			foreach($file as $k=>$v)
			{
				$layout      		= unserialize($v['data']);
		        $layout_info 		= $layout['lout_info'];
				$layout_cell 		= $layout['lout_cell'];
		        if($lsign[$k])
		        {
		        	$layout_info['id']       = $lsign[$k];
		        	$layout_info['update_time']  = TIMENOW;
		        	if($layout_info['indexpic'])
			        {
			        	$indexpic = unserialize($layout_info['indexpic']);
			        	if(strstr($indexpic['host'],"img.dev.hogesoft.com")!==false)
				        {
				        	$url = $indexpic['host'].$indexpic['dir'].$indexpic['filepath'].$indexpic['filename'];
				        	$pic = file_get_contents($url);
							if($pic)
							{
								$dir = CUR_CONF_PATH.'data/layout/pic/';
								hg_mkdir($dir);
								file_put_contents($dir.$indexpic['filename'],$pic);
							}
							$index_pic  = array(
									'host'			=>	$this->settings['layout_image_url']."/",
									'dir'			=>	'pic/',
									'filepath'		=>	'',
									'filename'		=>	$indexpic['filename'],
							);
							$picurl =  $index_pic['host'].$index_pic['dir'].$index_pic['filepath'].$index_pic['filename'];
							$pic_info = $this->material->localMaterial($picurl);//插入图片服务器
							if($pic_info[0])
							{
								$arr = array(
									'host'			=>$pic_info[0]['host'],
									'dir'			=>$pic_info[0]['dir'],
									'filepath'		=>$pic_info[0]['filepath'],
									'filename'		=>$pic_info[0]['filename'],
								);
								$layout_info['indexpic'] = serialize($arr);
							}	
				        }
			        }
			        $sq_   = 'select *  from  ' . DB_PREFIX . 'layout   WHERE id =' . $lsign[$k];
					$layinfo   = $this->db->query_first($sq_);
					
	                $this->layout->update($layout_info, 'layout');
					
	                $this->addLogs('商店更新布局' , $layinfo , $layout_info, '商店更新布局'.$layout_info['title']);
	                
	                $layout_id = $lsign[$k];
	                if(is_array($layout_cell))
					{
						foreach($layout_cell as $ka=>$va)
						{
							if(is_array($moco[$layout_info['id']]))
							{
								if(in_array($va['sign'],$moco[$layout_info['id']]))
								{
									$va['id'] 			= $mosign[$layout_id][$va['sign']];
									$va['update_time']  = TIMENOW;
									if($va['csign'])
									{
										if($mode_arr[$va['csign']])
										{
											$va['cell_mode'] = $mode_arr[$va['csign']];
										}
										else
										{
											$rerurn = array();
											$rerurn = array(
												'sign'		=>	$va['csign'],
												'type'		=>	2,
												'layout'	=>  $layout_info['sign'],
											);
											//file_put_contents('../cache/01.txt',var_export($rerurn,1));
											echo json_encode($rerurn) ;exit;
										}
									}
									if($va['dsign'])
									{
										if($das_arr[$va['dsign']])
										{
											$va['data_source'] = $das_arr[$va['dsign']];
										}
										else
										{
											$rerurn = array();
											$rerurn = array(
												'sign'		=>	$va['dsign'],
												'type'		=>	1,
												'layout'	=>  $layout_info['sign'],
											);
											
											echo json_encode($rerurn) ;exit;
										}
									}
									
									
									if($css_arr[$va['ccsign']])
									{
										$va['css_id'] = $css_arr[$va['ccsign']];
									}
									unset($va['csign']);
									unset($va['dsign']);
									unset($va['ccsign']);
									$va['param_asso'] = addslashes($va['param_asso']);
									$va['layout_id'] = $layout_id;
									$this->layout->update($va, 'layout_cell');
								}
								else
								{
									unset($va['id']);
									$va['layout_id'] 	= $layout_id;
									$va['create_time']  = $va['update_time']  = TIMENOW;
									$va['user_id'] 		= $this->user['user_id'];
									$va['user_name'] 	= $this->user['user_name'];
									$va['appid'] 		= $this->user['appid'];
									$va['appname'] 		= $this->user['display_name'];
									if($va['csign'])
									{
										if($mode_arr[$va['csign']])
										{
											$va['cell_mode'] = $mode_arr[$va['csign']];
										}
										else
										{
											$rerurn = array();
											$rerurn = array(
												'sign'		=>	$va['csign'],
												'type'		=>	2,
												'layout'	=>  $layout_info['sign'],
											);
											
											echo json_encode($rerurn) ;exit;
										}
									}
									if($va['dsign'])
									{
										if($das_arr[$va['dsign']])
										{
											$va['data_source'] = $das_arr[$va['dsign']];
										}
										else
										{
											$rerurn = array();
											$rerurn = array(
												'sign'		=>	$va['dsign'],
												'type'		=>	1,
												'layout'	=>  $layout_info['sign'],
											);
											
											echo json_encode($rerurn) ;exit;
										}
									}
									
									if($css_arr[$va['ccsign']])
									{
										$va['css_id'] = $css_arr[$va['ccsign']];
									}
									unset($va['csign']);
									unset($va['dsign']);
									unset($va['ccsign']);
									$va['param_asso'] = addslashes($va['param_asso']);
									$cell_id = $this->layout->import_layout_info($va,'layout_cell');
								}
							}
							else
							{
								unset($va['id']);
								$va['layout_id'] 	= $layout_id;
								$va['create_time']  = $va['update_time']  = TIMENOW;
								$va['user_id'] 		= $this->user['user_id'];
								$va['user_name'] 	= $this->user['user_name'];
								$va['appid'] 		= $this->user['appid'];
								$va['appname'] 		= $this->user['display_name'];
								if($va['csign'])
								{
									if($mode_arr[$va['csign']])
									{
										$va['cell_mode'] = $mode_arr[$va['csign']];
									}
									else
									{
										$rerurn = array();
										$rerurn = array(
											'sign'		=>	$va['csign'],
											'type'		=>	2,
											'layout'	=>  $layout_info['sign'],
										);
										
										echo json_encode($rerurn) ;exit;
									}
								}
								if($va['dsign'])
								{
									if($das_arr[$va['dsign']])
									{
										$va['data_source'] = $das_arr[$va['dsign']];
									}
									else
									{
										$rerurn = array();
										$rerurn = array(
											'sign'		=>	$va['dsign'],
											'type'		=>	1,
											'layout'	=>  $layout_info['sign'],
										);
										
										echo json_encode($rerurn) ;exit;
									}
								}
								
								if($css_arr[$va['ccsign']])
								{
									$va['css_id'] = $css_arr[$va['ccsign']];
								}
								unset($va['csign']);
								unset($va['dsign']);
								unset($va['ccsign']);
								$va['param_asso'] = addslashes($va['param_asso']);
								$cell_id = $this->layout->import_layout_info($va,'layout_cell');
							}
						}
					}
		        }
		        else
		        {
		        	if(is_array($layout_info))
 					{
						unset($layout_info['id']);
						$layout_info['create_time']  = $layout_info['update_time']  = TIMENOW;
						$layout_info['user_id'] 	 = $this->user['user_id'];
						$layout_info['user_name'] 	 = $this->user['user_name'];

						if($layout_info['indexpic'])
				        {
				        	$indexpic = unserialize($layout_info['indexpic']);
				        	if(strstr($indexpic['host'],"img.dev.hogesoft.com")!==false)
					        {
					        	$url = $indexpic['host'].$indexpic['dir'].$indexpic['filepath'].$indexpic['filename'];
					        	$pic = file_get_contents($url);
								if($pic)
								{
									$dir = CUR_CONF_PATH.'data/layout/pic/';
									hg_mkdir($dir);
									file_put_contents($dir.$indexpic['filename'],$pic);
								}
								$index_pic  = array(
										'host'			=>	$this->settings['layout_image_url']."/",
										'dir'			=>	'pic/',
										'filepath'		=>	'',
										'filename'		=>	$indexpic['filename'],
								);
								$picurl =  $index_pic['host'].$index_pic['dir'].$index_pic['filepath'].$index_pic['filename'];
								$pic_info = $this->material->localMaterial($picurl);//插入图片服务器
								if($pic_info[0])
								{
									$arr = array(
										'host'			=>$pic_info[0]['host'],
										'dir'			=>$pic_info[0]['dir'],
										'filepath'		=>$pic_info[0]['filepath'],
										'filename'		=>$pic_info[0]['filename'],
									);
									$layout_info['indexpic'] = serialize($arr);
								}	
					        }
				        }
						$layout_id = $this->layout->import_layout_info($layout_info,'layout');
						
						$this->addLogs('商店安装布局' , '' , $layout_info, '商店安装布局'.$layout_info['title']);
					}
					if(is_array($layout_cell))
					{
						foreach($layout_cell as $k=>$v)
						{
							unset($v['id']);
							$v['layout_id'] = $layout_id;
							$v['create_time']   = $v['update_time']  = TIMENOW;
							$v['user_id'] 		= $this->user['user_id'];
							$v['user_name'] 	= $this->user['user_name'];
							$v['appid'] 		= $this->user['appid'];
							$v['appname'] 		= $this->user['display_name'];
							if($v['csign'])
							{
								if($mode_arr[$v['csign']])
								{
									$v['cell_mode'] = $mode_arr[$v['csign']];
								}
								else
								{
									$rerurn = array();
									$rerurn = array(
										'sign'		=>	$v['csign'],
										'type'		=>	2,
										'layout'	=>  $layout_info['sign'],
									);
									
									echo json_encode($rerurn) ;exit;
								}
							}
							
							if($v['dsign'])
							{
								if($das_arr[$v['dsign']])
								{
									$v['data_source'] = $das_arr[$v['dsign']];
								}
								else
								{
									$rerurn = array();
									$rerurn = array(
										'sign'		=>	$v['dsign'],
										'type'		=>	1,
										'layout'	=>  $layout_info['sign'],
									);
									
									echo json_encode($rerurn) ;exit;
								}
							}
							
							if($css_arr[$v['ccsign']])
							{
								$v['css_id'] = $css_arr[$v['ccsign']];
							}
							unset($v['csign']);
							unset($v['dsign']);
							unset($v['ccsign']);
							$v['param_asso'] = addslashes($v['param_asso']);
							$cell_id = $this->layout->import_layout_info($v,'layout_cell');
						}
					}
		        }
			}
		}
	}
}
$out = new layoutApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
