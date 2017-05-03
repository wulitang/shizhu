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
CheckLevel($logininid,$loginin,$classid,"picnews");
$url="<a href=ListPicNews.php".$ecms_hashur['whehref'].">管理图片信息</a>&nbsp;>&nbsp;增加图片信息";
$enews=ehtmlspecialchars($_GET['enews']);
//修改图片信息
if($enews=="EditPicNews")
{
	$picid=(int)$_GET['picid'];
	$r=$empire->fetch1("select title,pic_url,url,pic_width,pic_height,open_pic,border,pictext,classid from {$dbtbpre}enewspic where picid='$picid'");
	$open_pic0=" selected";
	$open_pic1="";
	if($r[open_pic]=="_parent")
	{
		$open_pic0="";
		$open_pic1=" selected";
	}
}
//图片类别
$sql=$empire->query("select classid,classname from {$dbtbpre}enewspicclass order by classid");
while($cr=$empire->fetch($sql))
{
	if($r[classid]==$cr[classid])
	{$select=" selected";}
	else
	{$select="";}
	$class.="<option value=".$cr[classid].$select.">".$cr[classname]."</option>";
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理图片信息</title>
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
        	<h3><span>增加图片信息</span></h3>
<div class="jqui anniuqun">
<form name="form1" method="post" action="ListPicNews.php">
<?=$ecms_hashur['form']?>
<input type=hidden name=enews value=<?=$enews?>>
<input type=hidden name=picid value=<?=$picid?>>
<table class="comm-table" cellspacing="0">
	<tbody>
      <tr>
      <td>请选择类别：</td>
      <td style="text-align:left;"><select name="add[classid]" id="add[classid]">
          <?=$class?>
        </select>
        <input type="button" name="Submit6222" value="管理分类" onclick="window.open('PicClass.php<?=$ecms_hashur['whehref']?>');"></td>
    </tr>
    <tr> 
      <td>图片地址：</td>
      <td style="text-align:left;"><input name="pic_url" type="text" id="pic_url" value="<?=$r[pic_url]?>" size="36">
        <a onClick="window.open('../ecmseditor/FileMain.php?modtype=5&type=1&classid=&doing=2&field=pic_url<?=$ecms_hashur['ehref']?>','','width=700,height=550,scrollbars=yes');" title="选择已上传的图片"><img src="../../data/images/changeimg.gif" width="22" height="22" border="0" align="absbottom"></a> 
        宽 
        <input name="pic_width" type="text" id="pic_width" value="<?=$r[pic_width]?>" size="4">
        × 高 
        <input name="pic_height" type="text" id="pic_height" value="<?=$r[pic_height]?>" size="4">
        ，边框： 
        <input name="border" type="text" id="border" value="<?=$r[border]?>" size="2"></td>
    </tr>
    <tr> 
      <td>信息标题：</td>
      <td style="text-align:left;"><input name="title" type="text" id="title" value="<?=$r[title]?>" size="50"></td>
    </tr>
    <tr> 
      <td>链接地址：</td>
      <td style="text-align:left;"><input name="url" type="text" id="url" value="<?=$r[url]?>" size="50"> 
        <select name=open_pic id="open_pic">
          <option value="_blank"<?=$open_pic0?>>在新窗口打开</option>
          <option value="_parent"<?=$open_pic1?>>在原窗口打开</option>
        </select></td>
    </tr>
    <tr> 
      <td valign="top">信息简介：</td>
      <td style="text-align:left;"><textarea name="pictext" cols="65" rows="6" id="pictext"><?=$r[pictext]?></textarea></td>
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
</body>
</html>