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
CheckLevel($logininid,$loginin,$classid,"memberconnect");

//设置接口
function EditMemberConnect($add,$userid,$username){
	global $empire,$dbtbpre;
	$add[id]=(int)$add[id];
	if(empty($add[appname])||!$add[id])
	{
		printerror("EmptyMemberConnect","history.go(-1)");
    }
	$add[isclose]=(int)$add[isclose];
	$add[myorder]=(int)$add[myorder];
	$add[appname]=eaddslashes(ehtmlspecialchars($add[appname]));
	$add[appid]=eaddslashes($add[appid]);
	$add[appkey]=eaddslashes($add[appkey]);
	$add[qappname]=eaddslashes($add[qappname]);
	$add[appsay]=eaddslashes($add[appsay]);
	$sql=$empire->query("update {$dbtbpre}enewsmember_connect_app set appname='$add[appname]',appid='$add[appid]',appkey='$add[appkey]',isclose='$add[isclose]',myorder='$add[myorder]',qappname='$add[qappname]',appsay='$add[appsay]' where id='$add[id]'");
	$appr=$empire->fetch1("select apptype from {$dbtbpre}enewsmember_connect_app where id='$add[id]'");
	if($sql)
	{
		GetConfig();
		//操作日志
		insert_dolog("id=".$add[id]."&apptype=".$appr[apptype]."<br>appname=".$add[appname]);
		printerror("EditMemberConnectSuccess","MemberConnect.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
//增加用户
if($enews=="EditMemberConnect")
{
	EditMemberConnect($_POST,$logininid,$loginin);
}

$sql=$empire->query("select * from {$dbtbpre}enewsmember_connect_app order by myorder,id");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>外部登录接口</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> &gt; 外部接口 &gt; <a href="MemberConnect.php<?=$ecms_hashur['whehref']?>">管理外部登录接口</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理外部登录接口</span></h3>
            <div class="line"></div>
<div class="anniuqun">
<form name="form1" method="post" action="MemberFeedback.php" onSubmit="return confirm('确认要删除?');">
<?=$ecms_hashur['form']?>
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>接口名称</th>
            <th>状态</th>
            <th>接口类型</th>
            <th>绑定人数</th>
            <th>登录次数</th>
            <th>操作</th>
		</tr>
<?php
  while($r=$empire->fetch($sql))
  {
  	$membernum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsmember_connect where apptype='$r[apptype]' limit 1");
	$loginnum=$empire->gettotal("select sum(loginnum) as total from {$dbtbpre}enewsmember_connect where apptype='$r[apptype]' limit 1");
  ?>
  <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'">
    <td align="center"><?=$r['id']?></td> 
    <td height="38" align="center"> 
      <?=$r['appname']?>    </td>
    <td><div align="center">
        <?=$r['isclose']==0?'开启':'关闭'?>
      </div></td>
    <td height="38"> <div align="center">
        <?=$r['apptype']?>
      </div></td>
    <td><div align="center"><?=$membernum?></div></td>
    <td><div align="center"><?=intval($loginnum)?></div></td>
    <td height="38"> <div align="center"><a href="SetMemberConnect.php?enews=EditMemberConnect&id=<?=$r['id']?><?=$ecms_hashur['ehref']?>">配置接口</a></div></td>
  </tr>
  <?php
  }
  db_close();
  $empire=null;
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
