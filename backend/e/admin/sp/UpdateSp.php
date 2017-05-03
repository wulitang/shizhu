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

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
	include('../../class/chtmlfun.php');
	include('../../data/dbcache/class.php');
	include('../../class/t_functions.php');
}
if($enews=='ReSp')//刷新碎片文件
{
	ReSp($_GET,$logininid,$loginin,1);
}

$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=30;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$add='';
$search='';
$search.=$ecms_hashur['ehref'];
//碎片类型
$sptype=(int)$_GET['sptype'];
if($sptype)
{
	$add.=" and sptype='$sptype'";
	$search.="&sptype=$sptype";
}
//分类
$cid=(int)$_GET['cid'];
if($cid)
{
	$add.=" and cid='$cid'";
	$search.="&cid=$cid";
}
//栏目
$classid=(int)$_GET['classid'];
if($classid)
{
	$add.=" and classid='$classid'";
	$search.="&classid=$classid";
}
$query="select spid,spname,varname,cid,classid,sptype,sppic,spsay,refile,spfile from {$dbtbpre}enewssp where isclose=0 and (groupid like '%,".$lur[groupid].",%' or userclass like '%,".$lur[classid].",%' or username like '%,".$lur[username].",%')".$add;
$totalquery="select count(*) as total from {$dbtbpre}enewssp where isclose=0 and (groupid like '%,".$lur[groupid].",%' or userclass like '%,".$lur[classid].",%' or username like '%,".$lur[username].",%')".$add;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by spid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
$url="<a href=UpdateSp.php".$ecms_hashur['whehref'].">更新碎片</a>";
//分类
$scstr="";
$scsql=$empire->query("select classid,classname from {$dbtbpre}enewsspclass order by classid");
while($scr=$empire->fetch($scsql))
{
	$select="";
	if($scr[classid]==$cid)
	{
		$select=" selected";
	}
	$scstr.="<option value='".$scr[classid]."'".$select.">".$scr[classname]."</option>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>碎片</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/ui.tab.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script></head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
<form name="searchform" method="GET" action="UpdateSp.php">
<?=$ecms_hashur['eform']?>
        	<h3><span><div class="left">选择条件：</div> 
            <div id="listplclassnav" style="width:115px; height:25px; float:left; margin-top:8px;"></div>
      <select name="cid">
        <option value="0">所有分类</option>
        <?=$scstr?>
      </select> <select name="sptype" id="所有类型">
          <option value="0">所有类型</option>
		  <option value="1"<?=$sptype==1?' selected':''?>>静态信息碎片</option>
          <option value="2"<?=$sptype==2?' selected':''?>>动态信息碎片</option>
          <option value="3"<?=$sptype==3?' selected':''?>>代码碎片</option>
        </select>
        <input type="submit" name="Submit" value="显示" class="anniu"> </span></h3>
</form>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>碎片名称</th>
			<th>变量名</th>
			<th>描述</th>
			<th>操作</th>
		</tr>
 <?
  while($r=$empire->fetch($sql))
  {
	if($r[sptype]==1)
	{
		$sptype='静态信息';
		$dourl="ListSpInfo.php?spid=$r[spid]".$ecms_hashur['ehref'];
	}
	elseif($r[sptype]==2)
	{
		$sptype='动态信息';
		$dourl="ListSpInfo.php?spid=$r[spid]".$ecms_hashur['ehref'];
	}
	else
	{
		$sptype='代码碎片';
		$dourl="AddSpInfo.php?enews=EditSpInfo&spid=$r[spid]".$ecms_hashur['ehref'];
	}
	//链接
	$sphref='';
	if($r['refile'])
	{
		$sphref=' href="'.$public_r['newsurl'].$r['spfile'].'" target="_blank"';
	}
	$sppic='';
	if($r[sppic])
	{
		$sppic='<a href="'.$r[sppic].'" title="碎片效果图" target="_blank"><img src="../../data/images/showimg.gif" border=0 align="absmiddle"></a>';
	}
  ?>
  <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"> 
    <td height="32"><a<?=$sphref?> title="<?=$sptype?>"> 
      <?=$r[spname]?>
      </a> <?=$sppic?></td>
    <td><div align="center"> 
        <?=$r[varname]?>
      </div></td>
    <td> 
      <?=$r[spsay]?>
    </td>
    <td height="25"><div align="center">[<a href="<?=$dourl?>" target="_blank">更新碎片</a>]
	<?php
	if($r['refile'])
	{
	?>
	 [<a href="UpdateSp.php?enews=ReSp&spid[]=<?=$r[spid]?><?=$ecms_hashur['href']?>">刷新碎片文件</a>]
	<?php
	}
	?>
	</div></td>
  </tr>
  <?
  }
  ?>
   <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'">
   <td height="32" colspan="4"><?=$returnpage?></td>
   </tr>
	</tbody>
</table>
</div>
<div class="line"></div>
        </div>
    </div>
</div>
</div>
<IFRAME frameBorder="0" id="showclassnav" name="showclassnav" scrolling="no" src="../ShowClassNav.php?ecms=6&classid=<?=$classid?><?=$ecms_hashur['ehref']?>" style="HEIGHT:0;VISIBILITY:inherit;WIDTH:0;Z-INDEX:1"></IFRAME>
</body>

</html>
<?
db_close();
$empire=null;
?>
