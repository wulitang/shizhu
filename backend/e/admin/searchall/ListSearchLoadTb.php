<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
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
CheckLevel($logininid,$loginin,$classid,"searchall");

//增加搜索数据源
function AddSearchLoadTb($add,$userid,$username){
	global $empire,$dbtbpre;
	$tbname=RepPostVar($add['tbname']);
	$titlefield=RepPostVar($add['titlefield']);
	$infotextfield=RepPostVar($add['infotextfield']);
	$smalltextfield=RepPostVar($add['smalltextfield']);
	$loadnum=(int)$add['loadnum'];
	if(!$tbname||!$titlefield||!$infotextfield||!$smalltextfield||!$loadnum)
	{
		printerror("EmptySearchLoadTb","history.go(-1)");
	}
	//操作权限
	CheckLevel($userid,$username,$classid,"searchall");
	//表是否存在
	$tbnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewssearchall_load where tbname='$tbname'");
	if($tbnum)
	{
		printerror("ReSearchLoadTb","history.go(-1)");
	}
	$lasttime=time();
	$sql=$empire->query("insert into {$dbtbpre}enewssearchall_load(tbname,titlefield,infotextfield,smalltextfield,loadnum,lasttime,lastid) values('$tbname','$titlefield','$infotextfield','$smalltextfield',$loadnum,$lasttime,0);");
	$lid=$empire->lastid();
	GetSearchAllTb();
	if($sql)
	{
		//操作日志
		insert_dolog("lid=".$lid."&tbname=".$tbname);
		printerror("AddSearchLoadTbSuccess","AddSearchLoadTb.php?enews=AddSearchLoadTb".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改搜索数据源
function EditSearchLoadTb($add,$userid,$username){
	global $empire,$dbtbpre;
	$lid=(int)$add['lid'];
	$tbname=RepPostVar($add['tbname']);
	$titlefield=RepPostVar($add['titlefield']);
	$infotextfield=RepPostVar($add['infotextfield']);
	$smalltextfield=RepPostVar($add['smalltextfield']);
	$loadnum=(int)$add['loadnum'];
	if(!$tbname||!$titlefield||!$infotextfield||!$smalltextfield||!$loadnum)
	{
		printerror("EmptySearchLoadTb","history.go(-1)");
	}
	//操作权限
	CheckLevel($userid,$username,$classid,"searchall");
	if($tbname<>$add['oldtbname'])
	{
		//表是否存在
		$tbnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewssearchall_load where tbname='$tbname' and lid<>$lid limit 1");
		if($tbnum)
		{
			printerror("ReSearchLoadTb","history.go(-1)");
		}
	}
	$sql=$empire->query("update {$dbtbpre}enewssearchall_load set tbname='$tbname',titlefield='$titlefield',infotextfield='$infotextfield',smalltextfield='$smalltextfield',loadnum='$loadnum' where lid='$lid'");
	GetSearchAllTb();
	if($sql)
	{
		//操作日志
		insert_dolog("lid=".$lid."&tbname=".$tbname);
		printerror("EditSearchLoadTbSuccess","ListSearchLoadTb.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除搜索数据源
function DelSearchLoadTb($lid,$userid,$username){
	global $empire,$dbtbpre;
	$lid=(int)$lid;
	if(!$lid)
	{
		printerror("NotDelSearchLoadTbid","history.go(-1)");
	}
	//操作权限
	CheckLevel($userid,$username,$classid,"searchall");
	$r=$empire->fetch1("select tbname from {$dbtbpre}enewssearchall_load where lid='$lid'");
	if(!$r['tbname'])
	{
		printerror("NotDelSearchLoadTbid","history.go(-1)");
	}
	$sql=$empire->query("delete from {$dbtbpre}enewssearchall_load where lid='$lid'");
	$classids=ReturnTbGetClassids($r['tbname']);
	if($classids)
	{
		$delsql=$empire->query("delete from {$dbtbpre}enewssearchall where classid in (".$classids.")");
	}
	GetSearchAllTb();
	if($sql)
	{
		//操作日志
		insert_dolog("lid=".$lid."&tbname=".$r['tbname']);
		printerror("DelSearchLoadTbSuccess","ListSearchLoadTb.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除数据源数据
function SearchallDelData($add,$userid,$username){
	global $empire,$dbtbpre;
	//操作权限
	CheckLevel($userid,$username,$classid,"searchall");
	$lid=$add['lid'];
	$count=count($lid);
	for($i=0;$i<$count;$i++)
	{
		$id=(int)$lid[$i];
		if(empty($id))
		{
			continue;
		}
		$lr=$empire->fetch1("select tbname from {$dbtbpre}enewssearchall_load where lid='$id'");
		if(empty($lr['tbname']))
		{
			continue;
		}
		$classids=ReturnTbGetClassids($lr['tbname']);
		if($classids)
		{
			$empire->query("delete from {$dbtbpre}enewssearchall where classid in (".$classids.")");
			$empire->query("update {$dbtbpre}enewssearchall_load set lastid=0 where lid='$id'");
		}
	}
	//操作日志
	insert_dolog("");
	printerror("SearchallDelDataSuccess","ListSearchLoadTb.php".hReturnEcmsHashStrHref2(1));
}

//全站搜索设置
function SetSearchAll($add,$userid,$username){
	global $empire,$dbtbpre;
	//操作权限
	CheckLevel($userid,$username,$classid,"searchall");
	$openschall=(int)$add['openschall'];
	$schallfield=(int)$add['schallfield'];
	$schallminlen=(int)$add['schallminlen'];
	$schallmaxlen=(int)$add['schallmaxlen'];
	$schallnotcid=','.$add['schallnotcid'].',';
	$schallnum=(int)$add['schallnum'];
	$schallpagenum=(int)$add['schallpagenum'];
	$schalltime=(int)$add['schalltime'];
	$sql=$empire->query("update {$dbtbpre}enewspublic set openschall=$openschall,schallfield=$schallfield,schallminlen=$schallminlen,schallmaxlen=$schallmaxlen,schallnotcid='$schallnotcid',schallnum='$schallnum',schallpagenum='$schallpagenum',schalltime='$schalltime' limit 1");
	GetConfig();
	//操作日志
	insert_dolog("");
	printerror("SetSearchAllSuccess","SetSearchAll.php".hReturnEcmsHashStrHref2(1));
}

//返回数据表里的栏目列表
function ReturnTbGetClassids($tbname){
	global $empire,$dbtbpre;
	$ids='';
	$sql=$empire->query("select classid from {$dbtbpre}enewsclass where tbname='$tbname' and islast=1");
	while($r=$empire->fetch($sql))
	{
		$dh=',';
		if($ids=='')
		{
			$dh='';
		}
		$ids.=$dh.$r['classid'];
	}
	return $ids;
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
//增加搜索数据源
if($enews=="AddSearchLoadTb")
{
	AddSearchLoadTb($_POST,$logininid,$loginin);
}
//修改搜索数据源
elseif($enews=="EditSearchLoadTb")
{
	EditSearchLoadTb($_POST,$logininid,$loginin);
}
//删除搜索数据源
elseif($enews=="DelSearchLoadTb")
{
	$lid=$_GET['lid'];
	DelSearchLoadTb($lid,$logininid,$loginin);
}
//删除数据源数据
elseif($enews=="SearchallDelData")
{
	SearchallDelData($_GET,$logininid,$loginin);
}
//全站搜索设置
elseif($enews=="SetSearchAll")
{
	SetSearchAll($_POST,$logininid,$loginin);
}

$query="select lid,tbname,lasttime,lastid from {$dbtbpre}enewssearchall_load order by lid";
$sql=$empire->query($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理搜索数据源</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
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
function CheckSearchAll(obj){
	if(!confirm('确认要操作?'))
	{
		return false;
	}
	if(obj.enews.value=='SearchallDelData')
	{
		obj.action="ListSearchLoadTb.php";
	}
	else
	{
		obj.action="SearchLoadData.php";
	}
}
//增加搜索数据源
function zjsssjy(){
art.dialog.open('searchall/AddSearchLoadTb.php?<?=$ecms_hashur[ehref]?>&enews=AddSearchLoadTb',
    {title: '增加搜索数据源',lock: true,opacity: 0.5, width: 800, height: 420,
	 close: function () {
      location.reload();
    }
	});
}
//清理多余数据
function qldysj(){
art.dialog.open('searchall/ClearSearchAll.php<?=$ecms_hashur[whehref]?>',
    {title: '清理多余数据',lock: true,opacity: 0.5, width: 800, height: 420,
	 close: function () {
      location.reload();
    }
	});
}
//全站搜索设置
function qzsssz(){
art.dialog.open('searchall/SetSearchAll.php<?=$ecms_hashur[whehref]?>',
    {title: '全站搜索设置',lock: true,opacity: 0.5, width: 800, height: 540,
	 close: function () {
      location.reload();
    }
	});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="ListSearchLoadTb.php">管理全站搜索数据源</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理全站搜索数据源 <a href="javascript:void(0)" onclick="zjsssjy()" class="add">增加搜索数据源</a> <a href="javascript:void(0)" onclick="qzsssz()" class="gl">全站搜索设置</a> <a href="javascript:void(0)" onclick="qldysj()" class="del">清理多余数据</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<form name="searchform" method="GET" action="SearchLoadData.php" onsubmit="return CheckSearchAll(document.searchform);">
<?=$ecms_hashur['form']?>
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th></th>
			<th>导入数据表</th>
			<th>最后导入ID</th>
            <th>最后导入时间</th>
            <th>操作</th>
		</tr>
    <?
	while($r=$empire->fetch($sql))
	{
	?>
    <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"> 
      <td><div align="center"> 
          <input name="lid[]" type="checkbox" id="lid[]" value="<?=$r[lid]?>">
        </div></td>
      <td height="25"><div align="center"> 
          <?=$r[tbname]?>
        </div></td>
      <td><div align="center"> 
          <?=$r[lastid]?>
        </div></td>
      <td><div align="center"> 
          <?=date("Y-m-d H:i:s",$r[lasttime])?>
        </div></td>
      <td height="25"><div align="center">[<a href="AddSearchLoadTb.php?enews=EditSearchLoadTb&lid=<?=$r[lid]?><?=$ecms_hashur['ehref']?>">修改</a>] 
          [<a href="SearchLoadData.php?lid[]=<?=$r[lid]?><?=$ecms_hashur['href']?>">导入</a>] [<a href="ListSearchLoadTb.php?enews=DelSearchLoadTb&lid=<?=$r[lid]?><?=$ecms_hashur['href']?>" onclick="return confirm('会同时删除此数据表的搜索记录，确认要删除?');">删除</a>] 
        </div></td>
    </tr>
    <?
	}
	?>
    <tr bgcolor="#FFFFFF"> 
      <td><input type=checkbox name=chkall value=on onclick="CheckAll(this.form)"></td>
      <td height="25" colspan="4" style="text-align:left;"> 
       <input type="submit" name="Submit" value="批量导入搜索表" onclick="document.searchform.enews.value='SearchallLoadData';">
        &nbsp;&nbsp;<input type="submit" name="Submit2" value="删除表数据" onclick="document.searchform.enews.value='SearchallDelData';">
        <input name="enews" type="hidden" id="enews" value="SearchallLoadData"> 
      </td>
    </tr>
	</tbody>
</table>
</form>
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
