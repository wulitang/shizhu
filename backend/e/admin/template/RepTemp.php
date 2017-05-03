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
$enews=$_POST['enews'];
if($enews)
{
	hCheckEcmsRHash();
}
if($enews=="RepTemp")
{
	include("../../class/tempfun.php");
	DoRepTemp($_POST,$logininid,$loginin);
}
$gid=(int)$_GET['gid'];
$gname=CheckTempGroup($gid);
$urlgname=$gname."&nbsp;->&nbsp;";
$url=$urlgname."<a href=RepTemp.php?gid=$gid".$ecms_hashur['ehref'].">批量替换模板内容</a>";
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>批量替换模板内容</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<style>
.comm-table td{ padding:8px;}
.comm-table td table{ border-top:1px solid #EFEFEF; text-align:left;}
.comm-table td table td{ text-align:left;}
#temptext{word-wrap:break-word; width:auto; border:1px solid #999999; background:#fff; box-shadow:inset 2px 1px 6px #999;-webkit-box-shadow:inset 2px 1px 6px #999;-moz-box-shadow:inset 2px 1px 6px #999;-o-box-shadow:inset 2px 1px 6px #999;border-radius:5px 0 0 5px;-webkit-border-radius:5px 0 0 5px;-moz-border-radius:5px 0 0 5px;-o-border-radius:5px 0 0 5px;padding:8px;width:100%; box-sizing:border-box; overflow:auto;}

</style><script>
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
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
<div class="jqui">
<form name="form1" method="post" action="RepTemp.php" onSubmit="return confirm('确认要替换？');">
<?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="RepTemp">
          <input name="gid" type="hidden" id="gid" value="<?=$gid?>">
          <table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
		<th style="width:180px;"><h3><span style="padding-left: 5px;">批量替换模板内容</span></h3></th>
			<th></th>
		</tr>
 <tr> 
            <td height="23"><div align="center"> </div>
              <div align="center"><strong>原字符</strong></div></td>
            <td height="23"><textarea name="oldword" cols="78" rows="8" id="temptext"></textarea></td>
          </tr>
          <tr> 
            <td height="23"><div align="center"><strong>新字符</strong></div></td>
            <td height="23"><textarea name="newword" cols="78" rows="8" id="temptext"></textarea></td>
          </tr>
          <tr>
            <td height="23" colspan="2">
            <table width="98%" border="0" cellspacing="1" cellpadding="3">
            <tr> 
              <td width="16%" height="25"> <div align="left"> 
                  <input name="classtemp" type="checkbox" id="classtemp" value="1">
                  封面模板</div></td>
              <td width="16%"> <div align="left"> 
                  <input name="bqtemp" type="checkbox" id="bqtemp" value="1">
                  标签模板</div></td>
              <td width="16%"> <div align="left"> 
                  <input name="listtemp" type="checkbox" id="listtemp" value="1">
                  列表模板</div></td>
              <td width="16%"> <div align="left"> 
                  <input name="newstemp" type="checkbox" id="classtemp3" value="1">
                  内容模板</div></td>
              <td width="16%"> <div align="left"> 
                  <input name="searchtemp" type="checkbox" id="newstemp" value="1">
                  搜索模板</div></td>
            </tr>
            <tr> 
              <td height="25"> <div align="left"> 
                  <input name="pltemp" type="checkbox" id="pltemp3" value="1">
                  评论列表模板 </div></td>
              <td> <div align="left"> 
                  <input name="indextemp" type="checkbox" id="indextemp2" value="1">
                  首页模板</div></td>
              <td> <div align="left"> 
                  <input name="cptemp" type="checkbox" id="cptemp" value="1">
                  控制面板模板</div></td>
              <td> <div align="left"> 
                  <input name="sformtemp" type="checkbox" id="sformtemp" value="1">
                  高级搜索表单模板</div></td>
              <td> <div align="left"> 
                  <input name="printtemp" type="checkbox" id="searchtemp" value="1">
                  打印模板</div></td>
            </tr>
            <tr> 
              <td height="25"> <input name="userpage" type="checkbox" id="userpage" value="1">
                自定义页面</td>
              <td> <input name="tempvar" type="checkbox" id="tempvar" value="1">
                模板变量</td>
              <td><input name="gbooktemp" type="checkbox" id="gbooktemp" value="1">
                留言板模板</td>
              <td><input name="loginiframe" type="checkbox" id="loginiframe" value="1">
                登陆状态模板</td>
              <td><input name="votetemp" type="checkbox" id="votetemp" value="1">
                投票模板</td>
            </tr>
            <tr> 
              <td height="25"><input name="pagetemp" type="checkbox" id="pagetemp" value="1">
                自定义页面模板</td>
              <td> <input name="pljstemp" type="checkbox" id="pljstemp" value="1">
                评论JS模板</td>
              <td> <input name="schalltemp" type="checkbox" id="schalltemp" value="1">
                全站搜索模板</td>
              <td><input name="loginjstemp" type="checkbox" id="loginjstemp" value="1">
                JS调用登陆状态模板 </td>
              <td><input name="downpagetemp" type="checkbox" id="downpagetemp" value="1">
                最终下载页模板</td>
            </tr>
            <tr>
              <td height="25"><input name="jstemp" type="checkbox" id="feedbackbtemp" value="1">
                JS模板</td>
              <td><input name="otherlinktemp" type="checkbox" id="jstemp" value="1">
                相关信息模板</td>
              <td><input name="feedbackbtemp" type="checkbox" id="feedbackbtemp3" value="1">
                反馈表单模板</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
            </td>
            </tr>
            <tr bgcolor="#FFFFFF">
      <td colspan="2">
<div align="center">
          <input type="submit" name="Submit" value=" 替 换 ">&nbsp;&nbsp;
          <input type="reset" name="Submit2" value="重置">
          &nbsp;&nbsp;<input type=checkbox name=chkall value=on onclick=CheckAll(this.form)>
          选中全部</div></td>
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
