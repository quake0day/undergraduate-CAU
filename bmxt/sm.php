<?php
/*����
if(send_mail('quake@vip.qq.com','���ű���11','��������11')=="")
{
echo "���ͳɹ���<br>";
} else{
echo "����ʧ�ܣ�<br>";
}
$url = "http://mail.163.com";
$url2 = "http://mail.126.com";
$lines_array = file($url);
$lines_string = implode('', $lines_array);
eregi("(.*)", $lines_string, $head);
echo $head[0];
echo $head[1];
echo $head[2];*/

function send_mail($to, $subject = 'No subject', $body) {
$loc_host = "im286"; //���ż��������������
$smtp_acc = "quake��vip.qq.com"; //Smtp��֤���û���������fish1240@fishcat.com.cn������fish1240
$smtp_pass="chensilara4"; //Smtp��֤�����룬һ���ͬpop3����
$smtp_host="smtp.qq.com"; //SMTP��������ַ������ smtp.tom.com
$from="quake@vip.qq.com"; //������Email��ַ����ķ��������ַ
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

?>