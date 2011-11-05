<?php
/**
* 实现从指定url抓取页面回来，自己修改url_info可以随意抓取别的页面
* 使用方法1: 从浏览器上输入本php文件的路径可以执行
* 使用方法2： 采用命令行方式执行 比如 c:\php5\php -f c:\getpage.php
* 抓取结果： 会在当前目录下生成一个抓取结果页面test.html
*
* Author: Michael <lee@toplee.com>
* HomePage:http://www.toplee.com
*/
$url_info = array(
       'timeout' => 5,
       'hostname'   => '202.205.88.60',
       'port'    => 8888,
       'uri'    => '/gradeLnAllAction.do?type=ln&oper=qbinfo&lnxndm=2008-2009学年秋(三学期)#2008-2009学年秋(三学期)',
       );
     
$status_line = '';
$fp = openUrl($url_info,$status_line);
if ($status_line == "200 OK") {
$content = getUrlData($fp);
writeToFile('test.html',$content);
echo "Get page ok! Please open test.html to see the result!\r\n";
echo "Any question, ask Michael<lee@toplee.com> For help!\r\n";
} else {
echo "Get page failed! Please check your url_info paramters and try again!\r\n";
}

/////////////// 下面是用到的三个函数 ////////////////////
function openUrl($url_data,&$status_line)
{
if (!isset($url_data['hostname']) || !$url_data['hostname'] || !isset($url_data['uri']) || !$url_data['uri'])
       return false;
if (!isset($url_data['port']))
       $url_data['port'] = 80;
if (!isset($url_data['timeout']))
       $url_data['timeout'] = 5;
$errno = '';
$errstr = '';
if (isset($url_data['proxy_host']) && $url_data['proxy_host']) {
       if (!isset($url_data['proxy_port']))
         $url_data['proxy_port'] = 80;
       $fp = @fsockopen($url_data['proxy_host'],$url_data['proxy_port'], $errno,$errstr,$url_data['timeout']);
       $uri = 'http://'.$url_data['hostname'].':'.$url_data['port'].$url_data['uri'];
       $hostname = $url_data['hostname'];
} else {
       $fp = @fsockopen($url_data['hostname'], $url_data['port'], $errno, $errstr, $url_data['timeout']);
       $uri = $url_data['uri'];
       $hostname = $url_data['hostname'];
}
if (!$fp)
       return false;
$method = ((isset($url_data['method']) && $url_data['method']) ? strtoupper($url_data['method']) : 'GET');
if ($method == 'GET') {
       fputs($fp, "GET $uri HTTP/1.0\r\nHost: $hostname\r\nUser-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows 98)\r\nConnection: close\r\n\r\n");
} elseif ($method == 'POST') {
       $len = strlen($url_data['content']);
       $post = "POST $uri HTTP/1.0\r\nHost: $hostname\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Encoding: chunked\r\nContent-Length: $len\r\nUser-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows 98)\r\nConnection: close\r\n\r\n";
       //echo $post;
       fputs($fp, $post);
       if (isset($url_data['content']))
         fputs($fp, $url_data['content']);
}
$status_line = '';
$count = 0;
while (!feof($fp)) {
       $count ++;
       $data = trim(fgets($fp,4096));
       if ($count == 1) {
         ereg("^HTTP/[0-9].[0-9] ([^\r\n]+)", $data, $regs);
         $status_line = $regs[1];
       }
       if ($data == "") break;
}
return $fp;
}
function getUrlData(&$fp, $code=0)
{
if ( !$fp ) return '';
$data = "";
while ( !feof($fp) ) $data .= fgets($fp, 4096);
fclose($fp);
if ($code < 0)
       return base64_decode($data);
elseif ($code == 0)
       return $data;
else
       return chunk_split(base64_encode($data));
}
function writeToFile($file,$content,$mode='w')
{
$oldmask = umask(0);
$fp = fopen($file, $mode);
if (!$fp) return false;
@fwrite($fp,$content);
@fclose($fp);
@umask($oldmask);
return true;
}
?>
