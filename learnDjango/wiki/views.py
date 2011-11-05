# Create your views here.
#coding = utf-8
from learn.wiki.models import Wiki
from django.template import loader,Context
from django.http import HttpResponse,HttpResponseRedirect
from django.shortcuts import render_to_response

def index(request, pagename=""):
	if pagename:
#		 pages=Wiki.objects.get_list(pagename__exact=pagename)
		pages=Wiki.objects.filter(pagename=pagename)
		if pages:
			return process('wiki/page.html',pages[0])
		else:
			return render_to_response('wiki/edit.html',{'pagename':pagename})
	else:
#		 page = Wiki.objects.get_object(pagename__exact='FrontPage')
		page = Wiki.objects.get(pagename='FrontPage')
		return process('wiki/page.html',page)
		
def edit(request,pagename):
#	 page = Wiki.objects.get_object(pagename__exact=pagename)
	page = Wiki.objects.get(pagename=pagename)
	return render_to_response('wiki/edit.html',{'pagename':pagename,'content':page.content})
def save(request,pagename):
	content = request.POST['content']
#	 pages = Wiki.objects.get_list(pagename__exact=pagename)
	pages = Wiki.objects.filter(pagename=pagename)
	if pages:
		pages[0].content = content
		pages[0].save()
	else:
		page = Wiki(pagename=pagename,content=content)
		page.save()
	return HttpResponseRedirect("/wiki/%s"% pagename)

import re

r = re.compile(r'\b(([A-Z]+[a-z]){2,})\b')
def process(template,page):
	t= loader.get_template(template)
	content = r.sub(r'<a href ="/wiki/\1">\1</a>',page.content)
	content = re.sub(r'[\n\r]+','<br>',content)
	c = Context({'pagename':page.pagename,'content':content})
	return HttpResponse(t.render(c))
	