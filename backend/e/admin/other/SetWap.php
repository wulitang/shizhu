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
CheckLevel($logininid,$loginin,$classid,"wap");

//设置
function SetWap($add,$userid,$username){
	global $empire,$dbtbpre;
	$wapopen=(int)$add['wapopen'];
	$wapdefstyle=(int)$add['wapdefstyle'];
	$wapshowmid=RepPostVar($add['wapshowmid']);
	$waplistnum=(int)$add['waplistnum'];
	$wapsubtitle=(int)$add['wapsubtitle'];
	$wapchar=(int)$add['wapchar'];
	$sql=$empire->query("update {$dbtbpre}enewspublic set wapopen=$wapopen,wapdefstyle=$wapdefstyle,wapshowmid='$wapshowmid',waplistnum=$waplistnum,wapsubtitle=$wapsubtitle,wapshowdate='$add[wapshowdate]',wapchar=$wapchar limit 1");
	//操作日志
	insert_dolog("");
	printerror("SetWapSuccess","SetWap.php".hReturnEcmsHashStrHref2(1));
}

$enews=$_POST['enews'];
if($enews)
{
	hCheckEcmsRHash();
}
if($enews=='SetWap')
{
	SetWap($_POST,$logininid,$loginin);
}

$r=$empire->fetch1("select wapopen,wapdefstyle,wapshowmid,waplistnum,wapsubtitle,wapshowdate,wapchar from {$dbtbpre}enewspublic limit 1");
//wap模板
$wapdefstyles='';
$stylesql=$empire->query("select styleid,stylename from {$dbtbpre}enewswapstyle order by styleid");
while($styler=$empire->fetch($stylesql))
{
	$select='';
	if($styler['styleid']==$r['wapdefstyle'])
	{
		$select=' selected';
	}
	$wapdefstyles.="<option value='".$styler[styleid]."'".$select.">".$styler[stylename]."</option>";
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>WAP设置</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script>
 //管理WAP模板
function glwapmb(){
art.dialog.open('other/WapStyle.php<?=$ecms_hashur['whehref']?>',
    {title: '管理WAP模板',lock: true,opacity: 0.5, width: 800, height: 540,
	 close: function () {
      location.reload();
    }
	});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="SetWap.php<?=$ecms_hashur['whehref']?>">WAP设置</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>WAP设置 <a href="javascript:glwapmb();" class="gl">管理WAP模板</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<form name="setwapform" method="post" action="SetWap.php">
  <?=$ecms_hashur['form']?>
 <input name=enews type=hidden value=SetWap>
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:200px;">WAP设置</th>
			<th></th>
		</tr>
          <tr bgcolor="#FFFFFF"> 
      <td height="25">开启WAP</td>
      <td height="25" style="text-align:left;"><input type="radio" name="wapopen" value="1"<?=$r[wapopen]==1?' checked':''?>>
        是 
        <input type="radio" name="wapopen" value="0"<?=$r[wapopen]==0?' checked':''?>>
        否 </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">WAP字符集</td>
      <td height="25" style="text-align:left;"><input type="radio" name="wapchar" value="1"<?=$r[wapchar]==1?' checked':''?>>
        UTF-8 
        <input type="radio" name="wapchar" value="0"<?=$r[wapchar]==0?' checked':''?>>
        UNICODE <font color="#666666">(默认为 UNICODE 编码)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">只显示系统模型列表</td>
      <td height="25" style="text-align:left;"><input name="wapshowmid" type="text" id="wapshowmid" value="<?=$r[wapshowmid]?>"> 
        <font color="#666666">(多个模型ID用&quot;,&quot;隔开,空为显示所有)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">默认使用WAP模板</td>
      <td height="25" style="text-align:left;"><select name="wapdefstyle" id="wapdefstyle">
          <?=$wapdefstyles?>
        </select> </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">列表每页显示</td>
      <td height="25" style="text-align:left;"><input name="waplistnum" type="text" id="waplistnum" value="<?=$r[waplistnum]?>">
        条信息</td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">标题截取</td>
      <td height="25" style="text-align:left;"><input name="wapsubtitle" type="text" id="wapsubtitle" value="<?=$r[wapsubtitle]?>">
        个字节 <font color="#666666">(0为不截取)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">时间显示格式</td>
      <td height="25" style="text-align:left;"><input name="wapshowdate" type="text" id="wapshowdate" value="<?=$r[wapshowdate]?>"> 
        <font color="#666666">(格式：Y表示年,m表示月,d表示天)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"></td>
      <td height="25" style="text-align:left;"><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25"></td>
      <td height="25" style="text-align:left;">WAP地址：<a href="<?=$public_r[newsurl]?>e/wap/" target="_blank"><?=$public_r[newsurl]?>e/wap/</a>&nbsp;</td>
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
