#!/usr/bin/python
# -*- coding: utf8 -*-
import sys
reload(sys)
sys.setdefaultencoding('utf8')
import os
import socket
import json
import hashlib
import time
import subprocess
#import base64

def checkCmd(cm):
    commands = getFileContent(SCRITPATH + 'allow.cmd')
    commands = commands.replace("\r\n", "\n")
    commands = commands.replace("\r", "\n")
    commands = commands.split("\n")
    cm = cm.strip()
    cm = cm.split(' ')
    cm = cm[0]
    if commands.__contains__(cm):
        return True
    return False

def getAllowCmd():
    commands = getFileContent(SCRITPATH + 'allow.cmd')
    sendDataBack(connection, commands)

def checkFiles(f):
    if not os.path.isfile(f):
        return False
    files = getFileContent(SCRITPATH + 'allow.file')
    files = files.replace("\r\n", "\n")
    files = files.replace("\r", "\n")
    files = files.split("\n")
    if files.__contains__(f):
        return True
    else:
        fs = f.split('/')
        filen = fs[-1]
        filen = filen.split('.')
        filen = '*.' + filen[-1]    
        if files.__contains__(filen):
            return True
        tmp = '/'
        for val in fs:
            if not val:
                continue;
            tmp = tmp + val + '/'
            if files.__contains__(tmp):
                return True
    return False

def getAllowFiles():
    commands = getFileContent(SCRITPATH + 'allow.file')
    sendDataBack(connection, commands)

def getFileContent(filen):
    if not os.path.isfile(filen):
        return ''
    fp = open(filen, 'r')
    content = fp.read() 
    fp.close()
    return content

def fileWrite(filen, content, m):
    if m == 'r' and not os.path.isfile(filen):
        return False
    #filen = './conf.txt'
    if not content:
        return False
    try:    
        fp = open(filen, m)
        fp.write(content) 
        fp.close()
        return True
    except Exception, e:
        print e
        return False

def md5(s):
    md5 = hashlib.md5()
    md5.update(s)
    return md5.hexdigest()

def getAccount():
    account = {}
    try:
        passwd = getFileContent(SCRITPATH + 'passwd')
        passwd = passwd.replace("\r\n", "\n")
        passwd = passwd.replace("\r", "\n")
        passwd = passwd.split("\n")
        for val in passwd:
            if not val or val[0:1] == '#':
                continue
            if -1 == val.find("="):
                continue
            val = val.split("=")
            k = val[0].strip()
            v = val[1].strip()
            if not k or not v:
                continue
            account[k] = v
        return account
    except Exception, e:
        return None

def checkPasswd(account, password):
    password = md5(password)
    if password == ACCOUNT[account]:
        return True
    return False

def parseData(s):
    try:
        j = json.loads(s)
        return j
    except Exception, e:
        print e
        return None

def getFile(connection, filen):
    if not checkFiles(filen):
        sendDataBack(connection, 'Can\'t access this file')
        return
    content = getFileContent(filen)
    sendDataBack(connection, content)

def write2File(connection, filen, content, chrset): 
    if not checkFiles(filen):
        sendDataBack(connection, 'Can\'t access this file')
        return
    print filen
    os.rename(filen, filen + '.bak')
    if chrset == 'utf8':
        content = content.encode('utf8')
    if fileWrite(filen, content, 'w'):
        sendDataBack(connection, 'success')
    else:
        try:
            os.rename(filen + '.bak', filen)
        except Exception, e:
            pass
        sendDataBack(connection, 'failed')

def getProgressPid(connection):
    pid = '%d' % os.getpid()
    sendDataBack(connection, pid)
    
def ospopen(connection, scmd):
    if not scmd:
        return
    if not checkCmd(scmd):
        sendDataBack(connection, scmd + ' not support')
        return
    os.popen(scmd)
    sendDataBack(connection, scmd + 'success')
    return
    
def runCmd(connection, scmd):
    if not scmd:
        sendDataBack(connection, 'No CMD run')
        return
    if not checkCmd(scmd):
        sendDataBack(connection, scmd + ' not support')
        return
    print scmd
    p = subprocess.Popen(scmd, shell=True, stdout=subprocess.PIPE, stderr=None, close_fds=True)
    content = ''
    for line in p.stdout.readlines():
        content = content + line
    sendDataBack(connection, content)
    return

def download(connection, package, todir):
    if not package or not todir:
        sendDataBack(connection, 'No package or not specify dir')
        return
    if todir[-1] != '/':
        todir = todir + '/'
    if package[-1] == '/':
        filen = 'index'
    else:
        filen = package.split('/')
        filen = filen[-1]
    if not os.path.exists(todir):
        os.makedirs(todir)
    scmd = 'curl %s > %s%s ' % (package, todir, filen)
    print scmd
    p = subprocess.Popen(scmd, shell=True, stdout=subprocess.PIPE, stderr=None, close_fds=True)
    #content = ''
    #for line in p.stdout.readlines():
    #    content = content + line
    #print content
    #sendDataBack(connection, content, False)
    while(p.poll() == None):
        time.sleep(1)

    if not os.path.isfile(todir + filen):
        sendDataBack(connection, 'download failed!')
        return
    suffix = filen.split('.')
    if suffix[-1] == 'zip':
        scmd = 'unzip -o %s%s -d %s' % (todir, filen, todir)
        print scmd
        f = os.popen(scmd)
        sendDataBack(connection, f.read())
        os.remove(todir + filen)
    elif suffix[-1] == 'gz':
        scmd = 'tar'
        sendDataBack(connection, 'Not support!')
    elif suffix[-1] == 'tar':
        scmd = 'tar'
        sendDataBack(connection, 'Not support!')
    else:
        sendDataBack(connection, 'Not a achive package!')
    return

