<?php
define('EmpireCMSAdmin','1');
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
$link=db_connect();
$empire=new mysqlquery();
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
CheckLevel($logininid,$loginin,$classid,"postdata");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>远程发布</title>
<link rel="stylesheet" type="text/css" href="adminstyle/1/yecha/yecha.css" />
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script>
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
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <a href="PostUrlData.php<?=$ecms_hashur['whehref']?>">远程发布</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>远程发布信息</span></h3>
            <div class="line"></div>
            <form name="form1" method="post" action="enews.php" onSubmit="return confirm('确认要发布？');">
  <table class="comm-table" cellspacing="0">
   <?=$ecms_hashur['form']?>
	<tbody>
		<tr>
			<th width="40"></th>
			<th>任务</th>
			<th>说明</th>
		</tr>
		<tr>
			<td></td>
			<td>附件包 (/d)</td>
			<td>存放附件目录</td>
		</tr>
        <tr> 
      <td>
          <input name="postdata[]" type="checkbox" id="postdata[]" value="d/file!!!0">
        </td>
      <td class="txtleft">上传附件包 (/d/file)</td>
      <td class="txtleft">系统上传的附件存放目录</td>
    </tr>
    <tr> 
      <td>
          <input name="postdata[]" type="checkbox" id="postdata[]" value="d/js!!!0">
        </td>
      <td class="txtleft">公共JS包 (/d/js)</td>
      <td class="txtleft">共公JS包括广告JS,投票JS,图片信息JS,总排行/最新JS等</td>
    </tr>
    <tr> 
      <td>
          <input name="postdata[]" type="checkbox" id="postdata[]" value="s!!!0">
        </td>
      <td class="txtleft">专题包 (/s)</td>
      <td class="txtleft">专题存放目录</td>
    </tr>
    <tr> 
      <td></td>
      <td class="txtleft">系统动态包[与数据库相关] (/e)</td>
      <td class="txtleft">与数据库打交道的包</td>
    </tr>
    <tr> 
      <td>
          <input name="postdata[]" type="checkbox" id="postdata[]3" value="search!!!0">
        </td>
      <td class="txtleft">信息搜索表单包 (/search)</td>
      <td class="txtleft">信息搜索表单</td>
    </tr>
    <tr> 
      <td>
          <input name="postdata[]" type="checkbox" id="postdata[]5" value="e/pl!!!0">
        </td>
      <td class="txtleft">信息评论包 (/e/pl)</td>
      <td class="txtleft">信息评论页面</td>
    </tr>
    <tr> 
      <td>
          <input name="postdata[]" type="checkbox" id="postdata[]" value="e/DoPrint!!!0">
        </td>
      <td class="txtleft">信息打印包(/e/DoPrint)</td>
      <td class="txtleft">信息打印页面</td>
    </tr>
    <tr> 
      <td>
          <input name="postdata[]" type="checkbox" id="postdata[]6" value="e/data/template!!!0">
        </td>
      <td class="txtleft">会员控制面板模板包 (/e/data/template)</td>
      <td class="txtleft">会员控制面板模板</td>
    </tr>
    <tr> 
      <td>
          <input name="postdata[]" type="checkbox" id="postdata[]7" value="e/config/config.php,e/data/dbcache/class.php,e/data/dbcache/class1.php,e/data/dbcache/ztclass.php,e/data/dbcache/MemberLevel.php!!!1">
        </td>
      <td class="txtleft">缓存包 (/e/config/config.php,e/data/dbcache/class.php)</td>
      <td class="txtleft">系统设置的一些参数缓存</td>
    </tr>
    <tr> 
      <td></td>
      <td class="txtleft"><strong>站点目录包 (/)</strong></td>
      <td class="txtleft">信息栏目存放目录</td>
    </tr>
    <?
	$sql=$empire->query("select classid,classurl,classname,classpath from {$dbtbpre}enewsclass where bclassid=0 order by classid desc");
	while($r=$empire->fetch($sql))
	{
	if($r[classurl])
	{
	$classurl=$r[classurl];
	}
	else
	{
	$classurl="../../".$r[classpath];
	}
	?>
    <tr> 
      <td>
          <input name="postdata[]" type="checkbox" id="postdata[]10" value="<?=$r[classpath]?>!!!0">
        </td>
      <td class="txtleft"><a href='<?=$classurl?>' target=_blank> 
        <?=$r[classname]?>
        </a>&nbsp;(/ 
        <?=$r[classpath]?>
        )</td>
      <td class="txtleft"> 
        <?=$r[classurl]?>
      </td>
    </tr>
    <?
	}
	?>
    <tr> 
      <td>
          <input name="postdata[]" type="checkbox" id="postdata[]" value="index<?=$public_r[indextype]?>!!!1">
        </td>
      <td class="txtleft">首页 (/index 
        <?=$public_r[indextype]?>
        )</td>
      <td class="txtleft">网站首页</td>
    </tr>
    <tr> 
      <td>
          <input type=checkbox name=chkall value=on onclick=CheckAll(this.form)>
        </td>
      <td class="txtleft"> <input type="submit" name="Submit" value="开始发布" class="anniu"> &nbsp;&nbsp; 
        <input type="button" name="Submit2" value="设置FTP参数" onClick="javascript:window.open('SetEnews.php<?=$ecms_hashur['whehref']?>');" class="anniu"> 
        <input name="enews" type="hidden" id="enews" value="AddPostUrlData"></td>
      <td class="txtleft">每 <input name="line" type="text" id="line" value="10" size="6">
        个项目为一组</td>
    </tr>
    <tr> 
      <td colspan="3">(备注：远程发布所发费的时间较长，请耐心等待.最好将程序运行时间设为最长)</td>
    </tr>
	</tbody>
</table>
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
