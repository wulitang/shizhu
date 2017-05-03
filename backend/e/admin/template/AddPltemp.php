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
$url=$urlgname."<a href=ListPltemp.php?gid=$gid".$ecms_hashur['ehref'].">管理评论模板</a>&nbsp;>&nbsp;增加评论模板";
//复制
if($enews=="AddPlTemp"&&$_GET['docopy'])
{
	$tempid=(int)$_GET['tempid'];
	$r=$empire->fetch1("select tempid,tempname,temptext from ".GetDoTemptb("enewspltemp",$gid)." where tempid=$tempid");
	$url=$urlgname."<a href=ListPltemp.php?gid=$gid".$ecms_hashur['ehref'].">管理评论模板</a>&nbsp;>&nbsp;复制评论模板：<b>".$r[tempname]."</b>";
}
//修改
if($enews=="EditPlTemp")
{
	$tempid=(int)$_GET['tempid'];
	$r=$empire->fetch1("select tempid,tempname,temptext from ".GetDoTemptb("enewspltemp",$gid)." where tempid=$tempid");
	$url=$urlgname."<a href=ListPltemp.php?gid=$gid".$ecms_hashur['ehref'].">管理评论模板</a>&nbsp;>&nbsp;修改评论模板：<b>".$r[tempname]."</b>";
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>增加评论模板</title>
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
<form name="form1" method="post" action="ListPltemp.php">
<?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="tempid" type="hidden" id="tempid" value="<?=$tempid?>"> 
        <input name="gid" type="hidden" id="gid" value="<?=$gid?>">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:140px;"><h3><span>增加评论模板</span></h3></th>
			<th></th>
		</tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">模板名称</td>
      <td height="25" style="text-align:left;"> <input name="tempname" type="text" id="tempname" value="<?=$r[tempname]?>" size="40"> 
      </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">标签说明</td>
      <td height="25" style="text-align:left;">[<a href="javascript:void(0)" onclick="ckbl()">显示模板变量说明</a>] 
        &nbsp;&nbsp;[<a href="#ecms" onclick="window.open('../ListClass.php<?=$ecms_hashur['whehref']?>','','width=1050,height=600,scrollbars=yes,resizable=yes');">查看JS调用地址</a>]  
        &nbsp;&nbsp;[<a href="javascript:void(0)" onclick="ckggmbbl()">查看公共模板变量</a>]</td>
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
      <td height="25" colspan="2"><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置">
        <?php
		if($enews=='EditPlTemp')
		{
		?>
        &nbsp;&nbsp;[<a href="#empirecms" onClick="window.open('TempBak.php?temptype=pltemp&tempid=<?=$tempid?>&gid=<?=$gid?>','ViewTempBak','width=450,height=500,scrollbars=yes,left=300,top=150,resizable=yes');">修改记录</a>] 
        <?php
		}
		?>      </td>
      </tr>
    <tr bgcolor="#FFFFFF">
      <td height="35" colspan="2">模板格式 列表头[!--empirenews.listtemp--]列表内容[!--empirenews.listtemp--]列表尾</td>
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
          	<th style="border-left:1px solid #CDCDCD;" colspan="3">整体页面支持的变量</th>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td width="33%" height="25"> <input name="textfield3" type="text" value="[!--newsnav--]">
              :所在位置导航条</td>
            <td width="34%"><input name="textfield72" type="text" value="[!--pagekey--]">
              :页面关键字 </td>
            <td width="33%"><input name="textfield73" type="text" value="[!--pagedes--]">
              :页面描述 </td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td height="25"><input name="textfield92" type="text" value="[!--class.menu--]">
              :一级栏目导航</td>
            <td><input name="textfield4" type="text" value="[!--titleurl--]">
              :信息链接</td>
            <td><input name="textfield5" type="text" value="[!--title--]">
              :信息标题</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield6" type="text" value="[!--classid--]">
              :栏目ID</td>
            <td><input name="textfield7" type="text" value="[!--id--]">
              :信息ID</td>
            <td><input name="textfield8" type="text" value="[!--pinfopfen--]">
              :信息平均评分</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield9" type="text" value="[!--infopfennum--]">
              :总评分人数</td>
            <td><input name="textfield10" type="text" value="[!--news.url--]">
              :网站地址</td>
            <td><input name="textfield11" type="text" value="[!--key.url--]">
              :发表评论验证码地址</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield12" type="text" value="[!--lusername--]">
              :登陆会员帐号</td>
            <td><input name="textfield13" type="text" value="[!--lpassword--]">
              :登陆用户密码(加密过)</td>
            <td><input name="textfield14" type="text" value="[!--listpage--]">
              :分页导航</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield15" type="text" value="[!--plnum--]">
              :总记录数</td>
            <td><input name="textfield16" type="text" value="[!--hotnews--]">
              :热门信息JS调用(默认表)</td>
            <td><input name="textfield17" type="text" value="[!--newnews--]">
              :最新信息JS调用(默认表)</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield18" type="text" value="[!--goodnews--]">
              :推荐信息JS调用(默认表)</td>
            <td><input name="textfield19" type="text" value="[!--hotplnews--]">
              :评论热门信息JS调用(默认表)</td>
            <td><input name="textfield182" type="text" value="&lt;script src=&quot;[!--news.url--]d/js/js/plface.js&quot;&gt;&lt;/script&gt;">
             :评论表情选择调用</td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td height="25"><strong>支持公共模板变量</strong></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        <table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#DBEAF5" class="comm-table2">
<tr>
          	<th style="border-left:1px solid #CDCDCD;" colspan="3">列表内容支持的变量</th>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td width="33%" height="25"> <input name="textfield20" type="text" value="[!--plid--]">
              :评论ID</td>
            <td width="34%"> <input name="textfield21" type="text" value="[!--pltext--]">
              :评论内容</td>
            <td width="33%"> <input name="textfield22" type="text" value="[!--pltime--]">
              :评论发表时间</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield23" type="text" value="[!--plip--]">
              :评论发表者IP</td>
            <td><input name="textfield24" type="text" value="[!--username--]">
              :发表者</td>
            <td><input name="textfield252" type="text" value="[!--userid--]">
              :发表者ID</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield26" type="text" value="[!--zcnum--]">
              :支持数</td>
            <td><input name="textfield27" type="text" value="[!--fdnum--]">
              :反对数</td>
            <td><input name="textfield28" type="text" value="[!--classid--]">
              :栏目ID</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><input name="textfield29" type="text" value="[!--id--]">
              :信息ID</td>
            <td><input name="textfield25" type="text" value="[!--includelink--]">
              :引用评论链接地址</td>
            <td><strong>[!--字段名--]:自定义字段内容调用</strong></td>
          </tr>
        </table>
</div>
</body>
</html>
