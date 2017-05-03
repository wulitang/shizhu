<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require("../../member/class/user.php");
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
$enews=$_GET['enews'];
$payid=(int)$_GET['payid'];
$r=$empire->fetch1("select * from {$dbtbpre}enewspayapi where payid='$payid'");
$url="在线支付&gt; <a href=PayApi.php>管理支付接口</a>&nbsp;>&nbsp;配置支付接口：<b>".$r[paytype]."</b>";
$registerpay='';
//支付宝
if($r[paytype]=='alipay')
{
	$registerpay="<input type=\"button\" value=\"立即申请支付宝接口\" onclick=\"javascript:window.open('http://www.phome.net/empireupdate/payapi/?ecms=alipay');\">";
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>支付接口</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<style>
.comm-table td{ padding:4px 4px; height:16px;}
.comm-table td table{ border-top:1px solid #EFEFEF; border-right:1px solid #EFEFEF;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>配置支付接口 <a href="ListPayRecord.php" class="gl">管理支付记录</a> <a href="SetPayFen.php" class="gl">支付参数设置</a></span></h3>
<div class="jqui anniuqun">
<form name="setpayform" method="post" action="PayApi.php" enctype="multipart/form-data">
<input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="payid" type="hidden" id="payid" value="<?=$payid?>"> 
<table class="comm-table" cellspacing="0">
	<tbody>
      <tr> 
      <td>接口类型：</td>
      <td style="text-align:left;"> 
        <?=$r[paytype]?>
      </td>
    </tr>
    <tr> 
      <td>接口状态：</td>
      <td style="text-align:left;"><input type="radio" name="isclose" value="0"<?=$r[isclose]==0?' checked':''?>>
        开启 
        <input type="radio" name="isclose" value="1"<?=$r[isclose]==1?' checked':''?>>
        关闭</td>
    </tr>
    <tr> 
      <td>接口名称：</td>
      <td style="text-align:left;"><input name="payname" type="text" id="payname" value="<?=$r[payname]?>" size="35"></td>
    </tr>
    <tr> 
      <td valign="top">接口描述：</td>
      <td style="text-align:left;"><textarea name="paysay" cols="65" rows="6" id="paysay"><?=ehtmlspecialchars($r[paysay])?></textarea></td>
    </tr>
    <?php
	if($r[paytype]=='alipay')
	{
	?>
    <tr> 
      <td>支付宝类型：</td>
      <td style="text-align:left;"><select name="paymethod" id="paymethod">
          <option value="0"<?=$r[paymethod]==0?' selected':''?>>使用标准双接口</option>
          <option value="1"<?=$r[paymethod]==1?' selected':''?>>使用即时到帐交易接口</option>
          <option value="2"<?=$r[paymethod]==2?' selected':''?>>使用担保交易接口</option>
        </select></td>
    </tr>
    <?php
	}
	?>
	<?php
	if($r[paytype]=='alipay')
	{
	?>
    <tr> 
      <td>支付宝帐号：</td>
      <td style="text-align:left;"><input name="payemail" type="text" id="payemail" value="<?=$r[payemail]?>" size="35"></td>
    </tr>
    <?
	}
	?>
    <tr> 
      <td><?=$r[paytype]=='alipay'?'合作者身份(parterID)':'商户号(ID)'?>：</td>
      <td style="text-align:left;"><input name="payuser" type="text" id="payuser" value="<?=$r[payuser]?>" size="35">
        <?=$registerpay?>
      </td>
    </tr>
    <tr> 
      <td><?=$r[paytype]=='alipay'?'交易安全校验码(key)':'密钥(KEY)'?>：</td>
      <td style="text-align:left;"><input name="paykey" type="text" id="paykey" value="<?=$r[paykey]?>" size="35"></td>
    </tr>
    <tr> 
      <td>手续费：</td>
      <td style="text-align:left;"><input name=payfee type=text id="payfee" value='<?=$r[payfee]?>' size="35">
        % </td>
    </tr>
    <tr>
      <td>显示排序：</td>
      <td style="text-align:left;"><input name=myorder type=text id="myorder" value='<?=$r[myorder]?>' size="35">
        <font color="#666666">(值越小显示越前面)</font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td style="text-align:left;"><input type="submit" name="Submit" value=" 设 置 "> &nbsp;&nbsp;&nbsp; 
        <input type="reset" name="Submit2" value="重置"></td>
    </tr>
	</tbody>
</table>
        </form>
</div>
        </div>
    </div>
</div>
</div>
</body>
</html>
