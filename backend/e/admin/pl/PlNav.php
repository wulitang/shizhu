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
//CheckLevel($logininid,$loginin,$classid,"pl");
$gr=ReturnLeftLevel($loginlevel);

$plr=$empire->fetch1("select pldatatbs,pldeftb from {$dbtbpre}enewspl_set limit 1");
$tr=explode(',',$plr['pldatatbs']);
//今日评论
$pur=$empire->fetch1("select lasttimepl,lastnumpl,lastnumpltb,todaytimeinfo,todaytimepl,todaynumpl,yesterdaynumpl from {$dbtbpre}enewspublic_update limit 1");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>菜单</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<SCRIPT lanuage="JScript">
function DisplayImg(ss,imgname,phome)
{
	if(imgname=="plimg")
	{
		img=todisplay(dopl,phome);
		document.images.plimg.src=img;
	}
	else if(imgname=="plotherimg")
	{
		img=todisplay(doplother,phome);
		document.images.plotherimg.src=img;
	}
	else
	{
	}
}
function todisplay(ss,phome)
{
	if(ss.style.display=="") 
	{
  		ss.style.display="none";
		theimg="../openpage/images/add.gif";
	}
	else
	{
  		ss.style.display="";
		theimg="../openpage/images/noadd.gif";
	}
	return theimg;
}
function turnit(ss,img)
{
	DisplayImg(ss,img,0);
}
</SCRIPT>
<style>
 body{ background:#919191;}
 .ileft h3{ padding:10px 10px;font-size: 1.2em;}
 .ileft li a{ padding:10px; padding-left:30px;}
 .ileft li a:before{content:"◆";font-size: 1.2em; margin-right:5px;}
</style>
</head>

<body topmargin="0">
	<div class="ileft">
<?php
if($gr['dopl'])
{
?>
    	<h3>管理评论</h3>
        <ul>
  <?php
	  $count=count($tr)-1;
	  for($i=1;$i<$count;$i++)
	  {
		$thistb=$tr[$i];
		$restbname="评论表".$thistb;
		$pltbname=$dbtbpre.'enewspl_'.$thistb;
		if($thistb==$plr['pldeftb'])
		{
			$restbname='<b>'.$restbname.'</b>';
		}
		$exp='|'.$thistb.',';
		$addnumr=explode($exp,$pur['lastnumpltb']);
		$addnumrt=explode('|',$addnumr[1]);
		$addnum=(int)$addnumrt[0];
	  ?>
        	<li><a href="ListAllPl.php?restb=<?=$tr[$i]?><?=$ecms_hashur['ehref']?>" title="+<?=$addnum?>" target="apmain"><?=$restbname?></a></li>
	  <?php
	  }
	  ?>
        </ul>
<?php
}
?>
<?php
if($gr['doplf']||$gr['dopltable']||$gr['dopl']||$gr['dopublic'])
{
?>
        <h3>其他管理</h3>
        <ul>
	<?php
	if($gr['doplf'])
	{
	?>
        	<li><a href="ListPlF.php<?=$ecms_hashur['whehref']?>" target="apmain">自定义评论字段</a></li>
	<?php
	}
	?>
	<?php
	if($gr['dopl'])
	{
	?>
            <li><a href="DelMorePl.php<?=$ecms_hashur['whehref']?>" target="apmain">批量删除评论</a></li>
            <li><a href="plface.php<?=$ecms_hashur['whehref']?>" target="apmain">管理评论表情</a></li>
	<?php
	}
	?>
	<?php
	if($gr['dopublic'])
	{
	?>
            <li><a href="SetPl.php<?=$ecms_hashur['whehref']?>" target="apmain">设置评论参数</a></li>
	<?php
	}
	?>
        </ul>
<?php
}
?>
    </div>
</body>
</html>