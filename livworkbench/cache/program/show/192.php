<?php
			$api = array(
				'host' => 'localhost',
				'port' => '80',
				'dir' => 'livsns/api/livmedia/admin/',
				);
			$this->tpl->addVar('__api', urlencode(json_encode($api)));
			$this->curl = new curl('localhost', 'livsns/api/livmedia/admin/');
		
			$count = intval($this->input['count']);
			$count = $count ? $count : 20;
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a', '__getConfig');
			$_configs = $this->curl->request('vod_media_node.php');
			$_configs = $_configs[0];
			$this->tpl->addVar('_configs', $_configs);
			
				$_page = $page = intval($this->input['pp']);
				
				//切换节点换
				$node_type = intval($this->input['node_type']);
				$_colid = $this->input["_colid"];

				$this->curl->initPostData();
				
				$this->curl->addRequestData('trigger_action','show');
				$this->curl->addRequestData('trigger_mod_uniqueid','livmedia_node');
				$this->curl->addRequestData('a', 'count');
				$total = $this->curl->request('vod_media_node.php');
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
				$data['pagelink'] = '?mid=192' . $extralink;
				$pagelink = hg_build_pagelinks($data);
				$this->tpl->addVar('pagelink', $pagelink);
				$this->tpl->addVar('total', $total);
			$op = array('delete' => array('name' =>'删除类别',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'删除类别\', 1);"',
					'pre' => '',
					'link' => './run.php?mid=192&a=delete',
					
					), 
);$batch_op = array('delete' => array('name' =>'删除类别', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'delete\',  \'删除类别\', 1, \'id\', \'\', \'ajax\');"',), 
);
					$this->tpl->addVar('op', $op);
					$this->tpl->addVar('batch_op', $batch_op);
			
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('trigger_action','show');
				$this->curl->addRequestData('trigger_mod_uniqueid','livmedia_node');
		
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
		
				$datas = $this->curl->request('vod_media_node.php');
		$_relate_module = array(0=> ' 无 ',);$relate_menu = array();
				$this->tpl->addHeaderCode('  <script type="text/javascript">
//<![CDATA[
var gBatchAction = new Array();gBatchAction[\'delete\'] = "./run.php?mid=192&a=delete";
//]]>
  </script>
');
				$this->tpl->setSoftVar('livmedia'); //设置软件界面
				$this->tpl->addVar('vod_sort_list', $datas);
				$this->tpl->addVar('module_id', 192);
				$this->tpl->addVar('_cur_module_name', '分类配置');
				$this->tpl->addVar('_m_menu_pos', 0);
				$this->tpl->addVar('relate_module_id', 0);
				$this->tpl->addVar('_relate_module', $_relate_module);
				$this->tpl->addVar('relate_menu', $relate_menu);
				$this->tpl->addVar('primary_key', 'id');
			
					$this->tpl->setTemplateVersion(''); 
					$this->tpl->setScriptDir(''); 
				
			$this->tpl->outTemplate('vod_sort_list');
				?>
			