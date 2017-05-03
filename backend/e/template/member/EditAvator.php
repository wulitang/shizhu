<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<?php
$public_diyr['pagetitle']='修改资料';
$url="<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>&nbsp;>&nbsp;修改资料";
require(ECMS_PATH.'e/template/incfile/header.php');
$userpic=$addr[userpic]?$addr[userpic]:"/e/data/images/nouserpic.gif";
?>
<script type="text/javascript" src="/e/extend/avator/scripts/swfobject.js"></script>
<script type="text/javascript" src="/e/extend/avator/scripts/fullAvatarEditor.js"></script>
<script type="text/javascript">
swfobject.addDomLoadEvent(function () {
				var swf = new fullAvatarEditor("/e/extend/avator/fullAvatarEditor.swf", "/e/extend/avator/expressInstall.swf", "avator", {
					   id : 'avator',
						upload_url : '/e/extend/avator/savepic.php',	//上传接口
						method : 'post',	//传递到上传接口中的查询参数的提交方式。更改该值时，请注意更改上传接口中的查询参数的接收方式
						src_upload : 0,		//是否上传原图片的选项，有以下值：0-不上传；1-上传；2-显示复选框由用户选择
						avatar_box_border_width : 0,
                        src_upload:0,
                        tab_visible:false,
                        src_url:'<?=$userpic?>',
                        button_cancel_text:'重选图片',
						avatar_sizes : '180*180',
						avatar_sizes_desc : '180*180像素'
					}, function (msg) {
						switch(msg.code)
						{
							case 1 : break;//alert("页面成功加载了组件！");
							case 2 : 
								//alert("已成功加载图片到编辑面板。");
								//document.getElementById("upload").style.display = "inline";
								break;
							case 3 :
								if(msg.type == 0)
								{
									//alert("摄像头已准备就绪且用户已允许使用。");
								}
								else if(msg.type == 1)
								{
									//alert("摄像头已准备就绪但用户未允许使用！");
								}
								else
								{
									//alert("摄像头被占用！");
								}
							break;
							case 5 : 
								if(msg.type == 0)
								{
									if(msg.content.sourceUrl)
									{
										//alert("原图已成功保存至服务器，url为：\n" +　msg.content.sourceUrl+"\n\n" + "头像已成功保存至服务器，url为：\n" + msg.content.avatarUrls.join("\n\n")+"\n\n传递的userid="+msg.content.userid+"&username="+msg.content.username);
									}
									else
									{
										location.reload();
									}
								}
							break;
						}
					}
				);
            });
		</script>
<div class="hymain">
  <div class="block">
    <? require(ECMS_PATH.'e/template/incfile/leftside.php');?>
    <div class="fr rmain">
      <h3>个人资料 <span class="f12 noBold c999">我们会对您的个人资料隐私加以保密</span></h3>
      <div class="tips" style="position:relative; padding-left:30px;"><i class="icon icon-1"></i>
      完善个人档案，不仅可以帮助我们给您提供个性化服务，更方便您在购物中的信息自动处理！
	  </div>
      <div class="yuer f12 p20 pt5">会员名: <span class="csh"><?=$user[username]?></span></div>
      <div class="tab">
        <div class="ddsearch fr"><a href="#" class="c4095ce">隐私申明>></a></div>
        <ul>
          <li><a href="/e/member/EditInfo/">基本信息</a></li>
          <li class="tabhover"><a href="/e/member/EditInfo/EditAvator.php">头像照片</a></li>
          <li><a href="/e/member/EditInfo/EditSafeInfo.php">密码修改</a></li>
          <div class="clearfix"></div>
        </ul>
      </div>
      <div id="edituserxx">
<div id="avator"></div>
	  
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
</div>
<?php
require(ECMS_PATH.'e/template/incfile/footer.php');
?>