#!/usr/bin/python
# -*- coding: utf8 -*-
#$Id: config.py 2483 2011-03-03 04:24:30Z develop_tong $

DBConfig = (
'10.0.1.80',#host
3306,#port
'root',#username
'hogesoft',#passwd
'sns_mblog_push',#dbname
''#socket
)
QueueConfig = [
    '10.0.1.80:21201',
    '10.0.1.66:21202',
]

DB_PREFIX = 'liv_'
PUSH_QUEUE = 'pushQ'