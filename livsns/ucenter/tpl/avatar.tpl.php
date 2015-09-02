<?php
/* $Id: avatar.tpl.php 1708 2011-01-11 03:09:46Z repheal $ */
?>
<?php include hg_load_template('head');?>
<script type="text/javascript"><!-- 
        $(document).ready(function() {

        	/* 提交文件表单  */
            $('#avatat_upload').ajaxForm(function(data) {
	            if(data == 1)
	            {
	             	alert('上传的图片应小于1000*1000像素,小于50*50像素!');   
	            }
	            else if(data == 2)
	            {
		            alert('上传图片的格式应该为.gif .jpg .png 格式！  ');
		        }
	            else
	            {
	            	var json_obj = eval('(' + data + ')'); 	   //将json串转化为json对象 
	                var pic_url = json_obj.larger;
	                $("#avatar_img").attr('src' , pic_url);
	                $('#cut_info').val('');
	                $('#cut_btn').css('display' , 'inline-block'); 
	                $('#save_btn').css('display' , 'inline-block');     
	            }                      
            });


        	/* 保存头像裁剪   */
        	
            $('#save_btn').click(function(){

                if($('#cut_info').val() == '')
                {
                	alert('请先裁剪图片！');     
                }
                else
                {
                	$('#avatat_upload').ajaxSubmit(function(data){
                        
                        $('#cut_info').val('');
                        var showNotice = '<span style="display:inline-block;background:white;padding:10px;border:5px solid silver;text-align:center;color:green;">保存成功！</span>';   
                        $('#show_message').html(showNotice);
            			
        				$('#show_message').animate({opacity:'show'},2000)
        				 				 .animate({opacity:'toggle'},2000);

        				window.location.href = '<?php echo SNS_UCENTER . 'avatar.php'; ?>'; 	
                        });
                	return false;
                }
			});                                        	        
        });


        /* 图片裁剪过程中调用方法 */
        $.extend($.imgAreaSelect, {
             
            animate: function (fx) { 
            var start = fx.elem.start, 
            	end = fx.elem.end, now = fx.now, 
            	curX1 = Math.round(start.x1 + (end.x1 - start.x1) * now), 
            	curY1 = Math.round(start.y1 + (end.y1 - start.y1) * now), 
           		curX2 = Math.round(start.x2 + (end.x2 - start.x2) * now), 
           		curY2 = Math.round(start.y2 + (end.y2 - start.y2) * now); 
            fx.elem.ias.setSelection(curX1, curY1, curX2, curY2); 
            fx.elem.ias.update(); 
            },
             
            prototype: $.extend($.imgAreaSelect.prototype, { 
                animateSelection: function (x1, y1, x2, y2, duration) { 
                var fx = $.extend($('<div/>')[0], { 
                    ias: this, start: this.getSelection(),
                     end: { x1: x1, y1: y1, x2: x2, y2: y2 } }); 
                if (!$.imgAreaSelect.fxStepDefault) {
                     $.imgAreaSelect.fxStepDefault = $.fx.step._default; 
                     $.fx.step._default = function (fx) { 
                         return fx.elem.ias ? $.imgAreaSelect.animate(fx) : $.imgAreaSelect.fxStepDefault(fx); 
                         }; 
                } 
                $(fx).animate({ cur: 1 }, duration, 'swing'); 
                } 
            }) 
        });

        /* 图片裁剪完调用方法 */
        function getCss(img, selection){

	        var cut_info = selection.x1 + ',' + selection.y1 + ','  + selection.width + ',' + selection.height;
			$('#cut_info').val(cut_info);
        }

        /* 裁剪初始化  */
        $(window).load(function () {
        	
        	$('#cut_btn').click(function () {

            	//载入原图 
            	$.ajax({
					url: "avatar.php",
					type: 'POST',
					dataType: 'html',
					timeout: 5000,
					cache: false,
					data: {a: "get_ori_img" 				 	
					},
					error: function(){
						
					},
					success: function(path){						
						$("#avatar_img").attr('src' , path);	
					}
            	});

				ias = $('img#avatar_img').imgAreaSelect({
	        		minWidth: 40,   //裁剪的最小宽度
	        		minHeight: 40,  //裁剪的最小高度
	        		maxWidth: 250,  //裁剪的最大宽度 
	        		maxHeight: 250, //裁剪的最大高度  
	            	fadeSpeed: 400, 
	            	handles: true,  
	            	instance: true,
	            	onSelectEnd: getCss  //裁剪及诶数回调的函数
            	 });

				//显示裁剪的区块  
            	if (!ias.getSelection().width) 
                	ias.setOptions({ show: true, x1: 199, y1: 149, x2: 200, y2: 150 }); 
            	ias.animateSelection(40, 40, 150, 150, 'slow'); 
            });					
			            	
        });
         
--></script> 

<div class="content">
	<div class="content_top"></div>	
	<div class="content_middle lin_con clear"> 
		<!-- 导航按钮  -->
		<?php include hg_load_template('userset'); ?>
		<div class="clear"></div>
		<div class="con-avatar" id = "avatar" style="padding:20px 0 20px 143px;">
			<form id="avatat_upload" action='avatar.php' method='post' enctype='multipart/form-data'>
				<div style="float:left;">
					<img id="avatar_img" style="border: 2px solid #DDDDDD;padding:8px;" src="<?php echo $this->user_info[0]['larger_avatar'];?>?<?php echo TIMENOW?>" />							
				</div>

				<div style="float:left;">
					<input id="cut_btn" type='button'  name='btn' value='裁剪' style="display:none;margin-left:20px;height:30px;font-size:14px;"/>
					<input id="save_btn" type='button' name='save_cut' value='保存' style="display:none;margin-left:20px;height:30px;font-size:14px;"/>
					<span id="show_message"></span>				
				</div>
				
				<div class="clear">
					<span style="font-size:14px;">请选择图片：</span>
					<input style="height:30px;font-size:14px;" type='file' name='files'  />
					<input id="cut_info" type="hidden" name="cut_info" value=""/>
					<input type='submit' name='upload_avatar' value='上传头像' style="margin-left:20px;height:30px;font-size:14px;"/>
					<input type='hidden' name='a' value='uploadImage' />
				</div>
				
			</form>
		</div>		
	</div>
	<div class="content_bottom"></div>
</div>

<?php include hg_load_template('foot');?>
