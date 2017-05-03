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
CheckLevel($logininid,$loginin,$classid,"key");

//增加关键字
function AddKey($keyname,$keyurl,$userid,$username){
	global $empire,$dbtbpre;
	$cid=(int)$_POST['cid'];
	$fcid=(int)$_POST['fcid'];
	if(!$keyname||!$keyurl)
	{printerror("EmptyKeyname","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"key");
	$keyname=hRepPostStr($keyname,1);
	$keyurl=hRepPostStr($keyurl,1);
	$sql=$empire->query("insert into {$dbtbpre}enewskey(keyname,keyurl,cid) values('$keyname','$keyurl','$cid');");
	$keyid=$empire->lastid();
	GetConfig();//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("keyid=".$keyid."<br>keyname=".$keyname);
		printerror("AddKeySuccess","key.php?fcid=$fcid".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改关键字
function EditKey($keyid,$keyname,$keyurl,$userid,$username){
	global $empire,$dbtbpre;
	$cid=(int)$_POST['cid'];
	$fcid=(int)$_POST['fcid'];
	if(!$keyname||!$keyurl||!$keyid)
	{printerror("EmptyKeyname","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"key");
	$keyid=(int)$keyid;
	$keyname=hRepPostStr($keyname,1);
	$keyurl=hRepPostStr($keyurl,1);
	$sql=$empire->query("update {$dbtbpre}enewskey set keyname='$keyname',keyurl='$keyurl',cid='$cid' where keyid='$keyid'");
	GetConfig();//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("keyid=".$keyid."<br>keyname=".$keyname);
		printerror("EditKeySuccess","key.php?fcid=$fcid".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除关键字
function DelKey($keyid,$userid,$username){
	global $empire,$dbtbpre;
	$fcid=(int)$_GET['fcid'];
	$keyid=(int)$keyid;
	if(!$keyid)
	{printerror("NotDelKeyid","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"key");
	$r=$empire->fetch1("select keyname from {$dbtbpre}enewskey where keyid='$keyid'");
	$sql=$empire->query("delete from {$dbtbpre}enewskey where keyid='$keyid'");
	GetConfig();//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("keyid=".$keyid."<br>keyname=".$r[keyname]);
		printerror("DelKeySuccess","key.php?fcid=$fcid".hReturnEcmsHashStrHref2(0));
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
//增加关键字
if($enews=="AddKey")
{
	$keyname=$_POST['keyname'];
	$keyurl=$_POST['keyurl'];
	AddKey($keyname,$keyurl,$logininid,$loginin);
}
//修改关键字
elseif($enews=="EditKey")
{
	$keyid=$_POST['keyid'];
	$keyname=$_POST['keyname'];
	$keyurl=$_POST['keyurl'];
	EditKey($keyid,$keyname,$keyurl,$logininid,$loginin);
}
//删除关键字
elseif($enews=="DelKey")
{
	$keyid=$_GET['keyid'];
	DelKey($keyid,$logininid,$loginin);
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
$add='';
//分类
$fcid=(int)$_GET['fcid'];
if($fcid)
{
	$add=" where cid='$fcid'";
	$search.='&fcid='.$fcid;
}
$totalquery="select count(*) as total from {$dbtbpre}enewskey".$add;
$num=$empire->gettotal($totalquery);
$query="select keyid,keyname,keyurl,cid from {$dbtbpre}enewskey".$add." order by keyid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
//分类
$cstr='';
$csql=$empire->query("select classid,classname from {$dbtbpre}enewskeyclass");
while($cr=$empire->fetch($csql))
{
	$cstr.="<option value='$cr[classid]'>$cr[classname]</option>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>关键字</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="key.php<?=$ecms_hashur['whehref']?>">管理内容关键字</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>选择分类： 
      <select name="fcid" id="fcid" onchange=window.location='key.php?<?=$ecms_hashur['ehref']?>&fcid='+this.options[this.selectedIndex].value>
        <option value="0">显示所有分类</option>
		<?=$fcid?str_replace("'$fcid'>","'$fcid' selected>",$cstr):$cstr?>
      </select> <a href="KeyClass.php<?=$ecms_hashur['whehref']?>" class="gl">管理内容关键字分类</a></span></h3>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
  <form name="form1" method="post" action="key.php">
  <input type=hidden name=enews value=AddKey>
  <input type=hidden name=fcid value=<?=$fcid?>>
  		<tr> 
          <td style="background:#DBEAF5;">增加关键字:</td>
		  <td style="text-align:left;background:#DBEAF5;">关键字: 
        <input name="keyname" type="text" id="keyname">
        链接地址:
        <input name="keyurl" type="text" id="keyurl" value="http://" size="30">
        所属分类:
        <select name="cid" id="cid">
          <option value="0">不隶属分类</option>
		  <?=$cstr?>
        </select> 
        <input type="submit" name="Submit" value="增加">
        <input type="reset" name="Submit2" value="重置"></td>
		  </tr>
 </form>
</table>
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th>关键字</th>
			<th>操作</th>
		</tr>
<?
  while($r=$empire->fetch($sql))
  {
  ?>
  <form name=form2 method=post action=key.php>
    <input type=hidden name=enews value=EditKey>
    <input type=hidden name=keyid value=<?=$r[keyid]?>>
	<input type=hidden name=fcid value=<?=$fcid?>>
    <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
      <td height="25">关键字: 
        <input name="keyname" type="text" id="keyname" value="<?=$r[keyname]?>">
        链接地址: 
        <input name="keyurl" type="text" id="keyurl" value="<?=$r[keyurl]?>" size="30">
        所属分类: 
        <select name="cid" id="cid">
          <option value="0">不隶属分类</option>
          <?=$r[cid]?str_replace("'$r[cid]'>","'$r[cid]' selected>",$cstr):$cstr?>
        </select> </td>
      <td height="25"><div align="center"> 
          <input type="submit" name="Submit3" value="修改">
          &nbsp; 
          <input type="button" name="Submit4" value="删除" onclick="if(confirm('确认要删除?')){self.location.href='key.php?enews=DelKey&keyid=<?=$r[keyid]?>&fcid=<?=$fcid?><?=$ecms_hashur['href']?>';}">
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
