$(document).ready(
	function (){
	// init
	init();
	//load province
	if(show_location)
	{
		loadProvince();
	}
	//
	$(".friends .subject").mouseout(function(){
		$("#00123").hide();
	  });
	$(".friends .subject").mouseover(function(){
		 $("#00123").show();
	  });
	}
);
function getPromptInfo(text)
{
	var str ="<div style='position:relative;z-index:100;background-color:#ffffff;float:left;border:2px solid #CC6600;padding:3px 10px;'>"+text+"</div>";
	return str;
}

function getPromptInfo1(text)
{
	var str ="<div style='position:relative;z-index:900;margin-top:-15px;background-color:#ffffff;float:left;border:2px solid #CC6600;padding:3px 10px;'>"+text+"</div>";
	return str;
}

function loadProvince()
{
	for( index in zone)
	{
		if(parseInt(index) > 82 )break;
		if(userInfo.province == zone[index]['name'])		
			opt = "<option value='"+index+"' selected='true'>"+zone[index]['name']+"</option>";
		else
			opt = "<option value='"+index+"' >"+zone[index]['name']+"</option>";
		$(opt).appendTo($('#province'));
		
	}
	//if user's location exists
	if(userInfo.city)
	{
		//load city and show city
		var index = $('#province')[0].options[$('#province')[0].selectedIndex].value;
		loadCity(index);
		//load country and show country 
		index = $('#city')[0].options[$('#city')[0].selectedIndex].value;
		loadCountry(index);
	}
		
}
function loadCity(value)
{
	$('#city').empty();
	$("<option value='0'>请选择</option>").appendTo($('#city'));
	var city = zone[value];
	for ( index in city )
	{
		if( index == 'name') continue;
		if(userInfo.city == city[index])
			opt = "<option value='"+index+"' selected='true' >"+city[index]+"</option>";
		else
			opt = "<option value='"+index+"' >"+city[index]+"</option>";
		$(opt).appendTo($('#city'));
	}
}
function loadCountry(value)
{
	$('#country').empty();
	$("<option value='0'>请选择</option>").appendTo($('#country'));
	var country = zone[value];
	for ( index in country )
	{
		if( index == 'name' || index == value+'00') continue;
		if(userInfo.country == country[index])
			opt = "<option value='"+index+"' selected='true'>"+country[index]+"</option>";
		else
			opt = "<option value='"+index+"'>"+country[index]+"</option>";
		$(opt).appendTo($('#country'));
	}
}
function checkForm()
{
	if(checkUsername()==1 || checkLocation() ==1 || checkSex()==1 )
		return 1;
	else
		return 0;
}
function checkLocation()
{
	if(!show_location){return 0;}
	if(parseInt($('#province').val())==0 || parseInt($('#city').val())==0 || parseInt($('#country').val())==0)
	{
		//几个特别行政区，除外
		var compared = "31@71@81@82";
		$('#info03').text('');
		if(compared.indexOf($('#province').val())> -1)
		{

			var str = getPromptInfo('ok');
			$(str).appendTo($('#info03'));
			return 0;
		}	
		else
		{

			var str = getPromptInfo('地址不能为空');
			$(str).appendTo($('#info03'));
			return 1;
		}
	}
	else
	{
		$('#info03').text('');
		var str = getPromptInfo('ok');
		$(str).appendTo($('#info03'));
		return 0;
	}
}
function checkSex()
{
	if(!$("input[name='sex']:checked").val())
	{
		$('#info04').text('');
		var str = getPromptInfo('性别不能为空');
		$(str).appendTo($('#info04'));
		return 1;
	}
	else
		return 0;
}
function checkUsername()
{
	if($('#username').val()=="")
	{
		$('#info01').text('');
		var str = getPromptInfo('昵称不能为空');
		$(str).appendTo($('#info01'));
		return 1;
	}
	else
		return 0;
}

