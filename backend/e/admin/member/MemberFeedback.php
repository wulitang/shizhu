<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
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
CheckLevel($logininid,$loginin,$classid,"spacedata");

//删除反馈
function hDelMemberFeedback($add,$userid,$username){
	global $empire,$dbtbpre;
	$fid=intval($add['fid']);
	if(!$fid)
	{
		printerror("NotDelMemberFeedbackid","history.go(-1)");
	}
	$sql=$empire->query("delete from {$dbtbpre}enewsmemberfeedback where fid='$fid'");
	if($sql)
	{
		//操作日志
		insert_dolog("fid=".$fid);
		printerror("DelMemberFeedbackSuccess",$_SERVER['HTTP_REFERER']);
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//批量删除反馈
function hDelMemberFeedback_All($add,$userid,$username){
	global $empire,$dbtbpre;
	$fid=$add['fid'];
	$count=count($fid);
	if(empty($count))
	{
		printerror("NotDelMemberFeedbackid","history.go(-1)");
	}
	for($i=0;$i<$count;$i++)
	{
		$addsql.="fid='".intval($fid[$i])."' or ";
    }
	$addsql=substr($addsql,0,strlen($addsql)-4);
	$sql=$empire->query("delete from {$dbtbpre}enewsmemberfeedback where (".$addsql.")");
	if($sql)
	{
		//操作日志
		insert_dolog("");
		printerror("DelMemberFeedbackSuccess",$_SERVER['HTTP_REFERER']);
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

$enews=$_GET['enews'];
if(empty($enews))
{
	$enews=$_POST['enews'];
}
if($enews)
{
	hCheckEcmsRHash();
}
if($enews=="hDelMemberFeedback")
{
	hDelMemberFeedback($_GET,$logininid,$loginin);
}
elseif($enews=="hDelMemberFeedback_All")
{
	hDelMemberFeedback_All($_POST,$logininid,$loginin);
}
include("../../member/class/user.php");
include "../".LoadLang("pub/fun.php");
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
//搜索
$search='';
$search.=$ecms_hashur['ehref'];
$and='';
if($_GET['sear'])
{
	$keyboard=RepPostVar2($_GET['keyboard']);
	if($keyboard)
	{
		$show=RepPostStr($_GET['show'],1);
		if($show==1)//反馈标题
		{
			$and.=" where title like '%$keyboard%'";	
		}
		elseif($show==2)//反馈内容
		{
			$and.=" where ftext like '%$keyboard%'";
		}
		elseif($show==3)//空间主人用户ID
		{
			$and.=" where userid='$keyboard'";
		}
		elseif($show==4)//留言者IP
		{
			$and.=" where ip like '%$keyboard%'";
		}
		$search.="&sear=1&keyboard=$keyboard&show=$show";
	}
}
$query="select fid,title,uid,uname,addtime,userid from {$dbtbpre}enewsmemberfeedback".$and;
$totalquery="select count(*) as total from {$dbtbpre}enewsmemberfeedback".$and;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by fid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
$url="会员空间&nbsp;>&nbsp;<a href=MemberFeedback.php".$ecms_hashur['whehref'].">管理反馈</a>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理反馈</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<style>
.comm-table td{ padding:8px 4px; height:16px;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理反馈</span></h3>
            <div class="line"></div>
<div class="anniuqun">

<div class="saixuan">
  <form name="searchfb" method="get" action="MemberFeedback.php">
  <?=$ecms_hashur['eform']?>
    搜索： 
          <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
          <select name="show" id="show">
            <option value="1"<?=$show==1?' selected':''?>>反馈标题</option>
            <option value="2"<?=$show==2?' selected':''?>>反馈内容</option>
            <option value="3"<?=$show==3?' selected':''?>>空间主人用户ID</option>
            <option value="4"<?=$show==4?' selected':''?>>留言者IP</option>
          </select>
          <input type="submit" name="Submit2" value="搜索">
          <input name="sear" type="hidden" id="sear" value="1">
  </form>
</div>

<form name="form1" method="post" action="MemberFeedback.php" onSubmit="return confirm('确认要删除?');">
<?=$ecms_hashur['form']?>
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>标题(点击查看)</th>
            <th>空间主人</th>
            <th>发布时间</th>
            <th>操作</th>
		</tr>
   <?
  while($r=$empire->fetch($sql))
  {
  	$ur=$empire->fetch1("select ".egetmf('username')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$r[userid]'");
	$username=$ur['username'];
	if($r['uid'])
	{
		$r['uname']="<a href='../../space/?userid=$r[uid]' target='_blank'>$r[uname]</a>";
	}
	else
	{
		$r['uname']='游客';
	}
  ?>
    <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
      <td height="25"><div align="center"> 
          <?=$r[fid]?>
        </div></td>
      <td height="25"><div align="left"><a href="#ecms" onclick="window.open('MemberShowFeedback.php?fid=<?=$r[fid]?><?=$ecms_hashur['ehref']?>','','width=650,height=600,scrollbars=yes,top=70,left=100');"> 
          <?=$r[title]?>
          </a>&nbsp;(<?=$r['uname']?>)</div></td>
      <td height="25"><div align="center"><a href="MemberFeedback.php?sear=1&show=3&keyboard=<?=$r[userid]?><?=$ecms_hashur['ehref']?>"> 
                <?=$username?>
                </a></div></td>
      <td height="25"><div align="center"> 
          <?=$r[addtime]?>
        </div></td>
      <td height="25"><div align="center">[<a href="MemberFeedback.php?enews=hDelMemberFeedback&fid=<?=$r[fid]?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除?');">删除</a>
          <input name="fid[]" type="checkbox" value="<?=$r[fid]?>">
          ]</div></td>
    </tr>
    <?
  }
  ?>
  		<tr>
  		  <td colspan="5" style="text-align:left;">&nbsp;&nbsp;
<input type="submit" name="Submit" value="批量删除">
        <input name="enews" type="hidden" id="enews" value="hDelMemberFeedback_All"></td>
		  </tr>
  		<tr>
  		  <td colspan="5" style="height:35px; overflow:hidden;margin:0;background:#F2F2F2; padding:10px 0;"><?=$returnpage?></td>
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
