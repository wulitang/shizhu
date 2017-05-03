<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<?php
$public_diyr['pagetitle']='在线支付';
$url="<a href='../../'>首页</a>&nbsp;>&nbsp;<a href=../member/cp/>会员中心</a>&nbsp;>&nbsp;在线支付";
require(ECMS_PATH.'e/template/incfile/header.php');
?>
<script>
$(function(){
	$("input[name='money']").blur(function(){
		var a=$(this).val();
		if(a){
			var point=a*<?=$pointr[paymoneytofen]?>;
			$("#showbuyfen").html("&nbsp;&nbsp;"+point+"点");
		}
	});	
})
</script>

<div class="hymain">
  <div class="block">
    <? require(ECMS_PATH.'e/template/incfile/leftside.php');?>
    <div class="fr rmain">
      <h3>账户信息</h3>
      <div class="yuer f12 p20">会员等级: <span class="csh"><?=$tmgetgroupname?></span><span class="ml30">可用余额: <span class="csh f20">￥<?=number_format($user[money],2)?></span></span> <span class="ml30">剩余积分: <span class="csh f20"><?=$user[userfen]?></span></span></div>
      <div class="tab">
        <div class="ddsearch fr"><a href="#" class="c4095ce">充值帮助>></a></div>
        <ul>
          <li><a href="/e/payapi/">充值余额</a></li>
          <li class="tabhover"><a href="/e/payapi/buypoint.php">充值点数</a></li>
          <li><a href="/e/member/buybak/">帐户明细</a></li>
          <div class="clearfix"></div>
        </ul>
      </div>
      <form name="paytofenform" method="post" action="pay.php">
      <input type="hidden" name="phome" value="PayToFen">
      <div id="edituserxx">
          <table width="100%" align="center" cellpadding="3" cellspacing="0" bgcolor="">
            <tbody>
              <tr>
                <td width="" height="25" bgcolor="" style="width: 100px;">用户名</td>
                <td bgcolor="" width="">&nbsp;&nbsp;<?=$user[username]?></td>
              </tr>
              <tr>
                <td width="" height="25" bgcolor="" style="width: 100px;">购买金额</td>
                <td bgcolor="" width=""><input name="money" type="text" value="" size="8" onchange="ChangeShowBuyFen(document.paytofenform.money.value);">
        元 <font color="#333333">( 1元 = 
        <?=$pointr[paymoneytofen]?>
        点数)</font></td>
              </tr>
              <tr>
                <td width="" height="25" bgcolor="" style="width: 100px;">获得点数</td>
                <td bgcolor="" width="" id="showbuyfen">&nbsp;&nbsp;0 点</td>
              </tr>
            </tbody>
          </table>
        </div>
      <div id="chongzhi" class="pl20">
                <input name="payid" type="hidden" value="3">
                <ul>
                    <li class="payfs"><span class="fl pr30">支付方式：</span><a href="javascript:void();" class="hover" payfs="3"><span></span><img src="/eshop/skin/pay/zfb.jpg"></a> <a href="javascript:void();" payfs="1"><span></span><img src="/eshop/skin/pay/cft.jpg"></a> <a href="javascript:void();" payfs="2"><span></span><img src="/eshop/skin/pay/wyzx.jpg"></a><div class="clearfix"></div></li>
                    <li class="center"><input type="submit" name="Submit" value="确定支付" class="rbutton"></li>
                </ul>
            </form>
        </div>
    </div>
    <div class="clearfix"></div>
  </div>
</div>
<?php
require(ECMS_PATH.'e/template/incfile/footer.php');
?>