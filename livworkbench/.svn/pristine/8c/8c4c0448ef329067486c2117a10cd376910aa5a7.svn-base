#!/usr/bin/python
# -*- coding: utf8 -*-
#$Id: followCache.py 870 2010-12-18 09:07:24Z develop_tong $

from config import *
import threading,time, sys, os
import urllib, httplib, base64
import json

class cUrl(object):
    def __init__(self, host, port = 80, Token = None):
        self.setHostInfo(host, port)
        self.setToken(Token)
    
    def setToken (self, Token):
        self._token = Token
    
    def setHostInfo (self, host, port = 80):
        self._host = host
        self._port = port
        
    def fetchUrl(self, fn, params = None, method = 'GET'):
        if not params:
            params = {}
        params['appid'] = APPID
        params['appkey'] = APPKEY
        if None != params:
            params = urllib.urlencode(params)
        headers = {"Content-type": "application/x-www-form-urlencoded","Accept": "text/plain","Connection":"Keep-Alive"}
        handle = httplib.HTTPConnection(self._host, self._port)
        filepath = '/' + fn
        if method == 'GET':
            if filepath.find('?') != -1:
                filepath = '%s&appid=%d&appkey=%s' % (filepath, APPID,APPKEY);
            else:
                filepath = '%s?appid=%d&appkey=%s' % (filepath, APPID,APPKEY);
        #print filepath
        #print params
        handle.request(method, filepath, params, headers)
        response = handle.getresponse()
        response = response.read(1000000)
        handle.close()
        return response    

class Thread(threading.Thread):
    def __init__(self, no):
        threading.Thread.__init__(self)
        self._no = no
        self.curl = cUrl(CRON_TAB[0], CRON_TAB[1], CRON_TAB[2])
    
        
    def run(self):
    	datacount = 0;
        while True:
            try:
                response = self.curl.fetchUrl(CRON_TAB[3])
            except Exception, e:
                response = None
                print e
            if response:
                try:
                    response = json.loads(response)
                    if response:
                        for cron in response:
                            thread = RunCron(cron['host'], int(cron['port']), (cron['dir'] + cron['file']), cron['token'])
                            thread.start()
                except Exception, e:
                    print e
                    #sys.exit()
                    response = None
            thread = RunCron(CRON_TAB[0], CRON_TAB[1], 'datacount.php?a=stats', '')
            thread.start()
            time.sleep(1)
            '''
            if not datacount:
            	pass
            datacount = datacount + 1
            if datacount > 86400:
            	datacount = 0
            '''

class RunCron(threading.Thread):
    def __init__(self, host, port, script, Token):
        threading.Thread.__init__(self)
        self._script = script
        self.curl = cUrl(host, port, Token)
    
    def run(self):
        try:
            response = self.curl.fetchUrl(self._script)
            print self._script, response
        except Exception, e:
            response = None
    
def main():
    t = int(Args['t'])
    pid = os.getpid()
    print 'pid:', pid
    for i in range(0, t):
        try:
            mainThread = Thread(i)
            mainThread.start()
        except Exception, e:
            print e
            print 'can not create thread %s ' % i

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
    main()