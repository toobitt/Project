<?php
		
		$api = array(
			'host' => 'localhost',
			'port' => '80',
			'dir' => 'livsns/api/auth/admin/',
			);
		$this->tpl->addVar('__api', urlencode(json_encode($api)));
		$this->curl = new curl('localhost', 'livsns/api/auth/admin/');
		
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				
				$id = $this->input['id'];
				$this->curl->initPostData();
				$this->curl->addRequestData('a', 'create');
				$this->curl->addRequestData('id', $id);$hg_data_return = $this->curl->request('admin_update.php');
				
					//file_put_contents('1.txt', var_export($hg_data_return,1));
					if ($hg_data_return)
					{
						$callback = '';

						$url = explode('?', $callback);
						if (!$url[1])
						{
							$url = $url[0] . '?mid=270';
						}
						else
						{
							$url[1] = str_replace('&amp;', '&', $url[1]);
							$para = explode('&', $url[1]);
							$url = $url[0] . '?mid=270';
							$hg_d = $hg_data_return[0];
							foreach ($para AS $p)
							{
								$p = explode('=', $p);
								$url .= '&' . $p[0] . '=' . $hg_d[$p[0]];
							}
						}
						$this->redirect('添加用户成功', $url, 0, 0, "");
					}
					else
					{
						$this->ReportError('添加用户失败', '', 0, 0, "");
					}
					?>