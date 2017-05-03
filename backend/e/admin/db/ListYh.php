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
CheckLevel($logininid,$loginin,$classid,"yh");

//返回变量处理
function ReturnYhVar($add){
	$add['hlist']=(int)$add['hlist'];
	$add['qlist']=(int)$add['qlist'];
	$add['bqnew']=(int)$add['bqnew'];
	$add['bqhot']=(int)$add['bqhot'];
	$add['bqpl']=(int)$add['bqpl'];
	$add['bqgood']=(int)$add['bqgood'];
	$add['bqfirst']=(int)$add['bqfirst'];
	$add['bqdown']=(int)$add['bqdown'];
	$add['otherlink']=(int)$add['otherlink'];
	$add['qmlist']=(int)$add['qmlist'];
	$add['dobq']=(int)$add['dobq'];
	$add['dojs']=(int)$add['dojs'];
	$add['dosbq']=(int)$add['dosbq'];
	$add['rehtml']=(int)$add['rehtml'];
	return $add;
}

//增加优化方案
function AddYh($add,$userid,$username){
	global $empire,$dbtbpre;
	$add=ReturnYhVar($add);
	if(!$add[yhname])
	{
		printerror("EmptyYhname","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"yh");
	$sql=$empire->query("insert into {$dbtbpre}enewsyh(yhname,yhtext,hlist,qlist,bqnew,bqhot,bqpl,bqgood,bqfirst,bqdown,otherlink,qmlist,dobq,dojs,dosbq,rehtml) values('$add[yhname]','$add[yhtext]','$add[hlist]','$add[qlist]','$add[bqnew]','$add[bqhot]','$add[bqpl]','$add[bqgood]','$add[bqfirst]','$add[bqdown]','$add[otherlink]','$add[qmlist]','$add[dobq]','$add[dojs]','$add[dosbq]','$add[rehtml]');");
	GetClass();//更新缓存
	if($sql)
	{
		$id=$empire->lastid();
		//操作日志
		insert_dolog("id=$id&yhname=$add[yhname]");
		printerror("AddYhSuccess","AddYh.php?enews=AddYh".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改优化方案
function EditYh($add,$userid,$username){
	global $empire,$dbtbpre;
	$add=ReturnYhVar($add);
	$id=(int)$add['id'];
	if(!$id||!$add[yhname])
	{
		printerror("EmptyYhname","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"yh");
	$sql=$empire->query("update {$dbtbpre}enewsyh set yhname='$add[yhname]',yhtext='$add[yhtext]',hlist='$add[hlist]',qlist='$add[qlist]',bqnew='$add[bqnew]',bqhot='$add[bqhot]',bqpl='$add[bqpl]',bqgood='$add[bqgood]',bqfirst='$add[bqfirst]',bqdown='$add[bqdown]',otherlink='$add[otherlink]',qmlist='$add[qmlist]',dobq='$add[dobq]',dojs='$add[dojs]',dosbq='$add[dosbq]',rehtml='$add[rehtml]' where id='$id'");
	GetClass();//更新缓存
	if($sql)
	{
		//操作日志
	    insert_dolog("id=$id&yhname=$add[yhname]");
		printerror("EditYhSuccess","ListYh.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除优化方案
function DelYh($id,$userid,$username){
	global $empire,$dbtbpre;
	$id=(int)$id;
	if(!$id)
	{
		printerror("NotChangeYhid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"yh");
	$r=$empire->fetch1("select yhname from {$dbtbpre}enewsyh where id='$id'");
	$sql=$empire->query("delete from {$dbtbpre}enewsyh where id='$id'");
	GetClass();//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("id=$id&yhname=$r[yhname]");
		printerror("DelYhSuccess","ListYh.php".hReturnEcmsHashStrHref2(1));
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
if($enews=="AddYh")
{
	AddYh($_POST,$logininid,$loginin);
}
elseif($enews=="EditYh")
{
	EditYh($_POST,$logininid,$loginin);
}
elseif($enews=="DelYh")
{
	$id=$_GET['id'];
	DelYh($id,$logininid,$loginin);
}
else
{}
$search=$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=20;//每页显示条数
$page_line=20;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select id,yhname from {$dbtbpre}enewsyh";
$totalquery="select count(*) as total from {$dbtbpre}enewsyh";
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by id desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>优化方案</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/ui.tab.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>

<script type="text/javascript">
$(function(){
			
		});
//增加优化方案
function zjyhfa(){
art.dialog.open('db/AddYh.php?<?=$ecms_hashur[ehref]?>&enews=AddYh',
    {title: '增加优化方案',lock: true,opacity: 0.5, width: 800, height: 540,
	 close: function () {
      location.reload();
    }
	});
}
//修改优化方案
function editfa(id){
art.dialog.open('db/AddYh.php?<?=$ecms_hashur[ehref]?>&enews=EditYh&id='+id,
    {title: '修改优化方案',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//复制优化方案
function copyfa(id){
art.dialog.open('db/AddYh.php?<?=$ecms_hashur[ehref]?>&enews=AddYh&docopy=1&id='+id,
    {title: '复制优化方案',lock: true,opacity: 0.5, width: 800, height: 400,
	close: function () {
      location.reload();
    }
	});
}
//删除优化方案
function delfa(id){
art.dialog.open('db/ListYh.php?<?=$ecms_hashur[href]?>&enews=DelYh&id='+id,
    {title: '删除优化方案',lock: true,opacity: 0.5, width: 800, height: 540,
	init: function () {
    	var that = this, i = 3;
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
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <a href=ListYh.php<?=$ecms_hashur['whehref']?>>管理优化方案</a> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理优化方案 <a href="javascript:void(0)" onclick="zjyhfa()"class="add">增加优化方案</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>方案名称</th>
			<th>操作</th>
		</tr>
  <?
  while($r=$empire->fetch($sql))
  {
  ?>
 <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"> <div align="center"> 
        <?=$r[id]?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$r[yhname]?>
      </div></td>
      <td height="25"> <div align="center"> 
      [<a href="javascript:editfa(<?=$r[id]?>)">修改</a>]&nbsp;[<a href="javascript:copyfa(<?=$r[id]?>)">复制</a>]&nbsp;[<a href="javascript:delfa(<?=$r[id]?>)" onclick="return confirm('确认要删除？');">删除</a>]
      </div></td>
  </tr>
  <?
  }
  ?>
   <tr>
   <td height="25" colspan="6"><?=$returnpage?></td>
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
