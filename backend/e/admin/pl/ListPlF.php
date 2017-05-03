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
CheckLevel($logininid,$loginin,$classid,"plf");
$url="<a href=ListAllPl.php".$ecms_hashur['whehref'].">管理评论</a>&nbsp;>&nbsp;<a href=ListPlF.php".$ecms_hashur['whehref'].">管理评论自定义字段</a>";
$sql=$empire->query("select * from {$dbtbpre}enewsplf order by fid");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理字段</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理评论自定义字段 <a href="AddPlF.php?enews=AddPlF<?=$ecms_hashur[ehref]?>" class="add">增加字段</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<form name="form1" method="post" action="../ecmspl.php" onSubmit="return confirm('确认要操作?');">
  <?=$ecms_hashur['form']?>
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>字段名</th>
			<th>字段标识</th>
			<th>字段类型</th>
            <th>操作</th>
		</tr>
    <?
  while($r=$empire->fetch($sql))
  {
  	$ftype=$r[ftype];
  	if($r[flen])
	{
		if($r[ftype]!="TEXT"&&$r[ftype]!="MEDIUMTEXT"&&$r[ftype]!="LONGTEXT")
		{
			$ftype.="(".$r[flen].")";
		}
	}
  ?>
 <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"> <div align="center"> 
        <?=$r[f]?>
      </div></td>
    <td height="25"> <div align="center"> 
       <?=$r[fname]?>
      </div></td>
    <td><div align="center">
       <?=$ftype?>
      </div></td>
      <td height="25"><div align="center"> [<a href='AddPlF.php?enews=EditPlF&fid=<?=$r[fid]?><?=$ecms_hashur['ehref']?>'>修改</a>]&nbsp;&nbsp;[<a href='../ecmspl.php?enews=DelPlF&fid=<?=$r[fid]?><?=$ecms_hashur['href']?>' onclick="return confirm('确认要删除?');">删除</a>] 
      </div></td>
  </tr>
  <?
  }
  ?>
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
