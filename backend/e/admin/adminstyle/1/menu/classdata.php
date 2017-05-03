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
function Openpl(){
    window.open('../../openpage/AdminPage.php?leftfile=<?=urlencode('../pl/PlNav.php'.$ecms_hashur['whehref'])?>&mainfile=<?=urlencode('../pl/PlMain.php'.$ecms_hashur['whehref'])?>&title=<?=urlencode('管理评论')?><?=$ecms_hashur['ehref']?>','');
	}
function Openfj(){
    window.open('../../openpage/AdminPage.php?leftfile=<?=urlencode('../file/FileNav.php'.$ecms_hashur['whehref'])?>&title=<?=urlencode('管理附件')?><?=$ecms_hashur['ehref']?>','');
	}
</SCRIPT>
</head>
<body onLoad="initialize()">
<div class="leftside">
<h2><span>栏目管理</span></h2>
<span id="prcinfo" class="lmxggl" onClick="chengstate('cinfo')">信息相关管理</span>
<ul id="itemcinfo" style="display:none">
        <li><a href="../../ListAllInfo.php<?=$ecms_hashur[whehref]?>" target="main" class="glxx">管理信息</a></li>
        <li><a href="../../ListAllInfo.php?ecmscheck=1<?=$ecms_hashur['ehref']?>" target="main" class="shxx">审核信息</a></li>
        <li><a href="../../workflow/ListWfInfo.php<?=$ecms_hashur[whehref]?>" target="main" class="qfxx">签发信息</a></li>
		<?
		if($r[dopl])
		{
		?>
        <li><a href="javascript:void(0)" onclick="Openpl()" target="main" class="glpl">管理评论</a></li>
		<?
		}
		?>
		
		
        <li><a href="../../sp/UpdateSp.php<?=$ecms_hashur[whehref]?>" target="main" class="gxsp">更新碎片</a></li>
        <li><a href="../../special/UpdateZt.php<?=$ecms_hashur[whehref]?>" target="main" class="gxzt">更新专题</a></li>
        <li><a href="../../info/InfoMain.php<?=$ecms_hashur[whehref]?>" target="main" class="sjtj">数据统计</a></li>
        <li><a href="../../infotop.php<?=$ecms_hashur[whehref]?>" target="main" class="phtj">排行统计</a></li>
</ul>
<?
if($r[doclass]||$r[dosetmclass]||$r[doclassf])
{
?>
<span id="prclassdata" onClick="chengstate('classdata')" class="lmgl">栏目管理</span>
<ul id="itemclassdata" style="display:none">
		<?
		if($r[doclass])
		{
		?>
        <li><a href="../../ListClass.php<?=$ecms_hashur[whehref]?>" target="main" class="gllm">管理栏目</a></li>
        <li><a href="../../ListPageClass.php<?=$ecms_hashur[whehref]?>" target="main" class="gllmfy">管理栏目(分页)</a></li>
		<?
		}
		if($r[doclassf])
		{
		?>
        <li><a href="../../info/ListClassF.php<?=$ecms_hashur[whehref]?>" target="main" class="lmzdyzd">栏目自定义字段</a></li>
		<?
		}
		if($r[dosetmclass])
		{
		?>
        <li><a href="../../SetMoreClass.php<?=$ecms_hashur[whehref]?>" target="main" class="plszlmsx">批量设置栏目属性</a></li>
		<?
		}
		?>
</ul>
<?
}
?>

<?
if($r[dozt]||$r[doztf])
{
?>
<span id="przt" onClick="chengstate('zt')" class="ztgl">专题管理</span>
<ul id="itemzt" style="display:none">
		<?php
		if($r[dozt])
		{
		?>
        <li><a href="../../special/ListZtClass.php<?=$ecms_hashur[whehref]?>" target="main" class="glztfl">管理专题分类</a></li>
        <li><a href="../../special/ListZt.php<?=$ecms_hashur[whehref]?>" target="main" class="glzt">管理专题</a></li>
		<?php
		}
		if($r[doztf])
		{
		?>
        <li><a href="../../special/ListZtF.php<?=$ecms_hashur[whehref]?>" target="main" class="ztzdyzd">专题自定义字段</a></li>
        <?php
		}
		?>
</ul>
<?
}
?>

