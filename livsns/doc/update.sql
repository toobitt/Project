DB �޸� LOG��

ALTER TABLE `liv_member` ADD `digital_tv` CHAR( 30 ) NOT NULL  default '';
ALTER TABLE `liv_member_extra` ADD `reffer_user` INT( 10 ) NOT NULL default 0;

-- video
ALTER TABLE `liv_user` ADD `member_id` INT( 10 ) NOT NULL ;

-- ucenter

ALTER TABLE `liv_member` ADD `user_group_id` INT( 10 ) NOT NULL COMMENT '�û���id' AFTER `truename` ,
ADD `open_tv` TINYINT( 0 ) NOT NULL COMMENT '�Ƿ�ͨ��̨' AFTER `user_group_id`

----------------------------------- 
���ݿ� sns_ucenter 2011/3/7 chengqing

ALTER TABLE `liv_member_extra` ADD `video_count` INT( 10 ) NOT NULL COMMENT '�û���Ƶ��Ŀ' AFTER `status_count` ;

-----------------------------------
���ݿ� sns_ucenter 2011/3/9 chengqing
������liv_member_extra
����ֶ� credit int(10) �û�����  

------------------------------------ 
���ݿ� video 2011/3/11 chengqing

ALTER TABLE `liv_network_station` ADD `state` TINYINT NOT NULL DEFAULT '0' COMMENT '0: ����� 1:ͨ�� 2:��ͨ�� Ĭ��Ϊ0' AFTER `programe` ;

---------------------------------------
���ݿ� video 2011/3/11 repheal

ALTER TABLE  `liv_video` ADD  `bans` VARCHAR( 400 ) NOT NULL COMMENT  '������' AFTER  `state` ;

---------------------------------------
���ݿ� video 2011/3/16 repheal

ALTER TABLE  `liv_group` CHANGE  `thread_updating`  `thread_updating` VARCHAR( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '������µĻ��⻺��'
ALTER TABLE  `liv_video` ADD  `is_thread` TINYINT( 1 ) NULL DEFAULT  '0' COMMENT  '�ж��Ƿ񷢲���������';

CREATE TABLE  `video`.`liv_advertising` (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`content` VARCHAR( 4000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`create_time` INT( 10 ) NOT NULL
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci

CREATE TABLE  `video`.`liv_advert_video` (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`page_id` INT( 10 ) NOT NULL ,
`adver_id` INT( 10 ) NOT NULL ,
`create_time` INT( 10 ) NOT NULL
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci

---------------------------------------




-- ucenter

ALTER TABLE `liv_member` ADD email_check TINYINT( 1 ) NOT NULL  default 0;

-- ucenter
ALTER TABLE `liv_verify_code` ADD `type` TINYINT( 1 ) NOT NULL DEFAULT '0'

-- tong 
ALTER TABLE  `liv_channel` ADD  `up_stream_mark` VARCHAR( 20 ) NOT NULL AFTER  `up_stream_name`


-- tong
ALTER TABLE  `liv_vodinfo` ADD  `audio` VARCHAR( 30 ) NOT NULL COMMENT  '��Ƶ��ʽ',
ADD  `audio_channels` CHAR( 10 ) NOT NULL COMMENT  '���� L,R',
ADD  `sampling_rate` CHAR( 10 ) NOT NULL COMMENT  '����������';
ALTER TABLE `liv_vodinfo` DROP `audiohz`;
ALTER TABLE  `liv_vodinfo` ADD  `video` VARCHAR( 30 ) NOT NULL COMMENT  '��Ƶ��ʽ',
ADD  `frame_rate` CHAR( 10 ) NOT NULL COMMENT  'Ƶ��',
ADD  `aspect` CHAR( 6 ) NOT NULL COMMENT  '����';
ALTER TABLE  `liv_vodinfo` ADD  `trans_use_time` SMALLINT( 6 ) NOT NULL COMMENT  'ת�뻨��ʱ��';
ALTER TABLE  `liv_vodinfo` ADD  `starttime` INT( 10 ) NOT NULL COMMENT  '¼����Ŀ��ʼʱ��' AFTER  `source` ,
ADD  `delay_time` INT( 10 ) NOT NULL COMMENT  '¼��Ƶ���ؿ�ʱ��' AFTER 


--program_record
ALTER TABLE  `liv_program_record` ADD  `columnid` CHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '��������ĿID' AFTER  `item`




-- 2012 -06 -13
ALTER TABLE  `liv_vodinfo` ADD  `from_appid` INT( 10 ) NOT NULL COMMENT  '���Կͻ���id',
ADD  `from_appname` VARCHAR( 30 ) NOT NULL COMMENT  '���Կͻ�������',
ADD  `server` VARCHAR( 20 ) NOT NULL COMMENT  '���Է�����',
ADD  filepath VARCHAR( 60 ) NOT NULL COMMENT  '·��';