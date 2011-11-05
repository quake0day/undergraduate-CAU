<?php
	setcookie("lucky","luckybaby~~");
	if($_COOKIE['lucky'] =='luckybaby~~')
	{
		echo "你已经投过票了哦！下面是排行榜～<br />";
		echo "<b>********排行榜*******</b> <br />";
		$filename="data.txt";
		$farray=file($filename);
		rsort($farray);
		//print_r($farray);
		foreach($farray as $key=>$value)
		{
			if($value !=="")
			{
			$key=$key+1;
			echo "第".$key."名的点数是 ".$value;
			echo "<br />";
			}
		}
		//$ary=array_chunk($farray,1);
		//echo "as <br />";
		//print_r($ary);
	}	
	else
	{
	session_start();
	
	$user = $_POST['user_name'];
	if(!empty($user))
	{
		$_SESSION['user'] = $user;
		$welcome = '投票已记录<a href="http://ftpchensi.4cau.cn/vote/1.php">来查看下结果吧～</a>'.$user;
	}
	$arr = array(	
	);

	$i=rand(10,99);
   array_push($arr,$i);
	array_push($arr,$user);
	array_push($arr,"\n");
	//print_r($arr);
	echo "你的点数是:".$i;
	$file = "data.txt";
	$content = implode('**',$arr);
	if(!$fp = fopen($file,'a'))
	{
		echo "fuck!!!!!";
		exit;
	}
	if(fwrite($fp,$content) === FALSE)
	{
		echo "FUCK!";
		exit;
	}
	echo "投票成功";
	fclose($fp);
	}
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=GB2312">
<title>小小投票系统，仅供娱乐～</title>
</head>
<body>
<?php
	echo $welcome;
?>

</body>

