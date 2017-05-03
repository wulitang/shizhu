<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
//特殊提示
if($GLOBALS['ecmsadderrorurl'])//增加信息
{
	$error='<br>'.$error.'<br><br><a href="'.$GLOBALS['ecmsadderrorurl'].'">返回信息列表</a>';
}

//风格
$loginadminstyleid=EcmsReturnAdminStyle();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>信息提示</title>
<link href="<?=$a?>adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<?php
if(!$noautourl)
{
?>
<SCRIPT language=javascript>
var secs=2;//3秒
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
<div class="httipbox">
	<div class="tipleft">
    	<span><?=$error?></span>
        <em>如果您的浏览器没有自动跳转，请点击右侧</em>
    </div>
    <div class="tipright"><a href="<?=$gotourl?>" title="点击立刻跳转"></a></div>
</div>
</body>
</html>