jQuery(function($){
		var MC = $('.ad_form'),
			_this = this;
		var control = {
				init : function(){
					MC
					.on('click' , '.select-file-button' , $.proxy(this.upload , this))
					.on('change' , '#Filedata1' , $.proxy(this.change, this))
					.on('click' , '.preview-xml' , $.proxy(this.preview , this))
					.on('click' , '.recover' , $.proxy(this.recover , this));
				},
				
				upload : function(){
					MC.find("#Filedata1").trigger('click');
				},
				
				change : function( e ){
					var self = e.currentTarget,
						file = self.files[0],
						type = file.type;
					if ( !/.+\.(xml)$/.exec(file.name) ) {
						MC.find('.select-file-button').myTip({
							string : '文件格式必须为xml',
							delay: 1500,
							dleft : 120,
						});
						$(self).value = '';
						return;
					}else{
						var reader=new FileReader();
						reader.onload=function(event){
							 context=event.target.result;
							 MC.find('textarea[name="content"]').empty().val( context );
							 MC.find('input[name="content_xml"]').val( context );
						}
						reader.readAsText(file, "UTF-8");
						var val = MC.find('.file-long').val();
						!val && MC.find('.file-long').val(file.name);
					}
				},
				
				preview : function( event ){
					var self = $(event.currentTarget),
						txt =  JSON.stringify(MC.find('textarea[name="content"]').val()),
						box = MC.find('textarea[name="content"]'),
						url = './run.php?mid=' + gMid + '&a=preview' ;
					$.globalAjax( box , function(){
						return $.post(url , {xml_str : txt} , function( json ) {
							MC.find('textarea[name="content"]').val( json[0].xml_str );
							self.text('恢复').addClass('recover').removeClass('preview-xml');
						},'json');
					} );
				},
				
				recover : function( event ){
					var self = $(event.currentTarget),
						rexml = MC.find('input[name="content_xml"]').val();
					self.text('预览').removeClass('recover').addClass('preview-xml');
					MC.find('textarea[name="content"]').val(rexml);
				},
		};
		control.init();
});