<?php
define('EmpireCMSAdmin','1');
require('../../class/connect.php');
require('../../class/db_sql.php');
require('../../class/functions.php');
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

$enews=ehtmlspecialchars($_GET['enews']);
$ztid=(int)$_GET['ztid'];
if($enews=='EditZt')
{
	//验证权限
	$returnandlevel=CheckAndUsernamesLevel('dozt',$ztid,$logininid,$loginin,$loginlevel);
}
else
{
	//验证权限
	CheckLevel($logininid,$loginin,$classid,"zt");
	$returnandlevel=2;
}
$url="<a href=ListZt.php".$ecms_hashur['whehref'].">管理专题</a>&nbsp;>&nbsp;增加专题";
$postword='增加专题';
//初使化数据
$r[reorder]="newstime DESC";
$r[maxnum]=0;
$r[ztnum]=25;
$r[zttype]=".html";
$r[newline]=10;
$r[hotline]=10;
$r[goodline]=10;
$r[hotplline]=10;
$r[firstline]=10;
$pripath='s/';
//复制专题
$docopy=RepPostStr($_GET['docopy'],1);
if($docopy&&$enews=="AddZt")
{
	$copyclass=1;
}
$ecmsfirstpost=1;
if($enews=='EditZt')
{
	$filepass=$ztid;
}
else
{
	$filepass=ReturnTranFilepass();
}
//修改专题
if($enews=="EditZt"||$copyclass)
{
	$ecmsfirstpost=0;
	if($copyclass)
	{
		$thisdo="复制";
	}
	else
	{
		$thisdo="修改";
	}
	$r=$empire->fetch1("select * from {$dbtbpre}enewszt where ztid='$ztid'");
	$addr=$empire->fetch1("select * from {$dbtbpre}enewsztadd where ztid='$ztid'");
	$usernames=substr($r['usernames'],1,-1);
	$url="<a href=ListZt.php".$ecms_hashur['whehref'].">管理专题</a>&nbsp;>&nbsp;".$thisdo."专题：".$r[ztname];
	$postword=$thisdo.'专题';
	//专题目录
	$mycr=GetPathname($r[ztpath]);
	$pripath=$mycr[1];
	$ztpath=$mycr[0];
	//复制专题
	if($copyclass)
	{
		$r[ztname].='(1)';
		$ztpath.='1';
	}
}
//列表模板
$msql=$empire->query("select mid,mname from {$dbtbpre}enewsmod order by myorder,mid");
while($mr=$empire->fetch($msql))
{
	$listtemp_options.="<option value=0 style='background:#99C4E3'>".$mr[mname]."</option>";
	$l_sql=$empire->query("select tempid,tempname from ".GetTemptb("enewslisttemp")." where modid='$mr[mid]'");
	while($l_r=$empire->fetch($l_sql))
	{
		if($l_r[tempid]==$r[listtempid])
		{$l_d=" selected";}
		else
		{$l_d="";}
		$listtemp_options.="<option value=".$l_r[tempid].$l_d."> |-".$l_r[tempname]."</option>";
	}
}
//栏目
$options=ShowClass_AddClass("",$r[classid],0,"|-",0,0);
//封面模板
$classtempsql=$empire->query("select tempid,tempname from ".GetTemptb("enewsclasstemp")." order by tempid");
while($classtempr=$empire->fetch($classtempsql))
{
	$select="";
	if($r[classtempid]==$classtempr[tempid])
	{
		$select=" selected";
	}
	$classtemp.="<option value='".$classtempr[tempid]."'".$select.">".$classtempr[tempname]."</option>";
}
//评论模板
$pltemp='';
$pltempsql=$empire->query("select tempid,tempname from ".GetTemptb("enewspltemp")." order by tempid");
while($pltempr=$empire->fetch($pltempsql))
{
	$select="";
	if($r[pltempid]==$pltempr[tempid])
	{
		$select=" selected";
	}
	$pltemp.="<option value='".$pltempr[tempid]."'".$select.">".$pltempr[tempname]."</option>";
}
//分类
$zcstr="";
$zcsql=$empire->query("select classid,classname from {$dbtbpre}enewsztclass order by classid");
while($zcr=$empire->fetch($zcsql))
{
	$select="";
	if($zcr[classid]==$r[zcid])
	{
		$select=" selected";
	}
	$zcstr.="<option value='".$zcr[classid]."'".$select.">".$zcr[classname]."</option>";
}
//优化方案
$yh_options='';
$yhsql=$empire->query("select id,yhname from {$dbtbpre}enewsyh order by id");
while($yhr=$empire->fetch($yhsql))
{
	$select='';
	if($r[yhid]==$yhr[id])
	{
		$select=' selected';
	}
	$yh_options.="<option value='".$yhr[id]."'".$select.">".$yhr[yhname]."</option>";
}
$from=(int)$_GET['from'];
//当前使用的模板组
$thegid=GetDoTempGid();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>增加专题</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script>
//检查
function CheckForm(obj){
	if(obj.ztname.value=='')
	{
		alert("请输入专题名称");
		obj.ztname.focus();
		return false;
	}
	if(obj.ztpath.value=="")
	{
		alert("请输入专题目录");
		obj.ztpath.focus();
		return false;
	}
	if(obj.listtempid.value==0)
	{
		alert("请选择列表模板");
		obj.listtempid.focus();
		return false;
	}
}
  </script>
