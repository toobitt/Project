<?php
			$this->curlNode = new curl('localhost', 'livsns/api/livmedia/admin/', '0dd80226a589af659b38efa93b1d29d0');
		
			$fid = $this->input['fid'];
			$offset = $this->input['offset'];
			$count = $this->input['count'];
			$this->curlNode->setReturnFormat('json');
			$this->curlNode->initPostData();
			$this->curlNode->addRequestData('a', 'show');
			if (!empty($offset))
			{
				$this->curlNode->addRequestData('offset', $offset);
			}
			if (!empty($count))
			{
				$this->curlNode->addRequestData('count', $count);
			}
			$this->curlNode->addRequestData('fid', $fid);
			$this->curlNode->addRequestData('trigger_action', 'show');
			$this->curlNode->addRequestData('trigger_mod_uniqueid', 'livmedia');
			$hg_data = $this->curlNode->request('vod_media_node.php');
			$s = 'hg_columns_selected';
			if ($$s)
			{
				if (!is_array($$s))
				{
					$$s = array($$s);
				}
				$hg_selected_node = implode(',', $$s);
				$this->curlNode->initPostData();
				$this->curlNode->addRequestData('a', 'show');
				$this->curlNode->addRequestData('_id', $hg_selected_node);
				$hg_selected_data = $this->curlNode->request('vod_media_node.php');
				$this->tpl->addVar('hg_columns_selected', $hg_selected_data);
			}
			$hg_node_template = 'node';
			$extlink = '&amp;infrm=1';
			$hg_attr['nodeapi'] = 'fetch_node.php?nid=49&amp;node_en=vod_media_node&amp;mid=' . $this->input['mid'] . $extlink;
			
			$this->tpl->addVar('hg_columns', $hg_data);
			$this->tpl->addVar('hg_columns_attr', $hg_attr);
			$this->tpl->addVar('hg_data', $hg_data);
			$this->tpl->addVar('hg_attr', $hg_attr);
			