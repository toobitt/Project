(function($){
    $.widget('plan.calendar', {
        options : {
            year : 0,
            month : 0,
            day : 0
        },

        _create : function(){
            if(!this.options.year){
                var date = new Date();
                this.options.year = date.getFullYear();
                this.options.month = date.getMonth();
                this.options.day = date.getDate();
            }

            var root = this.element;
            $('<div class="calendar-box">' +
                '<div class="calendar-prev">&lt;&lt;<span></span></div>' +
                '<div class="calendar-next"><span></span>&gt;&gt;</div>' +
                '<div class="calendar-ym"></div>' +
                '<div class="calendar-days"></div>' +
                '</div>').appendTo(root);
            this.calendar = root.find('.calendar-box');
            this.prev = root.find('.calendar-prev');
            this.next = root.find('.calendar-next');
            this.ym = root.find('.calendar-ym');
            this.days = root.find('.calendar-days');


            this._createDays();
            this._createPrev();
            this._createNext();
            this._createYearAndMonth();
        },

        _init : function(){
            this._on(this.prev, {
                click : '_prevMonth'
            });

            this._on(this.next, {
                click : '_nextMonth'
            });


            this._on({
                'change .calendar-year' : '_changeYear',
                'change .calendar-month' : '_changeMonth'
            });
        },

        _createPrev : function(){
            var prevMonth = this.options.month - 1;
            prevMonth == -1 && (prevMonth = 11);
            this.prev.find('span').html(prevMonth + 1);
        },

        _createNext : function(){
            var nextMonth = this.options.month + 1;
            nextMonth == 12 && (nextMonth = 0);
            this.next.find('span').html(nextMonth + 1);
        },

        _createYearAndMonth : function(){
            var number = 10;
            var perNumber = number / 2;
            var _this = this;
            var year = parseInt(this.options.year);
            var yearSelect, monthSelect;
            yearSelect = '<select class="calendar-year">';
            $.each(new Array(number), function(i, n){
                var currentYear = year - perNumber + (++i);
                yearSelect += '<option value="'+ currentYear +'" ' + (year == currentYear ? 'selected="selected"' : '') + '>'+ currentYear +'</option>';
            });
            yearSelect += '</select>';

            var month = this.options.month + 1;
            monthSelect = '<select class="calendar-month">';
            $.each(new Array(12), function(i, n){
                i++;
                monthSelect += '<option value="'+ i +'" ' + (month == i ? 'selected="selected"' : '') + '>'+ i +'</option>';
            });
            monthSelect += '</select>';

            this.ym.html(yearSelect + '年' + monthSelect + '月');
        },

        _beforeCreateDays : function(cb){
            this.days.html('<img src="' + RESOURCE_URL + 'loading2.gif" style="width:30px;"/>');
            setTimeout(cb, 100);
        },

        _createDays : function(){
            var days = this._getDaysInMonth(this.options.year, this.options.month);
            if(!days) return;
            var index = -1;
            var daysHtml = '';
            var _this = this;
            var isWeeken;
            $.each(new Array(days), function(i, n){
                i++;
                index = _this._getWeek(_this.options.year, _this.options.month, i);
                isWeeken = false;
                if(index == 0 || index == 6){
                    isWeeken = true;
                }
                daysHtml += '<span class="calendar-item'+ (isWeeken ? ' calendar-weeken' : '') + (i == _this.options.day ? ' calendar-currentday' : '') +'">'+ i +'</span>';
            });

            this.days.html(daysHtml);
        },

        _prevMonth : function(){
            if(this.options.month == 0){
                this.options.year -= 1;
                this.options.month = 11;
            }else{
                this.options.month--;
            }
            this._refresh();
        },

        _nextMonth : function(){
            if(this.options.month == 11){
                this.options.year += 1;
                this.options.month = 0;
            }else{
                this.options.month++;
            }
            this._refresh();
        },

        _changeYear : function(event){
            var target = $(event.currentTarget);
            this.options.year = target.val();
            this._refresh();
        },

        _changeMonth : function(event){
            var target = $(event.currentTarget);
            this.options.month = target.val() - 1;
            this._refresh();
        },

        _refresh : function(){
            var _this = this;
            this._createPrev();
            this._createNext();
            this._createYearAndMonth();
            this._beforeCreateDays(function(){
                _this._createDays();
            });
        },

        _getWeek : function(year, month, day){
            return new Date(year, month, day).getDay();
        },

        _getDaysInMonth : function(year, month){
            month = parseInt(month) + 1;
            return new Date(year, month, 0).getDate();
        },

        _destroy : function(){

        }
    });
})(jQuery);