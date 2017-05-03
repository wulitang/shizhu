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
CheckLevel($logininid,$loginin,$classid,"task");

//返回秒组合
function ReturnTogMins($min){
	$count=count($min);
	if($count==0)
	{
		return ',';
	}
	$str=',';
	for($i=0;$i<$count;$i++)
	{
		$str.=$min[$i].',';
	}
	return $str;
}

//增加计划任务
function AddTask($add,$userid,$username){
	global $empire,$dbtbpre;
	if(empty($add['taskname'])||empty($add['filename']))
	{
		printerror('EmptyTaskname','');
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"task");
	if(strstr($add['filename'],'/')||strstr($add['filename'],"\\"))
	{
		printerror('ErrorTaskFilename','');
	}
	$userid=(int)$add['userid'];
	$isopen=(int)$add['isopen'];
	$add['dominute']=ReturnTogMins($add['min']);
	$sql=$empire->query("insert into {$dbtbpre}enewstask(taskname,userid,isopen,filename,lastdo,doweek,doday,dohour,dominute) values('$add[taskname]',$userid,$isopen,'$add[filename]',0,'$add[doweek]','$add[doday]','$add[dohour]','$add[dominute]');");
	if($sql)
	{
		$id=$empire->lastid();
		//操作日志
		insert_dolog("id=$id&taskname=$add[taskname]&filename=$add[filename]");
		printerror('AddTaskSuccess','AddTask.php?enews=AddTask'.hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror('DbError',"");
	}
}

//修改计划任务
function EditTask($add,$userid,$username){
	global $empire,$dbtbpre;
	$id=(int)$add['id'];
	if(!$id||empty($add['taskname'])||empty($add['filename']))
	{
		printerror('EmptyTaskname','');
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"task");
	if(strstr($add['filename'],'/')||strstr($add['filename'],"\\"))
	{
		printerror('ErrorTaskFilename','');
	}
	$userid=(int)$add['userid'];
	$isopen=(int)$add['isopen'];
	$add['dominute']=ReturnTogMins($add['min']);
	$sql=$empire->query("update {$dbtbpre}enewstask set taskname='$add[taskname]',userid=$userid,isopen=$isopen,filename='$add[filename]',doweek='$add[doweek]',doday='$add[doday]',dohour='$add[dohour]',dominute='$add[dominute]' where id=$id");
	if($sql)
	{
		//操作日志
		insert_dolog("id=$id&taskname=$add[taskname]&filename=$add[filename]");
		printerror('EditTaskSuccess','ListTask.php'.hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror('DbError',"");
	}
}

//删除计划任务
function DelTask($add,$userid,$username){
	global $empire,$dbtbpre;
	$id=(int)$add['id'];
	if(!$id)
	{
		printerror('EmptyDelTaskId','');
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"task");
	$r=$empire->fetch1("select taskname,filename from {$dbtbpre}enewstask where id=$id");
	$sql=$empire->query("delete from {$dbtbpre}enewstask where id=$id");
	if($sql)
	{
		//操作日志
		insert_dolog("id=$id&taskname=$r[taskname]&filename=$r[filename]");
		printerror('DelTaskSuccess','ListTask.php'.hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror('DbError',"");
	}
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}

if($enews=="AddTask")
{
	AddTask($_POST,$logininid,$loginin);
}
elseif($enews=="EditTask")
{
	EditTask($_POST,$logininid,$loginin);
}
elseif($enews=="DelTask")
{
	DelTask($_GET,$logininid,$loginin);
}
else
{}

$search=$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=20;//每页显示条数
$page_line=20;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select id,taskname,isopen,lastdo,doweek,doday,dohour,dominute from {$dbtbpre}enewstask";
$totalquery="select count(*) as total from {$dbtbpre}enewstask";
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by id desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理计划任务</title>
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
//增加计划任务
function zjjhrw(){
art.dialog.open('other/AddTask.php?<?=$ecms_hashur[ehref]?>&enews=AddTask',
    {title: '增加计划任务',lock: true,opacity: 0.5, width: 800, height: 540,
	 close: function () {
      location.reload();
    }
	});
}	
//修改计划任务
function xgjhrw(id){
art.dialog.open('other/AddTask.php?<?=$ecms_hashur[ehref]?>&enews=EditTask&id=' + id,
    {title: '修改计划任务',id: 'glzdbox',lock: true,opacity: 0.5, width: 800, height: 650,
	close: function () {
      location.reload();
    }
	});
}
//删除计划任务
function deljhrw(id){
art.dialog.open('other/ListTask.php?<?=$ecms_hashur[href]?>&enews=DelTask&id='+id,
    {title: '删除计划任务',lock: true,opacity: 0.5, width: 800, height: 540,
	init: function () {
    	var that = this, i = 3;
        var fn = function () {
            that.title( i + ' 秒后关闭');
            !i && that.close();
            i --;
        };
        timer = setInterval(fn, 1000);
        fn();
    },
	close: function () {
	  clearInterval(timer);
      location.reload();
    }
	});
}
//执行计划任务
function zxjhrw(id){
art.dialog.open('task.php?<?=$ecms_hashur[ehref]?>&ecms=TodoTask&id='+id,
    {title: '执行计划任务',lock: true,opacity: 0.5, width: 800, height: 540,
	init: function () {
    	var that = this, i = 3;
        var fn = function () {
            that.title( i + ' 秒后关闭');
            !i && that.close();
            i --;
        };
        timer = setInterval(fn, 1000);
        fn();
    },
	close: function () {
	  clearInterval(timer);
    }
	});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <a href='ListTask.php<?=$ecms_hashur['whehref']?>'>管理计划任务</a> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理计划任务 <a href="javascript:void(0)" onclick="zjjhrw()"class="add">增加计划任务</a> <a href="../task.php<?=$ecms_hashur['whhref']?>" target="_blank" class="gl">运行计划任务页面</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>任务名称</th>
			<th>分钟</th>
			<th>小时</th>
            <th>星期</th>
            <th>日</th>
            <th>最后执行时间</th>
            <th>状态</th>
            <th>操作</th>
		</tr>
<?php
  while($r=$empire->fetch($sql))
  {
  	$r['doweek']=','.$r['doweek'].','!=',*,'&&$r['doweek']==0?7:$r['doweek'];
	$lastdo=$r['lastdo']?date("Y-m-d H:i",$r['lastdo']):'---';
	if(strlen($r['dominute'])>26)
	{
		$r['dominute']=substr($r['dominute'],0,23).'...';
	}
  ?>
 <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"> <div align="center"> 
       <?=$r['id']?>
      </div></td>
    <td height="25"> <div align="center"> 
       <?=$r['taskname']?>
      </div></td>
    <td><div align="center">
      <?=$r['dominute']?>
      </div></td>
    <td><div align="center"> 
       <?=$r['dohour']?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$r['doweek']?>
      </div></td>
      <td height="25"> <div align="center"> 
       <?=$r['doday']?>
      </div></td>
      <td height="25"> <div align="center"> 
        <?=$lastdo?>
      </div></td>
      <td height="25"> <div align="center"> 
      <?=$r['isopen']==1?'开启':'关闭'?>
      </div></td>
    <td height="25"> <div align="center">[<a href="javascript:zxjhrw(<?=$r[id]?>)" onClick="return confirm('确认要执行?');">执行</a>] 
        [<a href="javascript:void(0)" onclick="xgjhrw(<?=$r[id]?>)">修改</a>]&nbsp;[<a href="javascript:deljhrw(<?=$r[id]?>)" onClick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
   <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'">
   <td colspan="9" class="txtleft"><?=$returnpage?></td>
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
