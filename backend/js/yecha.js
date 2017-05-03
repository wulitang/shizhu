function yufa(){
art.dialog.open('template/EnewsBq.php<?=$ecms_hashur[whehref]?>',
    {title: '帝国cms标签语法',lock: true,opacity: 0.5, width: 600, height: 450,cancelVal: '关闭',cancel: true});
}
function biaoqian(){
art.dialog.open('template/MakeBq.php<?=$ecms_hashur[whehref]?>',
    {title: '自动生成标签',lock: true,opacity: 0.5, width: 600, height: 540,cancelVal: '关闭',cancel: true});
}
function admindl(){
art.dialog.open('template/MakeBq.php<?=$ecms_hashur[whehref]?>',
    {title: '自动生成标签',lock: true,opacity: 0.5, width: 600, height: 540,cancelVal: '关闭',cancel: true});
}
//修改网站标题
function peizhi(){
art.dialog.open('SetEnews.php<?=$ecms_hashur[whehref]?>',
    {title: '系统参数设置',lock: true,opacity: 0.2, width: 1000, height: 450});
}
//增加信息
function addnews(){
art.dialog.open('AddInfoChClass.php<?=$ecms_hashur[whehref]?>',
    {title: '增加信息',lock: true,opacity: 0.5, width: 800, height: 540});
}
//单页管理
function danye(){
art.dialog.open('template/ListPage.php<?=$ecms_hashur[whehref]?>',
    {title: '修改单页面',lock: true,opacity: 0.5, width: 800, height: 540});
}
//留言管理
function lygl(){
art.dialog.open('tool/gbook.php<?=$ecms_hashur[whehref]?>',
    {title: '管理留言信息',lock: true,opacity: 0.5, width: 800, height: 540});
}
//反馈管理
function fkgl(){
art.dialog.open('tool/feedback.php<?=$ecms_hashur[whehref]?>',
    {title: '管理反馈信息',lock: true,opacity: 0.5, width: 800, height: 540});
}
//友情链接管理
function flinkgl(){
art.dialog.open('tool/ListLink.php<?=$ecms_hashur[whehref]?>',
    {title: '管理友情链接',lock: true,opacity: 0.5, width: 800, height: 540});
}

//简繁转换
$(function(){
	$.getScript("/js/ecmsshop_fanyi.js");
})