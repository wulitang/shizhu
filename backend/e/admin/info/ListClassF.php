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
CheckLevel($logininid,$loginin,$classid,"classf");
$url="<a href='../ListClass.php".$ecms_hashur['whehref']."'>管理栏目</a>&nbsp;>&nbsp;<a href='ListClassF.php".$ecms_hashur['whehref']."'>管理栏目字段</a>";
$sql=$empire->query("select * from {$dbtbpre}enewsclassf order by myorder,fid");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理字段</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type="text/javascript">
//增加栏目字段
function zjlmzd(){
art.dialog.open('info/AddClassF.php?<?=$ecms_hashur[ehref]?>&enews=AddClassF',
    {title: '增加栏目字段',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//管理栏目
function gllm(){
art.dialog.open('ListClass.php<?=$ecms_hashur[whehref]?>',
    {title: '管理栏目',lock: true,opacity: 0.5, width: 1050, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//修改栏目字段
function edit(obj){
art.dialog.open('info/AddClassF.php?<?=$ecms_hashur[ehref]?>&enews=EditClassF&fid='+obj,
    {title: '修改栏目字段',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//删除栏目字段
function del(fid){
	var classname=document.getElementById('lmzd'+fid).innerHTML;
	var dialog=art.dialog({
	 title:'删除警告',
	 follow: document.getElementById('del'+fid),
	 content: '你确定要删除 "'+classname+'" 栏目字段么?',
	 button: [{
    	name: '确定',
        callback: function () {
           art.dialog.open('ecmsclass.php?<?=$ecms_hashur[href]?>&enews=DelClassF&fid='+fid,
    {title: '删除栏目字段',lock: true,opacity: 0.5, width:800, height: 320,
	init: function () {
    	var that = this, i = 2;
        var fn = function () {
            that.title( i + ' 秒后关闭');
            !i && that.close();
            i --;
        };
        timer = setInterval(fn, 1000);
        fn();
    },
	close: function () {
	  clearInterval(timer);
      location.reload();
    }
	});
            return false;
        },
        focus: true
    },
		{
		 name:'取消',	
		}
	]
	})
}
</script>
</head>
<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理栏目字段 <a href="javascript:void(0)" onclick="zjlmzd()" class="add">增加栏目字段</a> <a href="javascript:void(0)" onclick="gllm()" class="gl">管理栏目</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<form name="form1" method="post" action="../ecmsclass.php" onsubmit="return confirm('确认要操作?');">
  <?=$ecms_hashur['form']?>
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>顺序</th>
			<th>字段名</th>
			<th>字段标识</th>
            <th>字段类型</th>
			<th>操作</th>
		</tr>
  <?
  while($r=$empire->fetch($sql))
  {
  	$ftype=$r[ftype];
  	if($r[flen])
	{
		if($r[ftype]!="TEXT"&&$r[ftype]!="MEDIUMTEXT"&&$r[ftype]!="LONGTEXT")
		{
			$ftype.="(".$r[flen].")";
		}
	}
  ?>
		<tr>
			<td><input name="myorder[]" type="text" id="myorder[]" value="<?=$r[myorder]?>" size="3">
          <input type=hidden name=fid[] value=<?=$r[fid]?>></td>
			<td><?=$r[f]?></td>
			<td id="lmzd<?=$r[fid]?>"><?=$r[fname]?></td>
            <td><?=$ftype?></td>
			<td>
        [<a href="javascript:edit(<?=$r[fid]?>)"><strong>修改</strong></a>] &nbsp;
        [<a href="javascript:del(<?=$r[fid]?>)" id="del<?=$r[fid]?>"><strong>删除</strong></a>] </td>
		</tr>
  <?php
  }
  ?>
  		<tr>
		  <td colspan="5" style="text-align:left;">
          <div align="left" class="anniuqun"> &nbsp;&nbsp;
          <input type="submit" name="Submit" value="修改字段顺序" class="anniu">
        <font color="#666666">(值越小越前面)</font> 
        <input name="enews" type="hidden" id="enews" value="EditClassFOrder"> 
        </div>
          </td>
		  </tr>
  		<tr>
  		  <td colspan="5" style="text-align:left;"><p><B>字段调用说明</B></p>
  		    <p>&nbsp;</p>
  		    <p>使用内置调用栏目自定义字段函数：ReturnClassAddField(栏目ID,字段名)，栏目ID=0为当前栏目ID。取多个字段内容可用逗号隔开，例子：<br>
  		      </p>
  		    <p>&nbsp;</p>
  		    <p>取得'classtext'字段内容：$value=ReturnClassAddField(0,'classtext'); //$value就是字段内容。<br>
  		      </p>
  		    <p>&nbsp;</p>
  		    <p>取得多个字段内容：$value=ReturnClassAddField(1,'classid,classtext'); //$value['classtext']才是字段内容。</p></td>
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
<?
db_close();
$empire=null;
?>
