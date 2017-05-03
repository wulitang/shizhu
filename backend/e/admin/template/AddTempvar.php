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
CheckLevel($logininid,$loginin,$classid,"tempvar");
$gid=(int)$_GET['gid'];
$gname=CheckTempGroup($gid);
$urlgname=$gname."&nbsp;>&nbsp;";
$enews=ehtmlspecialchars($_GET['enews']);
$cid=ehtmlspecialchars($_GET['cid']);
$r[myorder]=0;
$url=$urlgname."<a href=ListTempvar.php?gid=$gid".$ecms_hashur['ehref'].">管理模板变量</a>&nbsp;>&nbsp;增加模板变量";
//修改
if($enews=="EditTempvar")
{
	$varid=(int)$_GET['varid'];
	$r=$empire->fetch1("select myvar,varname,varvalue,classid,isclose,myorder from ".GetDoTemptb("enewstempvar",$gid)." where varid='$varid'");
	$r[varvalue]=ehtmlspecialchars(stripSlashes($r[varvalue]));
	$url=$urlgname."<a href=ListTempvar.php?gid=$gid".$ecms_hashur['ehref'].">管理模板变量</a>&nbsp;>&nbsp;修改模板变量：".$r[myvar];
}
//分类
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewstempvarclass order by classid");
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
<title>增加模板变量</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script>
function ReTempBak(){
	self.location.reload();
}

//查看模板标签语法
function ckmbbq(){
art.dialog.open('template/EnewsBq.php<?=$ecms_hashur[whehref]?>',
    {title: '查看模板标签语法',width: 800, height: 540,button: [{name: '关闭'}]
	});
}

//管理分类
function glfl(){
art.dialog.open('template/TempvarClass.php<?=$ecms_hashur[whehref]?>',
    {title: '管理分类',width: 800, height: 340});
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
<div class="jqui">
<form name="form1" method="post" action="ListTempvar.php">
 <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="varid" type="hidden" value="<?=$varid?>"> 
        <input name="cid" type="hidden" value="<?=$cid?>"> 
        <input name="gid" type="hidden" value="<?=$gid?>">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:140px;"><h3><span>增加模板变量</span></h3></th>
			<th></th>
		</tr>
      <tr bgcolor="#FFFFFF"> 
      <td height="25">变量名(*)</td>
      <td height="25" style="text-align:left;">[!--temp. 
        <input name="myvar" type="text" value="<?=$r[myvar]?>" size="16">
        --] <font color="#666666">(如：ecms，变量就是[!--temp.ecms--])</font></td>
      </tr>
         <tr bgcolor="#FFFFFF"> 
      <td height="25">所属类别</td>
      <td height="25" style="text-align:left;"><select name="classid">
          <option value="0">不隶属于任何类别</option>
          <?=$cstr?>
        </select> <input type="button" name="Submit6222322" value="管理分类" onClick="glfl()">
      </td>
    </tr>
        <tr bgcolor="#FFFFFF"> 
      <td height="25">变量标识(*)</td>
      <td height="25" style="text-align:left;"><input name="varname" type="text" value="<?=$r[varname]?>"> 
        <font color="#666666">(如：页面头部)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">是否开启变量</td>
      <td height="25" style="text-align:left;"><input type="radio" name="isclose" value="0"<?=$r[isclose]==0?' checked':''?>>
        是 
        <input type="radio" name="isclose" value="1"<?=$r[isclose]==1?' checked':''?>>
        否<font color="#666666">（开启才会在模板中生效）</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">变量排序</td>
      <td height="25" style="text-align:left;"><input name="myorder" type="text" value="<?=$r[myorder]?>" size="6"> 
        <font color="#666666">(值越大等级越高，可以嵌入更低等级的变量)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">标签说明</td>
      <td height="25" style="text-align:left;">[<a href="javascript:void(0)" onclick="ckmbbq()">查看模板标签语法</a>]
        &nbsp;&nbsp;[<a href="#ecms" onClick="window.open('../ListClass.php<?=$ecms_hashur[whehref]?>','','width=800,height=600,scrollbars=yes,resizable=yes');">查看JS调用地址</a>] 
        &nbsp;&nbsp;[<a href="javascript:void(0)" onclick="ckggmbbl()">查看公共模板变量</a>] 
        &nbsp;&nbsp;[<a href="javascript:void(0)" onclick="ckbqmb()">查看标签模板</a>]</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><strong>变量值</strong>(*)</td>
      <td height="25">请将变量内容<a href="#ecms" onclick="window.clipboardData.setData('Text',document.form1.varvalue.value);document.form1.varvalue.select()" title="点击复制模板内容"><strong>复制到Dreamweaver(推荐)</strong></a>或者使用<a href="#ecms" onclick="window.open('editor.php?getvar=opener.document.form1.varvalue.value&returnvar=opener.document.form1.varvalue.value&fun=ReturnHtml&notfullpage=1<?=$ecms_hashur['ehref']?>','edittemp','width=880,height=600,scrollbars=auto,resizable=yes');"><strong>模板在线编辑</strong></a>进行可视化编辑</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2"><div align="center">
          <textarea name="varvalue" cols="90" rows="27" wrap="OFF" id="temptext"><?=$r[varvalue]?></textarea>
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value="提交"> &nbsp;<input type="reset" name="Submit2" value="重置">
        <?php
		if($enews=='EditTempvar')
		{
		?>
        &nbsp;&nbsp;[<a href="#empirecms" onclick="window.open('TempBak.php?temptype=tempvar&tempid=<?=$varid?>&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','ViewTempBak','width=450,height=500,scrollbars=yes,left=300,top=150,resizable=yes');">修改记录</a>] 
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
</body>
</html>