<?
if($r[doinfotype])
{
?>
<span id="prinfotype" onClick="chengstate('infotype')" class="btflgl">标题分类管理</span>
<ul id="iteminfotype" style="display:none">
        <li><a href="../../info/AddInfoType.php?enews=AddInfoType<?=$ecms_hashur['ehref']?>" target="main" class="zjbtfl">增加标题分类</a></li>
        <li><a href="../../info/InfoType.php<?=$ecms_hashur[whehref]?>" target="main" class="glbtfl">管理标题分类</a></li>
</ul>
<?
}
?>

<?
if($r[dosp])
{
?>
<span id="prsp" onClick="chengstate('sp')" class="spgl">碎片管理</span>
<ul id="itemsp" style="display:none">
        <li><a href="../../sp/ListSpClass.php<?=$ecms_hashur[whehref]?>" target="main" class="glspfl">管理碎片分类</a></li>
        <li><a href="../../sp/ListSp.php<?=$ecms_hashur[whehref]?>" target="main" class="glsp">管理碎片</a></li>
</ul>
<?
}
?>

<?
if($r[douserpage])
{
?>
<span id="pruserpage" onClick="chengstate('userpage')" class="zdyym">自定义页面</span>
<ul id="itemuserpage" style="display:none">
        <li><a href="../../template/PageClass.php<?=$ecms_hashur[whehref]?>" target="main" class="glzdyymfl">管理自定义页面分类</a></li>
        <li><a href="../../template/AddPage.php?enews=AddUserpage<?=$ecms_hashur['ehref']?>" target="main" class="zjzdyym">增加自定义页面</a></li>
        <li><a href="../../template/ListPage.php<?=$ecms_hashur[whehref]?>" target="main" class="glzdyym">管理自定义页面</a></li>
</ul>
<?
}
?>

<?
if($r[douserlist])
{
?>
<span id="pruserlist" onClick="chengstate('userlist')" class="zdylb">自定义列表</span>
<ul id="itemuserlist" style="display:none">
        <li><a href="../../other/UserlistClass.php<?=$ecms_hashur[whehref]?>" target="main" class="glzdylbfl">管理自定义列表分类</a></li>
        <li><a href="../../other/AddUserlist.php?enews=AddUserlist<?=$ecms_hashur['ehref']?>" target="main" class="zjzdylb">增加自定义列表</a></li>
        <li><a href="../../other/ListUserlist.php<?=$ecms_hashur[whehref]?>" target="main" class="glzdylb">管理自定义列表</a></li>
</ul>
<?
}
?>

<?
if($r[douserjs])
{
?>
<span id="pruserjs" onClick="chengstate('userjs')" class="zdyjs">自定义JS</span>
<ul id="itemuserjs" style="display:none">
        <li><a href="../../other/UserjsClass.php<?=$ecms_hashur[whehref]?>" target="main" class="glzdyjsfl">管理自定义JS分类</a></li>
        <li><a href="../../other/AddUserjs.php?enews=AddUserjs<?=$ecms_hashur['ehref']?>" target="main" class="zjzdyjs">增加自定义JS</a></li>
        <li><a href="../../other/ListUserjs.php<?=$ecms_hashur[whehref]?>" target="main" class="glzdyjs">管理自定义JS</a></li>
</ul>
<?
}
?>

<?
if($r[dotags])
{
?>
<span id="prtags" onClick="chengstate('tags')" class="tagsgl">TAGS管理</span>
<ul id="itemtags" style="display:none">
        <li><a href="../../tags/SetTags.php<?=$ecms_hashur[whehref]?>" target="main" class="sztagscs">设置TAGS参数</a></li>
        <li><a href="../../tags/TagsClass.php<?=$ecms_hashur[whehref]?>" target="main" class="gltagsfl">管理TAGS分类</a></li>
        <li><a href="../../tags/ListTags.php<?=$ecms_hashur[whehref]?>" target="main" class="gltags">管理TAGS</a></li>
</ul>
<?
}
?>

