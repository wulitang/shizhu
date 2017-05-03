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
$enews=ehtmlspecialchars($_GET['enews']);
$r[showdate]="Y-m-d H:i:s";
$url=$urlgname."<a href=ListPrinttemp.php?gid=$gid".$ecms_hashur['ehref'].">管理打印模板</a>&nbsp;>&nbsp;增加打印模板";
//复制
if($enews=="AddPrintTemp"&&$_GET['docopy'])
{
	$tempid=(int)$_GET['tempid'];
	$r=$empire->fetch1("select tempid,tempname,temptext,showdate,modid from ".GetDoTemptb("enewsprinttemp",$gid)." where tempid=$tempid");
	$url=$urlgname."<a href=ListPrinttemp.php?gid=$gid".$ecms_hashur['ehref'].">管理打印模板</a>&nbsp;>&nbsp;复制打印模板：<b>".$r[tempname]."</b>";
}
//修改
if($enews=="EditPrintTemp")
{
	$tempid=(int)$_GET['tempid'];
	$r=$empire->fetch1("select tempid,tempname,temptext,showdate,modid from ".GetDoTemptb("enewsprinttemp",$gid)." where tempid=$tempid");
	$url=$urlgname."<a href=ListPrinttemp.php?gid=$gid".$ecms_hashur['ehref'].">管理打印模板</a>&nbsp;>&nbsp;修改打印模板：<b>".$r[tempname]."</b>";
}
//系统模型
$mod='';
$msql=$empire->query("select mid,mname from {$dbtbpre}enewsmod where usemod=0 order by myorder,mid");
while($mr=$empire->fetch($msql))
{
	if($mr[mid]==$r[modid])
	{$select=" selected";}
	else
	{$select="";}
	$mod.="<option value=".$mr[mid].$select.">".$mr[mname]."</option>";
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>增加打印模板</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
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
//查看公共模板变量
function ckggmbbl(){
art.dialog.open('template/ListTempvar.php<?=$ecms_hashur[whehref]?>',
    {title: '查看公共模板变量',width:680, height:340,button: [{name: '关闭'}]
	});
}
//查看内容字段
function cknrzd(){
art.dialog.open('template/ShowVar.php?<?=$ecms_hashur[ehref]?>&modid='+document.form1.modid.value,
    {title: '查看内容字段',width: 600, height: 540
	});
}
//管理系统模型
function glxtmx(){
art.dialog.open('db/ListTable.php<?=$ecms_hashur['whehref']?>',
    {title: '管理系统模型',width: 800, height: 540});
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
<div class="jqui">
<form name="form1" method="post" action="ListPrinttemp.php">
  <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="tempid" type="hidden" id="tempid" value="<?=$tempid?>"> 
        <input name="gid" type="hidden" id="gid" value="<?=$gid?>">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:140px;"><h3><span>增加打印模板</span></h3></th>
			<th></th>
		</tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">模板名(*)</td>
      <td height="25" style="text-align:left;"> <input name="tempname" type="text" id="tempname" value="<?=$r[tempname]?>"> 
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">所属系统模型(*)</td>
      <td height="25" style="text-align:left;"><select name="modid" id="modid">
          <?=$mod?>
        </select> <input type="button" name="Submit6" value="管理系统模型" onClick="glxtmx()"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">时间显示格式</td>
      <td height="25" style="text-align:left;"><input name="showdate" type="text" id="showdate" value="<?=$r[showdate]?>" size="20"> 
        <select name="select4" onChange="document.form1.showdate.value=this.value">
          <option value="Y-m-d H:i:s">选择</option>
          <option value="Y-m-d H:i:s">2005-01-27 11:04:27</option>
          <option value="Y-m-d">2005-01-27</option>
          <option value="m-d">01-27</option>
        </select></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">标签说明</td>
      <td height="25" style="text-align:left;">[<a href="javascript:void(0)" onclick="ckbl()">显示模板变量说明</a>] 
        &nbsp;&nbsp;[<a href="javascript:void(0)" onclick="cknrzd()">查看内容字段</a>]&nbsp;&nbsp;[<a href="#ecms" onclick="window.open('../ListClass.php<?=$ecms_hashur['whehref']?>','','width=1050,height=600,scrollbars=yes,resizable=yes');">查看JS调用地址</a>]  
        &nbsp;&nbsp;[<a href="javascript:void(0)" onclick="ckggmbbl()">查看公共模板变量</a>]</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><strong>模板内容</strong>(*)</td>
      <td height="25" style="text-align:left;">请将模板内容<a href="#ecms" onclick="window.clipboardData.setData('Text',document.form1.temptext.value);document.form1.temptext.select()" title="点击复制模板内容"><strong>复制到Dreamweaver(推荐)</strong></a>或者使用<a href="#ecms" onclick="window.open('editor.php?getvar=opener.document.form1.temptext.value&returnvar=opener.document.form1.temptext.value&fun=ReturnHtml<?=$ecms_hashur['ehref']?>','edittemp','width=880,height=600,scrollbars=auto,resizable=yes');"><strong>模板在线编辑</strong></a>进行可视化编辑</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2"><div align="center"> 
          <textarea name="temptext" cols="90" rows="27" id="temptext" wrap="OFF" style="WIDTH: 100%"><?=ehtmlspecialchars(stripSlashes($r[temptext]))?></textarea>
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2"><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置">
        <?php
		if($enews=='EditPrintTemp')
		{
		?>
        &nbsp;&nbsp;[<a href="#empirecms" onclick="window.open('TempBak.php?temptype=printtemp&tempid=<?=$tempid?>&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','ViewTempBak','width=450,height=500,scrollbars=yes,left=300,top=150,resizable=yes');">修改记录</a>] 
        <?php
		}
		?>      </td>
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
            <td height="25"><input name="textfield18" type="text" value="[!--pagetitle--]">
              :页面标题</td>
            <td><input name="textfield72" type="text" value="[!--pagekey--]">
              :页面关键字</td>
            <td><input name="textfield73" type="text" value="[!--pagedes--]">
              :页面描述</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield30222" type="text" value="[!--newsnav--]">
              :导航条</td>
            <td><input name="textfield92" type="text" value="[!--class.menu--]">
              :一级栏目导航</td>
            <td><input name="textfield34" type="text" value="[!--news.url--]">
              :网站地址</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td width="33%" height="25"><input name="textfield45" type="text" value="[!--id--]">
              :信息ID</td>
            <td width="34%"><input name="textfield46" type="text" value="[!--classid--]">
              :栏目ID</td>
            <td width="33%"><input name="textfield54" type="text" value="[!--titleurl--]">
              :标题链接</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield23" type="text" value="[!--keyboard--]">
              :关键字</td>
            <td><input name="textfield25" type="text" value="[!--class.name--]">
              :栏目名称</td>
            <td><input name="textfield36" type="text" value="[!--userid--]">
              :发布者ID</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield30" type="text" value="[!--bclass.id--]">
              :父栏目ID</td>
            <td><input name="textfield31" type="text" value="[!--bclass.name--]">
              :父栏目名称</td>
            <td><input name="textfield37" type="text" value="[!--username--]">
              :发布者</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield39" type="text" value="[!--userfen--]">
              :查看信息扣除点数</td>
            <td><input name="textfield42" type="text" value="[!--onclick--]">
              :点击数</td>
            <td><input name="textfield43" type="text" value="[!--totaldown--]">
              :下载数</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield44" type="text" value="[!--plnum--]">
              :评论数</td>
            <td><input name="textfield192" type="text" value="[!--ttid--]">
              :标题分类ID</td>
            <td><input name="textfield1922" type="text" value="[!--tt.name--]">
              :标题分类名称</td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td height="25"><input name="textfield19222" type="text" value="[!--tt.url--]">
            :标题分类地址</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><strong>[!--字段名--]:数据表字段内容调用，点 
              <input type="button" name="Submit3" value="这里" onclick="window.open('ShowVar.php?<?=$ecms_hashur['ehref']?>&modid='+document.form1.modid.value,'','width=300,height=520,scrollbars=yes');">
              可查看</strong></td>
            <td><strong>支持公共模板变量</strong></td>
            <td>&nbsp;</td>
          </tr>
        </table>
</div>
</body>
</html>