def sendDataBack(connection, ds, isclose = True):
    #ds = base64.b64encode(ds)
    connection.send(ds)
    if isclose:
        connection.close()

def memory_stat(connection):
    f = open('/proc/meminfo')
    lines = f.readlines()
    sendDataBack(connection, lines)

def top(connection):
    stime = time.time()
    p = subprocess.Popen('top -bn 1', shell=True, stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=None, close_fds=True)
    #content = p.communicate(input='q')    
    content = ''
    for line in p.stdout.readlines():
        content = content + line
    sendDataBack(connection, content)
    return

def mkdirs(connection, dirs): 
    if not os.path.exists(dirs):
        os.makedirs(dirs)
    sendDataBack(connection, 'success')
    return

def createuser(account, password):
    if account and password:
        s = account + '=' + md5(password) + "\n"
        fileWrite(SCRITPATH + 'passwd', s, 'a')
        print 'create new user ' + account
    else:
        print 'please specify user by -u account and -p password'

if __name__ == '__main__': 
    scritpath = sys.argv[0] 
    scritpath = scritpath.split('/')
    scritpath = scritpath[ : (len(scritpath) - 1)]
    SCRITPATH = '/' . join(scritpath) + '/'
    args = {'h' : '', 'P' : '6233', 'u' : '', 'p' : ''}
    for arg in sys.argv:
        if arg.startswith('-'):
            arg = arg[1:]
            k = arg[0:1]
            v = arg[1:].strip()
            if v:
                args[k] = v
    if len(sys.argv) > 1 and sys.argv[1] == 'createuser':
        createuser(args['u'], args['p'])
        sys.exit()
    H = args['h']
    if not H:
        try:
            H = socket.gethostbyname(socket.gethostname())
        except Exception, e:
            print 'Please set IP by -h'
            sys.exit();
    P = int(args['P'])
    ACCOUNT = getAccount()
    if not ACCOUNT:
        print 'Please create account by ' + sys.argv[0] + ' createuser -uaccount -ppassword'
        sys.exit()

    try:
        sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        sock.bind((H, P))
        sock.listen(100)
    except Exception, e:
        print 'Port %d already in use!' % P
        sys.exit()
    print 'Server start at %s:%d' % (H, P)
    TEMPDIR = './tmp/'
    if not os.path.exists(TEMPDIR):
        os.mkdir(TEMPDIR)
    while True:  
        connection,address = sock.accept()  
        try:  
            connection.settimeout(10)
            ds = ''
            s = ''
            while 1:
                buf = connection.recv(4096)
                if not buf:break
                ds += buf
                try:
                    s = json.loads(ds)
                    if s:
                        break
                except Exception, e:
                    continue
                #if len(buf) < 256:
                #    break
            #ds = base64.b64decode(ds)
            #print ds
            #s = parseData(ds)
            if not s:
                sendDataBack(connection, 'Data format error!')
                continue
            try:
                if not s['action']:
                    sendDataBack(connection, 'Unknow action!') 
                    continue
                #if not s['user'] or not s['pass']:
                #    sendDataBack(connection, 'Unknow user!')
                #    continue
                #if not checkPasswd(s['user'], s['pass']):
                #    sendDataBack(connection, 'user or pass error!')
                #    continue
                dofunc = s['action']
                {
                    'get.pid': lambda: getProgressPid(connection),
                    'start': lambda: ospopen(connection,s['para']),
                    'stop': lambda: ospopen(connection,s['para']),
                    'restart': lambda: ospopen(connection,s['para']),
                    'ls': lambda: runCmd(connection,'ls ' + s['para']),
                    'mkdir': lambda: runCmd(connection,'mkdir ' + s['para']),
                    'mkdirs': lambda: mkdirs(connection,s['para']),
                    'ping': lambda: runCmd(connection,'ping -c4 ' + s['para']),
                    'top': lambda: top(connection),
                    'allowcmd': lambda: getAllowCmd(connection),
                    'allowfile': lambda: getAllowFiles(connection),
                    'df': lambda: runCmd(connection,'df ' + s['para']),
                    'du': lambda: runCmd(connection,'du ' + s['para']),
                    'memory_stat': lambda: memory_stat(connection),
                    'pgrep': lambda: runCmd(connection, 'pgrep ' + s['para']),
                    'ps': lambda: runCmd(connection, 'ps ' + s['para']),
                    'ln': lambda: runCmd(connection, 'ln -s ' + s['target'] + ' ' + s['linkname']),
                    'runcmd': lambda: runCmd(connection, s['para']),
                    'getfile': lambda: getFile(connection,s['para']),
                    'download': lambda: download(connection,s['para'],s['dir']),
                    'write2file': lambda: write2File(connection,s['para'], s['data'], s['charset']),
                }[dofunc]()
            except Exception, e:
                print e
                sendDataBack(connection, 'Unknow action or account error!')
                continue

        except socket.timeout:  
            print 'time out'
        finally:
            connection.close()