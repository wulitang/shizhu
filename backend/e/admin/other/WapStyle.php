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
CheckLevel($logininid,$loginin,$classid,"wap");

//增加wap模板
function AddWapStyle($add,$userid,$username){
	global $empire,$dbtbpre;
	$path=RepPathStr($add['path']);
	$path=(int)$path;
	if(empty($path)||empty($add['stylename']))
	{
		printerror("EmptyWapStyle","history.go(-1)");
	}
	//目录是否存在
	if(!file_exists("../../wap/template/".$path))
	{
		printerror("EmptyWapStylePath","history.go(-1)");
	}
	$sql=$empire->query("insert into {$dbtbpre}enewswapstyle(stylename,path) values('$add[stylename]',$path);");
	if($sql)
	{
		$styleid=$empire->lastid();
		//操作日志
		insert_dolog("styleid=$styleid&stylename=$add[stylename]");
		printerror("AddWapStyleSuccess","WapStyle.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改wap模板
function EditWapStyle($add,$userid,$username){
	global $empire,$dbtbpre;
	$styleid=(int)$add['styleid'];
	$path=RepPathStr($add['path']);
	$path=(int)$path;
	if(!$styleid||empty($path)||empty($add['stylename']))
	{
		printerror("EmptyWapStyle","history.go(-1)");
	}
	//目录是否存在
	if(!file_exists("../../wap/template/".$path))
	{
		printerror("EmptyWapStylePath","history.go(-1)");
	}
	$sql=$empire->query("update {$dbtbpre}enewswapstyle set stylename='$add[stylename]',path=$path where styleid=$styleid");
	if($sql)
	{
		//操作日志
		insert_dolog("styleid=$styleid&stylename=$add[stylename]");
		printerror("EditWapStyleSuccess","WapStyle.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除wap模板
function DelWapStyle($styleid,$userid,$username){
	global $empire,$dbtbpre,$public_r;
	$styleid=(int)$styleid;
	if(!$styleid)
	{
		printerror("EmptyWapStyleid","history.go(-1)");
	}
	$r=$empire->fetch1("select stylename,path from {$dbtbpre}enewswapstyle where styleid=$styleid");
	if($styleid==$public_r['wapdefstyle'])
	{
		printerror("NotDelDefWapStyle","history.go(-1)");
	}
	$sql=$empire->query("delete from {$dbtbpre}enewswapstyle where styleid=$styleid");
	if($sql)
	{
		//操作日志
		insert_dolog("styleid=$styleid&stylename=$r[stylename]");
		printerror("DelWapStyleSuccess","WapStyle.php".hReturnEcmsHashStrHref2(1));
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
//增加wap模板
if($enews=="AddWapStyle")
{
	AddWapStyle($_POST,$logininid,$loginin);
}
//修改wap模板
elseif($enews=="EditWapStyle")
{
	EditWapStyle($_POST,$logininid,$loginin);
}
//删除wap模板
elseif($enews=="DelWapStyle")
{
	DelWapStyle($_GET['styleid'],$logininid,$loginin);
}
else
{}
$pr=$empire->fetch1("select wapdefstyle from {$dbtbpre}enewspublic limit 1");
$sql=$empire->query("select styleid,stylename,path from {$dbtbpre}enewswapstyle order by styleid");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script>
//WAP设置
function wapsz(){
art.dialog.open('other/SetWap.php<?=$ecms_hashur['whehref']?>',
    {title: 'WAP设置',lock: true,opacity: 0.5, width: 800, height: 540,
	 close: function () {
      location.reload();
    }
	});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="WapStyle.php<?=$ecms_hashur['whehref']?>">管理WAP模板</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理WAP模板 <a href="javascript:wapsz();" class="gl">WAP设置</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th width="150px">ID</th>
			<th>模板名称</th>
            <th>模板目录</th>
			<th>操作</th>
		</tr>
<?
  while($r=$empire->fetch($sql))
  {
  	$bgcolor="#FFFFFF";
	$movejs=' onmouseout="this.style.backgroundColor=\'#ffffff\'" onmouseover="this.style.backgroundColor=\'#C3EFFF\'"';
  	if($r[styleid]==$pr['wapdefstyle'])
	{
		$bgcolor="#DBEAF5";
		$movejs='';
	}
  ?>
  <form name=form2 method=post action=WapStyle.php>
 <?=$ecms_hashur['form']?>
    <input type=hidden name=enews value=EditWapStyle>
    <input type=hidden name=styleid value=<?=$r[styleid]?>>
    <tr bgcolor="<?=$bgcolor?>"<?=$movejs?>> 
      <td><div align="center">
          <?=$r[styleid]?>
        </div></td>
      <td height="25"> <div align="center"> 
          <input name="stylename" type="text" id="stylename" value="<?=$r[stylename]?>">
        </div></td>
      <td><div align="center">e/wap/template/
<input name="path" type="text" id="path" value="<?=$r[path]?>" size="6">
        </div></td>
      <td height="25"><div align="center">
          <input type="submit" name="Submit3" value="修改">
          &nbsp; 
          <input type="button" name="Submit4" value="删除" onclick="if(confirm('确认要删除?')){self.location.href='WapStyle.php?enews=DelWapStyle&styleid=<?=$r[styleid]?>';}">
        </div></td>
    </tr>
  </form>
  <?
  }
  db_close();
  $empire=null;
  ?>
<form name="form1" method="post" action="WapStyle.php">
 <?=$ecms_hashur['form']?>
  		<tr> 
          <td style="background:#DBEAF5;">增加WAP模板: 
        <input name=enews type=hidden id="enews" value=AddWapStyle> </td>
		  <td colspan="4" style="text-align:left;background:#DBEAF5;">模板名称: 
        <input name="stylename" type="text" id="stylename">
        模板目录:e/wap/template/ 
        <input name="path" type="text" id="path" size="6">
        (请填写数字) 
        <input type="submit" name="Submit" value="增加">
        <input type="reset" name="Submit2" value="重置"></td>
		  </tr>
 </form>
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