<script src="../ecmseditor/fieldfile/setday.js"></script>
<script>
//管理栏目
function gllm(){
art.dialog.open('ListClass.php<?=$ecms_hashur['whehref']?>',
    {title: '管理栏目',width: 1050, height: 540});
}
//管理分类
function glfl(){
art.dialog.open('special/ListZtClass.php<?=$ecms_hashur['whehref']?>',
    {title: '管理分类',width: 800, height: 540});
}
//管理优化方案
function glyh(){
art.dialog.open('db/ListYh.php<?=$ecms_hashur[whehref]?>',
    {title: '管理优化方案',width: 800, height: 540});
}
//管理封面模板
function glfmmb(){
art.dialog.open('template/ListClasstemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>',
    {title: '管理封面模板',width: 800, height: 540});
}
//管理列表模板
function gllbmb(){
art.dialog.open('template/ListListtemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>',
    {title: '管理列表模板',width: 800, height: 540});
}
//管理评论模板
function glplmb(){
art.dialog.open('template/ListPltemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>',
    {title: '管理评论模板',width: 800, height: 540});
}
</script>
</head>

<body>
<div class="container" style="overflow-x:hidden;">
<div class="nynav"><?=$enews=='EditNews'?'<a href="user/ListDolog.php?classid='.$classid.'&id='.$id.'" target="_blank" class="right" style="margin-right:10px;">[查看本信息操作日志]</a>':''?><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?></div></div>
<div class="kongbai"></div>
<form name="form1" method="post" action="../ecmsclass.php" onsubmit="return CheckForm(document.form1);">
  <?=$ecms_hashur['form']?>
<input type=hidden name=enews value=<?=$enews?>> <input name="from" type="hidden" id="from" value="<?=$from?>">
    <div id="tab"  style="padding-bottom:50px;_margin-bottom:100px;overflow:hidden;">
	<div class="ui-tab-container">
	<div class="ui-tab-bd">
		<div class="ui-tab-content">
            <div class="newscon anniuqun">
