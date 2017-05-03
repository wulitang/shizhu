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
CheckLevel($logininid,$loginin,$classid,"userjs");

//增加用户自定义js
function AddUserjs($add,$userid,$username){
	global $empire,$dbtbpre;
	$cid=(int)$add['cid'];
	$jstempid=(int)$add['jstempid'];
	if(!$add[jsname]||!$jstempid||!$add[jssql]||!$add[jsfilename])
	{
		printerror("EmptyUserJsname","history.go(-1)");
	}
	$query_first=substr($add['jssql'],0,7);
	if(!($query_first=="select "||$query_first=="SELECT "))
	{
		printerror("JsSqlError","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"userjs");
	$add[jssql]=ClearAddsData($add[jssql]);
	$add[jsname]=hRepPostStr($add[jsname],1);
	$add['classid']=(int)$add['classid'];
	$sql=$empire->query("insert into {$dbtbpre}enewsuserjs(jsname,jssql,jstempid,jsfilename,classid) values('$add[jsname]','".addslashes($add[jssql])."',$jstempid,'$add[jsfilename]','$add[classid]');");
	//刷新js
	ReUserjs($add,"../");
	if($sql)
	{
		$jsid=$empire->lastid();
		//操作日志
		insert_dolog("jsid=$jsid&jsname=$add[jsname]");
		printerror("AddUserjsSuccess","AddUserjs.php?enews=AddUserjs&classid=$cid".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改用户自定义js
function EditUserjs($add,$userid,$username){
	global $empire,$dbtbpre;
	$cid=(int)$add['cid'];
	$jsid=(int)$add['jsid'];
	$jstempid=(int)$add['jstempid'];
	if(!$jsid||!$add[jsname]||!$jstempid||!$add[jssql]||!$add[jsfilename])
	{
		printerror("EmptyUserJsname","history.go(-1)");
	}
	$query_first=substr($add['jssql'],0,7);
	if(!($query_first=="select "||$query_first=="SELECT "))
	{
		printerror("JsSqlError","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"userjs");
	//删除旧js文件
	if($add['oldjsfilename']<>$add['jsfilename'])
	{
		DelFiletext($add['oldjsfilename']);
	}
	$add[jssql]=ClearAddsData($add[jssql]);
	$add[jsname]=hRepPostStr($add[jsname],1);
	$add['classid']=(int)$add['classid'];
	$sql=$empire->query("update {$dbtbpre}enewsuserjs set jsname='$add[jsname]',jssql='".addslashes($add[jssql])."',jstempid=$jstempid,jsfilename='$add[jsfilename]',classid='$add[classid]' where jsid=$jsid");
	//刷新js
	ReUserjs($add,"../");
	if($sql)
	{
		//操作日志
	    insert_dolog("jsid=$jsid&jsname=$add[jsname]");
		printerror("EditUserjsSuccess","ListUserjs.php?classid=$cid".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除用户自定义js
function DelUserjs($jsid,$userid,$username){
	global $empire,$dbtbpre;
	$cid=(int)$add['cid'];
	$jsid=(int)$jsid;
	if(!$jsid)
	{
		printerror("NotChangeUserjsid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"userjs");
	$r=$empire->fetch1("select jsname,jsfilename from {$dbtbpre}enewsuserjs where jsid=$jsid");
	$sql=$empire->query("delete from {$dbtbpre}enewsuserjs where jsid=$jsid");
	//删除文件
	DelFiletext("../".$r['jsfilename']);
	if($sql)
	{
		//操作日志
		insert_dolog("jsid=$jsid&jsname=$r[jsname]");
		printerror("DelUserjsSuccess","ListUserjs.php?classid=$cid".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//刷新自定义JS
function DoReUserjs($add,$userid,$username){
	global $empire,$dbtbpre;
	//操作权限
	CheckLevel($userid,$username,$classid,"userjs");
	$jsid=$add['jsid'];
	$count=count($jsid);
	if(!$count)
	{
		printerror("EmptyReUserjsid","history.go(-1)");
    }
	for($i=0;$i<$count;$i++)
	{
		$jsid[$i]=(int)$jsid[$i];
		if(empty($jsid[$i]))
		{
			continue;
		}
		$ur=$empire->fetch1("select jsid,jsname,jssql,jstempid,jsfilename from {$dbtbpre}enewsuserjs where jsid='".$jsid[$i]."'");
		ReUserjs($ur,'../');
	}
	//操作日志
	insert_dolog("");
	printerror("DoReUserjsSuccess",$_SERVER['HTTP_REFERER']);
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
	require("../../data/dbcache/class.php");
}
if($enews=="AddUserjs")
{
	AddUserjs($_POST,$logininid,$loginin);
}
elseif($enews=="EditUserjs")
{
	EditUserjs($_POST,$logininid,$loginin);
}
elseif($enews=="DelUserjs")
{
	$jsid=$_GET['jsid'];
	DelUserjs($jsid,$logininid,$loginin);
}
elseif($enews=="DoReUserjs")
{
	DoReUserjs($_POST,$logininid,$loginin);
}
else
{}
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=20;//每页显示条数
$page_line=20;//每页显示链接数
$offset=$page*$line;//总偏移量
$search='';
$search.=$ecms_hashur['ehref'];
$query="select jsid,jsname,jsfilename from {$dbtbpre}enewsuserjs";
$totalquery="select count(*) as total from {$dbtbpre}enewsuserjs";
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
$query=$query." order by jsid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
//分类
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewsuserjsclass order by classid");
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
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<title>管理用户自定义JS</title>
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
 //增加自定义JS
function zjzdyjs(){
art.dialog.open('other/AddUserjs.php?<?=$ecms_hashur[ehref]?>&enews=AddUserjs',
    {title: '增加自定义JS',lock: true,opacity: 0.5, width: 800, height: 540,
	 close: function () {
      location.reload();
    }
	});
}
//管理自定义js分类
function glzdyjsfl(){
art.dialog.open('other/UserjsClass.php<?=$ecms_hashur[whehref]?>',
    {title: '管理自定义JS分类',lock: true,opacity: 0.5, width: 800, height: 540,
	 close: function () {
      location.reload();
    }
	});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href=ListUserjs.php<?=$ecms_hashur[whehref]?>>管理用户自定义JS</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>选择类别：
      <select name="classid" id="classid" onchange=window.location='ListUserjs.php?classid='+this.options[this.selectedIndex].value>
          <option value="0">显示所有类别</option>
          <?=$cstr?>
        </select> <a href="javascript:void(0)" onclick="zjzdyjs()" class="add">增加自定义JS</a> <a href="javascript:void(0)" onclick="glzdyjsfl()" class="gl">管理自定义JS分类</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<form name="form1" method="post" action="ListUserjs.php">
<?=$ecms_hashur['form']?>
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th><input type=checkbox name=chkall value=on onclick=CheckAll(this.form)></th>
			<th>ID</th>
			<th>JS名称</th>
            <th>JS地址</th>
            <th>预览</th>
            <th>操作</th>
		</tr>
  <?
  while($r=$empire->fetch($sql))
  {
  $jspath=$public_r['newsurl'].str_replace("../../","",$r['jsfilename']);
  ?>
  <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'">
    <td><div align="center">
        <input name="jsid[]" type="checkbox" id="jsid[]" value="<?=$r[jsid]?>">
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$r[jsid]?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$r[jsname]?>
      </div></td>
    <td height="25"> <div align="center"> 
        <input name="jspath" type="text" id="jspath" value="<?=$jspath?>">
      </div></td>
    <td><div align="center">[<a href="../view/js.php?js=<?=$jspath?>&classid=1<?=$ecms_hashur['ehref']?>" target="_blank">预览</a>]</div></td>
    <td height="25"> <div align="center">[<a href="AddUserjs.php?enews=EditUserjs&jsid=<?=$r[jsid]?>&cid=<?=$classid?><?=$ecms_hashur['ehref']?>">修改</a>]&nbsp;[<a href="AddUserjs.php?enews=AddUserjs&docopy=1&jsid=<?=$r[jsid]?>&cid=<?=$classid?><?=$ecms_hashur['ehref']?>">复制</a>]&nbsp;[<a href="ListUserjs.php?enews=DelUserjs&jsid=<?=$r[jsid]?>&cid=<?=$classid?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="6" style="text-align:left;"> 
        <?=$returnpage?>
      &nbsp;&nbsp;&nbsp; <input type="submit" name="Submit3" value="刷新"> <input name="enews" type="hidden" id="enews" value="DoReUserjs"> 
      </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25" colspan="6" style="text-align:left;">JS调用方法： 
      <input name="textfield" type="text" size="60" value="&lt;script src=&quot;JS地址&quot;&gt;&lt;/script&gt;">&nbsp;</td>
    </tr>
	</tbody>
</table>
</form>
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
