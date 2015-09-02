<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:ad_style}
{css:ext-all}
{js:bootstrap}
{code}
  $op_info_str   = json_encode($op_info);
  $store_design  = json_encode($app_design);
{/code}
<script type="text/javascript">
Ext.onReady(function(){
	var mid = '{$id}';
	var op_info_arr = eval('{$op_info_str}');
	var tab_item = [];
	var tree_children = [];
	for(var i = 0;i<op_info_arr.length;i++)
	{
		var fn_body  = "<div style='margin-left:20px;margin-top:10px;'><div style='font-size:16px;'>function <span style='color:green;font-size:16px;'>"+op_info_arr[i].op+"</span>()<span style='color:green;margin-left:30px;'>/*这是("+op_info_arr[i].name+")的方法*/</span></div>";
			fn_body += "<div>{</div>";
			fn_body += "<div style='margin-left:20px;font-size:16px;margin-top:6px;'>$this->curl->setSubmitType('post');</div>";
			fn_body += "<div style='margin-left:20px;font-size:16px;margin-top:6px;'>$this->curl->setReturnFormat('json');</div>";
			fn_body += "<div style='margin-left:20px;font-size:16px;margin-top:6px;'>$this->curl->initPostData();</div>";
			fn_body += "<div style='margin-left:20px;font-size:16px;margin-top:6px;'>$this->curl->addRequestData('a', 'getMaterialById');</div>";
			fn_body += "<div style='margin-left:20px;font-size:16px;margin-top:6px;'>$this->curl->addRequestData('cid', $cid);</div>";
			fn_body += "<div style='margin-left:20px;font-size:16px;margin-top:6px;'>$ret = $this->curl->request('material.php');</div>";
			fn_body += "<div style='margin-left:20px;font-size:16px;margin-top:6px;'>return $ret[0];</div>";
			fn_body += "<div>}</div></div>";
		var htmlContent  = "<div style='width:782px;height:240px;border:1px solid #d9e5f3;margin-top:30px;margin-left:30px;background:#eeeeee;overflow:auto;'>"+fn_body+"</div>";
		tab_item.push({title:op_info_arr[i].name,html:htmlContent,closable:true});
		tree_children.push({text:op_info_arr[i].op,leaf: true});
	}
	
	/*创建一个tabpanel*/
	 var tabPanel = Ext.create('Ext.tab.Panel', {
	     height:350,
	     deferredRender: false,
	     activeTab: 0,
	     enableTabScroll: true,
	     tabPosition:'bottom',
		 tbar:[{text:'创建方法',handler:hg_createModuleFn,id:'create_fun',icon:RESOURCE_URL+'add.png'}],
		 items:tab_item,
	 });

	/*左栏的树形结构*/
	 var tree = Ext.create('Ext.tree.Panel', {
	        title: "{$module_info['name']}",
	        root: {
		            text: "{$module_info['api_file']}",
		            expanded: true,
		            children: tree_children
	        		}
	    });

	/***********************************************存储设计表格开始******************************************/
	var store_design = eval('{$store_design}');
	var storage_info = [];
	if(store_design.length)
	{
		for(var i = 0;i<store_design.length;i++)
		{
			storage_info.push([store_design[i].bundle_id,store_design[i].name,store_design[i].desciption,store_design[i].type_length,store_design[i].data_source,store_design[i].data_type,parseInt(store_design[i].is_primary),parseInt(store_design[i].is_index)]);
		}
	}
	else
	{
		/*基本信息的存储信息*/
		storage_info = [
							['id','id','这是id','INT','来源1','字段',1,1],
							['create_time','创建时间','这是创建时间','INT','来源1','字段',0,0],
							['update_time','更新时间','这是更新时间','INT','来源1','字段',0,0],
							['ip','ip地址','这是ip地址','VARCHAR','来源1','字段',0,0],
						];
	}
	
	var storage_grid = Ext.create('Ext.grid.Panel',{
							frame:true,
							width:788,
							tbar:[{text:'保存',icon:RESOURCE_URL+'book.png',handler:saveGridData}],
							viewConfig:{
								forceFit:true,
								stripeRows:true,
							},
							store:{
								fields:['bundle_id','name','desciption','type_length','data_source','data_type','is_primary','is_index'],
								proxy:{
									type:'memory',
									data:storage_info,
									reader:'array',
								},
								autoLoad:true
							},
							plugins:[
										Ext.create('Ext.grid.plugin.CellEditing',{
											clicksToEdit:1
										})
									],
							selType:'cellmodel',
							columns:[
										 Ext.create('Ext.grid.RowNumberer',{text:'行号',width:35}),
								         {header:'标识',width:100,dataIndex:'bundle_id',
												 editor:{
													xtype:'textfield',
													allowBlank:false
											    }
										 },
								         {header:'名称',width:100,dataIndex:'name',
												editor:{
															xtype:'textfield',
															allowBlank:false
													    }
									     },
								         {header:'描述',width:100,dataIndex:'desciption',
									        	 editor:{
													xtype:'textfield',
													allowBlank:true
											    }
									     },
								         {header:'类型长度',width:100,dataIndex:'type_length',
											 editor: {
														xtype: 'combobox',
														typeAhead: true,
														triggerAction: 'all',
														selectOnTab: true,
														store: [
															['VARCHAR','VARCHAR'],
															['TEXT','TEXT'],
															['INT','INT'],
															['DATE','DATE'],
															['TINYINT','TINYINT'],
														],
														lazyRender: true,
													}
										 },
								         {header:'数据来源',width:100,dataIndex:'data_source',
										    editor: {
														xtype: 'combobox',
														typeAhead: true,
														triggerAction: 'all',
														selectOnTab: true,
														store: [
															['来源1','来源1'],
															['来源2','来源2'],
															['来源3','来源3'],
															['来源4','来源4'],
														],
														lazyRender: true,
													}
										 },
								         {header:'数据类型',width:100,dataIndex:'data_type',
											 editor: {
														xtype: 'combobox',
														typeAhead: true,
														triggerAction: 'all',
														selectOnTab: true,
														store: [
															['字段','字段'],
															['扩展字段','扩展字段'],
															['附属信息','附属信息'],
														],
														lazyRender: true,
													}
										 },
								         {header:'主键',width:40,dataIndex:'is_primary',renderer:formatPrimary},
								         {header:'索引',width:40,dataIndex:'is_index',renderer:formatIndex},
								         {header:'操作',width:60,
									      xtype:'actioncolumn',
									      items:[
											      {
													   icon:RESOURCE_URL+'delete.gif',
													   handler:function(grid,rowIndex,colIndex){
															var rec = grid.getStore().getAt(rowIndex);
													        grid.getStore().remove(rec);
											   	   	   }
										      	   },
										      	   {
										      		   icon:RESOURCE_URL+'add.png',
													   handler:function(grid,rowIndex,colIndex,view){
															

															  var rec = {
																			bundle_id:'',
																			name:'',
																			desciption:'',
																			type_length:'VARCHAR',
																			data_source:'来源1',
																			data_type:'字段',
																			is_primary:0,
																			is_index:0  
																		};
												      	      grid.getStore().add(rec);
											   	   	   }
											       }
											     ]
									     },
									]
					});


	function formatIndex(value)
	{
		if(value)
		{
			return "<input type='checkbox' checked='checked' name='index[]' />";
		}
		else
		{
			return "<input type='checkbox' name='index[]' />";
		}
	}

	function formatPrimary(value)
	{
		if(value)
		{
			return "<input type='radio' checked='checked' name='primary' />";
		}
		else
		{
			return "<input type='radio'  name='primary' />";
		}
	}

	function saveGridData()
	{
		Ext.Msg.confirm('提示','您确定保存吗?',save_callback);
	}

	function save_callback(id)
	{
		if(id == 'yes')
		{
			var store = storage_grid.getStore();/*获取总的数据*/
			
			var bundle_id = new Array();
			var name = new Array();
			var desciption = new Array();
			var type_length = new Array();
			var data_source = new Array();
			var data_type = new Array();
			var is_primary = new Array();
			var is_index = new Array();
			store.each(function(rec){
				bundle_id.push(rec.get('bundle_id'));
				name.push(rec.get('name'));
				desciption.push(rec.get('desciption'));
				type_length.push(rec.get('type_length'));
				data_source.push(rec.get('data_source'));
				data_type.push(rec.get('data_type'));
			});

			$('input[name="index[]"]').each(function(){
					if($(this).attr('checked'))
					{
						is_index.push(1);
					}
					else
					{
						is_index.push(0);
					}
			});

			$('input[name="primary"]').each(function(){
					if($(this).attr('checked'))
					{
						is_primary.push(1);
					}
					else
					{
						is_primary.push(0);
					}
			});
			var url = 'modules.php?a=saveStorage&id='+mid+'&name='+name.join(',')+'&bundle_id='+bundle_id.join(',')+'&desciption='+desciption.join(',')+'&type_length='+type_length.join(',')+'&data_source='+data_source.join(',')+'&data_type='+data_type.join(',')+'&is_primary='+is_primary.join(',')+'&is_index='+is_index.join(',');
			hg_ajax_post(url);
		}
	}

	/***********************************************存储设计表格结束******************************************/	


	/*ui设计里面的编辑器*/
	
	var defaultHTML = "此处是html源码";
	
	var htmlEdit = Ext.create('Ext.form.Panel',{
						frame:true,
						items:[
								{
									xtype:'htmleditor',
									height:320,
									width:868,
									value:defaultHTML,
									labelWidth:70,
									labelSeparator:':',
									createLinkText:'创建超链接',
									defaultLinkValue:'http://www.hogesoft.com',
									enableAlignments:true,
									enableColors:true,
									enableFont:true,
									enableFontSize:true,
									enableFormat:true,
									enableLinks:true,
									enableLists:true,
									enableSourceEdit:true,
								}
							   ]
					});

	/*右边栏的组件的gird*/
	var right_data = [['搜索'],['排序'],['批量']];
	var right_grid = Ext.create('Ext.grid.Panel',{
							frame:true,
							viewConfig:{
								forceFit:true,
								stripeRows:true,
							},
							store:{
								fields:['name'],
								proxy:{
									type:'memory',
									data:right_data,
									reader:'array',
								},
								autoLoad:true
							},
							columns:[
								         {header:'名称',width:75,dataIndex:'name'},
								         {header:'操作',width:75,
									      xtype:'actioncolumn',
									      items:[
										      	   {
										      		   icon:RESOURCE_URL+'add.png',
													   handler:function(grid,rowIndex,colIndex){
												      		 var rec = grid.getStore().getAt(rowIndex);
															 alert('添加'+rec.get('name'));
											   	   	   }
											       }
											     ]
									     },
									]
						});

/*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>布局部分开始>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/

	new Ext.Panel({
			renderTo:"app_design_content",
			width : 1160,
	        height: 800,
			items:{
		        xtype: 'panel',
		        width : 1160,
		        height: 800,
		        title:"{$module_info['app_name']}应用",
		        layout: 'border',
		        collapsible: false,
		        defaults: {
		            collapsible: true,
		            split      : true
		        },
		        items: [
/*****************************************************子级部分开始**********************************************************/
				        	/*底部的框子*/
			                {
			                    region: 'south',
			                    contentEl: 'south',
			                    split: true,
			                    height: 150,
			                    minSize: 100,
			                    maxSize: 200,
			                    collapsible: true,
			                    collapsed: true,
			                    title: '属性',
			                    margins: '0 0 0 0'
			                }, 
			                /*左边的框子*/
				            {
			                	region: 'west',
						        title : '模块',
						        collapsible: true,
						        layout: 'accordion',
						        x: 0, 
						        y: 0,
						        width : 150,
						        bodyStyle: {
						            'background-color': '#eee'
						        },
						        items:[
						                tree, 
						                {
						                    title: '附属信息',
						                    html: '这是附属信息'
						                }, 
						                {
						                    title: '分类',
						                    html : '这是一些分类'
						                }
						              ]
						    },
				            /*右边的框子*/
				            {
				                title  : '组件',
				                region : 'east',
				                margins: '0 5 0 0',
				                width  : 150,
				                items:[right_grid]
				            },
				            /*中间的框子*/
				             Ext.create('Ext.tab.Panel', {
					            	region: 'center',
					                deferredRender: false,
					                activeTab: 0,
					                items: [
						                        {
								                    title: '接口与UI设计',
								                    autoScroll: false,
								                    items:[
															tabPanel,
															{
									                 			title:"UI设计",
									                 			height:400,
									                 		 	items:[htmlEdit]
									        	             }
									             		  ]
								                }, 
						                        {
								                    title: '存储设计',
								                    autoScroll: true,
								                  	items:[storage_grid],
								                }
								                
		               						]
            						})
/*****************************************************子级部分结束**********************************************************/
		       		 ]
		    	}
			
		});
