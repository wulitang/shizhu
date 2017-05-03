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
CheckLevel($logininid,$loginin,$classid,"sp");
$enews=ehtmlspecialchars($_GET['enews']);
$postword='增加碎片';
$noteditword='<font color="#666666">(设置后不可修改)</font>';
$disabled='';
$sptypehidden='';
$r[maxnum]=0;
$url="<a href=ListSp.php".$ecms_hashur['whehref'].">管理碎片</a> &gt; 增加碎片";
$fcid=(int)$_GET['fcid'];
$fclassid=(int)$_GET['fclassid'];
$fsptype=(int)$_GET['fsptype'];
$r['spfile']='html/sp/'.time().'.html';
$spid=(int)$_GET['spid'];
if($enews=='EditSp')
{
	$filepass=$spid;
}
else
{
	$filepass=ReturnTranFilepass();
}
//复制
if($enews=="AddSp"&&$_GET['docopy'])
{
	$r=$empire->fetch1("select * from {$dbtbpre}enewssp where spid='$spid'");
	$url="<a href=ListSp.php".$ecms_hashur['whehref'].">管理碎片</a> &gt; 复制碎片：<b>".$r[spname]."</b>";
	$username=substr($r[username],1,-1);
}
//修改
if($enews=="EditSp")
{
	$r=$empire->fetch1("select * from {$dbtbpre}enewssp where spid='$spid'");
	$postword='修改碎片';
	$noteditword='';
	$disabled=' disabled';
	$sptypehidden='<input type="hidden" name="sptype" value="'.$r[sptype].'">';
	$url="<a href=ListSp.php".$ecms_hashur['whehref'].">管理碎片</a> &gt; 修改碎片：<b>".$r[spname]."</b>";
	$username=substr($r[username],1,-1);
}
//标签模板
$bqtemp='';
$bqtempsql=$empire->query("select tempid,tempname from ".GetTemptb("enewsbqtemp")." order by tempid");
while($bqtempr=$empire->fetch($bqtempsql))
{
	$select="";
	if($r[tempid]==$bqtempr[tempid])
	{
		$select=" selected";
	}
	$bqtemp.="<option value='".$bqtempr[tempid]."'".$select.">".$bqtempr[tempname]."</option>";
}
//栏目
$options=ShowClass_AddClass("",$r[classid],0,"|-",0,0);
//分类
$scstr='';
$scsql=$empire->query("select classid,classname from {$dbtbpre}enewsspclass order by classid");
while($scr=$empire->fetch($scsql))
{
	$select="";
	if($scr[classid]==$r[cid])
	{
		$select=" selected";
	}
	$scstr.="<option value='".$scr[classid]."'".$select.">".$scr[classname]."</option>";
}
//用户组
$group='';
$groupsql=$empire->query("select groupid,groupname from {$dbtbpre}enewsgroup order by groupid");
while($groupr=$empire->fetch($groupsql))
{
	$select='';
	if(strstr($r[groupid],','.$groupr[groupid].','))
	{
		$select=' selected';
	}
	$group.="<option value='".$groupr[groupid]."'".$select.">".$groupr[groupname]."</option>";
}
//部门
$userclass='';
$ucsql=$empire->query("select classid,classname from {$dbtbpre}enewsuserclass order by classid");
while($ucr=$empire->fetch($ucsql))
{
	$select='';
	if(strstr($r[userclass],','.$ucr[classid].','))
	{
		$select=' selected';
	}
	$userclass.="<option value='".$ucr[classid]."'".$select.">".$ucr[classname]."</option>";
}
//当前使用的模板组
$thegid=GetDoTempGid();
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>碎片</title>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script>
//管理分类
function glspfl(){
art.dialog.open('sp/ListSpClass.php<?=$ecms_hashur['whehref']?>',
    {title: '管理分类',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//管理栏目
function gllm(){
art.dialog.open('ListClass.php<?=$ecms_hashur['whehref']?>',
    {title: '管理栏目',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//管理标签模板
function zjbqmb(){
art.dialog.open('template/ListBqtemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>',
    {title: '管理标签模板',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
</script>
<script>
function selectalls(doselect,formvar)
{  
	 var bool=doselect==1?true:false;
	 var selectform=document.getElementById(formvar);
	 for(var i=0;i<selectform.length;i++)
	 { 
		  selectform.all[i].selected=bool;
	 } 
}
</script>
</head>

<body>

<div class="container" style="overflow-x:hidden;">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?></div></div>
<div class="kongbai"></div>
<form name="form1" method="post" action="ListSp.php">
  <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="spid" type="hidden" id="spid" value="<?=$spid?>"> 
        <input name="fcid" type="hidden" id="fcid" value="<?=$fcid?>"> <input name="fclassid" type="hidden" id="fclassid" value="<?=$fclassid?>"> 
        <input name="fsptype" type="hidden" id="fsptype" value="<?=$fsptype?>">
		<input name="filepass" type="hidden" id="filepass" value="<?=$filepass?>">
    <div id="tab" style="padding-bottom:50px;_margin-bottom:100px;overflow:hidden;">
	<div class="ui-tab-container">
	<div class="ui-tab-bd">
		<div class="ui-tab-content">
            <div class="newscon anniuqun">
<div class="ui-tab-content">
        	<h3><span><?=$postword?></span></h3>
            <div class="line"></div>
            <ul>
            	<li class="jqui"><label>碎片类型</label><select name="sptype" id="sptype"<?=$disabled?>>
          <option value="1"<?=$r[sptype]==1?' selected':''?>>静态信息碎片</option>
          <option value="2"<?=$r[sptype]==2?' selected':''?>>动态信息碎片</option>
          <option value="3"<?=$r[sptype]==3?' selected':''?>>代码碎片</option>
        </select> 
        <?=$noteditword?>
        <?=$sptypehidden?> </li>
    <li><label>碎片名称</label><input name="spname" type="text" id="spname" value="<?=$r[spname]?>" size="42"> </li>
            <li><label>碎片变量名</label><input name="varname" type="text" id="varname" value="<?=$r[varname]?>" size="42"></li>
            <li><label>所属分类</label><select name="cid" id="cid">
          <option value="0">不隶属于任何类别</option>
          <?=$scstr?>
        </select> <input type="button" name="Submit6222322" value="管理分类" onclick="glspfl()">
        </li>
            <li class="jqui"><label>隶属信息栏目</label> 
             <select name="classid" id="classid">
          <option value="0">隶属于所有栏目</option>
          <?=$options?>
        </select> <input type="button" name="Submit622232" value="管理栏目" onclick="gllm()"> 
        <font color="#666666">(选择父栏目，将应用于子栏目)</font></li>
                <li class="jqui"><label>最大信息数量</label><input name="maxnum" type="text" id="spname3" value="<?=$r[maxnum]?>" size="42"> 
        <font color="#666666">(0为不限)</font></li>
                <li class="jqui"><label>使用标签模板</label><select name="tempid" id="tempid">
          <?=$bqtemp?>
        </select> <input type="button" name="Submit6222323" value="管理标签模板" onclick="zjbqmb()"></li>
                <li class="jqui"><label>是否生成碎片文件</label><input type="radio" name="refile" value="0"<?=$r[refile]==0?' checked':''?>>
        不生成 
        <input type="radio" name="refile" value="1"<?=$r[refile]==1?' checked':''?>>
        生成</li>
                <li class="jqui"><label>生成碎片文件名</label>/ 
        <input name="spfile" type="text" id="spfile" value="<?=$r[spfile]?>" size="42">
        <input name="oldspfile" type="hidden" id="oldspfile" value="<?=$r[spfile]?>"></li>
                <li class="jqui"><label>生成碎片文件内容设置</label>显示信息数量：
        <input name="spfileline" type="text" id="spfileline" value="<?=$r[spfileline]?>" size="6">
        ，标题截取字数：
        <input name="spfilesub" type="text" id="spfilesub" value="<?=$r[spfilesub]?>" size="6"></li>
                <li class="jqui"><label>碎片效果图</label><input name="sppic" type="text" id="sppic" value="<?=$r[sppic]?>" size="42"> 
        <a onclick="window.open('../ecmseditor/FileMain.php?<?=$ecms_hashur['ehref']?>&modtype=7&type=1&classid=&doing=2&field=sppic&filepass=<?=$filepass?>&sinfo=1','','width=700,height=360,scrollbars=yes');" title="选择已上传的图片"><img src="../../data/images/changeimg.gif" alt="选择/上传图片" border="0" align="absbottom"></a>
        </li>
                <li class="jqui"><label>碎片描述</label><textarea name="spsay" cols="60" rows="5" id="varname3"><?=ehtmlspecialchars($r[spsay])?></textarea></li>
                <li class="jqui"><label>可越权限推送</label><input type="radio" name="cladd" value="0"<?=$r[cladd]==0?' checked':''?>>
        是 
        <input type="radio" name="cladd" value="1"<?=$r[cladd]==1?' checked':''?>>
        否 <font color="#666666">(不在权限设置范围内的用户也能推送信息)</font></li>
                <li class="jqui"><label>是否开启</label><input type="radio" name="isclose" value="0"<?=$r[isclose]==0?' checked':''?>>
        是 
        <input type="radio" name="isclose" value="1"<?=$r[isclose]==1?' checked':''?>>
        否</li>
        </ul>
        <h3><span>权限设置</span></h3>
        <ul>
                <li><label>用户组</label><select name="groupid[]" size="5" multiple id="groupidselect" style="width:180">
          <?=$group?>
        </select>
        [<a href="#empirecms" onclick="selectalls(0,'groupidselect')">全部取消</a>]
                </li>
            <li class="jqui"><label>部门</label> 
            <select name="userclass[]" size="5" multiple id="userclassselect" style="width:180">
          <?=$userclass?>
        </select>
        [<a href="#empirecms" onclick="selectalls(0,'userclassselect')">全部取消</a>]</li>
            <li><label>用户</label><input name="username" type="text" id="username" value="<?=$username?>" size="42"> 
        <font color="#666666"> 
        <input type="button" name="Submit3" value="选择" onclick="window.open('../ChangeUser.php?field=username&form=form1<?=$ecms_hashur['ehref']?>','','width=700,height=520,scrollbars=yes');">
        (多个用户用“,”逗号隔开)</font></li>
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
