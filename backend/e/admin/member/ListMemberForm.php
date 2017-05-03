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
CheckLevel($logininid,$loginin,$classid,"memberf");
$search=$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=30;//每页显示条数
$page_line=23;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select fid,fname from {$dbtbpre}enewsmemberform";
$totalquery="select count(*) as total from {$dbtbpre}enewsmemberform";
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by fid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="ListMemberForm.php">管理会员表单</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理会员字段 <a href="AddMemberForm.php?enews=AddMemberForm<?=$ecms_hashur['ehref']?>" class="add">增加会员表单</a> <a href="ListMemberF.php<?=$ecms_hashur['whehref']?>" class="gl">管理会员字段</a></span></h3>
            <div class="line"></div>
<div class="anniuqun">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:150px;">ID</th>
			<th>表单名称</th>
            <th>操作</th>
		</tr>
 <?
  while($r=$empire->fetch($sql))
  {
  ?>
  <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td><div align="center"> 
        <?=$r[fid]?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$r[fname]?>
      </div></td>
    <td height="25"><div align="center">[<a href="AddMemberForm.php?enews=EditMemberForm&fid=<?=$r[fid]?><?=$ecms_hashur['ehref']?>">修改</a>] 
        [<a href="AddMemberForm.php?enews=AddMemberForm&fid=<?=$r[fid]?>&docopy=1<?=$ecms_hashur['ehref']?>">复制</a>] 
        [<a href="../ecmsmember.php?enews=DelMemberForm&fid=<?=$r[fid]?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除?');">删除</a>] 
      </div></td>
  </tr>
  <?
  }
  db_close();
  $empire=null;
  ?>
  		<tr>
  		  <td colspan="3" style="height:35px; overflow:hidden;margin:0;background:#F2F2F2; padding:10px 0;"><?=$returnpage?></td>
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
