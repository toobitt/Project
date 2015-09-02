$(function(){
    $('.my-placeholder').on({
        focus : function(event, init){
            $(this).data('focus', true);
            var target = $('#' + $(this).attr('target'));
            var targetVal = target.val();
            var val = init ? targetVal : $.trim($(this).text());
            var placeholder = $(this).attr('placeholder');
            var preval = $(this).attr('preval');
            $(this).addClass('on');
            if(!val || val == placeholder){
                $(this).text('');
            }else if(val == (preval + targetVal)){
                $(this).text(targetVal);
            }else{
                $(this).text(targetVal);
            }
        },

        blur : function(){
            if(!$(this).data('focus')) return;
            $(this).data('focus', false);
            var val = $.trim($(this).text());
            var placeholder = $(this).attr('placeholder');
            var preval = $(this).attr('preval');
            var target = $('#' + $(this).attr('target'));
            $(this).removeClass('on');
            if(!val || val == placeholder){
                $(this).text(placeholder);
                target.val('');
            }else{
                $(this).text(preval + val);
                target.val(val);
            }
        }
    }).each(function(){
        $(this).trigger('focus', [true]).trigger('blur');
    });
});

$(function(){
	(function($){
		var indexPicEdit = function(){
			var imgid = $('#indexpic').val();
			$('#indexpic_url').picEdit({
		    	imgSrc : $(this).attr('_src'),
		        mouseCheck : function(){
		            return $(this).attr('_state') > 0 ? false : true;
		        },
		        saveAfter : function(){
		            top.$('body').find('img.tmp-edit-top-img').remove();
		            top.$('body').off('_picsave').on('_picsave', function(event, info){
		                try{
		                    var img = $(this).find('#formwin')[0].contentWindow.$('#indexpic_url');
		                    img.attr('src', $.globalImgUrl(info, '', true));
		                    top.$('body').find('img#indexpic_url').remove();
		                }catch(e){}
		            }).append($('#indexpic_url').clone().hide().addClass('tmp-edit-top-img'));
		            $.editorPlugin.get($.myueditor, 'imgmanage').imgmanage('refreshPicSrc', imgid);
		        }
		   });
		};
		indexPicEdit();
		//上传索引图
		$('.indexpic').click(function(){
			$.editorPlugin.get($.myueditor, 'imgmanage').imgmanage('showInputFile');
		});
		var suoyinId = parseInt( $('#indexpic').val() );
		//初始化编辑器
		var	init = function(){
			$.myueditor = $.m2oEditor.get( 'magazine_editor', {
				initialFrameWidth : 690,
				initialFrameHeight : 590,
				slide : true,					//风格
				removeFormatTags:'b,big,code,del,dfn,em,font,i,ins,kbd,q,samp,small,span,strike,strong,sub,sup,tt,u,var,iframe,object,embed',
				editorContentName : 'content',	//编辑器内容的name名
				relyDom : '.form-right',		//slide风格依赖dom（用于计算定位和高度）
				needCount : true,				//字数统计
				countDom : '#editor-count',		//字数统计dom
				suoyinId : suoyinId,			//索引图的素材id	
			} );
			$.myueditor.addListener('_setIndex', function(event, data) {
				var index_box = $('.indexpic-box'),
					indexpic_hidden = $('#indexpic'),
					index_flag = index_box.find('.indexpic-suoyin'),
					indexpic_img = index_box.find('img'),
					from_indexpic_material = $('.from-indexpic-material');
				var load = $.globalLoad( index_box );
				if (data) {
					var img = new Image();
					img.src = $.globalImgUrl(data, '160x', true);
					img.onload = function(){
						load();
						indexpic_img.css('width','auto').attr({
							'src' : img.src,
							'_src' : img.src
						});
					};
					indexpic_hidden.val(data.id);
					index_flag.addClass('indexpic-suoyin-current');
					indexPicEdit();
				} else {
					load();
					indexpic_hidden.val('');
					indexpic_img.attr('src',RESOURCE_URL+'news/suoyin-default.png');
					index_flag.removeClass('indexpic-suoyin-current');
				}
				from_indexpic_material.length && from_indexpic_material.remove();
			});
			$.myueditor.addListener('_refreshIndexPic',function(event, data){
				var img = $('.indexpic').find('img'),
					src = img.attr('src');
				if( data ){
					img.attr('src', $.globalImgUrl(data, '160x', true) ).css('width','auto');
				}else{
					img.attr('src',src+'?'+new Date().getTime());
				}
			});
			$.myueditor.addListener('_title', function(event, selectedText) {
				$('#title').val( selectedText ).attr({
					'_value' : selectedText,
					'title' : selectedText
				});
			});
			$.myueditor.addListener('_desc', function(event, selectedText, widget) {
				widget.hideAll();
				$('#brief-clone').text( selectedText );
				$('#brief').val( selectedText );
			});
			$.myueditor.addListener('_keyword',function(event, text, widget){
				widget.hideAll();
				$('#keywords-box')[0] && $('#keywords-box').trigger('append', text);
			});
			$.myueditor.addListener('_uploadSuoyinCallback',function(event, data){
				var src = data[0]['_mSrc'],
					from_indexpic_material = $('.from-indexpic-material');
				$('.indexpic').find('img').attr('src',src);
				$('.indexpic-suoyin').addClass('indexpic-suoyin-current');
				$('#indexpic').val( data[0]['id'] );
				from_indexpic_material.length && from_indexpic_material.remove();
			});
		};
		
		$.includeUEditor( init, {
			plugins: ['editorCount','imginfo','imglocal','imgmanage','pizhu','removetag','tip','water']
		} );
	})($);
	//编辑器end
});
