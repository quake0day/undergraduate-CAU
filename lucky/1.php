<?php
	setcookie("lucky","luckybaby~~");
	if($_COOKIE['lucky'] =='luckybaby~~')
	{
		echo "���Ѿ�Ͷ��Ʊ��Ŷ�����������а�<br />";
		echo "<b>********���а�*******</b> <br />";
		$filename="data.txt";
		$farray=file($filename);
		rsort($farray);
		//print_r($farray);
		foreach($farray as $key=>$value)
		{
			if($value !=="")
			{
			$key=$key+1;
			echo "��".$key."���ĵ����� ".$value;
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
		$welcome = 'ͶƱ�Ѽ�¼<a href="http://ftpchensi.4cau.cn/vote/1.php">���鿴�½���ɡ�</a>'.$user;
	}
	$arr = array(	
	);

	$i=rand(10,99);
   array_push($arr,$i);
	array_push($arr,$user);
	array_push($arr,"\n");
	//print_r($arr);
	echo "��ĵ�����:".$i;
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
	echo "ͶƱ�ɹ�";
	fclose($fp);
	}
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=GB2312">
<title>ССͶƱϵͳ���������֡�</title>
</head>
<body>
<?php
	echo $welcome;
?>

</body>

