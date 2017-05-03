$(function(){
	$("input[name='title']").blur(function(){
		var title=$(this).val(),key=$("input[name='keyboard']").val();
		if(title!="" && key==""){
			$.post("/e/extend/keyword/",{title:title},function(data){
					  if(data){
						  $("input[name='keyboard']").val(data);
					  }
            });
		}	
	});
})