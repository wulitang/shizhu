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
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <a href="ChangeData.php<?=$ecms_hashur['whehref']?>">数据更新</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>总体刷新</span></h3>
        <div class="line"></div>
<table class="comm-table tban" cellspacing="0" id=ReAllHtml>
	<tbody>
		<tr>
			<th>整体主要页面刷新</th>
			<th>其他页面刷新</th>
			<th width="270">更新缓存数据</th>
		</tr>
		<tr>
			<td><input type="button" name="Submit2" value="刷新首页" onClick="self.location.href='../ecmschtml.php?enews=ReIndex<?=$ecms_hashur['href']?>';" title="生成首页"></td>
			<td><input type="button" name="Submit2222" value="刷新所有专题页" onclick="self.location.href='../ecmschtml.php?enews=ReZtListHtml_all&amp;from=ReHtml/ChangeData.php<?=urlencode($ecms_hashur['whehref'])?><?=$ecms_hashur['href']?>';" title="生成所有专题页面" /></td>
			<td rowspan="6" valign="top" style=" padding:0">
				<div class="shuaxin">
                	<ul>
                    	<li><a href="../enews.php?enews=ChangeEnewsData<?=$ecms_hashur['href']?>" title="更新系统的缓存(一般是网站搬家时使用)">更新数据库缓存</a></li>
                        <li><a href="../ecmschtml.php?enews=ReClassPath<?=$ecms_hashur['href']?>" title="重新建立栏目目录(一般是网站搬家时使用)">恢复栏目目录</a></li>
                        <li><a href="../ecmsclass.php?enews=DelFcListClass<?=$ecms_hashur['href']?>" title="重新更新[信息管理]菜单下的栏目列表及[栏目管理]菜单下的管理栏目页面。(一般是网站搬家时使用)">删除栏目缓存文件</a></li>
                        <li><a href="../ecmsclass.php?enews=ChangeSonclass<?=$ecms_hashur['href']?>" title="一般应用于修改栏目所属父栏目后使用此功能。">更新栏目关系</a></li>
                        <li><a href="../ecmschtml.php?enews=UpdateClassInfosAll&from=ReHtml/ChangeData.php<?=urlencode($ecms_hashur['whehref'])?><?=$ecms_hashur['href']?>" title="重新统计栏目下信息数量，一般应用于批量删除信息后使用此功能。">更新栏目信息数</a></li>
                        <li><a href="../ecmscom.php?enews=ClearTmpFileData<?=$ecms_hashur['href']?>" onClick="return confirm('清除前请确认用户没有正在采集、批量刷新页面与远程发布，确认?');" title="清除临时和缓存文件，可清空产生的临时文件，还有就是更新动态页面模板时使用，用于实时更换模板">清除临时文件和数据</a></li>
                      <li class="zdyym">自定义刷新页面</li>
                        <li><a href="../ecmschtml.php?enews=ReUserpageAll&from=ReHtml/ChangeData.php<?=urlencode($ecms_hashur['whehref'])?><?=$ecms_hashur['href']?>" title="生成所有自定义页面">刷新所有自定义页面</a></li>
                        <li><a href="../ecmschtml.php?enews=ReUserlistAll&from=ReHtml/ChangeData.php<?=urlencode($ecms_hashur['whehref'])?><?=$ecms_hashur['href']?>" title="生成所有自定义列表">刷新所有自定义列表</a></li>
                        <li><a href="../ecmschtml.php?enews=ReUserjsAll&from=ReHtml/ChangeData.php<?=urlencode($ecms_hashur['whehref'])?><?=$ecms_hashur['href']?>" title="生成所有自定义JS">刷新所有自定义JS</a></li>
                    </ul>
                </div>
              </td>
		</tr>
		<tr>
		  <td><input type="button" name="Submit22" value="刷新所有信息栏目页" onclick="self.location.href='../ecmschtml.php?enews=ReListHtml_all&amp;from=ReHtml/ChangeData.php<?=urlencode($ecms_hashur['whehref'])?><?=$ecms_hashur['href']?>';" title="生成所有栏目页面" /></td>
		  <td><input type="button" name="Submit422" value="批量刷新碎片文件" onclick="self.location.href='../ecmschtml.php?enews=ReSpAll&amp;from=ReHtml/ChangeData.php<?=urlencode($ecms_hashur['whehref'])?><?=$ecms_hashur['href']?>';" title="生成碎片文件" /></td>
		  </tr>
		<tr>
		  <td>
