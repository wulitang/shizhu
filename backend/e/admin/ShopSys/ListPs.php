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
CheckLevel($logininid,$loginin,$classid,"shopps");

//增加配送方式
function AddPs($add,$userid,$username){
	global $empire,$dbtbpre;
	if(empty($add[pname]))
	{
		printerror("EmptyPayname","history.go(-1)");
    }
	//验证权限
	CheckLevel($userid,$username,$classid,"shopps");
	$add[price]=(float)$add[price];
	$add['isclose']=(int)$add['isclose'];
	$sql=$empire->query("insert into {$dbtbpre}enewsshopps(pname,price,otherprice,psay,isclose) values('".eaddslashes($add[pname])."','$add[price]','$add[otherprice]','".eaddslashes($add[psay])."','$add[isclose]');");
	$pid=$empire->lastid();
	if($sql)
	{
		//操作日志
		insert_dolog("pid=".$pid."<br>pname=".$add[pname]);
		printerror("AddPayfsSuccess","AddPs.php?enews=AddPs".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改配送方式
function EditPs($add,$userid,$username){
	global $empire,$dbtbpre;
	$add[pid]=(int)$add[pid];
	if(empty($add[pname])||!$add[pid])
	{
		printerror("EmptyPayname","history.go(-1)");
    }
	//验证权限
	CheckLevel($userid,$username,$classid,"shopps");
	$add[price]=(float)$add[price];
	$add['isclose']=(int)$add['isclose'];
	$sql=$empire->query("update {$dbtbpre}enewsshopps set pname='".eaddslashes($add[pname])."',price='$add[price]',otherprice='$add[otherprice]',psay='".eaddslashes($add[psay])."',isclose='$add[isclose]' where pid='$add[pid]'");
	if($sql)
	{
		//操作日志
		insert_dolog("pid=".$add[pid]."<br>pname=".$add[pname]);
		printerror("EditPayfsSuccess","ListPs.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除配送方式
function DelPs($pid,$userid,$username){
	global $empire,$dbtbpre;
	$pid=(int)$pid;
	if(!$pid)
	{
		printerror("EmptyPayfsid","history.go(-1)");
    }
	//验证权限
	CheckLevel($userid,$username,$classid,"shopps");
	$r=$empire->fetch1("select pname from {$dbtbpre}enewsshopps where pid='$pid'");
	$sql=$empire->query("delete from {$dbtbpre}enewsshopps where pid='$pid'");
	if($sql)
	{
		//操作日志
		insert_dolog("pid=".$pid."<br>pname=".$r[pname]);
		printerror("DelPayfsSuccess","ListPs.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//设置为默认配送方式
function DefPs($pid,$userid,$username){
	global $empire,$dbtbpre;
	$pid=(int)$pid;
	if(!$pid)
	{
		printerror("EmptyPayfsid","history.go(-1)");
    }
	//验证权限
	CheckLevel($userid,$username,$classid,"shopps");
	$r=$empire->fetch1("select pname from {$dbtbpre}enewsshopps where pid='$pid'");
	$upsql=$empire->query("update {$dbtbpre}enewsshopps set isdefault=0");
	$sql=$empire->query("update {$dbtbpre}enewsshopps set isdefault=1 where pid='$pid'");
	if($sql)
	{
		//操作日志
		insert_dolog("pid=".$pid."<br>pname=".$r[pname]);
		printerror("DefPayfsSuccess","ListPs.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
if($enews=="AddPs")
{
	AddPs($_POST,$logininid,$loginin);
}
elseif($enews=="EditPs")
{
	EditPs($_POST,$logininid,$loginin);
}
elseif($enews=="DelPs")
{
	$pid=$_GET['pid'];
	DelPs($pid,$logininid,$loginin);
}
elseif($enews=="DefPs")
{
	$pid=$_GET['pid'];
	DefPs($pid,$logininid,$loginin);
}
else
{}

$search=$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=16;//每页显示条数
$page_line=18;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select * from {$dbtbpre}enewsshopps";
$num=$empire->num($query);//取得总条数
$query=$query." order by pid limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>管理配送方式</title>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="ListPs.php<?=$ecms_hashur['whehref']?>">管理配送方式</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理配送方式 <a href="AddPs.php?enews=AddPs<?=$ecms_hashur['ehref']?>" class="add">增加配送方式</a></span></h3>
            <div class="line"></div>
<div class="anniuqun">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
    <th width="5%">ID</th>
    <th width="36%"> 配送方式</th>
    <th width="15%">价格</th>
    <th width="11%">默认</th>
    <th width="11%">启用</th>
    <th width="22%"> 操作</th>
		</tr>
 <?
  while($r=$empire->fetch($sql))
  {
  ?>
  <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"> <div align="center"> 
        <?=$r[pid]?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$r[pname]?>
      </div></td>
    <td><div align="center"> 
        <?=$r[price]?>
        元 </div></td>
    <td><div align="center"><?=$r[isdefault]==1?'是':'--'?></div></td>
    <td><div align="center"><?=$r[isclose]==1?'关闭':'开启'?></div></td>
    <td height="25"> <div align="center">[<a href="AddPs.php?enews=EditPs&pid=<?=$r[pid]?><?=$ecms_hashur['ehref']?>">修改</a>] [<a href="ListPs.php?enews=DefPs&pid=<?=$r[pid]?><?=$ecms_hashur['href']?>">设为默认</a>] [<a href="ListPs.php?enews=DelPs&pid=<?=$r[pid]?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
  		<tr>
  		  <td colspan="6" style="height:35px; overflow:hidden;margin:0;background:#F2F2F2; padding:10px 0;"><?=$returnpage?></td>
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
