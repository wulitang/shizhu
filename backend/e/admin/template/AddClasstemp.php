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
$url=$urlgname."<a href=ListClasstemp.php?gid=$gid&classid=$cid".$ecms_hashur['ehref'].">管理封面模板</a>&nbsp;>&nbsp;增加封面模板";
$postword='增加封面模板';
//复制
if($enews=="AddClasstemp"&&$_GET['docopy'])
{
	$tempid=(int)$_GET['tempid'];
	$r=$empire->fetch1("select tempid,tempname,temptext,classid from ".GetDoTemptb("enewsclasstemp",$gid)." where tempid=$tempid");
	$url=$urlgname."<a href=ListClasstemp.php?gid=$gid&classid=$cid".$ecms_hashur['ehref'].">管理封面模板</a>&nbsp;>&nbsp;复制封面模板: ".$r[tempname];
	$postword='修改封面模板';
}
//修改
if($enews=="EditClasstemp")
{
	$tempid=(int)$_GET['tempid'];
	$r=$empire->fetch1("select tempid,tempname,temptext,classid from ".GetDoTemptb("enewsclasstemp",$gid)." where tempid=$tempid");
	$url=$urlgname."<a href=ListClasstemp.php?gid=$gid&classid=$cid".$ecms_hashur['ehref'].">管理封面模板</a>&nbsp;>&nbsp;修改封面模板: ".$r[tempname];
	$postword='修改封面模板';
}
//分类
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewsclasstempclass order by classid");
while($cr=$empire->fetch($csql))
{
	$select="";
	if($cr[classid]==$r[classid])
	{
		$select=" selected";
	}
	$cstr.="<option value='".$cr[classid]."'".$select.">".$cr[classname]."</option>";
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
</script>
<script>
//管理分类
function glfl(){
art.dialog.open('template/ClassTempClass.php<?=$ecms_hashur['whehref']?>',
    {title: '管理分类',width: 800, height: 340});
}
</script>
<style>
.comm-table td {}
.comm-table td p{ padding:5px;}
.comm-table td table{ border-right:1px solid #EFEFEF; border-top:1px solid #EFEFEF; margin-top:10px;}
#temptext {word-wrap:break-word; width:auto; border:1px solid #999999; background:#fff; box-shadow:inset 2px 1px 6px #999;-webkit-box-shadow:inset 2px 1px 6px #999;-moz-box-shadow:inset 2px 1px 6px #999;-o-box-shadow:inset 2px 1px 6px #999;border-radius:5px 0 0 5px;-webkit-border-radius:5px 0 0 5px;-moz-border-radius:5px 0 0 5px;-o-border-radius:5px 0 0 5px;padding:8px;width:100%; box-sizing:border-box; overflow:auto;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
<div class="jqui">
<form name="form1" method="post" action="ListClasstemp.php">
 <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="tempid" type="hidden" id="tempid" value="<?=$tempid?>"> 
<input name="cid" type="hidden" id="cid" value="<?=$cid?>"> <input name="gid" type="hidden" id="gid" value="<?=$gid?>">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:140px;"><h3><span><?=$postword?></span></h3></th>
			<th></th>
		</tr>
      <tr bgcolor="#FFFFFF"> 
      <td height="25">模板名称(*)</td>
      <td height="25" style="text-align:left;"><input name="tempname" type="text" id="tempname" value="<?=$r[tempname]?>" size="30"></td>
      </tr>
      <tr bgcolor="#FFFFFF"> 
      <td height="25">所属分类</td>
      <td height="25" style="text-align:left;"><select name="classid" id="classid">
          <option value="0">不隶属于任何类别</option>
          <?=$cstr?>
        </select> <input type="button" name="Submit6222322" value="管理分类" onClick="glfl()"></td></td>
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
      <td height="25" colspan="2"><input type="submit" name="Submit" value="保存模板"> <input type="reset" name="Submit2" value="重置"> 
        <?php
		if($enews=='EditClasstemp')
		{
		?>
        &nbsp;&nbsp;[<a href="#empirecms" onclick="window.open('TempBak.php?temptype=classtemp&tempid=<?=$tempid?>&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','ViewTempBak','width=450,height=500,scrollbars=yes,left=300,top=150,resizable=yes');">修改记录</a>] 
        <?php
		}
		?>     </td>
      </tr>
	<tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2">&nbsp;&nbsp;[<a href="#ecms" onclick="tempturnit(showtempvar);">显示模板变量说明</a>] 
        &nbsp;&nbsp;[<a href="#ecms" onclick="window.open('EnewsBq.php<?=$ecms_hashur['whehref']?>','','width=600,height=500,scrollbars=yes,resizable=yes');">查看模板标签语法</a>] 
        &nbsp;&nbsp;[<a href="#ecms" onclick="window.open('../ListClass.php<?=$ecms_hashur['whehref']?>','','width=1050,height=600,scrollbars=yes,resizable=yes');">查看JS调用地址</a>]  
        &nbsp;&nbsp;[<a href="#ecms" onclick="window.open('ListTempvar.php<?=$ecms_hashur['whehref']?>','','width=800,height=600,scrollbars=yes,resizable=yes');">查看公共模板变量</a>] 
        &nbsp;&nbsp;[<a href="#ecms" onclick="window.open('ListBqtemp.php<?=$ecms_hashur['whehref']?>','','width=800,height=600,scrollbars=yes,resizable=yes');">查看标签模板</a>] 
      </td>
    </tr>
    <tr bgcolor="#FFFFFF" id="showtempvar" style="display:none"> 
      <td height="25" colspan="2"><table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#DBEAF5">
          <tr bgcolor="#FFFFFF"> 
            <td width="33%" height="25"> <input name="textfield7" type="text" value="[!--pagetitle--]">
              :页面标题</td>
            <td width="34%"><input name="textfield72" type="text" value="[!--pagekey--]">
              :页面关键字</td>
            <td width="33%"><input name="textfield73" type="text" value="[!--pagedes--]">
              :页面描述</td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td height="25"><input name="textfield8" type="text" value="[!--news.url--]">
              :网站地址</td>
            <td><input name="textfield9" type="text" value="[!--newsnav--]">
              :所在位置导航条</td>
            <td><input name="textfield92" type="text" value="[!--class.menu--]">
              :一级栏目导航</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield10" type="text" value="[!--self.classid--]">
              :本栏目/专题ID</td>
            <td><input name="textfield11" type="text" value="[!--class.keywords--]">
              :栏目/专题关键字</td>
            <td><input name="textfield12" type="text" value="[!--class.classimg--]">
              :栏目/专题缩略图</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield132" type="text" value="[!--class.name--]">
              :栏目名</td>
            <td><input name="textfield13" type="text" value="[!--class.intro--]">
              :栏目/专题简介</td>
            <td><input name="textfield14" type="text" value="[!--bclass.id--]">
              : 父栏目ID</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield15" type="text" value="[!--bclass.name--]">
              :父栏目名称</td>
            <td><strong>支持公共模板变量</strong></td>
            <td><strong>支持所有模板标签</strong></td>
          </tr>
        </table></td>
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
