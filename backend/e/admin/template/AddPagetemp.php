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
CheckLevel($logininid,$loginin,$classid,"template");
$gid=(int)$_GET['gid'];
$gname=CheckTempGroup($gid);
$urlgname=$gname."&nbsp;>&nbsp;";
$cid=ehtmlspecialchars($_GET['cid']);
$enews=ehtmlspecialchars($_GET['enews']);
$url=$urlgname."<a href=ListPagetemp.php?gid=$gid".$ecms_hashur['ehref'].">管理自定义页面模板</a>&nbsp;>&nbsp;增加自定义页面模板";
$postword='增加自定义页面模板';
//复制
if($enews=="AddPagetemp"&&$_GET['docopy'])
{
	$tempid=(int)$_GET['tempid'];
	$r=$empire->fetch1("select tempid,tempname,temptext from ".GetDoTemptb("enewspagetemp",$gid)." where tempid='$tempid'");
	$url=$urlgname."<a href=ListPagetemp.php?gid=$gid".$ecms_hashur['ehref'].">管理自定义页面模板</a>&nbsp;>&nbsp;复制自定义页面模板: ".$r[tempname];
	$postword='修改自定义页面模板';
}
//修改
if($enews=="EditPagetemp")
{
	$tempid=(int)$_GET['tempid'];
	$r=$empire->fetch1("select tempid,tempname,temptext from ".GetDoTemptb("enewspagetemp",$gid)." where tempid='$tempid'");
	$url=$urlgname."<a href=ListPagetemp.php?gid=$gid".$ecms_hashur['ehref'].">管理自定义页面模板</a>&nbsp;>&nbsp;修改自定义页面模板: ".$r[tempname];
	$postword='修改自定义页面模板';
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=$postword?></title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script>
function ReturnHtml(html)
{
document.form1.temptext.value=html;
}
</script>
<SCRIPT lanuage="JScript">
<!--
function tempturnit(ss)
{
 if (ss.style.display=="") 
  ss.style.display="none";
 else
  ss.style.display=""; 
}
-->
</SCRIPT>
<script>
function ReTempBak(){
	self.location.reload();
}
//查看变量
function ckbl(){
art.dialog({title: '查看变量',content: document.getElementById('mbbl'),padding:0,width:600, height:275,button: [{name: '关闭'}]
	});
}
//查看模板标签语法
function ckmbbq(){
art.dialog.open('template/EnewsBq.php<?=$ecms_hashur[whehref]?>',
    {title: '查看模板标签语法',width: 800, height: 540,button: [{name: '关闭'}]
	});
}
//查看公共模板变量
function ckggmbbl(){
art.dialog.open('template/ListTempvar.php<?=$ecms_hashur[whehref]?>',
    {title: '查看公共模板变量',width:680, height:340,button: [{name: '关闭'}]
	});
}
//查看标签模板
function ckbqmb(){
art.dialog.open('template/ListBqtemp.php<?=$ecms_hashur[whehref]?>',
    {title: '查看标签模板',width:880, height:340,button: [{name: '关闭'}]
	});
}
</script>
<style>
.comm-table td {}
.comm-table td p{ padding:5px;}
.comm-table td table{ border-right:1px solid #EFEFEF; border-top:1px solid #EFEFEF; margin-top:10px;}
#temptext,#listvar{word-wrap:break-word; width:auto; border:1px solid #999999; background:#fff; box-shadow:inset 2px 1px 6px #999;-webkit-box-shadow:inset 2px 1px 6px #999;-moz-box-shadow:inset 2px 1px 6px #999;-o-box-shadow:inset 2px 1px 6px #999;border-radius:5px 0 0 5px;-webkit-border-radius:5px 0 0 5px;-moz-border-radius:5px 0 0 5px;-o-border-radius:5px 0 0 5px;padding:8px;width:100%; box-sizing:border-box; overflow:auto;}
.comm-table table td{ text-align:left; line-height:25px; padding:6px;}
.comm-table2 td{ padding:5px 10px; background:#fff; text-align:center;}
.comm-table2 th{ text-align:center;}
.comm-table2 input{ width:180px; margin:4px 0; text-align:center; border:1px solid #eee; padding:4px 0; color:#5CA4CB;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
<div class="line"></div>
<div class="jqui">
 <form name="form1" method="post" action="ListPagetemp.php">
  <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="tempid" type="hidden" id="tempid" value="<?=$tempid?>"> 
        <input name="gid" type="hidden" id="gid" value="<?=$gid?>">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:180px;"><h3><span><?=$postword?></span></h3></th>
			<th></th>
		</tr>
     <tr bgcolor="#FFFFFF"> 
      <td height="25">模板名称(*)</td>
      <td height="25" style="text-align:left;"> <input name="tempname" type="text" id="tempname" value="<?=$r[tempname]?>" size="30"> 
      </td>
    </tr>
     <tr bgcolor="#FFFFFF">
       <td height="25">标签说明</td>
      <td height="25" style="text-align:left;">[<a href="javascript:void(0)" onclick="ckbl()">显示模板变量说明</a>] 
        &nbsp;&nbsp;[<a href="javascript:void(0)" onclick="ckmbbq()">查看模板标签语法</a>]
        &nbsp;&nbsp;[<a href="#ecms" onClick="window.open('../ListClass.php<?=$ecms_hashur['whehref']?>','','width=1050,height=600,scrollbars=yes,resizable=yes');">查看JS调用地址</a>] 
        &nbsp;&nbsp;[<a href="javascript:void(0)" onclick="ckggmbbl()">查看公共模板变量</a>] 
        &nbsp;&nbsp;[<a href="javascript:void(0)" onclick="ckbqmb()">查看标签模板</a>]</td>
     </tr>
     <tr bgcolor="#FFFFFF"> 
      <td height="25"><strong>模板内容</strong>(*)</td>
      <td height="25" style="text-align:left;">请将模板内容<a href="#ecms" onClick="window.clipboardData.setData('Text',document.form1.temptext.value);document.form1.temptext.select()" title="点击复制模板内容"><strong>复制到Dreamweaver(推荐)</strong></a>或者使用<a href="#ecms" onClick="window.open('editor.php?getvar=opener.document.form1.temptext.value&returnvar=opener.document.form1.temptext.value&fun=ReturnHtml<?=$ecms_hashur['ehref']?>','edittemp','width=880,height=600,scrollbars=auto,resizable=yes');"><strong>模板在线编辑</strong></a>进行可视化编辑</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2"><div align="center"> 
          <textarea name="temptext" cols="90" rows="27" id="temptext" wrap="OFF" style="WIDTH: 100%"><?=ehtmlspecialchars(stripSlashes($r[temptext]))?></textarea>
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25" style="text-align:left;"><input type="submit" name="Submit" value="保存模板"> &nbsp;
        <input type="reset" name="Submit2" value="重置"> 
        <?php
		if($enews=='EditPagetemp')
		{
		?>
        &nbsp;&nbsp;[<a href="#empirecms" onclick="window.open('TempBak.php?temptype=pagetemp&tempid=<?=$tempid?>&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','ViewTempBak','width=450,height=500,scrollbars=yes,left=300,top=150,resizable=yes');">修改记录</a>] 
        <?php
		}
		?>
      </td>
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
<div id="mbbl" style="display:none;">
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#DBEAF5" class="comm-table2">
		  <tr>
          	<th style="border-left:1px solid #CDCDCD;" colspan="3">模板变量说明</th>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td width="33%" height="25"> <input name="textfield" type="text" value="[!--pagetitle--]">
              : 网页标题</td>
            <td width="34%"><input name="textfield2" type="text" value="[!--pagekeywords--]">
              : 网页关键词</td>
            <td width="33%"><input name="textfield3" type="text" value="[!--pagedescription--]">
              : 网页描述</td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td height="25"><input name="textfield32" type="text" value="[!--pagetext--]">
              : 网页内容</td>
            <td><input name="textfield322" type="text" value="[!--pagename--]">
              : 页面名称</td>
            <td><input name="textfield3222" type="text" value="[!--pageid--]">
              : 页面ID</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield4" type="text" value="[!--news.url--]">
              : 网站地址</td>
            <td><strong>支持公共模板变量</strong></td>
            <td><strong>支持所有模板标签</strong></td>
          </tr>
        </table>
</div>
</body>
</html>
