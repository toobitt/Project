<?php
		
		$api = array(
			'host' => 'localhost',
			'port' => '80',
			'dir' => 'livsns/api/livmedia/admin/',
			);
		$this->tpl->addVar('__api', urlencode(json_encode($api)));
		$this->curl = new curl('localhost', 'livsns/api/livmedia/admin/');
		
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				
				$id = $this->input['id'];
				$this->curl->initPostData();
				$this->curl->addRequestData('a', 'advanced_search_form');
				$this->curl->addRequestData('id', $id);$hg_data_return = $this->curl->request('vod.php');
				$relate_menu = array();
					$nav = array(
						'name' => '高级搜索',
						'link' => '#'
					);
					$this->append_nav($nav);
					$this->tpl->addVar('_nav', $this->nav);
					$this->tpl->addVar('_navdata', $this->navdata);
					$this->curl->initPostData();
					$this->curl->addRequestData('a', '__getConfig');
					$_configs = $this->curl->request('vod.php');
					$_configs = $_configs[0];
					$this->tpl->addVar('_configs', $_configs);
					$this->tpl->setSoftVar('livmedia'); //设置软件界面
					$this->tpl->addVar('id', $id);
					$this->tpl->addVar('hg_commend_fields', $hg_commend_fields);
					$this->tpl->addVar('fixcommendfields', $fixcommendfields);
					$this->tpl->addVar('primary_key', 'id');
					$this->tpl->addVar('relate_menu', $relate_menu);
					$hg_set_template = 'advanced_search';
					$hg_set_callback = '';
					$hg_set_return = 'formdata';
					$hg_primary_key = 'id';
				
						$this->tpl->setTemplateVersion(''); 
						$this->tpl->setScriptDir(''); 
					
					if (is_array($hg_data_return) && !$hg_data_return["ErrorCode"])
					{
						$hg_data_return = $hg_data_return[0];
					}$_relate_module = array(0=> ' 无 ',);
					$this->tpl->addVar('_relate_module', $_relate_module);
					$this->tpl->addVar('formdata', $hg_data_return);
					if($hg_data_return["ErrorCode"])
					{
						$this->tpl->outTemplate('advanced_search', ",error");
					}
					else
					{
						$this->tpl->outTemplate('advanced_search', "");
					}
					?>