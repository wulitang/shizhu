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
CheckLevel($logininid,$loginin,$classid,"player");

//验证文件
function CheckPlayerFilename($filename){
	if(strstr($filename,"\\")||strstr($filename,"/")||strstr($filename,".."))
	{
		printerror("PlayerFileNotExist","history.go(-1)");
	}
	//文件是否存在
	if(!file_exists("../../DownSys/play/".$filename))
	{
		printerror("PlayerFileNotExist","history.go(-1)");
	}
}

//------------------增加播放器
function AddPlayer($add,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add[player]||!$add[filename])
	{
		printerror("EmptyPlayerName","history.go(-1)");
	}
	CheckPlayerFilename($add[filename]);
	$add['player']=hRepPostStr($add['player'],1);
	$add['bz']=hRepPostStr($add['bz'],1);
	$sql=$empire->query("insert into {$dbtbpre}enewsplayer(player,filename,bz) values('".$add['player']."','".eaddslashes($add[filename])."','".$add[bz]."');");
	$id=$empire->lastid();
	if($sql)
	{
		//操作日志
		insert_dolog("id=$id<br>player=$add[player]");
		printerror("AddPlayerSuccess","player.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//----------------修改播放器
function EditPlayer($add,$userid,$username){
	global $empire,$dbtbpre;
	$add[id]=(int)$add[id];
	if(!$add[player]||!$add[filename]||!$add[id])
	{
		printerror("EmptyPlayerName","history.go(-1)");
	}
	CheckPlayerFilename($add[filename]);
	$add['player']=hRepPostStr($add['player'],1);
	$add['bz']=hRepPostStr($add['bz'],1);
	$sql=$empire->query("update {$dbtbpre}enewsplayer set player='".$add['player']."',filename='".eaddslashes($add[filename])."',bz='".$add['bz']."' where id='$add[id]'");
	if($sql)
	{
		//操作日志
		insert_dolog("id=$add[id]<br>player=$add[player]");
		printerror("EditPlayerSuccess","player.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//---------------删除播放器
function DelPlayer($id,$userid,$username){
	global $empire,$dbtbpre;
	$id=(int)$id;
	if(!$id)
	{
		printerror("NotDelPlayerID","history.go(-1)");
	}
	$r=$empire->fetch1("select id,player from {$dbtbpre}enewsplayer where id='$id'");
	if(!$r[id])
	{
		printerror("NotDelPlayerID","history.go(-1)");
	}
	$sql=$empire->query("delete from {$dbtbpre}enewsplayer where id='$id'");
	if($sql)
	{
		//操作日志
		insert_dolog("id=$id<br>player=$r[player]");
		printerror("DelPlayerSuccess","player.php".hReturnEcmsHashStrHref2(1));
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
//增加播放器
if($enews=="AddPlayer")
{
	AddPlayer($_POST,$logininid,$loginin);
}
//修改播放器
elseif($enews=="EditPlayer")
{
	EditPlayer($_POST,$logininid,$loginin);
}
//删除播放器
elseif($enews=="DelPlayer")
{
	$id=$_GET['id'];
	DelPlayer($id,$logininid,$loginin);
}
$sql=$empire->query("select id,player,filename,bz from {$dbtbpre}enewsplayer order by id");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>增加播放器</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="player.php<?=$ecms_hashur['whehref']?>">管理播放器</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理播放器</span></h3>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
<form name="addplayerform" method="post" action="player.php">
  <?=$ecms_hashur['form']?>
<input type=hidden name=enews value=AddPlayer>
  		<tr>
      <th width="14%" bgcolor="#FFFFFF">播放器名称</th>
      <th width="33%" bgcolor="#FFFFFF">文件名</th>
      <th width="13%" bgcolor="#FFFFFF">说明</th>
      <th width="40%" bgcolor="#FFFFFF">&nbsp;</th>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> 
        <input name="player" type="text" id="player" value="">
      </td>
      <td bgcolor="#FFFFFF">e/DownSys/play/ 
        <input name="filename" type="text" id="filename" value="">
        <a href="#ecms" onclick="window.open('ChangePlayerFile.php?returnform=opener.document.addplayerform.filename.value<?=$ecms_hashur['ehref']?>','','width=400,height=500,scrollbars=yes');">[选择]</a></td>
      <td bgcolor="#FFFFFF"><input name="bz" type="text" id="bz"></td>
      <td bgcolor="#FFFFFF"><input type="submit" name="Submit" value="增加"></td>
    </tr>
 </form>
</table>
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
	<th width="8%">ID</th>
    <th width="14%">播放器名称</th>
    <th width="33%">文件名</th>
    <th width="13%">说明</th>
    <th width="32%"> 操作</th>
		</tr>
<?
  while($r=$empire->fetch($sql))
  {
  ?>
  <form name="playerform<?=$r[id]?>" method=post action=player.php>
	  <?=$ecms_hashur['form']?>
    <input type=hidden name=enews value=EditPlayer>
    <input type=hidden name=id value=<?=$r[id]?>>
    <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'">
      <td><div align="center"><?=$r[id]?></div></td>
      <td height="25"> <input name="player" type="text" value="<?=$r[player]?>"> 
      </td>
      <td>e/DownSys/play/ 
        <input name="filename" type="text" value="<?=$r[filename]?>"> 
        <a href="#ecms" onclick="window.open('ChangePlayerFile.php?returnform=opener.document.playerform<?=$r[id]?>.filename.value<?=$ecms_hashur['ehref']?>','','width=400,height=500,scrollbars=yes');">[选择]</a></td>
      <td><input name="bz" type="text" value="<?=$r[bz]?>"></td>
      <td height="25"> <div align="left"> 
          <input type="submit" name="Submit3" value="修改">
          &nbsp; 
          <input type="button" name="Submit4" value="删除" onclick="if(confirm('确认要删除?')){self.location.href='player.php?enews=DelPlayer&id=<?=$r[id]?><?=$ecms_hashur['href']?>';}">
        </div></td>
    </tr>
  </form>
  <?
  }
  db_close();
  $empire=null;
  ?>
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
