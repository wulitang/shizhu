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
CheckLevel($logininid,$loginin,$classid,"table");
$enews=RepPostStr($_GET['enews'],1);
$url="<a href=ListTable.php".$ecms_hashur['whehref'].">管理数据表</a>&nbsp;>&nbsp;新建数据表";
//修改
if($enews=="EditTable")
{
	$tid=(int)$_GET['tid'];
	$url="<a href=ListTable.php".$ecms_hashur['whehref'].">管理数据表</a>&nbsp;>&nbsp;修改数据表";
	$r=$empire->fetch1("select tid,tbname,tname,tsay,yhid,intb from {$dbtbpre}enewstable where tid='$tid'");
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
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>新建数据表</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script src="../ecmseditor/fieldfile/setday.js"></script>
<script type="text/javascript">
$(function(){
			
		});
//管理优化方案
function glyhfa(){
art.dialog.open('db/ListYh.php<?=$ecms_hashur[whehref]?>',
    {title: '管理优化方案',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php<?=$ecms_hashur[whehref]?>">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>建立/修改数据表</span></h3>
            <div class="line"></div>
            <form name="form1" method="post" action="../ecmsmod.php">
			  <?=$ecms_hashur['form']?>
			<ul>
            		<li class="jqui"><label>数据表名:</label><i><?=$dbtbpre?>ecms_</i><input name="tbname" type="text" id="tbname" value="<?=$r[tbname]?>" size="10">
        *<font color="#666666">(如:news,只能由字母、数字组成)</font></li>
                    <li class="jqui"><label>数据表标识:</label><input name="tname" type="text" id="tname" value="<?=$r[tname]?>" size="20">
        *<font color="#666666">(如:新闻数据表)</font></li>
         <li><label>使用优化方案:</label><select name="yhid" id="yhid">
          <option name="0">不使用</option>
          <?=$yh_options?>
        </select> <input type="button" name="Submit63" value="管理优化方案" onClick="glyhfa();" class="anniu"></li>
                    <li class="jqui"><label>是否内部表:</label><input type="radio" name="intb" value="0"<?=$r['intb']==0?' checked':''?>>
        <i>正常表</i> 
        <input type="radio" name="intb" value="1"<?=$r['intb']==1?' checked':''?>>
        <i>内部表</i> <font color="#666666">(内部表前台不显示和生成，只有后台才能查看)</font></li>
        <li class="jqui"><label>备注:</label><textarea name="tsay" cols="70" rows="8" id="tsay"><?=stripSlashes($r[tsay])?></textarea></li>
                    <li class="jqui"><label>&nbsp;</label><input type="submit" name="Submit" value="提交"> <input name="tid" type="hidden" id="tid" value="<?=$tid?>"> 
        <input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="oldtbname" type="hidden" id="oldtbname" value="<?=$r[tbname]?>"></li>
            </ul>
            </form>
        </div>
        <div class="line"></div>
    </div>
</div>
</div>
</body>
</html>
