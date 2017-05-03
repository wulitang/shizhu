$(function(){
  $(".ui-tab-list li").click(function(){
    $(".ui-tab-list li").removeClass("ui-tab-active");
    $(this).addClass("ui-tab-active");
    var num=$(this).index();
    $(".ui-tab-bd .ui-tab-content").hide();
    $(".ui-tab-bd .ui-tab-content").eq(num).show();
  });
})