/*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>布局部分结束>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
	
	
/*********************************一些处理事件开始**********************************************************/
	function hg_createModuleFn()
	{
		/*
		var msg = Ext.MessageBox.show({
					title:'创建方法',
					msg:'请输入方法名创建标签',
					modal:true,
					prompt:true,
					icon:Ext.Msg.INFO,
					buttons:Ext.Msg.OKCANCEL,
					fn:hg_create_tab_panel,
					animateTarget: 'create_fun'
		});
		*/

		/*创建方法的表单*/
		var create_fn_form = Ext.create('Ext.form.Panel',{
				url:'1.php',
				frame:true,
				allowBlank:false,
				blankText:'不允许为空',
				defaultType:'textfield',
				filedDefaults:{
					labelSeparator:':',
					labelWidth:80,
					width:270,
					msgTarget:'qtip'
				},
				items:[
					       {name:'fn_name',fieldLabel:'名称',},
					       {name:'fn_op',fieldLabel:'操作名',},
					       {name:'fn_file',fieldLabel:'文件',},
					       {name:'fn_a_name',fieldLabel:'方法名',},
					  ],
				buttons:[{
							text:'确定',
							handler:function(){
								hg_create_tab_panel('ok','哈哈哈');
					       }
						 }
						]
		});
		
		var win = new Ext.Window({
			   title:"创建方法",
	           width:370,
	           height:450,
	           animateTarget: 'create_fun',
	           items:[create_fn_form]
	        });
	   win.show();

	}

	function hg_create_tab_panel(id,msg)
	{
		if(id == 'ok' && msg)
		{
			var tabPage = tabPanel.add({
					title:msg,
					closable:true,
				});
			tabPanel.setActiveTab(tabPage);
		}
	}

/*********************************一些处理事件结束**********************************************************/

/*********************************数据的处理开始**********************************************************/


});

</script>
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_module first"><em></em><a>模块</a></li>
			<li class=" dq"><em></em><a>应用设计</a></li>
			<li class="last"><span  style="padding:10px 0px 32px 4px;"></span></li>
		</ul>
	</div>
	<div id="hg_parent_page_menu" class="new_menu">
	</div>
</div>
<div class="wrap clear">
	<div class="ad_middle" style="width:100%;height:810px;margin-top:10px;" id="app_design_content"></div>
</div>

<!-- 配置区域开始 -->
<div id="south">此处是一些属性</div>
<!-- 配置区域结束 -->
{template:foot}