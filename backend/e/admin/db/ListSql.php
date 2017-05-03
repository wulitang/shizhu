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
CheckLevel($logininid,$loginin,$classid,"execsql");
$search=$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=16;//每页显示条数
$page_line=25;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select id,sqlname from {$dbtbpre}enewssql";
$totalquery="select count(*) as total from {$dbtbpre}enewssql";
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by id desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>管理SQL语句</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
//增加SQL语句
function zjsqyj(){
art.dialog.open('db/AddSql.php?<?=$ecms_hashur[ehref]?>&enews=AddSql',
    {title: '增加SQL语句',lock: true,opacity: 0.5, width: 800, height: 540,
	 close: function () {
      location.reload();
    }
	});
}

//执行SQL语句
function zxsqyj(id){
art.dialog.open('db/DoSql.php<?=$ecms_hashur['whehref']?>',
    {title: '执行SQL语句',lock: true,opacity: 0.5, width: 800, height: 650,
	close: function () {
      location.reload();
    }
	});
}
//修改SQL语句
function editsqyj(id){
art.dialog.open('db/AddSql.php?<?=$ecms_hashur[ehref]?>&enews=EditSql&id='+id,
    {title: '修改SQL语句',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
</script>
</head> 

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <a href="ListSql.php<?=$ecms_hashur['whehref']?>">管理SQL语句</a> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理SQL语句 <a href="javascript:void(0)" onclick="zjsqyj()" class="add">增加SQL语句</a><a href="javascript:void(0)" onclick="zxsqyj()" class="add">执行SQL语句</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
	<th>ID</th>
    <th>SQL名称</th>
    <th>操作</th>
		</tr>
<?php
  while($r=$empire->fetch($sql))
  {
  ?>
  <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"> <div align="center"> 
        <?=$r['id']?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$r['sqlname']?>
      </div></td>
    <td height="25"> <div align="center">[<a href="DoSql.php?enews=ExecSql&id=<?=$r[id]?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要执行SQL？');">执行</a>] [<a href="javascript:editsqyj(<?=$r[id]?>)">修改</a>]&nbsp;[<a href="DoSql.php?enews=DelSql&id=<?=$r[id]?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
<tr>
<td colspan="6" class="txtleft"><?=$returnpage?></td>
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
