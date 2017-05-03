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

//执行SQL语句
function DoExecSql($add,$userid,$username){
	global $empire,$dbtbpre;
	$dosave=(int)$add['dosave'];
	$query=$add['query'];
	if(!$query)
	{
		printerror("EmptyDoSqlQuery","history.go(-1)");
    }
	if($dosave==1&&!$add['sqlname'])
	{
		printerror("EmptySqltext","history.go(-1)");
	}
	$query=ClearAddsData($query);
	//保存
	if($dosave==1)
	{
		$add['sqlname']=hRepPostStr($add['sqlname'],1);
		$isql=$empire->query("insert into {$dbtbpre}enewssql(sqlname,sqltext) values('".$add['sqlname']."','".addslashes($query)."');");
	}
	$query=RepSqlTbpre($query);
	DoRunQuery($query);
	//操作日志
	insert_dolog("query=".$query);
	printerror("DoExecSqlSuccess","DoSql.php".hReturnEcmsHashStrHref2(1));
}

//运行SQL
function DoRunQuery($sql){
	global $empire;
	$sql=str_replace("\r","\n",$sql);
	$ret=array();
	$num=0;
	foreach(explode(";\n",trim($sql)) as $query)
	{
		$queries=explode("\n",trim($query));
		foreach($queries as $query)
		{
			$ret[$num].=$query[0]=='#'||$query[0].$query[1]=='--'?'':$query;
		}
		$num++;
	}
	unset($sql);
	foreach($ret as $query)
	{
		$query=trim($query);
		if($query)
		{
			$empire->query($query);
		}
	}
}

//增加SQL语句
function AddSql($add,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add['sqlname']||!$add['sqltext'])
	{
		printerror("EmptySqltext","history.go(-1)");
	}
	$add['sqlname']=hRepPostStr($add['sqlname'],1);
	$add[sqltext]=ClearAddsData($add[sqltext]);
	$sql=$empire->query("insert into {$dbtbpre}enewssql(sqlname,sqltext) values('".$add['sqlname']."','".addslashes($add[sqltext])."');");
	$lastid=$empire->lastid();
	if($sql)
	{
		//操作日志
		insert_dolog("id=".$lastid."<br>sqlname=".$add[sqlname]);
		printerror("AddSqlSuccess","AddSql.php?enews=AddSql".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改SQL语句
function EditSql($add,$userid,$username){
	global $empire,$dbtbpre;
	$id=(int)$add[id];
	if(!$add['sqlname']||!$add['sqltext']||!$id)
	{
		printerror("EmptySqltext","history.go(-1)");
	}
	$add['sqlname']=hRepPostStr($add['sqlname'],1);
	$add[sqltext]=ClearAddsData($add[sqltext]);
	$sql=$empire->query("update {$dbtbpre}enewssql set sqlname='".$add['sqlname']."',sqltext='".addslashes($add[sqltext])."' where id='$id'");
	if($sql)
	{
		//操作日志
		insert_dolog("id=".$id."<br>sqlname=".$add[sqlname]);
		printerror("EditSqlSuccess","ListSql.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除SQL语句
function DelSql($id,$userid,$username){
	global $empire,$dbtbpre;
	$id=(int)$id;
	if(!$id)
	{
		printerror("EmptySqlid","history.go(-1)");
	}
	$r=$empire->fetch1("select sqlname from {$dbtbpre}enewssql where id='$id'");
	$sql=$empire->query("delete from {$dbtbpre}enewssql where id='$id'");
	if($sql)
	{
		//操作日志
		insert_dolog("id=".$id."<br>sqlname=".$r[sqlname]);
		printerror("DelSqlSuccess","ListSql.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//运行SQL语句
function ExecSql($id,$userid,$username){
	global $empire,$dbtbpre;
	$id=(int)$id;
	if(empty($id))
	{
		printerror('EmptyExecSqlid','');
	}
	$r=$empire->fetch1("select sqltext from {$dbtbpre}enewssql where id='$id'");
	if(!$r['sqltext'])
	{
		printerror('EmptyExecSqlid','');
    }
	$query=RepSqlTbpre($r['sqltext']);
	DoRunQuery($query);
	//操作日志
	insert_dolog("query=".$query);
	printerror("DoExecSqlSuccess","ListSql.php".hReturnEcmsHashStrHref2(1));
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
//执行SQL语句
if($enews=='DoExecSql')
{
	DoExecSql($_POST,$logininid,$loginin);
}
elseif($enews=='AddSql')//增加
{
	AddSql($_POST,$logininid,$loginin);
}
elseif($enews=='EditSql')//修改
{
	EditSql($_POST,$logininid,$loginin);
}
elseif($enews=='DelSql')//删除
{
	DelSql($_GET['id'],$logininid,$loginin);
}
elseif($enews=='ExecSql')//执行
{
	ExecSql($_GET['id'],$logininid,$loginin);
}

$url="<a href=DoSql.php".$ecms_hashur['whehref'].">执行SQL语句</a>";
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>执行SQL语句</title>
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
        	<h3><span>执行SQL语句 <a href="javascript:void(0)" onclick="glsqyj()" class="add">管理SQL语句</a><a href="javascript:void(0)" onclick="zjsqyj()" class="add">增加SQL语句</a></span></h3>
            <div class="line"></div>
<form action="DoSql.php" method="POST" name="sqlform" onsubmit="return confirm('确认要执行？');">
  <?=$ecms_hashur['form']?>
			<ul>
            		<li class="jqui"><div align="center">(多条语句请用&quot;回车&quot;格开,每条语句以&quot;;&quot;结束，数据表前缀可用：“[!db.pre!]&quot;表示) </div></li>
                    <li class="jqui"><div align="center"><textarea name="query" cols="90" rows="12" id="query" style="float: none;"></textarea></div></li>
					<li class="jqui"><div align="center"><input type="submit" name="Submit" value=" 执行SQL" style="padding: 1px 4px;">
          &nbsp;&nbsp; 
          <input type="reset" name="Submit2" value="重置" style="padding: 1px 4px;">
          <input name="enews" type="hidden" id="enews" value="DoExecSql" onclick="document.sqlform.dosave.value=0;">
          <input name="dosave" type="hidden" id="dosave" value="0"></div></li>
        			<li class="jqui"><div align="center">SQL名称： 
          <input name="sqlname" type="text" id="sqlname">
          <input type="submit" name="Submit3" value="执行SQL并保" onclick="document.sqlform.dosave.value=1;" style="padding: 1px 4px;"></div></li>
		  <li class="jqui"><div align="center">此功能影响到整个系统的数据,请慎用!</div></li>
            </ul>
            </form>
			<div class="line"></div>
        </div>
    </div>
</div>

</div>
</body>
</html>
