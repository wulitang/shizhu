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
<h2><span>插件管理</span></h2>
<?
if($r[doad])
{
?>
<span id="prad" class="ggxt" onClick="chengstate('ad')">广告系统</span>
<ul id="itemad" style="display:none">
        <li><a href="../../tool/AdClass.php<?=$ecms_hashur[whehref]?>" target="main" class="glggfl">管理广告分类</a></li>
        <li><a href="../../tool/ListAd.php<?=$ecms_hashur[whehref]?>" target="main" class="glgg">管理广告</a></li>
</ul>
<?
}
?>
<?
if($r[dovote])
{
?>
<span id="prvote" class="tpxt" onClick="chengstate('vote')">投票系统</span>
<ul id="itemvote" style="display:none">
        <li><a href="../../tool/AddVote.php?enews=AddVote<?=$ecms_hashur['ehref']?>" target="main" class="zjtp">增加投票</a></li>
        <li><a href="../../tool/ListVote.php<?=$ecms_hashur[whehref]?>" target="main" class="gltp">管理投票</a></li>
</ul>
<?
}
?>

<?
if($r[dolink])
{
?>
<span id="prlink" class="yqljgl" onClick="chengstate('link')">友情链接管理</span>
<ul id="itemlink" style="display:none">
        <li><a href="../../tool/LinkClass.php<?=$ecms_hashur[whehref]?>" target="main" class="glyqljfl">管理友情链接分类</a></li>
        <li><a href="../../tool/ListLink.php<?=$ecms_hashur[whehref]?>" target="main" class="glyqlj">管理友情链接</a></li>
</ul>
<?
}
?>

<?
if($r[dogbook])
{
?>
<span id="prgbook" class="lybgl" onClick="chengstate('gbook')">留言板管理</span>
<ul id="itemgbook" style="display:none">
        <li><a href="../../tool/GbookClass.php<?=$ecms_hashur[whehref]?>" target="main" class="gllyfl">管理留言分类</a></li>
        <li><a href="../../tool/gbook.php<?=$ecms_hashur[whehref]?>" target="main" class="glly">管理留言</a></li>
        <li><a href="../../tool/DelMoreGbook.php<?=$ecms_hashur[whehref]?>" target="main" class="plscly">批量删除留言</a></li>
</ul>
<?
}
?>

<?
if($r[dofeedback]||$r[dofeedbackf])
{
?>
<span id="prfeedback" class="xxfkgl" onClick="chengstate('feedback')">信息反馈管理</span>
<ul id="itemfeedback" style="display:none">
		<?
		if($r[dofeedbackf])
		{
		?>
        <li><a href="../../tool/FeedbackClass.php<?=$ecms_hashur[whehref]?>" target="main" class="glfkfl">管理反馈分类</a></li>
        <li><a href="../../tool/ListFeedbackF.php<?=$ecms_hashur[whehref]?>" target="main" class="glfkzd">管理反馈字段</a></li>
        <?
		}
		if($r[dofeedback])
		{
		?>
        <li><a href="../../tool/feedback.php<?=$ecms_hashur[whehref]?>" target="main" class="glxxfk">管理信息反馈</a></li>
        <?
		}
		?>
</ul>
<?
}
?>
<?
if($r[donotcj])
{
?>
<span id="prnotcj" class="fcjcj" onClick="chengstate('notcj')">防采集插件</span>
<ul id="itemnotcj" style="display:none">
        <li><a href="../../template/NotCj.php<?=$ecms_hashur[whehref]?>" target="main" class="glfcjsjzf">管理防采集随机字符</a></li>
</ul>
<?
}
?>

<?php
$b=0;
//自定义插件菜单
$menucsql=$empire->query("select classid,classname from {$dbtbpre}enewsmenuclass where classtype=2 and (groupids='' or groupids like '%,".intval($lur[groupid]).",%') order by myorder,classid");
while($menucr=$empire->fetch($menucsql))
{
	$menujsvar='diymenu'.$menucr['classid'];
	$b=1;
?>
<span id="pr<?=$menujsvar?>" class="menu1" onClick="chengstate('<?=$menujsvar?>')"><?=$menucr['classname']?></span>
<ul id="item<?=$menujsvar?>" style="display:none">
		<?php
		$menusql=$empire->query("select menuid,menuname,menuurl,addhash from {$dbtbpre}enewsmenu where classid='$menucr[classid]' order by myorder,menuid");
		while($menur=$empire->fetch($menusql))
		{
			if(!(strstr($menur['menuurl'],'://')||substr($menur['menuurl'],0,1)=='/'))
			{
				$menur['menuurl']='../../'.$menur['menuurl'];
			}
			$menu_ecmshash='';
			if($menur['addhash'])
			{
				if(strstr($menur['menuurl'],'?'))
				{
					$menu_ecmshash=$menur['addhash']==2?$ecms_hashur['href']:$ecms_hashur['ehref'];
				}
				else
				{
					$menu_ecmshash=$menur['addhash']==2?$ecms_hashur['whhref']:$ecms_hashur['whehref'];
				}
				$menur['menuurl'].=$menu_ecmshash;
			}
		?>
        <li><a href="<?=$menur['menuurl']?>" target="main" ><?=$menur['menuname']?></a></li>
        <?php
		}
		?>
</ul>
<?php
}
?>
</div>
</body>
</html>