<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<?php
//查询SQL，如果要显示自定义字段记得在SQL里增加查询字段
$query="select id,classid,isurl,titleurl,isqf,havehtml,istop,isgood,firsttitle,ismember,userid,username,plnum,totaldown,onclick,newstime,truetime,lastdotime,titlepic,title from {$dbtbpre}ecms_".$class_r[$classid][tbname]."_doc".$ewhere." order by ".$doorder." limit $offset,$line";
$sql=$empire->query($query);
//返回头条和推荐级别名称
$ftnr=ReturnFirsttitleNameList(0,0);
$ftnamer=$ftnr['ftr'];
$ignamer=$ftnr['igr'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理归档</title>
<link rel="stylesheet" type="text/css" href="adminstyle/1/yecha/yecha.css" />
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="adminstyle/<?=$loginadminstyleid?>/js/ui.tab.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type="text/javascript" src="/js/yecha.js"></script>
<script>
function senfe(o,a,b,c,d){
 var t=document.getElementById(o).getElementsByTagName("tr");
 for(var i=0;i<t.length;i++){
  t[i].style.backgroundColor=(t[i].sectionRowIndex%2==0)?a:b;
  t[i].onmouseover=function(){
   if(this.x!="1")this.style.backgroundColor=c;
  }
  t[i].onmouseout=function(){
   if(this.x!="1")this.style.backgroundColor=(this.sectionRowIndex%2==0)?a:b;
  }
 }
}
function CheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
  }
</script>
</head>
<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?></div>
<div style=" background:none; float:right; padding-right:20px;"></div>
</div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>信息列表 <a href="ListNews.php?bclassid=<?=$bclassid?>&classid=<?=$classid?><?=$ecms_hashur['ehref']?>" title="已发布信息总数：<?=$classinfos?>" style=" margin-left:10px;" class="gl">已发布</a>
            <a href="ListNews.php?bclassid=<?=$bclassid?>&classid=<?=$classid?>&ecmscheck=1<?=$ecms_hashur['ehref']?>" title="待审核信息总数：<?=$classckinfos?>" class="gl">待审核</a>
            <a href="ListInfoDoc.php?bclassid=<?=$bclassid?>&classid=<?=$classid?><?=$addecmscheck?><?=$ecms_hashur['ehref']?>" class="glon">归档</a></span></h3>	  
            <div class="line"></div>
           	<div class="saixuan">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr> 
      <td>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td> <div align="left"  class="anniuqun"> 
                  <input type=button name=button value=增加信息 onClick="self.location.href='AddNews.php?enews=AddNews&bclassid=<?=$bclassid?>&classid=<?=$classid?><?=$addecmscheck?><?=$ecms_hashur['ehref']?>'">
                  &nbsp; 
                  <input type="button" name="Submit" value="刷新首页" onclick="self.location.href='ecmschtml.php?enews=ReIndex<?=$ecms_hashur['href']?>'">
                  &nbsp; 
                  <input type="button" name="Submit22" value="刷新本栏目" onclick="self.location.href='enews.php?<?=$ecms_hashur['href']?>&enews=ReListHtml&classid=<?=$classid?>'">
                  &nbsp; 
                  <input type="button" name="Submit4" value="刷新所有信息JS" onclick="window.open('ecmschtml.php?<?=$ecms_hashur['href']?>&enews=ReAllNewsJs&from=<?=$phpmyself?>','','');">
                  &nbsp; 
                  <input type="button" name="Submit10" value="栏目设置" onclick="self.location.href='AddClass.php?<?=$ecms_hashur['ehref']?>&enews=EditClass&classid=<?=$classid?>'">
                  &nbsp; 
                  <input type="button" name="Submit102" value="增加采集节点" onclick="self.location.href='AddInfoClass.php?enews=AddInfoClass&newsclassid=<?=$classid?><?=$ecms_hashur['ehref']?>'">
                  &nbsp; 
                  <input type="button" name="Submit103" value="管理采集节点" onclick="self.location.href='ListInfoClass.php<?=$ecms_hashur['whehref']?>'">
              </div></td>
          </tr>
        </table>
      </td>
      <td>
<div align="right">
<form name="form2" method="GET" action="ListInfoDoc.php">
  <?=$ecms_hashur['eform']?>
          搜索: <input name="keyboard" type="text" id="keyboard2" value="<?=$keyboard?>" size="16">
          <select name="show">
            <option value="0" selected>不限字段</option>
            <?=$searchoptions_r['select']?>
          </select>
		  <?=$stts?>
          <input type="submit" name="Submit2" value="搜索" class="anniu">
          <input name="sear" type="hidden" id="sear2" value="1">
          <input name="bclassid" type="hidden" id="bclassid" value="<?=$bclassid?>">
          <input name="classid" type="hidden" id="classid" value="<?=$classid?>">
		  <input name="ecmscheck" type="hidden" id="ecmscheck" value="<?=$ecmscheck?>">
        </div></td>
    </tr>
  </form>
</table>      
            </div>
<form name="listform" method="post" action="ecmsinfo.php" onsubmit="return confirm('确认要执行此操作？');">
<?=$ecms_hashur['form']?>
<input type=hidden name=classid value=<?=$classid?>>
<input type=hidden name=bclassid value=<?=$bclassid?>>
<input type=hidden name=enews value=DelNews_all>
<input type=hidden name=doing value=0>
  <input name="ecmsdoc" type="hidden" id="ecmsdoc" value="0">
  <input name="docfrom" type="hidden" id="docfrom" value="<?=$phpmyself?>">
  <input name="ecmscheck" type="hidden" id="ecmscheck" value="<?=$ecmscheck?>">
  <div class="xwlistmenu">
  	<table>
