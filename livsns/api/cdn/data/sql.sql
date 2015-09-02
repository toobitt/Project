create database if not exists dev_cdn
character set utf8
collate utf8_unicode_ci;


create table liv_cdn_log 
(
 `id` int(10) not null auto_increment,
 `type` int(2) not null,
 `taskid` int(10) comment 'the chinacache return taskid',
 `data` text,
 `state` int(2) not null default '1' comment  '0 failed,1 success',
 
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `user_name` char(32) NOT NULL COMMENT '用户名',
  `appid` int(10) NOT NULL COMMENT '应用id',
  `appname` char(32) NOT NULL COMMENT '应用名',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '更新时间',
  `ip` char(64) NOT NULL COMMENT '创建者ip',

   primary key(id),
   key `taskid`(`taskid`)

) 
engine=myisam 
default charset 'utf8' 
collate utf8_unicode_ci
comment='cdn log';

