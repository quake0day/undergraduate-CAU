from django.conf.urls.defaults import *
from learn.views import current_datetime

urlpatterns = patterns('',
    # Example:
    # (r'^learn/', include('learn.foo.urls')),
	(r'^$','learn.add.index'),
	(r'^add/$','learn.add.index'),
	(r'^list/$','learn.list.index'),
    (r'^csv/(\w+)/$', 'learn.csv_test.output'),
	(r'^time/$',current_datetime),
	(r'^login/$','learn.login.login'),
	(r'^logout/$','learn.login.logout'),
	(r'^wiki/$','learn.wiki.views.index'),
	(r'^wiki/(?P<pagename>\w+)/$','learn.wiki.views.index'),
	(r'^wiki/(?P<pagename>\w+)/edit/$','learn.wiki.views.edit'),
	(r'^wiki/(?P<pagename>\w+)/save/$','learn.wiki.views.save'),
	(r'^address/',include('learn.address.urls')),
	(r'^address/as/',include('learn.address.urls')),

	
    # Uncomment this for admin:
     (r'^admin/', include('django.contrib.admin.urls')),
)
