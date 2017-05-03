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
CheckLevel($logininid,$loginin,$classid,"template");

//增加js模板
function AddJstemp($add,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add[tempname]||!$add[temptext])
	{
		printerror("EmptyJstempname","history.go(-1)");
    }
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$add[tempname]=hRepPostStr($add[tempname],1);
	$modid=(int)$add['modid'];
	$classid=(int)$add['classid'];
	$subnews=(int)$add['subnews'];
	$subtitle=(int)$add['subtitle'];
	$add[temptext]=str_replace("\r\n","",$add[temptext]);
	$gid=(int)$add['gid'];
	$sql=$empire->query("insert into ".GetDoTemptb("enewsjstemp",$gid)."(tempname,temptext,classid,showdate,modid,subnews,subtitle) values('$add[tempname]','".eaddslashes2($add[temptext])."',$classid,'$add[showdate]','$modid','$subnews','$subtitle');");
	$tempid=$empire->lastid();
	//备份模板
	AddEBakTemp('jstemp',$gid,$tempid,$add[tempname],$add[temptext],$subnews,0,'',0,$modid,$add[showdate],$subtitle,$classid,0,$userid,$username);
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=$tempid&tempname=$add[tempname]&gid=$gid");
		printerror("AddJstempSuccess","AddJstemp.php?enews=AddJstemp&gid=$gid".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改js模板
function EditJstemp($add,$userid,$username){
	global $empire,$dbtbpre;
	$tempid=(int)$add['tempid'];
	if(!$tempid||!$add[tempname]||!$add[temptext])
	{
		printerror("EmptyJstempname","history.go(-1)");
    }
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$add[tempname]=hRepPostStr($add[tempname],1);
	$modid=(int)$add['modid'];
	$classid=(int)$add['classid'];
	$subnews=(int)$add['subnews'];
	$subtitle=(int)$add['subtitle'];
	$add[temptext]=str_replace("\r\n","",$add[temptext]);
	$gid=(int)$add['gid'];
	$sql=$empire->query("update ".GetDoTemptb("enewsjstemp",$gid)." set tempname='$add[tempname]',temptext='".eaddslashes2($add[temptext])."',classid=$classid,showdate='$add[showdate]',modid='$modid',subnews='$subnews',subtitle='$subtitle' where tempid=$tempid");
	//备份模板
	AddEBakTemp('jstemp',$gid,$tempid,$add[tempname],$add[temptext],$subnews,0,'',0,$modid,$add[showdate],$subtitle,$classid,0,$userid,$username);
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=$tempid&tempname=$add[tempname]&gid=$gid");
		printerror("EditJstempSuccess","ListJstemp.php?classid=$add[cid]&gid=$gid".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除js模板
function DelJstemp($add,$userid,$username){
	global $empire,$dbtbpre;
	$tempid=(int)$add['tempid'];
	if(!$tempid)
	{
		printerror("EmptyJstempid","history.go(-1)");
    }
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$gid=(int)$add['gid'];
	$r=$empire->fetch1("select tempname from ".GetDoTemptb("enewsjstemp",$gid)." where tempid=$tempid");
	$sql=$empire->query("delete from ".GetDoTemptb("enewsjstemp",$gid)." where tempid=$tempid");
	//删除备份记录
	DelEbakTempAll('jstemp',$gid,$tempid);
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=$tempid&tempname=$r[tempname]&gid=$gid");
		printerror("DelJstempSuccess","ListJstemp.php?classid=$add[cid]&gid=$gid".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//设为默认js模板
function DefaultJstemp($add,$userid,$username){
	global $empire,$dbtbpre;
	$tempid=(int)$add['tempid'];
	if(!$tempid)
	{
		printerror("EmptyJstempid","history.go(-1)");
    }
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$gid=(int)$add['gid'];
	$r=$empire->fetch1("select tempname from ".GetDoTemptb("enewsjstemp",$gid)." where tempid=$tempid");
	$usql=$empire->query("update ".GetDoTemptb("enewsjstemp",$gid)." set isdefault=0");
	$sql=$empire->query("update ".GetDoTemptb("enewsjstemp",$gid)." set isdefault=1 where tempid=$tempid");
	$psql=$empire->query("update {$dbtbpre}enewspublic set jstempid=$tempid");
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=$tempid&tempname=$r[tempname]&gid=$gid");
		printerror("DefaultJstempSuccess","ListJstemp.php?classid=$add[cid]&gid=$gid".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//操作
$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
	include("../../class/tempfun.php");
}
//增加JS模板
if($enews=="AddJstemp")
{
	AddJstemp($_POST,$logininid,$loginin);
}
//修改JS模板
elseif($enews=="EditJstemp")
{
	EditJstemp($_POST,$logininid,$loginin);
}
//删除JS模板
elseif($enews=="DelJstemp")
{
	DelJstemp($_GET,$logininid,$loginin);
}
//默认JS模板
elseif($enews=="DefaultJstemp")
{
	DefaultJstemp($_GET,$logininid,$loginin);
}
$gid=(int)$_GET['gid'];
$gname=CheckTempGroup($gid);
$urlgname=$gname."&nbsp;>&nbsp;";
$url=$urlgname."<a href=ListJstemp.php?gid=$gid".$ecms_hashur['ehref'].">管理JS模板</a>";
$search="&gid=$gid".$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select tempid,tempname,isdefault from ".GetDoTemptb("enewsjstemp",$gid);
$totalquery="select count(*) as total from ".GetDoTemptb("enewsjstemp",$gid);
//类别
$add="";
$classid=(int)$_GET['classid'];
if($classid)
{
	$add=" where classid=$classid";
	$search.="&classid=$classid";
}
$query.=$add;
$totalquery.=$add;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by tempid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
//分类
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewsjstempclass order by classid");
while($cr=$empire->fetch($csql))
{
	$select="";
	if($cr[classid]==$classid)
	{
		$select=" selected";
	}
	$cstr.="<option value='".$cr[classid]."'".$select.">".$cr[classname]."</option>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理JS模板</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<style>
.comm-table td{ height:16px; padding:8px 0;}
</style>
<script type="text/javascript">
//管理JS模板分类
function gljsmbfl(){
art.dialog.open('template/JsTempClass.php?<?=$ecms_hashur[ehref]?>&gid=<?=$gid?>',
    {title: '管理JS模板分类',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">

        	<h3>
              <form name="form1" method="get" action="ListJstemp.php">
 <?=$ecms_hashur['eform']?>
  <input type=hidden name=gid value="<?=$gid?>">
            <span>限制显示： 
        <select name="classid" id="classid" onChange="document.form1.submit()">
          <option value="0">显示所有分类</option>
		  <?=$cstr?>
        </select> <a href="AddJstemp.php?enews=AddJstemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" class="add">增加JS模板</a> <a href="javascript:void()" onclick="gljsmbfl()" class="gl">管理JS模板分类</a></span>
        </form>
        </h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>模板名</th>
            <th>操作</th>
		</tr>
<?
  while($r=$empire->fetch($sql))
  {
  $color="#ffffff";
  $movejs=' onmouseout="this.style.backgroundColor=\'#ffffff\'" onmouseover="this.style.backgroundColor=\'#C3EFFF\'"';
  if($r[isdefault])
  {
  $color="#DBEAF5";
  $movejs='';
  }
  ?>
  <tr bgcolor="<?=$color?>"<?=$movejs?>> 
    <td height="25"><div align="center"> 
        <?=$r[tempid]?>
      </div></td>
    <td height="25"><div align="center"> 
        <?=$r[tempname]?>
      </div></td>
    <td height="25"><div align="center"> [<a href="AddJstemp.php?enews=EditJstemp&tempid=<?=$r[tempid]?>&cid=<?=$classid?>&gid=<?=$gid?><?=$ecms_hashur['ehref']?>">修改</a>] 
        [<a href="AddJstemp.php?enews=AddJstemp&docopy=1&tempid=<?=$r[tempid]?>&cid=<?=$classid?>&gid=<?=$gid?><?=$ecms_hashur['ehref']?>">复制</a>] 
        [<a href="ListJstemp.php?enews=DefaultJstemp&tempid=<?=$r[tempid]?>&cid=<?=$classid?>&gid=<?=$gid?><?=$ecms_hashur['href']?>" onclick="return confirm('确认设为默认？');">设为默认</a>] 
        [<a href="ListJstemp.php?enews=DelJstemp&tempid=<?=$r[tempid]?>&cid=<?=$classid?>&gid=<?=$gid?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
  		<tr>
  		  <td colspan="3"><?=$returnpage?></td>
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
