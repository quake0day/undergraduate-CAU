<?
include("conn.php"); //数据链接
include("common.php");  //引入友好提示自编函数

function send_mail($to, $subject = 'No subject', $body) {
$loc_host = "im286"; //发信计算机名，可随意
$smtp_acc = "0607040101"; //Smtp认证的用户名，类似fish1240@fishcat.com.cn，或者fish1240
$smtp_pass="13055301"; //Smtp认证的密码，一般等同pop3密码
$smtp_host="smtp.cau.edu.cn"; //SMTP服务器地址，类似 smtp.tom.com
$from="yueguang8@cau.edu.cn"; //发信人Email地址，你的发信信箱地址
$headers = "Content-Type: text/plain; charset=\"gb2312\"\r\nContent-Transfer-Encoding: base64";
$lb="\r\n"; //linebreak

$hdr = explode($lb,$headers); //解析后的hdr
if($body) {$bdy = preg_replace("/^\./","..",explode($lb,$body));}//解析后的Body

$smtp = array(
//1、EHLO，期待返回220或者250
array("EHLO ".$loc_host.$lb,"220,250","HELO error: "),
//2、发送Auth Login，期待返回334
array("AUTH LOGIN".$lb,"334","AUTH error:"),
//3、发送经过Base64编码的用户名，期待返回334
array(base64_encode($smtp_acc).$lb,"334","AUTHENTIFICATION error : "),
//4、发送经过Base64编码的密码，期待返回235
array(base64_encode($smtp_pass).$lb,"235","AUTHENTIFICATION error : "));
//5、发送Mail From，期待返回250
$smtp[] = array("MAIL FROM: <".$from.">".$lb,"250","MAIL FROM error: ");
//6、发送Rcpt To。期待返回250
$smtp[] = array("RCPT TO: <".$to.">".$lb,"250","RCPT TO error: ");
//7、发送DATA，期待返回354
$smtp[] = array("DATA".$lb,"354","DATA error: ");
//8.0、发送From
$smtp[] = array("From: ".$from.$lb,"","");
//8.2、发送To
$smtp[] = array("To: ".$to.$lb,"","");
//8.1、发送标题
$smtp[] = array("Subject: ".$subject.$lb,"","");
//8.3、发送其他Header内容
foreach($hdr as $h) {$smtp[] = array($h.$lb,"","");}
//8.4、发送一个空行，结束Header发送
$smtp[] = array($lb,"","");
//8.5、发送信件主体
if($bdy) {foreach($bdy as $b) {$smtp[] = array(base64_encode($b.$lb).$lb,"","");}}
//9、发送“.”表示信件结束，期待返回250
$smtp[] = array(".".$lb,"250","DATA(end)error: ");
//10、发送Quit，退出，期待返回221
$smtp[] = array("QUIT".$lb,"221","QUIT error: ");

//打开smtp服务器端口
$fp = @fsockopen($smtp_host, 25);
if (!$fp) echo "<b>Error:</b> Cannot conect to ".$smtp_host."<br>";
while($result = @fgets($fp, 1024)){if(substr($result,3,1) == " ") { break; }}

$result_str="";
//发送smtp数组中的命令/数据
foreach($smtp as $req){
//发送信息
@fputs($fp, $req[0]);
//如果需要接收服务器返回信息，则
if($req[1]){
//接收信息
while($result = @fgets($fp, 1024)){
if(substr($result,3,1) == " ") { break; }
};
if (!strstr($req[1],substr($result,0,3))){
$result_str.=$req[2].$result."<br>";
}
}
}
//关闭连接
@fclose($fp);
return $result_str;
}
//写入数据库---------------------------------------
if($_POST[ok]){

if($_POST[username]==''){ErrView("·请输入姓名，以便我们联系您",0);exit;}
if($_POST[age]==''){ErrView("·请输入年龄。",0);exit;}
if($_POST[tel]==''){ErrView("·请输入常用电话。",0);exit;}
if($_POST[address]==''){ErrView("·请输入学校名称及专业名称",0);exit;}
if($_POST[classid]==''){ErrView("·请输入报名类型！",0);exit;}


$sql = "insert into signup (username,mail,qq,msn,sex,age,tel,address,classid,remark,intime) values ('$_POST[username]','$_POST[mail]','$_POST[qq]','$_POST[msn]','$_POST[sex]','$_POST[age]','$_POST[tel]','$_POST[address]','$_POST[classid]','$_POST[remark]',now())";

mysql_query($sql);
mysql_close();

ErrView("·正在处理中，请稍候......<meta http-equiv=refresh content='2;URL=http://www.asseaspire.com/ok.html'>",1);
$subject = "报名信息: \n姓名:".$_POST[username]."\n性别(1为男性 2为女性):".$_POST[sex]."\n年龄:".$_POST[age]."\n电话:".$_POST[tel]."\nE-mail:".$_POST[mail]."\nQQ:".$_POST[qq]." MSN:".$_POST[msn]."\n学校及专业:".$_POST[address]."\n欲报项目(1为高中交换生 2为大学生暑假实习 3为美国企业带薪实习 4为国际互惠生)：".$_POST[classid]."\n备注:".$_POST[remark]."\n详情请访问http://www.darlingtree.com/bmxt/show.php 查看！ 这封邮件是由系统自动发送，请勿回复！";
if(send_mail('504001382@qq.com','有新的报名！',$subject)=="")
{
echo "";
} else{
echo "";
}
}else{

//读取报名类型---------------------------------------

$sql = "select * from class order by id asc";
$result = mysql_query($sql);

while($rs = mysql_fetch_object($result))
{
$classid .= "<option value=\"$rs->id\">$rs->classname</option>\n";
}
mysql_close();
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
.STYLE1 {color: #FF0000}
-->
</style>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle"><form name="myform" method="post" action="add.php"><table width="500" border="0" cellpadding="3" cellspacing="1" bgcolor="#333333">
      <tr>
        <td height="39" colspan="2" bgcolor="#000066"><p style="padding-left:25px; font-size:15px; font-weight:bold; color:#00FFFF">学生报名系统(红色*处为必填项)</p></td>
      </tr>
      <tr>
        <td width="22%" height="26" align="center" bgcolor="#ECE9D8" class="a1">姓名(<span class="STYLE1">*</span>)</td>
        <td width="78%" bgcolor="#FFFFFF">
          <input name="username" type="text" class="a2" id="username" size="10">        </td>
      </tr>
      <tr>
        <td height="26" align="center" class="a1">性别(<span class="STYLE1">*</span>)</td>
        <td bgcolor="#FFFFFF">

            <input name="sex" type="radio" value="1" checked>
            男

            <input type="radio" name="sex" value="0">
            女
       </td>
      </tr>
      <tr>
        <td height="26" align="center" class="a1">年龄(<span class="STYLE1">*</span>)</td>
        <td bgcolor="#FFFFFF">
          <input name="age" type="text" class="a2" id="age" size="6">
        </td>
      </tr>
      <tr>
        <td height="26" align="center" class="a1">电话(<span class="STYLE1">*</span>)</td>
        <td bgcolor="#FFFFFF">
          <input name="tel" type="text" class="a2" id="tel" size="15">
        </td>
      </tr>
            <tr>
        <td height="26" align="center" class="a1">E-mail(<span class="STYLE1">*</span>)</td>
        <td bgcolor="#FFFFFF">
          <input name="mail" type="text" class="a2" id="mail" size="15">
        </td>
      </tr>
            <tr>
        <td height="26" align="center" class="a1">QQ</td>
        <td bgcolor="#FFFFFF">
          <input name="qq" type="text" class="a2" id="qq" size="15">
        </td>
      </tr>
            <tr>
        <td height="26" align="center" class="a1">MSN</td>
        <td bgcolor="#FFFFFF">
          <input name="msn" type="text" class="a2" id="msn" size="15">
        </td>
      </tr>
      <tr>
        <td height="26" align="center" class="a1">学校名称及专业(<span class="STYLE1">*</span>)</td>
        <td bgcolor="#FFFFFF">
          <input name="address" type="text" class="a2" id="address" size="35">
        </td>
      </tr>
      <tr>
        <td height="26" align="center" class="a1">欲报项目(<span class="STYLE1">*</span>)</td>
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
          <textarea name="remark" cols="50" rows="6" class="a2" id="remark"></textarea>
        </td>
      </tr>
      <tr>
        <td height="26" colspan="2" align="center" bgcolor="#FFFFFF"><label>
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
<?}?>