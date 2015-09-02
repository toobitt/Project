$(function(){
	var isupdate = Number($('input[name="isupdate"]:checked').val());
	if(isupdate)
	$('#credits').toggle();
	var showcredit = Number($('input[name="showcredit"]:checked').val());
	if(showcredit==0)
	$('#credits_rule_diy').toggle();
	
   $(".isupdate").click(function(){
  if($(this).attr("value")=="0")
   $("#credits").show();
  else
   $("#credits").hide();
   });
   $(":text[name='usernamecolor']").bigColorpicker(function(el,color){
		$(el).val(color);
	});

	$('.img-box .img').hover(
		function(){
			$('.del-pic').show();
		},
		function(){
			$('.del-pic').hide();
		}
	);
   $(".del-pic").click(function(){
	   $(".icondel").val("1");
	   $(".img-box").find('img').remove();
	   $('.del-pic').hide();
	});
   $(".showcredit").click(function(){
	   if($(this).attr("value")=="1")
		   $("#credits_rule_diy").show();
		  else
		   $("#credits_rule_diy").hide();
	});
   $("#credits_rules_option").change(function(){
	   var checkValue=$("#credits_rules_option").val();
	   var check_Value=$("#credits_rules_option").find("option:selected").attr('_value');
	   var checkText=$("#credits_rules_option").find("option:selected").text();
	   if(checkValue)
	   {
		  // console.log(check_Value);
			var html='<div id="'+check_Value+'_rules" _data="'+check_Value+'" data="'+checkValue+'" class="form_ul_div clear info rules_del" title="'+checkText+'"><span class="title">'+checkText+': </span><input type="text" name="'+checkValue+'"'+' value="0" style="width:100px;"/><em class="del-creditrules" title="删除此条DIY信息"></em></div>';
			 $("#credits_rule_diy").append(html); 
			 $("#credits_rules_option option[value='"+checkValue+"']").remove();
		   }
   });

   $(".del-creditrules").live('click',function(){
	   var parent=$(this).parent();
	   var data=parent.attr("data");
	   var _data=parent.attr("_data");
	   var id=parent.attr("id");
	   var title=parent.attr("title");
		   $("#"+id).remove();
		 var option= '<option id="'+_data+'" _value="'+_data+'" value="'+data+'">'+title+'</option>';
		   $("#credits_rules_option").append(option);
			var son =  $("#credits_rule_diy").find("div").size();
			if(son==1)
			{
				$('input:radio[name="showcredit"][value="1"]').attr("checked",false);
				$('input:radio[name="showcredit"][value="0"]').attr("checked",true);
				$('#credits_rule_diy').toggle();
				}
});

   var MC = $('.img-box');
	
	MC.on('click' , '.img-upload-btn' , function( e ){
		var self = $( e.currentTarget );
		self.closest('.img-box').find('input[type="file"]').trigger('click');
	});

	MC.on('change' , 'input[type="file"]' , function( e ){
		var self = e.currentTarget,
	   		file = self.files[0],
	   		type = file.type;
		var reader=new FileReader();
		reader.onload=function(event){
			imgData=event.target.result;
			var box = $(self).closest('.img-box').find('.img'),
			img = box.find('img');
			!img[0] && (img = $('<img />').appendTo( box ));
			img.attr('src', imgData);
		}
   	reader.readAsDataURL( file );
	});

});