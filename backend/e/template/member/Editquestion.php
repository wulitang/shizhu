<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<?php
$public_diyr['pagetitle']='修改资料';
$url="<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>&nbsp;>&nbsp;修改安全信息";
require(ECMS_PATH.'e/template/incfile/header.php');
?>

<div class="hymain">
  <div class="block">
    <? require(ECMS_PATH.'e/template/incfile/leftside.php');?>
    <div class="fr rmain">
      <h3>个人资料 <span class="f12 noBold c999">我们会对您的个人资料隐私加以保密</span></h3>
      <div class="tips" style="position:relative; padding-left:30px;"><i class="icon icon-1"></i> 完善个人档案，不仅可以帮助我们给您提供个性化服务，更方便您在购物中的信息自动处理！ </div>
      <div class="yuer f12 p20 pt5">会员名: <span class="csh">
        <?=$user[username]?>
        </span></div>
      <div class="tab">
        <div class="ddsearch fr"><a href="#" class="c4095ce">隐私申明>></a></div>
        <ul>
          <li><a href="/e/member/EditInfo/">基本信息</a></li>
          <li><a href="/e/member/EditInfo/EditAvator.php">头像照片</a></li>
          <li><a href="/e/member/EditInfo/EditSafeInfo.php">密码修改</a></li>
          <li class="tabhover"><a href="/e/member/EditInfo/Editquestion.php">完善安全问题</a></li>
          <div class="clearfix"></div>
        </ul>
      </div>
      <form name=userinfoform method=post enctype="multipart/form-data" action=../doaction.php>
        <input type=hidden name=enews value=EditSafequestion>
        <div id="edituserxx">
          <table width="100%" align="center" cellpadding="3" cellspacing="0" bgcolor="">
            <tbody>
              <tr>
                <td width="" height="25" bgcolor="" style="width: 70px;">用户名</td>
                <td bgcolor="" width="">&nbsp;&nbsp;<?=$user[username]?></td>
              </tr>
              <tr>
                <td width="" height="25" bgcolor="" style="width: 70px;">安全问题</td>
                <td bgcolor="" width=""><select name="question" class="seletys"><?=$public_r['add_question']?></select></td>
              </tr>
              <tr>
                <td width="" height="25" bgcolor="" style="width: 70px;">安全回答</td>
                <td bgcolor="" width=""><input name='answer' type='text' id='question' size="38" maxlength='20' class="input_text"> (修改安全信息必填项)</td>
              </tr>
              <tr>
                <td width="" height="25" bgcolor="" style="width: 70px;">确认安全回答</td>
                <td bgcolor="" width=""><input name='reanswer' type='text' id='question' size="38" maxlength='20' class="input_text"> (修改安全信息必填项)</td>
              </tr>
            </tbody>
          </table>
          <div class="pl78">
            <input type="submit" name="Submit" value="保存" class="button small gray">
          </div>
        </div>
      </form>
    </div>
    <div class="clearfix"></div>
  </div>
</div>
<?php
require(ECMS_PATH.'e/template/incfile/footer.php');
?>