<?
if($r[dofile])
{
?>
<span id="prfile" onClick="chengstate('file')" class="fjgl">附件管理</span>
<ul id="itemfile" style="display:none">
        <li><a href="javascript:void(0)" onclick="Openfj()" target="main" class="glfj">管理附件</a></li>
</ul>
<?
}
?>

<?
if($r[docj])
{
?>
<span id="prcj" onClick="chengstate('cj')" class="cjgl">采集管理</span>
<ul id="itemcj" style="display:none">
        <li><a href="../../AddInfoC.php<?=$ecms_hashur[whehref]?>" target="main" class="zjcjjd">增加采集节点</a></li>
        <li><a href="../../ListInfoClass.php<?=$ecms_hashur[whehref]?>" target="main" class="glcjjd">管理采集节点</a></li>
        <li><a href="../../ListPageInfoClass.php<?=$ecms_hashur[whehref]?>" target="main" class="glcjjdfy">管理采集节点(分页)</a></li>
</ul>
<?
}
?>

<?
if($r[dosearchall])
{
?>
<span id="prsearchall" onClick="chengstate('searchall')" class="qzqwss">全站全文搜索</span>
<ul id="itemsearchall" style="display:none">
        <li><a href="../../searchall/SetSearchAll.php<?=$ecms_hashur[whehref]?>" target="main" class="qzsssz">全站搜索设置</a></li>
        <li><a href="../../searchall/ListSearchLoadTb.php<?=$ecms_hashur[whehref]?>" target="main" class="qlsssjy">管理搜索数据源</a></li>
        <li><a href="../../searchall/ClearSearchAll.php<?=$ecms_hashur[whehref]?>" target="main" class="qlsssj">清理搜索数据</a></li>
</ul>
<?
}
?>

<?
if($r[dowap])
{
?>
<span id="prwap" onClick="chengstate('wap')" class="wapgl">WAP管理</span>
<ul id="itemwap" style="display:none">
        <li><a href="../../other/SetWap.php<?=$ecms_hashur[whehref]?>" target="main" class="wapsz">WAP设置</a></li>
        <li><a href="../../other/WapStyle.php<?=$ecms_hashur[whehref]?>" target="main" class="glwapmb">管理WAP模板</a></li>
</ul>
<?
}
?>

<?
if($r[domovenews]||$r[doinfodoc]||$r[dodelinfodata]||$r[dorepnewstext]||$r[dototaldata]||$r[dosearchkey]||$r[dovotemod])
{
?>
<span id="prcother" onClick="chengstate('cother')" class="qtxg">其他相关</span>
<ul id="itemcother" style="display:none">
		<?
		if($r[dototaldata])
		{
		?>
        <li><a href="../../TotalData.php<?=$ecms_hashur[whehref]?>" target="main" class="tjxxsj">统计信息数据</a></li>
        <li><a href="../../user/UserTotal.php<?=$ecms_hashur[whehref]?>" target="main" class="yhfbtj">用户发布统计</a></li>
		<?
		}
		if($r[dosearchkey])
		{
		?>
        <li><a href="../../SearchKey.php<?=$ecms_hashur[whehref]?>" target="main" class="glssgjz">管理搜索关键字</a></li>
        <?
		}
		if($r[dorepnewstext])
		{
		?>
        <li><a href="../../db/RepNewstext.php<?=$ecms_hashur[whehref]?>" target="main" class="plthgjz">批量替换字段值</a></li>
        <?
		}
		if($r[domovenews])
		{
		?>
        <li><a href="../../MoveClassNews.php<?=$ecms_hashur[whehref]?>" target="main" class="plzyxx">批量转移信息</a></li>
        <?
		}
		if($r[doinfodoc])
		{
		?>
        <li><a href="../../InfoDoc.php<?=$ecms_hashur[whehref]?>" target="main" class="xxplgd">信息批量归档</a></li>
        <?
		}
		if($r[dodelinfodata])
		{
		?>
        <li><a href="../../db/DelData.php<?=$ecms_hashur[whehref]?>" target="main" class="plscxx">批量删除信息</a></li>
        <?
		}
		if($r[dovotemod])
		{
		?>
        <li><a href="../../other/ListVoteMod.php<?=$ecms_hashur[whehref]?>" target="main" class="glystp">管理预设投票</a></li>
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