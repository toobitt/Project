#!/usr/bin/python
# -*- coding: utf8 -*-
import os, sys
import shutil
import socket
import hashlib
import getpass

def md5(s):
    md5 = hashlib.md5()
    md5.update(s)
    return md5.hexdigest()

if __name__ == '__main__': 
    try:
        if sys.argv[1] != 'install':
            print 'Please run "python ' + sys.argv[0] + ' install" to install'
            sys.exit();
    except Exception, e:
        print e
        print 'Please run "python ' + sys.argv[0] + ' install" to install'
        sys.exit();
    args = {'prefix' : '/usr/local/monitor/'}
    for arg in sys.argv:
        if arg.startswith('--'):
            arg = arg[2:]
            arg = arg.split('=')
            try:
                k = arg[0]
                v = arg[1]
                if v:
                    args[k] = v
            except Exception, e:
                print 'parameter error!'
                sys.exit();
    if args['prefix'][:-1] != '/':
        args['prefix'] = args['prefix'] + '/'
    if os.path.exists(args['prefix']):
        print args['prefix'] + ' is exists.'
        sys.exit()
    if not os.path.isfile('./hogeMonitor.py') or not os.path.isfile('./allow.cmd') or not os.path.isfile('./allow.file'):
        print 'file lacked';
        sys.exit()
    os.mkdir(args['prefix'])
    print 'copying ./hogeMonitor.py to ' + args['prefix'] + '...'
    shutil.copy('./hogeMonitor.py', args['prefix'])
    print 'copying ./allow.cmd to ' + args['prefix'] + '...'
    shutil.copy('./allow.cmd', args['prefix'])
    print 'copying ./allow.file to ' + args['prefix'] + '...'
    shutil.copy('./allow.file', args['prefix'])
    print args['prefix'] + 'hogeMonitor.py'
    if not os.path.isfile(args['prefix'] + 'hogeMonitor.py') and not os.path.isfile(args['prefix'] + 'allow.cmd') and not os.path.isfile(args['prefix'] + 'allow.file'):
        print 'Install failed';
        rmcmd = 'rm -Rf ' + args['prefix']
        os.popen(rmcmd)
        sys.exit()
    os.chmod(args['prefix'] + 'hogeMonitor.py', 0755)
    #ip = socket.gethostbyname(socket.gethostname())
    
    ip = raw_input('Please set server IP:')
    while (ip == ''):
        ip = raw_input('Please set server IP:')
    autocmd = 'echo "\nnohup %shogeMonitor.py -h%s > /dev/null &" >> /etc/rc.local' % (args['prefix'], ip)
    os.popen(autocmd)

    print 'Install success.'
    print 'Please create user for connect it.'
    username = raw_input('username:')
    while (username == ''):
        username = raw_input('username:')
    password = ''
    password2 = '*'
    while (password != password2):
        password = getpass.getpass('password:')
        password2 = getpass.getpass('confirm password:')
    content = username + '=' + md5(password) + "\n"
    filen = args['prefix'] + 'passwd'
    chose = 'N'
    try:    
        fp = open(filen, 'w')
        fp.write(content)
        fp.close()
        chose = raw_input('Run it now?[Y/N]:')
    except Exception, e:
        print 'User create failed.'
        print 'Please create user by cmd %sshogeMonitor.py createuser -uaccount -ppassword' % (args['prefix'])
    
    if chose == 'Y':
        runcmd = 'nohup %shogeMonitor.py -h%s> /dev/null &' % (args['prefix'], ip)
        os.popen(runcmd)
        print '%shogeMonitor.py is run now' % (args['prefix'])
    else:
        print 'Run it with nohup %shogeMonitor.py -h%s> /dev/null &' % (args['prefix'], ip)