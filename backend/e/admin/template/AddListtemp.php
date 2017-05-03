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
$mid=ehtmlspecialchars($_GET['mid']);
$cid=ehtmlspecialchars($_GET['cid']);
$enews=ehtmlspecialchars($_GET['enews']);
$r[subnews]=0;
$r[rownum]=1;
$r[subtitle]=0;
$r[showdate]="Y-m-d H:i:s";
$url=$urlgname."<a href=ListListtemp.php?gid=$gid".$ecms_hashur['ehref'].">管理列表模板</a>&nbsp;>&nbsp;增加列表模板";
$autorownum=" checked";
//复制
if($enews=="AddListtemp"&&$_GET['docopy'])
{
	$tempid=(int)$_GET['tempid'];
	$r=$empire->fetch1("select tempname,temptext,subnews,listvar,rownum,modid,showdate,subtitle,classid,docode from ".GetDoTemptb("enewslisttemp",$gid)." where tempid='$tempid'");
	$url=$urlgname."<a href=ListListtemp.php?gid=$gid".$ecms_hashur['ehref'].">管理列表模板</a>&nbsp;>&nbsp;复制列表模板：".$r[tempname];
}
//修改
if($enews=="EditListtemp")
{
	$tempid=(int)$_GET['tempid'];
	$r=$empire->fetch1("select tempname,temptext,subnews,listvar,rownum,modid,showdate,subtitle,classid,docode from ".GetDoTemptb("enewslisttemp",$gid)." where tempid='$tempid'");
	$url=$urlgname."<a href=ListListtemp.php?gid=$gid".$ecms_hashur['ehref'].">管理列表模板</a>&nbsp;>&nbsp;修改列表模板：".$r[tempname];
}
//系统模型
$msql=$empire->query("select mid,mname from {$dbtbpre}enewsmod where usemod=0 order by myorder,mid");
while($mr=$empire->fetch($msql))
{
	if($mr[mid]==$r[modid])
	{$select=" selected";}
	else
	{$select="";}
	$mod.="<option value=".$mr[mid].$select.">".$mr[mname]."</option>";
}
//分类
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewslisttempclass order by classid");
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
<title>管理列表模板</title>
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
//查看内容字段
function cknrzd(){
art.dialog.open('template/ShowVar.php?<?=$ecms_hashur[ehref]?>&modid='+document.form1.modid.value,
    {title: '查看内容字段',width: 600, height: 540
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
//管理系统模型
function glxtmx(){
art.dialog.open('db/ListTable.php<?=$ecms_hashur[whehref]?>',
    {title: '管理系统模型',width: 800, height: 540});
}
//管理分类
function glfl(){
art.dialog.open('template/ListtempClass.php<?=$ecms_hashur[whehref]?>',
    {title: '管理分类',width: 800, height: 340});
}
</script>
<style>
.comm-table td {}
.comm-table td p{ padding:5px;}
.comm-table td table{ border-right:1px solid #EFEFEF; border-top:1px solid #EFEFEF; margin-top:10px;}
#temptext,#listvar{word-wrap:break-word; width:auto; border:1px solid #999999; background:#fff; box-shadow:inset 2px 1px 6px #999;-webkit-box-shadow:inset 2px 1px 6px #999;-moz-box-shadow:inset 2px 1px 6px #999;-o-box-shadow:inset 2px 1px 6px #999;border-radius:5px 0 0 5px;-webkit-border-radius:5px 0 0 5px;-moz-border-radius:5px 0 0 5px;-o-border-radius:5px 0 0 5px;padding:8px;width:100%; box-sizing:border-box; overflow:auto;}
.comm-table table td{ text-align:left; line-height:25px; padding:6px;}
.comm-table2 td{ padding:5px 10px; background:#fff; text-align:center;}
#mbbl th{ text-align:center;}
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
<form name="form1" method="post" action="ListListtemp.php">
 <?=$ecms_hashur['form']?>
<input type=hidden name=enews value="<?=$enews?>"> <input name="tempid" type="hidden" id="tempid" value="<?=$tempid?>"> 
        <input name="cid" type="hidden" id="cid" value="<?=$cid?>"> <input name="mid" type="hidden" id="mid" value="<?=$mid?>"> 
        <input name="gid" type="hidden" id="gid" value="<?=$gid?>">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:140px;"><h3><span>增加列表模板</span></h3></th>
			<th></th>
		</tr>
      <tr bgcolor="#FFFFFF"> 
      <td height="25">模板名称(*)</td>
      <td height="25" style="text-align:left;"><input name="tempname" type="text" id="tempname" value="<?=$r[tempname]?>" size="36"></td>
      </tr>
         <tr bgcolor="#FFFFFF"> 
      <td height="25">所属系统模型(*)</td>
      <td height="25" style="text-align:left;"><select name="modid" id="modid">
          <?=$mod?>
        </select> <input type="button" name="Submit6" value="管理系统模型" onClick="glxtmx()"> 
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">所属分类</td>
      <td height="25" style="text-align:left;"><select name="classid" id="classid">
          <option value="0">不隶属于任何分类</option>
          <?=$cstr?>
        </select> <input type="button" name="Submit6222322" value="管理分类" onClick="glfl()"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">简介截取字数</td>
      <td height="25" style="text-align:left;"><input name="subnews" type="text" id="subnews" value="<?=$r[subnews]?>" size="6">
        个字节<font color="#666666">(0为不截取)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">标题截取字数</td>
      <td height="25" style="text-align:left;"><input name="subtitle" type="text" id="subtitle" value="<?=$r[subtitle]?>" size="6">
        个字节<font color="#666666">(0为不截取)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">每次显示</td>
      <td height="25" style="text-align:left;"><input name="rownum" type="text" id="rownum" value="<?=$r[rownum]?>" size="6">
        条记录<font color="#666666">( 
        <input name="autorownum" type="checkbox" id="autorownum" value="1"<?=$autorownum?>>
        自动识别)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">时间显示格式</td>
      <td style="text-align:left;"> <input name="showdate" type="text" id="showdate" value="<?=$r[showdate]?>" size="20"> 
        <select name="select4" onChange="document.form1.showdate.value=this.value">
          <option value="Y-m-d H:i:s">选择</option>
          <option value="Y-m-d H:i:s">2005-01-27 11:04:27</option>
          <option value="Y-m-d">2005-01-27</option>
          <option value="m-d">01-27</option>
        </select> </td>
    </tr>
    	<tr bgcolor="#FFFFFF"> 
       <td>查看说明</td>
      <td height="25" style="text-align:left;">[<a href="javascript:void(0)" onclick="ckbl()">显示模板变量说明</a>] 
        &nbsp;[<a href="javascript:void(0)" onclick="cknrzd()">查看内容字段</a>] &nbsp;[<a href="javascript:void(0)" onclick="ckmbbq()">查看模板标签语法</a>]
        &nbsp;&nbsp;[<a href="#ecms" onclick="window.open('../ListClass.php<?=$ecms_hashur['whehref']?>','','width=1050,height=600,scrollbars=yes,resizable=yes');">查看JS调用地址</a>]  
        &nbsp;&nbsp;[<a href="javascript:void(0)" onclick="ckggmbbl()">查看公共模板变量</a>] 
        &nbsp;&nbsp;[<a href="javascript:void(0)" onclick="ckbqmb()">查看标签模板</a>]</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><strong>页面模板内容</strong>(*)</td>
      <td style="text-align:left;">请将模板内容<a href="#ecms" onClick="window.clipboardData.setData('Text',document.form1.temptext.value);document.form1.temptext.select()" title="点击复制模板内容"><strong>复制到Dreamweaver(推荐)</strong></a>或者使用<a href="#ecms" onClick="window.open('editor.php?getvar=opener.document.form1.temptext.value&returnvar=opener.document.form1.temptext.value&fun=ReturnHtml','edittemp','width=880,height=600,scrollbars=auto,resizable=yes');"><strong>模板在线编辑</strong></a>进行可视化编辑</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2" valign="top"><p> 
          <textarea name="temptext" cols="90" rows="27" wrap="OFF" id="temptext"><?=ehtmlspecialchars(stripSlashes($r[temptext]))?></textarea>
        </p></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><strong>列表内容模板(list.var) </strong>(*)</td>
      <td><div align="right" class="right">
          <input name="docode" type="checkbox" id="docode" value="1"<?=$r[docode]==1?' checked':''?>>
          <a title="list.var使用程序代码">使用程序代码</a></div>
          请将模板内容<a href="#ecms" onclick="window.clipboardData.setData('Text',document.form1.listvar.value);document.form1.listvar.select()" title="点击复制模板内容"><strong>复制到Dreamweaver(推荐)</strong></a>或者使用<a href="#ecms" onclick="window.open('editor.php?getvar=opener.document.form1.listvar.value&returnvar=opener.document.form1.listvar.value&fun=ReturnHtml&notfullpage=1<?=$ecms_hashur['ehref']?>','edittemp','width=880,height=600,scrollbars=auto,resizable=yes');"><strong>模板在线编辑</strong></a>进行可视化编辑</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="2" valign="top"> <div align="center"> 
          <textarea name="listvar" cols="90" rows="12" id="listvar" wrap="OFF"><?=ehtmlspecialchars(stripSlashes($r[listvar]))?></textarea>
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2"><input type="submit" name="Submit" value="保存模板">
        &nbsp; <input type="reset" name="Submit2" value="重置">
        <?php
		if($enews=='EditListtemp')
		{
		?>
        &nbsp;&nbsp;[<a href="#empirecms" onClick="window.open('TempBak.php?temptype=listtemp&tempid=<?=$tempid?>&gid=<?=$gid?>','ViewTempBak','width=450,height=500,scrollbars=yes,left=300,top=150,resizable=yes');">修改记录</a>] 
        <?php
		}
		?>      </td>
      </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">模板格式说明</td>
      <td height="25" style="text-align:left;"><p> <strong>页面模板内容：</strong>列表头[!--empirenews.listtemp--]列表内容[!--empirenews.listtemp--]列表尾<br>
          页面模板格式举列：&lt;table&gt;[!--empirenews.listtemp--]&lt;tr&gt;&lt;td&gt;&lt;!--list.var1--&gt;&lt;/td&gt;&lt;td&gt;&lt;!--list.var2--&gt;&lt;/td&gt;&lt;/tr&gt;[!--empirenews.listtemp--]&lt;/table&gt;<font color="#FF0000">(每次显示2条记录)</font><br>
          <strong>列表内容模板：</strong>即"页面模板内容"中"&lt;!--list.var*--&gt;"标签显示的内容．</p></td>
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
          	<th style="border-left:1px solid #CDCDCD;" colspan="3">页面模板内容支持的变量</th>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td width="33%" height="25"> <input name="textfield" type="text" value="[!--pagetitle--]">
              :页面标题</td>
            <td width="34%"><input name="textfield72" type="text" value="[!--pagekey--]">
              :页面关键字 </td>
            <td width="33%"><input name="textfield73" type="text" value="[!--pagedes--]">
              :页面描述 </td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td height="25"><input name="textfield2" type="text" value="[!--newsnav--]">
              :导航条</td>
            <td><input name="textfield92" type="text" value="[!--class.menu--]">
              :一级栏目导航</td>
            <td><input name="textfield132" type="text" value="[!--class.name--]">
              :栏目名</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield4" type="text" value="[!--self.classid--]">
              :本栏目/专题ID</td>
            <td><input name="textfield5" type="text" value="[!--bclass.id--]">
              :父栏目ID</td>
            <td><input name="textfield6" type="text" value="[!--bclass.name--]">
              :父栏目名称</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield7" type="text" value="[!--class.intro--]">
              :栏目/专题简介</td>
            <td><input name="textfield8" type="text" value="[!--class.keywords--]">
              :栏目/专题关键字</td>
            <td><input name="textfield9" type="text" value="[!--class.classimg--]">
              :栏目/专题缩略图</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield10" type="text" value="[!--show.page--]">
              :分页导航(下拉式)<br></td>
            <td><input name="textfield11" type="text" value="[!--show.listpage--]">
              :分页导航(列表式)</td>
            <td><input name="textfield12" type="text" value="[!--list.pageno--]">
              :当前分页号</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield13" type="text" value="[!--hotnews--]">
              :热门信息JS调用(默认表)<br> <input name="textfield14" type="text" value="[!--self.hotnews--]">
              :本栏目热门信息JS调用</td>
            <td><input name="textfield15" type="text" value="[!--newnews--]">
              :最新信息JS调用(默认表)<br> <input name="textfield16" type="text" value="[!--self.newnews--]">
              :本栏目最新信息JS调用</td>
            <td><input name="textfield17" type="text" value="[!--goodnews--]">
              :推荐信息JS调用(默认表)<br> <input name="textfield18" type="text" value="[!--self.goodnews--]">
              :本栏目推荐信息JS调用</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield19" type="text" value="[!--hotplnews--]">
              :评论热门信息JS调用(默认表)<br> <input name="textfield20" type="text" value="[!--self.hotplnews--]">
              :本栏目评论热门信息JS调用</td>
            <td><input name="textfield21" type="text" value="[!--firstnews--]">
              :头条信息JS调用(默认表)<br> <input name="textfield22" type="text" value="[!--self.firstnews--]">
              :本栏目头条信息JS调用</td>
            <td><input name="textfield3" type="text" value="[!--page.stats--]" />
:统计访问</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25" colspan="3"><strong> 内容变量用： &lt;!--list.var编号--&gt; (如：&lt;!--list.var1--&gt;,&lt;!--list.var2--&gt;) 代替</strong></td>
          </tr>
        </table>
        <table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#DBEAF5" class="comm-table2">
<tr>
          	<th style="border-left:1px solid #CDCDCD;" colspan="3">列表内容模板(list.var)支持的变量</th>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td width="33%" height="25"> <input name="textfield23" type="text" value="[!--id--]">
              :信息ID</td>
            <td width="34%"> <input name="textfield24" type="text" value="[!--titleurl--]">
              :标题链接</td>
            <td width="33%"> <input name="textfield25" type="text" value="[!--oldtitle--]">
              :标题ALT(不截取字符)</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield26" type="text" value="[!--classid--]">
              :栏目ID</td>
            <td><input name="textfield27" type="text" value="[!--class.name--]">
              :栏目名称(带链接)</td>
            <td><input name="textfield28" type="text" value="[!--this.classname--]">
              :栏目名称(不带链接)</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield29" type="text" value="[!--this.classlink--]">
              :栏目地址</td>
            <td><input name="textfield30" type="text" value="[!--news.url--]">
              :网站地址</td>
            <td><input name="textfield31" type="text" value="[!--no.num--]">
              :信息编号</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield32" type="text" value="[!--userid--]">
              :发布者ID</td>
            <td><input name="textfield33" type="text" value="[!--username--]">
              :发布者</td>
            <td><input name="textfield34" type="text" value="[!--userfen--]">
              :查看信息扣除点数</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield35" type="text" value="[!--onclick--]">
              :点击数</td>
            <td><input name="textfield36" type="text" value="[!--totaldown--]">
              :下载数</td>
            <td><input name="textfield37" type="text" value="[!--plnum--]">
              :评论数</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield192" type="text" value="[!--ttid--]">
              :标题分类ID</td>
            <td><input name="textfield1922" type="text" value="[!--tt.name--]">
              :标题分类名称</td>
            <td><input name="textfield19222" type="text" value="[!--tt.url--]">
              :标题分类地址</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><strong>[!--字段名--]:数据表字段内容调用，点 
              <input type="button" name="Submit3" value="这里" onclick="window.open('ShowVar.php?<?=$ecms_hashur['ehref']?>&modid='+document.form1.modid.value,'','width=300,height=520,scrollbars=yes');">
              可查看</strong></td>
            <td>&nbsp;</td>
			<td>&nbsp;</td>
          </tr>
        </table>
</div>
</body>
</html>
