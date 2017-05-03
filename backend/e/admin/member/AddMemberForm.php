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
CheckLevel($logininid,$loginin,$classid,"memberf");
$enews=ehtmlspecialchars($_GET['enews']);
$ftype=" checked";
$record="<!--record-->";
$field="<!--field--->";
$url="<a href='ListMemberForm.php".$ecms_hashur['whehref']."'>管理会员表单</a>&nbsp;>&nbsp;增加会员表单";
$postword='增加会员表单';
if($enews=="AddMemberForm"&&$_GET['docopy'])
{
	$fid=(int)$_GET['fid'];
	$ftype="";
	$r=$empire->fetch1("select * from {$dbtbpre}enewsmemberform where fid='$fid'");
	$url="<a href='ListMemberForm.php".$ecms_hashur['whehref']."'>管理会员表单</a>&nbsp;>&nbsp;复制会员表单: ".$r['fname'];
	$postword='复制会员表单';
}
//修改
if($enews=="EditMemberForm")
{
	$fid=(int)$_GET['fid'];
	$ftype="";
	$url="<a href='ListMemberForm.php".$ecms_hashur['whehref']."'>管理会员表单</a>&nbsp;>&nbsp;修改会员表单";
	$postword='修改会员表单';
	$r=$empire->fetch1("select * from {$dbtbpre}enewsmemberform where fid='$fid'");
}
//取得字段
$no=0;
$fsql=$empire->query("select f,fname from {$dbtbpre}enewsmemberf order by myorder,fid");
while($fr=$empire->fetch($fsql))
{
	$no++;
	$bgcolor="ffffff";
	if($no%2==0)
	{
		$bgcolor="#F8F8F8";
	}
	$like=$field.$fr[f].$record;
	$slike=",".$fr[f].",";
	//录入项
	$enterchecked="";
	if(strstr($r[enter],$like))
	{
		$enterchecked=" checked";
		//取得字段标识
		$dor=explode($like,$r[enter]);
		if(strstr($dor[0],$record))
		{
			$dor1=explode($record,$dor[0]);
			$last=count($dor1)-1;
			$fr[fname]=$dor1[$last];
		}
		else
		{
			$fr[fname]=$dor[0];
		}
	}
	$entercheckbox="<input name=center[] type=checkbox value='".$fr[f]."'".$enterchecked.">";
	//前台显示项
	if(strstr($r[viewenter],$like))
	{
		$viewenterchecked=" checked";
	}
	else
	{
		$viewenterchecked="";
	}
	$viewentercheckbox="<input name=venter[] type=checkbox value='".$fr[f]."'".$viewenterchecked.">";
	//必填项
	$mustfchecked="";
	if(strstr($r[mustenter],$slike))
	{$mustfchecked=" checked";}
	$mustfcheckbox="<input name=menter[] type=checkbox value='".$fr[f]."'".$mustfchecked.">";
	//搜索项
	$searchchecked="";
	if(strstr($r[searchvar],$slike))
	{
		$searchchecked=" checked";
	}
	$searchcheckbox="<input name=schange[] type=checkbox value='".$fr[f]."'".$searchchecked.">";
	//可增加
	$canaddfchecked="";
	if(strstr($r[canaddf],$slike))
	{
		$canaddfchecked=" checked";
	}
	if($enews=="AddMemberForm")
	{
		$canaddfchecked=" checked";
	}
	$canaddfcheckbox="<input name=canadd[] type=checkbox value='".$fr[f]."'".$canaddfchecked.">";
	//可修改
	$caneditfchecked="";
	if(strstr($r[caneditf],$slike))
	{
		$caneditfchecked=" checked";
	}
	if($enews=="AddMemberForm")
	{
		$caneditfchecked=" checked";
	}
	$caneditfcheckbox="<input name=canedit[] type=checkbox value='".$fr[f]."'".$caneditfchecked.">";
	$data.="<tr bgcolor='".$bgcolor."'> 
            <td height=25> <div align=center> 
                <input name=cname[".$fr[f]."] type=text value='".$fr[fname]."'>
              </div></td>
            <td> <div align=center> 
                <input name=cfield type=text value='".$fr[f]."' readonly>
              </div></td>
            <td><div align=center> 
                ".$entercheckbox."
              </div></td>
			  <td><div align=center> 
                ".$mustfcheckbox."
              </div></td>
			  <td><div align=center> 
                ".$canaddfcheckbox."
              </div></td>
			  <td><div align=center> 
                ".$caneditfcheckbox."
              </div></td>
			  <td><div align=center> 
                ".$searchcheckbox."
              </div></td>
			  <td><div align=center> 
                ".$viewentercheckbox."
              </div></td>
          </tr>";
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>增加会员表单</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<script>
  function senfe(o,a,b,c,d){
 var t=document.getElementById(o).getElementsByTagName("tr");
 for(var i=0;i<t.length;i++){
  t[i].style.backgroundColor=(t[i].sectionRowIndex%2==0)?a:b;
  t[i].onclick=function(){
   if(this.x!="1"){
    this.x="1";
    this.style.backgroundColor=d;
   }else{
    this.x="0";
    this.style.backgroundColor=(this.sectionRowIndex%2==0)?a:b;
   }
  }
  t[i].onmouseover=function(){
   if(this.x!="1")this.style.backgroundColor=c;
  }
  t[i].onmouseout=function(){
   if(this.x!="1")this.style.backgroundColor=(this.sectionRowIndex%2==0)?a:b;
  }
 }
}

</script>
<style>
.comm-table2 td{ padding:8px 0; height:16px; background:none;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span><?=$postword?></span></h3>
<div class="jqui anniuqun">
<form name="form1" method="post" action="../ecmsmember.php">
 <?=$ecms_hashur['form']?>
<input name="fid" type="hidden" id="fid" value="<?=$fid?>"> <input name="enews" type="hidden" id="enews" value="<?=$enews?>"> 
<table class="comm-table" cellspacing="0">
	<tbody>
        <tr> 
      <td>表单名称</td>
      <td style="text-align:left;"><input name="fname" type="text" id="fname" value="<?=$r[fname]?>" size="43">
        <font color="#666666">(比如：个人注册) </font></td>
    </tr>
    <tr> 
      <td valign="top">选择本表单的字段项<br>
        <br> <br> <input type="button" name="Submit3" value="字段管理" onClick="window.open('ListMemberF.php<?=$ecms_hashur['whehref']?>');"> 
      </td>
      <td valign="top" style="text-align:left;">
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="comm-table2" id="changecolor">
          <tr bgcolor="#DBEAF5"> 
            <th width="26%" style="border-left:1px solid #CDCDCD;"> <div align="center">字段标识</div></th>
            <th width="25%"> <div align="center">字段名</div></th>
            <th width="8%"> <div align="center">录入项</div></th>
            <th width="8%"> <div align="center">必填项</div></th>
            <th width="8%"><div align="center">可增加</div></th>
            <th width="8%"><div align="center">可修改</div></th>
            <th width="8%"><div align="center">搜索项</div></th>
            <th width="9%"><div align="center">前台显示</div></th>
          </tr>
          <?=$data?>
        </table></td>
    </tr>
    <tr> 
      <td valign="top"><p>录入表单模板<br>
          <br>
          (<font color="#FF0000"> 
          <input name="ftype" type="checkbox" id="ftype" value="1"<?=$ftype?>>
          自动生成表单</font>)</p></td>
      <td style="text-align:left;"><textarea name="ftemp" cols="75" rows="20" id="ftemp" style="WIDTH: 100%"><?=ehtmlspecialchars(stripSlashes($r[ftemp]))?></textarea></td>
    </tr>
    <tr> 
      <td valign="top">注释：</td>
      <td style="text-align:left;"><textarea name="fzs" cols="75" rows="10" id="fzs" style="WIDTH: 100%"><?=stripSlashes($r[fzs])?></textarea></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td style="text-align:left;"><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
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
<script language="javascript">
senfe("changecolor","#F2F2F2","#F7F7F7","","");
</script>
</body>
</html>
