#!/usr/bin/python
# -*- coding: utf8 -*-
#$Id: createQueue.py 2847 2011-03-16 10:18:48Z develop_tong $

from config import *
import MySQLdb, memcache, threading,time, sys
    
class Thread(threading.Thread):
    def __init__(self, no):
        threading.Thread.__init__(self)
        self._no = no
        self._queue = Queue
        self._DB = DBCursor
    
    def getFollowers (self, id):
        if not id:
            return 0    
        memcache = ConnectMemcache();
        if memcache:
            followers = memcache.get(FOLLOWERS_MEM_PRE + id)
            if DebugMode:
                print 'followers cache ', followers
            if followers:
                followers = followers.split(',');
                type = 0
                directGet = False
            else:
                directGet = True
        else:
            directGet = True
        if directGet:
            sql = 'SELECT member_id FROM %smember_relation WHERE fmember_id=%d' % (DB_PREFIX, int(id))                 
            threadClock.acquire()
            try:
                self._DB.execute(sql)
            except Exception, e:
                if DebugMode:
                    print 'Reconnecting DB %s' % DBConfig[0]
                self._DB = ConnectDB()
                self._DB.execute(sql)
            followers = self._DB.fetchall()
            type = 1
            threadClock.release()
        return [type,followers]
        
    def run(self):
        while True:
            try:
                newstatus = self._queue.get(STATUS_QUEUE)
            except Exception, e:
                if DebugMode:
                    print 'Reconnecting Queue'
                self._queue = ConnectQueue()
                continue
            if newstatus:         
                newstatus = newstatus.split(',')
                if DebugMode:
                    print 'Thread[%s]' % self._no,  newstatus
                member_id = newstatus[0]
                status_id = int(newstatus[1])
                self._queue.set(PUSH_QUEUE, '%d,%d,1' % (int(member_id), status_id))              
                sql = 'UPDATE %smember_extra SET status_count = status_count + 1, last_status_id=%d WHERE member_id=%d' % (DB_PREFIX, status_id, int(member_id))
                threadClock.acquire()
                try:
                    self._DB.execute(sql)
                except Exception, e:
                    if DebugMode:
                        print 'Reconnecting DB %s' % DBConfig[0]
                    self._DB = ConnectDB()
                    self._DB.execute(sql)
                threadClock.release()
                follows = self.getFollowers(member_id);
                if follows[1]:                
                    if 0 == follows[0]:
                        for id in follows[1]:
                            self._queue.set('%s1' % PUSH_QUEUE, '%d,%d,1' % (int(id), status_id))
                    elif 1 == follows[0]:
                        for id in follows[1]:
                            self._queue.set('%s1' % PUSH_QUEUE, '%d,%d,1' % (int(id[0]), status_id)) 

            time.sleep(1)

def ConnectMemcache():
    try:
        Memcache = memcache.Client([MemcacheConfig[0] + ':' + MemcacheConfig[1]],debug=0) 
    except Exception, e:
        Memcache = False

    return Memcache

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
                print 'Reconnecting Queue ' , QueueConfig
            time.sleep(3)
            continue
    return Queue

def main():
    t = int(Args['t'])
    for i in range(0, t):
        try:
            mainThread = Thread(i)
            mainThread.start()
        except Exception, e:
            print 'can not create thread %s ' % i    

if __name__ == '__main__':
    Args = {'t' : 100, 'debug' : 0}
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