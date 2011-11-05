#coding=gb2312
from django.shortcuts import render_to_response
from django.template import loader,Context

address = [
	{'name':'陈思','address':'ddd'},
	{'name':'张兴业','address':'bbb'}
	]
	
def index(request,filename):
	response = HttpResponse(mimetype='text/csv')
	response['Content-Disposition'] = 'attachment;filename=%s.csv' %filename
	
	t=loader.get_template('csv.html')
	c=Context({
		'data':address,
	})
	response.write(t.render(c))
	return response