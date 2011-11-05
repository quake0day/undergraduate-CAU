#coding=gb2312
from django.shortcuts import render_to_response

address = [
	{'name':'陈思','address':'ddd'},
	{'name':'张兴业','address':'bbb'}
	]
	
def index(request):
	return render_to_response('list.html',{'address':address})