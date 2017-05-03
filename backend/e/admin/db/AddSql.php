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
CheckLevel($logininid,$loginin,$classid,"execsql");

$enews=RepPostStr($_GET['enews'],1);
if(empty($enews))
{
	$enews='AddSql';
}
$url="<a href='ListSql.php".$ecms_hashur['whehref']."'>管理SQL语句</a>&nbsp;>&nbsp;增加SQL语句";
$postword='增加SQL语句';
if($enews=='EditSql')
{
	$id=intval($_GET['id']);
	$r=$empire->fetch1("select * from {$dbtbpre}enewssql where id='$id'");
	$url="<a href='ListSql.php".$ecms_hashur['whehref']."'>管理SQL语句</a>&nbsp;>&nbsp;修改SQL语句: <b>".$r[sqlname]."</b>";
	$postword='修改SQL语句';
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>增加SQL语句</title>
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
//管理SQL语句
function glsqyj(id){
art.dialog.open('db/ListSql.php<?=$ecms_hashur['whehref']?>',
    {title: '管理SQL语句',lock: true,opacity: 0.5, width: 800, height: 650,
	close: function () {
      location.reload();
    }
	});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?>  </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>增加SQL语句 <a href="javascript:void(0)" onclick="glsqyj()" class="add">管理SQL语句</a><a href="javascript:void(0)" onclick="zxsqyj()" class="add">执行SQL语句</a></span></h3>
            <div class="line"></div>
<form action="DoSql.php" method="POST" name="sqlform">
  <?=$ecms_hashur['form']?>
			<ul>
            		<li class="jqui"><div align="center">(多条语句请用&quot;回车&quot;格开,每条语句以&quot;;&quot;结束，数据表前缀可用：“[!db.pre!]&quot;表示) </div></li>
                    <li class="jqui"><div align="center"><textarea name="sqltext" cols="90" rows="12" id="sqltext" style="float: none;"><?=ehtmlspecialchars($r[sqltext])?></textarea></div></li>
        			<li class="jqui"><div align="center">SQL名称： 
          <input name="sqlname" type="text" id="sqlname" value="<?=$r[sqlname]?>">
          <input type="submit" name="Submit3" value="保存" style="padding: 1px 4px;">
          &nbsp;<input type="reset" name="Submit2" value="重置" style="padding: 1px 4px;">
          <input name="enews" type="hidden" id="enews" value="<?=$enews?>">
          <input name="id" type="hidden" id="id" value="<?=$id?>"></div></li>
            </ul>
            </form>
			<div class="line"></div>
        </div>
    </div>
</div>

</div>
</body>
</html>
