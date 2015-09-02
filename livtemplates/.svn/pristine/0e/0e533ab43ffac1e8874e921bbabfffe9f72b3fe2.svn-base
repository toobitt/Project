var TAG = {'short': [ 'br','img','input','hr','button','meta'],'normal': [	'var','ul','tt','tr','title','thead','th','tfoot','textarea','td','tbody','table','sup','sub','style','strong','span','small','select','script','samp','q','pre','param','p','option','optgroup','ol','object','noscript','noframes','map','link','li','lagend','label','kdb','ins','iframe','i','html','head','h1','h2','h3','h4','h5','h6','frameset','frame','form','fieldset','em','dt','dl','dfn','div','del','dd','colgroup','col','code','cite','caption','body','blockquote','big','bdo','base','b','area','address','a']};
var PROPERTY = ['name' , 'id' , 'value' , 'action', 'src' , 'url' , 'href' , 'class'  , 'index' , 'onsubmit' , 'onclick' , 'ondblclick' , 'onkeypress' ,'onkeydown','onkeyup' , 'onmouseover' , 'onmouseout' , 'onfocus' , 'onblur' , 'onchange' , 'onload' , 'rows' ,'rowspan', 'cols' , 'colspan', 'style' ,'tabindex' , 'type' , 'border' , 'method' , 'checked' , 'selected' , 'size' , 'alt' , 'title' , 'accept' , 'align' , 'enctype' , 'target' , 'http-equiv' , 'scheme' , 'content' , 'xmlns' , 'charset' , 'hreflang' , 'media' , 'rel' , 'rev' , 'disabled' , 'maxlength' , 'readonly' , 'height' , 'ismap' , 'longdesc', 'usemap' , 'width' , 'cellpadding' , 'cellspacing' , 'frame' , 'rules' , 'summary' , 'multiple'];
var _PROPERTY = {
	'method' 	: ['get' , 'post'],
	'enctype' 	: ['application/x-www-form-urlencoded' , 'multipart/form-data' , 'text/plain'],
	'target'	: ['_blank' , '_self' , '_top' , '_panret'],
	'type' 		: {'input' :['hidden' , 'text' , 'button' , 'radio' , 'checkbox' , 'submit' , 'reset' ,'file' , 'image' , 'password'],'script':['text/javascript'], 'style':['text/css']},
	'http-equiv': ['Content-Type','Expires' , 'Refresh' , 'Set-Cookie'],
	'name'	: {'meta' : ['author' , 'description' , 'keywords' , 'generator' , 'revised' , 'others' ]},
	'media' : {'link' : ['screen' , 'tty', 'tv' , 'projection' , 'handheld' , 'print' , 'braille' , 'aural' , 'all'], 'style' :['screen' , 'tty', 'tv' , 'projection' , 'handheld' , 'print' , 'braille' , 'aural' , 'all']},
	'rel' 	: {'link' : ['alternate' , 'appendix', 'bookmark' , 'chapter' , 'contents' , 'copyright' , 'glossary' , 'help' , 'home' , 'index' , 'next' , 'prev' , 'section' , 'start' , 'stylesheet' , 'subsection']},
	'rev' 	: {'link' : ['alternate' , 'appendix', 'bookmark' , 'chapter' , 'contents' , 'copyright' , 'glossary' , 'help' , 'home' , 'index' , 'next' , 'prev' , 'section' , 'start' , 'stylesheet' , 'subsection']}
};

