from django.db import models   
  
class Publisher(models.Model):   
    name = models.CharField(maxlength=30)   
    address = models.CharField(maxlength=50)   
    city = models.CharField(maxlength=60)   
    state_province = models.CharField(maxlength=30)   
    country = models.CharField(maxlength=50)   
    website = models.URLField()   
  
class Author(models.Model):   
    salutation = models.CharField(maxlength=10)   
    first_name = models.CharField(maxlength=30)   
    last_name = models.CharField(maxlength=40)   
    email = models.EmailField()   
   
  
class Book(models.Model):   
    title = models.CharField(maxlength=100)   
    authors = models.ManyToManyField(Author)   
    publisher = models.ForeignKey(Publisher)   
    publication_date = models.DateField()  
	
	def __str__(self):
        return self.title
	
	class Admin : 
		pass