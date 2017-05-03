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
	<h2><span>系统设置</span></h2>
<?
if($r[dopublic]||$r[dofirewall]||$r[dosetsafe]||$r[dopubvar])
{
?>
<span id="prsetting" onClick="chengstate('setting')" class="xtsz">系统设置</span>
    <ul id="itemsetting" style="display:none">
		<?
		if($r[dopublic])
		{
		?>
    	<li><a href="../../SetEnews.php<?=$ecms_hashur[whehref]?>" target="main" class="xtcssz">系统参数设置</a></li>
        <li><a href="../../pub/SetRewrite.php<?=$ecms_hashur[whehref]?>" target="main" class="wjtsz">伪静态参数设置</a></li>
		<?
		}
		if($r[dopubvar])
		{
		?>
    	<li><a href="../../pub/ListPubVar.php<?=$ecms_hashur[whehref]?>" target="main" class="kzbl">扩展变量</a></li>
		<?
		}
		if($r[dosetsafe])
		{
		?>
    	<li><a href="../../pub/SetSafe.php<?=$ecms_hashur[whehref]?>" target="main" class="aqcs">安全参数配置</a></li>
		<?
		}
		if($r[dofirewall])
		{
		?>
        <li><a href="../../pub/SetFirewall.php<?=$ecms_hashur[whehref]?>" target="main" class="fhq">网站防火墙</a></li>
		<?
		}
		?>
      </ul>
<?
}
?>
<?
if($r[dochangedata]||$r[dopostdata])
{
?>
<span id="prchangedata" onClick="chengstate('changedata')" class="sjgx">数据更新</span>
<ul id="itemchangedata" style="display:none">
		<?
		if($r[dochangedata])
		{
		?>
        <li><a href="../../ReHtml/ChangeData.php<?=$ecms_hashur[whehref]?>" target="main" class="sjgxzx">数据更新中心</a></li>
        <li><a href="../../ReHtml/ReInfoUrl.php<?=$ecms_hashur[whehref]?>" target="main" class="gxxxy">更新信息页地址</a></li>
		<li><a href="../../ReHtml/DoUpdateData.php<?=$ecms_hashur['whehref']?>" target="main" class="sjzl">数据整理</a></li>
		<?
		}
		if($r[dopostdata])
		{
		?>
        <li><a href="../../PostUrlData.php<?=$ecms_hashur[whehref]?>" target="main" class="ycfb">远程发布</a></li>
		<?
		}
		?>
        </ul>
<?
}
?>

<?
if($r[dof]||$r[dom]||$r[dotable])
{
?>
<span id="prtable" onClick="chengstate('table')" class="sjbymx">数据表与系统模型</span>
<ul id="itemtable" style="display:none">
        <li><a href="../../db/AddTable.php?enews=AddTable<?=$ecms_hashur['ehref']?>" target="main" class="xjsjb">新建数据表</a></li>
        <li><a href="../../db/ListTable.php<?=$ecms_hashur[whehref]?>" target="main" class="glsjb">管理数据表</a></li>
</ul>
<?
}
?>

<?
if($r[dodo]||$r[dotask])
{
?>
<span id="prtask" onClick="chengstate('task')" class="jhrw">计划任务</span>
<ul id="itemtask" style="display:none">
		<?
		if($r[dodo])
		{
		?>
        <li><a href="../../ListDo.php<?=$ecms_hashur[whehref]?>" target="main" class="glsxrw">管理刷新任务</a></li>
		<?
		}
		if($r[dotask])
		{
		?>
        <li><a href="../../other/ListTask.php<?=$ecms_hashur[whehref]?>" target="main" class="gljhrw">管理计划任务</a></li>
		<?
		}
		?>
</ul>
<?
}
?>

<?
if($r[doworkflow])
{
?>
<span id="prwf" onClick="chengstate('wf')" class="gzl">工作流</span>
<ul id="itemwf" style="display:none">
        <li><a href="../../workflow/AddWf.php?enews=AddWorkflow<?=$ecms_hashur['ehref']?>" target="main" class="zjgzl">增加工作流</a></li>
        <li><a href="../../workflow/ListWf.php<?=$ecms_hashur[whehref]?>" target="main" class="glgzl">管理工作流</a></li>
</ul>
<?
}
?>

<?
if($r[doyh])
{
?>
<span id="pryh" onClick="chengstate('yh')" class="yhfa">优化方案</span>
<ul id="itemyh" style="display:none">
        <li><a href="../../db/ListYh.php<?=$ecms_hashur['whehref']?>" target="main" class="glyhfa">管理优化方案</a></li>
</ul>
<?
}
?>

<?
if($r[domoreport])
{
?>
<span id="prmport" onClick="chengstate('mport')" class="wzdfwd">网站多访问端</span>
<ul id="itemmport" style="display:none">
        <li><a href="../../moreport/ListMoreport.php<?=$ecms_hashur['whehref']?>" target="main" class="glwzfwd">管理网站访问端</a></li>
</ul>
<?
}
?>
<?
if($r[domenu])
{
?>
<span id="prmenu" onClick="chengstate('menu')" class="kzcd">扩展菜单</span>
<ul id="itemmenu" style="display:none">
        <li><a href="../../other/MenuClass.php<?=$ecms_hashur[whehref]?>" target="main" class="glkzcd">管理菜单</a></li>
</ul>
<?
}
?>

<?
if($r[dodbdata]||$r[doexecsql])
{
?>
<span id="prbak" onClick="chengstate('bak')" class="bfyhf">备份与恢复数据</span>
<ul id="itembak" style="display:none">
		<?
		if($r[dodbdata])
		{
		?>
        <li><a href="../../ebak/ChangeDb.php<?=$ecms_hashur[whehref]?>" target="main" class="bfsj">备份数据</a></li>
        <li><a href="../../ebak/ReData.php<?=$ecms_hashur[whehref]?>" target="main" class="hfsj">恢复数据</a></li>
        <li><a href="../../ebak/ChangePath.php<?=$ecms_hashur[whehref]?>" target="main" class="glbfml">管理备份目录</a></li>
		<?
		}
		if($r[doexecsql])
		{
		?>
        <li><a href="../../db/DoSql.php<?=$ecms_hashur[whehref]?>" target="main" class="zxsql">执行SQL语句</a></li>
		<?
		}
		?>
</ul>
<?
}
?>

</div>
</body>
</html>