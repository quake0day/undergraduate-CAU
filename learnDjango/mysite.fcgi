#!/usr/local/bin/python 
import sys, os 

# ����Զ���Python·�� 
sys.path.insert(0, "/usr/local/bin/python") 

# �л�������Ŀ¼����ѡ�� 
os.chdir("/www/99181621.host/darlingtree_com/htdocs/learn/") 

# �趨DJANGO_SETTINGS_MODULE�������� 
os.environ['DJANGO_SETTINGS_MODULE'] = "address.settings" 

from django.core.servers.fastcgi import runfastcgi 
runfastcgi(method="threaded", daemonize="false") 