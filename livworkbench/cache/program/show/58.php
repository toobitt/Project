<?php
			$api = array(
				'host' => 'localhost',
				'port' => '80',
				'dir' => 'livsns/api/news/admin/',
				);
			$this->tpl->addVar('__api', urlencode(json_encode($api)));
			$this->curl = new curl('localhost', 'livsns/api/news/admin/');
		
			$count = intval($this->input['count']);
			$count = $count ? $count : 20;
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a', '__getConfig');
			$_configs = $this->curl->request('news.php');
			$_configs = $_configs[0];
			$this->tpl->addVar('_configs', $_configs);
			
				$_page = $page = intval($this->input['pp']);
				
				//切换节点换
				$node_type = intval($this->input['node_type']);
				$_colid = $this->input["_colid"];

				$this->curl->initPostData();
				
				$this->curl->addRequestData('trigger_action','show');
				$this->curl->addRequestData('trigger_mod_uniqueid','news');
				$this->curl->addRequestData('a', 'count');
				$total = $this->curl->request('news.php');
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
				$data['pagelink'] = '?mid=58' . $extralink;
				$pagelink = hg_build_pagelinks($data);
				$this->tpl->addVar('pagelink', $pagelink);
				$this->tpl->addVar('total', $total);
			$op = array('recommend' => array('name' =>'签发',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'签发\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=58&a=recommend',
					
					), 
'delete' => array('name' =>'删除',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'删除\', 1);"',
					'pre' => '',
					'link' => './run.php?mid=58&a=delete',
					
					), 
'upload' => array('name' =>'附件上传',
					'brief' =>'各种附件，包括图片，doc，xls等',
					'attr' => ' onclick="return hg_ajax_post(this, \'附件上传\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=58&a=upload',
					
					), 
'top' => array('name' =>'置顶',
					'brief' =>'置顶操作',
					'attr' => ' onchange="return hg_ajax_post_select(this, \'./run.php?mid=58&a=top\', \'置顶\', 1);"',
					'pre' => '',
					'link' => './run.php?mid=58&a=top',
					'group_op' => array('-1' => ' - 请选择 - ','1' => '置顶文章 ','0' => '取消置顶',),
					), 
'audit' => array('name' =>'审核',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'审核\', 1);"',
					'pre' => '',
					'link' => './run.php?mid=58&a=audit',
					
					), 
'delete_comp' => array('name' =>'彻底删除',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'彻底删除\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=58&a=delete_comp',
					
					), 
'revolveImg' => array('name' =>'旋转图片',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'旋转图片\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=58&a=revolveImg',
					
					), 
'getTujiNode' => array('name' =>'获取图集分类',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'获取图集分类\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=58&a=getTujiNode',
					
					), 
'getTuji' => array('name' =>'获取图集',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'获取图集\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=58&a=getTuji',
					
					), 
'getPic' => array('name' =>'获取图片',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'获取图片\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=58&a=getPic',
					
					), 
'get_material_node' => array('name' =>'取应用、模块',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'取应用、模块\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=58&a=get_material_node',
					
					), 
'get_material_info' => array('name' =>'获取引用素材的详细信息',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'获取引用素材的详细信息\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=58&a=get_material_info',
					
					), 
'form_outerlink' => array('name' =>'编辑外链数据',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'编辑外链数据\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=58&a=form_outerlink',
					
					), 
'upload_indexpic' => array('name' =>'上传索引图片',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'上传索引图片\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=58&a=upload_indexpic',
					
					), 
'move_form' => array('name' =>'打开移动表单',
					'brief' =>'',
					'attr' => ' onclick="return hg_ajax_post(this, \'打开移动表单\', 0);"',
					'pre' => '',
					'link' => './run.php?mid=58&a=move_form',
					
					), 
);$batch_op = array('recommend' => array('name' =>'签发', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'recommend\',  \'签发\', 0, \'id\', \'\', \'ajax\');"',), 
'delete' => array('name' =>'删除', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'delete\',  \'删除\', 1, \'id\', \'\', \'ajax\');"',), 
'top' => array('name' =>'置顶', 'brief' =>'置顶操作', 'attr' => ' onchange="return hg_ajax_batchpost_select(this, \'top\',  \'置顶\', 1, \'id\', \'\', \'ajax\');"','group_op' => array('-1' => ' - 请选择 - ','1' => '置顶文章 ','0' => '取消置顶',),), 
'audit' => array('name' =>'审核', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'audit\',  \'审核\', 1, \'id\', \'\', \'ajax\');"',), 
'form_outerlink' => array('name' =>'编辑外链数据', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'form_outerlink\',  \'编辑外链数据\', 0, \'id\', \'\', \'ajax\');"',), 
'upload_indexpic' => array('name' =>'上传索引图片', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'upload_indexpic\',  \'上传索引图片\', 0, \'id\', \'\', \'ajax\');"',), 
'move_form' => array('name' =>'打开移动表单', 'brief' =>'', 'attr' => ' onclick="return hg_ajax_batchpost(this, \'move_form\',  \'打开移动表单\', 0, \'id\', \'\', \'ajax\');"',), 
);
					$this->tpl->addVar('op', $op);
					$this->tpl->addVar('batch_op', $batch_op);
			
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('trigger_action','show');
				$this->curl->addRequestData('trigger_mod_uniqueid','news');
		
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
		
				$datas = $this->curl->request('news.php');
		
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
									'title' => 'ID',
									'pic' => '',
									'time' => '',
									'exper' => '{$v[\'id\']}',
									'width' => '',
									),
