<?php
$month = array(
	title => '2014-01月',
	weeks => array(
		firstweek => '2013.12.30 - 2014.01.03',
		secondweek => '2014.01.06 - 2014.01.10',
		threeweek => '2014.01.13 - 2014.01.17',
		fourweek => '2014.01.19 - 2014.01.24',
		fiveweek => '2014.01.27 - 2014.01.31'
	)
);
$weekrecord = array( 						//应用模块详细信息记录
	   
	'firstweek' => array(
		0 => array(
	   		'task' => '统计应用模块',
	   		'target' => '与后端对接口，完成页面动态输出及交互',
	   		'real_plan' => '暂无系统推送数据，搜索条件还没有刷选成功，其他部分ok',
	   		'start_time' => '2013-12-30',
	   		'end_time' => '2013-12-31',
			'people' => '胡金霞',
			'remark' => '无',
	   		//'done' => true		//完成后把done置为true
	   ),
	   1 => array(
	   		'task' => '电子报前台test页',
	   		'target' => '能模拟输出前台的电子报demo页',
	   		'real_plan' => '完成',
	   		'start_time' => '2013-12-30',
	   		'end_time' => '2013-12-30',
			'people' => '陈梦洁',
			'remark' => '无',
	   		'done' => true
	   ),
	   2 => array(
	   		'task' => 'flash点播切播完善',
	   		'target' => '开关灯、宽屏、切播第二天js回调测试',
	   		'real_plan' => '交互已完成，需要搭建平台测试',
	   		'start_time' => '2013-12-30',
	   		'end_time' => '2014-01-02',
			'people' => '陈梦洁',
			'remark' => '无'
	   ),
	   3 => array(
	   		'task' => 'm2o平台组件(验证码模块)',
	   		'target' => '能跑通流程，页面能保存和正常流转',
	   		'real_plan' => '已完成',
	   		'start_time' => '2013-12-30',
	   		'end_time' => '2014-01-04',
			'people' => '乔学武',
			'remark' => '无',
	   		'done' => true
	   ),
	   4 => array(
	   		'task' => '杂志页细节样式调整',
	   		'target' => '杂志文章form页细节样式调整及功能测试',
	   		'real_plan' => '完成',
	   		'start_time' => '2014-01-02',
	   		'end_time' => '2014-01-02',
			'people' => '胡金霞',
			'remark' => '无',
			'done' => true
	   ),
	   5 => array(
	   		'task' => '电子报交互细节调试',
	   		'target' => '修复功能bug以及一些交互体验',
	   		'real_plan' => '已完成',
	   		'start_time' => '2014-01-03',
	   		'end_time' => '2014-01-03',
			'people' => '陈梦洁',
			'remark' => '无',
			'done' => true
	   ),
	   6 => array(
	   		'task' => '颜色选择器插件',
	   		'target' => '颜色选择器插件',
	   		'real_plan' => '完成',
	   		'start_time' => '2014-01-02',
	   		'end_time' => '2014-01-02',
			'people' => '张振',
			'remark' => '无',
	   		'done' => true
	   ),
	   7 => array(
	   		'task' => '专题摘要调整为编辑器',
	   		'target' => '专题摘要改为编辑器,概要内容(居中，换行等都可以编辑)',
	   		'real_plan' => '正在处理',
	   		'start_time' => '2014-01-02',
	   		'end_time' => '2014-01-02',
			'people' => '张振',
			'remark' => '无'
	   ),
	   8 => array(
	   		'task' => 'aplant增加创建webview模块功能',
	   		'target' => '目前只能创建 原生的模块，不利于拓展，而公司客户端目前是原生和web混合的APP；<br /><br />
						1.创建“模块”增加 “原生模块”和“web模块”选择；<br /><br />
						2.选择“原生模块”则跟原先配置逻辑一致；<br /><br />
						3.选择“web模块”显示新的配置内容，配置web的url，url可选可自定义加，但一个模块仅可选/加1个url；
							可选取的web模块，去取40”app工厂“（增加web模块管理列表，主要字段：名称、描述、url）后台配置好的；
							可加，为提供用户输入框，自定义输入url；<br /><br />
						4.webview地址通过接口传递给客户端；（用户可在模块管理中变更url）',
	   		'real_plan' => '前端切换、选择交互已完成，需要后台对接保存',
	   		'start_time' => '2014-01-02',
	   		'end_time' => '',
			'people' => '陈梦洁',
			'remark' => '备忘→投票类型保存那里，前端已改，后台接口要改'
	   )
	 ),
	 'secondweek' => array(
		0 => array(
	   		'task' => '统计应用模块',
	   		'target' => '调整搜索，可以本地存储设置的搜索条件,系统推送设置更新间隔，本地存储组件化',
	   		'real_plan' => '搜索功能已完成，并且可本地存储搜索条件，系统推送时间间隔已完成，还需推送设置的接口数据',
	   		'start_time' => '2014-01-06',
	   		'end_time' => '2014-01-07',
			'people' => '胡金霞',
			'remark' => '无',
	   		//'done' => true		//完成后把done置为true
	   ),
	   1 => array(
	   		'task' => '企业平台app模块添加webview模块',
	   		'target' => '模块既可以用原生也可以已webview方式创建',
	   		'real_plan' => '完成',
	   		'start_time' => '2014-01-06',
	   		'end_time' => '2014-01-06',
			'people' => '陈梦洁',
			'remark' => '无',
	   		'done' => true
	   ),
	   2 => array(
	   		'task' => '企业平台app列表页ajax加载更多',
	   		'target' => 'ajax动态加载更多',
	   		'real_plan' => '完成',
	   		'start_time' => '2014-01-07',
	   		'end_time' => '',
			'people' => '陈梦洁',
			'remark' => '无',
	   		'done' => true
	   ),
	   3 => array(
	   		'task' => '企业平台app图集详情页的切换查看图集',
	   		'target' => '根据检测如果是手机端就用touch事件切换，如果是pc平台就加左右箭头切换',
	   		'real_plan' => '完成',
	   		'start_time' => '2014-01-08',
	   		'end_time' => '',
			'people' => '陈梦洁',
			'remark' => '无',
	   		'done' => true
	   ),
	   4 => array(
	   		'task' => '企业平台app我的收藏功能',
	   		'target' => '本地存储id数据，发起ajax请求取出详细数据',
	   		'real_plan' => '本地存储数据已完成，需搭建页面和接口测试',
	   		'start_time' => '2014-01-08',
	   		'end_time' => '2014-01-04',
			'people' => '胡金霞',
			'remark' => '无',
	   		'done' => false
	   ),
	   5 => array(
	   		'task' => '专题概要改为编辑器',
	   		'target' => '专题摘要改为编辑器,概要内容(居中，换行等都可以编辑)',
	   		'real_plan' => '完成',
	   		'start_time' => '2014-01-06',
	   		'end_time' => '2014-01-06',
			'people' => '张振',
			'remark' => '无',
			'done' => true
	   ),
	   6 => array(
	   		'task' => '拍客前端--批量上传、图片评论切换',
	   		'target' => '拍客前端--照片批量上传、大图banner切换下面的评论数据跟着改变、评论增加分页功能，增加评论',
	   		'real_plan' => '已完成',
	   		'start_time' => '2014-01-06',
	   		'end_time' => '2014-01-08',
			'people' => '乔学武',
			'remark' => '无',
			'done' => true
	   ),
	   7 => array(
	   		'task' => '企业平台app天气功能',
	   		'target' => '通过html5获取本地经纬度，通过百度api得到当前城市，发起ajax请求得到天气情况',
	   		'real_plan' => '已取得当前城市，等待接口',
	   		'start_time' => '2014-01-08',
	   		'end_time' => '2014-01-04',
			'people' => '胡金霞',
			'remark' => '无',
	   		'done' => false
	   )
	   
	 ),
	 'threeweek' => array(
	 0 => array(
	   		'task' => '无线城市webview直播、点播模块切图',
	   		'target' => '模块分离组织好、完成静态页面及一些交互',
	   		'real_plan' => '已完成',
	   		'start_time' => '2014-01-13',
	   		'end_time' => '',
			'people' => '陈梦洁',
			'remark' => '无',
			'done' => true
	   ),
	   1 => array(
	   		'task' => '无线城市webview新闻、公交模块切图',
	   		'target' => '模块分离组织好、完成静态页面及一些交互',
	   		'real_plan' => '公交「附近、收藏、路线、站点」选项下页面切图 √<br />新闻首页、详情页<br />',
	   		'start_time' => '2014-01-13',
	   		'end_time' => '',
			'people' => '陈梦洁',
			'remark' => '无',
			'done' => false
	   ),
	   2 => array(
	   		'task' => '企业平台app我的收藏本地存储',
	   		'target' => '与后端对接口输出收藏列表',
	   		'real_plan' => '已完成',
	   		'start_time' => '2014-01-14',
	   		'end_time' => '2014-01-17',
			'people' => '胡金霞',
			'remark' => '后台数据有问题',
			'done' => true
	   ),
	   3 => array(
	   		'task' => '企业平台app天气模块',
	   		'target' => '与后端对接口输出天气页面',
	   		'real_plan' => '已完成',
	   		'start_time' => '2014-01-13',
	   		'end_time' => '2014-01-15',
			'people' => '胡金霞',
			'remark' => '无',
			'done' => true
	   ),
	   4 => array(
	   		'task' => '数据源弹窗添加栏目数及与后端对接',
	   		'target' => '统一为数据源弹窗添加栏目数及添加人，后端提供统一接口',
	   		'real_plan' => '完成',
	   		'start_time' => '2014-01-13',
	   		'end_time' => '',
			'people' => '张振',
			'remark' => '无',
			'done' => true
	   ),
	   5 => array(
	   		'task' => 'app手机端交互事件的添加',
	   		'target' => '如抽屉式左右菜单的左右清扫滑出划入，加载更多等手机端事件，在手机端走一遍流程',
	   		'real_plan' => '抽屉式左清扫滑出划入，加载更多等手机端事件已加',
	   		'start_time' => '2014-01-13',
	   		'end_time' => '',
			'people' => '张振 陈梦洁',
			'remark' => '无',
			'done' => false
	   ),
	   6 => array(
	   		'task' => 'M2O Boke 前端调整',
	   		'target' => '配合调整前端交互',
	   		'real_plan' => '已完成',
	   		'start_time' => '2014-01-13',
	   		'end_time' => '',
			'people' => '乔学武',
			'remark' => '无',
			'done' => true
	   ),
	   7 => array(
	   		'task' => '专题模版',
	   		'target' => '专题模版模块功能跑通',
	   		'real_plan' => '已完成',
	   		'start_time' => '2014-01-16',
	   		'end_time' => '',
			'people' => '乔学武',
			'remark' => '无',
			'done' => true
	   ),
	   8 => array(
	   		'task' => '无线城市webview自行车模块切图',
	   		'target' => '模块分离组织好、完成静态页面及一些交互',
	   		'real_plan' => '已完成',
	   		'start_time' => '2014-01-13',
	   		'end_time' => '2014-01-20',
			'people' => '胡金霞',
			'remark' => '无',
			'done' => true
	   ),
	 ),
	 'fourweek' => array(
	 0 => array(
	 	'task' => 'applant企业平台app关于我们后台与手机端',
	 	'target' => '后台可添加app关于我们，手机端加入关于我们并展示',
	 	'real_plan' => '完成',
	 	'start_time' => '2014-01-23',
   		'end_time' => '2014-01-24',
		'people' => '胡金霞',
		'remark' => '无',
		'done' => true
	 ),
	 1 => array(
	 	'task' => 'applant企业平台app评论',
	 	'target' => 'webapp可以发布评论',
	 	'real_plan' => '已完成',
	 	'start_time' => '2014-01-24',
   		'end_time' => '2014-01-25',
		'people' => '胡金霞',
		'remark' => '无',
		'done' => true
	 ),
	 2 => array(
	 	'task' => '云平台数据源',
	 	'target' => '从云平台数据源接口取数据，可以加入到本地应用',
	 	'real_plan' => '完成',
	 	'start_time' => '2014-01-20',
   		'end_time' => '2014-01-22',
		'people' => '张振',
		'remark' => '无',
		'done' => true
	 ),
	 3 => array(
	 	'task' => '分享弹窗bug修正',
	 	'target' => '分享账号数据根据接口重新输出及交互bug修改',
	 	'real_plan' => '完成',
	 	'start_time' => '2014-01-22',
   		'end_time' => '2014-01-22',
		'people' => '胡金霞',
		'remark' => '无',
		'done' => true
	 ),
	 4 => array(
	 	'task' => '专题模版加入历史记录及本地简单搜索',
	 	'target' => '专题模版整体流转',
	 	'real_plan' => '完成',
	 	'start_time' => '2014-01-20',
   		'end_time' => '2014-01-22',
		'people' => '乔学武',
		'remark' => '无',
		'done' => true
	 ),
	 5 => array(
	 	'task' => 'm2o互动组件问卷调查',
	 	'target' => '完成互动组件前端工作',
	 	'real_plan' => '目前完成列表页包括样式交互，完成新增空白页面，新增引用页面，结果查询页面，以及评论查看的静态页，添加文本题，填空题，单选题，多选题页面，静态页面都已完成，基本交互也完成，等对接口',
	 	'start_time' => '2014-01-23',
   		'end_time' => '',
		'people' => '乔学武',
		'remark' => '无',
		'done' => false
	 )
	 ),
	 'fiveweek' => array(
	 )
 );
 ?>
 