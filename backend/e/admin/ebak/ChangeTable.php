<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require("class/functions.php");
$link=db_connect();
$empire=new mysqlquery();
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
CheckLevel($logininid,$loginin,$classid,"dbdata");
$bakpath=$public_r['bakdbpath'];
$mydbname=RepPostVar($_GET['mydbname']);
if(empty($mydbname))
{
	printerror("NotChangeBakTable","history.go(-1)");
}
//选择数据库
$udb=$empire->usequery("use `".$mydbname."`");
//查询
$and="";
$keyboard=RepPostVar($_GET['keyboard']);
$sear=RepPostStr($_GET['sear'],1);
if(empty($sear))
{
	$keyboard=$dbtbpre;
}
if($keyboard)
{
	$and=" LIKE '%$keyboard%'";
}
$sql=$empire->query("SHOW TABLE STATUS".$and);
//存放目录
$mypath=$mydbname."_".date("YmdHis");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>选择数据表</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/ui.tab.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type="text/javascript" src="/js/yecha.js"></script>
<script type="text/javascript">
$(function(){
			
		});
</script>
<script language="JavaScript">
function CheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if(e.name=='bakstru'||e.name=='bakstrufour'||e.name=='beover'||e.name=='autoauf'||e.name=='baktype'||e.name=='bakdatatype')
		{
		continue;
	    }
	if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
  }
function reverseCheckAll(form)
{
  for (var i=0;i<form.elements.length;i++)
  {
    var e = form.elements[i];
    if(e.name=='bakstru'||e.name=='bakstrufour'||e.name=='beover'||e.name=='autoauf'||e.name=='baktype'||e.name=='bakdatatype')
	{
		continue;
	}
	if (e.name != 'chkall')
	{
	   if(e.checked==true)
	   {
       		e.checked = false;
	   }
	   else
	   {
	  		e.checked = true;
	   }
	}
  }
}
function SelectCheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if(e.name=='bakstru'||e.name=='bakstrufour'||e.name=='beover'||e.name=='autoauf'||e.name=='baktype'||e.name=='bakdatatype')
		{
		continue;
	    }
	if (e.name != 'chkall')
	  	e.checked = true;
    }
  }
