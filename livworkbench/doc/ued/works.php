<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>前端工作记录</title>
<link href="reset.css" type="text/css" rel="stylesheet" />
<link href="works.css" type="text/css" rel="stylesheet" />
</head>
<?php 
$modules = array( 							//应用模块
	'supermarket' => '商家超市',
	'epaper' => '电子报',
    'EMS' => '企业管理系统',
	'editor' => '编辑器',
	'mediasever' => '转码',
	'news' => '文稿',
	'magazine' => '杂志',
	'boke' => '播客',
	'mobile' => '移动App',
	'publishcontent' => '发布库',
	'cheapbuy' => '惠购',
	'lbs' => 'LBS信息',
	'program' => '节目单',
	'vote' => '投票',
	'gather' => '采集',
	'access' => '统计',
	'special' => '专题',
	'verifycode' => '验证码',
	'mood' => '心情顶踩',
	'survey' => '调查问卷',
	'applant' => '企业平台',
	'subway' => '地铁',
	'other' => '其他',
); 											
$workrecord = array( 						//应用模块详细信息记录
	'supermarket' => array(
		0 => array( 
			'child_module' => '商超列表',
			'module_file' =>  'market_list.php',
			'js_file' => 'market_list.js',
			'work_people' => '胡金霞',
			'work_descr' => '',
			'create_time' => '2013-09-06',
			'work_time' => '2013-11-07'
		),
		1 => array(
			'child_module' => '门店信息',
			'module_file' =>  'market_store_list.php',
			'js_file' => 'store_list.js',
			'work_people' => '胡金霞',
			'create_time' => '2013-09-06',
			'work_time' => '2013-09-06'
		),
		2 => array(
			'child_module' => '消息通知',
			'module_file' =>  'market_message_list.php',
			'js_file' => 'market_message.js',
			'work_people' => '胡金霞',
			'create_time' => '2013-09-06',
			'work_time' => '2013-09-06'
		),
		3 => array(
			'child_module' => '特惠活动',
			'module_file' =>  'special_offer_activity_list.php',
			'js_file' => 'special_offer_activity.js',
			'work_people' => '胡金霞',
			'create_time' => '2013-09-06',
			'work_time' => '2013-09-06'
		),
		4 => array(
			'child_module' => '特惠商品',
			'module_file' =>  'special_offer_product_list.php',
			'js_file' => 'special_commodity.js',
			'work_people' => '胡金霞',
			'create_time' => '2013-09-11',
			'work_time' => '2013-09-11'
		),
		5 => array(
			'child_module' => '会员管理',
			'module_file' =>  'market_member_list.php',
			'js_file' => 'market_member.js',
			'work_people' => '胡金霞',
			'work_descr' => '搜索会员添加红色标记',
			'create_time' => '2013-09-11',
			'work_time' => '2013-11-30'
		)
	 ),
	'editor' => array(
	   0 => array(
	   		'child_module' => '图片管理',
			'module_file' =>  '无',
			'js_file' => 'imgmanage.js',
			'work_people' => '胡金霞',
			'work_descr' => '添加图片',
			'create_time' => '2013-09-10',
			'work_time' => '2013-09-14'
	   ),
	   1 => array(
	   		'child_module' => '附件管理',
			'module_file' =>  '无',
			'js_file' => 'attach.js',
			'work_people' => '胡金霞',
			'work_descr' => '添加附件',
			'create_time' => '2013-09-10',
			'work_time' => '2013-09-13'
	   ),
	   2 => array(
	   		'child_module' => '水印设置',
			'module_file' =>  '无',
			'js_file' => 'water.js',
			'work_people' => '胡金霞',
			'work_descr' => '添加水印',
			'create_time' => '2013-09-12',
			'work_time' => '2013-09-14'
	   ),
	   3 => array(
	   		'child_module' => '应用素材',
			'module_file' =>  '无',
			'js_file' => 'refer.js',
			'work_people' => '胡金霞',
			'work_descr' => '添加素材',
			'create_time' => '2013-09-12',
			'work_time' => '2013-09-18'
	   ),
	   4 => array(
	   		'child_module' => '图片属性',
			'module_file' =>  '无',
			'js_file' => 'imginfo.js',
			'work_people' => '胡金霞',
			'work_descr' => '设置图片属性',
			'create_time' => '2013-09-26',
			'work_time' => '2013-09-29'
	   )
	),
	'program' => array(
		0 => array(
			'child_module' => '节目单设置页',
			'module_file' =>  'program_list_day.php',
			'js_file' => 'program_day.js',
			'work_people' => '胡金霞',
			'work_descr' => '发起请求方式',
	    	'create_time' => '2013-10-22',
			'work_time' => '2013-11-17'
		),
		1 => array(
			'child_module' => '节目模板列表页',
			'module_file' =>  'template_list.php',
			'js_file' => 'program_template.js',
			'work_people' => '胡金霞',
			'work_descr' => '节目模板列表',
	    	'create_time' => '2013-10-31',
			'work_time' => '2013-11-04'
		),
		2 => array(
			'child_module' => '新增节目模板',
			'module_file' =>  'template_create.php',
			'js_file' => 'program_template.js',
			'work_people' => '胡金霞',
			'work_descr' => '设置保存后的调整页面',
	    	'create_time' => '2013-10-31',
			'work_time' => '2013-11-04'
		),
		3 => array(
			'child_module' => '节目库列表页',
			'module_file' =>  'program_library_list.php',
			'js_file' => 'program_library.js',
			'work_people' => '胡金霞',
			'work_descr' => '节目播放时期',
	    	'create_time' => '2013-11-06',
			'work_time' => '2013-11-12'
		),
	),
	'epaper' => array(
	    0 => array(
			'child_module' => '电子报列表页',
			'module_file' =>  'epaper_list.php',
			'js_file' => 'epaper_add_list.js',
			'work_people' => '乔学武',
	    	'create_time' => '2013-09-06',
			'work_time' => '2013-09-06'
		),
		1 => array(
			'child_module' => '往期列表页',
			'module_file' =>  'previous_list.php',
			'js_file' => 'epaper_list.js',
			'work_people' => '乔学武',
		    'work_descr' => '名称框隐藏域',
			'create_time' => '2013-09-06',
			'work_time' => '2013-09-30'
		),
		2 => array( 
			'child_module' => '新增一期',
			'module_file' =>  'add_form.php',
			'js_file' => 'epaper.js',
			'work_people' => '陈梦洁',
			'work_descr' => '自定义版面信息',
			'create_time' => '2013-09-06',
			'work_time' => '2013-09-29'
		),
		3 => array( 
			'child_module' => '新闻编辑',
			'module_file' =>  'news_edit.php',
			'js_file' => 'epaper_form.js',
			'work_people' => '陈梦洁',
			'create_time' => '2013-09-06',
			'work_time' => '2013-09-12'
		),
		4 => array( 
			'child_module' => '链接编辑',
			'module_file' =>  'edit_link.php',
			'js_file' => 'edit_link.js',
			'work_people' => '陈梦洁',
			'work_descr' => '热区新闻对应显示',
			'create_time' => '2013-09-06',
			'work_time' => '2013-09-29'
		),
		5 => array( 
			'child_module' => '新增一期',
			'module_file' =>  'add_form.php',
			'js_file' => 'epaper.js',
			'work_people' => '乔学武',
			'work_descr' => '保存期刊前的时间判断',
			'create_time' => '2013-09-23',
			'work_time' => '2013-09-23'
		)
		
		
	 ),
	 'EMS' => array(
	     0 => array(
	        'child_module' => '新增专题',
			'module_file' =>  'special.php',
			'js_file' => 'special_from.js',
			'work_people' => '乔学武',
	     	'create_time' => '2013-09-06',
			'work_time' => '2013-08-16'
	     ),
	     1 => array(
	        'child_module' => '专题内容页',
			'module_file' =>  'special_content_list.php',
			'js_file' => 'special_content_list.js',
			'work_people' => '乔学武',
	     	'create_time' => '2013-09-06',
			'work_time' => '2013-09-06'
	     ),
	     2 => array(
	        'child_module' => '注册页',
			'module_file' =>  'special.php',
			'js_file' => 'special.php',
			'work_people' => '乔学武',
	     	'create_time' => '2013-09-06',
			'work_time' => '2013-09-06'
	     )
	 ), 
	  'mediasever' => array(
	 	0 => array(
			'child_module' => 'ftp大文件提交',
			'module_file' =>  'ftp_video_list.php',
			'js_file' => 'ftp_video_list.php',
			'work_people' => '乔学武',
			'work_descr' => '增加弹框提醒,上传中文件样式',
	     	'create_time' => '2013-09-25',
			'work_time' => '2013-09-30'
		)
	 ),
	 'news' => array(
	 	0 => array(
			'child_module' => '分享',
			'module_file' =>  'share_from.php',
			'js_file' => 'share_from.php',
			'work_people' => '乔学武',
			'work_descr' => '增加下拉框',
	     	'create_time' => '2013-09-23',
			'work_time' => '2013-09-23'
		)
	 ),
	 'magazine' => array(
	 	0 => array(
			'child_module' => '杂志列表页',
			'module_file' =>  'magazine_list.php',
			'js_file' => 'magazine-list.js',
			'work_people' => '胡金霞',
			'work_descr' => '权限错误显示问题',
	     	'create_time' => '2013-10-08',
			'work_time' => '2013-10-23'
		),
		1 => array(
			'child_module' => '期刊列表页',
			'module_file' =>  'issue_list.php',
			'js_file' => 'magzine-add.js',
			'work_people' => '胡金霞',
			'work_descr' => '权限错误显示问题',
	     	'create_time' => '2013-10-08',
			'work_time' => '2013-10-23'
		),
		2 => array(
			'child_module' => '文章列表页',
			'module_file' =>  'article_list.php',
			'js_file' => 'article-list.js',
			'work_people' => '胡金霞',
			'work_descr' => '添加排序功能',
	     	'create_time' => '2013-10-08',
			'work_time' => '2013-11-05'
		),
		3 => array(
			'child_module' => '文章表单页',
			'module_file' =>  'add_article.php',
			'js_file' => 'common/editor_common.js',
			'work_people' => '胡金霞',
			'work_descr' => '权限错误显示问题',
	     	'create_time' => '2013-10-08',
			'work_time' => '2013-10-23'
		),
		4 => array(
			'child_module' => '最新期刊页',
			'module_file' =>  'show_lastest_issue.php',
			'js_file' => 'lastest-list.js',
			'work_people' => '胡金霞',
			'work_descr' => '分页优化',
	     	'create_time' => '2013-10-08',
			'work_time' => '2013-12-11'
		),
	 ),
	 'other' =>array(
	 	0 => array(
			'child_module' => '编目',
			'module_file' =>  'catalog.js',
			'js_file' => 'catalog.js',
			'work_people' => '胡金霞',
			'work_descr' => '编目增加必填项控制',
	     	'create_time' => '2013-10-12',
			'work_time' => '2013-12-25'
		),
		1 => array(
			'child_module' => '内容选择器',
			'module_file' =>  '',
			'js_file' => 'pop_list.js',
			'work_people' => '陈梦洁',
			'work_descr' => '内容选择弹窗',
	     	'create_time' => '2013-10-18',
			'work_time' => '2013-10-18'
		),
		2 => array(
			'child_module' => '安徽卫视直播',
			'module_file' =>  '模板/样式',
			'js_file' => '模板',
			'work_people' => '陈梦洁',
			'work_descr' => '',
	     	'create_time' => '2013-10-25',
			'work_time' => '2013-10-25'
		),
		3 => array(
			'child_module' => '快编完善',
			'module_file' =>  'vod_fast_editor.php',
			'js_file' => 'fast_dui.js/fast.js',
			'work_people' => '胡金霞',
			'work_descr' => '另存为增加提交字段',
	     	'create_time' => '2013-12-19',
			'work_time' => '2013-12-20'
		),
		4 => array(
			'child_module' => '拆条完善',
			'module_file' =>  'vod_show_list.php',
			'js_file' => 'split.js/split_pian.js/split_tiao.js/sort_video.js/publish_video.js',
			'work_people' => '胡金霞',
			'work_descr' => '拆条信息完善',
	     	'create_time' => '2013-12-19',
			'work_time' => '2013-12-26'
		),
		5 => array(
			'child_module' => '权限 模板应用改版',
			'module_file' =>  'admin_role_form.php',
			'js_file' => 'base_pop.js/column_select.js/auth_publishsys.js',
			'work_people' => '胡金霞',
			'work_descr' => '部署和生成发布 模板应用改版',
	     	'create_time' => '2014-7-15',
			'work_time' => '2014-10-24'
		)
	 ),
	 'boke' =>array(
	    0 => array(
			'child_module' => '视频详情页',
			'module_file' =>  'front_play_left.php',
			'js_file' => 'front_play_left.php',
			'work_people' => '乔学武',
			'work_descr' => '点赞,点踩,收藏,下载,发表评论交互',
	     	'create_time' => '2013',
			'work_time' => '2013-10-24'
		),
		1 => array(
			'child_module' => '注册页',
			'module_file' =>  'admin_reg.php',
			'js_file' => 'admin_reg.php',
			'work_people' => '乔学武',
			'work_descr' => '注册功能',
	     	'create_time' => '2013',
			'work_time' => '2013-10-24'
		),
		2 => array(
			'child_module' => '头部',
			'module_file' =>  'header.php',
			'js_file' => 'header.php',
			'work_people' => '乔学武',
			'work_descr' => '下拉框的功能,上传视频前的判断',
	     	'create_time' => '2013',
			'work_time' => '2013-10-24'
		),
	),	
	'lbs' =>array(
	    0 => array(
			'child_module' => 'lbs列表页',
			'module_file' =>  'lbs_list.php',
			'js_file' => 'lbs_list.php',
			'work_people' => '乔学武',
			'work_descr' => '列表页排序功能',
	     	'create_time' => '2013-11-19',
			'work_time' => '2013-11-28'
		),
		1 => array(
			'child_module' => 'lbs表单页',
			'module_file' =>  'lbs_form.php',
			'js_file' => 'lbs_form.js',
			'work_people' => '胡金霞',
			'work_descr' => '保存信息点时增加判断',
	     	'create_time' => '2013-11-19',
			'work_time' => '2013-12-16'
		),
		2 => array(
			'child_module' => 'lbs附加信息配置列表页',
			'module_file' =>  'lbs_field_list.php',
			'js_file' => 'lbs_field_list.php',
			'work_people' => '胡金霞',
			'work_descr' => '启用滑动按钮',
	     	'create_time' => '2013-11-21',
			'work_time' => '2013-11-22'
		),
		3 => array(
			'child_module' => 'lbs附加信息配置表单页',
			'module_file' =>  'lbs_field_form.php',
			'js_file' => 'lbs/lbs_field_form.js',
			'work_people' => '乔学武',
			'work_descr' => '增加表单验证 标识正则判断 类型选择',
	     	'create_time' => '2013-11-21',
			'work_time' => '2013-12-07'
		),
	),	
	'mobile' => array(
		0 => array(
			'child_module' => '配置/详情',
			'module_file' =>  'client_edit_info.php',
			'js_file' => 'mobile_client_list.php',
			'work_people' => '陈梦洁',
			'work_descr' => '安装信息',
	     	'create_time' => '2013-09-30',
			'work_time' => '2013-09-30'
		),
		1 => array(
			'child_module' => '添加消息',
			'module_file' =>  'detail_push_mes.php',
			'js_file' => 'char_count',
			'work_people' => '陈梦洁',
			'work_descr' => '输入字符长度实时检测',
	     	'create_time' => '2013-10-10',
			'work_time' => '2013-10-10'
		),
		2 => array(
			'child_module' => '添加消息',
			'module_file' =>  'detail_push_mes.php',
			'js_file' => 'pop/popList',
			'work_people' => '陈梦洁',
			'work_descr' => '选择弹窗',
	     	'create_time' => '2013-10-20',
			'work_time' => '2013-10-23'
		),
		3 => array(
			'child_module' => '配置/接口分类',
			'module_file' =>  'unit/sort_list.php',
			'js_file' => 'mobile/importing',
			'work_people' => '陈梦洁',
			'work_descr' => '导入文件',
	     	'create_time' => '2013-10-25',
			'work_time' => '2013-10-25'
		),
	),
	'vote' => array(
		0 => array(
			'child_module' => '投票列表页',
			'module_file' =>  'vote_question_list.php',
			'js_file' => 'vote_list.js',
			'work_people' => '乔学武',
			'work_descr' => '结构调整，增加开启/关闭按钮',
	     	'create_time' => '2013-11-15',
			'work_time' => '2013-11-19'
		),
		1 => array(
			'child_module' => '投票编辑页',
			'module_file' =>  'vote_question_form.php',
			'js_file' => 'vote_add.js',
			'work_people' => '乔学武',
			'work_descr' => '增加验证码功能',
	     	'create_time' => '2013-11-15',
			'work_time' => '2013-12-02'
		),
		2 => array(
			'child_module' => '投票详情展示页',
			'module_file' =>  'question_option_list.php',
			'js_file' => '',
			'work_people' => '胡金霞',
			'work_descr' => '',
	     	'create_time' => '2013-11-15',
			'work_time' => '2013-11-15'
		),
		3 => array(
			'child_module' => '投票详情页',
			'module_file' =>  'vote_result.php',
			'js_file' => '',
			'work_people' => '乔学武',
			'work_descr' => '新增查看功能',
	     	'create_time' => '2013-12-02',
			'work_time' => '2013-12-02'
		),
	),
	'gather' => array(
		0 => array(
			'child_module' => '采集列表页',
			'module_file' =>  'gather_list.php',
			'js_file' => 'gather_list.js',
			'work_people' => '胡金霞',
			'work_descr' => '采集全部数据显示问题',
	     	'create_time' => '2013-12-11',
			'work_time' => '2013-12-25'
		),
		1 => array(
			'child_module' => '采集表单页',
			'module_file' =>  'gather_form.php',
			'js_file' => 'gather_form.php',
			'work_people' => '胡金霞',
			'work_descr' => '采集改版',
	     	'create_time' => '2013-12-11',
			'work_time' => '2013-12-18'
		),
	),
	'publishcontent' => array(
		0 => array(
			'child_module' => '发布库首页',
			'module_file' =>  'content.php',
			'js_file' => 'nav.js',
			'work_people' => '陈梦洁',
			'work_descr' => '改版',
	     	'create_time' => '2013-11-15',
			'work_time' => '2013-11-15'
		),
	),
	'cheapbuy' => array(
		0 => array(
			'child_module' => '商品列表页',
			'module_file' => 'product_list.php',
			'js_file' => 'product_list.js',
			'work_people' => '陈梦洁',
			'work_descr' => '',
	     	'create_time' => '2013-11-15',
			'work_time' => '2013-11-22'
		),
		1 => array(
			'child_module' => '商品详情页',
			'module_file' => 'product_form.php',
			'js_file' => 'product_form.js',
			'work_people' => '陈梦洁',
			'work_descr' => '',
	     	'create_time' => '2013-11-15',
			'work_time' => '2013-11-22'
		),
		2 => array(
			'child_module' => '订单列表页',
			'module_file' => 'order_list.php',
			'js_file' => 'order_list.js',
			'work_people' => '陈梦洁',
			'work_descr' => '',
	     	'create_time' => '2013-11-15',
			'work_time' => '2013-11-22'
		),
	),
	'access' => array(
		0 => array( 
			'child_module' => '统计列表',
			'module_file' =>  'access_list.php',
			'js_file' => 'access.js/access_set.js/hoge_storage.js',
			'work_people' => '胡金霞',
			'work_descr' => '代码优化',
			'create_time' => '2014-01-02',
			'work_time' => '2014-01-26'
		),
	 ),
	 'special' => array(
	   0 => array(
	   		'child_module' => '专题模板',
			'module_file' =>  'fast_special_form.php',
			'js_file' => 'special/special_tmpl.js',
			'work_people' => '乔学武',
			'work_descr' => '模块',
			'create_time' => '2014-01-26',
			'work_time' => '2014-01-26'
	   ),
	),
	 'verifycode' => array(
	   0 => array(
	   		'child_module' => '验证码',
			'module_file' =>  'verifycode',
			'js_file' => 'verifycode',
			'work_people' => '乔学武',
			'work_descr' => '模块',
			'create_time' => '2014-01-26',
			'work_time' => '2014-01-26'
	   ),
	),
	'mood' => array(
	   0 => array(
	   		'child_module' => '心情顶踩',
			'module_file' =>  'mood',
			'js_file' => 'mood',
			'work_people' => '乔学武',
			'work_descr' => '模块',
			'create_time' => '2014-01-26',
			'work_time' => '2014-01-26'
	   ),
	),
	'survey' => array(
	   0 => array(
	   		'child_module' => '调查问卷',
			'module_file' =>  'survey',
			'js_file' => 'survey',
			'work_people' => '乔学武',
			'work_descr' => '模块',
			'create_time' => '2014-01-26',
			'work_time' => '2014-01-26'
	   ),
	),
	'applant' => array(
		0 => array(
	   		'child_module' => '应用设置',
			'module_file' =>  'app_editor',
			'js_file' => 'webapp/setting_ctrl.js',
			'work_people' => '陈梦洁',
			'work_descr' => '属性设置',
			'create_time' => '2014-01-26',
			'work_time' => '2014-01-26'
	   ),
	   1 => array(
	   		'child_module' => '应用设置',
			'module_file' =>  'lib/webapp目录下',
			'js_file' => '在各自页面里',
			'work_people' => '陈梦洁',
			'work_descr' => 'iframe预览',
			'create_time' => '2014-01-26',
			'work_time' => '2014-01-26'
	   ),
	),
	'subway' => array(
		0 => array(
	   		'child_module' => '地铁表单页',
			'module_file' =>  'subway_form',
			'js_file' => 'subway.js/subway_tab.js/subway_set.js',
			'work_people' => '胡金霞',
			'work_descr' => '特殊字符校验',
			'create_time' => '2014-03-28',
			'work_time' => '2014-03-05'
	   ),
	   1 => array(
	   		'child_module' => '地铁服务表单页',
			'module_file' =>  'subway_service_list',
			'js_file' => '',
			'work_people' => '胡金霞',
			'work_descr' => '发布功能',
			'create_time' => '2014-04-03',
			'work_time' => '2014-03-28'
	   )
	),
 );