<tr> 
            <td width="60%"><font color="#ffffff">[<a href="InfoDoc.php<?=$ecms_hashur['whehref']?>" target="_blank">批量归档信息</a>]&nbsp;[<a href="ReHtml/ChangeData.php<?=$ecms_hashur['whehref']?>" target=_blank>更新数据</a>]&nbsp;[<a href=../../ target=_blank>预览首页</a>]&nbsp;[<a href="<?=$classurl?>" target=_blank>预览栏目</a>]</font> 
            </td>
          </tr>
        </table></div></td>
    </tr>
    </table>
  
<table class="comm-table2" cellspacing="0" id="changecolor">
	<tbody>
		<tr>
        	<th width="6%" height="25"> <div align="center">选择</div></th>
			<th width="7%" height="25"><div align="center"><a href='ListInfoDoc.php?<?=$search1?>&myorder=4'><u>ID</u></a></div></th>
            <th width="47%" height="25"> <div align="center">标题</div></th>
            <th width="18%" height="25"><div align="center">发布者</div></th>
            <th width="22%" height="25"> <div align="center"><a href='ListInfoDoc.php?<?=$search1?>&myorder=1'><u>发布时间</u></a></div></th>
		</tr>
    <?php
	while($r=$empire->fetch($sql))
	{
		//状态
		$st='';
		if($r[istop])//置顶
		{
			$st.="<font color=red>[顶".$r[istop]."]</font>";
		}
		if($r[isgood])//推荐
		{
			$st.="<font color=red>[".$ignamer[$r[isgood]-1]."]</font>";
		}
		if($r[firsttitle])//头条
		{
			$st.="<font color=red>[".$ftnamer[$r[firsttitle]-1]."]</font>";
		}
		//时间
		$truetime=date("Y-m-d H:i:s",$r[truetime]);
		$lastdotime=date("Y-m-d H:i:s",$r[lastdotime]);
		$oldtitle=$r[title];
		$r[title]=stripSlashes(sub($r[title],0,50,false));
		$titleurl=sys_ReturnBqTitleLink($r);
		//会员投稿
		if($r[ismember])
		{
			$r[username]="<a href='member/AddMember.php?enews=EditMember&userid=".$r[userid].$ecms_hashur['ehref']."' target='_blank'><font color=red>".$r[username]."</font></a>";
		}
		//签发
		$qf="";
		if($r[isqf])
		{
			$qfr=$empire->fetch1("select checktno,tstatus from {$dbtbpre}enewswfinfo where id='$r[id]' and classid='$r[classid]' limit 1");
			if($qfr[checktno]=='100')
			{
				$qf="(<font color='red'>已通过</font>)";
			}
			elseif($qfr[checktno]=='101')
			{
				$qf="(<font color='red'>返工</font>)";
			}
			elseif($qfr[checktno]=='102')
			{
				$qf="(<font color='red'>已否决</font>)";
			}
			else
			{
				$qf="(<font color='red'>$qfr[tstatus]</font>)";
			}
			$qf="<a href='#ecms' onclick=\"window.open('workflow/DoWfInfo.php?classid=$r[classid]&id=$r[id]".$addecmscheck.$ecms_hashur['ehref']."','','width=600,height=520,scrollbars=yes');\">".$qf."</a>";
		}
		//标题图片
		$showtitlepic="";
		if($r[titlepic])
		{
			$showtitlepic="<a href='".$r[titlepic]."' title='预览标题图片' target=_blank><img src='../data/images/showimg.gif' border=0></a>";
		}
		//未生成
		$myid=$r['id'];
		if(empty($r[havehtml]))
		{
		$myid="<a title='未生成'><b>".$r[id]."</b></a>";
		}
	?>
    <tr id=news<?=$r[id]?>> 
    <td height="25"> <div align="center"> 
          <input name="id[]" type="checkbox" id="id[]" value="<?=$r[id]?>">
    </div></td>
      <td height="32"> <div align="center"> 
          <?=$myid?>
        </div></td>
      <td height="25"> <div align="left"> 
          <?=$st?>
          <?=$showtitlepic?>
          <a href='<?=$titleurl?>' target=_blank title="<?=$oldtitle?>"> 
          <?=$r[title]?>
          </a> 
          <?=$qf?>
        </div></td>
      <td height="25"> <div align="center"> 
          <?=$r[username]?>
        </div></td>
      <td height="25"> <div align="center"> <a href="#ecms" title="<? echo"增加时间：".$truetime."\r\n最后修改：".$lastdotime;?>"> 
          <?=date("Y-m-d H:i:s",$r[newstime])?>
          </a> </div></td>
    </tr>
    <?
	}
	?>
    <tr bgcolor="#FFFFFF" class="anniuqun"> 
    	<td align="center"><input type=checkbox name=chkall value=on onClick="CheckAll(this.form)"></td>
      <td colspan="4" height="32">
      <div><span id="moveclassnav"></span>
       <input type="submit" name="Submit3" value="删除" onClick="document.listform.enews.value='DelInfoDoc_all';document.listform.action='ecmsinfo.php';">
                <input type="submit" name="Submit11" value="还原归档" onClick="document.listform.enews.value='InfoToDoc';document.listform.doing.value='1';document.listform.action='ecmsinfo.php';">
        </div>
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="5" style=" height:35px; overflow:hidden; padding:0; margin:0;background:#F2F2F2; padding-top:10px;"> 
        <?=$returnpage?>
      　 </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="8"><font color="#666666">备注：发布者红色为会员投稿；信息ID粗体为未生成.</font></td>
    </tr>
	</tbody>
</table>
</form>
        </div>
        <div class="line"></div>
    </div>
</div>
</div>
<script language="javascript">
senfe("changecolor","#F2F2F2","#F7F7F7","","#bce774");
</script>
</body>
</html>