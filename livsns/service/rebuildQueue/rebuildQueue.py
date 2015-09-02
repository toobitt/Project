#!/usr/bin/python
# -*- coding: utf8 -*-
#$Id: rebuildQueue.py 949 2010-12-21 08:48:41Z develop_tong $

from config import *
import MySQLdb, memcache, threading,time, sys
    
class Thread(threading.Thread):
    def __init__(self, no):
        threading.Thread.__init__(self)
        self._no = no
        self._queue = Queue
        self._DB = DBCursor
            
    def run(self):
        while True:
            sql = 'SELECT id, name, value FROM %squeue ORDER BY id ASC LIMIT 100' % (DB_PREFIX)        
            if DebugMode:
                print 'sql is %s' % sql         
            threadClock.acquire()
            try:
                self._DB.execute(sql)
            except Exception, e:
                if DebugMode:
                    print 'Reconnecting DB %s' % DBConfig[0]
                self._DB = ConnectDB()
                self._DB.execute(sql)
            queues = self._DB.fetchall()
            threadClock.release()
            if queues:
                if DebugMode:
                    print queues
                qid = []
                for queue in queues:
                    qid.append(str(queue[0]))
                    qname = queue[1].encode()
                    qvalue = queue[2].encode()
                    try:
                        self._queue.set(qname, qvalue) 
                    except Exception, e:
                        if DebugMode:
                            print 'Reconnecting Queue'
                        self._queue = ConnectQueue()
                        self._queue.set(qname, qvalue) 
                        continue
                print qid
                ids = ','.join(qid)
                sql = 'DELETE  FROM %squeue WHERE id IN (%s)' % (DB_PREFIX, ids)
                if DebugMode:
                    print 'sql is %s' % sql
                threadClock.acquire()
                try:
                    self._DB.execute(sql)
                except Exception, e:
                    if DebugMode:
                        print 'Reconnecting DB %s' % DBConfig[0]
                    self._DB = ConnectDB()
                    self._DB.execute(sql)
                threadClock.release()                 
            
            time.sleep(3)

def ConnectDB ():
    conected = False
    while not conected:
        try:
            dbConnectId=MySQLdb.Connection(host=DBConfig[0],user=DBConfig[2],passwd=DBConfig[3],db=DBConfig[4] ,port=DBConfig[1],unix_socket=DBConfig[5],charset='utf8')
            DBCursor=dbConnectId.cursor()
            conected = True
        except Exception, e:
            if DebugMode:
                print 'Reconnecting DB %s' % DBConfig[0]
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
    mainThread = Thread(0)
    mainThread.start() 

if __name__ == '__main__':
    Args = {'t' : 1, 'debug' : 0}
    if len(sys.argv) > 1 and (sys.argv[1].find('help') != -1 or sys.argv[1].find('?') != -1) :
        print ''
        print '-------------------------------------------------------------------'
        print '   This Program has follow args:'
        print '        -t      Set threads num,default 300'
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
    DBCursor = ConnectDB()
    Queue = ConnectQueue()   
    threadClock = threading.RLock()
    main()