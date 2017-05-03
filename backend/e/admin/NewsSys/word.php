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
CheckLevel($logininid,$loginin,$classid,"word");

//------------------增加禁用字符
function AddWord($oldword,$newword,$userid,$username){
	global $empire,$dbtbpre;
	if(!$oldword)
	{printerror("EmptyWord","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"word");
	$sql=$empire->query("insert into {$dbtbpre}enewswords(oldword,newword) values('".eaddslashes($oldword)."','".eaddslashes($newword)."');");
	$wordid=$empire->lastid();
	GetConfig();//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("wordid=".$wordid);
		printerror("AddWordSuccess","word.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//----------------修改禁用字符
function EditWord($wordid,$oldword,$newword,$userid,$username){
	global $empire,$dbtbpre;
	if(!$oldword||!$wordid)
	{printerror("EmptyWord","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"word");
	$wordid=(int)$wordid;
	$sql=$empire->query("update {$dbtbpre}enewswords set oldword='".eaddslashes($oldword)."',newword='".eaddslashes($newword)."' where wordid='$wordid'");
	GetConfig();//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("wordid=".$wordid);
	printerror("EditWordSuccess","word.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//---------------删除禁用字符
function DelWord($wordid,$userid,$username){
	global $empire,$dbtbpre;
	$wordid=(int)$wordid;
	if(!$wordid)
	{printerror("NotDelWordid","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"word");
	$sql=$empire->query("delete from {$dbtbpre}enewswords where wordid='$wordid'");
	GetConfig();//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("wordid=".$wordid);
		printerror("DelWordSuccess","word.php".hReturnEcmsHashStrHref2(1));
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
//增加过滤字符
if($enews=="AddWord")
{
	$oldword=$_POST['oldword'];
	$newword=$_POST['newword'];
	AddWord($oldword,$newword,$logininid,$loginin);
}
//修改过滤字符
elseif($enews=="EditWord")
{
	$wordid=$_POST['wordid'];
	$oldword=$_POST['oldword'];
	$newword=$_POST['newword'];
	EditWord($wordid,$oldword,$newword,$logininid,$loginin);
}
//删除过滤字符
elseif($enews=="DelWord")
{
	$wordid=$_GET['wordid'];
	DelWord($wordid,$logininid,$loginin);
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
$totalquery="select count(*) as total from {$dbtbpre}enewswords";
$num=$empire->gettotal($totalquery);
$query="select wordid,oldword,newword from {$dbtbpre}enewswords order by wordid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>过滤字符</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<style>
#tab textarea { float:none;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="word.php<?=$ecms_hashur['whehref']?>">管理过滤字符</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理作者</span></h3>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
<form name="form1" method="post" action="word.php">
  <input type=hidden name=enews value=AddWord>
<?=$ecms_hashur['form']?>
  		<tr> 
          <td style="background:#DBEAF5;">增加过滤字符:</td>
		  <td valign="middle" style="text-align:left;background:#DBEAF5;">将新闻内容包含 
        <textarea name="oldword" cols="45" rows="3" id="oldword"></textarea>
        替换成 
        <textarea name="newword" cols="45" rows="3" id="newword"></textarea> 
        <input type="submit" name="Submit" value="增加">
        <input type="reset" name="Submit2" value="重置"></td>
		  </tr>
 </form>
</table>
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th>过滤字符</th>
			<th>操作</th>
		</tr>
<?
  while($r=$empire->fetch($sql))
  {
  ?>
  <form name=form2 method=post action=word.php>
    <input type=hidden name=enews value=EditWord>
    <input type=hidden name=wordid value=<?=$r[wordid]?>>
    <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
      <td height="25"> 将新闻内容包含 
        <textarea name="oldword" cols="43" rows="3" id="oldword"><?=ehtmlspecialchars($r[oldword])?></textarea>
        替换成 
        <textarea name="newword" cols="43" rows="3" id="newword"><?=ehtmlspecialchars($r[newword])?></textarea> 
      </td>
      <td height="25"><div align="center"> 
          <input type="submit" name="Submit3" value="修改">
          &nbsp; 
          <input type="button" name="Submit4" value="删除" onclick="if(confirm('确认要删除?')){self.location.href='word.php?enews=DelWord&wordid=<?=$r[wordid]?><?=$ecms_hashur['href']?>';}">
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
