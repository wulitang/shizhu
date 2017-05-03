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
CheckLevel($logininid,$loginin,$classid,"userpage");
$gid=(int)$_GET['gid'];
if(!$gid)
{
	$gid=GetDoTempGid();
}
$cid=ehtmlspecialchars($_GET['cid']);
$enews=ehtmlspecialchars($_GET['enews']);
$r[path]='../../page.html';
$r['tempid']=0;
$url="<a href=ListPage.php".$ecms_hashur['whehref'].">管理自定义页面</a>&nbsp;>&nbsp;增加自定义页面";
//复制
if($enews=="AddUserpage"&&$_GET['docopy'])
{
	$id=(int)$_GET['id'];
	$r=$empire->fetch1("select id,title,path,pagetext,classid,pagetitle,pagekeywords,pagedescription,tempid from {$dbtbpre}enewspage where id='$id'");
	$url="<a href=ListPage.php".$ecms_hashur['whehref'].">管理自定义页面</a>&nbsp;>&nbsp;复制自定义页面：<b>".$r[title]."</b>";
}
//修改
if($enews=="EditUserpage")
{
	$id=(int)$_GET['id'];
	$r=$empire->fetch1("select id,title,path,pagetext,classid,pagetitle,pagekeywords,pagedescription,tempid from {$dbtbpre}enewspage where id='$id'");
	$url="<a href=ListPage.php".$ecms_hashur['whehref'].">管理自定义页面</a>&nbsp;>&nbsp;修改自定义页面：<b>".$r[title]."</b>";
}
//模式
$pagemod=1;
if($r['tempid'])
{
	$pagemod=2;
}
if($_GET['ChangePagemod'])
{
	$pagemod=(int)$_GET['ChangePagemod'];
}
//分类
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewspageclass order by classid");
while($cr=$empire->fetch($csql))
{
	$select="";
	if($cr[classid]==$r[classid])
	{
		$select=" selected";
	}
	$cstr.="<option value='".$cr[classid]."'".$select.">".$cr[classname]."</option>";
}
if($pagemod==2)
{
//模板
$pagetempsql=$empire->query("select tempid,tempname from ".GetTemptb("enewspagetemp")." order by tempid");
while($pagetempr=$empire->fetch($pagetempsql))
{
	$select="";
	if($r[tempid]==$pagetempr[tempid])
	{
		$select=" selected";
	}
	$pagetemp.="<option value='".$pagetempr[tempid]."'".$select.">".$pagetempr[tempname]."</option>";
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>增加用户自定义页面</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script>
function ReturnHtml(html)
{
document.form1.pagetext.value=html;
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
//查看变量
function ckbl(){
art.dialog({title: '查看变量',content: document.getElementById('mbbl'),padding:0,width:400, height:275,button: [{name: '关闭'}]
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
// 管理自定义页面分类
function glzdyymfl(){
art.dialog.open('template/PageClass.php<?=$ecms_hashur['whehref']?>',
    {title: ' 管理自定义页面分类',width: 800, height: 540});
}
//管理自定义页面模板
function glzdymb(){
art.dialog.open('template/ListPagetemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>',
    {title: '管理自定义页面模板',width: 800, height: 540});
}
</SCRIPT>
<style>
.comm-table2 td{ padding:5px 10px; background:#fff;}
#mbbl th{ text-align:center;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	
            <div class="line"></div>
<div class="jqui">
   <form name="form1" method="post" action="../ecmscom.php">
  <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="id" type="hidden" id="id" value="<?=$id?>"> 
        <input name="oldpath" type="hidden" id="oldpath" value="<?=$r[path]?>"> 
        <input name="cid" type="hidden" id="cid" value="<?=$cid?>"> <input name="gid" type="hidden" id="gid" value="<?=$gid?>">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:200px;"><h3><span>增加用户自定义页面</span></h3></th>
			<th></th>
		</tr>
        <tr>
        	<td>页面模式：</td>
            <td style="text-align:left;"><input type="radio" name="pagemod" value="1" onclick="self.location.href='AddPage.php?enews=<?=$enews?>&id=<?=$id?>&cid=<?=$cid?>&docopy=<?=$_GET['docopy']?>&gid=<?=$gid?>&ChangePagemod=1';"<?=$pagemod==1?' checked':''?>>
        直接页面式 
        <input type="radio" name="pagemod" value="2" onclick="self.location.href='AddPage.php?enews=<?=$enews?>&id=<?=$id?>&cid=<?=$cid?>&docopy=<?=$_GET['docopy']?>&gid=<?=$gid?>&ChangePagemod=2';"<?=$pagemod==2?' checked':''?>>
        采用模板式</td>
        </tr>
        <tr>
        	<td>页面名称(*)</td>
            <td style="text-align:left;"><input name="title" type="text" id="title" value="<?=$r[title]?>" size="42"> 
        <font color="#666666">(如：联系我们)</font></td>
        </tr>
        <tr>
        	<td>文件名(*)</td>
            <td style="text-align:left;"><input name="path" type="text" id="path" value="<?=$r[path]?>" size="42"> 
        <input type="button" name="Submit4" value="选择目录" onclick="window.open('../file/ChangePath.php?returnform=opener.document.form1.path.value<?=$ecms_hashur['ehref']?>','','width=400,height=500,scrollbars=yes');" class="anniu"> 
        <font color="#666666">(如：../../about.html，放于根目录)</font></td>
        </tr>
        <tr>
        	<td>所属分类</td>
            <td style="text-align:left;"><select name="classid" id="classid">
          <option value="0">不隶属于任何类别</option>
          <?=$cstr?>
        </select> <input type="button" name="Submit6222322" value="管理分类" onclick="glzdyymfl()" class="anniu"></td>
        </tr>
        <?php
	if($pagemod==2)
	{
	?>
        <tr>
        	<td>使用的模板</td>
            <td style="text-align:left;"><select name="tempid" id="tempid">
          <?=$pagetemp?>
        </select> 
        <input type="button" name="Submit62223" value="管理自定义页面模板" onclick="glzdymb()" class="anniu"></td>
        </tr>
   <?php
	}
	?>
        <tr>
        	<td>网页标题</td>
            <td style="text-align:left;"><input name="pagetitle" type="text" id="pagetitle" value="<?=ehtmlspecialchars(stripSlashes($r[pagetitle]))?>" size="42"></td>
        </tr>
        <tr>
        	<td>网页关键词</td>
            <td style="text-align:left;"><input name="pagekeywords" type="text" id="pagekeywords" value="<?=ehtmlspecialchars(stripSlashes($r[pagekeywords]))?>" size="42"></td>
        </tr>
        <tr>
        	<td>网页描述</td>
            <td style="text-align:left;"><input name="pagedescription" type="text" id="pagedescription" value="<?=ehtmlspecialchars(stripSlashes($r[pagedescription]))?>" size="42"></td>
        </tr>
            <?php
	if($pagemod!=2)
	{
	?>
	<tr bgcolor="#FFFFFF"> 
      <td>查看说明</td>
      <td height="25" style=" text-align:left">&nbsp;&nbsp;[<a href="javascript:void(0)" onclick="ckbl()">显示模板变量说明</a>] 
        &nbsp;&nbsp;[<a href="javascript:void(0)" onclick="ckmbbq()">查看模板标签语法</a>] 
        &nbsp;&nbsp;[<a href="#ecms" onclick="window.open('../ListClass.php<?=$ecms_hashur['whehref']?>','','width=1050,height=600,scrollbars=yes,resizable=yes');">查看JS调用地址</a>]  
        &nbsp;&nbsp;[<a href="javascript:void(0)" onclick="ckggmbbl()">查看公共模板变量</a>] 
        &nbsp;&nbsp;[<a href="javascript:void(0)" onclick="ckbqmb()">查看标签模板</a>]</td>
    </tr>
	<?php
	}
	?>
        <?php
	if($pagemod==2)
	{
		//--------------------html编辑器
		include('../ecmseditor/infoeditor/fckeditor.php');
	?>
        <tr>
        	<td><strong>页面内容</strong>(*)</td>
            <td style="text-align:left;"></td>
        </tr>
        <tr>
        	 <td colspan="2" valign="top">    
<?=ECMS_ShowEditorVar('pagetext',stripSlashes($r[pagetext]),'Default','../ecmseditor/infoeditor/','300','100%')?>
   		</td>
        </tr>
   	<?php
	}
	else
	{
	?>
        <tr>
        	<td>页面内容 (*)</td>
            <td style="text-align:left;">请将页面内容<a href="#ecms" onclick="window.clipboardData.setData('Text',document.form1.pagetext.value);document.form1.pagetext.select()" title="点击复制模板内容"><strong>复制到Dreamweaver(推荐)</strong></a>或者使用<a href="#ecms" onclick="window.open('editor.php?getvar=opener.document.form1.pagetext.value&returnvar=opener.document.form1.pagetext.value&fun=ReturnHtml<?=$ecms_hashur['ehref']?>','edittemp','width=880,height=600,scrollbars=auto,resizable=yes');"><strong>模板在线编辑</strong></a>进行可视化编辑</td>
        </tr>
        <tr> 
      <td colspan="2" valign="top"><div align="center"> 
          <textarea name="pagetext" cols="90" rows="27" id="pagetext" wrap="OFF" style="WIDTH: 100%"><?=ehtmlspecialchars(stripSlashes($r[pagetext]))?></textarea>
        </div></td>
    </tr>
	<?php
	}
	?>
        <tr>
        	<td></td>
            <td style="text-align:left;"><input type="submit" name="Submit" value="提交" class="anniu"> <input type="reset" name="Submit2" value="重置" class="anniu"></td>
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
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#DBEAF5" class="comm-table2" style="width:400px; overflow:hidden;">
          <tr>
          	<th style="border-left:1px solid #CDCDCD;">变量标题</th>
            <th>变量代码</th>
          </tr>
          <tr>
          	<td>网页标题</td>
            <td><input name="textfield" type="text" value="[!--pagetitle--]"></td>
          </tr>
          <tr>
          	<td>网页关键词</td>
            <td><input name="textfield2" type="text" value="[!--pagekeywords--]"></td>
          </tr>
          <tr>
          	<td>网页描述</td>
            <td><input name="textfield3" type="text" value="[!--pagedescription--]"></td>
          </tr>
          <tr>
          	<td>页面名称</td>
            <td><input name="textfield322" type="text" value="[!--pagename--]"></td>
          </tr>
          <tr>
          	<td>页面ID</td>
            <td><input name="textfield3222" type="text" value="[!--pageid--]"></td>
          </tr>
          <tr>
          	<td>网站地址</td>
            <td><input name="textfield4" type="text" value="[!--news.url--]"></td>
          </tr>
          <tr>
          	<td colspan="2">支持公共模板变量</td>
          </tr>
          <tr>
          	<td colspan="2">支持所有模板标签</td>
          </tr>
        </table>
</div>
</body>
</html>
<?php
db_close();
$empire=null;
?>