?>
<body>
<div class="main">
<div class="list-head m2o-flex m2o-flex-center">
	<div class="module">应用模块</div>
	<div class="info-box m2o-flex m2o-flex-center">
		<div class="child-module">子模块</div>
		<div class="module-file">模板文件</div>
		<div class="js-file">js文件</div>
		<div class="work-descr">描述</div>
		<div class="work-people">维护人</div>
		<div class="create-time">创建时间</div>
		<div class="work-time">维护时间</div>
	</div>
</div>
<?php
	foreach( $modules as $k => $v ){
		echo "<div class='list-item  m2o-flex m2o-flex-center'>";
			echo "<div class='module'>$k<br/>$v</div>";
			echo "<ul class='info-box'>";
			if( $workrecord[$k] )
			{
				foreach( $workrecord[$k] as $kk => $vv ){
					echo "<li class='info-record m2o-flex m2o-flex-center'>";
						echo "<div class='child-module'>" .$vv['child_module']."</div>";
						echo "<div class='module-file'>" .$vv['module_file']."</div>";
						echo "<div class='js-file'>" .$k. '/'.  $vv['js_file']. "</div>";
						echo "<div class='work-descr'>" . $vv['work_descr']. "</div>";
						echo "<div class='work-people'>" . $vv['work_people']. "</div>";
						echo "<div class='create-time'>" .$vv['create_time'] ."</div>";
						echo "<div class='work-time'>" .$vv['work_time'] ."</div>";
					echo "</li>";
				}
			}
			echo "</ul>";
		echo "</div>";
	}
?>
</div>
<div style="position:fixed;background:#fff;height:38px;right:0;top:0;width:375px;border:1px dashed #ccc;">
	<a class="fast-style" href="http://localhost/livworkbench/index.php" target="_blank">本地m2o</a>
	<a class="fast-style" style="right:140px"  href="http://10.0.1.40/livworkbench/index.php" target="_blank">40m2o</a>
	<a class="fast-style" style="right:220px;color:red;" href="week.php">本周工作计划及进度</a>
</div>
</body>
</html>