var editor = {
	inited	: false,
	id		: null,
	obj		: null,
	dobj	: null,
	line	: 0,
	value	: '',
	isIE	: true,

	keyTab : "\t",
	capture	: false,
	captureCaretOffset : 0,
	currentCaretOffset : 0,
	tag		: '',
	tagCapture : false,
	property : '',
	propertyCapture : false,
	propertys	: [],
	autoListCapture : false,
	captureInput : '',
	browserCapture : false,
	extendPropertyCapture : false,

	init :function()
	{
		if(this.inited) return true;

		this.isIE = navigator.userAgent.toLowerCase().indexOf('msie') == -1 ? false : true;
		if($('#autolist').length == 0)
		{
			$(document.body).append('<div id="autolist" style="display:none;"></div>');
			$(document.body).append('<div id="browser" style="display:none;"><a href="#" onclick=";return false;">浏览……</a></div>');
		}
		this.inited = true;

	},

	resetCaret	: function(pos)
	{
		if(this.isIE)
		{
			var s = document.selection.createRange();
			s.moveEnd('character',pos);
			s.select();
			s.text = '';
			s.moveStart('character',-1);
		}
		else
		{
			this.dobj.setSelectionRange(this.dobj.selectionStart + pos , this.dobj.selectionStart + pos);
		}
	},

	process	: function(event)
	{
		event = event || window.event;
		var k = event.keyCode || event.which;
		if(this.autoListCapture)
		{
			if(k > 36 && k < 41 || k == 13) return false; /* for ff , in ff keydown capture return false can't stop keypress capture ,ie can */
			else if(k == 61)
			{
				this.insert('=""');
				this.resetCaret(-1);
				jQuery('#autolist').hide();
				this.autoListCapture = false;
				return false;
			}
		}
		var c = String.fromCharCode(k);
		this.currentCaretOffset = this.caretOffset();
		this.value = this.dobj.value;
		if(this.isIE)
		{
			this.value = this.value.replace(/\r/ig , "");
		}
		var left = this.value.substr(0 , this.currentCaretOffset);
		if(k == 8)
		{
			left = left.substr(0 , left.length - 1);
		}
		else
		{
			left += c;
		}
		var right = this.value.substring(this.currentCaretOffset , this.value.length);
		var pos1 = left.lastIndexOf('<');
		var pos2 = left.lastIndexOf('>');
		var pos3 = left.lastIndexOf("\n");

		//alert(this.currentCaretOffset + '|' + pos1 + '|' + pos2 + '|' + pos3);

		if(pos1 == -1 || pos3 > pos1)
		{
			this.release();
			return true;
		}

		this.value = left + right;
		if(k == 8)
		{
			left = left.substring(pos1 , this.currentCaretOffset - 1);
		}
		else
		{
			left = left.substring(pos1 , this.currentCaretOffset + 1);
		}
		this.value = this.value.substring(pos1 , this.value.length);
		var pattern = /^<([\?a-z][a-z0-9]*)?([^>\n]*>?)/i;
			pattern.lastIndex = 0;
		var m = this.value.match(pattern);
		this.release();
		if(m)
		{
			if(m[1] == '?php')
			{
				if(m[2].indexOf('?') == -1)
				{
					this.insert(' ?>');
					this.resetCaret(-3);
				}
				this.release();
				return true;
			}

			m.push(left);
			this.captureInput = m[3];
			this.tag = m[1];
			if(m[3].length == m[1].length + 1)
			{
				this.tagCapture = true;
				this.property = '';
				this.propertyCapture = false;
			}
			else
			{
				this.tagCapture = false;
				this.propertys = [];
				var pattern = /\s([a-z]+)$/i;
					pattern.lastIndex = 0;
				var p = m[3].match(pattern);
				var c = m[0].charAt(m[3].length);
				if(p && (c == '' || c == '=' || c == ' ' || c == '>' || c == '/' || c == '"' || c == '\''))
				{
					this.property = p[1];
					this.propertyCapture = true;
				}

				pattern = /\s([a-z]+)/ig;
				pattern.lastIndex = 0;
				var p = m[0].match(pattern);
				if(p)
				{
					for(var i = 0; i<p.length ; i++)
					{
						p[i] = $.trim(p[i]).toLowerCase();
						if(p[i] != this.property.toLowerCase())
							this.propertys.push(p[i]);
					}
				}
			}

			if(this.tagCapture)
			{
				if(this.tag)
				{
					this.autoList();
					this.autolistCapture = true;
				}
				else
				{
					jQuery('#autolist').hide();
					this.autolistCapture = false;
				}
			}
			else if(this.propertyCapture)
			{
				if(this.property)
				{
					this.propertyCapture = true;
					this.autoList();
					this.autolistCapture = true;
				}
				else
				{
					jQuery('#autolist').hide();
					this.autolistCapture = false;
				}
			}

			switch(k)
			{
				case 62:
				{
					if(this.tag)
					{
						if($.inArray(this.tag.toLowerCase() , TAG['short']) != -1)
						{
							this.insert(' />');
							this.release();
							return false;
						}
						this.insert('</' + this.tag + '>');
						this.resetCaret(-(3 + this.tag.length));
					}
					break;
				}
				case 47:
				{
					if(this.tag && $.inArray(this.tag.toLowerCase() , TAG['short']) != -1)
					{
						this.insert(' />');
						this.release();
						return false;
					}
					break;
				}

			}
		}
		else
		{
			jQuery('#autolist').hide();
			this.autolistCapture = false;
		}
	},

	autoList : function()
	{
		var list = [];
		var func = '';
		var len = 0;
		if(this.tagCapture)
		{
			var tag = TAG['short'].concat(TAG['normal']);
			for(var i = 0 ; i < tag.length ; i++)
			{
				if(tag[i].indexOf(this.tag.toLowerCase()) == 0)
				{
					list.push(tag[i]);
					len = Math.max(len , tag[i].length);
				}
			}
			func = 'tagSelect';
		}
		else if (this.propertyCapture)
		{
			for(var i = 0 ; i < PROPERTY.length ; i++)
			{
				if(PROPERTY[i].indexOf(this.property.toLowerCase()) == 0 && $.inArray(PROPERTY[i] , this.propertys) == -1)
				{
					list.push(PROPERTY[i]);
					len = Math.max(len , PROPERTY[i].length);
				}
			}
			func = 'propertySelect';
		}
		else if(this.extendPropertyCapture)
		{
			for(var i = 0 ; i < arguments[0].length ; i++)
			{
				list.push(arguments[0][i]);
				len = Math.max(len , arguments[0][i].length);
			}
			func = '_propertySelect';
		}

		list.sort();
		if(list.length > 0)
		{
			var html = '';
			var cls = '';
			for(var i = 0 ; i< list.length;i++)
			{
				cls = "";
				if(i == 0)
				{
					cls = 'class="current"';
				}
				if(this.tagCapture && list[i] == this.tag)
				{
					cls = 'class="current"';
				}
				else if(this.propertyCapture && list[i] == this.property)
				{
					cls = 'class="current"';
				}

				html += '<li ' + cls + ' index="'+ (i + 1) + '" onclick="editor.' + func + '($(this).text());" onmouseover="$(this).addClass(\'current\');" onmouseout="$(this).removeClass(\'current\');">' + list[i] +'</li>';
			}
			var caretPosition = this.caretPosition();
			$('#autolist').html('<ul>' + html + '</ul>').css({'position':'absolute' , 'top' : caretPosition.top + 20 , 'left' : caretPosition.left - 10  , 'width' : len * 10}).show();
			this.autoListCapture = true;
			this.currentCaretOffset = this.caretOffset();
		}
		else
		{
			$('#autolist').hide();
			this.autoListCapture = false;
		}
	},

	tagSelect : function(tag)
	{
		var fix = tag.substring(this.tag.length);
		this.insert(fix);
		$('#autolist').hide();
		this.autoListCapture = false;
	},

	propertySelect : function(property)
	{
		var fix = property.substring(this.property.length);
		fix += '=""'
		this.insert(fix);
		if(property == 'action' || property == 'href' || property == 'src' || property == 'url')
		{
			var caretPosition = this.caretPosition();
			$('#browser').css({'position':'absolute' , 'top' : caretPosition.top + 6 , 'left' : caretPosition.left}).show().addClass('browser');
			$('#browser a').addClass(property);
			var p1 = this.tag;
			$('#browser a').get(0).onclick = function(){alert(p1);alert(property);return false;};
			this.browserCapture = true;
		}
		this.resetCaret(-1);
		this.property = '';
		$('#autolist').hide();
		this.autoListCapture = false;
		if(_PROPERTY[property])
		{
			if(_PROPERTY[property] instanceof Array)
			{
				this.propertyCapture = false;
				this.extendPropertyCapture = true;
				this.autoList(_PROPERTY[property]);
			}
			else
			{
				if(_PROPERTY[property][this.tag])
				{
					this.propertyCapture = false;
					this.extendPropertyCapture = true;
					this.autoList(_PROPERTY[property][this.tag]);
				}
			}
		}
	},

	_propertySelect : function(_property)
	{
		this.insert(_property);

		$('#autolist').hide();
		this.autoListCapture = false;
		this.extendPropertyCapture = false;
	},

	release : function()
	{
		$('#autolist').hide();
		$('#browser').hide();
		this.capture = false;
		this.captureCaretOffset = 0;
		this.currentCaretOffset = 0;
		this.tag = '';
		this.tagCapture = false;
		this.property = '';
		this.propertyCapture = false;
		this.autoListCapture = false;
		this.captureInput = '';

		this.browserCapture = false;
		$('#browser').hide();

		$('#debug').html('');
		$('#input').val('');
	},

	focus : function(id)
	{
		this.init();

		this.id = id;
		this.obj = $('#' + id);
		this.dobj = this.obj.get(0);
		this.value = this.dobj.value;

		if(this.obj.data('init')) return false;
		this.obj.data('init' , 1);
		this.linesInit(1 , 0);
		this.dobj.onkeypress = function(event){return editor.process(event);};
		this.dobj.onkeydown = function(event) {return editor.keyDown(event);};
		this.dobj.onkeyup = function(event)   {return editor.keyUp(event);	};
		this.dobj.onscroll = function(){$('#' + this.id + '_line').scrollTop($(this).scrollTop());};
		this.dobj.onclick = function(){return editor.captureCaret();};

	},

	captureCaret : function()
	{
		this.currentCaretOffset = this.caretOffset();

		this.value = this.dobj.value;
		if(this.isIE)
		{
			this.value = this.value.replace(/\r/ig , "");
		}

		var left = this.value.substr(0 , this.currentCaretOffset);
		var right = this.value.substring(this.currentCaretOffset + 1 , this.value.length);
		var pos1 = left.lastIndexOf('<');
		var pos2 = left.lastIndexOf('>');
		var pos3 = left.lastIndexOf("\n");

		//alert(this.currentCaretOffset + '|' + pos1 + '|' + pos2 + '|' + pos3);

		if(pos1 == -1 || pos2 > pos1 || pos3 > pos1)
		{
			this.release();
			return false;
		}
			left = left.substring(pos1 , this.currentCaretOffset);
		this.value = this.value.substring(pos1 , this.value.length);
		var pattern = /^<([a-z][a-z0-9]*)([^>\n]*>?)/i;
			pattern.lastIndex = 0;
		var m = this.value.match(pattern);
		this.release();
		if(m)
		{
			m.push(left);
			this.capture = true;
			if(left.length == m[1].length + 1) //选中标签
			{
				this.tagCapture = true;
				this.property = '';
				this.propertyCapture = false;
			}
			else
			{
				this.tagCapture = false;
				this.property = '';
				var pattern = /\s([a-z]+)$/i;
					pattern.lastIndex = 0;
				var p = m[3].match(pattern);
				var c = m[0].charAt(m[3].length);
				if(p && (c == '' || c == '=' || c == ' ' || c == '>' || c == '/' || c == '"' || c == '\'')) //选中属性
				{
					this.property = p[1];
					this.propertyCapture = true;
				}
			}
			return m;
		}
		else
		{
			this.release();
			return [];
		}
	},

	linesInit : function(init , key)
	{
		var line = '';
		if(init)
		{
			this.value = this.obj.val();
			var l = this.value.match(/\n/ig);
			this.line = ( l ? l.length : 0 ) + 1;
			for(var i = 0 ; i < this.line ; i ++)
			{
				line += (i + 1)  + "\n";
			}
			$('#' + this.id + '_line').val(line);
			$('#' + this.id + '_line').scrollTop($(this.dobj).scrollTop());
			return false;
		}
		else
		{

		}

		this.value = this.obj.val();
		var l = this.value.match(/\n/ig);
		var line_count = ( l ? l.length : 0 ) + 1;
		if(this.line == line_count)
		{
		}
		else
		{
			var line = $('#' + this.id + '_line').val();
			if(line == '')
			{
				for(var i = 0 ; i < line_count ; i ++)
				{
					line += (i + 1)  + "\n";
				}
			}
			else
			{
				if(this.line > line_count)
				{
					line = '';
					for(var i = 0 ; i < this.line ; i ++)
					{
						line += (i + 1)  + "\n";
					}
				}
				else
				{
					for(var i = this.line ; i < line_count ; i ++)
					{
						line += (i + 1)  + "\n";
					}
				}
			}
			this.line = line_count;
			$('#' + this.id + '_line').val(line);
			$('#' + this.id + '_line').scrollTop($(this.dobj).scrollTop());
		}
	},

	caretOffset : function()
	{
		if(this.dobj.selectionStart)
		{
			return this.dobj.selectionStart
		}
		else if(!document.selection)
		{
			return 0;
		}

		var c = String.fromCharCode(1);
		var s = document.selection.createRange();
		if(s.boundingWidth)
		{
			return false;
		}
		s.text = c;
		var r = this.dobj.createTextRange();
		var l = r.text.replace(/\r/ig , '').indexOf(c);
		s.moveStart('character' , -1);
		s.text = ''
		return l;
	},

	caretPosition : function()
	{
		if (window.getSelection)
		{
			return {'top' : 200 , 'left' : 200 };
		}
		else if(document.selection)
		{
			var d = document.selection.createRange();
			var p = this.obj.offset();
				p = {'top' : $(window).scrollTop() , 'left':$(window).scrollLeft()};
			return {'top' : d.offsetTop + p.top, 'left' : d.offsetLeft + p.left};
		}
		else
		{
			return {'top' : 0 , 'left' : 0 };
		}
	},

	insert : function(str)
	{
		if(this.isIE)
		{
			//this.dobj.focus();	/*外部调用方法时候必须加这个*/
			document.selection.createRange().text = str;
		}
		else
		{
			var l = this.dobj.value.length;
			var s = this.dobj.selectionStart;
			var p = this.obj.scrollTop();
			this.dobj.value = this.dobj.value.substr(0,s) + str + this.dobj.value.substring(s,l);
			this.dobj.selectionStart = this.dobj.selectionEnd = s + str.length;
			this.obj.scrollTop(p);
		}
	},

	getSelectionText : function()
	{
		if(this.isIE)
		{
			return document.selection.createRange().text;
		}
		else
		{
			return this.dobj.value.substring(this.dobj.selectionStart , this.dobj.selectionEnd);
		}
	},

	replaceSelectionText : function(toString)
	{
		if(this.isIE)
		{
			var r = document.selection.createRange();
			var r1 = r.duplicate();
			if(r.text.length == 0)
			{
				return false;
			}
			else
			{
				r.text = toString;
				r.setEndPoint("StartToStart",r1);
				r.select();
			}
		}
		else
		{
			var ss = this.dobj.selectionStart;
			var se = this.dobj.selectionEnd;
			var str = this.dobj.value.substring(ss,se);
			if(str.length == 0)
			{
				return false
			}
			else
			{
				str = toString;
				this.dobj.value = this.dobj.value.substring(0 , ss) + str + this.dobj.value.substring(se,this.dobj.value.length);
				this.dobj.setSelectionRange(ss,ss + str.length);
			}
		}
	},

	keyDown : function(event)
	{
		event = event || window.event;
		var k = event.keyCode || event.which;
		switch(k)
		{
			case 9:
			{
				if(this.isIE)
				{
					var r = document.selection.createRange();
					var r1 = r.duplicate();
					if(r.text.length == 0)
					{
						this.insert(this.keyTab);
					}
					else
					{
						if(event.shiftKey)
						{
							var pattern = new RegExp('^' + this.keyTab + '' , 'mg');
								pattern.lastIndex = 0;
							r.text = r.text.replace(pattern , "");
						}
						else
						{
							r.text = r.text.replace(/^(.)/mg , this.keyTab + "$1");
						}
						r.setEndPoint("StartToStart",r1);
						r.select();
					}
				}
				else
				{
					var ss = this.dobj.selectionStart;
					var se = this.dobj.selectionEnd;
					var str = this.dobj.value.substring(ss,se);
					if(str.length == 0)
					{
						this.insert(this.keyTab);
					}
					else
					{
						if(event.shiftKey)
						{
							var pattern = new RegExp('^' + this.keyTab + '' , 'mg');
								pattern.lastIndex = 0;
							str = str.replace(pattern , "");
						}
						else
						{
							str = str.replace(/^(.)/mg , this.keyTab + "$1");
						}
						this.dobj.value = this.dobj.value.substring(0 , ss) + str + this.dobj.value.substring(se,this.dobj.value.length);
						this.dobj.setSelectionRange(ss,ss + str.length);
					}
				}
				return false;
				break;
			}
			case 46: //delete
			{
				this.linesInit(0 , k);
				break;
			}
			case 8: //backspace
			{
				this.process(event);
				break;
			}
			case 37: //left
			case 39: //right
			{
				this.autoListCapture = false;
				$('#autolist').hide();
				this.browserCapture = false;
				$('#browser').hide();
				break;
			}
			case 38: //up
			case 40: //down
			{
				if(this.autoListCapture)
				{
					var list = $('#autolist').children('ul').children();
					var l = list.length;
					var c =  $('#autolist').children('ul').children('[class="current"]').attr('index');
					if(!c)
					{
						c = 1;
					}
					else if(k == 37 || k == 38)
					{
						c --;
						c = c > 0 ? c : l;
					}
					else
					{
						c++;
						c = c > l ? 1 : c;
					}
					$('#autolist').children('ul').children('[class="current"]').removeClass('current');
					$('#autolist').children('ul').children('[index="' + c + '"]').addClass('current');
					//event.stopPropagation();
					return false;
				}
				else
				{
					$('#autolist').hide();
					this.release();
				}
				break;
			}
			case 13 :
			//case 32 :
			{
				if(this.autoListCapture)
				{
					var t =  $('#autolist').children('ul').children('[class="current"]').text();
					if(t)
					{
						if(this.tagCapture)
						{
							this.tagSelect(t);
							return false;
						}
						else if(this.propertyCapture)
						{
							this.propertySelect(t);
							return false;
						}
						else if(this.extendPropertyCapture)
						{
							this._propertySelect(t);
							return false;
						}
					}
				}
				if(this.browserCapture)
				{
					$('#browser a').get(0).click();
					return false;
				}

				var pos = this.caretOffset();
				this.value = this.dobj.value.replace(/\r/g , '');
				var left = this.value.substr(0 , pos);
				var pos2 = left.lastIndexOf("\n");
					left = left.substring(pos2);
				var pattern = new RegExp('^[\t\s ]*' , 'mg');  /*???*/
					pattern.lastIndex = 0;
				var match = left.match(pattern);
				if(match[1])
				{
					this.insert("\n"  + match[1]);
					this.resetCaret(0);
					return false;
				}
				break;
			}
			default:
			{
				if(event.ctrlKey)
				{
					if(k == 85)
					{
						var str = this.getSelectionText();
						str = str.toUpperCase();
						this.replaceSelectionText(str);
						return false;
					}
					else if(k == 76)
					{
						var str = this.getSelectionText();
						str = str.toLowerCase();
						this.replaceSelectionText(str);
						return false;
					}
				}
			}
		}
		return true;
	},

	keyUp : function(event)
	{
		event = event || window.event;
		var k = event.keyCode || event.which;
		switch(k)
		{
			case 8:		//del
			case 10:	//\r
			case 13:	//\n
			//case 46:  // 不能在这算 delete
			{
				this.linesInit( 0 , k);
				break;
			}
			case 37:
			case 38:
			case 39:
			case 40:
			{
				if(!this.autolistCapture)
				{
					this.captureCaret();
				}
				break;
			}
			case 86:
			{
				if(event.ctrlKey)
				{
					this.linesInit(1 , 0);
				}
				break;
			}
		}
	},

	debug : function(obj)
	{
		var html = '';
		for(var i in obj)
		{
			html += '[' + i + ']:' + obj[i] + "\n";
		}
		alert(html);
	}

};

