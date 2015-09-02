/*初始化文本属性*/
$(function() {
	var activeEle = getActiveElement(),
		value = 0;
	value = $(activeEle).css('font-weight');
	if (value >= 700 || value === 'bold' || value == 'bolder' ) {
		$('.text-bold').addClass('selected');
	}
	
	//初始化行高	
	value = parseInt( (function() {
		if( !activeEle ) {
			return 23;
		}
		return $(activeEle).css( 'line-height' );
	})() );
	$( '.line-height-slide' ).slider({
		animate: true,
		min: 0,
		max: 100,
		step: 1,
		value: value,
		slide: function( e, ui ) {
			I_FormatText('line-height', ui.value + 'px');
			$(this).next().text( ui.value );
		},
		create: function() {
			$(this).next().text( value );
		}
	});
	
	//初始化文字字间距
	value = parseInt( (function() {
		if( !activeEle ) {
			return 0;
		}
		var v = $(activeEle).css( 'letter-spacing' ) ;
		return v == 'normal' ? 0 : v;
	})() );
	$( '.letter-spacing-slide' ).slider({
		animate: true,
		min: 0,
		max: 100,
		step: 1,
		value: value,
		slide: function( e, ui ) {
			I_FormatText('letter-spacing', ui.value + 'px');
			$(this).next().text( ui.value );
		},
		create: function() {
			$(this).next().text( value );
		}
	});

	/*初始化字体样式*/
	var width = 40, height = 50;
	value = $(activeEle).css("font-size");
	$( '.font-size-box > div > div' ).each( function() {
		$(this).css( {'width': width + 'px', 'height': height + 'px'} );
		if ( value == $(this).data("val") + "px" ) $(this).css("border-color", "blue");
		width += 2;
		height += 2;
	}).on("click", function () {
		$( '.font-size-box > div > div' ).css("border-color", "");
		$(this).css("border-color", "blue");
		if ( $.browser.mozilla ) {
			I_Size( $(this).data("val") );
		} else {
			I_Size_saf( $(this).data("val") );
		}
	});
	function getActiveElement() {
		if (!isiPad) {
			obj.setFocus();
		}
		var oEditor = parent.oUtil.oEditor;
		var oSel;
		if (parent.oUtil.activeElement) {
			oElement = parent.oUtil.activeElement;
			return oElement;
		} else {
			if (navigator.appName.indexOf('Microsoft') != -1) {
				oSel = oEditor.document.selection.createRange();
				if (oSel.parentElement) {
					if (oSel.text == "") {
						oElement = oSel.parentElement();
						if (oElement)
							if (oElement.tagName != "BODY")
								return oElement;
					} else {
						return null;
					}
				} else {
					oElement = oSel.item(0);
					if (oElement)
						return oElement;
				}
			} else {
				oSel = oEditor.getSelection();
				oElement = parent.getSelectedElement(oSel);
				return oElement;
			}
		}		
	};
	
	$('.item-text-attr').click(function() {
		$(this).toggleClass('selected');
	});
	
	 var fontfamily = {
           'songti':'宋体',
           'kaiti':'楷体',
           'heiti':'黑体',
           'lishu':'隶书',
           'yahei':'微软雅黑',
           'andaleMono':'andale mono',
           'arial': 'arial',
           'arialBlack':'arial black',
           'comicSansMs':'comic sans ms',
           'impact':'impact',
           'timesNewRoman':'times new roman'
    };
    fontfamily = $.map(fontfamily, function (v) { return v;});
	value = $(activeEle).css("font-family");
	
     $("#fontFramilySelect").html( function () {
     	var html = '', selected = '';
     	$.each(fontfamily, function (i, n) {
     		selected = RegExp(n).test(value) ? "selected" : '';
     		html += '<option value="' + i + '" ' + selected + '>' + n + '</option>';
     	});
     	return html;
     } () ).on("change", function () {
     	I_ApplyFont( fontfamily[ $(this).val() ], true );
     })
});