'title' => array(
									'title' => '标题',
									'pic' => '',
									'time' => '',
									'exper' => '<span class=\"title\">{$v[\'title\']}</span>',
									'width' => '',
									),
'author' => array(
									'title' => '作者',
									'pic' => '',
									'time' => '',
									'exper' => '{$v[\'author\']}',
									'width' => '',
									),
'columnid' => array(
									'title' => '栏目',
									'pic' => '',
									'time' => '',
									'exper' => '{$v[\'columnid\']}',
									'width' => '',
									),
'user_name' => array(
									'title' => '操作员',
									'pic' => '',
									'time' => '',
									'exper' => '{$v[\'user_name\']}',
									'width' => '',
									),
'create_time_show' => array(
									'title' => '创建时间',
									'pic' => '',
									'time' => '',
									'exper' => '{$v[\'create_time_show\']}',
									'width' => '',
									),
'update_time_show' => array(
									'title' => '更新时间',
									'pic' => '',
									'time' => '',
									'exper' => '{$v[\'update_time_show\']}',
									'width' => '',
									),
);

				$this->tpl->addVar('list_fields', $list_fields);
				$_relate_module = array(0=> ' 无 ',);$relate_menu = array();
				$this->tpl->addHeaderCode('  <script type="text/javascript">
//<![CDATA[
var gBatchAction = new Array();gBatchAction[\'recommend\'] = "./run.php?mid=58&a=recommend";gBatchAction[\'delete\'] = "./run.php?mid=58&a=delete";gBatchAction[\'top\'] = "./run.php?mid=58&a=top";gBatchAction[\'audit\'] = "./run.php?mid=58&a=audit";gBatchAction[\'form_outerlink\'] = "./run.php?mid=58&a=form_outerlink";gBatchAction[\'upload_indexpic\'] = "./run.php?mid=58&a=upload_indexpic";gBatchAction[\'move_form\'] = "./run.php?mid=58&a=move_form";
//]]>
  </script>
');
				$this->tpl->setSoftVar('news'); //设置软件界面
				$this->tpl->addVar('list', $datas);
				$this->tpl->addVar('module_id', 58);
				$this->tpl->addVar('_cur_module_name', '文稿');
				$this->tpl->addVar('_m_menu_pos', 0);
				$this->tpl->addVar('relate_module_id', 0);
				$this->tpl->addVar('_relate_module', $_relate_module);
				$this->tpl->addVar('relate_menu', $relate_menu);
				$this->tpl->addVar('primary_key', 'id');
			
					$this->tpl->setTemplateVersion(''); 
					$this->tpl->setScriptDir(''); 
				
			$this->tpl->outTemplate('list');
				?>
			