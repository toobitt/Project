#!/usr/bin/python
# -*- coding: utf8 -*-
#$Id: statusPush.py 2847 2011-03-16 10:18:48Z develop_tong $

from config import *
import MySQLdb, memcache, threading,time, sys
    
class mblog_push(threading.Thread):
    def __init__(self, no):
        threading.Thread.__init__(self)
        self._no = no
        self._queue = Queue
        self._DB = None

    def run(self):
        while True:
            try:
                mblog = self._queue.get(PUSH_QUEUE)
            except Exception, e:
                if DebugMode:
                    print 'Reconnecting Queue'
                self._queue = ConnectQueue()
                continue
            if mblog:         
                mblog = mblog.split(',')
                if DebugMode:
                    print 'Thread[%s]' % self._no,  mblog
                if int(mblog[2]) == 1:                
                    sql = 'INSERT IGNORE INTO %sstatus_push (member_id, status_id) VALUES (%d, %d)' % (DB_PREFIX, int(mblog[0]), int(mblog[1]))
                    deletesql = 'call clear_push_data(%d)' % int(mblog[0])
                else:
                    sql = 'DELETE FROM %sstatus_push WHERE member_id=%d AND  status_id=%d' % (DB_PREFIX, int(mblog[0]), int(mblog[1]))
                    deletesql = ''
                if DebugMode:
                    print 'sql %s' % sql
                threadClock.acquire()
                self._DB = ConnectDB();
                try:
                    self._DB.execute(sql)               
                except Exception, e:
                    if DebugMode:
                        print 'Reconnecting DB %s' % DBConfig[0]
                    self._DB = ConnectDB()
                    self._DB.execute(sql)
                if deletesql:
                    try:
                            self._DB.execute(deletesql)
                    except Exception, e:
                        pass
                self._DB.close();
                threadClock.release()
            time.sleep(1)

def ConnectDB ():
    conected = False
    while not conected:
        try:
            dbConnectId=MySQLdb.Connection(host=DBConfig[0],user=DBConfig[2],passwd=DBConfig[3],db=DBConfig[4] ,port=DBConfig[1],unix_socket=DBConfig[5],charset='utf8')
            DBCursor=dbConnectId.cursor()
            conected = True
        except Exception, e:
            time.sleep(3)
            continue
    return DBCursor
    
def ConnectQueue ():
    conected = False;
    while not conected:
        try:
            Queue = memcache.Client(QueueConfig,debug=0) 
            conected = True
        except Exception, e:
            if DebugMode:
                print 'Reconnecting Queue'
            time.sleep(3)
            continue
    return Queue

def main():
    t = int(Args['t'])
    for i in range(0, t):
        try:
            mainThread = mblog_push(i)
            mainThread.start()
        except Exception, e:
            print 'can not create thread %s ' % i
    

if __name__ == '__main__':
    Args = {'t' : 100, 'debug' : 0, 'queue' : 'pushQ'}
    if len(sys.argv) > 1 and (sys.argv[1].find('help') != -1 or sys.argv[1].find('?') != -1) :
        print ''
        print '-------------------------------------------------------------------'
        print '   This Program has follow args:'
        print '        -t      Set threads num,default 300'
        print '        -debug      Debug Mode, default 0 '
        print '        -queue      Queue name, default pushQ'
        print  '  Run in background command:  '
        print '       Enter to this program dir first, and then run '
        print '   [root@host #] nohup  %s > run.out &' % sys.argv[0]
        print '-------------------------------------------------------------------'
        print ''
        sys.exit();
    
    for arg in sys.argv:
        if arg.startswith('-') and arg.find('=') != -1:
            arg = arg[1:]
            arg = arg.split('=')
            Args[arg[0]] = arg[1]
    DebugMode = Args['debug']
    PUSH_QUEUE = Args['queue']
    Queue = ConnectQueue()   
    threadClock = threading.RLock()
    main()