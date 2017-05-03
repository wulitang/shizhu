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
$url=$urlgname."<a href=ListVotetemp.php?gid=$gid".$ecms_hashur['ehref'].">管理投票模板</a>&nbsp;>&nbsp;增加投票模板";
//复制
if($enews=="AddVoteTemp"&&$_GET['docopy'])
{
	$tempid=(int)$_GET['tempid'];
	$r=$empire->fetch1("select tempid,tempname,temptext from ".GetDoTemptb("enewsvotetemp",$gid)." where tempid=$tempid");
	$url=$urlgname."<a href=ListVotetemp.php?gid=$gid".$ecms_hashur['ehref'].">管理投票模板</a>&nbsp;>&nbsp;复制投票模板：<b>".$r[tempname]."</b>";
}
//修改
if($enews=="EditVoteTemp")
{
	$tempid=(int)$_GET['tempid'];
	$r=$empire->fetch1("select tempid,tempname,temptext from ".GetDoTemptb("enewsvotetemp",$gid)." where tempid=$tempid");
	$url=$urlgname."<a href=ListVotetemp.php?gid=$gid".$ecms_hashur['ehref'].">管理投票模板</a>&nbsp;>&nbsp;修改投票模板：<b>".$r[tempname]."</b>";
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>增加投票模板</title>
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
</script>
<style>
.comm-table td {}
.comm-table td p{ padding:5px;}
.comm-table td table{ border-right:1px solid #EFEFEF; border-top:1px solid #EFEFEF; margin-top:10px;}
#temptext,#listvar{word-wrap:break-word; width:auto; border:1px solid #999999; background:#fff; box-shadow:inset 2px 1px 6px #999;-webkit-box-shadow:inset 2px 1px 6px #999;-moz-box-shadow:inset 2px 1px 6px #999;-o-box-shadow:inset 2px 1px 6px #999;border-radius:5px 0 0 5px;-webkit-border-radius:5px 0 0 5px;-moz-border-radius:5px 0 0 5px;-o-border-radius:5px 0 0 5px;padding:8px;width:100%; box-sizing:border-box; overflow:auto;}
.comm-table table td{ text-align:left; line-height:25px; padding:6px;}
.comm-table2 td{ padding:5px 10px; background:#fff; text-align:center;}
.comm-table2 th{ text-align:center;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
<div class="jqui">
 <form name="form1" method="post" action="ListVotetemp.php">
  <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="tempid" type="hidden" id="tempid" value="<?=$tempid?>"> 
        <input name="gid" type="hidden" id="gid" value="<?=$gid?>">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:160px;"><h3><span>增加投票模板</span></h3></th>
			<th></th>
		</tr>
     <tr bgcolor="#FFFFFF"> 
      <td height="25">模板名称</td>
      <td height="25" style="text-align:left;"> <input name="tempname" type="text" id="tempname" value="<?=$r[tempname]?>"> 
      </td>
    </tr>
     <tr bgcolor="#FFFFFF">
       <td height="25">标签说明</td>
      <td height="25" style="text-align:left;">[<a href="javascript:void(0)" onclick="ckbl()">显示模板变量说明</a>]</td>
     </tr>
     <tr bgcolor="#FFFFFF"> 
      <td height="25"><strong>模板内容</strong>(*)</td>
      <td height="25">请将模板内容<a href="#ecms" onclick="window.clipboardData.setData('Text',document.form1.temptext.value);document.form1.temptext.select()" title="点击复制模板内容"><strong>复制到Dreamweaver(推荐)</strong></a>或者使用<a href="#ecms" onclick="window.open('editor.php?getvar=opener.document.form1.temptext.value&returnvar=opener.document.form1.temptext.value&fun=ReturnHtml&notfullpage=1<?=$ecms_hashur['ehref']?>','edittemp','width=880,height=600,scrollbars=auto,resizable=yes');"><strong>模板在线编辑</strong></a>进行可视化编辑</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2"><div align="center"> 
          <textarea name="temptext" cols="90" rows="23" id="temptext" wrap="OFF" style="WIDTH: 100%"><?=ehtmlspecialchars(stripSlashes($r[temptext]))?></textarea>
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2"><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置">
        <?php
		if($enews=='EditVoteTemp')
		{
		?>
        &nbsp;&nbsp;[<a href="#empirecms" onclick="window.open('TempBak.php?temptype=votetemp&tempid=<?=$tempid?>&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','ViewTempBak','width=450,height=500,scrollbars=yes,left=300,top=150,resizable=yes');">修改记录</a>] 
        <?php
		}
		?>      </td>
      </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25" colspan="2">模板格式: 列表头[!--empirenews.listtemp--]列表内容[!--empirenews.listtemp--]列表尾</td>
    </tr>
	</tbody>
</table>
        </form>
</div>
        </div>
    </div>
</div>
</div>
<div id="mbbl" style="display:none;">
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#DBEAF5" class="comm-table2">
		  <tr>
          	<th style="border-left:1px solid #CDCDCD;" colspan="2">投票插件使用时支持的模板变量列表</th>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td height="25"><div align="center">[!--news.url--]</div></td>
            <td>网站地址</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td width="36%" height="25"> <div align="center">[!--vote.action--]</div></td>
            <td width="64%">投票表单提交地址</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"> <div align="center">[!--title--]</div></td>
            <td>显示投票的标题</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"> <div align="center">[!--vote.view--]</div></td>
            <td>查看投票结果地址</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"> <div align="center">[!--width--](宽度)、[!--height--](高度)</div></td>
            <td>弹出投票结果窗口大小</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"> <div align="center">[!--voteid--]</div></td>
            <td>此投票的ID</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"> <div align="center">[!--vote.box--]</div></td>
            <td>投票选项（单选框 
              <input type="radio" name="radiobutton" value="radiobutton">
              与复选框 
              <input type="checkbox" name="checkbox" value="checkbox">
              ）</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"> <div align="center">[!--vote.name--]</div></td>
            <td>投票选项名称</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><div align="center">投票事件变量</div></td>
            <td>&lt;input type=&quot;hidden&quot; name=&quot;<strong>enews</strong>&quot; 
              value=&quot;<strong>AddVote</strong>&quot;&gt;</td>
          </tr>
        </table>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#DBEAF5" class="comm-table2">
		  <tr>
          	<th style="border-left:1px solid #CDCDCD;" colspan="2">信息投票使用时支持的模板变量列表</th>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td height="25"><div align="center">[!--news.url--]</div></td>
            <td>网站地址</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td width="36%" height="25"> <div align="center">投票表单提交地址</div></td>
            <td width="64%">/e/enews/index.php</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><div align="center">查看投票结果地址</div></td>
            <td>/e/public/vote/?classid=[!--classid--]&amp;id=[!--id--]</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"> <div align="center">[!--title--]</div></td>
            <td>显示投票的标题</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"> <div align="center">[!--width--](宽度)、[!--height--](高度)</div></td>
            <td>弹出投票结果窗口大小</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"> <div align="center">[!--id--]</div></td>
            <td>信息ID</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><div align="center">[!--classid--]</div></td>
            <td>栏目ID</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"> <div align="center">[!--vote.box--]</div></td>
            <td>投票选项（单选框 
              <input type="radio" name="radiobutton" value="radiobutton">
              与复选框 
              <input type="checkbox" name="checkbox2" value="checkbox">
              ）</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"> <div align="center">[!--vote.name--]</div></td>
            <td>投票选项名称</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><div align="center">投票事件变量</div></td>
            <td>&lt;input type=&quot;hidden&quot; name=&quot;<strong>enews</strong>&quot; 
              value=&quot;<strong>AddInfoVote</strong>&quot;&gt;</td>
          </tr>
        </table>
</div>
<div class="line"></div>
</body>
</html>
