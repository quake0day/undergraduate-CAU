<?php
	$upload_path = $_SERVER['DOCUMENT_ROOT']."/gpa/upload/";
	$dest_file = $upload_path."1.txt";
	//echo $_FILES['myfile']['type'];
	$aaa = "2.txt";
	//echo $_FILES['myfile']['name'];
	if($_FILES['myfile']['type'] != 'text/html'){echo "sorry 请上传txt格式的";}
	else
	{
		if(move_uploaded_file($_FILES['myfile']['tmp_name'],$dest_file))
		{
				echo "上传成功！点击下面的按钮开始计算吧！";
				if(!copy( $dest_file,$aaa))
				{
					echo "fail";
					exit;
				}
		}
		else
		{
			echo "sorry";
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>GPA学分计算</title>
<script type="text/javascript">
var xmlHttp;
var data;
var where=0;
var word;
var end;
var change;
var courseNum=0;
k= new Array(10);
scores = new Array;
score1 = new Array;
function createXMLHttpRequest(){
	if(window.ActiveXObject){
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	else if(window.XMLHttpRequest){
		xmlHttp = new XMLHttpRequest();
	}
}

function startRequest(){
	createXMLHttpRequest();
	try{
		xmlHttp.onreadystatechange = handleStateChange;
		xmlHttp.open("GET", "upload/1.txt", true);
		//xmlHttp.open("GET", "get.php", true);
		xmlHttp.send(null);	
	}catch(exception){
		alert("您要访问的资源不存在!");
	}
}

function handleStateChange(){	
	if(xmlHttp.readyState == 4){		
		if (xmlHttp.status == 200 || xmlHttp.status == 0){
			// 显示返回结果
			//alert("responseText's value: " + xmlHttp.responseText);
			//alert(encodeURI("中等"));
			getNum()
		  getscore1();
			getscores();
			cal();
			
			
		}
	}
}
function getscore1()
{
	h = new Array;
	var q = 0;
	var t;
	var ecode;
	var cj;//保存成绩字符串
	data = xmlHttp.responseText;
	//k[0] = data.indexOf("#ffffff",k[i]);
	for(i=0;i<courseNum;i++)
	{
	h[i] = data.indexOf("eft\">",h[i]+1);
	//alert(h[i]);
	//alert(cj);
	cj = data.substring(h[i]+5,h[i]+9);
	//alert(cj);
	score1[i] = parseFloat(cj);
	//alert(q);
	h[i+1]=h[i];
	t=isNaN(score1[i]);
	if(t == true)
	{
	  ecode = encodeURI(cj);
	  ecode = ecode.substring(ecode.length-3,ecode.length)+ecode.substring(9,15)+ecode.substring(0,2);
	  //alert(ecode);
	  switch (ecode){
	  	//如果是优秀则
	  	case "%BD%EF%BF%E":
	  			score1[i] = 90;
	  			break;
	  	case "F&n%EF%BF%E":
	  			score1[i] = 90;
	  			break;
	  	// 如果是良好则
	  	case "BD&%EF%BF%E":
	  			score1[i] = 85;
	  			break;
	  	case "BFn%BF%EF%7":
	  		  score1[i] = 85;
	  		  break;
	  	// 如果是中等的话...还没写呢 再说
	  	case "BFn%EF%BF%E":
	  			score1[i] = 75;
	  			break;
	  	case "BD&%D0%B5%E":
	  			score1[i] = 75;
	  			break;
	  	case "%EF%BF%BF%EF%BF%BF%EF%BF%BFn":
	  			score1[i] = 75;
	  			break;
	  	default:
	  			score1[i] = 0;
	  			break;
	  	}
	}
	//alert(score1[i]);

}
}
function getscores()
{
	 var i=0;
   var j=0;
   k = new Array;
	    data = xmlHttp.responseText;
	    for (j=0; j < courseNum; j++)
	   {
	    k[0] =data.indexOf("#FFFFF",k[i]);
			for (i=0; i<5; i++)
			{
			k[i] = data.indexOf("td_n",k[i]+1);
			//alert(k[i]);
			k[i+1]=k[i];
		  }

		  		//word = data.charAt(k[i]+5); //从td_n的t开始往后数5个就是学分值了
      		word = data.substring(k[i]+10,k[i]+50);
      		//alert(word+"对应编号"+j);
      		change = parseFloat(word);
      		scores[j]= change;
      		//alert(scores[j]);
		  	
		  }
}
function getNum()
{
	var getN;
	var ss;
	var NUM;
	var i;
	var f;
	k = new Array;
	t = new Array;
	c = new Array;
	data = xmlHttp.responseText;
	end = data.length;
	for( i = 0 ; i<10; i++)
	{
	k[i] = data.indexOf("#F2F2F2",k[i]);
	t[i] = data.indexOf("</td>",k[i]);
	if(t[i]+100 > end)
	{ss = data.substring(t[i]-13,t[i]);
	c=ss.split(";");
	if(c[0] == '')
	{
		//alert("a");
		NUM = parseFloat(c[1]);
	}
else 
	{
		NUM = parseFloat(c[0]);
		}
	k[i+1] = k[i]+1;
	courseNum+=NUM;
	//alert(courseNum);
		break;}
else{
	ss = data.substring(t[i]-13,t[i]);
	c=ss.split(";");
	if(c[0] == '')
	{
		//alert("a");
		NUM = parseFloat(c[1]);
	}
else 
	{
		NUM = parseFloat(c[0]);
		}
	k[i+1] = k[i]+1;
	courseNum+=NUM;
	//alert(courseNum);
}
}
}
function cal()
{
	var i;
	var WholeScore=0;
	var calScore=0;
	var FinalS=0;
	score2 = new Array;
	for(i=0;i < courseNum;i++)
	{
		if(score1[i] >= 90)
		{
			score2[i]=4.0;
			//alert("cool");
		}
	else if(score1[i]>=85 && score1[i] < 90)
		{
			score2[i]= 3.7;
			//alert("good");
			
		}
	else if(score1[i]>=82 && score1[i] < 85)
		{
			score2[i]=3.3;
		}
			else if(score1[i]>=78 && score1[i] < 82)
		{
			score2[i]=3.0;
		}
			else if(score1[i]>=75 && score1[i] < 78)
		{
			score2[i]=2.7;
		}
			else if(score1[i]>=72 && score1[i] < 75)
		{
			score2[i]=2.3;
		}
			else if(score1[i]>=68 && score1[i] < 72)
		{
			score2[i]=2.0;
		}
					else if(score1[i]>=64 && score1[i] < 68)
		{
			score2[i]=1.5;
		}
					else if(score1[i]>=60 && score1[i] < 64)
		{
			score2[i]=1.0;
		}
					else if(score1[i] < 60 && score1[i] >=0)
		{
			score2[i]=0.0;
		}
	//alert(score2[i]);
	}
	

for(i =0;i <courseNum;i++)
{
	//alert(" 编号："+i);
	//alert("学分数"+scores[i]+" 我的成绩"+score1[i]+" 编号："+i);
		calScore+=scores[i]*score2[i];
	WholeScore+=scores[i];
}
		
		//alert(calScore);
	  //alert(WholeScore);

		FinalS = calScore/WholeScore;
		alert("下面由陈思为你计算GPA..");
		alert("嗯 1+1 =2 2+2=4 4+4 =9 9+9=81.....");
		alert("好了！");
		alert("你的学分绩点是"+FinalS);
		alert("感谢使用！如果计算错误请给我quake@vip.qq.com发信报告BUG(需要同时附上你的成绩文件) 我会不断完善这个小程序！")
	
}

</script>
</head>
<body>
	<div>
		<input type="button" value="开始计算！"
				onclick="startRequest();" />
	</div>
</body>
    </html>