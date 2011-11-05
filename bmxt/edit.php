<?
include("conn.php"); //数据链接
include("common.php");  //引入友好提示自编函数

//更新数据库---------------------------------------
if($_POST[ok]){

if($_POST[id]==''){ErrView("·非法操作！",0);exit;}
if($_POST[username]==''){ErrView("·请输入姓名！",0);exit;}
if($_POST[age]==''){ErrView("·请输入年龄！",0);exit;}
if($_POST[tel]==''){ErrView("·请输入电话！",0);exit;}
if($_POST[address]==''){ErrView("·请输入地址！",0);exit;}
if($_POST[classid]==''){ErrView("·请输入报名类型！",0);exit;}
if($_POST[remark]==''){ErrView("·请输入备注！",0);exit;}



$sql = "UPDATE signup SET username = '$_POST[username]',sex = '$_POST[sex]',age = '$_POST[age]',tel = '$_POST[tel]',address = '$_POST[address]',classid = '$_POST[classid]',remark = '$_POST[remark]' WHERE id ='$_POST[id]' ";
mysql_query($sql);
mysql_close();

ErrView("·用户信息成功！<meta http-equiv=refresh content='1;URL=show.php'>",1);

}else{
//读取用户信息---------------------------------------

if($_GET[id]==''){ErrView("·非法操作！",0);exit;}

$usql = "select * from signup where id = '$_GET[id]'";
$uresult = mysql_query($usql);
$urs = mysql_fetch_array($uresult);


//读取报名类型---------------------------------------

$sql = "select * from class order by id asc";
$result = mysql_query($sql);

while($rs = mysql_fetch_object($result))
{
if($rs->id==$urs[classid]){
$classid .= "<option value=\"$rs->id\" selected>$rs->classname</option>\n";
}else{
$classid .= "<option value=\"$rs->id\">$rs->classname</option>\n";
}

}

?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>学生报名系统</title>
<style type="text/css">
<!--
.a1 {
	font-size: 12px;
	color:#FFFFFF;
	background-color:#006699
	
}
.a2{ 
background-color:#E8E8E8;
BORDER: #999999 1px solid;
}
body {
	background-color: #CCCCCC;
	margin: 0px;


}
body,td,th {
	font-size: 12px;
}
-->
</style>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle"><form name="myform" method="post" action="edit.php"><table width="500" border="0" cellpadding="3" cellspacing="1" bgcolor="#333333">
      <tr>
        <td height="39" colspan="2" bgcolor="#000066"><p style="padding-left:25px; font-size:15px; font-weight:bold; color:#00FFFF">学生报名系统-【修改信息】</p></td>
      </tr>
      <tr>
        <td width="22%" height="26" align="center" class="a1">姓名</td>
        <td width="78%" bgcolor="#FFFFFF">
          <input name="username" type="text" class="a2" id="username" value="<?=$urs[username]?>" size="10">        </td>
      </tr>
      <tr>
        <td height="26" align="center" class="a1">性别</td>
        <td bgcolor="#FFFFFF">
        
            <input name="sex" type="radio" value="1" <?if($urs[sex]==1){echo "checked";}?>>
            男
        
            <input type="radio" name="sex" value="0" <?if($urs[sex]==0){echo "checked";}?>>
            女
       </td>
      </tr>
      <tr>
        <td height="26" align="center" class="a1">年龄</td>
        <td bgcolor="#FFFFFF">
          <input name="age" type="text" class="a2" id="age" value="<?=$urs[age]?>" size="6">
        </td>
      </tr>
      <tr>
        <td height="26" align="center" class="a1">电话</td>
        <td bgcolor="#FFFFFF">
          <input name="tel" type="text" class="a2" id="tel" value="<?=$urs[tel]?>" size="15">
        </td>
      </tr>
      <tr>
        <td height="26" align="center" class="a1">地址</td>
        <td bgcolor="#FFFFFF">
          <input name="address" type="text" class="a2" id="address" value="<?=$urs[address]?>" size="35">
        </td>
      </tr>
      <tr>
        <td height="26" align="center" class="a1">报名类型</td>
        <td bgcolor="#FFFFFF">
		<select name="classid" class="a2" id="classid">
		<option value="">请选择</option>
		 <?=$classid?>
        </select>
          
       </td>
      </tr>
      <tr>
        <td height="26" align="center" class="a1">备注</td>
        <td bgcolor="#FFFFFF">
          <textarea name="remark" cols="50" rows="6" class="a2" id="remark"><?=$urs[remark]?></textarea>
        </td>
      </tr>
      <tr>
        <td height="26" colspan="2" align="center" bgcolor="#FFFFFF"><label>
          <input name="id" type="hidden" id="id" value="<?=$urs[id]?>">
          <input type="submit" name="ok" value="确认发布">
        </label></td>
      </tr>
    </table>
      
      </form>
    </td>
  </tr>
</table>
</body>
</html>
<?
mysql_close();
}
?>