function check()
{
	var ok;
	ok=confirm("确认要执行此操作?");
	return ok;
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > 备份数据 -&gt; <a href="ChangeDb.php<?=$ecms_hashur['whehref']?>">选择数据库</a> -&gt; <a href="ChangeTable.php?mydbname=<?=$mydbname?><?=$ecms_hashur['ehref']?>">选择备份表</a>&nbsp;(<?=$mydbname?>) </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3>
            <div class="right" style="margin-right:10px;">
            <form name="searchtb" method="GET" action="ChangeTable.php">
<?=$ecms_hashur['eform']?>
            <input name="sear" type="hidden" id="sear" value="1">
			<input name="mydbname" type="hidden" value="<?=$mydbname?>">
            <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
          	<input type="submit" name="Submit3" value="显示数据表" class="anniu">
            </form>
            </div>
            <span><a href="javascript:();" style="font-size:12px;">备份步骤：选择数据库 -&gt; <font color="#FF0000">选择要备份的表</font> -&gt; 开始备份 -&gt; 完成</a></span>          
            </h3>
            <div class="line"></div>
<div>
<form action="phome.php" method="post" name="ebakchangetb" target="_blank" onsubmit="return check();">
  <?=$ecms_hashur['form']?>
<input name="phome" type="hidden" id="phome2" value="DoEbak"> 
<input name="mydbname" type="hidden" id="mydbname" value="<?=$mydbname?>">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th style="text-align:left; padding-left:20px;">备份参数设置：</th>
            <th></th>
		</tr>
 <tr bgcolor="#FFFFFF"> 
    <td height="25" style="text-align:left; padding-left:20px;"><input type="radio" name="baktype" value="0"<?=$dbaktype==0?' checked':''?>> 按文件大小备份</td>
    <td height="25" style="text-align:left; padding-left:20px;"><label>每组备份大小：</label><input name="filesize" type="text" id="filesize" value="300" size="6">  <font color="#666666">KB (1 MB = 1024 KB)</font></td>
  </tr>
 <tr bgcolor="#FFFFFF">
   <td height="25" style="text-align:left; padding-left:20px;"><input type="radio" name="baktype" value="1"<?=$dbaktype==1?' checked':''?>> 
              按记录数备份</td>
   <td height="25" style="text-align:left; padding-left:20px;"><input name="bakline" type="text" id="bakline" value="500" size="6">
              条记录， 
              <input name="autoauf" type="checkbox" id="autoauf" value="1" checked>
              自动识别自增字段<font color="#666666">(此方式效率更高)</font></td>
 </tr>
         <tr> 
            <td style="text-align:left; padding-left:20px;">备份数据库结构</td>
            <td height="23" style="text-align:left; padding-left:20px;"><input name="bakstru" type="checkbox" id="bakstru" value="1" checked> 
              <font color="#666666">(没特殊情况，请选择)</font></td>
          </tr>
          <tr> 
            <td valign="top" style="text-align:left; padding-left:20px;">数据编码</td>
            <td height="23" style="text-align:left; padding-left:20px;"> <select name="dbchar" id="dbchar">
                <option value="auto"<?=$ddbchar=='auto'?' selected':''?>>自动识别编码</option>
                <option value=""<?=$ecms_config['db']['setchar']==''?' selected':''?>>不设置</option>
                <option value="gbk"<?=$ecms_config['db']['setchar']=='gbk'?' selected':''?>>gbk</option>
                <option value="utf8"<?=$ecms_config['db']['setchar']=='utf8'?' selected':''?>>utf8</option>
                <option value="gb2312"<?=$ecms_config['db']['setchar']=='gb2312'?' selected':''?>>gb2312</option>
                <option value="big5"<?=$ecms_config['db']['setchar']=='big5'?' selected':''?>>big5</option>
                <option value="latin1"<?=$ecms_config['db']['setchar']=='latin1'?' selected':''?>>latin1</option>
              </select> <font color="#666666">(从mysql4.0导入mysql4.1以上版本需要选择固定编码，不能选自动)</font></td>
          </tr>
          <tr>
            <td valign="top" style="text-align:left; padding-left:20px;">数据存放格式</td>
            <td height="23" style="text-align:left; padding-left:20px;"><input name="bakdatatype" type="radio" value="0" checked>
              正常 
              <input type="radio" name="bakdatatype" value="1">
              十六进制方式<font color="#666666">(十六进制备份文件会占用更多的空间)</font></td>
          </tr>
          <tr> 
            <td style="text-align:left; padding-left:20px;">存放目录</td>
            <td height="23" style="text-align:left; padding-left:20px;">admin/ebak/ 
              <?=$bakpath?>
              / 
              <input name="mypath" type="text" id="mypath" value="<?=$mypath?>"> 
              <input type="button" name="Submit2" value="选择目录" onclick="javascript:window.open('ChangePath.php?change=1&toform=ebakchangetb<?=$ecms_hashur['ehref']?>','','width=600,height=500,scrollbars=yes');" class="anniu"> 
              <font color="#666666">(目录不存在，系统会自动建立)</font></td>
          </tr>
          <tr> 
            <td valign="top" style="text-align:left; padding-left:20px;">备份选项</td>
            <td height="23" style="text-align:left; padding-left:20px;">导入方式: 
              <select name="insertf" id="insertf">
                <option value="replace">REPLACE</option>
                <option value="insert">INSERT</option>
              </select>
              , 
              <input name="beover" type="checkbox" id="beover" value="1"<?=$dbeover==1?' checked':''?>>
              完整插入, 
              <input name="bakstrufour" type="checkbox" id="bakstrufour" value="1"> 
              <a title="需要转换数据表编码时选择">转成MYSQL4.0格式</a>, 每组备份间隔： 
              <input name="waitbaktime" type="text" id="waitbaktime" value="0" size="2">
              秒</td>
          </tr>
          <tr> 
            <td valign="top" style="text-align:left; padding-left:20px;">备份说明<br> <font color="#666666">(系统会生成一个readme.txt)</font></td>
            <td height="23" style="text-align:left; padding-left:20px;"><textarea name="readme" cols="80" rows="8" id="readme"></textarea></td>
          </tr>
          <tr> 
            <td valign="top" style="text-align:left; padding-left:20px;">去除自增值的字段列表：<br> <font color="#666666">(格式：<strong>表名.字段名</strong><br>
              多个请用&quot;,&quot;格开)</font></td>
            <td height="23" style="text-align:left; padding-left:20px;"><textarea name="autofield" cols="80" rows="5" id="autofield"></textarea></td>
          </tr>
	</tbody>
</table>
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th><a href="#ebak" onclick="SelectCheckAll(document.ebakchangetb)"><u>全选</u></a> 
        | <a href="#ebak" onclick="reverseCheckAll(document.ebakchangetb);"><u>反选</u></a></th>
            <th>表名(点击查看字段)</th>
            <th>类型</th>
            <th>编码</th>
            <th>记录数</th>
            <th>大小</th>
            <th>碎片</th>
		</tr>
        <?
		  $totaldatasize=0;//总数据大小
		  $tablenum=0;//总表数
		  $datasize=0;//数据大小
		  $rownum=0;//总记录数
		  while($r=$empire->fetch($sql))
		  {
		  $rownum+=$r[Rows];
		  $tablenum++;
		  $datasize=$r[Data_length]+$r[Index_length];
		  $totaldatasize+=$r[Data_length]+$r[Index_length]+$r[Data_free];
		  $collation=$r[Collation]?$r[Collation]:'---';
		  ?>
          <tr id=tb<?=$r[Name]?>> 
            <td height="23"> <div align="center"> 
                <input name="tablename[]" type="checkbox" id="tablename[]" value="<?=$r[Name]?>" onclick="if(this.checked){tb<?=$r[Name]?>.style.backgroundColor='#F1F7FC';}else{tb<?=$r[Name]?>.style.backgroundColor='#ffffff';}" checked>
              </div></td>
            <td height="23"> <a href="#ebak" onclick="window.open('ListField.php?mydbname=<?=$mydbname?>&mytbname=<?=$r[Name]?><?=$ecms_hashur['ehref']?>','','width=660,height=500,scrollbars=yes');" title="点击查看表字段列表"> 
              <?=$r[Name]?>
              </a></td>
            <td height="23"> <div align="center">
                <?=$r[Type]?$r[Type]:$r[Engine]?>
              </div></td>
            <td><div align="center">
				<?=$collation?>
			</div></td>
            <td height="23"> <div align="center">
                <?=$r[Rows]?>
              </div></td>
            <td height="23"> <div align="center">
                <?=Ebak_ChangeSize($datasize)?>
              </div></td>
            <td height="23"> <div align="center">
                <?=Ebak_ChangeSize($r[Data_free])?>
              </div></td>
          </tr>
          <?
		  }
		  db_close();
		  $empire=null;
		  ?>
         <tr bgcolor="#DBEAF5"> 
            <td height="23"> <div align="center">
                <input type=checkbox name=chkall value=on onclick=CheckAll(this.form) checked>
              </div></td>
            <td height="23"> <div align="center"> 
                <?=$tablenum?>
              </div></td>
            <td height="23"> <div align="center">---</div></td>
            <td><div align="center">---</div></td>
            <td height="23"> <div align="center">
                <?=$rownum?>
              </div></td>
            <td height="23" colspan="2"> <div align="center">
                <?=Ebak_ChangeSize($totaldatasize)?>
              </div></td>
          </tr>
	</tbody>
</table>
<div class="line"></div>
<div class="sub">  <input type="submit" name="Submit" value="开始备份" onclick="document.ebakchangetb.phome.value='DoEbak';" class="anniu">
          &nbsp;&nbsp; &nbsp;&nbsp; 
          <input type="submit" name="Submit2" value="修复数据表" onclick="document.ebakchangetb.phome.value='DoRep';" class="anniu">
          &nbsp;&nbsp; &nbsp;&nbsp; 
          <input type="submit" name="Submit22" value="优化数据表" onclick="document.ebakchangetb.phome.value='DoOpi';" class="anniu">
        &nbsp;&nbsp; &nbsp;&nbsp; 
          <input type="submit" name="Submit22" value="删除数据表" onclick="document.ebakchangetb.phome.value='DoDrop';" class="anniu">
		&nbsp;&nbsp; &nbsp;&nbsp; 
          <input type="submit" name="Submit22" value="清空数据表" onclick="document.ebakchangetb.phome.value='EmptyTable';" class="anniu"></div>
</form>
</div>
      </div>
    </div>
</div>
</div>
</body>
</html>
