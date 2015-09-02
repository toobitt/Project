ALTER TABLE  `liv_modules` ADD  `relate_molude_id` INT( 10 ) NOT NULL DEFAULT  '0' COMMENT  '关联模块';
ALTER TABLE  `liv_module_append` ADD  `op` CHAR( 20 ) NOT NULL DEFAULT '' ;
ALTER TABLE  `liv_module_op` ADD  `op_link` VARCHAR( 250 ) NOT NULL DEFAULT '' COMMENT  '指定链接',
ADD  `direct_return` TINYINT( 1 ) NOT NULL DEFAULT  '0';
-- 2011-09-22 by tong
ALTER TABLE  `liv_modules` ADD  `menu_pos` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '菜单位置';
--  2011-0929 by tong
ALTER TABLE  `liv_applications` ADD  `father_id` INT( 10 ) NOT NULL DEFAULT  '0' COMMENT  '上级系统' AFTER  `name` ;UPDATE `hoge_workbench`.`liv_module_op` SET `exec_callback` = '1' WHERE `liv_module_op`.`id` =42 LIMIT 1 ;

-- 2011-10-19 by zhuld
ALTER TABLE `liv_advcontent` ADD `advpos` VARCHAR( 50 ) NOT NULL COMMENT '广告位' AFTER `link` ;
ALTER TABLE `liv_advgroup` ADD `columnid` INT( 10 ) NOT NULL COMMENT '绑定的栏目' AFTER `flag` ;
INSERT INTO `hoge_workbench`.`liv_module_node` (
`id` ,
`module_id` ,
`node_id` ,
`module_op`
)
VALUES (
NULL , '23', '1', 'form'
);
ALTER TABLE `liv_advcontent` ADD `online` TINYINT( 1 ) NOT NULL COMMENT '0-1本地 1-视频库素材 2-在线素材' AFTER `type` ;

ALTER TABLE `liv_vodinfo` ADD `mark_count` INT( 10 ) NULL DEFAULT '0' COMMENT '该视频被标注的个数';
ALTER TABLE `liv_vodinfo` ADD `mark_etime` INT( 10 ) NULL DEFAULT '0' COMMENT '标注的结束时间';
ALTER TABLE `liv_vodinfo` ADD `isfile` TINYINT( 10 ) NULL DEFAULT '0' COMMENT '是否有物理文件';

-- 2011-12-07
ALTER TABLE  `liv_module_op` CHANGE  `file_name`  `file_name` VARCHAR( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '模块文件名',
CHANGE  `template`  `template` VARCHAR( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '模块模板'

2012/1/7
ALTER TABLE `liv_advpub` ADD `pos_flag` CHAR( 64 ) NOT NULL COMMENT '广告位英文标志' AFTER `pos_id` 

2012/1/9
ALTER TABLE `liv_publish` ADD `status` INT NOT NULL COMMENT '内容状态' AFTER `cms_contentmap_id`

2012/1/13
UPDATE `dev_workbench`.`liv_modules` SET `is_pub` = '1' WHERE `liv_modules`.`id` =14;

2012/1/14
ALTER TABLE `liv_module_op` ADD `触发发布` TINYINT( 1 ) NOT NULL AFTER `fetch_lastdata` 
ALTER TABLE `liv_module_op` CHANGE `触发发布` `trigger_pub` TINYINT( 1 ) NOT NULL COMMENT '触发发布'

2012/1/30
ALTER TABLE `liv_channel` ADD `record_time` INT( 10 ) NOT NULL COMMENT '录制节目时间偏差设置'
ALTER TABLE `liv_channel` ADD `audio_only` TINYINT( 1 ) NOT NULL COMMENT '是否音频（1表示音频，0表示视频）'

2012/2/9
ALTER TABLE `liv_vodinfo` ADD `is_finish` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT '指示是否标注完0=>未完成，1=>已完成' AFTER `is_allow` 

2012/2/15
ALTER TABLE `liv_tuji_sort` ADD `order_id` INT( 10 ) NOT NULL COMMENT '排序ID'

2012/2/24
ALTER TABLE `liv_module_op` CHANGE `trigger_pub` `trigger_pub` VARCHAR( 2000 ) NOT NULL COMMENT '触发发布'
ALTER TABLE `liv_module_op` CHANGE `show_pub` `show_pub` VARCHAR( 2000 ) NOT NULL COMMENT '显示发布信息'
2012/5/10
ALTER TABLE `liv_advpos` ADD `form_style` VARCHAR( 500 ) NOT NULL AFTER `para` 
ALTER TABLE `liv_animation` ADD `form_style` VARCHAR( 500 ) NOT NULL AFTER `para` 

2012/5/18
ALTER TABLE `liv_columns` ADD `special` TINYINT( 1 ) NOT NULL COMMENT '是否是专题'
ALTER TABLE `liv_columns` CHANGE `type` `type` VARCHAR( 16 ) NOT NULL COMMENT '栏目类型 手机2 网站1'


ALTER TABLE  `liv_menu` ADD  `app_uniqueid` VARCHAR( 30 ) NOT NULL ,
ADD  `mod_uniqueid` VARCHAR( 30 ) NOT NULL ;
ALTER TABLE  `liv_node` 
ADD  `node_uniqueid` VARCHAR( 30 ) NOT NULL 

2012/08/22
ALTER TABLE  `liv_channel` ADD  `appid` INT( 10 ) NOT NULL
ALTER TABLE  `liv_channel` ADD  `appname` CHAR( 64 ) NOT NULL

ALTER TABLE  `liv_vote_question` ADD  `user_id` INT( 10 ) NOT NULL ,
ADD  `user_name` CHAR( 64 ) NOT NULL ,
ADD  `appid` INT( 10 ) NOT NULL ,
ADD  `appname` CHAR( 64 ) NOT NULL

ALTER TABLE  `liv_vote` ADD  `user_id` INT( 10 ) NOT NULL ,
ADD  `user_name` CHAR( 64 ) NOT NULL ,
ADD  `appid` INT( 10 ) NOT NULL ,
ADD  `appname` CHAR( 64 ) NOT NULL

ALTER TABLE  `liv_vodinfo` ADD  `img_info` VARCHAR( 256 ) NOT NULL COMMENT  '图片信息' AFTER  `img`

ALTER TABLE  `liv_vodinfo` ADD  `is_forcecode` TINYINT( 1 ) NOT NULL COMMENT  '该视频是否被强制转码的' AFTER  `is_finish`

