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
CheckLevel($logininid,$loginin,$classid,"f");
$tid=(int)$_GET['tid'];
$tbname=RepPostVar($_GET['tbname']);
if(empty($tid)||empty($tbname))
{
	printerror("ErrorUrl","history.go(-1)");
}
$enews=RepPostStr($_GET['enews'],1);
$fid=(int)$_GET['fid'];
$url="数据表:[".$dbtbpre."ecms_".$tbname."]&nbsp;>&nbsp;<a href=ListF.php?tid=$tid&tbname=$tbname".$ecms_hashur['ehref'].">字段管理</a>&nbsp;>&nbsp;修改系统字段";
$r=$empire->fetch1("select * from {$dbtbpre}enewsf where fid='$fid' and tid='$tid'");
if(!$r[fid])
{
	printerror("ErrorUrl","history.go(-1)");
}
$oftype="type".$r[ftype];
$$oftype=" selected";
$ofform="form".$r[fform];
$$ofform=" selected";
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>修改系统字段</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<style>
.comm-table td{ padding:6px 10px; line-height:22px;}
.comm-table td table{ border-top:1px solid #EFEFEF; border-right:1px solid #EFEFEF;}
</style>
<script>
function ShowFieldFormSet(obj,val){
	if(val=='text')
	{
		fsizediv.style.display="";
		flinkfielddiv.style.display="none";
	}
	else if(val=='img')
	{
		fsizediv.style.display="";
		flinkfielddiv.style.display="none";
	}
	else if(val=='linkfield')
	{
		fsizediv.style.display="";
		flinkfielddiv.style.display="";
	}
	else if(val=='linkfieldselect')
	{
		fsizediv.style.display="none";
		flinkfielddiv.style.display="";
	}
}
</script>
</head>

<body onLoad="ShowFieldFormSet(document.addfform,'<?=$r[fform]?$r[fform]:'text'?>')">
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>修改数据表( 
        <?=$dbtbpre?>
        ecms_ 
        <?=$tbname?>
        )的系统字段</span></h3>
<div class="jqui anniuqun" style="margin-bottom:30px;">
<form name="addfform" method="post" action="../ecmsmod.php">
<?=$ecms_hashur['form']?>
<input name="fid" type="hidden" id="fid" value="<?=$fid?>"> <input name="enews" type="hidden" id="enews" value="EditSysF"> 
        <input name="oldfform" type="hidden" id="oldfform" value="<?=$r[fform]?>"> 
        <input name="oldf" type="hidden" id="oldf" value="<?=$r[f]?>"> <input name="tbname" type="hidden" id="tbname" value="<?=$tbname?>"> 
        <input name="tid" type="hidden" id="tid" value="<?=$tid?>"> <input name="oldfvalue" type="hidden" id="oldfvalue" value="<?=ehtmlspecialchars(stripSlashes($r[fvalue]))?>"> 
        <input name="oldiskey" type="hidden" id="oldiskey" value="<?=$r[iskey]?>"> 
        <input name="oldsavetxt" type="hidden" id="oldsavetxt" value="<?=$r[savetxt]?>"> 
        <input name="oldisonly" type="hidden" id="oldisonly" value="<?=$r[isonly]?>"> 
        <input name="oldlinkfieldval" type="hidden" id="oldlinkfieldval" value="<?=$r[linkfieldval]?>"> 
        <input name="oldfformsize" type="hidden" id="oldfformsize" value="<?=$r[fformsize]?>"> 
<table class="comm-table" cellspacing="0">
	<tbody>
      <tr> 
      <th colspan="2">基本设置</th>
    </tr>
    <tr> 
      <td>字段名</td>
      <td style="text-align:left;"><b> 
        <?=$r[f]?>
        <input name="f" type="hidden" id="f" value="<?=$r[f]?>">
        </b></td>
    </tr>
    <tr> 
      <td>字段标识</td>
      <td style="text-align:left;"> <input name="fname" type="text" id="fname" value="<?=$r[fname]?>"></td>
    </tr>
	<?php
	if($r[f]=='title'||$r[f]=='titlepic')
	{
	?>
    <tr> 
      <td>字段类型</td>
      <td style="text-align:left;"> <select name="ftype" id="select">
          <option value="CHAR"<?=$typeCHAR?>>定长字符型0-255字节(CHAR)</option>
          <option value="VARCHAR"<?=$typeVARCHAR?>>字符型0-255字节(VARCHAR)</option>
        </select>
        长度 
        <input name="flen" type="text" id="flen" value="<?=$r[flen]?>" size="6"> 
      </td>
    </tr>
	<?php
	}
	else
	{
	?>
	<input type="hidden" name="ftype" value="<?=$r[ftype]?>">
	<input type="hidden" name="flen" value="<?=$r[flen]?>">
	<?php
	}
	?>
    <tr> 
      <th colspan="2">特殊属性</th>
    </tr>
	<?php
	if($r[f]!='special.field')
	{
	?>
	<?php
	if($r[f]!='newstime')
	{
	?>
    <tr> 
      <td>加索引</td>
      <td style="text-align:left;"> <input type="radio" name="iskey" value="1"<?=$r[iskey]==1?' checked':''?>>
        是 
        <input type="radio" name="iskey" value="0"<?=$r[iskey]==0?' checked':''?>>
        否</td>
    </tr>
	<?php
	}
	else
	{
	?>
	<input type="hidden" name="iskey" value="<?=$r[iskey]?>">
	<?php
	}
	?>
    <tr> 
      <td>值唯一</td>
      <td style="text-align:left;"> <input type="radio" name="isonly" value="1"<?=$r[isonly]==1?' checked':''?>>
        是 
        <input type="radio" name="isonly" value="0"<?=$r[isonly]==0?' checked':''?>>
        否</td>
    </tr>
    <tr> 
      <td>后台增加信息处理函数</td>
      <td style="text-align:left;"><input name="adddofun" type="text" id="adddofun" value="<?=$r[adddofun]?>">
        <font color="#666666">(一般不设置，格式"函数名##参数"参数可不设置)</font></td>
    </tr>
    <tr> 
      <td>后台修改信息处理函数</td>
      <td style="text-align:left;"><input name="editdofun" type="text" id="editdofun" value="<?=$r[editdofun]?>">
        <font color="#666666">(一般不设置，格式"函数名##参数"参数可不设置)</font></td>
    </tr>
    <tr> 
      <td>前台增加信息处理函数</td>
      <td style="text-align:left;"><input name="qadddofun" type="text" id="qadddofun" value="<?=$r[qadddofun]?>">
        <font color="#666666">(一般不设置，格式"函数名##参数"参数可不设置)</font></td>
    </tr>
    <tr> 
      <td>前台修改信息处理函数</td>
      <td style="text-align:left;"><input name="qeditdofun" type="text" id="qeditdofun" value="<?=$r[qeditdofun]?>">
        <font color="#666666">(一般不设置，格式"函数名##参数"参数可不设置)</font></td>
    </tr>
	<?php
	}	
	?>
    <tr> 
      <td>显示顺序</td>
      <td style="text-align:left;"> <input name="myorder" type="text" id="myorder" value="<?=$r[myorder]?>"> 
        <font color="#666666">(数字越小越前面)</font></td>
    </tr>
	<tr> 
      <th colspan="2">表单显示设置</th>
    </tr>
	<?php
	if($r[f]!='special.field')
	{
	?>
    <tr> 
      <td bgcolor="#FFFFFF">输入表单显示元素</td>
      <td style="text-align:left;"> <select name="fform" id="fform" onChange="ShowFieldFormSet(document.addfform,this.options[this.selectedIndex].value)">
          <option value="text"<?=$formtext?>>单行文本框(text)</option>
          <option value="img"<?=$formimg?>>图片(img)</option>
          <option value="linkfield"<?=$formlinkfield?>>选择外表关联字段(linkfield)</option>
          <option value="linkfieldselect"<?=$formlinkfieldselect?>>下拉外表关联字段(linkfieldselect)</option>
        </select> </td>
    </tr>
    <tr> 
      <td>选项</td>
      <td style="text-align:left;"><table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr id="fsizediv"> 
            <td height="23" style="text-align:left;"><strong>元素长度</strong><br> <input name="fformsize" type="text" id="fformsize2" value="<?=$r[fformsize]?>"> 
              <font color="#666666">(空为按默认)</font></td>
          </tr>
          <tr id="flinkfielddiv"> 
            <td height="23" style="text-align:left;"><strong>选择模型字段设置</strong><br>
              数据表名 
              <input name="linkfieldtb" type="text" id="linkfieldtb" value="<?=$r[linkfieldtb]?>"> 
              <br>
              值字段名 
              <input name="linkfieldval" type="text" id="linkfieldval" value="<?=$r[linkfieldval]?>"> 
              <input name="samedata" type="checkbox" id="samedata" value="1"<?=$r[samedata]==1?' checked':''?>>
              数据同步<br>
              显示字段 
              <input name="linkfieldshow" type="text" id="linkfieldshow" value="<?=$r[linkfieldshow]?>"> 
              <input name="oldlinkfieldtb" type="hidden" id="oldlinkfieldtb" value="<?=$r[linkfieldtb]?>"> 
              <input name="oldlinkfieldshow" type="hidden" id="oldlinkfieldshow" value="<?=$r[linkfieldshow]?>"></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td valign="top" bgcolor="#FFFFFF">初始值</td>
      <td> <textarea name="fvalue" cols="65" rows="8" id="fvalue" style="WIDTH: 100%"><?=ehtmlspecialchars(stripSlashes(str_replace("|","\r\n",$r[fvalue])))?></textarea></td>
    </tr>
	<?php
	}
	?>
    <tr> 
      <td valign="top" bgcolor="#FFFFFF">输入表单替换html代码<br> <font color="#666666">(增加字段时请留空)</font></td>
      <td> <textarea name="fhtml" cols="65" rows="10" id="fhtml" style="WIDTH: 100%"><?=ehtmlspecialchars(stripSlashes($r[fhtml]))?></textarea></td>
    </tr>
    <tr> 
      <td valign="top" bgcolor="#FFFFFF">投稿表单替换html代码<br> <font color="#666666">(增加字段时请留空)</font></td>
      <td> <textarea name="qfhtml" cols="65" rows="10" id="qfhtml" style="WIDTH: 100%"><?=ehtmlspecialchars(stripSlashes($r[qfhtml]))?></textarea></td>
    </tr>
    <tr> 
      <td valign="top" bgcolor="#FFFFFF">注释</td>
      <td> <textarea name="fzs" cols="65" rows="6" id="fzs" style="WIDTH: 100%"><?=stripSlashes($r[fzs])?></textarea></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td style="text-align:left;"> <input type="submit" name="Submit" value="提交"> 
        <input type="reset" name="Submit2" value="重置"></td>
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
