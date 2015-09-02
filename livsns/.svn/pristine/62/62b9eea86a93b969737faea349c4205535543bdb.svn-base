<?php
require('global.php');
class installApi extends BaseFrm
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function show()
	{
		$this->publish();
	}
	
	function publish()
	{
		include_once(ROOT_PATH . 'lib/class/publishsys.class.php');
		$obj = new publishsys();
		$data = array(
			'bundle_id' => "news",
			'module_id' => "news_m",
			'struct_id' => "article",
			'struct_ast_id' => "",
			'content_type' => "新闻",
			'expand_id' => "",
			'content_fromid' => "",
			'field' => "id,title,page_title,tcolor,isbold,isitalic,subtitle,keywords,brief,author,source,indexpic,outlink,state,sort_id,is_img,is_affix,is_video,video_id,column_id,user_name,order_id,istop,istpl,tpl_file,pub_time,create_time,update_time,ip,iscom,comm_num,click_num,is_del,water_id,water_name,content,expand_id,ori_url",
			'field_sql' => "`id` int(10) NOT NULL AUTO_INCREMENT,
							  `title` varchar(200) NOT NULL COMMENT '标题',
							  `page_title` varchar(150) NOT NULL COMMENT '分页标题',
							  `tcolor` varchar(20) NOT NULL COMMENT '标题颜色',
							  `isbold` tinyint(1) NOT NULL DEFAULT '0' COMMENT '标题是否为粗体。1为加粗，0为不加粗',
							  `isitalic` tinyint(1) NOT NULL DEFAULT '0' COMMENT '标题是否为斜体。1为斜体，0不是斜体',
							  `subtitle` varchar(200) NOT NULL COMMENT '副标题',
							  `keywords` varchar(200) NOT NULL COMMENT '关键词用,隔开',
							  `brief` varchar(300) NOT NULL COMMENT '简介',
							  `author` varchar(30) NOT NULL COMMENT '作者',
							  `source` varchar(30) NOT NULL COMMENT '文章来源',
							  `indexpic` int(10) NOT NULL COMMENT '索引图片id',
							  `outlink` varchar(150) NOT NULL COMMENT '外链',
							  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0-待发布1--发布成功',
							  `sort_id` int(10) NOT NULL COMMENT '所属分类',
							  `is_img` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否图片',
							  `is_affix` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否附件',
							  `is_video` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否视频',
							  `video_id` int(10) NOT NULL COMMENT '视频ID，用,',
							  `column_id` varchar(100) NOT NULL COMMENT '发布的栏目',
							  `user_name` varchar(30) NOT NULL COMMENT '发布者',
							  `order_id` int(10) NOT NULL DEFAULT '0' COMMENT '排序ID',
							  `istop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶',
							  `istpl` tinyint(1) NOT NULL COMMENT '是否是独立模板',
							  `tpl_file` varchar(100) NOT NULL COMMENT '//指定模板文件名',
							  `pub_time` int(10) NOT NULL COMMENT '发布时间',
							  `create_time` int(10) NOT NULL COMMENT '创建时间',
							  `update_time` int(10) NOT NULL COMMENT '更新时间',
							  `ip` varchar(60) NOT NULL,
							  `iscomm` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否准许评论，1准许，0不准许',
							  `comm_num` int(10) NOT NULL COMMENT '//评论数',
							  `click_num` int(10) NOT NULL COMMENT '//点击数',
							  `is_del` tinyint(1) NOT NULL COMMENT '0--删除 1--不删除',
							  `water_id` int(10) NOT NULL COMMENT '水印ID',
							  `water_name` varchar(50) NOT NULL COMMENT '水印标识',
							  `content` longtext NOT NULL COMMENT '内容',
							  `expand_id` int(10) NOT NULL COMMENT '发布系统',
							  `ori_url` varchar(300) NOT NULL COMMENT '原始文章ID',
							  PRIMARY KEY (`id`),
							  KEY `sort_id` (`sort_id`),
							  KEY `state` (`state`),
							  KEY `order_id` (`order_id`),
							  KEY `create_time` (`create_time`)",
			'table_title' => "素材",
			'child_table' => "material",
			'show_field' => array(
					array('field'=>'title','title'=>'标题','type'=>'text'),
					array('field'=>'brief','title'=>'简介','type'=>'text'),
					array('field'=>'keywords','title'=>'关键词','type'=>'text'),
					array('field'=>'author','title'=>'作者','type'=>'text'),
				),			
		);
		$ret = $obj->create_table($data);
		$ret = $ret[0];
		if(empty($ret['error']))
		{
			$data = array(
				'bundle_id' => "news",
				'module_id' => "news_m",
				'struct_id' => "article",
				'struct_ast_id' => "material",
				'content_type' => "素材",
				'expand_id' => "",
				'content_fromid' => "",
				'field' => "id,cid,expand_id,content_fromid,material_id,name,pic_host,pic_dir,pic_filepath,pic_filename,type,imgwidth,imgheight,filesize,isdel,create_time,ip,remote_url,title,brief",
				'field_sql' => "`id` int(10) NOT NULL AUTO_INCREMENT,
							  `cid` int(10) NOT NULL COMMENT '//对应内容id',
							  `expand_id` int(10) NOT NULL COMMENT '//发布系统',
							  `content_fromid` int(10) NOT NULL COMMENT '//原内容id',
							  `material_id` int(10) NOT NULL COMMENT '图片服务器的素材ID',
							  `name` varchar(40) NOT NULL COMMENT '图片名称',
							  `pic_host` varchar(200) NOT NULL COMMENT 'host',
							  `pic_dir` varchar(100) NOT NULL COMMENT 'dir',
							  `pic_filepath` varchar(100) NOT NULL COMMENT '原图的存储路径',
							  `pic_filename` varchar(40) NOT NULL COMMENT '文件名称',
							  `type` varchar(10) NOT NULL COMMENT '图片类型',
							  `imgwidth` smallint(4) NOT NULL COMMENT '图片宽度',
							  `imgheight` smallint(4) NOT NULL COMMENT '图片高度',
							  `filesize` int(10) NOT NULL COMMENT '图片大小',
							  `isdel` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否删除0－是 1－ 否',
							  `create_time` int(10) NOT NULL,
							  `ip` varchar(60) NOT NULL,
							  `remote_url` varchar(200) NOT NULL COMMENT '图片原始远程地址',
							  `title` varchar(200) NOT NULL COMMENT '标题',
							  `brief` varchar(400) NOT NULL COMMENT '标题',
							  PRIMARY KEY (`id`)",
				'table_title' => "素材",
				'child_table' => "",
				'show_field' => array(
						array('field'=>'title','title'=>'标题','type'=>'text'),
						array('field'=>'brief','title'=>'简介','type'=>'text'),
						array('field'=>'name','title'=>'图片名称','type'=>'text'),
						array('field'=>'pic','title'=>'图片','type' => 'img'),
						array('field'=>'remote_url','title'=>'图片原始远程地址','type'=>'text'),
					),			
			);	
			$retChild = $obj->create_table($data);
			if(empty($retChild[0]['error']))
			{
				echo $retChild[0]['msg'];
			}
			else
			{
				echo $retChild[0]['msg'];
			}
		}
		else
		{
			echo $ret[0]['msg'];
		}
	}
	
	
	/**
	 *   
	 * 
	 */

	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new installApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