<form action="ecmschtml.php" method="post" name="dorehtml" id="dorehtml">
          <p>
		    <input type="button" name="Submit3" value="刷新所有信息内容页面" onclick="var toredohtml=0;if(document.dorehtml.havehtml.checked==true){toredohtml=1;}self.location.href='DoRehtml.php?enews=ReNewsHtml&amp;start=0&amp;havehtml='+toredohtml+'&amp;from=ReHtml/ChangeData.php<?=urlencode($ecms_hashur['whehref'])?><?=$ecms_hashur['href']?>';" title="生成所有内容页" />
		  </p>
		    <p>
		      全部刷新 
		      <input name="havehtml" type="checkbox" id="havehtml" value="1" title="把已经生成的内容页一起更新">
		      </p>
</form>
              </td>
		  <td><input type="button" name="Submit" value="批量刷新投票JS" onclick="self.location.href='../tool/ListVote.php?enews=ReVoteJs_all&amp;from=../ReHtml/ChangeData.php<?=urlencode($ecms_hashur['whehref'])?><?=$ecms_hashur['href']?>';" title="生成投票插件的JS文件" /></td>
		  </tr>
		<tr>
		  <td><input type="button" name="Submit4" value="刷新所有信息JS调用" onclick="self.location.href='../ecmschtml.php?enews=ReAllNewsJs&amp;from=ReHtml/ChangeData.php<?=urlencode($ecms_hashur['whehref'])?><?=$ecms_hashur['href']?>';" title="生成所有JS调用文件" /></td>
		  <td><input type="button" name="Submit4222" value="批量刷新广告JS" onclick="self.location.href='../tool/ListAd.php?enews=ReAdJs_all&amp;from=../ReHtml/ChangeData.php<?=urlencode($ecms_hashur['whehref'])?><?=$ecms_hashur['href']?>';" title="生成广告插件的JS文件" /></td>
		  </tr>
		<tr>
		  <td><input type="button" name="Submit422232" value="批量更新动态页面" onclick="self.location.href='../ecmschtml.php?enews=ReDtPage<?=$ecms_hashur['href']?>';" title="生成控制面板模板、登陆状态、登陆JS等动态页面" /></td>
		  <td>
  <form action="../ecmsmod.php" method="GET" name="dochangemodform" id="dochangemodform">
<?=$ecms_hashur['form']?>
    <input type=hidden name=enews value="ChangeAllModForm">
    <p>
      <input type="submit" name="Submit5" value="批量更新模型表单" title="生成发布跟投稿表单(一般是网站搬家时使用)"  class="anniu"/>
      </p>
    <p>更新栏目导航
      <input name="ChangeClass" type="checkbox" id="ChangeClass" value="1" title="更新投稿时选择的栏目" />
      </p>
    </form>
		    </td>
		  </tr>
		<tr>
		  <td>                  <input type="button" name="Submit222" value="刷新所有标题分类页" onclick="self.location.href='../ecmschtml.php?enews=ReTtListHtml_all&from=ReHtml/ChangeData.php<?=urlencode($ecms_hashur['whehref'])?><?=$ecms_hashur['href']?>';" title="生成所有标题分类页面" /></td>
		  <td><input type="button" name="Submit4222322" value="批量更新反馈表单" onclick="self.location.href='../tool/FeedbackClass.php?enews=ReMoreFeedbackClassFile<?=$ecms_hashur['href']?>';" title="生成自定义反馈的表单(一般是网站搬家时使用)" /></td>
		  </tr>
	</tbody>
