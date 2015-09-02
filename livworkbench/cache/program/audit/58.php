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
				$this->curl->addRequestData('a', 'audit');
				$this->curl->addRequestData('id', $id);$hg_data_return = $this->curl->request('news_update.php');
				
						
								$HTTP_HOST = $_SERVER['HTTP_HOST'];
								$info = explode(':', $HTTP_HOST);
								$hg_data_return = json_encode($hg_data_return);
								if ($info[1])
								{
									$hg_data_return = str_replace(array('img.hoge.cn', 'vod.hoge.cn'), array('img.hoge.cn:234', 'vod.hoge.cn:234'), $hg_data_return);
								}
								echo $hg_data_return;?>