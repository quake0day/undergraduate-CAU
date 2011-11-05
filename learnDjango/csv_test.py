#coding=gb2312
from django.shortcuts import render_to_response
from django.template import loader,Context

address = [
	{'name':'��˼','address':'ddd'},
	{'name':'����ҵ','address':'bbb'}
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