</table>
<div class="line"></div>
		<h3><span>多栏目刷新</span></h3>
		<div class="line"></div>
        <table class="comm-table tban" cellspacing="0" id=ReMoreListHtml>
	<tbody>
		<tr>
			<th>刷新多栏目页面</th>
			<th width="270"><strong>刷新多专题页面</strong></th>
		</tr>
		<tr>
			<td valign="top">
             			<form name="form2" method="post" action="../ecmschtml.php">
<?=$ecms_hashur['form']?>
            			<select name="classid[]" size="12" multiple id="classid[]" style="width:310px">
                          <?=$class?>
                        </select><br />
                        <input type="submit" name="Submit8" value="开始刷新" class="anniu">
          				<input name="enews" type="hidden" id="enews3" value="GoReListHtmlMore">
                        <input name="gore" type="hidden" id="enews4" value="0">
                        <input name="from" type="hidden" id="gore" value="ReHtml/ChangeData.php<?=$ecms_hashur['whehref']?>"><br />
                        <font color=&quot;#666666>多个用ctrl/shift选择</font>
                        </form>
             </td>
             <td>
				<form name="form2" method="post" action="../ecmschtml.php">
<?=$ecms_hashur['form']?>
                		<select name="classid[]" size="12" multiple id="select2" style="width:250px">
                          <?=$ztclass?>
                        </select><br />
                        <input name="ecms" type="checkbox" id="ecms" value="1" checked>
                        含子分类
                        <input type="submit" name="Submit82" value="开始刷新" class="anniu">
                        <input name="enews" type="hidden" id="enews5" value="GoReListHtmlMore">
                        <input name="gore" type="hidden" id="gore" value="1">
                        <input name="from" type="hidden" id="from" value="ReHtml/ChangeData.php<?=$ecms_hashur['whehref']?>"><br />
                        <font color="#666666">多个用ctrl/shift选择</font>
                </form>
             </td>
        </tr>
</table>
<div class="line"></div>
<h3><span>按条件刷新信息内容页面</span></h3>
<div class="line"></div>
<form action="DoRehtml.php" method="get" name="reform"  onSubmit="return confirm('确认要刷新?');">
 <?=$ecms_hashur['form']?>
<input name="from" type="hidden" id="from" value="ReHtml/ChangeData.php<?=$ecms_hashur['whehref']?>">
	<ul id=ReIfInfoHtml>
    	<li><label>刷新数据表</label><table><tr><td><input name=chkall type=checkbox onClick=CheckAll(this.form) value=on checked>
                <font color="#666666">全选</font></td></tr><tr><td><?=$retable?></td></tr></table></li>
        <li><label>刷新栏目</label><select name="classid" id="classid">
                  <option value="0">所有栏目</option>
                  <?=$class?>
                </select>
                <font color="#666666"> (如选择父栏目，将刷新所有子栏目)</font></li>
        <li><label><input name="retype" type="radio" value="0" checked>按时间刷新：</label>从 
                <input name="startday" type="text" size="12" onClick="setday(this)">
                到 
                <input name="endday" type="text" size="12" onClick="setday(this)">
                之间的数据 
                <?=$changeday?>
                <font color="#666666"> (不填将刷新所有页面)</font></li>
        <li><label><input name="retype" type="radio" value="1">按ID刷新：</label>从 
                <input name="startid" type="text" id="startid" value="0" size="6">
                到 
                <input name="endid" type="text" id="endid" value="0" size="6">
                之间的数据 <font color="#666666">(两个值为0将刷新所有页面)</font></li>
        <li><label>全部刷新：</label><input name="havehtml" type="checkbox" id="havehtml" value="1">
                是<font color="#666666"> (不选择将不刷新已生成过的信息)</font></li>
        <li><label>&nbsp;</label><input type="submit" name="Submit6" value="开始刷新" class="anniu"> 
                <input type="reset" name="Submit7" value="重置" class="anniu"> <input name="enews" type="hidden" id="enews" value="ReNewsHtml"> </li>
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
