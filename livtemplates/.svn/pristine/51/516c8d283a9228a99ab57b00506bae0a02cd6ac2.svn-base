jQuery(function($){

    var matchs = location.href.match(/dates=([^&]+)/);
    var currentDate = matchs ? matchs[1] : '';


    $('#plan-box').plans();
    $('#calendar-box').calendar();
    $('#file-box').file();
    $('#stream-box').stream();
    $('#channel-box').channel({
        date : currentDate
    });

    $('#plan-option-btn').on({
        click : function(){
            var state = $(this).data('state');
            $('#plan-box').plans(state ? 'outEditing' : 'inEditing');
            $('#select-box').select(state ? 'dragEnable' : 'dragDisable');
            $(this).data('state', !state).html(!state ? $(this).attr('_outedit') : $(this).attr('_inedit'));
        },

        _show : function(){
            $(this).show();
        },

        _hide : function(){
            $(this).hide();
        }
    });

    $('.need-mytime').mydate({
        timeBox : $.timeBox()
    });

    $('.date-pic').hms();
});