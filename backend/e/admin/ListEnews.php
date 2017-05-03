<?php
define('EmpireCMSAdmin','1');
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
$link=db_connect();
$empire=new mysqlquery();
//验证用户
$lur=is_login();
$logininid=(int)$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];
//ehash
$ecms_hashur=hReturnEcmsHashStrAll();

$user_r=$empire->fetch1("select adminclass,groupid from {$dbtbpre}enewsuser where userid='$logininid'");
//用户组权限
$gr=$empire->fetch1("select doall from {$dbtbpre}enewsgroup where groupid='$user_r[groupid]'");
if($gr['doall'])
{
	$fcfile='../data/fc/ListEnews.php';
}
else
{
	$fcfile='../data/fc/ListEnews'.$logininid.'.php';
}
$fclistenews='';

if(file_exists($fcfile))
{
	$fclistenews=str_replace(AddCheckViewTempCode(),'',ReadFiletext($fcfile));
}
//数据表
$changetbs='';
$dh='';
$tbi=0;
$tbsql=$empire->query("select tbname,tname from {$dbtbpre}enewstable order by tid");
while($tbr=$empire->fetch($tbsql))
{
	$tbi++;
	$changetbs.=$dh.'new ContextItem("'.$tbr['tname'].'",function(){ parent.document.main.location="ListAllInfo.php?tbname='.$tbr['tbname'].$ecms_hashur['ehref'].'"; })';
	if($tbi%3==0)
	{
		$changetbs.=',new ContextSeperator()';
	}
	$dh=',';
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理信息</title>
<link href="../data/menu/menu.css" rel="stylesheet" type="text/css">
<script src="../data/menu/menu.js" type="text/javascript"></script>
<script language="javascript" src="../data/rightmenu/context_menu.js"></script>
<script language="javascript" src="../data/rightmenu/ieemu.js"></script>
<script src="/js/jquery.js"></script>
<script src="/js/ecmsshop_fanyi.js"></script>
<SCRIPT lanuage="JScript">
if(self==top)
{self.location.href='admin.php<?=$ecms_hashur['whehref']?>';}

if(!document.all) document.captureEvents(Event.MOUSEDOWN);
var _Tmenu = 0;
var _Amenu = 0;
var _Type  = 'A';
document.onclick = _Hidden;
function _Hidden()
{
	if(_Tmenu==0) return;
	document.getElementById(_Tmenu).style.visibility='hidden';
	_Tmenu=0;
	document.getElementById(_Tmenu).innerHTML='';
}



document.oncontextmenu = function (e)
{
	_Hidden();
	var _Obj = document.all ? event.srcElement : e.target;
	if(_Type.indexOf(_Obj.tagName) == -1) return;
	_Amenu = _Obj.getAttribute('menu');
	_classid = _Obj.getAttribute('classid');
	_bclassid = _Obj.getAttribute('bclassid');
	_classurl = _Obj.getAttribute('classurl');
	_showmenu = _Obj.getAttribute('showmenu')
	if(_Amenu == 'null') return;
	if(document.all) e = event;
	_ShowMenu(_Amenu, e, _classid, _bclassid, _classurl, _showmenu);
	
	return false;
}

function _ShowMenu(Eid, event, classid, bclassid, classurl, showmenu)
{
	var _Menu = document.getElementById("menudiv");
	var _Left = event.clientX + document.body.scrollLeft;
	var _Top  = event.clientY + document.body.scrollTop;
	_Menu.style.left = _Left.toString() + 'px';
	_Menu.style.top  = _Top.toString()  + 'px';
	_Menu.style.visibility = 'visible';
	_Tmenu = "menudiv";
	if(showmenu==1){
	_Menu.innerHTML='<a href="AddNews.php?<?=$ecms_hashur[ehref]?>&enews=AddNews&bclassid='+bclassid+'&classid='+classid+'" target="main" class="addxx">增加信息</a> <a href="enews.php?<?=$ecms_hashur[href]?>&enews=ReListHtml&classid='+classid+'" target="main" class="refresh">刷新栏目</a><a href="ecmschtml.php?<?=$ecms_hashur[href]?>&enews=ReSingleJs&doing=0&classid='+classid+'" target="main">刷新栏目JS</a><a href="ecmschtml.php?<?=$ecms_hashur[href]?>&enews=ReIndex" target="main">刷新首页</a><a href="ReHtml/ChangeData.php<?=$ecms_hashur[whehref]?>" target="main">数据更新</a><a href="../../" target="_target" class="yulan">预览首页</a><a href="'+classurl+'" target="_target">预览栏目</a><a href="AddClass.php?<?=$ecms_hashur[ehref]?>&classid='+classid+'&enews=EditClass" target="main">修改栏目</a><a href="AddClass.php?<?=$ecms_hashur[ehref]?>&enews=AddClass" target="main" class="addxx">增加新栏目</a><a href="AddClass.php?<?=$ecms_hashur[ehref]?>&classid='+classid+'&enews=AddClass&docopy=1" target="main" class="copy">复制栏目</a><a href="ecmsclass.php?<?=$ecms_hashur[href]?>&classid='+classid+'&enews=DelClass" target="main" onclick="return confirm(\'确认要删除此栏目，将删除所属子栏目及信息\')">删除栏目</a><a href="AddInfoClass.php?<?=$ecms_hashur[ehref]?>&enews=AddInfoClass&newsclassid='+classid+'" target="main">增加采集节点</a><a href="file/ListFile.php?<?=$ecms_hashur[ehref]?>&type=9&classid='+classid+'" target="main">管理附件</a>'
	}
	else if(showmenu==2)
	{
	_Menu.innerHTML='<a href="enews.php?<?=$ecms_hashur[href]?>&enews=ReListHtml&classid='+classid+'" target="main"  class="refresh">刷新栏目</a><a href="ecmschtml.php?<?=$ecms_hashur[href]?>&enews=ReSingleJs&doing=0&classid='+classid+'" target="main">刷新栏目JS</a><a href="ecmschtml.php?<?=$ecms_hashur[href]?>&enews=ReIndex" target="main">刷新首页</a><a href="ReHtml/ChangeData.php<?=$ecms_hashur[whehref]?>" target="main">数据更新</a><a href="../../" target="_target" class="yulan">预览首页</a><a href="'+classurl+'" target="_target">预览栏目</a><a href="AddClass.php?<?=$ecms_hashur[ehref]?>&classid='+classid+'&enews=EditClass" target="main">修改栏目</a><a href="AddClass.php?<?=$ecms_hashur[ehref]?>&enews=AddClass&bclassid='+classid+'" target="main" class="addxx">增加新栏目</a><a href="AddClass.php?<?=$ecms_hashur[ehref]?>&classid='+classid+'&enews=AddClass&docopy=1" target="main" class="copy">复制栏目</a><a href="ecmsclass.php?<?=$ecms_hashur[href]?>&classid='+classid+'&enews=DelClass" target="main" onclick="return confirm(\'确认要删除此栏目，将删除所属子栏目及信息\')">删除栏目</a>';
	}
}
function tourl(bclassid,classid){
	parent.main.location.href="ListNews.php?<?=$ecms_hashur['ehref']?>&bclassid="+bclassid+"&classid="+classid;
}

if(moz) {
	extendEventObject();
	extendElementModel();
	emulateAttachEvent();
}
function goaddclass(){
    parent.main.location.href='AddClass.php?enews=AddClass<?=$ecms_hashur['ehref']?>';
}
</SCRIPT>
</head>
<body onLoad="initialize();ContextMenu.intializeContextMenu();">
<div id="menudiv" class='DreamMenu'>
</div>
<div class="leftside glsyxx">
<h2><span onClick="window.open('ListAllInfo.php<?=$ecms_hashur['whehref']?>','main','');">管理信息</span></h2>
<?php
echo $fclistenews;
?>
</div>
</body>
</html>
<?php
db_close();
$empire=null;
?>