jQuery(function($){
    function getCurrentDay(date){
        return (date || '').split('-')[2];
    }

    function getCurrentMonth(month){
        return months[month] + '月';
    }

    $('.week-box').on({
        click : function(){
            var prev = !!$(this).hasClass('prev-week-btn');
            var parent = $(this).parent();
            var selfWeek = parent.find('.self-week');
            var imgload = parent.find('.img-load');
            if(imgload.is(':visible')){
                return false;
            }
            selfWeek.css('opacity', 0);
            if(!imgload[0]){
                imgload = $('<img class="img-load" src="' + RESOURCE_URL + 'loading2.gif" style="width:30px;"/>').appendTo(parent);
            }
            imgload.show();

            var channelId = selfWeek.data('channelid');
            var week = prev ? -1 : 1;
            var datesA = selfWeek.find('a');
            var dates = datesA.eq(week == -1 ? 0 : 6).attr('data-date');

            var _this = this;
            $.getJSON(
                'run.php?mid=' + gMid + '&a=get_week',
                {
                    week : week,
                    channel_id : channelId,
                    dates : dates
                },
                function(json){
                    json = json[0];
                    var _week = json['week'];
                    var _isSchedule = json['is_schedule'];
                    var _month = getCurrentMonth(parseInt(json['month']) - 1);
                    parent.find('.self-month').html(_month);
                    if(_week){
                        $.each(_week, function(i, n){
                            var dateA = datesA.eq(i).html(getCurrentDay(n));
                            dateA.attr('href', dateA.attr('href').replace(/(&dates=)[^&]*/, '$1' + n));
                            dateA.attr('data-date', n);
                            dateA.removeClass('is-set current-index');
                            if(_isSchedule[i] > 0){
                                dateA.addClass('is-set');
                            }
                            if(today == n){
                                dateA.addClass('current-index');
                            }
                        });
                    }
                    imgload.hide();
                    selfWeek.css('opacity', 1);
                }
            );
        }
    }, '.prev-week-btn, .next-week-btn');

    $('.self-week, .self-week-title').each(function(){
        $('a', this).eq(currentIndex).addClass('current-index');
    });
});