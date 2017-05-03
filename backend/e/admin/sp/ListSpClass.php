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
CheckLevel($logininid,$loginin,$classid,"sp");

//增加碎片分类
function AddSpClass($add,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add[classname])
	{
		printerror("EmptySpClassname","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"sp");
	$sql=$empire->query("insert into {$dbtbpre}enewsspclass(classname,classsay) values('$add[classname]','$add[classsay]');");
	$classid=$empire->lastid();
	if($sql)
	{
		//操作日志
		insert_dolog("classid=".$classid."<br>classname=".$add[classname]);
		printerror("AddSpClassSuccess","AddSpClass.php?enews=AddSpClass".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改碎片分类
function EditSpClass($add,$userid,$username){
	global $empire,$dbtbpre;
	$classid=(int)$add[classid];
	if(!$classid||!$add[classname])
	{
		printerror("EmptySpClassname","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"sp");
	$sql=$empire->query("update {$dbtbpre}enewsspclass set classname='$add[classname]',classsay='$add[classsay]' where classid='$classid'");
	if($sql)
	{
		//操作日志
		insert_dolog("classid=".$classid."<br>classname=".$add[classname]);
		printerror("EditSpClassSuccess","ListSpClass.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除碎片分类
function DelSpClass($classid,$userid,$username){
	global $empire,$dbtbpre;
	$classid=(int)$classid;
	if(!$classid)
	{
		printerror("NotDelSpClassid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"sp");
	$r=$empire->fetch1("select classname from {$dbtbpre}enewsspclass where classid='$classid'");
	$sql=$empire->query("delete from {$dbtbpre}enewsspclass where classid='$classid'");
	if($sql)
	{
		//操作日志
		insert_dolog("classid=".$classid."<br>classname=".$r[classname]);
		printerror("DelSpClassSuccess","ListSpClass.php".hReturnEcmsHashStrHref2(1));
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
if($enews=="AddSpClass")//增加碎片分类
{
	AddSpClass($_POST,$logininid,$loginin);
}
elseif($enews=="EditSpClass")//修改碎片分类
{
	EditSpClass($_POST,$logininid,$loginin);
}
elseif($enews=="DelSpClass")//删除碎片分类
{
	$classid=$_GET['classid'];
	DelSpClass($classid,$logininid,$loginin);
}

$search=$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=30;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select classid,classname from {$dbtbpre}enewsspclass";
$totalquery="select count(*) as total from {$dbtbpre}enewsspclass";
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by classid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
$url="<a href=ListSp.php".$ecms_hashur['whehref'].">管理碎片</a>&nbsp;>&nbsp;<a href=ListSpClass.php".$ecms_hashur['whehref'].">管理碎片分类</a>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>碎片分类</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script>
//增加碎片分类
function zjspfl(){
art.dialog.open('sp/AddSpClass.php?<?=$ecms_hashur[ehref]?>&enews=AddSpClass',
    {title: '增加碎片分类',lock: true,opacity: 0.5, width: 650, height:320,
	close: function () {
      location.reload();
    }
	});
}
//修改碎片分类
function edit(classid){
art.dialog.open('sp/AddSpClass.php?<?=$ecms_hashur[ehref]?>&enews=EditSpClass&classid=' + classid,
    {title: '修改碎片分类',lock: true,opacity: 0.5, width: 650, height: 320,
	close: function () {
      location.reload();
    }
	});
}
//删除碎片分类
function del(classid){
art.dialog.open('sp/ListSpClass.php?<?=$ecms_hashur[href]?>&enews=DelSpClass&classid='+classid,
    {title: '删除碎片分类',lock: true,opacity: 0.5, width:600, height:280,
	init: function () {
    	var that = this, i = 2;
        var fn = function () {
            that.title( i + ' 秒后关闭');
            !i && that.close();
            i --;
        };
        timer = setInterval(fn, 1000);
        fn();
    },
	close: function () {
	  clearInterval(timer);
      location.reload();
    }
	});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理碎片分类 <a href="javascript:void(0)" onclick="zjspfl()" class="add">增加碎片分类</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>分类名称</th>
            <th>操作</th>
		</tr>
  <?
  while($r=$empire->fetch($sql))
  {
  ?>
   <tr bgcolor="#FFFFFF"> 
      <td><?=$r[classid]?></td>
      <td><a href="ListSp.php?cid=<?=$r[classid]?>" target="_blank"><?=$r[classname]?></a></td>
	  <td height="25"><div align="center">[<a href="javascript:void(0)" onclick="edit(<?=$r[classid]?>)">修改</a>]&nbsp;[<a href="javascript:del(<?=$r[classid]?>)" onClick="return confirm('确认要删除？');">删除</a>]</div></td>
    </tr>
    <?
  }
  ?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="7"> 
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
<?
db_close();
$empire=null;
?>
