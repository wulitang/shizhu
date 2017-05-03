<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>信息提示</title>
<Meta name="Author" Content="yecha，yecha@qq.com"> 
<meta name="copyright" content="© http://www.yecha.com" />
<link href="<?=$public_r[newsurl]?>e/message/images/mes.css" rel="stylesheet" type="text/css">
<?php
if(!$noautourl)
{
?>
<SCRIPT language=javascript>
var secs=3;//3秒
for(i=1;i<=secs;i++) 
{ window.setTimeout("update(" + i + ")", i * 1000);} 
function update(num) 
{ 
if(num == secs) 
{ <?=$gotourl_js?>; } 
else 
{ } 
}
</SCRIPT>
<?
}
?>
</head>

<body>
	<div class="tip">
    	<div class="closebox"><a href="<?=$gotourl?>" title="点击关闭并跳转"></a></div>
    	<div class="message"><?=$error?><a href="<?=$gotourl?>">如果您的浏览器没有自动跳转,请点击跳转......</a></div>
        <div class="otherlink"><div class="fr"><a href="javascript:history.go(-1);" class="return">返回上页</a> <a href="<?=$gotourl?>" class="next">快速跳转</a></div>快捷操作: <a href="/" class="index">返回首页</a> <a href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&email=dwMdDw5HR0A3BgZZFBga" class="error" target="_blank">错误提交</a></div>
    </div>
</body>
</html>