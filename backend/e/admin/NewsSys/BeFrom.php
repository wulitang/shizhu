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
CheckLevel($logininid,$loginin,$classid,"befrom");

//增加来源
function AddBefrom($sitename,$siteurl,$userid,$username){
	global $empire,$dbtbpre;
	if(!$sitename||!$siteurl)
	{
		printerror("EmptyBefrom","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"befrom");
	$sitename=hRepPostStr($sitename,1);
	$siteurl=hRepPostStr($siteurl,1);
	$sql=$empire->query("insert into {$dbtbpre}enewsbefrom(sitename,siteurl) values('".$sitename."','".$siteurl."');");
	$lastid=$empire->lastid();
	GetConfig();//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("befromid=".$lastid."<br>sitename=".$sitename);
		printerror("AddBefromSuccess","BeFrom.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改来源
function EditBefrom($befromid,$sitename,$siteurl,$userid,$username){
	global $empire,$dbtbpre;
	if(!$sitename||!$siteurl||!$befromid)
	{
		printerror("EmptyBefrom","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"befrom");
	$befromid=(int)$befromid;
	$sitename=hRepPostStr($sitename,1);
	$siteurl=hRepPostStr($siteurl,1);
	$sql=$empire->query("update {$dbtbpre}enewsbefrom set sitename='".$sitename."',siteurl='".$siteurl."' where befromid='$befromid'");
	GetConfig();//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("befromid=".$befromid."<br>sitename=".$sitename);
		printerror("EditBefromSuccess","BeFrom.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除来源
function DelBefrom($befromid,$userid,$username){
	global $empire,$dbtbpre;
	$befromid=(int)$befromid;
	if(!$befromid)
	{
		printerror("NotDelBefromid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"befrom");
	$r=$empire->fetch1("select sitename from {$dbtbpre}enewsbefrom where befromid='$befromid'");
	$sql=$empire->query("delete from {$dbtbpre}enewsbefrom where befromid='$befromid'");
	GetConfig();//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("befromid=".$befromid."<br>sitename=".$r[sitename]);
		printerror("DelBefromSuccess","BeFrom.php".hReturnEcmsHashStrHref2(1));
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
//增加信息来源
if($enews=="AddBefrom")
{
	$sitename=$_POST['sitename'];
	$siteurl=$_POST['siteurl'];
	AddBefrom($sitename,$siteurl,$logininid,$loginin);
}
//修改信息来源
elseif($enews=="EditBefrom")
{
	$sitename=$_POST['sitename'];
	$siteurl=$_POST['siteurl'];
	$befromid=$_POST['befromid'];
	EditBefrom($befromid,$sitename,$siteurl,$logininid,$loginin);
}
//删除信息来源
elseif($enews=="DelBefrom")
{
	$befromid=$_GET['befromid'];
	DelBefrom($befromid,$logininid,$loginin);
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
$totalquery="select count(*) as total from {$dbtbpre}enewsbefrom";
$num=$empire->gettotal($totalquery);
$query="select sitename,siteurl,befromid from {$dbtbpre}enewsbefrom order by befromid desc limit $offset,$line";
$sql=$empire->query($query);
$addsitename=ehtmlspecialchars($_GET['addsitename']);
$search.="&addsitename=$addsitename";
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>信息来源</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="BeFrom.php<?=$ecms_hashur['whehref']?>">管理信息来源</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理广告类别</span></h3>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
    <form name="form1" method="post" action="BeFrom.php">
      <?=$ecms_hashur['form']?>
  <input type=hidden name=enews value=AddBefrom>
  		<tr> 
          <td style="background:#DBEAF5;">增加信息来源:</td>
		  <td style="text-align:left;background:#DBEAF5;">站点名称: 
        <input name="sitename" type="text" id="sitename" value="<?=$addsitename?>">
        链接地址:
        <input name="siteurl" type="text" id="siteurl" value="http://" size="50"> 
        <input type="submit" name="Submit" value="增加">
        <input type="reset" name="Submit2" value="重置"></td>
		  </tr>
 </form>
</table>
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th>信息来源</th>
			<th>操作</th>
		</tr>
<?
  while($r=$empire->fetch($sql))
  {
  ?>
  <form name=form2 method=post action=BeFrom.php>
	  <?=$ecms_hashur['form']?>
    <input type=hidden name=enews value=EditBefrom>
    <input type=hidden name=befromid value=<?=$r[befromid]?>>
    <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
      <td height="25">站点名称: 
        <input name="sitename" type="text" id="sitename" value="<?=$r[sitename]?>">
        链接地址: 
        <input name="siteurl" type="text" id="siteurl" value="<?=$r[siteurl]?>" size="30"> 
      </td>
      <td height="25"><div align="center"> 
          <input type="submit" name="Submit3" value="修改">
          &nbsp; 
          <input type="button" name="Submit4" value="删除" onclick="if(confirm('确认要删除?')){self.location.href='BeFrom.php?enews=DelBefrom&befromid=<?=$r[befromid]?><?=$ecms_hashur['href']?>';}">
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
