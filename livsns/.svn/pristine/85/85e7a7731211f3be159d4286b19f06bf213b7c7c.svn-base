#!/usr/bin/python
# -*- coding: utf8 -*-
import os,sys, time
import pexpect 
if __name__ == '__main__':  
    args = {'i':'', 'd':''}
    for arg in sys.argv:
        if arg.startswith('-'):
            arg = arg[1:]
            k = arg[0:1]
            v = arg[1:].strip()
            if v:
                args[k] = v
    if not os.path.isfile(args['i']):
        print args['i'],' is not exists'
        sys.exit()
    if not os.path.exists(args['d']):
        print args['d'],' is not exists'
        sys.exit()
    child = pexpect.spawn('openssl pkcs12 -clcerts -nokeys -out ' + args['d'] + 'cert.pem -in ' + args['i'])
    child.expect ('Enter Import Password:')
    child.sendline ('')
    time.sleep(1)
    child = pexpect.spawn('openssl pkcs12 -nocerts -out ' + args['d'] + 'key.pem -in ' + args['i'])
    child.expect ('Enter Import Password:')
    child.sendline ('')
    child.expect ('Enter PEM pass phrase:')
    child.sendline ('HOGE')
    child.expect ('Verifying - Enter PEM pass phrase:')
    child.sendline ('HOGE')
    time.sleep(1)
    child = pexpect.spawn('openssl rsa -in ' + args['d'] + 'key.pem -out ' + args['d'] + 'key.unencrypted.pem')
    child.expect ('Enter pass phrase for ' + args['d'] + 'key.pem:')
    child.sendline ('HOGE')
    time.sleep(1)
    print 'cat ' + args['d'] + 'key.unencrypted.pem ' + args['d'] + 'cert.pem > ' + args['d'] + 'push.pems'
    os.popen('cat ' + args['d'] + 'key.unencrypted.pem ' + args['d'] + 'cert.pem > ' + args['d'] + 'push.pems')
    child.sendline ('')
    sys.exit