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
CheckLevel($logininid,$loginin,$classid,"changedata");

//批量更新信息页地址
function ReInfoUrl($start,$classid,$from,$retype,$startday,$endday,$startid,$endid,$tbname,$userid,$username){
	global $empire,$public_r,$class_r,$fun_r,$dbtbpre;
	//验证权限
	//CheckLevel($userid,$username,$classid,"changedata");
	$start=(int)$start;
	$tbname=RepPostVar($tbname);
	if(empty($tbname)||!eCheckTbname($tbname))
	{
		printerror("ErrorUrl","history.go(-1)");
    }
	$add1='';
	//按栏目刷新
	$classid=(int)$classid;
	if($classid)
	{
		if(empty($class_r[$classid][islast]))//父栏目
		{
			$where=ReturnClass($class_r[$classid][sonclass]);
		}
		else//终极栏目
		{
			$where="classid='$classid'";
		}
		$add1=" and (".$where.")";
    }
	//按ID刷新
	if($retype)
	{
		$startid=(int)$startid;
		$endid=(int)$endid;
		if($endid)
		{
			$add1.=" and id>=$startid and id<=$endid";
	    }
    }
	else
	{
		$startday=RepPostVar($startday);
		$endday=RepPostVar($endday);
		if($startday&&$endday)
		{
			$add1.=" and truetime>=".to_time($startday." 00:00:00")." and truetime<=".to_time($endday." 23:59:59");
	    }
    }
	$b=0;
	$sql=$empire->query("select id,classid,checked from {$dbtbpre}ecms_".$tbname."_index where id>$start".$add1." order by id limit ".$public_r[delnewsnum]);
	while($r=$empire->fetch($sql))
	{
		$b=1;
		$new_start=$r[id];
		//返回表
		$infotb=ReturnInfoMainTbname($tbname,$r['checked']);
		$infor=$empire->fetch1("select newspath,filename,groupid,isurl,titleurl from ".$infotb." where id='$r[id]' limit 1");
		$infourl=GotoGetTitleUrl($r['classid'],$r['id'],$infor['newspath'],$infor['filename'],$infor['groupid'],$infor['isurl'],$infor['titleurl']);
		$empire->query("update ".$infotb." set titleurl='$infourl' where id='$r[id]' limit 1");
	}
	if(empty($b))
	{
	    insert_dolog("");//操作日志
		printerror("ReInfoUrlSuccess",$from);
	}
	echo $fun_r[OneReInfoUrlSuccess]."(ID:<font color=red><b>".$new_start."</b></font>)<script>self.location.href='ReInfoUrl.php?enews=ReInfoUrl&tbname=$tbname&classid=$classid&start=$new_start&from=".urlencode($from)."&retype=$retype&startday=$startday&endday=$endday&startid=$startid&endid=$endid".hReturnEcmsHashStrHref(0)."';</script>";
	exit();
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
	include('../../data/dbcache/class.php');
}
if($enews=="ReInfoUrl")//批量更新信息页地址
{
	$start=$_GET['start'];
	$classid=$_GET['classid'];
	$from=$_GET['from'];
	$retype=$_GET['retype'];
	$startday=$_GET['startday'];
	$endday=$_GET['endday'];
	$startid=$_GET['startid'];
	$endid=$_GET['endid'];
	$tbname=$_GET['tbname'];
	ReInfoUrl($start,$classid,$from,$retype,$startday,$endday,$startid,$endid,$tbname,$logininid,$loginin);
}

//栏目
$fcfile="../../data/fc/ListEnews.php";
$class="<script src=../../data/fc/cmsclass.js></script>";
if(!file_exists($fcfile))
{$class=ShowClass_AddClass("",0,0,"|-",0,0);}
//刷新表
$retable="";
$selecttable="";
$i=0;
$tsql=$empire->query("select tid,tbname,tname from {$dbtbpre}enewstable where intb=0 order by tid");
while($tr=$empire->fetch($tsql))
{
	$i++;
	if($i%4==0)
	{
		$br="<br>";
	}
	else
	{
		$br="";
	}
	$retable.="<input type=checkbox name=tbname[] value='$tr[tbname]' checked>$tr[tname]&nbsp;&nbsp;".$br;
	$selecttable.="<option value='".$tr[tbname]."'>".$tr[tname]."</option>";
}
//选择日期
$todaydate=date("Y-m-d");
$todaytime=time();
$changeday="<select name=selectday onchange=\"document.reform.startday.value=this.value;document.reform.endday.value='".$todaydate."'\">
<option value='".$todaydate."'>--选择--</option>
<option value='".$todaydate."'>今天</option>
<option value='".ToChangeTime($todaytime,7)."'>一周</option>
<option value='".ToChangeTime($todaytime,30)."'>一月</option>
<option value='".ToChangeTime($todaytime,90)."'>三月</option>
<option value='".ToChangeTime($todaytime,180)."'>半年</option>
<option value='".ToChangeTime($todaytime,365)."'>一年</option>
</select>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>批量更新信息页地址</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script src="../ecmseditor/fieldfile/setday.js"></script>
<script type="text/javascript">
$(function(){
			
		});
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
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <a href="ReInfoUrl.php<?=$ecms_hashur['whehref']?>">批量更新信息页地址</a>  </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>批量更新信息页地址</span></h3>
            <div class="line"></div>
            <form action="ReInfoUrl.php" method="get" name="form1" onSubmit="return confirm('确认要更新?');">
 <?=$ecms_hashur['form']?>
            <input name="from" type="hidden" id="from" value="ReInfoUrl.php<?=$ecms_hashur['whehref']?>">
			<ul id="ReInfoUrl">
            		<li><label>数据表：</label><select name="tbname" id="tbname">
                  <option value=''>------ 选择数据表 ------</option>
                  <?=$selecttable?>
                </select></li>
                    <li><label>栏目</label><select name="classid">
                  <option value="0">所有栏目</option>
                  <?=$class?>
                </select><font color="#666666">(如选择父栏目，将更新所有子栏目)</font></li>
                    <li class="jqui"><label>按时间更新：</label><i>从</i> 
                <input name="startday" type="text" size="10" onClick="setday(this)">
                <i>到</i> 
                <input name="endday" type="text" size="12" onClick="setday(this)">
                <i>之间的信息</i> <font color="#666666">(不填将更新所有信息)</font></li>
                    <li class="jqui"><label>按ID更新：</label><i>从</i> 
                <input name="startid" type="text" value="0" size="6">
                <i>到</i> 
                <input name="endid" type="text" value="0" size="6">
                <i>之间的信息</i> <font color="#666666">(两个值为0将更新所有信息)</font></li>
                    <li class="jqui"><label>&nbsp;</label><input type="submit" name="Submit62" value="开始更新"> 
                <input type="reset" name="Submit72" value="重置"> 
                <input name="enews" type="hidden" value="ReInfoUrl"> </li>
                    <li><label>&nbsp;</label><font color="#666666">说明：当改变信息目录形式时，请用此功能来批量更新内容页地址。</font></li>
            </ul>
            </form>
        </div>
        <div class="line"></div>
    </div>
</div>
</div>
</body>
</html>
<?
db_close();
$empire=null;
?>
