<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>菜单</title>
<link href="../../../data/menu/menu.css" rel="stylesheet" type="text/css">
<script src="../../../data/menu/menu.js" type="text/javascript"></script>
<script src="/js/jquery.js"></script>
<script src="/js/ecmsshop_fanyi.js"></script>
<SCRIPT lanuage="JScript">
function tourl(url){
	parent.main.location.href=url;
}
</SCRIPT>
</head>
<body onLoad="initialize()">
<div class="leftside">
<h2><span>其他管理</span></h2>
<?
if($r[dobefrom]||$r[dowriter]||$r[dokey]||$r[doword])
{
?>
<span id="prnewsadmin" class="xwmxxg" onClick="chengstate('newsadmin')">新闻模型相关</span>
<ul id="itemnewsadmin" style="display:none">
		<?
		if($r[dobefrom])
		{
		?>
        <li><a href="../../NewsSys/BeFrom.php<?=$ecms_hashur[whehref]?>" target="main" class="glxxly">管理信息来源</a></li>
        <?
		}
		if($r[dowriter])
		{
		?>
        <li><a href="../../NewsSys/writer.php<?=$ecms_hashur[whehref]?>" target="main" class="glzz">管理作者</a></li>
        <?
		}
		if($r[dokey])
		{
		?>
        <li><a href="../../NewsSys/key.php<?=$ecms_hashur[whehref]?>" target="main" class="glnrgjz">管理内容关键字</a></li>
        <?
		}
		if($r[doword])
		{
		?>
        <li><a href="../../NewsSys/word.php<?=$ecms_hashur[whehref]?>" target="main" class="glglzf">管理过滤字符</a></li>
        <?
		}
		?>
</ul>
<?
}
?>

<?
if($r[dodownurl]||$r[dodeldownrecord]||$r[dodownerror]||$r[dorepdownpath]||$r[doplayer])
{
?>
<span id="prdownadmin" class="xzmxxg" onClick="chengstate('downadmin')">下载模型相关</span>
<ul id="itemdownadmin" style="display:none">
		<?
		if($r[dodownurl])
		{
		?>
        <li><a href="../../DownSys/url.php<?=$ecms_hashur[whehref]?>" target="main" class="gldzqz">管理地址前缀</a></li>
        <?
		}
		if($r[dodeldownrecord])
		{
		?>
        <li><a href="../../DownSys/DelDownRecord.php<?=$ecms_hashur[whehref]?>" target="main" class="scxzjl">删除下载记录</a></li>
        <?
		}
		if($r[dodownerror])
		{
		?>
        <li><a href="../../DownSys/ListError.php<?=$ecms_hashur[whehref]?>" target="main" class="glcwbg">管理错误报告</a></li>
        <?
		}
		if($r[dorepdownpath])
		{
		?>
        <li><a href="../../DownSys/RepDownLevel.php<?=$ecms_hashur[whehref]?>" target="main" class="plthdzqx">批量替换地址权限</a></li>
        <?
		}
		if($r[doplayer])
		{
		?>
        <li><a href="../../DownSys/player.php<?=$ecms_hashur[whehref]?>" target="main" class="bfqgl">播放器管理</a></li>
        <?
		}
		?>
</ul>
<?
}
?>

<?
if($r[dopay])
{
?>
<span id="prpay" class="zxzf" onClick="chengstate('pay')">在线支付</span>
<ul id="itempay" style="display:none">
        <li><a href="../../pay/SetPayFen.php<?=$ecms_hashur[whehref]?>" target="main" class="zfcspz">支付参数配置</a></li>
        <li><a href="../../pay/PayApi.php<?=$ecms_hashur[whehref]?>" target="main" class="glzfjk">管理支付接口</a></li>
        <li><a href="../../pay/ListPayRecord.php<?=$ecms_hashur[whehref]?>" target="main" class="glzfjl">管理支付记录</a></li>
</ul>
<?
}
?>

<?
if($r[dopicnews])
{
?>
<span id="prpicnews" class="tpxxgl" onClick="chengstate('picnews')">图片信息管理</span>
<ul id="itempicnews" style="display:none">
        <li><a href="../../NewsSys/PicClass.php<?=$ecms_hashur[whehref]?>" target="main" class="gltpxxfl">管理图片信息分类</a></li>
        <li><a href="../../NewsSys/ListPicNews.php<?=$ecms_hashur[whehref]?>" target="main" class="gltpxx">管理图片信息</a></li>
</ul>
<?
}
?>
</div>
</body>
</html>