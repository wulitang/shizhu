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
<title>扩展项目</title>
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
<h2><span>扩展项目</span></h2>
<?php
$b=0;
//自定义扩展菜单
$menucsql=$empire->query("select classid,classname from {$dbtbpre}enewsmenuclass where classtype=3 and (groupids='' or groupids like '%,".intval($lur[groupid]).",%') order by myorder,classid");
while($menucr=$empire->fetch($menucsql))
{
	$menujsvar='diymenu'.$menucr['classid'];
	$b=1;
?>
<span id="pr<?=$menujsvar?>" class="xwmxxg" onClick="chengstate('<?=$menujsvar?>')"><?=$menucr['classname']?></span>
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
        <li><a href="<?=$menur['menuurl']?>" target="main" class="glglzf"><?=$menur['menuname']?></a></li>
<?php
}
?>
</ul>
  <?php
}
//没菜单
if(!$b)
{
	$notrecordword="您还未添加扩展菜单,<br><a href='../../other/MenuClass.php".$ecms_hashur['whehref']."' target='main'><u><b>点击这里</b></u></a>进行添加操作";
	echo"<tr><td>$notrecordword</td></tr>";
}
?>
</div>
</body>
</html>