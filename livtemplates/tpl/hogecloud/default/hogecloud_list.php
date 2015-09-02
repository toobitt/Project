{template:head}
{css:2013/iframe}
{js:jquery.cookie}
{js:2013/ajaxload_new}
{code}
	//print_r($list);
	//echo $user_id;
{/code}
<style type="text/css">
.source_item {cursor:pointer; border:1px solid #CCC; display:inline-block; padding:3px 5px; margin:5px;}
</style>

<style>
.person-title{text-indent:30px;padding-bottom:6px;margin:16px 0 0 10px; border-bottom:1px dotted #e0e0e0;}
.person-title .website{color:#6ea5e8; font-size:18px; }

.person-area{width:950px; margin:40px auto 50px; min-height:500px; }

.person-area .main-con .item{height:48px; margin-bottom:15px; border-bottom:1px dotted #e0e0e0; }
.person-area .item input{height: 27px;border-radius: 3px;}
.person-area .main-con .name{display:inline-block; width:120px; margin-right:15px; color:#333; font-size:14px; text-align:right;}

.person-area .person-save{margin: 0px 0 30px 140px;}
.important{float:none;margin-left: 10px;}
#mibao_info_box{width:100%;position:relative;}
.pass-card{position:absolute;height:300px;right:220px;top:170px;width:103px;}
.pass-card a{margin-bottom:22px;}

.modal{display:none; }
.modal .inner{text-align:center; margin-top:20px; font-size:14px; }
.modal .referto{width:0; height:0; line-height:0; font-size:0; }
</style>
<div class="wrap clear">
	<form action="" method="post" enctype="multipart/form-data">
		<h2 class="person-title">绑定厚建云<span class="website">(http://i.hogecloud.com)</span>
		</h2>
	    <div class="person-area">
              <div class="login-item active">
                   <div class="main-con">
                   		<div class="item">
                            <span  class="name">您的云平台账号：</span>
							<font class="important" style="color:red" >{$list[0]['username']}</font>
                        </div>
                        <div class="item">
                            <span  class="name">请设置密码：</span>
							<input type="password" name='password' style="width:300px;" id='password'   onkeyup="checkNewPassword(this);">
							<font class="important" style="color:red" id='new_password_hint'></font>	
                        </div>
                        <div class="item">
                            <span  class="name">请确认密码：</span>
							<input type="password" name='password_again' style="width:300px;"   id='password_again' onkeyup="checkNewPassword(this);"   onblur="checkNewPasswordAgain();">
							<font class="important" style="color:red" id='new_password_again_hint' ></font>
                        </div>
                   </div>
                   <span class="button_6_14 person-save"> 绑定 </span>
              </div>
	    </div>
    </form>
    <div class="modal">
    	<p class="inner">您已绑定厚建云，正在跳转中，请稍候。。。</p>
    </div>
</div>

<script type="text/javascript">
	var userinfo = <?php echo $list ? json_encode( $list ) : '[]'; ?>;
	if( $.isArray( userinfo ) && userinfo[0] ){
		var data = userinfo[0];
		if( data.url && data.errorCode == 100 ){
			bounded( data );
		}
	}
	
	
	function bounded( data ){
		$('.person-area').hide();
		var modal = $('.modal').show();
		setTimeout(function(){
			top.location.href = data.url + '?token=' + data.token;
		}, 3000);
	}
</script>

<script type="text/javascript">
	function checkNewPassword(obj)
	{
		var str = $(obj).val();
		var res = str.match(/^@(.*)?/);
		if (str)
		{
			if (res!=null)
			{
				msg = '密码不能以@符号打头';
				$('#new_password_hint').css('color','red').html(msg);
				$(obj).val('');
			}else{
				msg = '正确';
				$('#new_password_hint').css('color','green').html(msg);
			}
		}
	}
	function checkNewPasswordAgain()
	{
		var str = document.getElementById("password_again").value;
		var str0 = document.getElementById("password").value;

				if (str == str0)
				{
					msg = '正确';
					document.getElementById("new_password_again_hint").style.color="green";
					document.getElementById('new_password_again_hint').innerHTML=msg;
				}else{
					msg = '两次输入密码不同';
					document.getElementById("new_password_again_hint").style.color="red";
					document.getElementById('new_password_again_hint').innerHTML=msg;
				}
	}

	$(function(){
		$('.person-save').on('click' , function( event ){
			var self = $( event.currentTarget );
			var form = $('.wrap').find('form');
			
			var param = {};
			param.password = $('input[name="password"]').val();
			param.password_again = $('input[name="password_again"]').val();
			param.ajax = 1;
			
			url = 'run.php?mid={$_INPUT['mid']}&a=bound';
			$.globalAjax( self , function(){
	    		return $.post( url , param , function( data ){
	    			if( typeof data === 'string' ){
	    				data = JSON.parse( data );
	    			}
					if( data.errorCode && data.errorCode !== 100 ){
						myTip( self, data.errorText || data.errorCode );
					}else if( $.isArray( data ) && data[0] ){
						data = data[0];
						if( data.errorCode == 100 ){
							myTip( self , '绑定成功');
							setTimeout(function(){
								top.location.href = data.url + '?token=' + data.token;
							}, 3000);
						}else{
							myTip( self, data.errorText || data.ErrorText || data.errorCode );
						}
					}
	    		    });
	    		});
		});

		function myTip( item , msg ){
			item.myTip({
				string : msg,
				width : 200,
				delay: 2000,
				dtop : 0,
				dleft : 10,
			});
		}
   });
</script>

{template:foot}