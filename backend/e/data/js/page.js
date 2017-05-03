var color1="#6F6F6F";
var color2="#000000";
var color3="#FFF";
function HHTPage1(countP,currentPageP,totalPageP){
	this.leave=5;
	this.indexHeight="20";
	this.indexWidth="25";
	this.fontSize="13";
	this.init=function(){
		var count=countP;
		var currentPage=currentPageP;
		var totalPage=totalPageP;
		var leave=this.leave;
		var ecmspageStr="";
		var startIndex=1;
		if(currentPage>(leave-(-1)))startIndex=currentPage-leave;
		var endIndex=startIndex-(-2*leave);
		if(endIndex>totalPage)endIndex=totalPage;
		if(endIndex-startIndex<2*leave)startIndex=endIndex-2*leave;
		if(startIndex<1)startIndex=1;
		uppage=currentPage-2;
		if(uppage<0)uppage=0;
		ecmspageStr=ecmspageStr+'共<label style="color:'+color1+';">'+count+'</label>条&nbsp;';
		ecmspageStr=ecmspageStr+'第<label style="color:'+color1+';">'+currentPage+'/'+totalPage+'</label>页&nbsp;';
		ecmspageStr=ecmspageStr+'<a href="'+URL+0+Search+'" class="first paginate_button">首页</a>';
		ecmspageStr=ecmspageStr+'<a href="'+URL+uppage+Search+'" class="previous paginate_button">上一页</a>';
		for(var i=startIndex;i<=endIndex;i++){
			if(i==currentPage)ecmspageStr=ecmspageStr+'<a class="paginate_active">'+i+'';
			else ecmspageStr=ecmspageStr+'<a href="'+URL+(i-1)+Search+'" class="paginate_button">'+i+'</a>'
		};
		if(currentPage==totalPageP)currentPage=totalPageP-1;
		ecmspageStr=ecmspageStr+'<a href="'+URL+(currentPage)+Search+'" class="next paginate_button">下一页</a>';
		ecmspageStr=ecmspageStr+'<a href="'+URL+(totalPage-1)+Search+'" class="last paginate_button">尾页</a>';
		ecmspageStr=ecmspageStr+'第<input id="hhtPage1IndexGoto" maxlength="4" onkeypress="javascript:key=event.keyCode;if(key>0x39||key<0x30)return false;"/>页';
		ecmspageStr=ecmspageStr+'<a href="javascript:gotoPageIndex();" class="paginate_button">GO</a>';
		document.all.ecmspage.innerHTML=ecmspageStr;
		document.all.hhtPage1IndexGoto.value=currentPage
	}
};
function a_over(obj){
	obj.style.color=color1;
	obj.style.textDecoration="underline"
};
function a_out(obj){
	obj.style.color=color2;
	obj.style.textDecoration="none"
};
function gotoPageIndex(){
	location.href=basePath+URL+(document.all.hhtPage1IndexGoto.value-1)+Search
}