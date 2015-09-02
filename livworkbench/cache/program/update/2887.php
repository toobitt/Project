<?php
		
		$this->curl = new curl('localhost', 'livsns/api/outpush/admin/');
		
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				
				$id = $this->input['id'];
				$this->curl->initPostData();
				$this->curl->addRequestData('a', 'update');
				$this->curl->addRequestData('id', $id);
				$hg_data_return = $this->curl->request('outpush_update.php');
				
					$this->curl->initPostData();
					$this->curl->addRequestData('a', '__getConfig');
					$_configs = $this->curl->request('outpush_update.php');
					$_configs = $_configs[0];
					$this->tpl->addVar('_configs', $_configs);
					$this->tpl->setSoftVar('outpush'); //设置软件界面
					$this->tpl->addVar('id', $id);
					$this->tpl->addVar('hg_commend_fields', $hg_commend_fields);
					$this->tpl->addVar('fixcommendfields', $fixcommendfields);
					$this->tpl->addVar('primary_key', 'id');
					$hg_set_template = 'outpush_list';
					$hg_set_callback = '';
					$hg_set_return = 'formdata';
					$hg_primary_key = 'id';
				
						$this->tpl->setTemplateVersion(''); 
						$this->tpl->setScriptDir(''); 
					
				if (is_array($hg_data_return) && !$hg_data_return["ErrorCode"])
				{
					$hg_data_return = $hg_data_return[0];
				}
				$this->tpl->addVar('_relate_module', $_relate_module);
				$this->tpl->addVar('formdata', $hg_data_return);
				if($hg_data_return["ErrorCode"])
				{
					$this->tpl->outTemplate('outpush_list', ",error");
				}
				else
				{
					$this->tpl->outTemplate('outpush_list', "");
				}
				 ?>