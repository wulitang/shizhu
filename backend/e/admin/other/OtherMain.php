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
//ehash
$ecms_hashur=hReturnEcmsHashStrAll();
//验证权限
//CheckLevel($logininid,$loginin,$classid,"pl");

$todaydate=date('Y-m-d');
$yesterday=date('Y-m-d',time()-24*3600);
//会员
$membertb=eReturnMemberTable();
$checkmembernum=$empire->gettotal("select count(*) as total from ".$membertb." where ".egetmf('checked')."=0");
$allmembernum=eGetTableRowNum($membertb);
//管理员
$adminnum=eGetTableRowNum($dbtbpre.'enewsuser');
//订单
$showshopmenu=stristr($public_r['closehmenu'],',shop,')?0:1;
if($showshopmenu)
{
	$allddnum=eGetTableRowNum($dbtbpre.'enewsshopdd');
	$todayddnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsshopdd where ddtime>='".$todaydate." 00:00:00' and ddtime<='".$todaydate." 23:59:59' limit 1");
	$yesterdayddnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsshopdd where ddtime>='".$yesterday." 00:00:00' and ddtime<='".$yesterday." 23:59:59' limit 1");
}
//留言
$allgbooknum=eGetTableRowNum($dbtbpre.'enewsgbook');
$checkgbooknum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsgbook where checked=1");
//反馈
$allfeedbacknum=eGetTableRowNum($dbtbpre.'enewsfeedback');
$noreadfeedbacknum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsfeedback where haveread=0");
//广告
$alladnum=eGetTableRowNum($dbtbpre.'enewsad');
$outtimeadnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsad where endtime<'$todaydate' and endtime<>'0000-00-00'");
//错误报告
$errornum=eGetTableRowNum($dbtbpre.'enewsdownerror');
//友情链接
$sitelinknum=eGetTableRowNum($dbtbpre.'enewslink');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>统计</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script>
function OpenShopSysDdPage(){
	window.open('../openpage/AdminPage.php?leftfile=<?=urlencode('../ShopSys/pageleft.php'.$ecms_hashur['whehref'])?>&mainfile=<?=urlencode('../ShopSys/ListDd.php'.$ecms_hashur['whehref'])?>&title=<?=urlencode('商城系统管理')?><?=$ecms_hashur['ehref']?>','AdminShopSys','');
}
</script>
<script language="javascript">
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
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="OtherMain.php<?=$ecms_hashur['whehref']?>">其他统计</a></div></div>
<div class="kongbai"></div>
    <div id="tab">
	<div class="ui-tab-container">
	<ul class="clearfix ui-tab-list">
		<li><a href="../info/InfoMain.php<?=$ecms_hashur['whehref']?>">信息统计</a></li>
		<li><a href="../pl/PlMain.php<?=$ecms_hashur['whehref']?>">评论统计</a></li>
		<li class="ui-tab-active"><a href="../other/OtherMain.php">其他统计</a></li>
	</ul>
	<div class="ui-tab-bd">
<table class="comm-table2" cellspacing="0" id="changecolor">
<form name="form1" method="post" action="">
	<tbody>
		<tr>
			<th>其他统计</th>
			<th></th>
		</tr>
 <tr bgcolor="<?=$bgcolor?>" height="25"> 
    <td align="center">会员</td>
    <td><div align="left">待审核会员：<a href="../member/ListMember.php?sear=1&schecked=1<?=$ecms_hashur['ehref']?>" target="_blank"><?=$checkmembernum?></a>，会员总数：<a href="../member/ListMember.php<?=$ecms_hashur['whehref']?>" target="_blank"><?=$allmembernum?></a></div></td>
  </tr>
 		<tr>
    <td height="25" align="center">管理员</td>
    <td align="left">管理员总数：<a href="../user/ListUser.php<?=$ecms_hashur['whehref']?>" target="_blank"><?=$adminnum?></a></td>
    </tr>
  <?php
  if($showshopmenu)
  {
  ?>
 		<tr>
 		  <td height="25" align="center">商城订单</td>
 		  <td align="left">今天订单数：<a href="#empirecms" onClick="OpenShopSysDdPage();"><?=$todayddnum?></a>，昨日订单数：<a href="#empirecms" onClick="OpenShopSysDdPage();"><?=$yesterdayddnum?></a>，总订单数：<a href="#empirecms" onClick="OpenShopSysDdPage();"><?=$allddnum?></a></td>
 		  </tr>
  <?php
  }
  ?>
 		  <tr>
    <td height="25"><div align="center"><strong>留言</strong></div></td>
    <td align="right"><div align="left">待审核留言：<a href="../tool/gbook.php?sear=1&checked=2<?=$ecms_hashur['ehref']?>" target="_blank"><?=$checkgbooknum?></a>，总留言数：<a href="../tool/gbook.php<?=$ecms_hashur['whehref']?>" target="_blank"><?=$allgbooknum?></a></div></td>
  </tr>
  <tr>
    <td height="25"><div align="center"><strong>反馈</strong></div></td>
    <td align="right"><div align="left">未查看反馈：<a href="../tool/feedback.php?sear=1&haveread=2<?=$ecms_hashur['ehref']?>" target="_blank"><?=$noreadfeedbacknum?></a>，总反馈数：<a href="../tool/feedback.php<?=$ecms_hashur['whehref']?>" target="_blank"><?=$allfeedbacknum?></a></div></td>
  </tr>
  <tr>
    <td height="25"><div align="center"><strong>广告</strong></div></td>
    <td align="right"><div align="left">过期广告数：<a href="../tool/ListAd.php?time=1<?=$ecms_hashur['ehref']?>" target="_blank"><?=$outtimeadnum?></a>，总广告数：<a href="../tool/ListAd.php<?=$ecms_hashur['whehref']?>" target="_blank"><?=$alladnum?></a></div></td>
  </tr>
  <tr>
    <td height="25"><div align="center"><strong>错误报告</strong></div></td>
    <td align="right"><div align="left">错误报告数：<a href="../DownSys/ListError.php<?=$ecms_hashur['whehref']?>" target="_blank"><?=$errornum?></a></div></td>
  </tr>
  <tr>
    <td height="25"><div align="center"><strong>友情链接</strong></div></td>
    <td align="right"><div align="left">友情链接数：<a href="../tool/ListLink.php<?=$ecms_hashur['whehref']?>" target="_blank"><?=$sitelinknum?></a></div></td>
  </tr>
 		<tr>
 		  <td colspan="2" style="padding:10px;"><font color="#666666">说明：点击"数量"可进入相应的管理。</font></td>
 		  </tr>
	</tbody>
  </form>
</table>
 <div class="line"></div>
  </div>
 </div>
</div>
 <div class="clear"></div>
 </div>
  <script language="javascript">
senfe("changecolor","#FFFFFF","#F0F0F0","#bce774","#bce774");
</script>
</body>
</html>
<?php
db_close();
$empire=null;
?>