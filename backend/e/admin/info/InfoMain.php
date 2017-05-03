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

//今日信息
$pur=$empire->fetch1("select lasttimeinfo,lastnuminfo,lastnuminfotb,todaytimeinfo,todaytimepl,todaynuminfo,yesterdaynuminfo from {$dbtbpre}enewspublic_update limit 1");
//更新昨日信息
$todaydate=date('Y-m-d');
if(date('Y-m-d',$pur['todaytimeinfo'])<>$todaydate||date('Y-m-d',$pur['todaytimepl'])<>$todaydate)
{
	DoUpdateYesterdayAddDataNum();
	$pur=$empire->fetch1("select lasttimeinfo,lastnuminfo,lastnuminfotb,todaytimeinfo,todaytimepl,todaynuminfo,yesterdaynuminfo from {$dbtbpre}enewspublic_update limit 1");
}
$sql=$empire->query("select tid,tbname,tname,isdefault from {$dbtbpre}enewstable order by tid");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>信息</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script language="javascript">
function senfe(o,a,b,c,d){
 var t=document.getElementById(o).getElementsByTagName("tr");
 for(var i=0;i<t.length;i++){
  t[i].style.backgroundColor=(t[i].sectionRowIndex%2==0)?a:b;
  t[i].onclick=function(){
   if(this.x!="1"){
    this.x="1";
    this.style.backgroundColor=d;
   }else{
    this.x="0";
    this.style.backgroundColor=(this.sectionRowIndex%2==0)?a:b;
   }
  }
  t[i].onmouseover=function(){
   if(this.x!="1")this.style.backgroundColor=c;
  }
  t[i].onmouseout=function(){
   if(this.x!="1")this.style.backgroundColor=(this.sectionRowIndex%2==0)?a:b;
  }
 }
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="InfoMain.php<?=$ecms_hashur['whehref']?>">信息统计</a></div></div>
<div class="kongbai"></div>
    <div id="tab">
	<div class="ui-tab-container">
	<ul class="clearfix ui-tab-list">
		<li class="ui-tab-active"><a href="../info/InfoMain.php<?=$ecms_hashur['whehref']?>">信息统计</a></li>
		<li><a href="../pl/PlMain.php<?=$ecms_hashur['whehref']?>">评论统计</a></li>
		<li><a href="../other/OtherMain.php<?=$ecms_hashur['whehref']?>">其他统计</a></li>
	</ul>
	<div class="ui-tab-bd">
<table class="comm-table2" cellspacing="0" id="changecolor">
<form name="form1" method="post" action="">
	<tbody>
		<tr>
		  <th colspan="6" align="left" style="padding-left:10px;">信息发布统计 (今日信息数：<?=$pur['todaynuminfo']?>，昨天信息数：<?=$pur['yesterdaynuminfo']?>)</th>
		  </tr>
		<tr>
			<th>表名</th>
			<th>已审核</th>
			<th>待审核</th>
            <th>未审核投稿</th>
			<th>总数</th>
            <th>从 
     <font color="#0360AF"><?=date('Y-m-d H:i:s',$pur['lasttimeinfo'])?></font>
    截止至现在的新增数量</th>
		</tr>
  <?php
	  $i=0;
	  $alltbinfos=0;
	  while($tr=$empire->fetch($sql))
	  {
	  	$i++;
		$bgcolor='#FFFFFF';
		if($i%2==0)
		{
			$bgcolor='';
		}
		$thistb=$tr['tid'];
		$infotbname='ecms_'.$tr['tbname'];
		$tbinfos=eGetTableRowNum($dbtbpre.$infotbname);//已审核
		$checkinfotbname='ecms_'.$tr['tbname'].'_check';
		$checktbinfos=eGetTableRowNum($dbtbpre.$checkinfotbname);//未审核
		$qchecktbinfos=$empire->gettotal("select count(*) as total from ".$dbtbpre.$checkinfotbname." where ismember=1");//投稿数
		$alltbinfos=$tbinfos+$checktbinfos;//总数
		$tname=$tr['tname'];
		if($tr['isdefault'])
		{
			$tname='<b>'.$tname.'</b>';
		}
		$exp='|'.$thistb.',';
		$addnumr=explode($exp,$pur['lastnuminfotb']);
		$addnumrt=explode('|',$addnumr[1]);
		$addnum=(int)$addnumrt[0];
		$totaltbinfos+=$tbinfos;
		$totalchecktbinfos+=$checktbinfos;
		$totalqchecktbinfos+=$qchecktbinfos;
		$totalalltbinfos+=$alltbinfos;
	  ?>
 <tr bgcolor="<?=$bgcolor?>"> 
    <td height="25" align="center">
		<div align="center"><a href="../ListAllInfo.php?tbname=<?=$tr['tbname']?><?=$ecms_hashur['ehref']?>" title="*<?=$infotbname?>" target="_blank">
	    <?=$tname?>
	    </a></div></td>
    <td align="center"><div><a href="../ListAllInfo.php?tbname=<?=$tr['tbname']?><?=$ecms_hashur['ehref']?>" target="_blank"><?=$tbinfos?></a></div></td>
    <td align="center"><div><a href="../ListAllInfo.php?tbname=<?=$tr['tbname']?>&ecmscheck=1<?=$ecms_hashur['ehref']?>" target="_blank"><?=$checktbinfos?></a></div></td>
    <td align="center"><a href="../ListAllInfo.php?tbname=<?=$tr['tbname']?>&ecmscheck=1&sear=1&showspecial=7<?=$ecms_hashur['ehref']?>" target="_blank"><?=$qchecktbinfos?></a></td>
    <td align="center"><?=$alltbinfos?></td>
    <td align="center"><?=$addnum?></td>
	</td>
  </tr>
    <?
	}
	?>
 		<tr align="center">
   <td height="25"><div>总计：</div></td>
    <td><div><?=$totaltbinfos?></div></td>
    <td><div><?=$totalchecktbinfos?></div></td>
    <td><?=$totalqchecktbinfos?></td>
    <td><?=$totalalltbinfos?></td>
    <td><?=$pur['lastnuminfo']?></td>
    </tr>
 		<tr>
 		  <td colspan="6" style="padding:10px;"><input type="button" name="Submit" value="重置截止统计" onClick="if(confirm('确认要重置信息数统计?')){self.location.href='../ecmscom.php?enews=ResetAddDataNum&type=info&from=info/InfoMain.php<?=urlencode($ecms_hashur['whehref'])?><?=$ecms_hashur['href']?>';}" class="anniu"> <font color="#666666">说明：点击"已审核"、"未审核"或"未审核投稿"数可进入相应的管理。</font></td>
 		  </tr>
	</tbody>
  </form>
</table>
 <div class="line"></div>
  </div>
 </div>
</div>
 <div class="clear"></div>
 </div>
  <script language="javascript">
senfe("changecolor","#FFFFFF","#F0F0F0","#bce774","#bce774");
</script>
</body>
</html>
<?php
db_close();
$empire=null;
?>