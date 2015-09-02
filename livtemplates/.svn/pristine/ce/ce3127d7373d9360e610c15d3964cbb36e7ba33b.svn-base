{template:head}
{js:ajax_upload}
<div>
	<span class="button" style="width:100px;height:30px;background:orange;display:block;">上传字体包</span>
	<input type="file" name="filedata" class="upload-file" style="display:none;">
</div>
<script>

$(function(){
$('.button').on('click',function(){
	$('.upload-file').click();
})
$('.upload-file').on('change',function(){
	$(this).ajaxUpload({
		url : "run.php?mid={$_INPUT['mid']}&a=create",
		type : 'ttf',
		phpkey : 'filedata',
		before : function( info ){
		},
		after : function( json ){
			
		}
	});
})

})


</script>