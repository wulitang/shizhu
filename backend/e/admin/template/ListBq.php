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
CheckLevel($logininid,$loginin,$classid,"bq");

$search=$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=20;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select bqid,bqname,bq,issys,funname,isclose,classid from {$dbtbpre}enewsbq";
$totalquery="select count(*) as total from {$dbtbpre}enewsbq";
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
$query=$query." order by myorder desc,isclose,bqid limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
//类别
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewsbqclass order by classid");
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
<title>管理标签</title>
<style>
.comm-table td{ height:16px; padding:8px 0;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="ListBq.php<?=$ecms_hashur['whehref']?>">管理标签</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">

        	<h3><span>选择类别： 
      <select name="classid" id="classid" onchange=window.location='ListBq.php?<?=$ecms_hashur['ehref']?>&classid='+this.options[this.selectedIndex].value>
        <option value="0">显示所有类别</option>
        <?=$cstr?>
      </select> <a href="AddBq.php?enews=AddBq&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" class="add">增加标签</a> <a href="LoadInBq.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" class="add">导入标签</a> <a href="BqClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>" class="gl">管理标签分类</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>标签名称</th>
            <th>标签符号</th>
            <th>系统标签</th>
            <th>开启</th>
            <th>操作</th>
		</tr>
 <?
  while($r=$empire->fetch($sql))
  {
  if($r[issys])
  {$issys="是";}
  else
  {$issys="否";}
  //开启
  if($r[isclose])
  {
  $isclose="<font color=red>关闭</font>";
  }
  else
  {
  $isclose="开启";
  }
  ?>
  <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"> <div align="center">
        <?=$r[bqid]?>
      </div></td>
    <td height="25"> <div align="center">
        <?=$r[bqname]?>
      </div></td>
    <td height="25"> <div align="center">
        <?=$r[bq]?>
      </div></td>
    <td height="25"> <div align="center">
        <?=$issys?>
      </div></td>
    <td><div align="center"><?=$isclose?></div></td>
    <td height="25"> <div align="center">[<a href="AddBq.php?enews=EditBq&bqid=<?=$r[bqid]?>&cid=<?=$classid?><?=$ecms_hashur['ehref']?>">修改</a>]&nbsp;[<a href="../ecmstemp.php?enews=DelBq&bqid=<?=$r[bqid]?>&cid=<?=$classid?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除？');">删除</a>]&nbsp;[<a href="#ecms" onclick="window.open('LoadOutBq.php?bqid=<?=$r[bqid]?><?=$ecms_hashur['ehref']?>','','width=500,height=500,scrollbars=auto');">导出</a>]</div></td>
  </tr>
  <?
  }
  ?>
  		<tr>
  		  <td colspan="6" style="text-align:left;"><?=$returnpage?></td>
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
