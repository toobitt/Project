#!/usr/bin/python
# -*- coding: utf8 -*-
import os,base64
def getFileContent(filename):
    fp = open(filename, 'rb')
    content = fp.read() 
    fp.close()
    return content

if __name__ == '__main__':  
    import socket  
    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)  
    sock.bind(('127.0.0.1', 8089))  
    sock.listen(200)
    while True:  
        connection,address = sock.accept()  
        try:  
            connection.settimeout(5)  
            buf = connection.recv(1024)  
            if os.path.exists(buf):
                cont = getFileContent(buf)
                buf = base64.b64encode(cont)
            else:
                buf = 'NoSnap'
            connection.send(buf)  
        except socket.timeout:  
            print 'time out'  
        connection.close() 