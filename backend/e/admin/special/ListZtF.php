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
CheckLevel($logininid,$loginin,$classid,"ztf");
$url="<a href='ListZt.php".$ecms_hashur['whehref']."'>管理专题</a>&nbsp;>&nbsp;<a href='ListZtF.php".$ecms_hashur['whehref']."'>管理专题字段</a>";
$sql=$empire->query("select * from {$dbtbpre}enewsztf order by myorder,fid");
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
//增加专题字段
function zjztzd(){
art.dialog.open('special/AddZtF.php?enews=AddZtF<?=$ecms_hashur['ehref']?>',
    {title: '增加专题字段',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//管理专题
function glzt(){
art.dialog.open('special/ListZt.php<?=$ecms_hashur['whehref']?>',
    {title: '管理专题',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//修改专题字段
function edit(obj){
art.dialog.open('special/AddZtF.php?<?=$ecms_hashur[ehref]?>&enews=EditZtF&fid='+obj,
    {title: '修改专题字段',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//删除专题字段
function del(fid){
	var classname=document.getElementById('lmzd'+fid).innerHTML;
	var dialog=art.dialog({
	 title:'删除警告',
	 follow: document.getElementById('del'+fid),
	 content: '你确定要删除 "'+classname+'" 栏目字段么?',
	 button: [{
    	name: '确定',
        callback: function () {
           art.dialog.open('ecmsclass.php?<?=$ecms_hashur[href]?>&enews=DelZtF&fid='+fid,
    {title: '删除专题字段',lock: true,opacity: 0.5, width:800, height: 320,
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
        	<h3><span>管理专题字段： <a href="javascript:void(0)" onclick="zjztzd()" class="add">增加专题字段</a> <a href="javascript:void(0)" onclick="glzt()" class="gl">管理专题</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<form name="form1" method="post" action="../ecmsclass.php" onsubmit="return confirm('确认要操作?');">
<table class="comm-table" cellspacing="0">
  <?=$ecms_hashur['form']?>
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
    <tr bgcolor="ffffff"> 
      <td height="25"><div align="center"> 
          <input name="myorder[]" type="text" id="myorder[]" value="<?=$r[myorder]?>" size="3">
          <input type=hidden name=fid[] value=<?=$r[fid]?>>
        </div></td>
      <td height="25"><div align="center"> 
          <?=$r[f]?>
        </div></td>
      <td><div align="center" id="lmzd<?=$r[fid]?>"> 
          <?=$r[fname]?>
        </div></td>
      <td><div align="center">
	  	  <?=$ftype?>
	  </div></td>
			<td>
       [<a href="javascript:edit(<?=$r[fid]?>)"><strong>修改</strong></a>] &nbsp;
        [<a href="javascript:del(<?=$r[fid]?>)" id="del<?=$r[fid]?>"><strong>删除</strong></a>]</td>
		</tr>
  <?php
  }
  ?>
  		<tr>
		  <td colspan="7" style="text-align:left;">
          <div align="left" class="anniuqun"> &nbsp;&nbsp;
          <input type="submit" name="Submit" value="修改字段顺序">
        <font color="#666666">(值越小越前面)</font> 
        <input name="enews" type="hidden" id="enews" value="EditZtFOrder"> 
        </div>
          </td>
		  </tr>
  		<tr>
  		  <td colspan="7" style="text-align:left;">
            <p><b>字段调用说明</b></p>
            <p>&nbsp;</p>
            <p>使用内置调用专题自定义字段函数：ReturnZtAddField(专题ID,字段名)，专题ID=0为当前专题ID。取多个字段内容可用逗号隔开，例子：<br>
              </p>
            <p>&nbsp;</p>
            <p>取得'classtext'字段内容：$value=ReturnZtAddField(0,'classtext'); //$value就是字段内容。<br>
              </p>
            <p>&nbsp;</p>
            <p>取得多个字段内容：$value=ReturnZtAddField(1,'ztid,classtext'); //$value['classtext']才是字段内容。 </p></td>
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
