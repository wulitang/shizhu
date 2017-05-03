<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require "../".LoadLang("pub/enews.php");
require "../".LoadLang("pub/fun.php");
require("../../data/dbcache/class.php");
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
CheckLevel($logininid,$loginin,$classid,"log");

//删除日志
function DelDoLog($logid,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"log");
	$logid=(int)$logid;
	if(!$logid)
	{
		printerror("NotDelLogid","history.go(-1)");
	}
	$sql=$empire->query("delete from {$dbtbpre}enewsdolog where logid='$logid'");
	if($sql)
	{
		//操作日志
		insert_dolog("logid=".$logid);
		printerror("DelLogSuccess","ListDolog.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//批量删除日志
function DelDoLog_all($logid,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"log");
	$count=count($logid);
	if(!$count)
	{
		printerror("NotDelLogid","history.go(-1)");
	}
	for($i=0;$i<$count;$i++)
	{
		$add.=" logid='".intval($logid[$i])."' or";
	}
	$add=substr($add,0,strlen($add)-3);
	$sql=$empire->query("delete from {$dbtbpre}enewsdolog where".$add);
	if($sql)
	{
		//操作日志
		insert_dolog("");
		printerror("DelLogSuccess","ListDolog.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//日期删除日志
function DelDoLog_date($add,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"log");
	$start=RepPostVar($add['startday']);
	$end=RepPostVar($add['endday']);
	if(!$start||!$end)
	{
		printerror('EmptyDelLogTime','');
	}
	$startday=$start.' 00:00:00';
	$endday=$end.' 23:59:59';
	$sql=$empire->query("delete from {$dbtbpre}enewsdolog where logtime<='$endday' and logtime>='$startday'");
	if($sql)
	{
		//操作日志
		insert_dolog("time=".$start."~".$end);
		printerror("DelLogSuccess","ListDolog.php".hReturnEcmsHashStrHref2(1));
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
//删除日志
if($enews=="DelDoLog")
{
	$logid=$_GET['logid'];
	DelDoLog($logid,$logininid,$loginin);
}
//批量删除日志
elseif($enews=="DelDoLog_all")
{
	$logid=$_POST['logid'];
	DelDoLog_all($logid,$logininid,$loginin);
}
elseif($enews=="DelDoLog_date")
{
	DelDoLog_date($_POST,$logininid,$loginin);
}

$line=20;//每页显示条数
$page_line=18;//每页显示链接数
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$offset=$page*$line;//总偏移量
//搜索
$search='';
$search.=$ecms_hashur['ehref'];
$where='';
$and=' where ';
//信息ID
$classid=(int)$_GET['classid'];
$id=(int)$_GET['id'];
if($classid&&$id)
{
	$pubid=ReturnInfoPubid($classid,$id);
	$where.=$and."pubid='$pubid'";
	$and=' and ';
	$search.="&classid=$classid&id=$id";
}
if($_GET['sear']==1)
{
	$search.="&sear=1";
	$startday=RepPostVar($_GET['startday']);
	$endday=RepPostVar($_GET['endday']);
	if($startday&&$endday)
	{
		$where.=$and."logtime<='".$endday." 23:59:59' and logtime>='".$startday." 00:00:00'";
		$and=' and ';
		$search.="&startday=$startday&endday=$endday";
	}
	$keyboard=RepPostVar($_GET['keyboard']);
	if($keyboard)
	{
		$show=RepPostStr($_GET['show'],1);
		if($show==1)
		{
			$where.=$and."username like '%$keyboard%'";
		}
		elseif($show==2)
		{
			$where.=$and."logip like '%$keyboard%'";
		}
		else
		{
			$where.=$and."(username like '%$keyboard%' or logip like '%$keyboard%')";
		}
		$and=' and ';
		$search.="&keyboard=$keyboard&show=$show";
	}
}
$search2=$search;
//排序
$mydesc=(int)$_GET['mydesc'];
$desc=$mydesc?'asc':'desc';
$orderby=(int)$_GET['orderby'];
if($orderby==1)//登陆用户
{
	$order="username ".$desc.",logid desc";
	$usernamedesc=$mydesc?0:1;
}
elseif($orderby==2)//IP
{
	$order="logip ".$desc.",logid desc";
	$logipdesc=$mydesc?0:1;
}
elseif($orderby==3)//操作时间
{
	$order="logtime ".$desc.",logid desc";
	$logtimedesc=$mydesc?0:1;
}
else//ID
{
	$order="logid ".$desc;
	$logiddesc=$mydesc?0:1;
}
$search.="&orderby=$orderby&mydesc=$mydesc";
$query="select logid,logip,logtime,username,enews,doing,ipport from {$dbtbpre}enewsdolog".$where;
$totalquery="select count(*) as total from {$dbtbpre}enewsdolog".$where;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by ".$order." limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理登陆日志</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
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
<style>
.comm-table2 td{ padding:0; zoom:1;box-shadow:inset 0 -1px 1px #fff; line-height:25px;}
.comm-table2 td table{ border:0; background:#EBEBEB; color:#666666; text-align:center;box-shadow:inset 0 -1px 1px #fff;}
</style>
</head>
<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > 日志管理 &gt; <a href="ListDolog.php<?=$ecms_hashur['whehref']?>">管理操作日志</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理操作日志 <a href="ListLog.php<?=$ecms_hashur['whehref']?>" class="gl">管理登陆日志</a></span></h3>
            <div class="line"></div>
<div class="anniuqun">

<div class="saixuan">
 <form name=searchlogform method=get action='ListDolog.php'>
		时间从 
          <input name="startday" type="text" value="<?=$startday?>" size="12" onClick="setday(this)">
          到 
          <input name="endday" type="text" value="<?=$endday?>" size="12" onClick="setday(this)">
          ，关键字： 
          <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
          <select name="show" id="show">
            <option value="0"<?=$show==0?' selected':''?>>不限</option>
            <option value="1"<?=$show==1?' selected':''?>>用户名</option>
            <option value="2"<?=$show==2?' selected':''?>>登陆IP</option>
          </select>
          栏目ID：
          <input name="classid" type="text" id="classid" value="<?=$classid?>" size="10">
          信息ID：
          <input name="id" type="text" id="id" value="<?=$id?>" size="10">
          <input name=submit1 type=submit id="submit12" value=搜索>
          <input name="sear" type="hidden" id="sear" value="1">
  </form> 
            </div>

<form name="form2" method="post" action="ListDolog.php" onSubmit="return confirm('确认要删除?');">
<table class="comm-table2" cellspacing="0">
<?=$ecms_hashur['form']?>
	<tbody>
		<tr>
			<th><a href="ListDolog.php?orderby=0&mydesc=<?=$logiddesc.$search2?>">ID</a></th>
			<th><a href="ListDolog.php?orderby=1&mydesc=<?=$usernamedesc.$search2?>">操作者</a></th>
            <th><a href="ListDolog.php?orderby=2&mydesc=<?=$logipdesc.$search2?>">IP</a></th>
            <th><a href="ListDolog.php?orderby=3&mydesc=<?=$logtimedesc.$search2?>">操作时间</a></th>
            <th>删除</th>
		</tr>
<?
  while($r=$empire->fetch($sql))
  {
  ?>
    <tr bgcolor="#DBEAF5" id=log<?=$r[logid]?>> 
      <td> 
        <div align="center"><?=$r[logid]?></div></td>
      <td> 
        <div align="center"> 
          <?=$r[username]?>
        </div></td>
      <td> 
        <div align="center"> 
          <?=$r[logip]?>
        </div></td>
      <td> 
        <div align="center"> 
          <?=$r[logtime]?>
        </div></td>
      <td> 
        <div align="center">[<a href="ListDolog.php?enews=DelDoLog&logid=<?=$r[logid]?><?=$ecms_hashur['href']?>" onClick="return confirm('确认要删除此日志?');">删除</a> 
          <input name="logid[]" type="checkbox" id="logid[]" value="<?=$r[logid]?>" onClick="if(this.checked){log<?=$r[logid]?>.style.backgroundColor='#cccccc';}else{log<?=$r[logid]?>.style.backgroundColor='#DBEAF5';}">
          ]</div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="5"><table border="0" cellspacing="1" cellpadding="3" width="100%;">
          <tr>
            <td width="9%">动作：</td>
            <td width="25%"><?=$enews_r[$r[enews]]?></td>
            <td width="10%">操作对像：</td>
            <td width="56%"><?=$r[doing]?></td>
          </tr>
        </table></td>
    </tr>
    <?
  }
  ?>
  		<tr>
  		  <td colspan="5" style="text-align:left;">&nbsp;&nbsp;
<input type="submit" name="Submit" value="批量删除"> <input name="enews" type="hidden" id="phome" value="DelDoLog_all">&nbsp;
        <input type=checkbox name=chkall value=on onClick=CheckAll(this.form)>
        选中全部</td>
		  </tr>
  		<tr>
  		  <td colspan="5" style="height:35px; overflow:hidden;margin:0;background:#F2F2F2; padding:10px 0;"><?=$returnpage?></td>
		  </tr>
	</tbody>
</table>
</form>
<div class="saixuan" style="text-align:center;">
<form action="ListDolog.php" method="post" name="dellogform" id="dellogform" onSubmit="return confirm('确认要删除?');">          
<?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="DelDoLog_date">
          删除从 
          <input name="startday" type="text" id="startday" onClick="setday(this)" value="<?=$startday?>" size="12">
          到 
          <input name="endday" type="text" id="endday" onClick="setday(this)" value="<?=$endday?>" size="12">
          之间的日志
<input type="submit" name="Submit2" value="提交">
</form>
</div>
<div class="line"></div>
</div>
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