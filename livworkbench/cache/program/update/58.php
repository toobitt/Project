<?php
		
		$this->curl = new curl('localhost', 'livsns/api/news/admin/');
		
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				
				$id = $this->input['id'];
				$this->curl->initPostData();
				$this->curl->addRequestData('a', 'update');
				$this->curl->addRequestData('id', $id);
				$hg_data_return = $this->curl->request('news_update.php');
				
				include_once(ROOT_PATH . 'lib/class/log.class.php');
				$log = new hglog();
				$logcontent = '保存内容';
				$log->add_log($logcontent, 'update');
				if($log_pub)
				{
					$log->add_log("文稿发布内容[{$id}]至CMS栏目");
				}
				
					if ($hg_data_return)
					{
						$this->redirect('保存成功', '', 0, 0, "top.&#036;.closeFormWin('$id')");
					}
					else
					{
						$this->ReportError('保存失败', '', 0, 0, "top.&#036;.closeFormWin('$id')");
					}
					 ?>