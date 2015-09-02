<?php
			$api = array(
				'host' => 'localhost',
				'port' => '80',
				'dir' => 'livsns/api/livmedia/admin/',
				);
			$this->tpl->addVar('__api', urlencode(json_encode($api)));
			$this->curl = new curl('localhost', 'livsns/api/livmedia/admin/');
		
						$this->curl1 = $this->curl;
				
			$this->curl1->initPostData();
			$this->curl1->addRequestData('trigger_action', 'show');
			$this->curl1->addRequestData('trigger_mod_uniqueid', 'livmedia');
			
					$this->curl1->addRequestData('count', '100');
				
					$this->curl1->setReturnFormat('json');
					$this->curl1->addRequestData('a', 'getWaterPic');$datas = $this->curl1->request('getSettingsFromMediaserver.php');
			
						$this->tpl->addVar('water_pic', $datas);
				
						$this->curl1 = $this->curl;
				
			$this->curl1->initPostData();
			$this->curl1->addRequestData('trigger_action', 'show');
			$this->curl1->addRequestData('trigger_mod_uniqueid', 'livmedia');
			
					$this->curl1->addRequestData('count', '100');
				
					$this->curl1->setReturnFormat('json');
					$this->curl1->addRequestData('a', 'getMosaic');$datas = $this->curl1->request('getSettingsFromMediaserver.php');
			
						$this->tpl->addVar('mosaic', $datas);
				
						$this->curl1 = $this->curl;
				
			$this->curl1->initPostData();
			$this->curl1->addRequestData('trigger_action', 'show');
			$this->curl1->addRequestData('trigger_mod_uniqueid', 'livmedia');
			
					$this->curl1->addRequestData('count', '100');
				
					$this->curl1->setReturnFormat('json');
					$this->curl1->addRequestData('a', 'getTranscodeServer');$datas = $this->curl1->request('getSettingsFromMediaserver.php');
			
						$this->tpl->addVar('transcode_server', $datas);
				
						$this->curl1 = $this->curl;
				
			$this->curl1->initPostData();
			$this->curl1->addRequestData('trigger_action', 'show');
			$this->curl1->addRequestData('trigger_mod_uniqueid', 'livmedia');
			
					$this->curl1->addRequestData('count', '100');
				
					$this->curl1->setReturnFormat('json');
					$this->curl1->addRequestData('a', 'getDefaultWater');$datas = $this->curl1->request('getSettingsFromMediaserver.php');
			
						$this->tpl->addVar('water_default', $datas);
				
						$this->curl1 = $this->curl;
				
			$this->curl1->initPostData();
			$this->curl1->addRequestData('trigger_action', 'show');
			$this->curl1->addRequestData('trigger_mod_uniqueid', 'livmedia');
			
					$this->curl1->addRequestData('count', '100');
				
					$this->curl1->setReturnFormat('json');
					$this->curl1->addRequestData('a', 'getVodConfig');$datas = $this->curl1->request('getSettingsFromMediaserver.php');
			
						$this->tpl->addVar('vod_config', $datas);
				
			$count = intval($this->input['count']);
			$count = $count ? $count : 20;
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a', '__getConfig');
			$_configs = $this->curl->request('vod.php');
			$_configs = $_configs[0];
			$this->tpl->addVar('_configs', $_configs);
			
				$_page = $page = intval($this->input['pp']);
				
				//切换节点换
				$node_type = intval($this->input['node_type']);
				$_colid = $this->input["_colid"];

				$this->curl->initPostData();
				
				$this->curl->addRequestData('trigger_action','show');
				$this->curl->addRequestData('trigger_mod_uniqueid','livmedia');
				$this->curl->addRequestData('a', 'count');
				$total = $this->curl->request('vod.php');
				$total = intval($total['total']);
				$data = array();
				$data['totalpages']   = $total;
				$data['perpage'] = $count;
				$data['curpage'] = $_page;
				$extralink = '';

				foreach ($this->input AS $k => $v)
				{
					if ($k != 'mid' && $k != 'hg_search')
					{
					    if($k == 'referto' && $v){
                            $v = urlencode($v);
                        }
						$extralink .= '&amp;' . $k . '=' . $v;
					}
				}
				$data['pagelink'] = '?mid=190' . $extralink;
				$pagelink = hg_build_pagelinks($data);
				$this->tpl->addVar('pagelink', $pagelink);
				$this->tpl->addVar('total', $total);
			$op = array('recommend' => array('name' =>'推荐',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'推荐\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=190&a=recommend',
					
					), 
'delete' => array('name' =>'删除',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'删除\', 1);"',
					'pre' => '',
					'link' => './run.php?mid=190&a=delete',
					
					), 
'audit' => array('name' =>'审核',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'审核\', 1);"',
					'pre' => '',
					'link' => './run.php?mid=190&a=audit',
					
					), 
'move' => array('name' =>'移动视频到某个类别下',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'移动视频到某个类别下\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=190&a=move',
					
					), 
'setmark' => array('name' =>'设置是否可以标注',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'设置是否可以标注\', 1);"',
					'pre' => '',
					'link' => './run.php?mid=190&a=setmark',
					
					), 
'pickup_video' => array('name' =>'提取视频到制定目录',
					'brief' =>'提取视频到制定目录',
					'attr' => ' onclick="return hg_ajax_post(this, \'提取视频到制定目录\', 1);"',
					'pre' => '',
					'link' => './run.php?mid=190&a=pickup_video',
					
					), 
'sync_letv' => array('name' =>'上传至乐视云',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'上传至乐视云\', 1);"',
					'pre' => '',
					'link' => './run.php?mid=190&a=sync_letv',
					
					), 
'get_form_api' => array('name' =>'表单初始化',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'表单初始化\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=190&a=get_form_api',
					
					), 
);$batch_op = array('recommend' => array('name' =>'推荐', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'recommend\',  \'推荐\', 0, \'id\', \'\', \'ajax\');"',), 
'delete' => array('name' =>'删除', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'delete\',  \'删除\', 1, \'id\', \'\', \'ajax\');"',), 
'audit' => array('name' =>'审核', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'audit\',  \'审核\', 1, \'id\', \'\', \'ajax\');"',), 
'move' => array('name' =>'移动视频到某个类别下', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'move\',  \'移动视频到某个类别下\', 0, \'id\', \'\', \'ajax\');"',), 
'setmark' => array('name' =>'设置是否可以标注', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'setmark\',  \'设置是否可以标注\', 1, \'id\', \'\', \'ajax\');"',), 
'pickup_video' => array('name' =>'提取视频到制定目录', 'brief' =>'提取视频到制定目录', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'pickup_video\',  \'提取视频到制定目录\', 1, \'id\', \'\', \'ajax\');"',), 
'sync_letv' => array('name' =>'上传至乐视云', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'sync_letv\',  \'上传至乐视云\', 1, \'id\', \'\', \'ajax\');"',), 
);
					$this->tpl->addVar('op', $op);
					$this->tpl->addVar('batch_op', $batch_op);
			
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('trigger_action','show');
				$this->curl->addRequestData('trigger_mod_uniqueid','livmedia');
		
					$this->curl->addRequestData('offset', $page);
			
				$this->curl->addRequestData('count', $count);
				if(isset($this->input["_colid"]))
				{
					if($conids)
					{
						$this->curl->addRequestData('id', implode(",",$conids["conid"]));
					}
					else
					{
						$this->curl->addRequestData('id', -1);
					}
				}
				else
				{
				}

				$this->curl->addRequestData('a', 'show');
		
				$datas = $this->curl->request('vod.php');
		$_relate_module = array(0=> ' 无 ',);$relate_menu = array();
				$this->tpl->addHeaderCode('  <script type="text/javascript">
//<![CDATA[
var gBatchAction = new Array();gBatchAction[\'recommend\'] = "./run.php?mid=190&a=recommend";gBatchAction[\'delete\'] = "./run.php?mid=190&a=delete";gBatchAction[\'audit\'] = "./run.php?mid=190&a=audit";gBatchAction[\'move\'] = "./run.php?mid=190&a=move";gBatchAction[\'setmark\'] = "./run.php?mid=190&a=setmark";gBatchAction[\'pickup_video\'] = "./run.php?mid=190&a=pickup_video";gBatchAction[\'sync_letv\'] = "./run.php?mid=190&a=sync_letv";
//]]>
  </script>
');
				$this->tpl->setSoftVar('livmedia'); //设置软件界面
				$this->tpl->addVar('vodinfo_list', $datas);
				$this->tpl->addVar('module_id', 190);
				$this->tpl->addVar('_cur_module_name', '视频');
				$this->tpl->addVar('_m_menu_pos', -1);
				$this->tpl->addVar('relate_module_id', 190);
				$this->tpl->addVar('_relate_module', $_relate_module);
				$this->tpl->addVar('relate_menu', $relate_menu);
				$this->tpl->addVar('primary_key', 'id');
			
					$this->tpl->setTemplateVersion(''); 
					$this->tpl->setScriptDir(''); 
				
			$this->tpl->outTemplate('vodinfo_list');
				?>
			