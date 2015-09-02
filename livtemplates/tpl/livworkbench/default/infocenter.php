{template:head}
{css:ad_style}
{css:column_node}
{css:2013/iframe}
{code}
	//print_r($list);
	//echo $user_id;
{/code}
<style type="text/css">
.source_item {cursor:pointer; border:1px solid #CCC; display:inline-block; padding:3px 5px; margin:5px;}
</style>
<script type="text/javascript">
	function checkPassword()
	{
		//var oldPassword = document.getElementById("old_password").value;
		//var url = 'infocenter.php?a=checkPassword&password='+oldPassword;
		//hg_request_to(url,'', 'get', 'pass', 1);
	}
	var pass = function (data)
	{	
		var str = '';
		if (data == 1)
		{
			str = '正确';
			document.getElementById("old_password_hint").style.color="green";
			document.getElementById('old_password_hint').innerHTML=str;
		}else
		{
			str = '密码错误';
			document.getElementById("old_password_hint").style.color="red";
			document.getElementById('old_password_hint').innerHTML=str;
		}		
	}
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

	function hg_get_mibao(id)
	{
		var url = "infocenter.php?a=get_mibao_info&id="+id;
		hg_ajax_post(url,'','','hg_show_mibao');
	}

	function hg_show_mibao(obj)
	{
		$('#mibao_card').attr('src',obj.img);
		$('#mibao_info_box').show();
	}

	function hg_download_mibao()
	{
		var img = $('#mibao_card').attr('src');
		var url = "infocenter.php?a=download_card&img="+img;
		window.location.href=url;
	}

	function hg_rebind_mibao()
	{
		var id = $('#admin_id').val();
		var url = "infocenter.php?a=bind_card&id="+id;
		hg_ajax_post(url,'','','hg_change_mibao');
	}

	function hg_change_mibao(obj)
	{
		$('#mibao_card').attr('src',obj.img);
		if($('#mibao_info_box').css('display') == 'none')
		{
			$('#mibao_info_box').show();
		}
		$('.passView').text('查看密保卡');
		$('#mibao_info_box').find('img').show();
	}

	function hg_cancel_mibao()
	{
		var id = $('#admin_id').val();
		var url = "infocenter.php?a=cancel_bind&id="+id;
		hg_ajax_post(url,'','','hg_cancel_bind_mibao');
	}

	function hg_cancel_bind_mibao(obj)
	{
		if(parseInt(obj.status))
		{
			$('#mibao_card').attr('src','');
			$('#mibao_info_box').find('img').hide();
			$('#mibao_button').css('display','inline-block');
			$('.passView').text('绑定密保卡');
		}
	}
	$(function(){
		$('.person-area').on('click','.editor',function(event){
			var self=$(this),
			    item=self.closest('li');
		    self.toggleClass('up');
		    item.toggleClass('active');
		    item.siblings().hasClass('active') && item.siblings().removeClass('active');
		    item.siblings().find('.editor').hasClass('up') && item.siblings().find('.editor').removeClass('up')
		});
		$('.passView').click(function(event){
			var self=$(event.currentTarget);
			var id=self.data('id');
			hg_get_mibao(id);
		});
		function handleattachFiles(files){
			for(var i=0;i<files.length;i++){
				var file=files[i];
				var imageType=/image.*/;
				if(!file.type.match(imageType)){
					alert("请上传图片文件");
					continue;
				}
				var reader=new FileReader();
				reader.onload=function(e){
					imgData=e.target.result;
					var box=$('.img_view');
					var img = box.find('img');
		            !img[0] && (img = $('<img/>').appendTo(box));
		            img.attr('src', imgData);
				}
				reader.readAsDataURL(file);
			}
		}
		$('.change-photo').on('click',function(){
			$('#photo-file').click();
		});
		$('#photo-file').change(function(){
			var file=this.files;
			handleattachFiles(file);
		});
   });
</script>
<style>
.person-title{text-indent:30px;padding-bottom:6px;margin:16px 0 0 10px;background:url({$RESOURCE_URL}dottedLine.png) repeat-x bottom;}
.person-area{width:950px;min-height:500px;margin:40px auto 50px;}
.person-area li{height:48px;background:url({$RESOURCE_URL}dottedLine.png) repeat-x bottom;padding-bottom:1px;clear:left;}
.person-area .top{height:48px;line-height:48px;padding:0 35px 0 0;}
.person-area .title{float:left;font-size:16px;text-indent:28px;width:100px;text-align:right;padding-right:100px;}
.person-area .info{float:left;}
.person-area .editor{float:right;color:#808080;padding-right:18px;cursor:pointer;font-size:14px;background:url({$RESOURCE_URL}person/down_b.png) no-repeat right center;}
.person-area .up{background-image:url({$RESOURCE_URL}person/up_w.png)}
.person-area .main-con{background:#eaf3fc;padding:30px 0 40px 200px;display:none;}
.person-area .main-con .item{margin-bottom:10px;}
.person-area .main-con .name{display:inline-block;width:80px;color:#8e8e90;margin-right:15px;text-align:right;}
.person-area .photo-img{width:36px;height:36px;border-radius:50%;vertical-align:middle;}
.person-area .big-img{width:100px;height:100px;margin-right:15px;}
.person-area li.active{height:auto;}
.person-area .active .top{background:#6ba2d8;}
.person-area .active .title{color:#fff;}
.person-area .active .editor{color:#fff;background-image:url({$RESOURCE_URL}person/up_w.png)}
.person-area .active .info{display:none;}
.person-area .active .main-con{display:block;}
.person-area .person-save{margin:20px 0 0 50px;}
.important{float:none;}
#mibao_info_box{width:100%;position:relative;}
.pass-card{position:absolute;height:300px;right:220px;top:170px;width:103px;}
.pass-card a{margin-bottom:22px;}
@media only screen and (-webkit-min-device-pixel-ratio: 2),
only screen and (-moz-min-device-pixel-ratio: 2),
only screen and (-o-min-device-pixel-ratio: 2/1),
only screen and (min-device-pixel-ratio: 2) {
        .person-area .editor{background-image:url({$RESOURCE_URL}person/down_b-2x.png);background-size:8px 8px;}
        .person-area .up{background-image:url({$RESOURCE_URL}person/up_w-2x.png);background-size:8px 8px;}
}
</style>
<div class="wrap clear">
	<form action="" method="post" enctype="multipart/form-data">
		<h2 class="person-title">个人设置</h2>
	    <div class="person-area">
	         <ul>
	              <li>
	                   <div class="top">
	                       <span class="title">个人资料</span>
	                       <div class="info"><span>{$user_name}</span></div>
	                       <span class="editor">编辑</span>
	                   </div>
	                   <div class="main-con">
	                        <div class="item">
	                            <span class="name">用户名:</span>
	                            <input type="text" value="{$user_name}" name='user_name' style="width:300px;" disabled="disabled">
	                        </div>
	                   </div>
	              </li>
	              <li>
	                   <div class="top">
	                       <span class="title">头像</span>
	                       <div class="info">
		                          <span class="img_view">
		                             {code}
										$index_img = '';
										if($avatar)
										{	
											$pic = $avatar;
											$index_img = $pic['host'] . $pic['dir'] .'100x75/'. $pic['filepath'] . $pic['filename'];
										}
									{/code}
									{if $index_img}
										<img src="{$index_img}" alt="头像" class="photo-img"/>
									{/if}
		                         </span>
	                        </div>
	                       <span class="editor">编辑</span>
	                   </div>
	                   <div class="main-con">
	                        <div class="item">
	                            <span class="img_view">
		                             {code}
										$index_img = '';
										if($avatar)
										{	
											$pic = $avatar;
											$index_img = $pic['host'] . $pic['dir'] .'100x75/'. $pic['filepath'] . $pic['filename'];
										}
									{/code}
									{if $index_img}
										<img src="{$index_img}" alt="头像" class="photo-img big-img"/>
									{/if}
		                         </span>
	                            <span class="button_6_14 change-photo"/>修改头像</span>
	                            <input type="file" name='Filedata' style="display:none;" id="photo-file"/>
	                        </div>
	                   </div>
	              </li>
	             <!--  
	              <li>
	                   <div class="top">
	                       <span class="title">联系方式</span>
	                       <div class="info"><span></span></div>
	                       <span class="editor">编辑</span>
	                   </div>
	              </li>-->
	              <li>
	                   <div class="top">
	                       <span class="title">密码</span>
	                       <div class="info"><span style="font-size:14px;vertical-align:middle;">******</span></div>
	                       <span class="editor">编辑</span>
	                   </div>
	                   <div class="main-con">
	                        <div class="item">
	                            <span  class="name">原始密码：</span>
								<input type="password" value="" name='old_password' style="width:300px;" onblur='checkPassword()' id='old_password'>
								<font class="important" style="color:red" id='old_password_hint'></font>
	                        </div>
	                        <div class="item">
	                            <span  class="name">新密码：</span>
								<input type="password" value="" name='password' style="width:300px;" id='password'   onkeyup="checkNewPassword(this);">
								<font class="important" style="color:red" id='new_password_hint'></font>	
	                        </div>
	                        <div class="item">
	                            <span  class="name">确认密码：</span>
								<input type="password" value="" name='password_again' style="width:300px;"   id='password_again'    onkeyup="checkNewPassword(this);"   onblur="checkNewPasswordAgain();">
								<font class="important" style="color:red" id='new_password_again_hint' ></font>
	                        </div>
	                   </div>
	              </li>
	              {if $is_open_card}
	              <li>
	                   <div class="top">
	                       <span class="title">密保卡</span>
	                       <span class="editor passView" data-id="{$id}">{if $is_bind_card}查看密保卡{else}绑定密保卡{/if}</span>
	                   </div>
	                   <div class="main-con">
	                        <div id="mibao_info_box" class="right_version" style="height:420px;display:none;">
								<div style="width:100%;height:420px;">
									<img id="mibao_card"  src="" />
								</div>
								<div class="pass-card">
									<a href="javascript:void(0)" class="button_6" style="margin-left:20px;" onclick="hg_download_mibao();">下载密保卡</a>
									<a href="javascript:void(0)" class="button_6" style="margin-left:20px;" onclick="hg_rebind_mibao();">重新绑定</a>
									<a href="javascript:void(0)" class="button_6" style="margin-left:20px;" onclick="hg_cancel_mibao();">取消绑定</a>
								</div>
		                    </div>
	                   </div>
	              </li>
	              {/if}
	         </ul>
	         <input type="submit" name="sub" value="提交" class="button_6_14 person-save"/>
	    </div>
	             <input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="id" value="{$id}" id="admin_id" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
    </form>
</div>
{template:foot}