function checkEmail()
{
	var value = $('#email').val();
	if(value)
	{
		var reg = /^[a-zA-Z0-9_]+@([a-zA-Z0-9]+\.){1,2}[a-zA-Z0-9]+$/;
		var result = reg.exec(value);
		 if(result)
		 {

			 return 0;
		 }
		 else
		 {
			 $('#info06').text('incorrect type of email');
			 return 1;
		 }
			 
	}
}
function init()
{
	//reselect province
	$('#province').change(function(){
		
		//empty city options
		$('#city').empty();
		$("<option value='0'>请选择</option>").appendTo($('#city'));
		//empty country options
		$('#country').empty();
		$("<option value='0'>请选择</option>").appendTo($('#country'));
		
		var index = this.options[this.selectedIndex].value;
		if(index == 0)
			return ;
		//reload province,clean user's location
		userInfo.city='';
		userInfo.country='';
			
		loadCity(index);
		});
	//reselect city
	$('#city').change(function(){
		var index = this.options[this.selectedIndex].value;
		if(index == 0)
			return ;
		
		loadCountry(index);
		});
	//submit form
	$('#submit01').mousemove(function(){
		$('#submit01').attr('class','move');
	});
	$('#submit01').mouseout(function(){
		$('#submit01').attr('class','');
	});
	$('#submit01').mousedown(function(){
		$('#submit01').attr('class','cilck');
	});
	$('#submit01').click(function(){
		//check form
		if(checkForm())
			return;
		//获得生日和权限
		var birthday = $("#year").find("option:selected").text()+"-"+$("#month").find("option:selected").text()+"-"+$("#date").find("option:selected").text();
		var privacy = $('#pub_name').val()+$('#pub_birth').val()+$('#pub_email').val()+$('#pub_qq').val()+$('#pub_msn').val()+$('#pub_mobile').val();

		if(show_location)
		{
			var location = $("#province").find("option:selected").text()+"-"+$("#city").find("option:selected").text()+"-"+$("#country").find("option:selected").text();
			var location_code=$("#province").val()+"-"+$("#city").val()+"-"+$("#country").val();
		}
		else
		{
			var location = '';
			var location_code = '';
		}
		
		//alert($("input[name='sex']:checked").val());
		
		
		$.ajax({
			url:'userprofile.php',
			type:'post',
			timeout: TIME_OUT,
			cache:true,
			data:{
					a:'submitForm',
					username:$('#username').val(),
					truename:$('#truename').val(),
					email:$('#email').val(),
					qq:$('#qq').val(),
					msn:$('#msn').val(),
					mobile:$('#mobile').val(),
					tv:$('#tv').val(),
					location:location,
					location_code:location_code,
					birthday:birthday,
					sex: $("input[name='sex']:checked").val(),
					privacy:privacy
				},
			error:function(){alert('ajax request error');},
			success:function(html){

					$('#info_prompt').text(html);
					$('#info_prompt').animate({opacity:'show'},2000)
					 .animate({opacity:'toggle'},2000);
					
			}
		});
		});
	//check username
	$('#username').blur(function(){
			if(checkUsername())
				return;
			$.ajax({
					url:'userprofile.php',
					type:'post',
					data:{
						a:'checkUsername',
						username:$('#username').val()
					},
					error:function(){alert('ajax request error');},
					success:function(html){
							$('#info01').text('');
							var str = getPromptInfo(html);
							$(str).appendTo($('#info01'));
						}
				})
				
		});
	$('#email').blur(function(){
		if(checkEmail())
			return;
		$.ajax({
				url:'userprofile.php',
				type:'post',
				data:{
					a:'checkEmail',
					email:$('#email').val()
				},
				error:function(){alert('ajax request error');},
				success:function(html){
						//$('#info06').text('');
					$('#info06').text(html);
						
					}
			})
	});
	
	//
	$('#country').blur(function(){
		checkLocation();
	});
	

}