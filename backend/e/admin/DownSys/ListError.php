<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require "../".LoadLang("pub/fun.php");
require("../../data/dbcache/class.php");
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
CheckLevel($logininid,$loginin,$classid,"downerror");

//删除单个报告
function DelError($errorid,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"downerror");
	$errorid=(int)$errorid;
	if(empty($errorid))
	{
		printerror("EmptyDelErrorid","history.go(-1)");
    }
	$sql=$empire->query("delete from {$dbtbpre}enewsdownerror where errorid='$errorid'");
	if($sql)
	{
		//操作日志
		insert_dolog("errorid=$errorid");
		printerror("DelErrorSuccess","ListError.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//批量删除报告
function DelError_all($errorid,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"downerror");
	$count=count($errorid);
	if(empty($count))
	{
		printerror("EmptyDelErrorid","history.go(-1)");
	}
	for($i=0;$i<$count;$i++)
	{
		$add.="errorid='".intval($errorid[$i])."' or ";
	}
	$add=substr($add,0,strlen($add)-4);
	$sql=$empire->query("delete from {$dbtbpre}enewsdownerror where ".$add);
	if($sql)
	{
		//操作日志
		insert_dolog("");
		printerror("DelErrorSuccess","ListError.php".hReturnEcmsHashStrHref2(1));
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
//删除单个错误报告
if($enews=="DelError")
{
	$errorid=$_GET['errorid'];
	DelError($errorid,$logininid,$loginin);
}
//批量删除错误报告
elseif($enews=="DelError_all")
{
	$errorid=$_POST['errorid'];
	DelError_all($errorid,$logininid,$loginin);
}
$start=0;
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$line=15;//每行显示
$page_line=15;
$offset=$page*$line;
//类别
$search='';
$search.=$ecms_hashur['ehref'];
$add="";
$cid=(int)$_GET['cid'];
if($cid)
{
	$add=" where cid='$cid'";
	$search.="&cid=$cid";
}
$totalquery="select count(*) as total from {$dbtbpre}enewsdownerror".$add;
$num=$empire->gettotal($totalquery);//取得总条数
$query="select * from {$dbtbpre}enewsdownerror".$add;
$query.=" order by errorid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
//分类
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewserrorclass order by classid");
while($cr=$empire->fetch($csql))
{
	$select="";
	if($cr[classid]==$cid)
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
<title>管理错误报告</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<script>
function CheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
  }
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <a href="ListError.php<?=$ecms_hashur['whehref']?>">管理错误报告</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
 <form name="chclassform" method="get" action="ListError.php">
 <?=$ecms_hashur['eform']?>
        	<h3><span>限制显示： 
        <select name="cid" onChange="document.chclassform.submit()">
          <option value="0">显示所有分类</option>
          <?=$cstr?>
        </select> <a href="ErrorClass.php<?=$ecms_hashur['whehref']?>" class="gl">管理错误报告分类</a></span></h3>  </form>
            <div class="line"></div>
<form name="form1" method="post" action="ListError.php" onSubmit="return confirm('确认要删除？');" class="anniuqun">
<?=$ecms_hashur['form']?>
<input type=hidden name=cid value="<?=$cid?>">
<?
while($r=$empire->fetch($sql))
{
	if($class_r[$r[classid]][tbname])
	{
		$tr=$empire->fetch1("select title,isurl,titleurl,classid,id from {$dbtbpre}ecms_".$class_r[$r[classid]][tbname]." where id='$r[id]' limit 1");
		$titleurl=sys_ReturnBqTitleLink($tr);
	}
	//分类
	$cr[classname]="---";
	if($r[cid])
	{
		$cr=$empire->fetch1("select classname,classid from {$dbtbpre}enewserrorclass where classid='$r[cid]' limit 1");
	}
?>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr bgcolor="#FFFFFF" class="header"> 
      <td width="57%" height="25">信息标题：<a href="<?=$titleurl?>" target=_blank>
        <?=stripSlashes($tr[title])?>
        </a></td>
      <td width="28%" height="25">所属分类：<a href="ListError.php?cid=<?=$r[cid]?><?=$ecms_hashur['ehref']?>"><?=$cr[classname]?></a></td>
      <td width="15%"><div align="center">
          <input name="errorid[]" type="checkbox" id="errorid[]" value="<?=$r[errorid]?>">
          <a href="ListError.php?enews=DelError&errorid=<?=$r[errorid]?>&cid=<?=$cid?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除？');">删除</a></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">发布者IP： 
        <?=$r[errorip]?>
        &nbsp;(
        <?=stripSlashes($r[email])?>
        ) </td>
      <td height="25" colspan="2">发布时间： 
        <?=$r[errortime]?>
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="3"> <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
          <tr> 
            <td> 
              <?=nl2br(ehtmlspecialchars(stripSlashes($r[errortext])))?>
            </td>
          </tr>
        </table></td>
    </tr>
  </table>
  <?
  }
  db_close();
  $empire=null;
  ?>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="comm-table">
  <tr> 
    <td style="text-align:left;">
      <input type=submit name=submit value=批量删除><input type=hidden name=enews value=DelError_all>
      &nbsp;&nbsp;<input type=checkbox name=chkall value=on onclick=CheckAll(this.form)>
      全选</td>
  	</tr>
    <tr> 
    <td style="height:35px; overflow:hidden;margin:0;background:#F2F2F2; padding:10px 0;">
      <?=$returnpage?>
     </td>
  	</tr>
</table>
</form>
        </div>
	<div class="line"></div>
    </div>
</div>
</div>
</body>
</html>
