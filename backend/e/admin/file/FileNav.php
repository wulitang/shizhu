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
//CheckLevel($logininid,$loginin,$classid,"file");
$gr=ReturnLeftLevel($loginlevel);

$url="<a href=ListFile.php?type=9".$ecms_hashur['ehref'].">管理附件</a>";
$filer=$empire->fetch1("select filedatatbs,filedeftb from {$dbtbpre}enewspublic limit 1");
$tr=explode(',',$filer['filedatatbs']);
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
	if(imgname=="infofileimg")
	{
		img=todisplay(doinfofile,phome);
		document.images.infofileimg.src=img;
	}
	else if(imgname=="otherfileimg")
	{
		img=todisplay(dootherfile,phome);
		document.images.otherfileimg.src=img;
	}
	else if(imgname=="fotherimg")
	{
		img=todisplay(dofother,phome);
		document.images.fotherimg.src=img;
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
if($gr['dofile'])
{
?>
    	<h3>管理信息附件</h3>
        <ul>
	  <?php
	  $count=count($tr)-1;
	  for($i=1;$i<$count;$i++)
	  {
		$thistb=$tr[$i];
		$fstbname="信息附件表".$thistb;
		$filetbname=$dbtbpre.'enewsfile_'.$thistb;
		if($thistb==$filer['filedeftb'])
		{
			$fstbname='<b>'.$fstbname.'</b>';
		}
	  ?>
        	<li><a href="ListFile.php?type=9&fstb=<?=$tr[$i]?><?=$ecms_hashur['ehref']?>" target="apmain"><?=$fstbname?></a></li>
	  <?php
	  }
	  ?>
        </ul>
	<h3>管理其他附件</h3>
    <ul>
    <li><a href="ListFile.php?type=9&modtype=5<?=$ecms_hashur['ehref']?>" target="apmain">公共附件</a></li>
    <li><a href="ListFile.php?type=9&modtype=1<?=$ecms_hashur['ehref']?>" target="apmain">栏目附件</a></li>
    <li><a href="ListFile.php?type=9&modtype=2<?=$ecms_hashur['ehref']?>" target="apmain">专题附件</a></li>
    <li><a href="ListFile.php?type=9&modtype=6<?=$ecms_hashur['ehref']?>" target="apmain">会员附件</a></li>
    <li><a href="ListFile.php?type=9&modtype=7<?=$ecms_hashur['ehref']?>" target="apmain">碎片附件</a></li>
    <li><a href="ListFile.php?type=9&modtype=4<?=$ecms_hashur['ehref']?>" target="apmain">反馈附件</a></li>
    <li><a href="ListFile.php?type=9&modtype=3<?=$ecms_hashur['ehref']?>" target="apmain">广告附件</a></li>
    </ul>
<?php
}
?>
<?php
if($gr['dofile']||$gr['dofiletable'])
{
?>
    <h3>其他管理</h3>
    <ul>
	<?php
	if($gr['dofile'])
	{
	?>
    <li><a href="FilePath.php<?=$ecms_hashur['whehref']?>" target="apmain">目录式管理附件</a></li>
    <li><a href="TranMoreFile.php<?=$ecms_hashur['whehref']?>" target="apmain">上传多附件</a></li>
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
<?php
db_close();
$empire=null;
?>