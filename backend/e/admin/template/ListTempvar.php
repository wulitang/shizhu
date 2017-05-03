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
CheckLevel($logininid,$loginin,$classid,"tempvar");

//增加模板全局变量
function AddTempvar($add,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add[myvar]||!$add[varvalue]||!$add[varname])
	{printerror("EmptyTempvar","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"tempvar");
	$add[myvar]=hRepPostStr($add[myvar],1);
	$add[varname]=hRepPostStr($add[varname],1);
	$classid=(int)$add[classid];
	$isclose=(int)$add[isclose];
	$add[myorder]=(int)$add[myorder];
	$add[varvalue]=RepPhpAspJspcode($add[varvalue]);
	$gid=(int)$add['gid'];
	$sql=$empire->query("insert into ".GetDoTemptb("enewstempvar",$gid)."(myvar,varname,varvalue,classid,isclose,myorder) values('$add[myvar]','$add[varname]','".eaddslashes2($add[varvalue])."',".$classid.",".$isclose.",$add[myorder]);");
	$lastid=$empire->lastid();
	//备份模板
	AddEBakTemp('tempvar',$gid,$lastid,$add[myvar],$add[varvalue],$add[myorder],0,$add[varname],0,0,'',0,$classid,$isclose,$userid,$username);
	if($sql)
	{
		//操作日志
	    insert_dolog("varid=".$lastid."<br>var=".$add[myvar]."&gid=$gid");
		printerror("AddTempvarSuccess","AddTempvar.php?enews=AddTempvar&gid=$gid".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改模板变量
function EditTempvar($add,$userid,$username){
	global $empire,$dbtbpre;
	$add[varid]=(int)$add['varid'];
	if(!$add[varid]||!$add[myvar]||!$add[varvalue]||!$add[varname])
	{printerror("EmptyTempvar","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"tempvar");
	$add[myvar]=hRepPostStr($add[myvar],1);
	$add[varname]=hRepPostStr($add[varname],1);
	$add[varvalue]=RepPhpAspJspcode($add[varvalue]);
	$classid=(int)$add[classid];
	$isclose=(int)$add[isclose];
	$add[myorder]=(int)$add[myorder];
	$gid=(int)$add['gid'];
	$sql=$empire->query("update ".GetDoTemptb("enewstempvar",$gid)." set myvar='$add[myvar]',varname='$add[varname]',varvalue='".eaddslashes2($add[varvalue])."',classid=$classid,isclose=$isclose,myorder=$add[myorder] where varid='$add[varid]'");
	//备份模板
	AddEBakTemp('tempvar',$gid,$add[varid],$add[myvar],$add[varvalue],$add[myorder],0,$add[varname],0,0,'',0,$classid,$isclose,$userid,$username);
	if($sql)
	{
		//操作日志
		insert_dolog("varid=".$add[varid]."<br>var=".$add[myvar]."&gid=$gid");
		printerror("EditTempvarSuccess","ListTempvar.php?classid=$add[cid]&gid=$gid".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除模板变量
function DelTempvar($varid,$cid,$userid,$username){
	global $empire,$dbtbpre;
	$varid=(int)$varid;
	if(!$varid)
	{printerror("NotDelTempvarid","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"tempvar");
	$gid=(int)$_GET['gid'];
	$r=$empire->fetch1("select myvar from ".GetDoTemptb("enewstempvar",$gid)." where varid='$varid'");
	$sql=$empire->query("delete from ".GetDoTemptb("enewstempvar",$gid)." where varid='$varid'");
	//删除备份记录
	DelEbakTempAll('tempvar',$gid,$varid);
	if($sql)
	{
		//操作日志
		insert_dolog("varid=".$varid."<br>var=".$r[myvar]."&gid=$gid");
		printerror("DelTempvarSuccess","ListTempvar.php?classid=$cid&gid=$gid".hReturnEcmsHashStrHref2(0));
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
	include("../../class/tempfun.php");
}
//增加模板变量
if($enews=="AddTempvar")
{
	$add=$_POST;
	AddTempvar($add,$logininid,$loginin);
}
//修改模板变量
elseif($enews=="EditTempvar")
{
	$add=$_POST;
	EditTempvar($add,$logininid,$loginin);
}
//删除模板变量
elseif($enews=="DelTempvar")
{
	$varid=$_GET['varid'];
	$cid=$_GET['cid'];
	DelTempvar($varid,$cid,$logininid,$loginin);
}

$gid=(int)$_GET['gid'];
$gname=CheckTempGroup($gid);
$urlgname=$gname."&nbsp;>&nbsp;";
$search="&gid=$gid".$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select varid,myvar,varvalue,varname,isclose from ".GetDoTemptb("enewstempvar",$gid);
$totalquery="select count(*) as total from ".GetDoTemptb("enewstempvar",$gid);
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
$query=$query." order by varid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
//类别
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewstempvarclass order by classid");
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
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<title>管理模板变量</title>
<style>
.comm-table td{ height:16px; padding:8px 0;}
</style>
<script type="text/javascript">
//管理模板模板分类
function glmbblfl(){
art.dialog.open('template/TempvarClass.php?<?=$ecms_hashur[ehref]?>&gid=<?=$gid?>',
    {title: '管理模板模板分类',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
</script>
</head>
<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$urlgname?> <a href="ListTempvar.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>">管理模板变量</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">

        	<h3><span>选择类别：
        <select name="classid" id="classid" onchange=window.location='ListTempvar.php?gid=<?=$gid?>&classid='+this.options[this.selectedIndex].value>
          <option value="0">显示所有类别</option>
		  <?=$cstr?>
        </select> <a href="AddTempvar.php?enews=AddTempvar&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" class="add">增加模板变量</a> <a href="javascript:void()" onclick="glmbblfl()" class="gl">管理模板变量分类</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>变量标识</th>
            <th>模板变量名</th>
            <th>开启</th>
            <th>操作</th>
		</tr>
<?
  while($r=$empire->fetch($sql))
  {
  //开启
  if($r[isclose])
  {
  $isclose="<font color=red>关闭</font>";
  }
  else
  {
  $isclose="开启";
  }
  ?>
  <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"> <div align="center"> 
        <?=$r[varid]?>
      </div></td>
	<td height="25"> <div align="center"> 
        <?=$r[varname]?>
      </div></td>
    <td height="25"> <div align="center"> 
        <input name=text1 type=text value="[!--temp.<?=$r[myvar]?>--]" size="32">
      </div></td>
    <td><div align="center"><?=$isclose?></div></td>
    <td height="25"> <div align="center">[<a href="AddTempvar.php?enews=EditTempvar&varid=<?=$r[varid]?>&cid=<?=$classid?>&gid=<?=$gid?><?=$ecms_hashur['ehref']?>">修改</a>]&nbsp;[<a href="ListTempvar.php?enews=DelTempvar&varid=<?=$r[varid]?>&cid=<?=$classid?>&gid=<?=$gid?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
  		<tr>
  		  <td colspan="5" style="text-align:left;"><?=$returnpage?></td>
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
