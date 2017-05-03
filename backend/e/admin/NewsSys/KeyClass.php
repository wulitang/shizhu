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
CheckLevel($logininid,$loginin,$classid,"key");

//增加内容关键字分类
function AddKeyClass($classname,$userid,$username){
	global $empire,$dbtbpre;
	if(!$classname)
	{
		printerror("EmptyKeyClass","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"key");
	$classname=hRepPostStr($classname,1);
	$sql=$empire->query("insert into {$dbtbpre}enewskeyclass(classname) values('$classname');");
	$classid=$empire->lastid();
	if($sql)
	{
		//操作日志
		insert_dolog("classid=".$classid."<br>classname=".$classname);
		printerror("AddKeyClassSuccess","KeyClass.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改内容关键字分类
function EditKeyClass($classid,$classname,$userid,$username){
	global $empire,$dbtbpre;
	$classid=(int)$classid;
	if(!$classname||!$classid)
	{
		printerror("EmptyKeyClass","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"key");
	$classname=hRepPostStr($classname,1);
	$sql=$empire->query("update {$dbtbpre}enewskeyclass set classname='$classname' where classid='$classid'");
	if($sql)
	{
		//操作日志
		insert_dolog("classid=".$classid."<br>classname=".$classname);
		printerror("EditKeyClassSuccess","KeyClass.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除内容关键字分类
function DelKeyClass($classid,$userid,$username){
	global $empire,$dbtbpre;
	$classid=(int)$classid;
	if(!$classid)
	{
		printerror("NotKeyClassid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"key");
	$r=$empire->fetch1("select classname from {$dbtbpre}enewskeyclass where classid='$classid'");
	$sql=$empire->query("delete from {$dbtbpre}enewskeyclass where classid='$classid'");
	$sql1=$empire->query("update {$dbtbpre}enewskey set cid=0 where cid='$classid'");
	if($sql)
	{
		//操作日志
		insert_dolog("classid=".$classid."<br>classname=".$r[classname]);
		printerror("DelKeyClassSuccess","KeyClass.php".hReturnEcmsHashStrHref2(1));
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
//增加内容关键字分类
if($enews=="AddKeyClass")
{
	$classname=$_POST['classname'];
	AddKeyClass($classname,$logininid,$loginin);
}
//修改内容关键字分类
elseif($enews=="EditKeyClass")
{
	$classname=$_POST['classname'];
	$classid=$_POST['classid'];
	EditKeyClass($classid,$classname,$logininid,$loginin);
}
//删除内容关键字分类
elseif($enews=="DelKeyClass")
{
	$classid=$_GET['classid'];
	DelKeyClass($classid,$logininid,$loginin);
}

$sql=$empire->query("select classid,classname from {$dbtbpre}enewskeyclass order by classid desc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理内容关键字分类</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href=key.php<?=$ecms_hashur['whehref']?>>管理内容关键字</a>&nbsp;&gt;&nbsp;<a href="KeyClass.php<?=$ecms_hashur['whehref']?>">管理内容关键字分类</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理作者</span></h3>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
<form name="form1" method="post" action="KeyClass.php">
  <?=$ecms_hashur['form']?>
  <input type=hidden name=enews value=AddKeyClass>
  		<tr> 
          <td style="background:#DBEAF5; width:150px;">增加内容关键字分类:</td>
		  <td style="text-align:left;background:#DBEAF5;">分类名称: 
        <input name="classname" type="text" id="classname">
        <input type="submit" name="Submit" value="增加">
        <input type="reset" name="Submit2" value="重置"></td>
		  </tr>
 </form>
</table>
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
            <th>分类名称</th>
			<th>操作</th>
		</tr>
 <?
  while($r=$empire->fetch($sql))
  {
  ?>
  <form name=form2 method=post action=KeyClass.php>
    <input type=hidden name=enews value=EditKeyClass>
    <input type=hidden name=classid value=<?=$r[classid]?>>
    <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'">
      <td><div align="center"><?=$r[classid]?></div></td>
      <td height="25"> <div align="center">
          <input name="classname" type="text" id="classname" value="<?=$r[classname]?>">
        </div></td>
      <td height="25"><div align="center"> 
          <input type="submit" name="Submit3" value="修改">
          &nbsp; 
          <input type="button" name="Submit4" value="删除" onclick="if(confirm('确认要删除?')){self.location.href='KeyClass.php?enews=DelKeyClass&classid=<?=$r[classid]?><?=$ecms_hashur['href']?>';}">
        </div></td>
    </tr>
  </form>
  <?
  }
  db_close();
  $empire=null;
  ?>
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
