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
CheckLevel($logininid,$loginin,$classid,"link");

//------------------增加友情链接
function AddLink($add,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add[lname]||!$add[lurl])
	{printerror("EmptyLname","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"link");
	$ltime=date("Y-m-d H:i:s");
	$add[lname]=hRepPostStr($add[lname],1);
	$add[lpic]=hRepPostStr($add[lpic],1);
	$add[lurl]=hRepPostStr($add[lurl],1);
	$add[email]=hRepPostStr($add[email],1);
	$add[onclick]=(int)$add[onclick];
	$add[myorder]=(int)$add[myorder];
	$add[ltype]=(int)$add[ltype];
	$add[checked]=(int)$add[checked];
	$add[classid]=(int)$add[classid];
	$add[cid]=(int)$add[cid];
	$sql=$empire->query("insert into {$dbtbpre}enewslink(lname,lpic,lurl,ltime,onclick,width,height,target,myorder,email,lsay,ltype,checked,classid) values('".$add[lname]."','".$add[lpic]."','".$add[lurl]."','$ltime',$add[onclick],'$add[width]','$add[height]','$add[target]',$add[myorder],'".$add[email]."','".eaddslashes($add[lsay])."',$add[ltype],$add[checked],$add[classid]);");
	$lastid=$empire->lastid();
	if($sql)
	{
		//操作日志
		insert_dolog("lid=".$lastid."<br>lname=".$add[lname]);
		printerror("AddLinkSuccess","AddLink.php?enews=AddLink&cid=$add[cid]".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//----------------修改友情链接
function EditLink($add,$userid,$username){
	global $empire,$dbtbpre;
	$add[lid]=(int)$add[lid];
	if(!$add[lname]||!$add[lurl]||!$add[lid])
	{printerror("EmptyLname","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"link");
	$add[lname]=hRepPostStr($add[lname],1);
	$add[lpic]=hRepPostStr($add[lpic],1);
	$add[lurl]=hRepPostStr($add[lurl],1);
	$add[email]=hRepPostStr($add[email],1);
	$add[onclick]=(int)$add[onclick];
	$add[myorder]=(int)$add[myorder];
	$add[ltype]=(int)$add[ltype];
	$add[checked]=(int)$add[checked];
	$add[classid]=(int)$add[classid];
	$add[cid]=(int)$add[cid];
	$sql=$empire->query("update {$dbtbpre}enewslink set lname='".$add[lname]."',lpic='".$add[lpic]."',lurl='".$add[lurl]."',onclick=$add[onclick],width='$add[width]',height='$add[height]',target='$add[target]',myorder=$add[myorder],email='".$add[email]."',lsay='".eaddslashes($add[lsay])."',ltype=$add[ltype],checked=$add[checked],classid=$add[classid] where lid='$add[lid]'");
	if($sql)
	{
		//操作日志
		insert_dolog("lid=".$add[lid]."<br>lname=".$add[lname]);
		printerror("EditLinkSuccess","ListLink.php?classid=$add[cid]".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//---------------删除友情链接
function DelLink($lid,$cid,$userid,$username){
	global $empire,$dbtbpre;
	$lid=(int)$lid;
	$cid=(int)$cid;
	if(!$lid)
	{printerror("EmptyLid","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"link");
	$r=$empire->fetch1("select lname from {$dbtbpre}enewslink where lid='$lid'");
	$sql=$empire->query("delete from {$dbtbpre}enewslink where lid='$lid'");
	if($sql)
	{
		//操作日志
		insert_dolog("lid=".$lid."<br>lname=".$r[lname]);
		printerror("DelLinkSuccess","ListLink.php?classid=$cid".hReturnEcmsHashStrHref2(0));
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
if($enews=="AddLink")
{
	AddLink($_POST,$logininid,$loginin);
}
elseif($enews=="EditLink")
{
	EditLink($_POST,$logininid,$loginin);
}
elseif($enews=="DelLink")
{
	$lid=$_GET['lid'];
	$cid=$_GET['cid'];
	DelLink($lid,$cid,$logininid,$loginin);
}
else
{}

$search=$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=16;//每页显示条数
$page_line=25;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select * from {$dbtbpre}enewslink";
$totalquery="select count(*) as total from {$dbtbpre}enewslink";
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
$query=$query." order by myorder,lid limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
//类别
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewslinkclass order by classid");
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
<title>管理友情链接</title>
<style>
.comm-table td{ padding:6px 0; height:16px;}
</style>
<SCRIPT>
//增加友情链接
function zjyqlj(){
art.dialog.open('tool/AddLink.php?<?=$ecms_hashur[ehref]?>&enews=AddLink',
    {title: '增加友情链接',lock: true,opacity: 0.5,width: 800, height: 570,
	 close: function () {
      location.reload();
    }
	});
}
</SCRIPT>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="ListLink.php<?=$ecms_hashur['whehref']?>">管理友情链接</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>选择类别： 
            <select name="classid" id="classid" onchange=window.location='ListLink.php?<?=$ecms_hashur['ehref']?>&classid='+this.options[this.selectedIndex].value>
        <option value="0">显示所有类别</option>
        <?=$cstr?>
      </select> <a href="javascript:zjyqlj()" class="add">增加友情链接</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th width="150px">ID</th>
			<th>预览</th>
			<th>点击</th>
            <th>状态</th>
            <th>操作</th>
		</tr>
<?
  while($r=$empire->fetch($sql))
  {
  //文字
  if(empty($r[lpic]))
  {
  $logo="<a href='".$r[lurl]."' title='".$r[lname]."' target=".$r[target].">".$r[lname]."</a>";
  }
  //图片
  else
  {
  $logo="<a href='".$r[lurl]."' target=".$r[target]."><img src='".$r[lpic]."' alt='".$r[lname]."' border=0 width='".$r[width]."' height='".$r[height]."'></a>";
  }
  if(empty($r[checked]))
  {$checked="关闭";}
  else
  {$checked="显示";}
  ?>
  <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"> <div align="center"> 
        <?=$r[lid]?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$logo?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$r[onclick]?>
      </div></td>
    <td><div align="center"><?=$checked?></div></td>
    <td height="25"> <div align="center">[<a href="AddLink.php?enews=EditLink&lid=<?=$r[lid]?>&cid=<?=$classid?><?=$ecms_hashur['ehref']?>">修改</a>]&nbsp;[<a href="ListLink.php?enews=DelLink&lid=<?=$r[lid]?>&cid=<?=$classid?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
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
