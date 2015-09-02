window.onload=function(){
	  parent.$('.child-top-loading').removeClass('show');
}

/*resize iframe高度*/
function special_find_wrapper(){
    return top ==parent.parent ? self.$('#childnodeFrame')[0].contentWindow.$('html'):$('html');
}
function special_resizenodeFrame(){
    var _html=special_find_wrapper(),
        _html_height=_html.height(),
        livmian=top.$("#mainwin")[0].contentWindow.$("#livnodewin"),
        livmian_height=livmian.height(),
        _body=livmian.find('#nodeFrame')[0].contentWindow.$('body');
    if(_html_height > livmian_height ){
    	livmian.height(_html_height);
    	_body.height(_html_height);
    }else{
    	_html.height(livmian_height);
    	_body.height(livmian_height);
    }
}

jQuery(function(){
  /*专题form页*/
  (function($){
	  $.widget('special.special_form',{
		  options:{
			  column:'.column-input',
			  del_btn:'.column-delete',
			  add_btn:'.column-add',
			  tip:'.column-tip',
			  msg1:'请输入栏目名',
			  msg2:'栏目名已存在'
		  },
		  _init:function(){
			  this._on({
				  'click .column-delete':'_deleteClomun',
				  'click .column-add':'_addClomun'
			  });
		  },
		  _deleteClomun:function(event){
			  var self=$(event.currentTarget);
			  if(self.data('id')){
				  jConfirm('删除栏目会删除栏目下所有内容列表,您确定删除栏目吗','删除栏目提醒',function(result){
					  if(result){
						  self.parent().remove();
					  }
				  });
			  }else{
				  self.parent().remove();
			  } 
	      },
	      _addClomun:function(event){
			  var self=$(event.currentTarget),
			      parent=self.parent(),
			      input=parent.find(this.options['column']),
				  value=$.trim(input.val());
			  if(value){
				  var isRepeat=this._checkRepeat(input);
				  if(!isRepeat){
					  parent.clone().appendTo('.column-list').find(this.options['column']).val('');
					  self.addClass('hide').next().removeClass('hide');
				  }else{
					  this._errorTip(parent,this.options['msg2']);
				  }
			  }else{
				  this._errorTip(parent, this.options['msg1']);
			  }
	      },
	      _checkRepeat:function(self_input){
	    	  var inputs=$(this.options['column']),
	    	      isRepeat=false;
	    	  inputs.each(function(){
	    		  if(self_input[0]==this) return;
	    		  if($.trim($(this).val()) == $.trim(self_input.val())){
	    			  isRepeat=true;
	    		  }
	    	  });
	    	  return isRepeat;
	      },
	      _errorTip:function(obj,msg){
	    	  var tip=obj.find(this.options['tip']);
			  tip.text(msg).addClass('column-tip-show');
			  setTimeout(function(){
				  tip.removeClass('column-tip-show');
			  },1000);
	      }
	  });
  })(jQuery);
  $('.special-form').special_form();

  
  (function($){
		$('#Filedata').change(function(){
			var file=this.files;
		    handleFiles(file);
		});
		$('.special-indexpic').on({
			'click':function(){
				$('#Filedata').trigger('click');
			}
		});
		function handleFiles(files){
			for(var i=0;i<files.length;i++){
				var file=files[i];
				var imageType=/image.*/;
				if(!file.type.match(imageType)){
					continue;
				}
				var reader=new FileReader();
				reader.onload=function(e){
					var imgData=e.target.result;
					$('.viewPic').attr('src',imgData).show();
					$('.special-indexpic').css({'background':'none'}).find('.indexpic-suoyin').addClass('indexpic-suoyin-current');
				}
				reader.readAsDataURL(file);
			}
		}
	})(jQuery);
  (function($){
	  $('.sp-conentEditor,.special-back,.showAll').on({
		  'click':function(){
			  parent.$('.child-top-loading').addClass('show');
		  }
	  });
  })(jQuery);
  (function($){
	  $('#special-form').submit(function(e){
		  var isnull=false;
		  $('.real-input').each(function(){
			  if(!$.trim($(this).val())){
                  isnull=true;
			  }
		  });
		  if($('.column-input').length==1){
			  var value=$.trim($('.column-input').val());
			  value || (isnull=true);
		  }
		  if(isnull){
			  alert('栏目不能为空');
			  return false;
		  }
	  });
  })($);
  
  
  (function($){
	   special_resizenodeFrame();
  })($);
  
  jQuery(function($){
	if (!$('.common-publish-button').size()) return;
	var pub = $("#form_publish");
	
    $('.common-publish-button').on('click', function(event){
        event.stopPropagation();
        event.preventDefault();
      
        if ( $(this).data('show') ) {
        	$(this).data('show', false);
       		pub.css({
       			top: -450
       		})
        } else {
        	$(this).data('show', true);
        	pub.css({top: 100, 'margin-left': pub.width() / -2});	
        }
    });
    pub.on('click', '.publish-box-close', function () { $('.common-publish-button').trigger('click'); });
    pub.find('.publish-box').hg_publish({
    	change: function () {
    		 $('.common-publish-button').html(function(){
        		var hidden = $('.publish-name-hidden', pub).val();
       			return hidden ? ($(this).attr('_prev') + '<span style="color:#000;">' + hidden + '</span>') : $(this).attr('_default');
    		 });	
    	},
    	maxColumn: 2
    });
  });

});
