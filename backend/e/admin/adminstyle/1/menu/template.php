<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
//模板组
$gid=(int)$_GET['gid'];
if(!$gid)
{
	if($ecms_config['sets']['deftempid'])
	{
		$gid=$ecms_config['sets']['deftempid'];
	}
	elseif($public_r['deftempid'])
	{
		$gid=$public_r['deftempid'];
	}
	else
	{
		$gid=1;
	}
}
$tempgroup="";
$tgname="";
$tgsql=$empire->query("select gid,gname,isdefault from {$dbtbpre}enewstempgroup order by gid");
while($tgr=$empire->fetch($tgsql))
{
	$tgselect="";
	if($tgr['gid']==$gid)
	{
		$tgname=$tgr['gname'];
		$tgselect=" selected";
	}
	$tempgroup.="<option value='".$tgr['gid']."'".$tgselect.">".$tgr['gname']."</option>";
}
if(empty($tgname))
{
	printerror("ErrorUrl","");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>菜单</title>
<link href="../../../data/menu/menu.css" rel="stylesheet" type="text/css">
<link href="/skins/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/yecha.js"></script>
<script src="../../../data/menu/menu.js" type="text/javascript"></script>
<SCRIPT lanuage="JScript">
function tourl(url){
	parent.main.location.href=url;
}
</SCRIPT>
<script type="text/javascript">
function xz_bg(obj)
{
    var a=document.getElementById("mbmenu").getElementsByTagName("a");
    for(var i=0;i<a.length;i++)
    {
        a[i].className="tyggmb";
    }
    obj.className="tyggmbon";
}
function yufa(){
art.dialog.open('template/EnewsBq.php<?=$ecms_hashur[whehref]?>',
    {title: '铁鹰cms标签语法',lock: true,opacity: 0.5, width: 600, height: 450,cancelVal: '关闭',cancel: true});
}
function biaoqian(){
art.dialog.open('template/MakeBq.php<?=$ecms_hashur[whehref]?>',
    {title: '自动生成标签',lock: true,opacity: 0.5, width: 600, height: 480,cancelVal: '关闭',cancel: true});
}
function admindl(){
art.dialog.open('template/MakeBq.php<?=$ecms_hashur[whehref]?>',
    {title: '自动生成标签',lock: true,opacity: 0.5, width: 600, height: 540,cancelVal: '关闭',cancel: true});
}
</script>
</head>
<body onLoad="initialize()">
<div class="leftside">
	<h2><span>
    <select name="selecttempgroup" onChange="self.location.href='left.php?<?=$ecms_hashur[ehref]?>&ecms=template&gid='+this.options[this.selectedIndex].value">
	<?=$tempgroup?>
	</select></span></h2>
<span class="ckbqyf" onclick="yufa()">查看标签语法</span>
<span class="zdscbq" onclick="biaoqian()">自动生成标签</span>
<?php
if($ecms_config['esafe']['openeditdttemp']&&$r[dodttemp])
{
?>
<span class="dtymmb" onClick="window.open('../../openpage/AdminPage.php?leftfile=<?=urlencode('../template/dttemppageleft.php'.$ecms_hashur['whehref'])?>&title=<?=urlencode('动态页面模板管理')?><?=$ecms_hashur['ehref']?>','dttemppage','');">动态页面模板管理</span>
<?php
}
?>
<h2><span>模板管理</span></h2>
<div id="mbmenu">
<?
if($r[dotemplate])
{
?>
<span id="prindextemp" onClick="chengstate('indextemp')" class="mb">首页模板</span>
<ul id="itemindextemp" style="display:none">
        <li><a href="../../template/EditPublicTemp.php?tname=indextemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main"  class="tyggmb" onClick="xz_bg(this)">首页模板</a></li>
        <li><a href="../../template/ListIndexpage.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理首页方案</a></li>
</ul>
<?
}
?>

<?
if($r[dotemplate])
{
?>
<span id="prclasstemp" onClick="chengstate('classtemp')" class="fmmb">封面模板</span>
<ul id="itemclasstemp" style="display:none">
        <li><a href="../../template/ClassTempClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理封面模板分类</a></li>
        <li><a href="../../template/ListClasstemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理封面模板</a></li>
</ul>
<?
}
?>

<?
if($r[dotemplate])
{
?>
<span id="prlisttemp" class="lbmb" onClick="chengstate('listtemp')">列表模板</span>
<ul id="itemlisttemp" style="display:none">
        <li><a href="../../template/ListtempClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理列表模板分类</a></li>
        <li><a href="../../template/ListListtemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理列表模板</a></li>
</ul>
<?
}
?>

<?
if($r[dotemplate])
{
?>
<span id="prnewstemp" class="nrmb" onClick="chengstate('newstemp')">内容模板</span>
<ul id="itemnewstemp" style="display:none">
        <li><a href="../../template/NewstempClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理内容模板分类</a></li>
        <li><a href="../../template/ListNewstemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理内容模板</a></li>
</ul>
<?
}
?>

<?
if($r[dotemplate])
{
?>
<span id="prbqtemp" class="bqmb" onClick="chengstate('bqtemp')">标签模板</span>
<ul id="itembqtemp" style="display:none">
        <li><a href="../../template/BqtempClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理标签模板分类</a></li>
        <li><a href="../../template/ListBqtemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理标签模板</a></li>
</ul>
<?
}
?>
<?
if($r[dotempvar])
{
?>
<span id="prtempvar" class="ggmbbl" onClick="chengstate('tempvar')">公共模板变量</span>
<ul id="itemtempvar" style="display:none">
        <li><a href="../../template/TempvarClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理模板变量分类</a></li>
        <li><a href="../../template/ListTempvar.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理模板变量</a></li>
</ul>
<?
}
?>
<?
if($r[dotemplate])
{
?>
<span id="prpubtemp" class="ggmb" onClick="chengstate('pubtemp')">公共模板</span>
<ul id="itempubtemp" style="display:none">
        <li><a href="../../template/EditPublicTemp.php?tname=cptemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">控制面板模板</a></li>
        <li><a href="../../template/EditPublicTemp.php?tname=schalltemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">全站搜索模板</a></li>
        <li><a href="../../template/EditPublicTemp.php?tname=searchformtemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">高级搜索表单模板</a></li>
        <li><a href="../../template/EditPublicTemp.php?tname=searchformjs&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">横向搜索JS模板</a></li>
        <li><a href="../../template/EditPublicTemp.php?tname=searchformjs1&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">纵向搜索JS模板</a></li>
        <li><a href="../../template/EditPublicTemp.php?tname=otherlinktemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">相关信息模板</a></li>
        <li><a href="../../template/EditPublicTemp.php?tname=gbooktemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">留言板模板</a></li>
        <li><a href="../../template/EditPublicTemp.php?tname=pljstemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">评论JS调用模板</a></li>
        <li><a href="../../template/EditPublicTemp.php?tname=downpagetemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">最终下载页模板</a></li>
        <li><a href="../../template/EditPublicTemp.php?tname=downsofttemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">下载地址模板</a></li>
        <li><a href="../../template/EditPublicTemp.php?tname=onlinemovietemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">在线播放地址模板</a></li>
        <li><a href="../../template/EditPublicTemp.php?tname=listpagetemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">列表分页模板</a></li>
        <li><a href="../../template/EditPublicTemp.php?tname=loginiframe&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">登陆状态模板</a></li>
        <li><a href="../../template/EditPublicTemp.php?tname=loginjstemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">JS调用登陆模板</a></li>
</ul>
<?
}
?>

<?
if($r[dotemplate])
{
?>
<span id="prjstemp" class="jsmb" onClick="chengstate('jstemp')">JS模板</span>
<ul id="itemjstemp" style="display:none">
        <li><a href="../../template/JsTempClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理JS模板分类</a></li>
        <li><a href="../../template/ListJstemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理JS模板</a></li>
</ul>
<?
}
?>
<?
if($r[dotemplate])
{
?>
<span id="prsearchtemp" class="ssmb" onClick="chengstate('searchtemp')">搜索模板</span>
<ul id="itemsearchtemp" style="display:none">
        <li><a href="../../template/SearchtempClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理搜索模板分类</a></li>
        <li><a href="../../template/ListSearchtemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理搜索模板</a></li>
</ul>
<?
}
?>
<?
if($r[dotemplate])
{
?>
<span id="prpltemp" class="pllbmb" onClick="chengstate('pltemp')">评论列表模板</span>
<ul id="itempltemp" style="display:none">
        <li><a href="../../template/AddPltemp.php?enews=AddPlTemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">增加评论模板</a></li>
        <li><a href="../../template/ListPltemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理评论模板</a></li>
</ul>
<?
}
?>

<?
if($r[dotemplate])
{
?>
<span id="prprinttemp" class="dymb" onClick="chengstate('printtemp')">打印模板</span>
<ul id="itemprinttemp" style="display:none">
        <li><a href="../../template/AddPrinttemp.php?enews=AddPrintTemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">增加打印模板</a></li>
        <li><a href="../../template/ListPrinttemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理打印模板</a></li>
</ul>
<?
}
?>
<?
if($r[dotemplate])
{
?>
<span id="pruserpagetemp" class="zdyymmb" onClick="chengstate('userpagetemp')">自定义页面模板</span>
<ul id="itemuserpagetemp" style="display:none">
        <li><a href="../../template/AddPagetemp.php?enews=AddPagetemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">增加自定义页面模板</a></li>
        <li><a href="../../template/ListPagetemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理自定义页面模板</a></li>
</ul>
<?
}
?>
<?
if($r[dotemplate])
{
?>
<span id="prvotetemp" class="tpmb" onClick="chengstate('votetemp')">投票模板</span>
<ul id="itemvotetemp" style="display:none">
        <li><a href="../../template/AddVotetemp.php?enews=AddVoteTemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">增加投票模板</a></li>
        <li><a href="../../template/ListVotetemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理投票模板</a></li>
</ul>
<?
}
?>

<?
if($r[dobq])
{
?>
<span id="prbq" class="bqmb" onClick="chengstate('bq')">标签</span>
<ul id="itembq" style="display:none">
        <li><a href="../../template/BqClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理标签分类</a></li>
        <li><a href="../../template/ListBq.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">管理标签</a></li>
</ul>
<?
}
?>

<?
if($r[dotempgroup])
{
?>
<span id="prtempgroup" class="mbzgl" onClick="chengstate('tempgroup')">模板组管理</span>
<ul id="itemtempgroup" style="display:none">
        <li><a href="../../template/TempGroup.php<?=$ecms_hashur['whehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">导入/导出模板组</a></li>
</ul>
<?
}
?>

<?
if($r[dotemplate])
{
?>
<span id="prtother" class="qtxg" onClick="chengstate('tother')">其他相关</span>
<ul id="itemtother" style="display:none">
        <li><a href="../../template/LoadTemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">批量导入栏目模板</a></li>
        <li><a href="../../template/ChangeListTemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">批量更换列表模板</a></li>
        <li><a href="../../template/RepTemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" target="main" class="tyggmb" onClick="xz_bg(this)">批量替换模板字符</a></li>
</ul>
<?
}
?>
</div>
</div>
</body>
</html>

