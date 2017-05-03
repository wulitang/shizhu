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
CheckLevel($logininid,$loginin,$classid,"precode");
$enews=ehtmlspecialchars($_GET['enews']);
$url="<a href=ListPrecode.php".$ecms_hashur['whehref'].">管理优惠码</a> &gt; <a href=AddMorePrecode.php".$ecms_hashur['whehref'].">批量增加优惠码</a>";
//会员组
$membergroup='';
$line=5;//一行显示五个
$i=0;
$mgsql=$empire->query("select groupid,groupname from {$dbtbpre}enewsmembergroup order by level");
while($level_r=$empire->fetch($mgsql))
{
	$i++;
	$br='';
	if($i%$line==0)
	{
		$br='<br>';
	}
	$membergroup.="<input type='checkbox' name='groupid[]' value='$level_r[groupid]'>".$level_r[groupname]."&nbsp;".$br;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<style>
.comm-table td{ padding:6px 4px; height:16px;}
.comm-table td table{ border-top:1px solid #EFEFEF; border-right:1px solid #EFEFEF;}
</style>
<title>批量增加优惠码</title>
<script>
function on()
{
var f=document.add
f.HTML.value=f.paysay.value;
}
function bs(){
var f=document.add
f.paysay.value=f.HTML.value;
}
function br(){
if(!confirm("是否复位？")){return false;}
document.add.title.select()
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>批量增加优惠码</span></h3>
<div class="jqui anniuqun">
<form name="form1" method="post" action="ListPrecode.php">
<?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="AddMorePrecode">
<table class="comm-table" cellspacing="0">
	<tbody>
     <tr bgcolor="#FFFFFF"> 
      <td style="width:140px;" height="25">批量生成数量(*)：</td>
      <td style="text-align:left;" height="25"><input name="donum" type="text" id="donum" value="10" size="42">
      个</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">优惠码位数(*)：</td>
      <td height="25" style="text-align:left;"><input name="precodenum" type="text" id="cardnum" value="20" size="42">
        位 </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="17%" height="25">优惠码名称(*)：</td>
      <td width="83%" height="25" style="text-align:left;"><input name="prename" type="text" id="prename" size="42"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">优惠类型：</td>
      <td height="25" style="text-align:left;"><select name="pretype" id="pretype">
        <option value="0" selected>减金额</option>
        <option value="1">商品百分比</option>
      </select>
      <font color="#666666">（“减金额”即订单金额-优惠金额，“商品百分比”即给商品打多少折）</font>      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">优惠金额(*)：</td>
      <td height="25" style="text-align:left;"><input name="premoney" type="text" id="premoney" size="42">
        <font color="#666666">(当减金额时填金额，单位：元，当商品百分比时填百分比，单位：%)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">过期时间：</td>
      <td height="25" style="text-align:left;"><input name="endtime" type="text" id="endtime" size="42" onclick="setday(this)">
        <font color="#666666">(空为不限制)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">优惠码重复使用：</td>
      <td height="25" style="text-align:left;"><input name="reuse" type="radio" value="0" checked>
        一次性使用
        <input type="radio" name="reuse" value="1">
可以重复使用</td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">&nbsp;</td>
      <td height="25" style="text-align:left;">限制重复使用次数：
      <input name="usenum" type="text" id="usenum" value="0">
      <font color="#666666">(0为不限)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">满多少金额可使用：</td>
      <td height="25" style="text-align:left;"><input name="musttotal" type="text" id="musttotal" value="0" size="42">
元 <font color="#666666">(0为不限)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">可使用的会员组：<br>
        <font color="#666666">(不选为不限)</font></td>
      <td height="25" style="text-align:left;"><?=$membergroup?></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">可使用的栏目商品：</td>
      <td height="25" style="text-align:left;"><input name="classid" type="text" id="classid" size="42" onclick="setday(this)">
        <font color="#666666">(空为不限，要填写终极栏目ID，多个ID可用半角逗号隔开“,”)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25" style="text-align:left;"> <input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
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