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
CheckLevel($logininid,$loginin,$classid,"deldownrecord");

//批量删除备份记录
function DelDownRecord($add,$userid,$username){
	global $empire,$dbtbpre;
	if(empty($add['downtime']))
	{
		printerror("EmptyDownTime","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"deldownrecord");
	$truetime=to_time($add['downtime']);
	$sql=$empire->query("delete from {$dbtbpre}enewsdownrecord where truetime<=".$truetime);
	if($sql)
	{
		//操作日志
		insert_dolog("time=$add[downtime]");
		printerror("DelDownRecordSuccess","DelDownRecord.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
//删除下载记录
if($enews=="DelDownRecord")
{
	$add=$_POST['add'];
	DelDownRecord($add,$logininid,$loginin);
}

db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>删除下载备份记录</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <a href="DelDownRecord.php<?=$ecms_hashur['whehref']?>">删除下载备份记录</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>删除下载备份记录</span></h3>
            <div class="line"></div>
<form name="form1" method="post" action="DelDownRecord.php" onSubmit="return confirm('确认要删除?');">
  <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="DelDownRecord">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th>删除下载备份记录</th>
		</tr>
<tr> 
      <td height="25" bgcolor="#FFFFFF"><div align="center">删除截止于 
          <input name="add[downtime]" type="text" id="add[downtime]" value="<?=date("Y-m-d H:i:s")?>" size="20">
          之前的备份记录 
          <input type="submit" name="Submit" value="提交">
          &nbsp; </div></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF"><font color="#666666">说明: 删除下载备份记录,以减少数据库空间.</font></td>
    </tr>
	</tbody>
</table>
</form>
        </div>
	<div class="line"></div>
    </div>
</div>
</div>
</body>
</html>
