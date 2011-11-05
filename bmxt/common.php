<?
function ErrView($Str,$Num){

		 echo "<style type=\"text/css\">";
		 echo "body,td,th {font-size: 12px;}";
		 echo "a{font-size: 12px;text-decoration: none;}";
		 echo "</style>";
	     echo "<title>提示信息</title>";
	     echo "<table width='100%' height='100%' border=0 cellspacing=0 cellpadding=0 class=table><tr><td>";
	     echo "<table align=center border=0 cellpadding=0 width='400' style='border:1px #CCCCCC solid' bgcolor='#FFFFFF'>";
	     echo "<tr><td height=22 bgcolor=#EFEFEF>&nbsp;<b>友情提示：</b></td></tr>";
	     echo "<tr><td height=150>";
	     echo "<table width='80%' height='100%' border=0 cellspacing=0 cellpadding=0 align=center><tr><td>";
	     echo $Str;
	     echo "</td></tr></table>";
		 echo "";
         switch ($Num){
           case 0:
	    	 echo "</td></tr><tr><td align=center>[<a Onclick=\"javascript:history.back()\" style='cursor:pointer'>返回上一页</a>]　[<a Onclick=\"javascript:window.close()\" style='cursor:pointer'>关闭</a>]</td></tr>";
			 echo "</table>";
	         echo "</td></tr></table>";
			 
			 break;
			 
           case 1:
	    	 echo "</td></tr><tr><td align=center>[<a Onclick=\"javascript:window.close()\" style='cursor:pointer'>关闭</a>]</td></tr>";
			 echo "</table>";
	         echo "</td></tr></table>";
			 
			 break;
			 
	      }
	     
		 }

function showclass($Str){

$sql = "select classname from class where id ='$Str'";
$result = mysql_query($sql);
$rs = mysql_fetch_array($result);
return $rs[0];
}
		 
?>