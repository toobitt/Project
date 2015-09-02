#!/usr/bin/python
# -*- coding: utf8 -*-
#$Id: config.py 2483 2011-03-03 04:24:30Z develop_tong $

DBConfig = (
'10.0.1.80',#host
3306,#port
'root',#username
'hogesoft',#passwd
'sns_ucenter',#dbname
''#socket
)
statusApi = (
    '10.0.1.80',
    '80',
    'livsns/api/statuses/get_userstatus_id.php', #用户微博接口
    'livsns/api/push/get_userstatus.php' #用户相关信息
)
QueueConfig = [
    '10.0.1.80:21201',
]
MemcacheConfig = (
    '10.0.1.80',
    '21202'
)
DB_PREFIX = 'liv_'
PUSH_QUEUE = 'pushQ'
FOLLOW_QUEUE = 'followQ'
STATUS_QUEUE = 'statusQ'

FRIENDS_MEM_PRE = 'friends'
FOLLOWERS_MEM_PRE = 'followers'
INNERTRANSKEY = 'dsafqoi8w222343255'