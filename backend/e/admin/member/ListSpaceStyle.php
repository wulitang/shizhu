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
CheckLevel($logininid,$loginin,$classid,"spacestyle");

//返回会员组
function ReturnSpaceStyleMemberGroup($membergroup){
	$count=count($membergroup);
	if($count==0)
	{
		return '';
	}
	$mg='';
	for($i=0;$i<$count;$i++)
	{
		$mg.=$membergroup[$i].',';
	}
	if($mg)
	{
		$mg=','.$mg;
	}
	return $mg;
}

//增加会员空间模板
function AddSpaceStyle($add,$userid,$username){
	global $empire,$dbtbpre;
	if(empty($add[stylename])||empty($add[stylepath]))
	{
		printerror('EmptySpaceStyle','history.go(-1)');
	}
	$add[stylepath]=RepPathStr($add[stylepath]);
	$add['stylepath']=RepPostStr($add['stylepath'],1);
	//目录是否存在
	if(!file_exists("../../space/template/".$add[stylepath]))
	{
		printerror("EmptySpaceStylePath","history.go(-1)");
	}
	$mg=ReturnSpaceStyleMemberGroup($add['membergroup']);
	$sql=$empire->query("insert into {$dbtbpre}enewsspacestyle(stylename,stylepic,stylesay,stylepath,isdefault,membergroup) values('$add[stylename]','$add[stylepic]','$add[stylesay]','$add[stylepath]',0,'$mg');");
	if($sql)
	{
		$styleid=$empire->lastid();
		insert_dolog("styleid=$styleid&stylename=$add[stylename]");//操作日志
		printerror("AddSpaceStyleSuccess","AddSpaceStyle.php?enews=AddSpaceStyle".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改会员空间模板
function EditSpaceStyle($add,$userid,$username){
	global $empire,$dbtbpre;
	$styleid=intval($add[styleid]);
	if(empty($add[stylename])||empty($add[stylepath])||!$styleid)
	{
		printerror('EmptySpaceStyle','history.go(-1)');
	}
	$add[stylepath]=RepPathStr($add[stylepath]);
	$add['stylepath']=RepPostStr($add['stylepath'],1);
	//目录是否存在
	if(!file_exists("../../space/template/".$add[stylepath]))
	{
		printerror("EmptySpaceStylePath","history.go(-1)");
	}
	$mg=ReturnSpaceStyleMemberGroup($add['membergroup']);
	$sql=$empire->query("update {$dbtbpre}enewsspacestyle set stylename='$add[stylename]',stylepic='$add[stylepic]',stylesay='$add[stylesay]',stylepath='$add[stylepath]',membergroup='$mg' where styleid='$styleid'");
	if($sql)
	{
		insert_dolog("styleid=$styleid&stylename=$add[stylename]");//操作日志
		printerror("EditSpaceStyleSuccess","ListSpaceStyle.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除会员空间模板
function DelSpaceStyle($add,$userid,$username){
	global $empire,$dbtbpre;
	$styleid=intval($add[styleid]);
	if(!$styleid)
	{
		printerror('EmptySpaceStyleid','history.go(-1)');
	}
	$r=$empire->fetch1("select stylename,isdefault from {$dbtbpre}enewsspacestyle where styleid='$styleid'");
	if($r[isdefault])
	{
		printerror('NotDelDefSpaceStyle','history.go(-1)');
	}
	$sql=$empire->query("delete from {$dbtbpre}enewsspacestyle where styleid='$styleid'");
	if($sql)
	{
		insert_dolog("styleid=$styleid&stylename=$r[stylename]");//操作日志
		printerror("DelSpaceStyleSuccess","ListSpaceStyle.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//默认会员空间模板
function DefSpaceStyle($add,$userid,$username){
	global $empire,$dbtbpre;
	$styleid=intval($add[styleid]);
	if(!$styleid)
	{
		printerror('EmptyDefSpaceStyleid','history.go(-1)');
	}
	$r=$empire->fetch1("select stylename from {$dbtbpre}enewsspacestyle where styleid='$styleid'");
	$usql=$empire->query("update {$dbtbpre}enewsspacestyle set isdefault=0");
	$sql=$empire->query("update {$dbtbpre}enewsspacestyle set isdefault=1 where styleid='$styleid'");
	$upsql=$empire->query("update {$dbtbpre}enewspublic set defspacestyleid='$styleid'");
	if($sql)
	{
		GetConfig();
		insert_dolog("styleid=$styleid&stylename=$r[stylename]");//操作日志
		printerror("DefSpaceStyleSuccess","ListSpaceStyle.php".hReturnEcmsHashStrHref2(1));
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
if($enews=="AddSpaceStyle")
{
	AddSpaceStyle($_POST,$logininid,$loginin);
}
elseif($enews=="EditSpaceStyle")
{
	EditSpaceStyle($_POST,$logininid,$loginin);
}
elseif($enews=="DelSpaceStyle")
{
	DelSpaceStyle($_GET,$logininid,$loginin);
}
elseif($enews=="DefSpaceStyle")
{
	DefSpaceStyle($_GET,$logininid,$loginin);
}

$search=$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=16;//每页显示条数
$page_line=25;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select * from {$dbtbpre}enewsspacestyle";
$totalquery="select count(*) as total from {$dbtbpre}enewsspacestyle";
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by styleid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
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
<title>会员空间模板</title>
<SCRIPT>
//增加会员空间模板
function zjhykjmb(){
art.dialog.open('member/AddSpaceStyle.php?<?=$ecms_hashur[ehref]?>&enews=AddSpaceStyle',
    {title: '增加会员空间模板',lock: true,opacity: 0.5,width: 800, height: 480,
	 close: function () {
      location.reload();
    }
	});
}
//修改会员空间模板
function xghykjmb(styleid){
art.dialog.open('member/AddSpaceStyle.php?<?=$ecms_hashur[ehref]?>&enews=EditSpaceStyle&styleid='+styleid,
    {title: '修改会员空间模板',lock: true,opacity: 0.5,width: 800, height: 480,
	 close: function () {
      location.reload();
    }
	});
}
</SCRIPT>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="ListSpaceStyle.php<?=$ecms_hashur['whehref']?>">管理会员空间模板</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理会员空间模板 <a href="javascript:zjhykjmb()" class="add">增加会员空间模板</a></span></h3>
            <div class="line"></div>
<div class="anniuqun">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:150px;">ID</th>
			<th>模板名称</th>
            <th>操作</th>
		</tr>
 <?php
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
    <td height="25"> <div align="center"> 
        <?=$r[styleid]?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$r[stylename]?>
      </div></td>
    <td height="25"> <div align="center">[<a href="ListSpaceStyle.php?enews=DefSpaceStyle&styleid=<?=$r[styleid]?><?=$ecms_hashur['href']?>">设为默认</a>] [<a href="javascript:xghykjmb(<?=$r[styleid]?>)">修改</a>]&nbsp;[<a href="ListSpaceStyle.php?enews=DelSpaceStyle&styleid=<?=$r[styleid]?><?=$ecms_hashur['href']?>" onClick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
  		<tr>
  		  <td colspan="3" style="height:35px; overflow:hidden;margin:0;background:#F2F2F2; padding:10px 0;"> <?=$returnpage?></td>
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
