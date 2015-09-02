$(document).ready(function(){

	var register_flag = true;
	/*用户登录注册*/
	if($(".account").size()){
	
		$(".email_field, .username_field, .password_field").find("input").focus(function(){
			$(this).parent().addClass("active").end().siblings(".tip_message").show().siblings(".error_message").hide();
			if(this.defaultValue==$(this).val()){
				$(this).val("");
			}
			
		}).blur(function(){
			$(this).parent().removeClass("active");			
			if($.trim($(this).val()) == ""){$(this).val(this.defaultValue);}
			
			switch($.trim($(this).attr('name')))
			{
				case 'username':
					$.ajax({
			            url: "register.php",
			            type: 'POST',
			            dataType: 'html',
			   			timeout: TIME_OUT,
			   			cache: false,
			            data: {
			            	member_name: $(this).val(),
				        	a: "verify_account"
				        	},
			            error: function() {
				        	alert('网络延迟！');
			            },
			            success: function(json) {
				            if(json != 'false')
				            {
					            var obj = new Function("return" + json)();
					            if(obj.member_name)
					            {
						            $(".username_field input").focus().siblings(".tip_message").hide().siblings(".error_message").show().html("帐号已存在");
						            register_flag = false;
					            }
				            }
				            else
				            {
					            $(".username_field input").siblings(".tip_message").show().siblings(".error_message").hide();
						        register_flag = true;
				            }
						}
				     });
					
				break;
				case 'email':
					$.ajax({
			            url: "register.php",
			            type: 'POST',
			            dataType: 'html',
			   			timeout: TIME_OUT,
			   			cache: false,
			            data: {
			            	email: $(this).val(),
				        	a: "verify_email"
				        	},
			            error: function() {
				        	alert('网络延迟！');
			            },
			            success: function(json) {
			            
				            if(json != 'false')
				            {
					            var obj = new Function("return" + json)();
					            if(obj.email)
					            {
						            $(".email_field input").focus().siblings(".tip_message").hide().siblings(".error_message").show().html("邮箱已存在");
						            register_flag = false;
					            }
				            }
				            else
				            {
					            $(".email_field input").siblings(".tip_message").show().siblings(".error_message").hide();
					            register_flag = true;
				            }
						}
				     });
				break;
				default:
				break;
			}
			
				
			
		});

		$(".other_field label").click(function(){
			var agreeProtocol=$(".other_field input").attr("checked");
			$(".other_field input").attr("checked", !agreeProtocol);
			if(agreeProtocol){
				$(this).parent().removeClass("ckecked");
			}else{
				$(this).parent().addClass("ckecked").find(".error_message").hide();
			}
		});
		
		
		$(".register_form form").bind("submit",function(e){
			var pattern = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
			if(!pattern.test($.trim($(".email_field input").val())) || !$.trim($(".email_field input").val())){
				$(".email_field input").focus().siblings(".tip_message").hide().siblings(".error_message").show().html("邮箱格式不正确");
				e.preventDefault();	return false;
			}
	
			
			/*if($.trim($(".username_field input").val())== "" || $(".username_field input").val().length<6 || $(".username_field input").val().length >14){
				$(".username_field input").focus().siblings(".tip_message").hide().siblings(".error_message").show().html("用户名格式不正确");
				e.preventDefault();	return false;
			}*/

            var username = $.trim($(".username_field input").val());
            var userError = false;
            if(!username){
                userError = true;
            }else{
                var num = 0;
                for(var i = 0, len = username.length; i < len; i++){
                    if(/[\u4e00-\u9fa5]/.test(username[i])){
                        num += 2
                    }else{
                        num++;
                    }
                }
                if(num < 6 || num > 14){
                    userError = true;
                }
            }
            
            if(userError){
                $(".username_field input").focus().siblings(".tip_message").hide().siblings(".error_message").show().html("用户名格式不正确");
                return false;
            }
			
						
			if($.trim($(".password_field input").val())== "" || $(".password_field input").val().length<6 ){
				$(".password_field input").focus().siblings(".tip_message").hide().siblings(".error_message").show().html("密码格式不正确");
				e.preventDefault();
				return false;
			}
			
			if($('#select_group_type').val() == 'default') {
				$('.xuanbeizhu').addClass('xuanbeizhu_s');
				e.preventDefault();
				return false;
			}
		
			if(!$(".agree_protocol").attr("checked")){
				$(".other_field .error_message").show().html("同意协议才能注册");
				e.preventDefault();
				return false;
			}
			
			if(!register_flag)
			{
				return false;
			}
		});
		
		var tips = {
			'default' : '选择个人、家庭或者团体',
			'personal' : '主要指大中学生、年轻白领等独立个体',
			'family' : '主要指中小学生及其家庭',
			'group' : '主要包括政府机构、社会化团体、媒体、学校、商业品牌等'
		};
		$('#select_group_type').change(function() {
			var key = $(this).val();
			$('.xuanbeizhu').removeClass('xuanbeizhu_s');
			$('.xuanbeizhu').html(tips[key]);
		});

		$(".login_form form").bind("submit",function(e){		
			/*
			var pattern = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
			if(!pattern.test($.trim($(".email_field input").val()))){
				$(".error_message").html("邮箱格式不正确");
				$(".email_field input").focus();			
				e.preventDefault();	return false;
			}*/
			if($.trim($(".username_field input").val())== "" || $(".username_field input").val().length<6 || $(".username_field input").val().length >14){
				$(".username_field input").focus();
				$(".error_message").html("帐号格式不正确");
				e.preventDefault();
				return false;
			}
						
			if($.trim($(".password_field input").val())== "" || $(".password_field input").val().length<6 ){
				$(".password_field input").focus();
				$(".error_message").html("密码格式不正确");
				e.preventDefault();
				return false;
			}

		});


	}/*用户登录注册*/


});