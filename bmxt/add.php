<?
include("conn.php"); //��������
include("common.php");  //�����Ѻ���ʾ�Աຯ��

function send_mail($to, $subject = 'No subject', $body) {
$loc_host = "im286"; //���ż��������������
$smtp_acc = "0607040101"; //Smtp��֤���û���������fish1240@fishcat.com.cn������fish1240
$smtp_pass="13055301"; //Smtp��֤�����룬һ���ͬpop3����
$smtp_host="smtp.cau.edu.cn"; //SMTP��������ַ������ smtp.tom.com
$from="yueguang8@cau.edu.cn"; //������Email��ַ����ķ��������ַ
$headers = "Content-Type: text/plain; charset=\"gb2312\"\r\nContent-Transfer-Encoding: base64";
$lb="\r\n"; //linebreak

$hdr = explode($lb,$headers); //�������hdr
if($body) {$bdy = preg_replace("/^\./","..",explode($lb,$body));}//�������Body

$smtp = array(
//1��EHLO���ڴ�����220����250
array("EHLO ".$loc_host.$lb,"220,250","HELO error: "),
//2������Auth Login���ڴ�����334
array("AUTH LOGIN".$lb,"334","AUTH error:"),
//3�����;���Base64������û������ڴ�����334
array(base64_encode($smtp_acc).$lb,"334","AUTHENTIFICATION error : "),
//4�����;���Base64��������룬�ڴ�����235
array(base64_encode($smtp_pass).$lb,"235","AUTHENTIFICATION error : "));
//5������Mail From���ڴ�����250
$smtp[] = array("MAIL FROM: <".$from.">".$lb,"250","MAIL FROM error: ");
//6������Rcpt To���ڴ�����250
$smtp[] = array("RCPT TO: <".$to.">".$lb,"250","RCPT TO error: ");
//7������DATA���ڴ�����354
$smtp[] = array("DATA".$lb,"354","DATA error: ");
//8.0������From
$smtp[] = array("From: ".$from.$lb,"","");
//8.2������To
$smtp[] = array("To: ".$to.$lb,"","");
//8.1�����ͱ���
$smtp[] = array("Subject: ".$subject.$lb,"","");
//8.3����������Header����
foreach($hdr as $h) {$smtp[] = array($h.$lb,"","");}
//8.4������һ�����У�����Header����
$smtp[] = array($lb,"","");
//8.5�������ż�����
if($bdy) {foreach($bdy as $b) {$smtp[] = array(base64_encode($b.$lb).$lb,"","");}}
//9�����͡�.����ʾ�ż��������ڴ�����250
$smtp[] = array(".".$lb,"250","DATA(end)error: ");
//10������Quit���˳����ڴ�����221
$smtp[] = array("QUIT".$lb,"221","QUIT error: ");

//��smtp�������˿�
$fp = @fsockopen($smtp_host, 25);
if (!$fp) echo "<b>Error:</b> Cannot conect to ".$smtp_host."<br>";
while($result = @fgets($fp, 1024)){if(substr($result,3,1) == " ") { break; }}

$result_str="";
//����smtp�����е�����/����
foreach($smtp as $req){
//������Ϣ
@fputs($fp, $req[0]);
//�����Ҫ���շ�����������Ϣ����
if($req[1]){
//������Ϣ
while($result = @fgets($fp, 1024)){
if(substr($result,3,1) == " ") { break; }
};
if (!strstr($req[1],substr($result,0,3))){
$result_str.=$req[2].$result."<br>";
}
}
}
//�ر�����
@fclose($fp);
return $result_str;
}
//д�����ݿ�---------------------------------------
if($_POST[ok]){

if($_POST[username]==''){ErrView("���������������Ա�������ϵ��",0);exit;}
if($_POST[age]==''){ErrView("�����������䡣",0);exit;}
if($_POST[tel]==''){ErrView("�������볣�õ绰��",0);exit;}
if($_POST[address]==''){ErrView("��������ѧУ���Ƽ�רҵ����",0);exit;}
if($_POST[classid]==''){ErrView("�������뱨�����ͣ�",0);exit;}


$sql = "insert into signup (username,mail,qq,msn,sex,age,tel,address,classid,remark,intime) values ('$_POST[username]','$_POST[mail]','$_POST[qq]','$_POST[msn]','$_POST[sex]','$_POST[age]','$_POST[tel]','$_POST[address]','$_POST[classid]','$_POST[remark]',now())";

mysql_query($sql);
mysql_close();

ErrView("�����ڴ����У����Ժ�......<meta http-equiv=refresh content='2;URL=http://www.asseaspire.com/ok.html'>",1);
$subject = "������Ϣ: \n����:".$_POST[username]."\n�Ա�(1Ϊ���� 2ΪŮ��):".$_POST[sex]."\n����:".$_POST[age]."\n�绰:".$_POST[tel]."\nE-mail:".$_POST[mail]."\nQQ:".$_POST[qq]." MSN:".$_POST[msn]."\nѧУ��רҵ:".$_POST[address]."\n������Ŀ(1Ϊ���н����� 2Ϊ��ѧ�����ʵϰ 3Ϊ������ҵ��нʵϰ 4Ϊ���ʻ�����)��".$_POST[classid]."\n��ע:".$_POST[remark]."\n���������http://www.darlingtree.com/bmxt/show.php �鿴�� ����ʼ�����ϵͳ�Զ����ͣ�����ظ���";
if(send_mail('504001382@qq.com','���µı�����',$subject)=="")
{
echo "";
} else{
echo "";
}
}else{

//��ȡ��������---------------------------------------

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
<title>ѧ������ϵͳ</title>
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
        <td height="39" colspan="2" bgcolor="#000066"><p style="padding-left:25px; font-size:15px; font-weight:bold; color:#00FFFF">ѧ������ϵͳ(��ɫ*��Ϊ������)</p></td>
      </tr>
      <tr>
        <td width="22%" height="26" align="center" bgcolor="#ECE9D8" class="a1">����(<span class="STYLE1">*</span>)</td>
        <td width="78%" bgcolor="#FFFFFF">
          <input name="username" type="text" class="a2" id="username" size="10">        </td>
      </tr>
      <tr>
        <td height="26" align="center" class="a1">�Ա�(<span class="STYLE1">*</span>)</td>
        <td bgcolor="#FFFFFF">

            <input name="sex" type="radio" value="1" checked>
            ��

            <input type="radio" name="sex" value="0">
            Ů
       </td>
      </tr>
      <tr>
        <td height="26" align="center" class="a1">����(<span class="STYLE1">*</span>)</td>
        <td bgcolor="#FFFFFF">
          <input name="age" type="text" class="a2" id="age" size="6">
        </td>
      </tr>
      <tr>
        <td height="26" align="center" class="a1">�绰(<span class="STYLE1">*</span>)</td>
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
        <td height="26" align="center" class="a1">ѧУ���Ƽ�רҵ(<span class="STYLE1">*</span>)</td>
        <td bgcolor="#FFFFFF">
          <input name="address" type="text" class="a2" id="address" size="35">
        </td>
      </tr>
      <tr>
        <td height="26" align="center" class="a1">������Ŀ(<span class="STYLE1">*</span>)</td>
        <td bgcolor="#FFFFFF">
		<select name="classid" class="a2" id="classid">
		<option value="">��ѡ��</option>
		 <?=$classid?>
        </select>

       </td>
      </tr>
      <tr>
        <td height="26" align="center" class="a1">��ע</td>
        <td bgcolor="#FFFFFF">
          <textarea name="remark" cols="50" rows="6" class="a2" id="remark"></textarea>
        </td>
      </tr>
      <tr>
        <td height="26" colspan="2" align="center" bgcolor="#FFFFFF"><label>
          <input type="submit" name="ok" value="ȷ�Ϸ���">
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