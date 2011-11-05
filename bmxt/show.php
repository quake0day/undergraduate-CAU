<?
include("conn.php"); //数据链接
include("common.php");  //引入友好提示自编函数


switch($_GET[Action]){
case "del"://删除
$sql = "delete from signup where id = '$_GET[id]'";
mysql_query($sql);
ErrView("·信息删除成功！<meta http-equiv=refresh content='1;URL=show.php'>",1);
break;

default:  //读取数据

$sql = "select id,username,mail,qq,msn,sex,age,tel,address,classid,remark,intime from signup order by id desc";
$result = mysql_query($sql);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>报名学生名单</title>
<style type="text/css">
<!--
body,td,th {
	font-size: 12px;
}
a {
font-size: 12px;
text-decoration: none;
}
body {
	margin: 0px;
	background-color: #CCCCCC;
}
.a1{ background-color:#DAF0F5;}
.a2{ background-color:#ffffff;}
-->
</style></head>

<body>
<table width="600" height="40" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="right" style="padding-top:8px;"><a href="add.php" style="font-size:14px">我要报名>></a>&nbsp;</td>
  </tr>
</table>
<table width="600" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#666666">
  <tr>
    <td height="39" bgcolor="#000066"><p style="padding-left:25px; font-size:15px; font-weight:bold; color:#00FFFF">学生名单</p></td>
  </tr>
  
  <?
  $i=0;
  while($rs = mysql_fetch_array($result)){
  ?>
  
  <tr>
    <td height="26" class="<?if($i==0){echo "a1";}else{echo "a2";}?>"><table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="20%" height="25" align="center" bgcolor="#FFFFFF">姓名</td>
        <td width="35%" bgcolor="#FFFFFF">&nbsp;<?=$rs[1]?></td>
        <td width="20%" align="center" bgcolor="#FFFFFF">性别</td>
        <td width="25%" bgcolor="#FFFFFF">&nbsp;<?if($rs[5]==1){echo "男";}else{echo "女";}?></td>
      </tr>
      <tr>
        <td height="25" align="center" bgcolor="#FFFFFF">年龄<BR></td>
        <td bgcolor="#FFFFFF">&nbsp;<?=$rs[6]?></td>
        <td align="center" bgcolor="#FFFFFF">电话</td>
        <td bgcolor="#FFFFFF">&nbsp;<?=$rs[7]?></td>
      </tr>
      <tr>
        <td height="25" align="center" bgcolor="#FFFFFF">地址</td>
        <td bgcolor="#FFFFFF">&nbsp;<?=$rs[8]?></td>
        <td align="center" bgcolor="#FFFFFF">报名类型</td>
        <td bgcolor="#FFFFFF">&nbsp;<?=showclass($rs[9])?></td>
      </tr>
      <tr>
      	
          <td height="25" align="center" bgcolor="#FFFFFF">E-mail</td>
        <td bgcolor="#FFFFFF">&nbsp;<?=$rs[2]?></td>
        <td align="center" bgcolor="#FFFFFF">QQ/MSN</td>
        <td bgcolor="#FFFFFF">&nbsp;<?=$rs[3]?><br /><?=$rs[4]?></td>
                
      	</tr>
      <tr>
        <td height="25" align="center" bgcolor="#FFFFFF">备注</td>
        <td colspan="3" bgcolor="#FFFFFF">&nbsp;<?=$rs[10]?></td>
        </tr>
      <tr>
        <td height="25" align="center" bgcolor="#FFFFFF">日期</td>
        <td colspan="3" bgcolor="#FFFFFF">&nbsp;<?=$rs[11]?></td>
        </tr>
      <tr>
        <td height="25" colspan="4" align="right" bgcolor="#FFFFFF">【<a href="?Action=del&id=<?=$rs[0]?>">删除</a>】&nbsp;【<a href="edit.php?id=<?=$rs[0]?>">修改</a>】&nbsp;</td>
        </tr>
      
    </table></td>
  </tr>
 


<?
$i++;
if($i>1){$i=0;}
}
mysql_close();
?> 
  
</table>
</body>
</html>
<?}?>
