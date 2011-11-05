from django.db import models

# Create your models here.

class Address(models.Model):
	name = models.CharField('Name',maxlength=20,unique=True)
	gender = models.CharField('SEx',choices=(('M','MALE'),('F','FEMALE')), 		  	 maxlength=1,radio_admin=True)
	telphone = models.CharField('TEL',maxlength=20)
	mobile = models.CharField('CELLPHONE',maxlength=11)
	room = models.CharField('ROOM',maxlength=10)
	
	def __str__(self):
		return self.name
	
	class Admin : pass
	class Meta: ordering=['name']
	