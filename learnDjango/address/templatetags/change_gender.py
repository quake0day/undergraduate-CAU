
from django import template

register = template.Library()

#@register.filter(name='change_gender')
def change_gender(value):
	if value =='M':
		return 'man'
	else:
		return 'girl'
		
register.filter('change_gender',change_gender)