<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require("../../data/dbcache/class.php");
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>菜单</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<SCRIPT lanuage="JScript">
function DisplayImg(ss,imgname,phome)
{
	if(imgname=="listddimg")
	{
		img=todisplay(dolistdd,phome);
		document.images.listddimg.src=img;
	}
	else if(imgname=="addsaleimg")
	{
		img=todisplay(doaddsale,phome);
		document.images.addsaleimg.src=img;
	}
	else if(imgname=="paysendimg")
	{
		img=todisplay(dopaysend,phome);
		document.images.paysendimg.src=img;
	}
	else if(imgname=="setshopimg")
	{
		img=todisplay(dosetshop,phome);
		document.images.setshopimg.src=img;
	}
	else
	{
	}
}
function todisplay(ss,phome)
{
	if(ss.style.display=="") 
	{
  		ss.style.display="none";
		theimg="../openpage/images/add.gif";
	}
	else
	{
  		ss.style.display="";
		theimg="../openpage/images/noadd.gif";
	}
	return theimg;
}
function turnit(ss,img)
{
	DisplayImg(ss,img,0);
}
</SCRIPT>
<style>
 body{ background:#919191;}
 .ileft h3{ padding:10px 10px;font-size: 1.2em;}
 .ileft li a{ padding:10px; padding-left:30px;}
 .ileft li a:before{content:"◆";font-size: 1.2em; margin-right:5px;}
</style>
</head>

<body topmargin="0">
<div class="ileft">
	<h3>管理订单</h3>
        <ul>
        	<li><a href="ListDd.php<?=$ecms_hashur['whehref']?>" target="apmain">所有订单</a></li>
            <li><a href="ListDd.php?sear=1&outproduct=9<?=$ecms_hashur['ehref']?>" target="apmain">未发货订单</a></li>
            <li><a href="ListDd.php?sear=1&outproduct=2<?=$ecms_hashur['ehref']?>" target="apmain">备货中的订单</a></li>
            <li><a href="ListDd.php?sear=1&outproduct=1<?=$ecms_hashur['ehref']?>" target="apmain">已发货的订单</a></li>
            <li><a href="ListDd.php?sear=1&checked=3<?=$ecms_hashur['ehref']?>" target="apmain">退货的订单</a></li>
        </ul>
    <h3>管理促销</h3>
        <ul>
        	<li><a href="ListPrecode.php<?=$ecms_hashur['whehref']?>" target="apmain">优惠码</a></li>
        </ul>
    <h3>支付与配送</h3>
        <ul>
        	<li><a href="ListPayfs.php<?=$ecms_hashur['whehref']?>" target="apmain">管理支付方式</a></li>
            <li><a href="ListPs.php<?=$ecms_hashur['whehref']?>" target="apmain">管理配送方式</a></li>
        </ul>
    <h3>商城参数设置</h3>
        <ul>
        	<li><a href="SetShopSys.php<?=$ecms_hashur['whehref']?>" target="apmain">商城参数设置</a></li>
        </ul>
</div>
</body>
</html>
<?php
db_close();
$empire=null;
?>