#coding=gb2312
from django.shortcuts import render_to_response

address = [
	{'name':'��˼','address':'ddd'},
	{'name':'����ҵ','address':'bbb'}
	]
	
def index(request):
	return render_to_response('list.html',{'address':address})