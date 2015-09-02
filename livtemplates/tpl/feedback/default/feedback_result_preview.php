{css:bootstrap/3.3.0/bootstrap.min}
{css:feedback_print}
{js:jquery.min}
{js:feedback/feedback_print}

<style type="text/css">
	@media print{
	.edit, .nav-btn{display:none; }
  }
</style>


<div class="inner-wrap container m2o-flex">
	<div class="nav-btn">
		<span class="btn btn-wid80 btn-set">编辑</span>
		<span class="btn btn-wid80 btn-print">打印</span>
	</div>
	<div class="table-box m2o-flex-one">
		{$formdata['preview']}
	</div>
	<div class="edit fold">
		<textarea id="source">
  		{$formdata['edit']}
		</textarea>
	</div>
</div>
<script type="text/javascript">
	$.globalSearch = {
		mid : {code} echo json_encode( $_INPUT['mid'] ) {/code},
		fid : {code} echo json_encode( $_INPUT['fid'] ) {/code}
	}
</script>