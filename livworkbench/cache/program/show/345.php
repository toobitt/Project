<?php
			$api = array(
				'host' => 'vapi1.dev.hogesoft.com',
				'port' => '80',
				'dir' => 'admin/',
				);
			$this->tpl->addVar('__api', urlencode(json_encode($api)));
			$this->curl = new curl('vapi1.dev.hogesoft.com', 'admin/');
		
			$count = intval($this->input['count']);
			$count = $count ? $count : 25;
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a', '__getConfig');
			$_configs = $this->curl->request('transcode_center.php');
			$_configs = $_configs[0];
			$this->tpl->addVar('_configs', $_configs);
			$op = array('delete' => array('name' =>'删除',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'删除\', 1);"',
					'pre' => '',
					'link' => './run.php?mid=345&a=delete',
					
					), 
);$batch_op = array('delete' => array('name' =>'删除', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'delete\',  \'删除\', 1, \'id\', \'\', \'ajax\');"',), 
);
					$this->tpl->addVar('op', $op);
					$this->tpl->addVar('batch_op', $batch_op);
			
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('trigger_action','show');
				$this->curl->addRequestData('trigger_mod_uniqueid','mediaserver');
		
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
		
				$datas = $this->curl->request('transcode_center.php');
		$_relate_module = array(0=> ' 无 ',);$relate_menu = array();
				$this->tpl->addHeaderCode('  <script type="text/javascript">
//<![CDATA[
var gBatchAction = new Array();gBatchAction[\'delete\'] = "./run.php?mid=345&a=delete";
//]]>
  </script>
');
				$this->tpl->setSoftVar('mediaserver'); //设置软件界面
				$this->tpl->addVar('transcode_center_list', $datas);
				$this->tpl->addVar('module_id', 345);
				$this->tpl->addVar('_cur_module_name', '转码');
				$this->tpl->addVar('_m_menu_pos', 0);
				$this->tpl->addVar('relate_module_id', 0);
				$this->tpl->addVar('_relate_module', $_relate_module);
				$this->tpl->addVar('relate_menu', $relate_menu);
				$this->tpl->addVar('primary_key', 'id');
			
					$this->tpl->setTemplateVersion(''); 
					$this->tpl->setScriptDir(''); 
				
			$this->tpl->outTemplate('transcode_center_list');
				?>
			