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
CheckLevel($logininid,$loginin,$classid,"buygroup");

//充值类型
function ReturnBuyGroupVar($add){
	$add[gmoney]=(int)$add[gmoney];
	$add[gfen]=(int)$add[gfen];
	$add[gdate]=(int)$add[gdate];
	$add[ggroupid]=(int)$add[ggroupid];
	$add[gzgroupid]=(int)$add[gzgroupid];
	$add[buygroupid]=(int)$add[buygroupid];
	$add[myorder]=(int)$add[myorder];
	return $add;
}

//增加充值类型
function AddBuyGroup($add,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"buygroup");
	$add=ReturnBuyGroupVar($add);
	if(!$add[gname]||!$add[gmoney])
	{
		printerror('EmptyBuyGroup','history.go(-1)');
	}
	$sql=$empire->query("insert into {$dbtbpre}enewsbuygroup(gname,gmoney,gfen,gdate,ggroupid,gzgroupid,buygroupid,gsay,myorder) values('$add[gname]','$add[gmoney]','$add[gfen]','$add[gdate]','$add[ggroupid]','$add[gzgroupid]','$add[buygroupid]','$add[gsay]','$add[myorder]');");
	$id=$empire->lastid();
	if($sql)
	{
		//操作日志
	    insert_dolog("id=$id&gname=$add[gname]&gfen=$add[gfen]&gdate=$add[gdate]");
		printerror('AddBuyGroupSuccess','AddBuyGroup.php?enews=AddBuyGroup'.hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror('DbError','');
	}
}

//修改充值类型
function EditBuyGroup($add,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"buygroup");
	$id=(int)$add['id'];
	$add=ReturnBuyGroupVar($add);
	if(!$id||!$add[gname]||!$add[gmoney])
	{
		printerror('EmptyBuyGroup','history.go(-1)');
	}
	$sql=$empire->query("update {$dbtbpre}enewsbuygroup set gname='$add[gname]',gmoney='$add[gmoney]',gfen='$add[gfen]',gdate='$add[gdate]',ggroupid='$add[ggroupid]',gzgroupid='$add[gzgroupid]',buygroupid='$add[buygroupid]',gsay='$add[gsay]',myorder='$add[myorder]' where id='$id'");
	if($sql)
	{
		//操作日志
	    insert_dolog("id=$id&gname=$add[gname]&gfen=$add[gfen]&gdate=$add[gdate]");
		printerror('EditBuyGroupSuccess','ListBuyGroup.php'.hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror('DbError','');
	}
}

//删除充值类型
function DelBuyGroup($id,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"buygroup");
	$id=(int)$id;
	if(!$id)
	{
		printerror('EmptyBuyGroupid','');
	}
	$r=$empire->fetch1("select id,gname,gfen,gdate from {$dbtbpre}enewsbuygroup where id='$id'");
	if(!$r[id])
	{
		printerror('EmptyBuyGroupid','');
	}
	$sql=$empire->query("delete from {$dbtbpre}enewsbuygroup where id='$id'");
	if($sql)
	{
		//操作日志
	    insert_dolog("id=$id&gname=$r[gname]&gfen=$r[gfen]&gdate=$r[gdate]");
		printerror('DelBuyGroupSuccess','ListBuyGroup.php'.hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror('DbError','');
	}
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
if($enews=='AddBuyGroup')//增加充值类型
{
	AddBuyGroup($_POST,$logininid,$loginin);
}
elseif($enews=='EditBuyGroup')//修改充值类型
{
	EditBuyGroup($_POST,$logininid,$loginin);
}
elseif($enews=='DelBuyGroup')//删除充值类型
{
	DelBuyGroup($_GET['id'],$logininid,$loginin);
}

$search=$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;
$page_line=25;
$offset=$line*$page;
$totalquery="select count(*) as total from {$dbtbpre}enewsbuygroup";
$num=$empire->gettotal($totalquery);
$query="select id,gname,gmoney,gfen,gdate from {$dbtbpre}enewsbuygroup";
$query.=" order by myorder,id limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理充值类型</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<SCRIPT>
//管理充值类型
function zjczlx(){
art.dialog.open('member/AddBuyGroup.php?<?=$ecms_hashur[ehref]?>&enews=AddBuyGroup',
    {title: '管理充值类型',lock: true,opacity: 0.5,width: 800, height: 570,
	 close: function () {
      location.reload();
    }
	});
}
</SCRIPT>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="ListBuyGroup.php<?=$ecms_hashur['whehref']?>">管理充值类型</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理充值类型 <a href="javascript:zjczlx()" class="add">增加充值类型</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>类型名称</th>
            <th>金额(元)</th>
            <th>点数</th>
            <th>有效期(天)</th>
            <th>操作</th>
		</tr>
 <?
  while($r=$empire->fetch($sql))
  {
  ?>
  <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"> <div align="center"> 
        <?=$r[id]?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$r[gname]?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$r[gmoney]?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$r[gfen]?>
      </div></td>
    <td><div align="center">
        <?=$r[gdate]?>
      </div></td>
    <td height="25"> <div align="center">[<a href="AddBuyGroup.php?enews=EditBuyGroup&id=<?=$r[id]?><?=$ecms_hashur['ehref']?>">修改</a>]　[<a href="ListBuyGroup.php?enews=DelBuyGroup&id=<?=$r[id]?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除?');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>	
  		
  		<tr>
  		  <td colspan="6" style="height:35px; overflow:hidden;margin:0;background:#F2F2F2; padding:10px 0;"><?=$returnpage?></td>
		  </tr>
        <tr>
  		 <td colspan="6">前台充值地址：<a href="<?=$public_r[newsurl].'e/member/buygroup/'?>" target="_blank"><?=$public_r[newsurl].'e/member/buygroup/'?></a></td>
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