<?php
define('EmpireCMSAdmin','1');
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
require LoadLang("pub/fun.php");
$link=db_connect();
$empire=new mysqlquery();
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
CheckLevel($logininid,$loginin,$classid,"do");

//组合栏目
function AddDoTogClassid($classid){
	$count=count($classid);
	$class=',';
	for($i=0;$i<$count;$i++)
	{
		$class.=intval($classid[$i]).',';
	}
	return $class;
}

//增加刷新任务
function AddDo($add,$userid,$username){
	global $empire,$dbtbpre;
	$count=count($add[classid]);
	if(empty($add[doname])||($add[doing]&&!$count))
	{
		printerror("EmptyDoname","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"do");
	if($add[dotime]<5)
	{
		$add[dotime]=5;
	}
	$lasttime=time();
	//变量处理
	$add[dotime]=(int)$add[dotime];
	$add[isopen]=(int)$add[isopen];
	$add[doing]=(int)$add[doing];
	$classid=AddDoTogClassid($add[classid]);
	$sql=$empire->query("insert into {$dbtbpre}enewsdo(doname,dotime,isopen,doing,classid,lasttime) values('$add[doname]',$add[dotime],$add[isopen],$add[doing],'$classid',$lasttime);");
	$doid=$empire->lastid();
	if($sql)
	{
		//操作日志
		insert_dolog("doid=$doid&doname=$add[doname]");
		printerror("AddDoSuccess","AddDo.php?enews=AddDo".hReturnEcmsHashStrHref2(0));
    }
	else
	{printerror("DbError","history.go(-1)");}
}

//修改刷新任务
function EditDo($add,$userid,$username){
	global $empire,$dbtbpre;
	$count=count($add[classid]);
	if(empty($add[doname])||($add[doing]&&!$count))
	{
		printerror("EmptyDoname","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"do");
	if($add[dotime]<5)
	{
		$add[dotime]=5;
	}
	//变量处理
	$add[dotime]=(int)$add[dotime];
	$add[isopen]=(int)$add[isopen];
	$add[doing]=(int)$add[doing];
	$classid=AddDoTogClassid($add[classid]);
	$sql=$empire->query("update {$dbtbpre}enewsdo set doname='$add[doname]',dotime=$add[dotime],isopen=$add[isopen],doing=$add[doing],classid='$classid' where doid='$add[doid]'");
	if($sql)
	{
		//操作日志
		insert_dolog("doid=$add[doid]&doname=$add[doname]");
		printerror("EditDoSuccess","ListDo.php".hReturnEcmsHashStrHref2(1));
    }
	else
	{printerror("DbError","history.go(-1)");}
}

//删除刷新任务
function DelDo($doid,$userid,$username){
	global $empire,$dbtbpre;
	$doid=(int)$doid;
	if(empty($doid))
	{printerror("EmptyDoid","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"do");
	$r=$empire->fetch1("select doname from {$dbtbpre}enewsdo where doid='$doid'");
	$sql=$empire->query("delete from {$dbtbpre}enewsdo where doid='$doid'");
	if($sql)
	{
		//操作日志
		insert_dolog("doid=$doid&doname=$r[doname]");
		printerror("EditDoSuccess","ListDo.php".hReturnEcmsHashStrHref2(1));
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
//增加刷新任务
if($enews=="AddDo")
{
	$add=$_POST;
	AddDo($add,$logininid,$loginin);
}
//修改刷新任务
elseif($enews=="EditDo")
{
	$add=$_POST;
	EditDo($add,$logininid,$loginin);
}
//删除刷新任务
elseif($enews=="DelDo")
{
	$doid=$_GET['doid'];
	DelDo($doid,$logininid,$loginin);
}

$search=$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select * from {$dbtbpre}enewsdo";
$num=$empire->num($query);//取得总条数
$query=$query." order by doid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>管理刷新任务</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="adminstyle/1/yecha/yecha.css" />
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="adminstyle/<?=$loginadminstyleid?>/js/ui.tab.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>

<script type="text/javascript">
$(function(){
			
		});
//增加刷新任务
function zjsxrw(){
art.dialog.open('AddDo.php?<?=$ecms_hashur[ehref]?>&enews=AddDo',
    {title: '增加刷新任务',lock: true,opacity: 0.5, width: 800, height: 540,
	 close: function () {
      location.reload();
    }
	});
}

//修改定时刷新任务
function xgdssxrw(doid){
art.dialog.open('AddDo.php?<?=$ecms_hashur[ehref]?>&enews=EditDo&doid=' + doid,
    {title: '修改定时刷新任务',id: 'glzdbox',lock: true,opacity: 0.5, width: 800, height: 650,
	close: function () {
      location.reload();
    }
	});
}
//删除定时刷新
function deldssx(doid){
art.dialog.open('ListDo.php?<?=$ecms_hashur[href]?>&enews=DelDo&doid='+doid,
    {title: '删除定时刷新任务',lock: true,opacity: 0.5, width: 800, height: 540,
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
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <a href="ListDo.php<?=$ecms_hashur['whehref']?>">管理定时刷新任务</a> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理定时刷新任务 <a href="javascript:void(0)" onclick="zjsxrw()" class="add">增加刷新任务</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>任务名</th>
			<th>时间间隔</th>
			<th>最后执行时间</th>
            <th>开启</th>
            <th>操作</th>
		</tr>
<?
  while($r=$empire->fetch($sql))
  {
  if($r[isopen])
  {$isopen="开启";}
  else
  {$isopen="关闭";}
  ?>
 <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"> <div align="center"> 
        <?=$r[doid]?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$r[doname]?>
      </div></td>
    <td><div align="center">
        <?=$r[dotime]?>
      </div></td>
    <td><div align="center"> 
        <?=date("Y-m-d H:i:s",$r[lasttime])?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$isopen?>
      </div></td>
    <td height="25"> <div align="center">[<a href="javascript:void(0)" onclick="xgdssxrw()">修改</a>]&nbsp;[<a href="ListDo.php?enews=DelDo&doid=<?=$r[doid]?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
<tr>
<td colspan="6" class="txtleft"><?=$returnpage?></td>
		  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="25" colspan="6"><font color="#666666">说明：执行定时刷新任务需要开着后台或者<a href="DoTimeRepage.php<?=$ecms_hashur['whehref']?>" target="_blank"><strong>点击这里</strong></a>开着这个页面才会执行。</font></td>
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
