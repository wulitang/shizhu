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
CheckLevel($logininid,$loginin,$classid,"ad");

//增加广告类别
function AddAdClass($add,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add[classname])
	{
		printerror("EmptyAdClassname","history.go(-1)");
    }
	//验证权限
	CheckLevel($userid,$username,$classid,"ad");
	$sql=$empire->query("insert into {$dbtbpre}enewsadclass(classname) values('$add[classname]');");
	$classid=$empire->lastid();
	if($sql)
	{
		//操作日志
	    insert_dolog("classid=".$classid."<br>classname=".$add[classname]);
		printerror("AddAdClassSuccess","AdClass.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改广告类别
function EditAdClass($add,$userid,$username){
	global $empire,$dbtbpre;
	$add[classid]=(int)$add[classid];
	if(!$add[classname]||!$add[classid])
	{printerror("EmptyAdClassname","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"ad");
	$sql=$empire->query("update {$dbtbpre}enewsadclass set classname='$add[classname]' where classid='$add[classid]'");
    if($sql)
	{
		//操作日志
		insert_dolog("classid=".$add[classid]."<br>classname=".$add[classname]);
		printerror("EditAdClassSuccess","AdClass.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除广告类别
function DelAdClass($classid,$userid,$username){
	global $empire,$public_r,$dbtbpre;
	$classid=(int)$classid;
	if(!$classid)
	{printerror("NotChangeAdClassid","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"ad");
	$c=$empire->fetch1("select classname from {$dbtbpre}enewsadclass where classid='$classid'");
	$sql=$empire->query("delete from {$dbtbpre}enewsadclass where classid='$classid'");
	/*
	//删除广告内容
	$a=$empire->query("select adid from {$dbtbpre}enewsad where classid='$classid'");
	while($r=$empire->fetch($a))
	{
		$file="../../../d/js/acmsd/".$public_r[adfile].$r[adid].".js";
		DelFiletext($file);
    }
	*/
	if($sql)
	{
		//操作日志
		insert_dolog("classid=".$classid."<br>classname=".$c[classname]);
		printerror("DelAdClassSuccess","AdClass.php".hReturnEcmsHashStrHref2(1));
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
//增加广告类别
if($enews=="AddAdClass")
{
	$add=$_POST['add'];
	AddAdClass($add,$logininid,$loginin);
}
//修改广告类别
elseif($enews=="EditAdClass")
{
	$add=$_POST['add'];
	EditAdClass($add,$logininid,$loginin);
}
//删除广告类型
elseif($enews=="DelAdClass")
{
	$classid=$_GET['classid'];
	DelAdClass($classid,$logininid,$loginin);
}

$sql=$empire->query("select classid,classname from {$dbtbpre}enewsadclass order by classid desc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="ListAd.php<?=$ecms_hashur['whehref']?>">管理广告</a> &gt; <a href="AdClass.php<?=$ecms_hashur['whehref']?>">管理广告类别</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理广告类别</span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th width="150px">ID</th>
			<th>类别名称</th>
			<th>操作</th>
		</tr>
<?
  while($r=$empire->fetch($sql))
  {
  ?>
  <form name=form2 method=post action=AdClass.php>
	  <?=$ecms_hashur['form']?>
    <input type=hidden name=enews value=EditAdClass>
    <input type=hidden name=add[classid] value=<?=$r[classid]?>>
    <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'">
      <td><div align="center"><?=$r[classid]?></div></td>
      <td height="25"> <div align="center">
          <input name="add[classname]" type="text" id="add[classname]" value="<?=$r[classname]?>">
        </div></td>
      <td height="25"><div align="center"> 
          <input type="submit" name="Submit3" value="修改">
          &nbsp; 
          <input type="button" name="Submit4" value="删除" onclick="self.location.href='AdClass.php?enews=DelAdClass&classid=<?=$r[classid]?><?=$ecms_hashur['href']?>';">
        </div></td>
    </tr>
  </form>
  <?
  }
  db_close();
  $empire=null;
  ?>
<form name="form1" method="post" action="AdClass.php">
  <?=$ecms_hashur['form']?>
<input type=hidden name=enews value=AddAdClass>
  		<tr> 
          <td style="background:#DBEAF5;">增加广告类别:</td>
		  <td colspan="4" style="text-align:left;background:#DBEAF5;">类别名称: 
        <input name="add[classname]" type="text" id="add[classname]">
        <input type="submit" name="Submit" value="增加">
        <input type="reset" name="Submit2" value="重置"></td>
		  </tr>
 </form>
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
