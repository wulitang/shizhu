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
CheckLevel($logininid,$loginin,$classid,"user");

//增加部门
function AddUserClass($add,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add[classname])
	{
		printerror("EmptyUserClass","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"user");
	$add[classname]=hRepPostStr($add[classname],1);
	$sql=$empire->query("insert into {$dbtbpre}enewsuserclass(classname) values('".$add[classname]."');");
	$lastid=$empire->lastid();
	if($sql)
	{
		//操作日志
		insert_dolog("classid=".$lastid."<br>classname=".$add[classname]);
		printerror("AddUserClassSuccess","UserClass.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改部门
function EditUserClass($add,$userid,$username){
	global $empire,$dbtbpre;
	$classid=(int)$add[classid];
	if(!$add[classname]||!$classid)
	{
		printerror("EmptyUserClass","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"user");
	$add[classname]=hRepPostStr($add[classname],1);
	$sql=$empire->query("update {$dbtbpre}enewsuserclass set classname='".$add[classname]."' where classid='$classid'");
	if($sql)
	{
		//操作日志
		insert_dolog("classid=".$classid."<br>classname=".$add[classname]);
		printerror("EditUserClassSuccess","UserClass.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除部门
function DelUserClass($classid,$userid,$username){
	global $empire,$dbtbpre;
	$classid=(int)$classid;
	if(!$classid)
	{
		printerror("NotDelUserClassid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"user");
	$r=$empire->fetch1("select classname from {$dbtbpre}enewsuserclass where classid='$classid'");
	$sql=$empire->query("delete from {$dbtbpre}enewsuserclass where classid='$classid'");
	if($sql)
	{
		//操作日志
		insert_dolog("classid=".$classid."<br>classname=".$r[classname]);
		printerror("DelUserClassSuccess","UserClass.php".hReturnEcmsHashStrHref2(1));
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
if($enews=="AddUserClass")//增加部门
{
	AddUserClass($_POST,$logininid,$loginin);
}
elseif($enews=="EditUserClass")//修改部门
{
	EditUserClass($_POST,$logininid,$loginin);
}
elseif($enews=="DelUserClass")//删除部门
{
	$classid=$_GET['classid'];
	DelUserClass($classid,$logininid,$loginin);
}
else
{}

$sql=$empire->query("select classid,classname from {$dbtbpre}enewsuserclass order by classid desc");
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
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="ListUser.php">管理用户</a> &gt; <a href="UserClass.php<?=$ecms_hashur['whehref']?>">管理部门</a></div></div>
<div id="tab" style="margin-top:35px;">

<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理部门</span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th width="150px">ID</th>
			<th>部门名称</th>
			<th>操作</th>
		</tr>
 <?
  while($r=$empire->fetch($sql))
  {
  ?>
  <form name=form2 method=post action=UserClass.php>
	  <?=$ecms_hashur['form']?>
    <input type=hidden name=enews value=EditUserClass>
    <input type=hidden name=classid value=<?=$r[classid]?>>
    <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'">
      <td><div align="center"><?=$r[classid]?></div></td>
      <td height="25"> <div align="center">
          <input name="classname" type="text" id="classname" value="<?=$r[classname]?>">
        </div></td>
      <td height="25"><div align="center"> 
          <input type="submit" name="Submit3" value="修改">
          &nbsp; 
          <input type="button" name="Submit4" value="删除" onclick="if(confirm('确认要删除?')){self.location.href='UserClass.php?enews=DelUserClass&classid=<?=$r[classid]?><?=$ecms_hashur['href']?>';}">
        </div></td>
    </tr>
  </form>
  <?
  }
  db_close();
  $empire=null;
  ?>
<form name="form1" method="post" action="UserClass.php">
  <?=$ecms_hashur['form']?>
        <input name=enews type=hidden id="enews" value=AddUserClass>
  		<tr> 
          <td style="background:#DBEAF5;">增加部门:</td>
		  <td colspan="4" style="text-align:left;background:#DBEAF5;">部门名称: 
        <input name="classname" type="text" id="classname">
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