<div class="ui-tab-content">
        	<h3><span>基本选项</span></h3>
            <div class="line"></div>
            <ul>
            	<li class="jqui"><label>专题名称(*)</label><input name="ztname" type="text" id="ztname" value="<?=$r[ztname]?>" size="38"> 
        <?
	  if($enews=="AddZt")
	  {
	  ?>
        <input type="button" name="Submit5" value="生成拼音目录" onclick="window.open('../GetPinyin.php?<?=$ecms_hashur['href']?>&hz='+document.form1.ztname.value+'&returnform=opener.document.form1.ztpath.value','','width=160,height=100');"> 
        <?
	  }
	  ?>
        <input name="ztid" type="hidden" id="ztid" value="<?=$ztid?>"> <input name="oldztid" type="hidden" id="oldztid" value="<?=$ztid?>">      <input name="filepass" type="hidden" id="filepass" value="<?=$filepass?>"></li>
        <?php
	if($returnandlevel==2)
	{
	?>
    <li><label>可更新专题的用户</label><input name="usernames" type="text" id="usernames" value="<?=$usernames?>" size="38">
        <font color="#666666">
        <input type="button" name="Submit32" value="选择" onclick="window.open('../ChangeUser.php?field=usernames&form=form1<?=$ecms_hashur['ehref']?>','','width=300,height=520,scrollbars=yes');">
(多个用户用“,”逗号隔开)</font></li>
    </tr>
	<?php
	}
	?>
            <li><label>隶属信息栏目</label><select name="classid" id="classid">
          <option value="0">隶属于所有栏目</option>
          <?=$options?>
        </select> <input type="button" name="Submit622232" value="管理栏目" onclick="gllm()"> 
        <font color="#666666">(选择父栏目，将应用于子栏目)</font> </li>
            <div>
            <li><label>存放文件夹(*) </label><input name="oldztpath" type="hidden" id="oldztpath2" value="<?=$r[ztpath]?>"> 
        <input name="oldpripath" type="hidden" id="oldztpath3" value="<?=$pripath?>">
        <table border="0" cellspacing="1" cellpadding="3" class="comm-table2" style="width:400px;">
        <tbody>
        <tr style="border-left:1px solid #CDCDCD"> 
                  <th style="line-height: 26px;height: 26px;">&nbsp;</th>
                  <th style="line-height: 26px;height: 26px;">上层目录</th>
                  <th style="line-height: 26px;height: 26px;">本专题目录</th>
                  <th style="line-height: 26px;height: 26px;">&nbsp;</th>
         </tr>
         <tr> 
                  <td nowrap="nowrap">根目录/</td>
                  <td><input name="pripath" type="text" id="pripath" value="<?=$pripath?>" size="15"></td>
                  <td><input name="ztpath" type="text" id="ztpath2" value="<?=$ztpath?>" size="15"></td>
                  <td> <div align="left"> 
                      <input type="button" name="Submit3" value="检测目录" onclick="javascript:window.open('../ecmscom.php?<?=$ecms_hashur['href']?>&enews=CheckPath&pripath='+document.form1.pripath.value+'&classpath='+document.form1.ztpath.value,'','width=100,height=100,top=250,left=450');" class="anniu">
                    </div></td>
          </tr>
          </tbody>
        </table>
        </li>
            </div>
            <li class="jqui"><label>使用优化方案</label> 
             <select name="yhid" id="yhid">
          <option name="0">不使用</option>
          <?=$yh_options?>
        </select> <input type="button" name="Submit63" value="管理优化方案" onclick="glyh()"></li>
                <li class="jqui"><label>文件扩展名</label><input name="zttype" type="text" id="zttype4" value="<?=$r[zttype]?>" size="38"> 
        <select name="select" onchange="document.form1.zttype.value=this.value">
          <option value=".html">扩展名</option>
          <option value=".html">.html</option>
          <option value=".htm">.htm</option>
          <option value=".php">.php</option>
          <option value=".shtml">.shtml</option>
        </select> </li>
                <li class="jqui"><label>绑定域名</label><input name="zturl" type="text" id="zturl" value="<?=$r[zturl]?>" size="38"> 
        <font color="#666666"> (如不绑定,请留空.后面无需加&quot;/&quot;)</font> </li>
                <li class="jqui"><label>所属分类</label><select name="zcid" id="zcid">
          <option value="0">不隶属于任何分类</option>
          <?=$zcstr?>
        </select> <input type="button" name="Submit6222322" value="管理分类" onclick="glfl()"> </li>
                <li class="jqui"><label>专题缩略图</label><input name="ztimg" type="text" id="ztimg" value="<?=$r[ztimg]?>" size="38"> 
        <a onclick="window.open('../ecmseditor/FileMain.php?<?=$ecms_hashur['ehref']?>&modtype=2&type=1&classid=&doing=2&field=ztimg&filepass=<?=$filepass?>&sinfo=1','','width=700,height=550,scrollbars=yes');" title="选择已上传的图片"><img src="../../data/images/changeimg.gif" border="0" align="absbottom"></a> </li>
                <li class="jqui"><label>网页关键字</label><input name="ztpagekey" type="text" id="ztpagekey" value="<?=$r[ztpagekey]?>" size="38"> </li>
                <li class="jqui"><label>专题简介</label><textarea name="intro" cols="70" rows="8" id="intro"><?=stripSlashes($r[intro])?></textarea> </li>
                <li class="jqui"><label>排序</label><input name="myorder" type="text" id="myorder" value="<?=$r[myorder]?>" size="38"> 
        <font color="#666666"> (值越小越前面)</font> </li>
                <li class="jqui"><label>显示到导航</label><input type="radio" name="showzt" value="0"<?=$r[showzt]==0?' checked':''?>>
        是 
        <input type="radio" name="showzt" value="1"<?=$r[showzt]==1?' checked':''?>>
        否<font color="#666666">（如：专题导航标签）</font> </li>
                <li class="jqui"><label>信息可选择</label><input type="radio" name="usezt" value="0"<?=$r[usezt]==0?' checked':''?>>
        是 
        <input type="radio" name="usezt" value="1"<?=$r[usezt]==1?' checked':''?>>
        否<font color="#666666">（如果选择否那么增加信息时不会显示这个专题）</font> </li>
                <li class="jqui"><label>过期时间</label><input name="endtime" type="text" id="endtime" value="<?=$r[endtime]?date('Y-m-d',$r[endtime]):''?>" size="12" onClick="setday(this)">
        <font color="#666666">(超过此期限不再更新和评论,空为不限制)
        <input name="oldendtime" type="hidden" id="oldendtime" value="<?=$r[endtime]?>">
        </font> </li>
                <li><label>评论</label>
                <input type="radio" name="closepl" value="0"<?=$r['closepl']==0?' checked':''?>>
        开启
          <input type="radio" name="closepl" value="1"<?=$r['closepl']==1?' checked':''?>>
        关闭，评论是否审核：
        <input name="checkpl" type="checkbox" id="checkpl" value="1"<?=$r['checkpl']==1?' checked':''?>>
        是 </li>
            </ul>
            <div>
            <h3><span>页面设置</span></h3>
            <ul>
            <li class="jqui"><label>页面显示模式</label> 
              <input type="radio" name="islist" value="0"<?=$r[islist]==0?' checked':''?>>
        封面式 
        <input type="radio" name="islist" value="1"<?=$r[islist]==1?' checked':''?>>
        列表式 
        <input type="radio" name="islist" value="2"<?=$r[islist]==2?' checked':''?>>
        页面内容式 
        <input name="oldislist" type="hidden" id="oldislist" value="<?=$r[islist]?>"><br /><label>&nbsp;</label> <font color="#666666">说明：封面式要选择封面模板、列表式要选择列表模板、内容式要录入页面内容</font></li>
            <li><label>封面模板</label><select name="classtempid">
          <?=$classtemp?>
        </select> <input type="button" name="Submit6223" value="管理封面模板" onclick="glfmmb()">
            </li>
            <li><label>所用列表模板</label><select name="listtempid" id="listtempid">
          <?=$listtemp_options?>
        </select> <input type="button" name="Submit622" value="管理列表模板" onclick="gllbmb()"></li>
            <li class="jqui"><label>评论模板</label><select name="pltempid" id="pltempid">
        <option value="0">使用默认模板 </option>
        <?=$pltemp?>
      </select>
        <input type="button" name="Submit62" value="管理评论模板" onclick="glplmb()"></li>
        </ul>
        <h3><span>列表式设置</span></h3>
        <ul>
            <li class="jqui"><label>排序方式</label><input name="reorder" type="text" id="reorder" value="<?=$r[reorder]?>"> 
              <select name="orderselect" onchange="document.form1.reorder.value=this.value">
                <option value="newstime DESC"></option>
                <option value="newstime DESC">按发布时间降序排序</option>
                <option value="id DESC">按信息ID降序排序</option>
                <option value="zid DESC">按加入ID降序排序</option>
				<option value="isgood DESC,newstime DESC">按推荐置顶排序</option>
              </select></li>
            <li><label>显示总记录数</label> 
             <input name="maxnum" type="text" id="maxnum" value="<?=$r[maxnum]?>" size="6"> 
              <font color="#666666">(0为显示所有记录)</font></li>
            <li><label>每页显示记录数</label><input name="ztnum" type="text" id="ztnum3" value="<?=$r[ztnum]?>" size="6">
            </li>
            <li><label>页面内容<br /><font color="#666666">(支持标签同封面模板)</font></label>请将内容<a href="#ecms" onclick="window.clipboardData.setData('Text',document.form1.classtext.value);document.form1.classtext.select()" title="点击复制模板内容"><strong>复制到Dreamweaver(推荐)</strong></a>或者使用<a href="#ecms" onclick="window.open('../template/editor.php?<?=$ecms_hashur['ehref']?>&getvar=opener.document.form1.classtext.value&returnvar=opener.document.form1.classtext.value&fun=ReturnHtml','editclasstext','width=880,height=600,scrollbars=auto,resizable=yes');"><strong>模板在线编辑</strong></a>进行可视化编辑
            </li>
            <li class="jqui"><textarea name="classtext" cols="80" rows="23" id="classtext" style="WIDTH: 100%"><?=ehtmlspecialchars(stripSlashes($addr[classtext]))?></textarea></td>
    </tr>
    <?php
  	$ztfnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsztf");
  	if($ztfnum)
  	{
  		$editorfnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsztf where fform='editor' limit 1");	
		if($editorfnum)
		{
			include('../ecmseditor/infoeditor/fckeditor.php');
		}
  	?>
    </li>
            <li><label>自定义字段设置</label><?php
	@include('../../data/html/ztaddform.php');
	?></li>
            <li><label>&nbsp;</label><strong>专题自定义字段调用说明</strong><br>
        内置调用专题自定义字段函数：ReturnZtAddField(专题ID,字段名)，专题ID=0为当前专题ID。取多个字段内容可用逗号隔开，例子：<br>
        取得'classtext'字段内容：$value=ReturnZtAddField(0,'classtext'); //$value就是字段内容。<br>
        取得多个字段内容：$value=ReturnZtAddField(1,'ztid,classtext'); //$value['classtext']才是字段内容。</li>
    <?php
	}
	?>
            </ul>
            </div>
        </div>
        	</div>
        </div>
 <div class="line"></div>
  </div>
 <div class="sub jqui"><input type="submit" name="addnews2" value="提交" class="anniu">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="Submit23" value="重置" class="anniu"></div>
 </div>
</div>
 </form>
 <div class="clear"></div>
</div>
</body>
</html>
<?php
db_close();
$empire=null;
?>