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
CheckLevel($logininid,$loginin,$classid,"repnewstext");
$url="<a href=RepNewstext.php".$ecms_hashur['whehref'].">批量替换信息内容</a>";
//栏目
$fcfile="../../data/fc/ListEnews.php";
$class="<script src=../../data/fc/cmsclass.js></script>";
if(!file_exists($fcfile))
{$class=ShowClass_AddClass("",0,0,"|-",0,0);}
//数据表
$tbname=RepPostVar($_GET['tbname']);
$table='';
$first=1;
$htb=0;
$tsql=$empire->query("select tid,tbname,tname from {$dbtbpre}enewstable order by tid");
while($tr=$empire->fetch($tsql))
{
	if($first==1)
	{
		$firsttable=$tr[tbname];
		$firsttid=$tr[tid];
		$first=0;
	}
	else
	{$first=0;}
	if($tbname==$tr[tbname])
	{
		$htb=1;
		$select=" selected";
		$thistid=$tr[tid];
	}
	else
	{
		$select="";
	}
	$table.="<option value='".$tr[tbname]."'".$select.">".$tr[tname]."</option>";
}
if(!$table)
{printerror("NotRepNewstextTb","history.go(-1)");}
$table="<select name='tbname' onchange=self.location='RepNewstext.php?".$ecms_hashur['ehref']."&tbname='+this.options[this.selectedIndex].value>".$table."</select>";
//字段
if(empty($tbname))
{
	$showtable=$firsttable;
	$showtid=$firsttid;
}
else
{
	if($htb==0)
	{
		printerror("ErrorUrl","history.go(-1)");
	}
	$showtable=$tbname;
	$showtid=$thistid;
}
$field='';
$s=$empire->query("SHOW FIELDS FROM {$dbtbpre}ecms_".$showtable);
$noshowfield=",id,onclick,newspath,keyboard,keyid,userid,username,istop,truetime,ismember,dokey,isgood,titlecolor,titlefont,isurl,titleurl,filename,plnum,firsttitle,totaldown,";
while($r=$empire->fetch($s))
{
	//不显示字段
	if(strstr($noshowfield,",".$r[Field].","))
	{
		//continue;
	}
	$field.="<option value='".$r[Field]."'>".$r[Field]."</option>";
}
$datafsql=$empire->query("SHOW FIELDS FROM {$dbtbpre}ecms_".$showtable."_data_1");
while($dfr=$empire->fetch($datafsql))
{
	if($dfr[Field]=='classid'||$dfr[Field]=='id')
	{
		continue;
	}
	$field.="<option value='".$dfr[Field]."'>".$dfr[Field]."</option>";
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>批量替换信息内容</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<style>
.comm-table td { text-align:left;}
.comm-table td p{ padding:5px;}
.comm-table td table{ border-right:1px solid #EFEFEF; border-top:1px solid #EFEFEF;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>批量替换信息内容</span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
<form action="../ecmscom.php" method="post" name="form1" target="_blank" onSubmit="return confirm('确认要替换？');">
 <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="DoRepNewstext"> <input name="tid" type="hidden" id="tid" value="<?=$showtid?>">
		<tr>
			<th style="width:200px;">批量替换信息内容</th>
			<th></th>
		</tr>
        <tr>
        	<td>选择替换表(*)：</td>
            <td style="text-align:left;"><?=$table?></td>
        </tr>
        <tr>
        	<td>操作栏目：</td>
            <td style="text-align:left;"><select name="classid" id="classid" style="width:150px;">
          <option value=0>所有栏目</option>
          <?=$class?>
        </select> <font color="#666666">(如选择父栏目，将应用于所有子栏目)</font></td>
        </tr>
   <tr> 
      <td height="25">替换字段(*)：</td>
      <td height="25"><select name="field" size="12" id="field" style="width:180px">
          <?=$field?>
        </select> </td>
    </tr>
    <tr> 
      <td height="26">替换内容：</td>
      <td height="26"> <table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#DBEAF5">
          <tr> 
            <td> <div align="center">原字符</div></td>
            <td> <div align="left"> 
                <textarea name="oldword" cols="50" rows="5" id="textarea"></textarea>
                (*)</div></td>
          </tr>
          <tr> 
            <td> <div align="center">新字符 </div></td>
            <td> <div align="left"> 
                <textarea name="newword" cols="50" rows="5" id="newword2"></textarea>
              </div></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td height="26">更新方式：</td>
      <td height="26"><input name="dotype" type="radio" value="0" checked>
        替换
        <input type="radio" name="dotype" value="1">
        覆盖 <font color="#666666">(覆盖方式为将字段全部内容更新为新字符内容，覆盖方式原字符可以不设置)</font></td>
    </tr>
    <tr> 
      <td rowspan="2">选项设置：</td>
      <td height="26">相同更新 
          <input name="over" type="checkbox" id="over" value="1">
          <font color="#666666">(相同更新:当"字段值=原字符"的条件下才替换或覆盖)</font>
        </td>
    </tr>
    <tr> 
      <td height="26">正则替换 
        <input name="dozz" type="checkbox" id="dozz" value="1">
        <font color="#666666"> (正则替换:原字符可以是用正则表示，用"*"表示任意字符)</font></td>
    </tr>
    <tr> 
      <td height="25">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
    </tr>
    <tr> 
      <td height="25" colspan="2">备注：替换时最好备份一下数据．</td>
    </tr>
        </form>
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
