#!/usr/bin/python
# -*- coding: utf8 -*-
#$Id: followCache.py 2847 2011-03-16 10:18:48Z develop_tong $

from config import *
import MySQLdb, memcache, threading,time, sys
import simplejson, urllib, httplib, base64

class cUrl(object):
    def __init__(self, host, port = 80, username = None, password = None, format = 'json'):
        self.setHostInfo(host, port)
        self.setUserVerify(username, password)
        self.setFormat(format)

    def setFormat (self, format = 'xml'):
        self._format = format
    
    def setHostInfo (self, host, port = 80):
        self._host = host
        self._port = port

    def setUserVerify (self, username = None, password = None):
        self._username = username
        self._password = password
        
    
    def fetchUrl(self, fn, params = None, method = 'POST'):
        params['innerTransKey'] = INNERTRANSKEY
        if None != params:
            params = urllib.urlencode(params)
        basic_auth = base64.encodestring('%s:%s' % (self._username, self._password))[:-1]
        headers = {"Content-type": "application/x-www-form-urlencoded","Accept": "text/plain","Connection":"Keep-Alive"}

        headers['Authorization'] = 'Basic %s' % basic_auth
        handle = httplib.HTTPConnection(self._host, self._port)
        filepath = '/' + fn
        if DebugMode:
            print filepath
        handle.request(method, filepath, params, headers)
        response = handle.getresponse()
        response = response.read(1000000)
        handle.close()
        return response

class getFollowStatus(threading.Thread):
    def __init__(self, action, member_id, fmember_id):
        threading.Thread.__init__(self)
        self.action = action
        self.member_id = member_id
        self.fmember_id = fmember_id
        self._queue = Queue
        self.statusApi = statusApi
        self.curl = cUrl(statusApi[0], statusApi[1])
    
    def fetchMemberStatus (self, since_id = 0):
        global statusApi
        params = {'user_id' : self.fmember_id, 'format' : 'json', 'count' : '200', 'since_id' : since_id}
        response = self.curl.fetchUrl(self.statusApi[2], params)
        if DebugMode:
            print response 
        try:
            response = simplejson.loads(response)
            sucess = False
            try:
                sucess = response['ErrorCode']
                if sucess:
                    response = None                
            except Exception, e:
                pass
        except Exception, e:
            response = None 
            pass
        try:
            del(response['Debug'])
        except Exception, e:
            pass
        return response        

    def fetchMemberInboxFirst (self):
        global statusApi
        params = {'user_id' : self.member_id, 'format' : 'json', 'a' : 'getfirst'}
        response = self.curl.fetchUrl(self.statusApi[3], params)
        return response        
    
    def run(self):
        member_status = self.fetchMemberStatus(0);
        if DebugMode:
            print member_status 
        if member_status:        
            try:
                followers = self._queue.get('testConQ')
            except Exception, e:
                if DebugMode:
                    print 'Reconnecting Queue'
                self._queue = ConnectQueue()
            for k,status in member_status.items():
                queue = '%s,%s,%s' % (self.member_id, status['id'], self.action)
                self._queue.set(PUSH_QUEUE, queue)
            if DebugMode:
                print 'Queue %s is built' % PUSH_QUEUE       
    

class Thread(threading.Thread):
    def __init__(self, no):
        threading.Thread.__init__(self)
        self._no = no
        self._queue = Queue
        self._DB = DBCursor
        self._memcache = Memcache
    
    def getFollow (self, id):
        if not id:
            return 0    
        sql = 'SELECT fmember_id FROM %smember_relation WHERE member_id=%d' % (DB_PREFIX, int(id))                 
        threadClock.acquire()
        try:
            self._DB.execute(sql)
        except Exception, e:
            if DebugMode:
                print 'Reconnecting DB %s' % DBConfig[0]
            self._DB = ConnectDB()
            self._DB.execute(sql)
        follow = self._DB.fetchall()
        threadClock.release()
        return follow

    def getFollowers (self, id):
        if not id:
            return 0    
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
        threadClock.release()
        return followers
        
    def run(self):
        while True:
            try:
                followers = self._queue.get(FOLLOW_QUEUE)
            except Exception, e:
                if DebugMode:
                    print 'Reconnecting Queue'
                self._queue = ConnectQueue()
                continue
            if followers:         
                followers = followers.split(',')
                if DebugMode:
                    print 'Thread[%s]' % self._no,  followers
                action = followers[0].strip()
                member_id = followers[1].strip()
                fmember_id = followers[2].strip()
                getStatusThread = getFollowStatus(action, member_id, fmember_id)
                getStatusThread.start()
                time.sleep(1)
                try:
                    self._memcache.set('testCon', 'python')
                except Exception, e:
                    if DebugMode:
                        print 'Reconnecting MemCache %s' % MemcacheConfig[0]
                    self._memcache = ConnectMemcache()
                    continue
                member_follows = self.getFollow(member_id);
                fmember_followers = self.getFollowers(fmember_id);
                ms = member_id;
                if member_follows:                
                    for id in member_follows:
                        ms = '%s,%s' % (ms, id[0])
                    try:
                        self._memcache.set(FRIENDS_MEM_PRE + member_id, ms)
                    except Exception, e:
                        if DebugMode:
                            print 'Reconnecting MemCache %s' % MemcacheConfig[0]
                        self._memcache = ConnectMemcache()
                        continue
                fms = fmember_id;
                if fmember_followers:     
                    for id in fmember_followers:
                        fms = '%s,%s' % (fms, id[0])
                    try:
                        self._memcache.set(FOLLOWERS_MEM_PRE + fmember_id, fms)
                    except Exception, e:
                        if DebugMode:
                            print 'Reconnecting MemCache %s' % MemcacheConfig[0]
                        self._memcache = ConnectMemcache()
                        continue
                
            time.sleep(1)

def ConnectMemcache():
    conected = False;
    while not conected:
        try:
            Memcache = memcache.Client([MemcacheConfig[0] + ':' + MemcacheConfig[1]],debug=0) 
            conected = True
        except Exception, e:
            time.sleep(3)
            continue
    return Memcache

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
            mainThread = Thread(i)
            mainThread.start()
        except Exception, e:
            print e
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
    Memcache = None  
    threadClock = threading.RLock()
    main()