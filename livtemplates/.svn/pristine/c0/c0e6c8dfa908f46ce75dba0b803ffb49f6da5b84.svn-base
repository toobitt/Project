$.widget("hoge.hogeDate",{
	options:{
			showId:'program_date',
			valueId:'dates',
			defaultValue:'2014-01-01',
			sort : 0,
			type : 0,
			extra_onclick : '',
			year : 0,
			mon:0
	},
	_init: function() {   
        var nstr = new Date(); //当前Date资讯
		this.ynow = nstr.getFullYear(); //年份
		this.mnow = nstr.getMonth(); //月份
		this.nows = nstr.getFullYear() + '-' + nstr.getMonth() + '-' + nstr.getDate(); //今日日期
		this.load_param();
		this.hgDate(this.options.showId,this.options.valueId,this.options.defaultValue,this.options.sort,this.options.type,this.options.extra_onclick);
		this._on({
				'click .default':'setValue',
				'click .list_week' : 'setValue',
				'click .switch' : 'switchBy'
			});
    },
	hgDate:function(showId,valueId,dates,sort,type,extra_onclick)
	{
		this.showId = showId;
		this.valueId = valueId;
		this.sort = sort ? sort : 0;
		this.type = type ? type : 0;
		this.extra_onclick = extra_onclick ? extra_onclick : '';
		var year = mon = 0;
		if(dates)
		{
			var obj = dates.split('-');
			year = Number(obj[0]);
			mon = Number(obj[1])-1;
			this.sdate = dates;
		}
		var ret = this.show(year,mon);
		document.getElementById(this.showId).innerHTML = ret;
	},
	load_param: function(ynow,mnow){
		if(ynow)
		{
			this.ynow = ynow;//月份
			this.mnow = mnow;//年份
		}
		var n1str = new Date(this.ynow,this.mnow,1); //当月第一天Date资讯
		this.firstday = n1str.getDay(); //当月第一天星期几
		this.m_days = new Array(31,28+this.is_leap(this.ynow),31,30,31,30,31,31,30,31,30,31); //各月份的总天数
		this.tr_str = Math.ceil((this.m_days[this.mnow] + this.firstday)/7); //表格所需要行数	
	},
	get_days: function(ynow,mnow)
	{
		var n1str = new Date(ynow,mnow,1); //当月第一天Date资讯
		firstday = n1str.getDay(); //当月第一天星期几
		return (new Array(31,28+this.is_leap(ynow),31,30,31,30,31,31,30,31,30,31)); //各月份的总天数
	},
	is_leap: function(year){
		return (year%100==0 ? res=(year%400==0 ? 1 : 0) : res=(year%4==0 ? 1: 0)); 
	},
	which_day:function(dnow){
		 var from=new Date(this.ynow,this.mnow,dnow);
		 return from.getDay();
	},
	show: function(ynow,mnow) {
		var nstr = new Date(); //当前Date资讯
		if(!ynow)
		{
			mnow = nstr.getMonth(); //月份
			ynow = nstr.getFullYear(); //年份
		}

		if(this.type)
		{
			this.date_top = Array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
		}
		else
		{
			this.date_top = Array('日','一','二','三','四','五','六');
		}
		this.load_param(ynow,mnow);
		var html = '<table id="' + this.valueId + '_table" align="center" width="245" class="date_table" cellspacing="0">';

		var pre_mon = mnow-1;
		var pre_years = ynow;
		if(pre_mon<0)
		{
			pre_mon = 11;
			var pre_years = ynow - 1 ;
		}

		var next_mon = mnow + 1;
		var next_years = ynow;
		if(next_mon == 12)
		{
			next_mon = 0;
			next_years = ynow + 1 ;
		}
		html +='<tr class="top"><td class="btn"><span class="left switch" _year="' + pre_years + '" _month="' + pre_mon + '"></span></td>'+
		'<th align="center" colspan="5" class="day_show">' + ynow + '年' + ((mnow+1)<10?'0'+(mnow+1):(mnow+1)) + '月</th>'+
		'<td class="btn"><span class="right switch" _year="' + next_years + '" _month="' + next_mon + '"></span></td></tr><tr>';

		for(j=0;j < 7;j++)
		{
			html += '<td align="center" class="date_th">' + this.date_top[j] + '</td>';
		}
		html += '</tr>';
		var con = '';
		var sdate = this.nows;
		if(this.sdate)
		{
			sdate = this.sdate;
		}
		for(i=0 ; i < this.tr_str; i++) { //表格的行
			var td = weeks = space = '';
			var nweek = 0;
			for(k=0 ; k < 7; k++) { //表格每行的单元格
				idx = i*7+k; //单元格自然序列号
				date_str = idx - this.firstday+1; //计算日期
				var css = clicks = '';
				if(date_str <=0)
				{
					var pre_num = mnow-1;
					if(pre_years != ynow)
					{
						pre_num = 11;
					}
					date_str = this.m_days[pre_num] + date_str;	
					css = 'dised';
				}else if(date_str > this.m_days[mnow])
				{
					date_str = date_str - this.m_days[mnow];
					css = 'dised';
				}else{
					if((ynow + '-' + ((mnow+1)<10 ? '0' + (mnow+1) : (mnow+1)) + '-' + (date_str<10 ? '0' + date_str : date_str)) == sdate)
					{
						css = 'current';
						nweek = 1;
					}
					clicks = ynow + '-' + ((mnow+1)<10?'0'+(mnow+1):(mnow+1)) + '-' + (date_str<10 ? '0' + date_str : date_str);
				}
				if(this.which_day(date_str) == 6 || this.which_day(date_str) == 0)
				{
					if(!css)
					{
						css = 'week';
					}
				}
				if(clicks && !this.sort)
				{
					td += '<td><span class="default ' + css + '" _date="' + clicks + '">' + date_str + '</span></td>';//onclick="this.setValue(this,\'' + clicks + '\');"
				}
				else
				{
					td += '<td><span class="none_default ' + css + '">' + date_str + '</span></td>';
				}
				if(this.sort)
				{
					weeks += space + (ynow + '-' + ((mnow+1)<10?'0'+(mnow+1):(mnow+1)) + '-' + (date_str<10 ? '0' + date_str : date_str));
					space = "*";
				}
			}
			if(this.sort)
			{
				con += '<tr class="list_week ' + (nweek?'week_current':'') + '" _date="' + weeks + '" >';
			}
			else
			{
				con += '<tr class="list_day">';
			}
			con += td + '</tr>';//表格的行结束
		}
		html += con + '</table><input type="hidden" value="' + this.sdate + '" id="' + this.valueId + '" name="' + this.valueId + '"/>'; //表格结束
		return html
    },
	switchBy:function(event)
	{
		var e = $(event.target);
		var year = parseInt(e.attr('_year'));
		var mon = parseInt(e.attr('_month'));
		var ret = this.show(year,mon);
		document.getElementById(this.showId).innerHTML = ret;
	},
	getYearWeek:function(a, b, c)
	{
		var d1 = new Date(a, b-1, c), d2 = new Date(a, 0, 1), 
		d = Math.round((d1 - d2) / 86400000); 
		return Math.ceil((d + ((d2.getDay() + 1) - 1)) / 7); 
	},
	setValue:function(event)
	{
		var e = $(event.target);
		var dates = e.attr('_date');
		if(this.sort)
		{
			$('#' + this.valueId).val(dates);
			var obj_all = document.getElementById(this.valueId+'_table').getElementsByTagName('tr');
			for(i=0;i<obj_all.length;i++)
			{
				if(obj_all[i].className=="list_week week_current")
				{
					obj_all[i].className = 'list_week';
				}
			}
			e.className = 'list_week week_current';
		}
		else
		{
			$('#' + this.valueId).val(dates);
			var obj_all = document.getElementById(this.valueId+'_table').getElementsByTagName('span');
			for(i=0;i<obj_all.length;i++)
			{
				if($(obj_all[i]).attr('class').indexOf("current") > -1)
				{
					if(this.which_day(Number($(obj_all[i]).html())) == 6 || this.which_day(Number($(obj_all[i]).html())) ==0 )
					{	
						$(obj_all[i]).removeClass('current').addClass('default week');
					}
					else
					{
						$(obj_all[i]).removeClass('current');
					}
				}
			}
			e.attr('class','current');
		}
		this.extra_click(event);
	},
	extra_click:function(e)
	{
		this._trigger("extra_click", e);
	}
});