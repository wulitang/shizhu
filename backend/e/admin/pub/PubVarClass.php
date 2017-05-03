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
CheckLevel($logininid,$loginin,$classid,"pubvar");

//增加分类
function AddPubVarClass($add,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add[classname])
	{
		printerror("EmptyPubVarClass","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"pubvar");
	$add['classname']=hRepPostStr($add['classname'],1);
	$add['classsay']=hRepPostStr($add['classsay'],1);
	$sql=$empire->query("insert into {$dbtbpre}enewspubvarclass(classname,classsay) values('".$add[classname]."','".$add[classsay]."');");
	$lastid=$empire->lastid();
	if($sql)
	{
		//操作日志
		insert_dolog("classid=".$lastid."<br>classname=".$add[classname]);
		printerror("AddPubVarClassSuccess","PubVarClass.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改分类
function EditPubVarClass($add,$userid,$username){
	global $empire,$dbtbpre;
	$classid=(int)$add[classid];
	if(!$add[classname]||!$classid)
	{
		printerror("EmptyPubVarClass","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"pubvar");
	$add['classname']=hRepPostStr($add['classname'],1);
	$add['classsay']=hRepPostStr($add['classsay'],1);
	$sql=$empire->query("update {$dbtbpre}enewspubvarclass set classname='".$add[classname]."',classsay='".$add[classsay]."' where classid='$classid'");
	if($sql)
	{
		//操作日志
		insert_dolog("classid=".$classid."<br>classname=".$add[classname]);
		printerror("EditPubVarClassSuccess","PubVarClass.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除分类
function DelPubVarClass($classid,$userid,$username){
	global $empire,$dbtbpre;
	$classid=(int)$classid;
	if(!$classid)
	{
		printerror("NotDelPubVarClassid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"pubvar");
	$r=$empire->fetch1("select classname from {$dbtbpre}enewspubvarclass where classid='$classid'");
	$sql=$empire->query("delete from {$dbtbpre}enewspubvarclass where classid='$classid'");
	//删除变量
	$delsql=$empire->query("delete from {$dbtbpre}enewspubvar where classid='$classid'");
	if($sql)
	{
		GetConfig();
		//操作日志
		insert_dolog("classid=".$classid."<br>classname=".$r[classname]);
		printerror("DelPubVarClassSuccess","PubVarClass.php".hReturnEcmsHashStrHref2(1));
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
if($enews=="AddPubVarClass")//增加分类
{
	AddPubVarClass($_POST,$logininid,$loginin);
}
elseif($enews=="EditPubVarClass")//修改分类
{
	EditPubVarClass($_POST,$logininid,$loginin);
}
elseif($enews=="DelPubVarClass")//删除分类
{
	$classid=$_GET['classid'];
	DelPubVarClass($classid,$logininid,$loginin);
}
else
{}

$sql=$empire->query("select classid,classname,classsay from {$dbtbpre}enewspubvarclass order by classid desc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript">
$(function(){
			
		});
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <a href="ListPubVar.php<?=$ecms_hashur['whehref']?>">管理扩展变量</a> > <a href="PubVarClass.php<?=$ecms_hashur['whehref']?>">管理变量分类</a> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>增加变量分类: <a href="ListPubVar.php<?=$ecms_hashur['whehref']?>" class="gl">管理扩展变量</a></span></h3>
            <div class="line"></div>
            <form name="form1" method="post" action="PubVarClass.php">
  <?=$ecms_hashur['form']?>
            <input name=enews type=hidden id="enews" value=AddPubVarClass>
			<ul>
            		<li class="jqui"><label>分类名称:</label><input name="classname" type="text" id="classname"></li>
                    <li class="jqui"><label>分类说明:</label><input name="classsay" type="text" id="classsay" size="42"></li>
        			<li class="jqui"><label>&nbsp;</label><input type="submit" name="Submit" value="增加"></li>
            </ul>
            </form>
            		<h3><span>变量分类名称列表</span></h3>
                    <table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th width="30">ID</th>
			<th>分类名称</th>
			<th>分类说明</th>
			<th>操作</th>
		</tr>
        
  <?php
  while($r=$empire->fetch($sql))
  {
  ?>
  <form name="form2" method="post" action="PubVarClass.php">
		<input type=hidden name=enews value=EditPubVarClass>
		<input type=hidden name=classid value=<?=$r[classid]?>>
		<tr>
			<td><?=$r[classid]?></td>
			<td><input name="classname" type="text" id="classname" value="<?=$r[classname]?>">
        [<a href="ListPubVar.php?classid=<?=$r[classid]?>" target="_blank">变量列表</a>]</td>
			<td><input name="classsay" type="text" id="classsay" value="<?=$r[classsay]?>" size="25"></td>
			<td><input type="submit" name="Submit3" value="修改" class="anniu">&nbsp;&nbsp;<input type="button" name="Submit4" value="删除" onClick="if(confirm('删除会删除分类下的所有变量，确认要删除?')){self.location.href='PubVarClass.php?enews=DelPubVarClass&classid=<?=$r[classid]?><?=$ecms_hashur['href']?>';}" class="anniu"></td>
		</tr>
    </form>
<?php
  }
  db_close();
  $empire=null;
  ?>
	</tbody>
</table>
        </div>
    </div>
</div>
</div>
</body>
</html>
