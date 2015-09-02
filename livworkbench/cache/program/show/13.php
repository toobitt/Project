<?php
			$api = array(
				'host' => 'localhost',
				'port' => '80',
				'dir' => 'livsns/api/tuji/admin/',
				);
			$this->tpl->addVar('__api', urlencode(json_encode($api)));
			$this->curl = new curl('localhost', 'livsns/api/tuji/admin/');
		
			$count = intval($this->input['count']);
			$count = $count ? $count : 20;
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a', '__getConfig');
			$_configs = $this->curl->request('tuji.php');
			$_configs = $_configs[0];
			$this->tpl->addVar('_configs', $_configs);
			
				$_page = $page = intval($this->input['pp']);
				
				//切换节点换
				$node_type = intval($this->input['node_type']);
				$_colid = $this->input["_colid"];

				$this->curl->initPostData();
				
				$this->curl->addRequestData('trigger_action','show');
				$this->curl->addRequestData('trigger_mod_uniqueid','tuji');
				$this->curl->addRequestData('a', 'count');
				$total = $this->curl->request('tuji.php');
				$total = intval($total['total']);
				$data = array();
				$data['totalpages']   = $total;
				$data['perpage'] = $count;
				$data['curpage'] = $_page;
				$extralink = '';

				foreach ($this->input AS $k => $v)
				{
					if ($k != 'mid' && $k != 'hg_search')
					{
					    if($k == 'referto' && $v){
                            $v = urlencode($v);
                        }
						$extralink .= '&amp;' . $k . '=' . $v;
					}
				}
				$data['pagelink'] = '?mid=13' . $extralink;
				$pagelink = hg_build_pagelinks($data);
				$this->tpl->addVar('pagelink', $pagelink);
				$this->tpl->addVar('total', $total);
			$op = array('upload' => array('name' =>'上传',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'上传\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=13&a=upload',
					
					), 
'list' => array('name' =>'查看',
					'brief' =>'',
					'attr' => '',
					'pre' => '_',
					'link' => './run.php?mid=17',
					
					), 
'recommend' => array('name' =>'推荐',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'推荐\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=13&a=recommend',
					
					), 
'delete' => array('name' =>'删除',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'删除\', 1);"',
					'pre' => '',
					'link' => './run.php?mid=13&a=delete',
					
					), 
'audit' => array('name' =>'审核',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'审核\', 1);"',
					'pre' => '',
					'link' => './run.php?mid=13&a=audit',
					
					), 
'revolveImg' => array('name' =>'图片旋转',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'图片旋转\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=13&a=revolveImg',
					
					), 
'form_outerlink' => array('name' =>'编辑外链数据',
					'brief' =>'',
					'attr' => '',
					'pre' => '',
					'link' => './run.php?mid=13&a=form_outerlink',
					
					), 
'upload_indexpic' => array('name' =>'上传索引图片',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'上传索引图片\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=13&a=upload_indexpic',
					
					), 
'move_form' => array('name' =>'打开移动表单',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'打开移动表单\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=13&a=move_form',
					
					), 
);$batch_op = array('recommend' => array('name' =>'推荐', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'recommend\',  \'推荐\', 0, \'id\', \'\', \'ajax\');"',), 
'delete' => array('name' =>'删除', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'delete\',  \'删除\', 1, \'id\', \'\', \'ajax\');"',), 
'audit' => array('name' =>'审核', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'audit\',  \'审核\', 1, \'id\', \'\', \'ajax\');"',), 
'form_outerlink' => array('name' =>'编辑外链数据', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'form_outerlink\',  \'编辑外链数据\', 0, \'id\', \'\', \'\');"',), 
'upload_indexpic' => array('name' =>'上传索引图片', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'upload_indexpic\',  \'上传索引图片\', 0, \'id\', \'\', \'ajax\');"',), 
'move_form' => array('name' =>'打开移动表单', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'move_form\',  \'打开移动表单\', 0, \'id\', \'\', \'ajax\');"',), 
);
					$this->tpl->addVar('op', $op);
					$this->tpl->addVar('batch_op', $batch_op);
			
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('trigger_action','show');
				$this->curl->addRequestData('trigger_mod_uniqueid','tuji');
		
					$this->curl->addRequestData('offset', $page);
			
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
		
				$datas = $this->curl->request('tuji.php');
		
				if($this->input['hgupdn'] == 'ASC')
				{
					$hgorderby = 'DESC';
					$order_clew = '倒序';
				}
				else
				{
					$hgorderby = 'ASC';
					$order_clew = '正序';
				}
				if ($this->input['search_hash'])
				{
					$hgorderby .= '&amp;search_hash=' . $this->input['search_hash'];
				}
				if ($this->input['_id'])
				{
					$hgorderby .= '&amp;_id=' . $this->input['_id'];
				}
			$list_fields = array('id' => array(
									'title' => '<a href="./run.php?mid=13&hgorder=id&hgupdn=' . $hgorderby . '" title="点击' . $order_clew . '排列">图集ID</a>',
									'pic' => '',
									'time' => '',
									'exper' => '{$v[\'id\']}',
									'width' => '',
									),
'title' => array(
									'title' => '<a href="./run.php?mid=13&hgorder=title&hgupdn=' . $hgorderby . '" title="点击' . $order_clew . '排列">图集标题</a>',
									'pic' => '',
									'time' => '',
									'exper' => '<span class=\"title\">{$v[\'title\']}</span>',
									'width' => '',
									),
'desc' => array(
									'title' => '<a href="./run.php?mid=13&hgorder=desc&hgupdn=' . $hgorderby . '" title="点击' . $order_clew . '排列">图集描述</a>',
									'pic' => '',
									'time' => '',
									'exper' => '{$v[\'desc\']}',
									'width' => '',
									),
'create_time' => array(
									'title' => '<a href="./run.php?mid=13&hgorder=create_time&hgupdn=' . $hgorderby . '" title="点击' . $order_clew . '排列">发布时间</a>',
									'pic' => '',
									'time' => '',
									'exper' => '{$v[\'create_time\']}',
									'width' => '',
									),
'status' => array(
									'title' => '<a href="./run.php?mid=13&hgorder=status&hgupdn=' . $hgorderby . '" title="点击' . $order_clew . '排列">审核</a>',
									'pic' => '',
									'time' => '',
									'exper' => '{$v[\'status\']}',
									'width' => '',
									),
'cover_url' => array(
									'title' => '<a href="./run.php?mid=13&hgorder=cover_url&hgupdn=' . $hgorderby . '" title="点击' . $order_clew . '排列">图集封面</a>',
									'pic' => 'cover_url',
									'time' => '',
									'exper' => '<img class=\"resize\" src=\"{$v[\'cover_url\']}\" />',
									'width' => '',
									),
'sort_name' => array(
									'title' => '<a href="./run.php?mid=13&hgorder=sort_name&hgupdn=' . $hgorderby . '" title="点击' . $order_clew . '排列">图集所属</a>',
									'pic' => '',
									'time' => '',
									'exper' => '{$v[\'sort_name\']}',
									'width' => '',
									),
);

				$this->tpl->addVar('list_fields', $list_fields);
				$_relate_module = array(0=> ' 无 ',);$relate_menu = array();
				$this->tpl->addHeaderCode('  <script type="text/javascript">
//<![CDATA[
var gBatchAction = new Array();gBatchAction[\'recommend\'] = "./run.php?mid=13&a=recommend";gBatchAction[\'delete\'] = "./run.php?mid=13&a=delete";gBatchAction[\'audit\'] = "./run.php?mid=13&a=audit";gBatchAction[\'form_outerlink\'] = "./run.php?mid=13&a=form_outerlink";gBatchAction[\'upload_indexpic\'] = "./run.php?mid=13&a=upload_indexpic";gBatchAction[\'move_form\'] = "./run.php?mid=13&a=move_form";
//]]>
  </script>
');
				$this->tpl->setSoftVar('tuji'); //设置软件界面
				$this->tpl->addVar('tuji_list', $datas);
				$this->tpl->addVar('module_id', 13);
				$this->tpl->addVar('_cur_module_name', '图集');
				$this->tpl->addVar('_m_menu_pos', 0);
				$this->tpl->addVar('relate_module_id', 0);
				$this->tpl->addVar('_relate_module', $_relate_module);
				$this->tpl->addVar('relate_menu', $relate_menu);
				$this->tpl->addVar('primary_key', 'id');
			
					$this->tpl->setTemplateVersion(''); 
					$this->tpl->setScriptDir(''); 
				
			$this->tpl->outTemplate('tuji_list');
				?>
			