<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require "../".LoadLang("pub/fun.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];
//ehash
$ecms_hashur=hReturnEcmsHashStrAll();
//验证权限
CheckLevel($logininid,$loginin,$classid,"moreport");

//增加访问端
function AddMoreport($add,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add[pname]||!$add[ppath]||!$add[purl]||!$add[postpass]||!$add[tempgid])
	{
		printerror("EmptyMoreport","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"moreport");
	$add['pname']=hRepPostStr($add['pname'],1);
	$add['purl']=RepPostStr($add['purl'],1);
	$add['ppath']=RepPostStr($add['ppath'],1);
	$add['postpass']=RepPostStr($add['postpass'],1);
	$add['postfile']=RepPostStr($add['postfile'],1);
	$add['tempgid']=(int)$add['tempgid'];
	$add['mustdt']=(int)$add['mustdt'];
	$add['isclose']=(int)$add['isclose'];
	$add['closeadd']=(int)$add['closeadd'];
	if(!file_exists($add['ppath'].'e/config/config.php'))
	{
		printerror("ErrorMoreportPath","history.go(-1)");
	}
	$sql=$empire->query("insert into {$dbtbpre}enewsmoreport(pname,purl,ppath,postpass,postfile,tempgid,mustdt,isclose,closeadd) values('$add[pname]','$add[purl]','$add[ppath]','$add[postpass]','$add[postfile]','$add[tempgid]','$add[mustdt]','$add[isclose]','$add[closeadd]');");
	$pid=$empire->lastid();
	//更新缓存
	Moreport_UpdateIsclose();
	GetConfig();
	if($sql)
	{
		//操作日志
	    insert_dolog("pid=$pid&pname=$add[pname]");
		printerror("AddMoreportSuccess","AddMoreport.php?enews=AddMoreport".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改访问端
function EditMoreport($add,$userid,$username){
	global $empire,$dbtbpre;
	$add[pid]=(int)$add[pid];
	if(!$add[pid]||!$add[pname]||!$add[ppath]||!$add[purl]||!$add[postpass]||!$add[tempgid])
	{
		printerror("EmptyMoreport","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"moreport");
	$add['pname']=hRepPostStr($add['pname'],1);
	$add['purl']=RepPostStr($add['purl'],1);
	$add['ppath']=RepPostStr($add['ppath'],1);
	$add['postpass']=RepPostStr($add['postpass'],1);
	$add['postfile']=RepPostStr($add['postfile'],1);
	$add['tempgid']=(int)$add['tempgid'];
	$add['mustdt']=(int)$add['mustdt'];
	$add['isclose']=(int)$add['isclose'];
	$add['closeadd']=(int)$add['closeadd'];
	if(!file_exists($add['ppath'].'e/config/config.php'))
	{
		printerror("ErrorMoreportPath","history.go(-1)");
	}
	$sql=$empire->query("update {$dbtbpre}enewsmoreport set pname='$add[pname]',purl='$add[purl]',ppath='$add[ppath]',postpass='$add[postpass]',postfile='$add[postfile]',tempgid='$add[tempgid]',mustdt='$add[mustdt]',isclose='$add[isclose]',closeadd='$add[closeadd]' where pid='$add[pid]'");
	//更新缓存
	Moreport_UpdateIsclose();
	GetConfig();
	if($sql)
	{
		//操作日志
	    insert_dolog("pid=$add[pid]&pname=$add[pname]");
		printerror("EditMoreportSuccess","ListMoreport.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除访问端
function DelMoreport($add,$userid,$username){
	global $empire,$dbtbpre;
	$pid=(int)$add['pid'];
	if(!$pid)
	{
		printerror("NotChangeMoreportId","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"moreport");
	$r=$empire->fetch1("select pname from {$dbtbpre}enewsmoreport where pid='$pid'");
	$sql=$empire->query("delete from {$dbtbpre}enewsmoreport where pid='$pid'");
	//更新缓存
	Moreport_UpdateIsclose();
	GetConfig();
	if($sql)
	{
		//操作日志
		insert_dolog("pid=$pid&pname=$r[pname]");
		printerror("DelMoreportSuccess","ListMoreport.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
	include('../../class/copypath.php');
	include('moreportfun.php');
}
//增加访问端
if($enews=="AddMoreport")
{
	AddMoreport($_POST,$logininid,$loginin);
}
elseif($enews=="EditMoreport")//修改访问端
{
	EditMoreport($_POST,$logininid,$loginin);
}
elseif($enews=="DelMoreport")//删除访问端
{
	DelMoreport($_GET,$logininid,$loginin);
}
elseif($enews=="MoreportChangeCacheAll")//更新访问端数据库缓存
{
	Moreport_ChangeCacheAll($_GET,$logininid,$loginin);
}
elseif($enews=="MoreportUpdateClassfileAll")//更新访问端栏目缓存文件
{
	Moreport_UpdateClassfileAll($_GET,$logininid,$loginin);
}
elseif($enews=="MoreportReDtPageAll")//更新访问端动态页面
{
	Moreport_ReDtPageAll($_GET,$logininid,$loginin);
}
elseif($enews=="MoreportClearTmpfileAll")//清理访问端临时文件
{
	Moreport_ClearTmpfileAll($_GET,$logininid,$loginin);
}
elseif($enews=="MoreportReIndexfileAll")//更新访问端动态首页文件
{
	Moreport_ReIndexfileAll($_GET,$logininid,$loginin);
}

$search=$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=30;
$page_line=25;
$add="";
$offset=$line*$page;
$totalquery="select count(*) as total from {$dbtbpre}enewsmoreport";
$num=$empire->gettotal($totalquery);
$query="select * from {$dbtbpre}enewsmoreport";
$query.=" order by pid limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理网站访问端</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/ui.tab.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script>
function CheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
  }
</script>
<script type="text/javascript">
$(function(){
			
		});
//增加访问端
function zjfwd(){
art.dialog.open('moreport/AddMoreport.php?<?=$ecms_hashur[ehref]?>&enews=AddMoreport',
    {title: '增加访问端',lock: true,opacity: 0.5, width: 800, height: 470,
	 close: function () {
      location.reload();
    }
	});
}
//修改访问端
function editfwd(pid){
art.dialog.open('moreport/AddMoreport.php?<?=$ecms_hashur[ehref]?>&enews=EditMoreport&pid='+pid,
    {title: '修改访问端',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//删除访问端
function delfwd(pid){
art.dialog.open('moreport/ListMoreport.php?<?=$ecms_hashur[href]?>&enews=DelMoreport&pid='+pid,
    {title: '删除访问端',lock: true,opacity: 0.5, width: 800, height: 540,
	init: function () {
    	var that = this, i = 3;
        var fn = function () {
            that.title( i + ' 秒后关闭');
            !i && that.close();
            i --;
        };
        timer = setInterval(fn, 1000);
        fn();
    },
	close: function () {
	  clearInterval(timer);
      location.reload();
    }
	});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <a href=ListYh.php<?=$ecms_hashur['whehref']?>>管理网站访问端</a> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理网站访问端 <a href="javascript:void(0)" onclick="zjfwd()"class="add">增加网站访问端</a><a href="javascript:void(0)" onclick="if(document.getElementById('moreportchangedata').style.display==''){document.getElementById('moreportchangedata').style.display='none';}else{document.getElementById('moreportchangedata').style.display='';}" class="add">更新所有访问端缓存与动态页面</a></span></h3>
	<div class="jqui">
	<form name="moreportchangedataform" method="GET" action="ListMoreport.php" onsubmit="return confirm('确认要更新?');">
	<?=$ecms_hashur['form']?>
	<input type="hidden" name="enews" value="MoreportChangeCacheAll">
<table width="100%" border="0" cellspacing="1" cellpadding="0"  class="comm-table" id="moreportchangedata" style="display:none">	
    <tr>
    <td height="25" colspan="6"><div id="ecmspage">
      更新数据库缓存
      <input name="doclassfile" type="checkbox" id="doclassfile" value="1" checked>
      更新栏目缓存文件
      <input name="dodtpage" type="checkbox" id="dodtpage" value="1" checked>
      更新动态页面
      <input name="dotmpfile" type="checkbox" id="dotmpfile" value="1" checked>
      清理临时文件
      <input name="doreindex" type="checkbox" id="doreindex" value="1" checked>
      更新动态首页文件
      <input type="submit" name="Submit" value="提交"></div></td>
  </tr>
  </table>
  </form>
</div>
            <div class="line"></div>
<div class="jqui">
  <form name="listmoreportform" method="post" action="ListMoreport.php" onsubmit="return confirm('确认要删除?');">
  <?=$ecms_hashur['form']?>
    <input type="hidden" name="enews" value="DelMoreport_all">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
      <th>ID</th>
     <th>访问端</th>
     <th>使用模板组</th>
     <th>强制动态页模式</th>
     <th>状态</th>
      <th>操作</th>
		</tr>
    <?
  while($r=$empire->fetch($sql))
  {
	//主访问端
	if($r['pid']==1)
	{
		$r['pname']='<b>'.$r['pname'].'</b>';
		if(empty($r['purl']))
		{
			$r['purl']=$public_r['newsurl'];
		}
		$tgr=$empire->fetch1("select gid,gname,isdefault from {$dbtbpre}enewstempgroup where isdefault=1");
	}
	else
	{
		$tgr=$empire->fetch1("select gid,gname,isdefault from {$dbtbpre}enewstempgroup where gid='$r[tempgid]'");
	}
  ?>
    <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"> 
      <td height="25"> <div align="center"> 
          <?=$r[pid]?>
        </div></td>
      <td height="25"> <div align="center"> 
	  <a href="<?=$r[purl]?>" target="_blank"><?=$r[pname]?></a>
	   </div></td>
      <td height="25"> <div align="center"> 
          <?=$tgr[gname]?>
        </div></td>
      <td><div align="center"><?=$r[mustdt]==1?'是':'否'?></div></td>
      <td><div align="center"><?=$r[isclose]==1?'关闭':'开启'?></div></td>
      <td height="25"> <div align="center"> 
      	  <?php
	  if($r['pid']==1)
	  {
	  ?>
	  	主访问端
	  <?php
	  }
	  else
	  {
	  ?>
		 [<a href="javascript:editfwd(<?=$r[pid]?>)">修改</a>]&nbsp;[<a href="javascript:delfwd(<?=$r[pid]?>)" onclick="return confirm('确认要删除？');">删除</a>]
	  <?php
	  }
	  ?>
      </div></td>
  </tr>
  <?
  }
  ?>
   <tr>
   <td height="25" colspan="6"><?=$returnpage?></td>
   </tr>
	</tbody>
</table>
</div>
<div class="line"></div>
      </div>
    </div>
</div>
</div>
</body>
</html>
<?
db_close();
$empire=null;
?>
