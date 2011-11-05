#!/usr/local/bin/python
import sys, os

# 添加自定义Python路径
sys.path.insert(0, "/usr/local/bin/python")

# 切换到工程目录（可选）
os.chdir("/www/darlingtree.com/htdocs/newtest")

# 设定DJANGO_SETTINGS_MODULE环境变量
os.environ['DJANGO_SETTINGS_MODULE'] = "newtest.settings"

from django.core.servers.fastcgi import runfastcgi
runfastcgi(method="threaded", daemonize="false") 
