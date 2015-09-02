<?php
		
						$this->curl1 = new curl('localhost', 'livsns/api/livmedia/admin/');
				
			$this->curl1->initPostData();
			$this->curl1->addRequestData('trigger_action', 'form');
			$this->curl1->addRequestData('trigger_mod_uniqueid', 'livmedia');
			
				$this->curl1->setReturnFormat('json');
				$this->curl1->addRequestData('a', 'get_status');$datas = $this->curl1->request('vod_get_status.php');
			
						$this->tpl->addVar('video_status', $datas);
				
						$this->curl1 = new curl('localhost', 'livsns/api/livmedia/admin/');
				
			$this->curl1->initPostData();
			$this->curl1->addRequestData('trigger_action', 'form');
			$this->curl1->addRequestData('trigger_mod_uniqueid', 'livmedia');
			
					$this->curl1->addRequestData('count', '100');
				
				$this->curl1->setReturnFormat('json');
				$this->curl1->addRequestData('a', 'get_sort_name');$datas = $this->curl1->request('vod_get_sort_name.php');
			
						$this->tpl->addVar('vod_sort', $datas);
				
		$this->curl = new curl('localhost', 'livsns/api/livmedia/admin/');
		
				$id = $this->input['id'];
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('a', '__getConfig');
				$_configs = $this->curl->request('vod.php');
				$_configs = $_configs[0];
				$this->tpl->addVar('_configs', $_configs);
				if ($id)
				{
					$this->curl->initPostData();
					$this->curl->addRequestData('a', 'detail');
					$this->curl->addRequestData('id', $id);
					$formdata = $this->curl->request('vod.php');
					if ($formdata)
					{
						if(count($formdata) == 1)
						{
							$formdata = $formdata[0];
						}
					}
					if (!$formdata)
					{
						$this->ReportError('指定记录不存在或已删除!');
					}
					$a = 'update';
					$optext = '更新';
				}
				else
				{
					$a = 'create';
					$formdata = $this->input;
					$optext = '增加';
				}
			$relate_menu = array();
					$nav = array(
						'name' => $optext,
						'link' => '#'
					);
					if($formdata["end"])
					{
						$this->navdata["title"] = array(
						"name" =>$formdata["title"],
			            "class" => "",
			            "link" => "#",
			            "target" => "mainwin",
						);
					}
					//$this->append_nav($nav);
					$this->tpl->addVar('_nav', $this->nav);
					$this->tpl->addVar('_navdata', $this->navdata);
					$this->tpl->setSoftVar('livmedia'); //设置软件界面
					$this->tpl->addVar('a', $a);
					$this->tpl->addVar('optext', $optext);
					$this->tpl->addVar('form_set', $form_set);
					$this->tpl->addVar('formdata', $formdata);
					$this->tpl->addVar('id', $id);
					$this->tpl->addVar('hg_title', '');
					$this->tpl->addVar('primary_key', 'id');
					$this->tpl->addVar('relate_menu', $relate_menu);
				
						$this->tpl->setTemplateVersion(''); 
						$this->tpl->setScriptDir(''); 
					$this->tpl->outTemplate('vod_update_list'); ?>