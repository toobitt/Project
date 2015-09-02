<?php
			$api = array(
				'host' => 'localhost',
				'port' => '80',
				'dir' => 'livsns/api/auth/admin/',
				);
			$this->tpl->addVar('__api', urlencode(json_encode($api)));
			$this->curl = new curl('localhost', 'livsns/api/auth/admin/');
		
						$this->curl1 = $this->curl;
				
			$this->curl1->initPostData();
			$this->curl1->addRequestData('trigger_action', 'show');
			$this->curl1->addRequestData('trigger_mod_uniqueid', 'auth');
			
					$this->curl1->addRequestData('count', '100');
				
					$this->curl1->setReturnFormat('json');
					$this->curl1->addRequestData('a', 'append_admin_role');$datas = $this->curl1->request('admin.php');
			
						$this->tpl->addVar('appendRole', $datas);
				
			$count = intval($this->input['count']);
			$count = $count ? $count : 20;
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a', '__getConfig');
			$_configs = $this->curl->request('admin.php');
			$_configs = $_configs[0];
			$this->tpl->addVar('_configs', $_configs);
			
				$_page = $page = intval($this->input['pp']);
				
				//切换节点换
				$node_type = intval($this->input['node_type']);
				$_colid = $this->input["_colid"];

				$this->curl->initPostData();
				
				$this->curl->addRequestData('trigger_action','show');
				$this->curl->addRequestData('trigger_mod_uniqueid','auth');
				$this->curl->addRequestData('a', 'count');
				$total = $this->curl->request('admin.php');
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
				$data['pagelink'] = '?mid=270' . $extralink;
				$pagelink = hg_build_pagelinks($data);
				$this->tpl->addVar('pagelink', $pagelink);
				$this->tpl->addVar('total', $total);
			$op = array('delete' => array('name' =>'删除',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'删除\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=270&a=delete',
					
					), 
);$batch_op = array('delete' => array('name' =>'删除', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'delete\',  \'删除\', 0, \'id\', \'\', \'ajax\');"',), 
);
					$this->tpl->addVar('op', $op);
					$this->tpl->addVar('batch_op', $batch_op);
			
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('trigger_action','show');
				$this->curl->addRequestData('trigger_mod_uniqueid','auth');
		
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
		
				$datas = $this->curl->request('admin.php');
		$_relate_module = array(0=> ' 无 ',);$relate_menu = array();
				$this->tpl->addHeaderCode('  <script type="text/javascript">
//<![CDATA[
var gBatchAction = new Array();gBatchAction[\'delete\'] = "./run.php?mid=270&a=delete";
//]]>
  </script>
');
				$this->tpl->setSoftVar('auth'); //设置软件界面
				$this->tpl->addVar('list', $datas);
				$this->tpl->addVar('module_id', 270);
				$this->tpl->addVar('_cur_module_name', '权限');
				$this->tpl->addVar('_m_menu_pos', 1);
				$this->tpl->addVar('relate_module_id', 0);
				$this->tpl->addVar('_relate_module', $_relate_module);
				$this->tpl->addVar('relate_menu', $relate_menu);
				$this->tpl->addVar('primary_key', 'id');
			
					$this->tpl->setTemplateVersion(''); 
					$this->tpl->setScriptDir(''); 
				
			$this->tpl->outTemplate('admin_list');
				?>
			