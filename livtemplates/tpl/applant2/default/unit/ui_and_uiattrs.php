{css:bootstrap/3.3.0/bootstrap.min}
{js:app_plant/ui_and_uiattrs}
<style>
.modal-dialog{width:900px;}
.modal-body{height:400px;overflow-y:auto;}
.modal-body .checkbox{margin:0;}
#ui-list-pop .list-group-item{line-height:30px;}
</style>
<div class="modal fade bs-example-modal-lg" id="ui-list-pop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-pop" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">UI列表</h4>
            </div>
            <div class="modal-body">
                <div class="list-group">
  					
  				</div>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-default close-pop">取消</button>
			</div>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-lg" id="ui-attr-pop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-pop" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">UI列表</h4>
            </div>
            <div class="modal-body">
                <div class="list-group">
  					
  				</div>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-default close-pop">返回</button>
				<button type="button" class="btn btn-primary submit-selected-attrs">确认选择</button>
			</div>
        </div>
    </div>
</div>
<script type="text/x-jquery-tmpl" id="ui-list-tpl">
<div class="list-group-item disabled">
	<div class="row">
		<div class="col-md-8">UI名</div>
		<div class="col-md-2">UI类型</div>
		<div class="col-md-2">操作</div>
	</div>
</div>
{{each data}}
<div class="list-group-item" _id="{{= $value['id']}}">
	<div class="row">
		<div class="col-md-8">{{= $value['name']}}</div>
		<div class="col-md-2">{{= $value['type_text']}}</div>
		<div class="col-md-2">
			<button type="button" class="btn btn-primary btn-sm get-attr-btn">查看属性</button>
		</div>
	</div>
</div>
{{/each}}
</script>
<script type="text/x-jquery-tmpl" id="ui-attr-tpl">
<div class="list-group-item disabled">
	<div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-5">属性名</div>
		<div class="col-md-4">属性类型</div>
		<div class="col-md-2">分组</div>
	</div>
</div>
{{each data}}
<div class="list-group-item" _id="{{= $value['id']}}" _attrtype="{{= $value['attr_type_mark']}}" data-json="{{= JSON.stringify($value)}}">
	<div class="row">
		{{if mySelectType == 'multiple'}}
		<div class="col-md-1"><input type="checkbox" class="checkbox"></div>
		{{else}}
		<div class="col-md-1"><input type="radio" class="radio"></div>
		{{/if}}
		<div class="col-md-5">{{= $value['name']}}</div>
		<div class="col-md-4">{{= $value['attr_type_name']}}</div>
		<div class="col-md-2">{{= $value['group_name']}}</div>
	</div>
</div>
{{/each}}
{{if page.length>1}}
<nav>
	<ul class="pagination">
		<li _index="0"><a>&laquo;</a></li>
		{{each page}}
		<li _index="{{= $index}}" class="{{if $value['currentPage']==$index}}active{{/if}}"><a>{{= $value['pageNum']}}</a></li>
		{{/each}}
		<li _index="{{= page.length-1}}"><a>&raquo;</a></li>
	</ul>
</nav>
{{/if}}
</script>