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
CheckLevel($logininid,$loginin,$classid,"card");

//增加点卡
function AddCard($add,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add[card_no]||!$add[password]||!$add[money])
	{printerror("EmptyCard","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"card");
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewscard where card_no='$add[card_no]' limit 1");
	if($num)
	{printerror("ReCard","history.go(-1)");}
	$cardtime=date("Y-m-d H:i:s");
	$add[cardfen]=(int)$add[cardfen];
	$add[money]=(int)$add[money];
	$add[carddate]=(int)$add[carddate];
	$add[cdgroupid]=(int)$add[cdgroupid];
	$add[cdzgroupid]=(int)$add[cdzgroupid];
	$sql=$empire->query("insert into {$dbtbpre}enewscard(card_no,password,cardfen,money,cardtime,endtime,carddate,cdgroupid,cdzgroupid) values('$add[card_no]','$add[password]',$add[cardfen],$add[money],'$cardtime','$add[endtime]',$add[carddate],$add[cdgroupid],$add[cdzgroupid]);");
	$cardid=$empire->lastid();
	if($sql)
	{
		//操作日志
	    insert_dolog("cardid=$cardid&card_no=$add[card_no]&cardfen=$add[cardfen]&carddate=$add[carddate]");
		printerror("AddCardSuccess","AddCard.php?enews=AddCard".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//批量增加点卡
function AddMoreCard($add,$userid,$username){
	global $empire,$dbtbpre;
	$donum=(int)$add['donum'];
	$cardnum=(int)$add['cardnum'];
	$passnum=(int)$add['passnum'];
	$add[cardfen]=(int)$add[cardfen];
	$add[money]=(int)$add[money];
	$add[carddate]=(int)$add[carddate];
	$add[cdgroupid]=(int)$add[cdgroupid];
	$add[cdzgroupid]=(int)$add[cdzgroupid];
	if(!$donum||!$cardnum||!$passnum||!$add[money])
	{printerror("EmptyMoreCard","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"card");
	$cardtime=date("Y-m-d H:i:s");
	//写入卡号
	$no=1;
    while($no<=$donum)
	{
		$card_no=strtolower(no_make_password($cardnum));
		$password=strtolower(no_make_password($passnum));
		$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewscard where card_no='$card_no' limit 1");
		if(!$num)
		{
			$sql=$empire->query("insert into {$dbtbpre}enewscard(card_no,password,cardfen,money,cardtime,endtime,carddate,cdgroupid,cdzgroupid) values('$card_no','$password',$add[cardfen],$add[money],'$cardtime','$add[endtime]',$add[carddate],$add[cdgroupid],$add[cdzgroupid]);");
			$no+=1;
	    }
    }
	if($sql)
	{
		//操作日志
		insert_dolog("cardnum=$donum&cardfen=$add[cardfen]&carddate=$add[carddate]");
		printerror("AddMoreCardSuccess","AddMoreCard.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改点卡
function EditCard($add,$userid,$username){
	global $empire,$time,$dbtbpre;
	$add[cardid]=(int)$add[cardid];
	if(!$add[card_no]||!$add[password]||!$add[money]||!$add[cardid])
	{printerror("EmptyCard","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"card");
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewscard where card_no='$add[card_no]' and cardid<>".$add[cardid]." limit 1");
	if($num)
	{printerror("ReCard","history.go(-1)");}
	$add[cardfen]=(int)$add[cardfen];
	$add[money]=(int)$add[money];
	$add[carddate]=(int)$add[carddate];
	$add[cdgroupid]=(int)$add[cdgroupid];
	$add[cdzgroupid]=(int)$add[cdzgroupid];
	$sql=$empire->query("update {$dbtbpre}enewscard set card_no='$add[card_no]',password='$add[password]',cardfen=$add[cardfen],money=$add[money],endtime='$add[endtime]',carddate=$add[carddate],cdgroupid=$add[cdgroupid],cdzgroupid=$add[cdzgroupid] where cardid='$add[cardid]'");
	if($sql)
	{
		//操作日志
	    insert_dolog("cardid=$add[cardid]&card_no=$add[card_no]&cardfen=$add[cardfen]&carddate=$add[carddate]");
		printerror("EditCardSuccess","ListCard.php?time=$time".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除点卡
function DelCard($cardid,$userid,$username){
	global $empire,$time,$dbtbpre;
	$cardid=(int)$cardid;
	if(!$cardid)
	{printerror("NotChangeCardid","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"card");
	$r=$empire->fetch1("select card_no,cardfen,carddate from {$dbtbpre}enewscard where cardid='$cardid'");
	$sql=$empire->query("delete from {$dbtbpre}enewscard where cardid='$cardid'");
	if($sql)
	{
		//操作日志
		insert_dolog("cardid=$cardid&card_no=$r[card_no]&cardfen=$r[cardfen]&carddate=$r[carddate]");
		printerror("DelCardSuccess","ListCard.php?time=$time".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//批量删除点卡
function DelCard_all($add,$userid,$username){
	global $empire,$time,$dbtbpre;
	$cardid=$add[cardid];
	$count=count($cardid);
	if(!$count)
	{
		printerror("NotChangeCardid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"card");
	$ids='';
	$dh='';
	for($i=0;$i<$count;$i++)
	{
		$ids.=$dh.intval($cardid[$i]);
		$dh=',';
	}
	$sql=$empire->query("delete from {$dbtbpre}enewscard where cardid in (".$ids.")");
	if($sql)
	{
		//操作日志
		insert_dolog("");
		printerror("DelCardSuccess","ListCard.php?time=$add[time]".hReturnEcmsHashStrHref2(0));
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
//增加点卡
if($enews=="AddCard")
{
	$add=$_POST['add'];
	AddCard($add,$logininid,$loginin);
}
//修改点卡
elseif($enews=="EditCard")
{
	$time=(int)$_POST['time'];
	$add=$_POST['add'];
	EditCard($add,$logininid,$loginin);
}
//删除点卡
elseif($enews=="DelCard")
{
	$time=(int)$_GET['time'];
	$cardid=$_GET['cardid'];
	DelCard($cardid,$logininid,$loginin);
}
elseif($enews=="AddMoreCard")//批量增加点卡
{
	$add=$_POST;
	AddMoreCard($add,$logininid,$loginin);
}
elseif($enews=="DelCard_all")//批量删除点卡
{
	DelCard_all($_POST,$logininid,$loginin);
}

$search=$ecms_hashur['ehref'];
$time=$_GET['time'];
if(empty($time))
{$time=$_POST['time'];}
$time=RepPostStr($time,1);
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;
$page_line=25;
$add="";
//搜索
$sear=$_POST['sear'];
if(empty($sear))
{$sear=$_GET['sear'];}
$sear=RepPostStr($sear,1);
if($sear)
{
	$show=$_POST['show'];
	if(empty($show))
	{$show=$_GET['show'];}
	$show=RepPostStr($show,1);
	$keyboard=$_POST['keyboard'];
	if(empty($keyboard))
	{$keyboard=$_GET['keyboard'];}
	$keyboard=RepPostVar2($keyboard);
	if($show==1)
	{$add=" where card_no like '%$keyboard%'";}
	elseif($show==2)
	{$add=" where money='$keyboard'";}
	elseif($show==3)
	{$add=" where cardfen='$keyboard'";}
	else
	{$add=" where carddate='$keyboard'";}
	$search.="&sear=1&show=$show&keyboard=$keyboard";
}
//过期
if($time)
{
	$today=date("Y-m-d");
	$search.="&time=$time";
	if($add)
	{$add.=" and endtime<>'0000-00-00' and endtime<'$today'";}
	else
	{$add.=" where endtime<>'0000-00-00' and endtime<'$today'";}
}
$offset=$line*$page;
$totalquery="select count(*) as total from {$dbtbpre}enewscard".$add;
$num=$empire->gettotal($totalquery);
$query="select cardid,card_no,password,cardfen,money,endtime,cardtime,carddate from {$dbtbpre}enewscard".$add;
$query.=" order by cardid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理点卡</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script>
function CheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
  }
//增加点卡
function zjdk(){
art.dialog.open('member/AddCard.php?<?=$ecms_hashur[ehref]?>&enews=AddCard',
    {title: '增加点卡',lock: true,opacity: 0.5,width: 800, height: 500,
	 close: function () {
      location.reload();
    }
	});
}
//批量增加点卡
function plzjdk(){
art.dialog.open('member/AddMoreCard.php<?=$ecms_hashur[whehref]?>',
    {title: '批量增加点卡',lock: true,opacity: 0.5,width: 800, height: 500,
	 close: function () {
      location.reload();
    }
	});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="ListCard.php<?=$ecms_hashur[whehref]?>">管理点卡</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理点卡 <a href="javascript:zjdk()" class="add">增加点卡</a> <a href="javascript:plzjdk()" class="add">批量增加点卡</a> <a href="ListCard.php?time=1<?=$ecms_hashur['ehref']?>" class="gl">管理过期点卡</a></span></h3>
            <div class="line"></div>
<div class="anniuqun">

<div class="saixuan">
<form name=search method=get action=ListCard.php>
 <?=$ecms_hashur['eform']?>
搜索： 
        <input name="keyboard" type="text" id="keyboard"> <select name="show" id="show">
          <option value="1">卡号</option>
          <option value="2">金额</option>
          <option value="3">点数</option>
          <option value="4">天数</option>
        </select> <input type="submit" name="Submit" value="搜索"> <input name="sear" type="hidden" id="sear" value="1"> 
        <input name="time" type="hidden" id="time" value="<?=$time?>">
	</form>
</div>

<form name="listcardform" method="post" action="ListCard.php" onSubmit="return confirm('确认要删除?');">
  <?=$ecms_hashur['form']?>
<input type="hidden" name="enews" value="DelCard_all">
	<input name="time" type="hidden" id="time" value="<?=$time?>">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
        <th></th>
			<th>ID</th>
			<th>卡号</th>
            <th>金额(元)</th>
            <th>有效期</th>
            <th>点数</th>
            <th>操作</th>
		</tr>
    <?
  while($r=$empire->fetch($sql))
  {
  ?>
    <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
      <td><div align="center"> 
          <input name="cardid[]" type="checkbox" id="cardid[]" value="<?=$r[cardid]?>">
        </div></td>
      <td height="25"> <div align="center"> 
          <?=$r[cardid]?>
        </div></td>
      <td height="25"> <div align="center"> <a alt="End Time:<?=$r[endtime]?><br>Add Time:<?=$r[cardtime]?>"> 
          <?=$r[card_no]?>
          </a> </div></td>
      <td height="25"> <div align="center"> 
          <?=$r[money]?>
        </div></td>
      <td><div align="center"><?=$r[carddate]?></div></td>
      <td height="25"> <div align="center"> 
          <?=$r[cardfen]?>
        </div></td>
      <td height="25"> <div align="center">[<a href="AddCard.php?enews=EditCard&cardid=<?=$r[cardid]?>&time=<?=$time?><?=$ecms_hashur['ehref']?>">修改</a>]&nbsp;[<a href="ListCard.php?enews=DelCard&cardid=<?=$r[cardid]?>&time=<?=$time?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除?');">删除</a>]</div></td>
    </tr>
    <?
  }
  ?>
  		<tr>
  		  <td colspan="7" style="text-align:left;">&nbsp;&nbsp;
<input type=checkbox name=chkall value=on onClick="CheckAll(this.form)"> &nbsp;&nbsp; <input type="submit" name="Submit2" value="删除选中"></td>
		  </tr>
  		<tr>
  		  <td colspan="7" style="height:35px; overflow:hidden;margin:0;background:#F2F2F2; padding:10px 0;"><?=$returnpage?> </td>
		  </tr>
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
<?
db_close();
$empire=null;
?>