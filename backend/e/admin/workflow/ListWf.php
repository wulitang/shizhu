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
CheckLevel($logininid,$loginin,$classid,"workflow");

//增加工作流
function AddWorkflow($add,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add[wfname])
	{
		printerror('EmptyWorkflow','history.go(-1)');
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"workflow");
	$add[myorder]=(int)$add[myorder];
	$addtime=time();
	$sql=$empire->query("insert into {$dbtbpre}enewsworkflow(wfname,wftext,myorder,addtime,adduser) values('$add[wfname]','$add[wftext]','$add[myorder]','$addtime','$username');");
	$wfid=$empire->lastid();
	if($sql)
	{
		//操作日志
		insert_dolog("wfid=".$wfid."<br>wfname=".$add[wfname]);
		printerror("AddWorkflowSuccess","AddWf.php?enews=AddWorkflow".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改工作流
function EditWorkflow($add,$userid,$username){
	global $empire,$dbtbpre;
	$wfid=(int)$add[wfid];
	if(!$wfid||!$add[wfname])
	{
		printerror('EmptyWorkflow','history.go(-1)');
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"workflow");
	$add[myorder]=(int)$add[myorder];
	$sql=$empire->query("update {$dbtbpre}enewsworkflow set wfname='$add[wfname]',wftext='$add[wftext]',myorder='$add[myorder]' where wfid='$wfid'");
	if($sql)
	{
		//操作日志
		insert_dolog("wfid=".$wfid."<br>wfname=".$add[wfname]);
		printerror("EditWorkflowSuccess","ListWf.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除工作流
function DelWorkflow($add,$userid,$username){
	global $empire,$dbtbpre;
	$wfid=(int)$add[wfid];
	if(!$wfid)
	{
		printerror('NotDelWorkflowid','history.go(-1)');
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"workflow");
	$r=$empire->fetch1("select wfname from {$dbtbpre}enewsworkflow where wfid='$wfid'");
	$sql=$empire->query("delete from {$dbtbpre}enewsworkflow where wfid='$wfid'");
	$sql2=$empire->query("delete from {$dbtbpre}enewsworkflowitem where wfid='$wfid'");
	if($sql&&$sql2)
	{
		//操作日志
		insert_dolog("wfid=".$wfid."<br>wfname=".$r[wfname]);
		printerror("DelWorkflowSuccess","ListWf.php".hReturnEcmsHashStrHref2(1));
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
}
if($enews=="AddWorkflow")//增加工作流
{
	AddWorkflow($_POST,$logininid,$loginin);
}
elseif($enews=="EditWorkflow")//修改工作流
{
	EditWorkflow($_POST,$logininid,$loginin);
}
elseif($enews=="DelWorkflow")//删除工作流
{
	DelWorkflow($_GET,$logininid,$loginin);
}

$search=$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select wfid,wfname,addtime,adduser from {$dbtbpre}enewsworkflow";
$totalquery="select count(*) as total from {$dbtbpre}enewsworkflow";
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by myorder,wfid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
$url="<a href=ListWf.php".$ecms_hashur['whehref'].">管理工作流</a>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理工作流</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/ui.tab.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>

<script type="text/javascript">
$(function(){
			
		});
//增加工作流
function zjgzl(){
art.dialog.open('workflow/AddWf.php?<?=$ecms_hashur[ehref]?>&enews=AddWorkflow',
    {title: '增加工作流',lock: true,opacity: 0.5, width: 800, height: 400,
	 close: function () {
      location.reload();
    }
	});
}
//管理节点
function gljd(wfid){
art.dialog.open('workflow/ListWfItem.php?<?=$ecms_hashur[ehref]?>&wfid=' + wfid,
    {title: '管理节点',lock: true,opacity: 0.5, width: 800, height: 400,
	close: function () {
      location.reload();
    }
	});
}
//修改工作流
function xg(wfid){
art.dialog.open('workflow/AddWf.php?<?=$ecms_hashur[ehref]?>&enews=EditWorkflow&wfid=' + wfid,
    {title: '修改工作流',lock: true,opacity: 0.5, width: 800, height: 400,
	close: function () {
      location.reload();
    }
	});
}
//删除工作流
function del(wfid){
art.dialog.open('workflow/ListWf.php?<?=$ecms_hashur[href]?>&enews=DelWorkflow&wfid='+wfid,
    {title: '删除工作流',lock: true,opacity: 0.5, width: 800, height: 540,
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
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理工作流 <a href="javascript:void(0)" onclick="zjgzl()"class="add">增加工作流</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>工作流名称</th>
			<th>增加者</th>
			<th>增加时间</th>
            <th>流程节点</th>
            <th>操作</th>
		</tr>
  <?
  while($r=$empire->fetch($sql))
  {
  ?>
 <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"> <div align="center"> 
        <?=$r[wfid]?>
      </div></td>
    <td height="25"> <div align="center"> 
       <?=$r[wfname]?>
      </div></td>
    <td><div align="center">
       <?=$r[adduser]?>
      </div></td>
    <td><div align="center"> 
      <?=date('Y-m-d H:i:s',$r[addtime])?>
      </div></td>
    <td height="25"> <div align="center"> 
       <a href="javascript:gljd(<?=$r[wfid]?><?=$ecms_hashur['ehref']?>)">管理节点</a>
      </div></td>
      <td height="25"> <div align="center"> 
      [<a href="AddWf.php?enews=EditWorkflow&wfid=<?=$r[wfid]?><?=$ecms_hashur['ehref']?>">修改</a>] 
        [<a href="ListWf.php?enews=DelWorkflow&wfid=<?=$r[wfid]?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除?');">删除</a>]
      </div></td>
  </tr>
  <?
  }
  ?>
<tr>
<td colspan="6" class="txtleft"><?=$returnpage?></td>
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
