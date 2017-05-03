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
CheckLevel($logininid,$loginin,$classid,"writer");

//------------------增加作者
function AddWriter($writer,$email,$userid,$username){
	global $empire,$dbtbpre;
	if(!$writer)
	{printerror("EmptyWriter","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"writer");
	$sql=$empire->query("insert into {$dbtbpre}enewswriter(writer,email) values('$writer','$email');");
	$lastid=$empire->lastid();
	GetConfig();//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("wid=".$lastid."<br>writer=".$writer);
		printerror("AddWriterSuccess","writer.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//----------------修改作者
function EditWriter($wid,$writer,$email,$userid,$username){
	global $empire,$dbtbpre;
	if(!$writer||!$wid)
	{printerror("EmptyWriter","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"writer");
	$wid=(int)$wid;
	$sql=$empire->query("update {$dbtbpre}enewswriter set writer='$writer',email='$email' where wid='$wid'");
	GetConfig();//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("wid=".$wid."<br>writer=".$writer);
		printerror("EditWriterSuccess","writer.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//---------------删除作者
function DelWriter($wid,$userid,$username){
	global $empire,$dbtbpre;
	$wid=(int)$wid;
	if(!$wid)
	{printerror("NotDelWid","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"writer");
	$r=$empire->fetch1("select writer from {$dbtbpre}enewswriter where wid='$wid'");
	$sql=$empire->query("delete from {$dbtbpre}enewswriter where wid='$wid'");
	GetConfig();//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("wid=".$wid."<br>writer=".$r[writer]);
		printerror("DelWriterSuccess","writer.php".hReturnEcmsHashStrHref2(1));
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
if($enews=="AddWriter")
{
	$writer=$_POST['writer'];
	$email=$_POST['email'];
	AddWriter($writer,$email,$logininid,$loginin);
}
elseif($enews=="EditWriter")
{
	$wid=$_POST['wid'];
	$writer=$_POST['writer'];
	$email=$_POST['email'];
	EditWriter($wid,$writer,$email,$logininid,$loginin);
}
elseif($enews=="DelWriter")
{
	$wid=$_GET['wid'];
	DelWriter($wid,$logininid,$loginin);
}
else
{}

$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=30;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$search='';
$search.=$ecms_hashur['ehref'];
$totalquery="select count(*) as total from {$dbtbpre}enewswriter";
$num=$empire->gettotal($totalquery);
$query="select wid,writer,email from {$dbtbpre}enewswriter order by wid desc limit $offset,$line";
$sql=$empire->query($query);
$addwritername=RepPostStr($_GET['addwritername'],1);
$search.="&addwritername=$addwritername";
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理作者</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="writer.php<?=$ecms_hashur['whehref']?>">管理作者</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理作者</span></h3>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
<form name="form1" method="post" action="writer.php">
  <?=$ecms_hashur['form']?>
<input name=enews type=hidden id="enews" value=AddWriter>
  		<tr> 
          <td style="background:#DBEAF5;">增加作者:</td>
		  <td style="text-align:left;background:#DBEAF5;">作者名称: 
        <input name="writer" type="text" id="writer" value="<?=$addwritername?>">
        联系邮箱: 
        <input name="email" type="text" id="email" value="mailto:" size="36"> 
        <input type="submit" name="Submit" value="增加">
        <input type="reset" name="Submit2" value="重置"></td>
		  </tr>
 </form>
</table>
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th>作者</th>
			<th>操作</th>
		</tr>
<?
  while($r=$empire->fetch($sql))
  {
  ?>
  <form name=form2 method=post action=writer.php>
    <input type=hidden name=enews value=EditWriter>
    <input type=hidden name=wid value=<?=$r[wid]?>>
    <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
      <td height="25">作者名称: 
        <input name="writer" type="text" id="writer" value="<?=$r[writer]?>">
        联系邮箱: 
        <input name="email" type="text" id="email" value="<?=$r[email]?>" size="30"> 
      </td>
      <td height="25"><div align="center"> 
          <input type="submit" name="Submit3" value="修改">
          &nbsp; 
          <input type="button" name="Submit4" value="删除" onclick="if(confirm('确认要删除?')){self.location.href='writer.php?enews=DelWriter&wid=<?=$r[wid]?><?=$ecms_hashur['href']?>';}">
        </div></td>
    </tr>
  </form>
  <?
  }
  db_close();
  $empire=null;
  ?>
  <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2" style="height:35px; overflow:hidden;margin:0;background:#F2F2F2; padding:10px 0;">
	  <?=$returnpage?>
	  </td>
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
