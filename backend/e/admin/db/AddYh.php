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
CheckLevel($logininid,$loginin,$classid,"yh");
$enews=RepPostStr($_GET['enews'],1);
$url="<a href=ListYh.php".$ecms_hashur['whehref'].">管理优化方案</a> &gt; 增加优化方案";
$r[hlist]=30;
$r[qlist]=30;
$r[bqnew]=30;
$r[bqhot]=30;
$r[bqpl]=30;
$r[bqgood]=30;
$r[bqfirst]=30;
$r[bqdown]=30;
$r[otherlink]=30;
$r[qmlist]=0;
$r[dobq]=1;
$r[dojs]=1;
$r[dosbq]=0;
$r[rehtml]=0;
//复制
if($enews=="AddYh"&&$_GET['docopy'])
{
	$id=(int)$_GET['id'];
	$r=$empire->fetch1("select * from {$dbtbpre}enewsyh where id='$id'");
	$url="<a href=ListYh.php".$ecms_hashur['whehref'].">管理优化方案</a> &gt; 复制优化方案：<b>".$r[yhname]."</b>";
}
//修改
if($enews=="EditYh")
{
	$id=(int)$_GET['id'];
	$r=$empire->fetch1("select * from {$dbtbpre}enewsyh where id='$id'");
	$url="<a href=ListYh.php".$ecms_hashur['whehref'].">管理优化方案</a> -&gt; 修改优化方案：<b>".$r[yhname]."</b>";
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>方案名称</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container" style="overflow-x:hidden;">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?></div></div>
<div class="kongbai"></div>
<form name="form1" method="post" action="ListYh.php">
  <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="id" type="hidden" id="id" value="<?=$id?>"> 
    <div id="tab"  style="padding-bottom:50px;_margin-bottom:100px;overflow:hidden;">
	<div class="ui-tab-container">
	<div class="ui-tab-bd">
		<div class="ui-tab-content">
            <div class="newscon anniuqun">
<div class="ui-tab-content">
        	<h3><span>方案名称</span></h3>
            <div class="line"></div>
            <ul>
            	<li class="jqui"><label>方案名称(*)</label><input name="yhname" type="text" id="yhname" value="<?=$r[yhname]?>" size="42"></li>
            <li><label>方案说明:</label><textarea name="yhtext" cols="45" rows="4" id="yhtext"><?=ehtmlspecialchars($r[yhtext])?></textarea></li>
            </ul>
          <div class="line"></div>
            <h3><span>信息列表</span></h3>
            <ul>
            <li class="jqui"><label>后台管理列表:</label>显示 
        <input name="hlist" type="text" id="hlist" value="<?=$r[hlist]?>" size="8">
        天内的信息 <font color="#666666">(0为不限)</font></li>
            <li><label>前台管理列表:</label>显示 
        <input name="qmlist" type="text" id="qmlist" value="<?=$r[qmlist]?>" size="8">
        天内的信息 <font color="#666666">(0为不限)</font></li>
            <li><label>前台信息列表:</label>显示 
        <input name="qlist" type="text" id="qlist" value="<?=$r[qlist]?>" size="8">
        天内的信息 <font color="#666666">(0为不限)</font></li>
        </ul>
		<div class="line"></div>
        <h3><span>标签调用</span></h3>
		<div class="line"></div>
        <ul>
            <li class="jqui"><label>优化范围:</label><input name="dobq" type="checkbox" id="dobq" value="1"<?=$r[dobq]==1?' checked':''?>>
        标签调用 
        <input name="dojs" type="checkbox" id="dojs" value="1"<?=$r[dojs]==1?' checked':''?>>
        JS调用 
        <input name="dosbq" type="checkbox" id="dosbq" value="1"<?=$r[dosbq]==1?' checked':''?>>
        会员空间标签调用</li>
            <li><label>最新信息:</label>调用 
        <input name="bqnew" type="text" id="hlist3" value="<?=$r[bqnew]?>" size="8">
        天内的信息 <font color="#666666">(0为不限)</font></li>
            <li><label>点击排行:</label>调用 
        <input name="bqhot" type="text" id="bqnew" value="<?=$r[bqhot]?>" size="8">
        天内的信息 <font color="#666666">(0为不限)</font></li>
            <li><label>推荐信息:</label>调用 
        <input name="bqgood" type="text" id="bqnew2" value="<?=$r[bqgood]?>" size="8">
        天内的信息 <font color="#666666">(0为不限)</font></li>
		<li><label>评论排行:</label>调用 
        <input name="bqpl" type="text" id="bqnew3" value="<?=$r[bqpl]?>" size="8">
        天内的信息 <font color="#666666">(0为不限)</font></li>
            <li><label>头条信息:</label>调用 
        <input name="bqfirst" type="text" id="bqnew4" value="<?=$r[bqfirst]?>" size="8">
        天内的信息 <font color="#666666">(0为不限)</font></li>
            <li><label>下载排行:</label>调用 
        <input name="bqdown" type="text" id="bqnew5" value="<?=$r[bqdown]?>" size="8">
        天内的信息 <font color="#666666">(0为不限)</font></li>
		</ul>
		<div class="line"></div>
         <h3><span>其它相关</span></h3>
		 <div class="line"></div>
        <ul>
            <li class="jqui"><label>内容页生成范围:</label>生成 
        <input name="rehtml" type="text" id="rehtml" value="<?=$r[rehtml]?>" size="8">
        天内的信息 <font color="#666666">(0为不限)</font></li>
            <li><label>相关链接检索范围:</label>查询 
        <input name="otherlink" type="text" id="otherlink" value="<?=$r[otherlink]?>" size="8">
        天内的信息 <font color="#666666">(0为不限)</font></li>        
            </ul>
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