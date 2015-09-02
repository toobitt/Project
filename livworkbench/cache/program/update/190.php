<?php
		
		$this->curl = new curl('localhost', 'livsns/api/livmedia/admin/');
		
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				
				$id = $this->input['id'];
				$this->curl->initPostData();
				$this->curl->addRequestData('a', 'update');
				$this->curl->addRequestData('id', $id);
				$hg_data_return = $this->curl->request('vod_update.php');
				
				include_once(ROOT_PATH . 'lib/class/log.class.php');
				$log = new hglog();
				$logcontent = '更新内容';
				$log->add_log($logcontent, 'update');
				if($log_pub)
				{
					$log->add_log("视频发布内容[{$id}]至CMS栏目");
				}
				
					if ($hg_data_return)
					{
						$this->redirect('更新成功', '', 0, 0, "top.&#036;.closeFormWin('$id')");
					}
					else
					{
						$this->ReportError('更新失败', '', 0, 0, "top.&#036;.closeFormWin('$id')");
					}
					 ?>