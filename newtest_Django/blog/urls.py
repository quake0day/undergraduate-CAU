from django.conf.urls.defaults import *
from newtest.blog.views import archive

urlpatterns = patterns('',
		url(r'^$',archive),
)
