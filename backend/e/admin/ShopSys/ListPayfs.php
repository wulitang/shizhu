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
CheckLevel($logininid,$loginin,$classid,"shoppayfs");

//增加支付方式
function AddPayfs($add,$userid,$username){
	global $empire,$dbtbpre;
	if(empty($add[payname]))
	{
		printerror("EmptyPayname","history.go(-1)");
    }
	//验证权限
	CheckLevel($userid,$username,$classid,"shoppayfs");
	$add[userpay]=(int)$add[userpay];
	$add[userfen]=(int)$add[userfen];
	$add['isclose']=(int)$add['isclose'];
	$sql=$empire->query("insert into {$dbtbpre}enewsshoppayfs(payname,payurl,paysay,userpay,userfen,isclose) values('".eaddslashes($add[payname])."','".eaddslashes($add[payurl])."','".eaddslashes($add[paysay])."','$add[userpay]','$add[userfen]','$add[isclose]');");
	$payid=$empire->lastid();
	if($sql)
	{
		//操作日志
		insert_dolog("payid=".$payid."<br>payname=".$add[payname]);
		printerror("AddPayfsSuccess","AddPayfs.php?enews=AddPayfs".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改支付方式
function EditPayfs($add,$userid,$username){
	global $empire,$dbtbpre;
	$add[payid]=(int)$add[payid];
	if(empty($add[payname])||!$add[payid])
	{
		printerror("EmptyPayname","history.go(-1)");
    }
	//验证权限
	CheckLevel($userid,$username,$classid,"shoppayfs");
	$add[userpay]=(int)$add[userpay];
	$add[userfen]=(int)$add[userfen];
	$add['isclose']=(int)$add['isclose'];
	$sql=$empire->query("update {$dbtbpre}enewsshoppayfs set payname='".eaddslashes($add[payname])."',payurl='".eaddslashes($add[payurl])."',paysay='".eaddslashes($add[paysay])."',userpay='$add[userpay]',userfen='$add[userfen]',isclose='$add[isclose]' where payid='$add[payid]'");
	if($sql)
	{
		//操作日志
		insert_dolog("payid=".$add[payid]."<br>payname=".$add[payname]);
		printerror("EditPayfsSuccess","ListPayfs.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除支付方式
function DelPayfs($payid,$userid,$username){
	global $empire,$dbtbpre;
	$payid=(int)$payid;
	if(!$payid)
	{
		printerror("EmptyPayfsid","history.go(-1)");
    }
	//验证权限
	CheckLevel($userid,$username,$classid,"shoppayfs");
	$r=$empire->fetch1("select payname from {$dbtbpre}enewsshoppayfs where payid='$payid'");
	$sql=$empire->query("delete from {$dbtbpre}enewsshoppayfs where payid='$payid'");
	if($sql)
	{
		//操作日志
		insert_dolog("payid=".$payid."<br>payname=".$r[payname]);
		printerror("DelPayfsSuccess","ListPayfs.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//设置为默认支付方式
function DefPayfs($payid,$userid,$username){
	global $empire,$dbtbpre;
	$payid=(int)$payid;
	if(!$payid)
	{
		printerror("EmptyPayfsid","history.go(-1)");
    }
	//验证权限
	CheckLevel($userid,$username,$classid,"shoppayfs");
	$r=$empire->fetch1("select payname from {$dbtbpre}enewsshoppayfs where payid='$payid'");
	$upsql=$empire->query("update {$dbtbpre}enewsshoppayfs set isdefault=0");
	$sql=$empire->query("update {$dbtbpre}enewsshoppayfs set isdefault=1 where payid='$payid'");
	if($sql)
	{
		//操作日志
		insert_dolog("payid=".$payid."<br>payname=".$r[payname]);
		printerror("DefPayfsSuccess","ListPayfs.php".hReturnEcmsHashStrHref2(1));
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
if($enews=="AddPayfs")
{
	AddPayfs($_POST,$logininid,$loginin);
}
elseif($enews=="EditPayfs")
{
	EditPayfs($_POST,$logininid,$loginin);
}
elseif($enews=="DelPayfs")
{
	$payid=$_GET['payid'];
	DelPayfs($payid,$logininid,$loginin);
}
elseif($enews=="DefPayfs")
{
	$payid=$_GET['payid'];
	DefPayfs($payid,$logininid,$loginin);
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
$query="select * from {$dbtbpre}enewsshoppayfs";
$num=$empire->num($query);//取得总条数
$query=$query." order by payid limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>管理支付方式</title>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="ListPayfs.php<?=$ecms_hashur['whehref']?>">管理支付方式</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理支付方式 <a href="AddPayfs.php?enews=AddPayfs<?=$ecms_hashur['ehref']?>" class="add">增加支付方式</a></span></h3>
            <div class="line"></div>
<div class="anniuqun">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th width="5%"> ID</th>
            <th width="41%"> 支付方式</th>
            <th width="16%">默认</th>
            <th width="16%">开启</th>
            <th width="22%"> 操作</th>
		</tr>
 <?
  while($r=$empire->fetch($sql))
  {
  ?>
  <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"> <div align="center"> 
        <?=$r[payid]?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$r[payname]?>
      </div></td>
    <td><div align="center"><?=$r[isdefault]==1?'是':'--'?></div></td>
    <td><div align="center"><?=$r[isclose]==1?'关闭':'开启'?></div></td>
    <td height="25"> <div align="center">[<a href="AddPayfs.php?enews=EditPayfs&payid=<?=$r[payid]?><?=$ecms_hashur['ehref']?>">修改</a>] [<a href="ListPayfs.php?enews=DefPayfs&payid=<?=$r[payid]?><?=$ecms_hashur['href']?>">设为默认</a>] [<a href="ListPayfs.php?enews=DelPayfs&payid=<?=$r[payid]?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
  		<tr>
  		  <td colspan="5" style="height:35px; overflow:hidden;margin:0;background:#F2F2F2; padding:10px 0;"><?=$returnpage?></td>
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
