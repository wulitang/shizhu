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
//栏目
$fcfile="../../data/fc/ListEnews.php";
$class="<script src=../../data/fc/cmsclass.js></script>";
if(!file_exists($fcfile))
{$class=ShowClass_AddClass("",0,0,"|-",0,0);}
//刷新表
$retable="";
$selecttable="";
$cleartable='';
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
	$cleartable.="<option value='".$tr[tid]."'>".$tr[tname]."</option>";
}
//专题
$ztclass="";
$ztsql=$empire->query("select ztid,ztname from {$dbtbpre}enewszt order by ztid desc");
while($ztr=$empire->fetch($ztsql))
{
	$ztclass.="<option value='".$ztr['ztid']."'>".$ztr['ztname']."</option>";
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
<title>更新数据</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="../ecmseditor/fieldfile/setday.js"></script>
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
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <a href="ChangeData.php">数据更新</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>批量更新信息评论数</span></h3>
        <div class="line"></div>
<form action="../ecmspl.php" method="get" name="form1" onsubmit="return confirm('确认要更新?');">
  <?=$ecms_hashur['form']?>
    <input name="from" type="hidden" id="from" value="ReHtml/DoUpdateData.php<?=$ecms_hashur['whehref']?>">
	<ul id=IfTotalPlNum>
    	<li><label>数据表：</label>
        		<select name="tbname" id="tbname">
                  <option value=''>------ 选择数据表 ------</option>
                  <?=$selecttable?>
                </select>
                (*)</li>
        <li><label>栏目</label><select name="classid">
                  <option value="0">所有栏目</option>
                  <?=$class?>
                </select>
                <font color="#666666">(如选择父栏目，将更新所有子栏目)</font></li>
        <li><label><input name="retype" type="radio" value="0" checked>按时间更新：</label><input name="startday" type="text" size="12" onClick="setday(this)">
                到 
                <input name="endday" type="text" size="12" onClick="setday(this)">
                之间的信息 <font color="#666666">(不填将更新所有信息)</font></li>
        <li><label><input name="retype" type="radio" value="1">按ID更新：</label>从 
                <input name="startid" type="text" value="0" size="6">
                到 
                <input name="endid" type="text" value="0" size="6">
                之间的信息 <font color="#666666">(两个值为0将更新所有信息)</font></li>
        <li><label>全部刷新：</label><input name="havehtml" type="checkbox" id="havehtml" value="1">
                是<font color="#666666"> (不选择将不刷新已生成过的信息)</font></li>
        <li><label>&nbsp;</label><input type="submit" name="Submit62" value="开始更新" class="anniu"> 
                <input type="reset" name="Submit72" value="重置" class="anniu"> <input name="enews" type="hidden" value="UpdateAllInfoPlnum">  </li>
                <li><label>&nbsp;</label>说明：当信息表里的评论数与实际评论数不符时使用。</li>
    </ul>
</form>
<div class="line"></div>
<h3><span>批量更新相关链接</span></h3>
<div class="line"></div>
<form action="../ecmscom.php" method="get" name="form1" onSubmit="return confirm('确认要更新?');">
  <?=$ecms_hashur['form']?>
    <input name="from" type="hidden" id="from" value="ReHtml/DoUpdateData.php<?=$ecms_hashur['whehref']?>">
	<ul id=IfOtherInfo>
    	<li><label>数据表：</label>
        		<select name="tbname" id="tbname">
                  <option value=''>------ 选择数据表 ------</option>
                  <?=$selecttable?>
                </select>
                (*)</li>
        <li><label>栏目</label><select name="classid">
                  <option value="0">所有栏目</option>
                  <?=$class?>
                </select>
                <font color="#666666">(如选择父栏目，将更新所有子栏目)</font></li>
        <li><label><input name="retype" type="radio" value="0" checked>按时间更新：</label><input name="startday" type="text" size="12" onClick="setday(this)">
                到 
                <input name="endday" type="text" size="12" onClick="setday(this)">
                之间的信息 <font color="#666666">(不填将更新所有信息)</font></li>
        <li><label><input name="retype" type="radio" value="1">按ID更新：</label>从 
                <input name="startid" type="text" value="0" size="6">
                到 
                <input name="endid" type="text" value="0" size="6">
                之间的信息 <font color="#666666">(两个值为0将更新所有信息)</font></li>
        <li><label>全部刷新：</label><input name="havehtml" type="checkbox" id="havehtml" value="1">
                是<font color="#666666"> (不选择将不刷新已生成过的信息)</font></li>
        <li><label>&nbsp;</label><input type="submit" name="Submit62" value="开始更新" class="anniu"> 
                <input type="reset" name="Submit72" value="重置" class="anniu"> <input name="enews" type="hidden" value="ChangeInfoOtherLink"> </li>
                <li><label>&nbsp;</label>友情提醒：此功能比较耗资源，非必要时请勿用。</li>
    </ul>
</form>
<div class="line"></div>
<h3><span>清理多余信息</span></h3>
<div class="line"></div>
<form action="../ecmscom.php" method="POST" name="form1" onSubmit="return confirm('确认要清理?');">
  <?=$ecms_hashur['form']?>
	<ul id="IfClearBreakInfo">
    	<li><label>选择要清理的数据表</label>
        		<select name="tid" id="tid">
          <option value=''>------ 选择数据表 ------</option>
          <?=$cleartable?>
        </select>
        *</li>
                <li><label>&nbsp;</label><input type="submit" name="Submit6" value="马上清理" class="anniu"> 
        <input name="enews" type="hidden" id="enews2" value="ClearBreakInfo"></li>
        <li><label>&nbsp;</label><table><tr><td><font color="#666666">说明: 当生成信息内容页时提示如下错误时使用本功能来清理多余信息：<br>
      生成内容页提示"Table '*.phome_ecms_' doesn't exist......update ***_ecms_ set havehtml=1   where id='' limit 1"时使用。</font></td></tr></table></li>
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
