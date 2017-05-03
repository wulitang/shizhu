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
CheckLevel($logininid,$loginin,$classid,"pubvar");
$enews=ehtmlspecialchars($_GET['enews']);
$cid=(int)$_GET['cid'];
$r[myorder]=0;
$url="<a href=ListPubVar.php".$ecms_hashur['whehref'].">管理扩展变量</a>&nbsp;>&nbsp;增加扩展变量";
//修改
if($enews=="EditPubVar")
{
	$varid=(int)$_GET['varid'];
	$r=$empire->fetch1("select myvar,varname,varvalue,varsay,classid,tocache,myorder from {$dbtbpre}enewspubvar where varid='$varid'");
	$r[varvalue]=ehtmlspecialchars($r[varvalue]);
	$url="<a href=ListPubVar.php".$ecms_hashur['whehref'].">管理扩展变量</a>&nbsp;>&nbsp;修改扩展变量：".$r[myvar];
}
//分类
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewspubvarclass order by classid");
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
<title>管理扩展变量</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type="text/javascript">
$(function(){
			
		});
//管理扩展变量分类
function glkzblfl(){
art.dialog.open('pub/PubVarClass.php<?=$ecms_hashur['whehref']?>',
    {title: '管理扩展变量分类',lock: true,opacity: 0.5, width: 800, height: 540});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>增加扩展变量</span></h3>
            <div class="line"></div>
            <form name="form1" method="post" action="ListPubVar.php">
<?=$ecms_hashur['form']?>
            <input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="varid" type="hidden" value="<?=$varid?>"> 
        <input name="cid" type="hidden" value="<?=$cid?>">
        <input name="oldmyvar" type="hidden" id="oldmyvar" value="<?=$r[myvar]?>">
        <input name="oldtocache" type="hidden" id="oldtocache" value="<?=$r[tocache]?>">
			<ul style="padding-bottom:120px;">
            		<li class="jqui"><label>变量名(*)</label><input name="myvar" type="text" value="<?=$r[myvar]?>"><font color="#666666">(由英文与数字组成，且不能以数字开头。如：&quot;title&quot;)</font></li>
                    <li class="jqui"><label>所属分类</label><select name="classid">
          <option value="0">不隶属于任何分类</option>
          <?=$cstr?>
        </select><i><a href="javascript:void(0)" onclick="glkzblfl()">管理分类</a></i></li>
        			<li class="jqui"><label>变量标识(*)</label><input name="varname" type="text" value="<?=$r[varname]?>"> <font color="#666666">(如：标题)</font></li>
                    <li class="jqui"><label>变量说明</label><input name="varsay" type="text" id="varsay" value="<?=$r[varsay]?>" size="40"></li>
                    <li class="jqui"><label>是否写入缓存</label><input type="radio" name="tocache" value="1"<?=$r[tocache]==1?' checked':''?>>
        <i>写入缓存</i> 
        <input type="radio" name="tocache" value="0"<?=$r[tocache]==0?' checked':''?>>
        <i>不写入缓存</i><font color="#666666">(大内容不建议写入缓存,缓存调用变量：$public_r['add_变量名'])</font></li>
                    <li class="jqui"><label>变量排序</label><input name="myorder" type="text" value="<?=$r[myorder]?>">
        <font color="#666666">(值越小显示越前面)</font></li>
                    <li style="position:relative;"><label>变量值</label><textarea name="varvalue" cols="60" rows="8" wrap="OFF" class="textarea"><?=$r[varvalue]?></textarea><div class="tips ipdown"><span class="tip-arrow tip-arrow-top"><em>◆</em><b>◆</b></span>
请将变量内容<a href="#ecms" onClick="window.clipboardData.setData('Text',document.form1.varvalue.value);document.form1.varvalue.select()" title="点击复制模板内容"><strong>复制到Dreamweaver(推荐)</strong></a>或者使用<a href="#ecms" onClick="window.open('../template/editor.php?<?=$ecms_hashur['ehref']?>&getvar=opener.document.form1.varvalue.value&returnvar=opener.document.form1.varvalue.value&fun=ReturnHtml&notfullpage=1','edittemp','width=880,height=600,scrollbars=auto,resizable=yes');"><strong>模板在线编辑</strong></a>进行可视化编辑</div></li>
                    <li></li>
            </ul>
            <div class="sub jqui"><input type="submit" name="Submit" value="提交"> &nbsp; <input type="reset" name="Submit2" value="重置"></div>
            </form>
        </div>
    </div>
</div>
</div>
</body>
</html>
