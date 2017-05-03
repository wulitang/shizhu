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
//验证权限
CheckLevel($logininid,$loginin,$classid,"pay");

//批量删除
function DelPayRecord_all($id,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"pay");
	$count=count($id);
	if(!$count)
	{
		printerror("NotDelPayRecordid","history.go(-1)");
	}
	for($i=0;$i<$count;$i++)
	{
		$add.=" id='".intval($id[$i])."' or";
	}
	$add=substr($add,0,strlen($add)-3);
	$sql=$empire->query("delete from {$dbtbpre}enewspayrecord where".$add);
	if($sql)
	{
		//操作日志
		insert_dolog("");
		printerror("DelPayRecordSuccess","ListPayRecord.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
//批量删除
if($enews=="DelPayRecord_all")
{
	$id=$_POST['id'];
	DelPayRecord_all($id,$logininid,$loginin);
}

$line=25;//每页显示条数
$page_line=18;//每页显示链接数
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$offset=$page*$line;//总偏移量
$query="select id,userid,username,orderid,money,posttime,paybz,type,payip from {$dbtbpre}enewspayrecord";
$totalquery="select count(*) as total from {$dbtbpre}enewspayrecord";
//搜索
$search='';
$where='';
if($_GET['sear']==1)
{
	$search.="&sear=1";
	$a='';
	$startday=RepPostVar($_GET['startday']);
	$endday=RepPostVar($_GET['endday']);
	if($startday&&$endday)
	{
		$search.="&startday=$startday&endday=$endday";
		$a.="posttime<='".$endday." 23:59:59' and posttime>='".$startday." 00:00:00'";
	}
	$keyboard=RepPostVar($_GET['keyboard']);
	if($keyboard)
	{
		$and=$a?' and ':'';
		$show=$_GET['show'];
		if($show==1)
		{
			$a.=$and."username like '%$keyboard%'";
		}
		elseif($show==2)
		{
			$a.=$and."payip like '%$keyboard%'";
		}
		elseif($show==3)
		{
			$a.=$and."paybz like '%$keyboard%'";
		}
		else
		{
			$a.=$and."orderid like '%$keyboard%'";
		}
		$search.="&keyboard=$keyboard&show=$show";
	}
	if($a)
	{
		$where.=" where ".$a;
	}
	$query.=$where;
	$totalquery.=$where;
}
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by id desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css"> 
<title>在线支付</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<style>
.comm-table td{ padding:8px 4px; height:16px;}
</style>
<script src="../ecmseditor/fieldfile/setday.js"></script>
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
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > 在线支付&gt; <a href="ListPayRecord.php">管理支付记录</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理支付记录 <a href="PayApi.php" class="gl">管理支付接口</a> <a href="SetPayFen.php" class="gl">支付参数设置</a></span></h3>
            <div class="line"></div>
<div class="anniuqun">

<div class="saixuan">
  <form name=searchlogform method=get action='ListPayRecord.php'>
			时间从 
          <input name="startday" type="text" value="<?=$startday?>" size="12" onClick="setday(this)">
          到 
          <input name="endday" type="text" value="<?=$endday?>" size="12" onClick="setday(this)">
          ，关键字： 
          <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
          <select name="show" id="show">
            <option value="0"<?=$show==0?' selected':''?>>订单号</option>
            <option value="1"<?=$show==1?' selected':''?>>汇款者</option>
            <option value="2"<?=$show==2?' selected':''?>>汇款IP</option>
			<option value="3"<?=$show==3?' selected':''?>>备注</option>
          </select>
          <input name=submit1 type=submit id="submit12" value=搜索>
          <input name="sear" type="hidden" id="sear" value="1">
  </form>
</div>

<form name="form2" method="post" action="ListPayRecord.php" onSubmit="return confirm('确认要删除?');">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:50px;"><input type=checkbox name=chkall value=on onClick="CheckAll(this.form)"></th>
			<th>订单号</th>
            <th>汇款者</th>
            <th>金额</th>
            <th>汇款时间</th>
            <th>汇款IP</th>
            <th>备注</th>
            <th>接口</th>
		</tr>
  <?
  while($r=$empire->fetch($sql))
  {
  	if($r['userid'])
	{
		$username="<a href='../member/AddMember.php?enews=EditMember&userid=$r[userid]'>$r[username]</a>";
	}
	else
	{
		$username="游客(".$r[username].")";
	}
  ?>
    <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
      <td><div align="center"> 
          <input name="id[]" type="checkbox" id="id[]" value="<?=$r[id]?>">
        </div></td>
      <td><div align="center"> 
          <?=$r[orderid]?>
        </div></td>
      <td><div align="center"> 
          <?=$username?>
        </div></td>
      <td height="25"><div align="center"> 
          <?=$r[money]?>
        </div></td>
      <td><div align="center"> 
          <?=$r[posttime]?>
        </div></td>
      <td height="25"><div align="center"> 
          <?=$r[payip]?>
        </div></td>
      <td><div align="center"> 
          <?=$r[paybz]?>
        </div></td>
      <td height="25"><div align="center"><?=$r[type]?></div></td>
    </tr>
    <?
  }
  ?>
  		<tr>
  		  <td colspan="8" style="text-align:left;">&nbsp;&nbsp;
<input type="submit" name="Submit" value="批量删除"> <input name="enews" type="hidden" id="enews" value="DelPayRecord_all"></td>
		  </tr>
  		<tr>
  		  <td colspan="8" style="height:35px; overflow:hidden;margin:0;background:#F2F2F2; padding:10px 0;"><?=$returnpage?></td>
		  </tr>
	</tbody>
</table>
</form>
<?
db_close();
$empire=null;
?>
</div>
        </div>
    </div>
</div>
</div>
</body>
</html>
