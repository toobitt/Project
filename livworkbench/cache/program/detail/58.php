<?php
		
		$api = array(
			'host' => 'localhost',
			'port' => '80',
			'dir' => 'livsns/api/news/admin/',
			);
		$this->tpl->addVar('__api', urlencode(json_encode($api)));
		$this->curl = new curl('localhost', 'livsns/api/news/admin/');
		
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				
				$id = $this->input['id'];
				$this->curl->initPostData();
				$this->curl->addRequestData('a', 'detail');
				$this->curl->addRequestData('id', $id);$hg_data_return = $this->curl->request('news.php');
				
								$this->curl1 = $this->curl;
						
					$this->curl1->initPostData();
					$this->curl1->addRequestData('trigger_action', 'detail');
					$this->curl1->addRequestData('trigger_mod_uniqueid', 'news');
					
							$this->curl1->addRequestData('count', '200');
						
							$this->curl1->setReturnFormat('json');
							$this->curl1->addRequestData('a', 'draft_list');$this->curl1->addRequestData('draft_list', $this->input["draft_list"]);$datas = $this->curl1->request('news.php');
					
								$this->tpl->addVar('draft_list', $datas);
						$relate_menu = array();
					$nav = array(
						'name' => '编辑表单',
						'link' => '#'
					);
					$this->append_nav($nav);
					$this->tpl->addVar('_nav', $this->nav);
					$this->tpl->addVar('_navdata', $this->navdata);
					$this->curl->initPostData();
					$this->curl->addRequestData('a', '__getConfig');
					$_configs = $this->curl->request('news.php');
					$_configs = $_configs[0];
					$this->tpl->addVar('_configs', $_configs);
					$this->tpl->setSoftVar('news'); //设置软件界面
					$this->tpl->addVar('id', $id);
					$this->tpl->addVar('hg_commend_fields', $hg_commend_fields);
					$this->tpl->addVar('fixcommendfields', $fixcommendfields);
					$this->tpl->addVar('primary_key', 'id');
					$this->tpl->addVar('relate_menu', $relate_menu);
					$hg_set_template = 'news_form';
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
						$this->tpl->outTemplate('news_form', ",error");
					}
					else
					{
						$this->tpl->outTemplate('news_form', "");
					}
					?>