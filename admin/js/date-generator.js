//new date genereate code
jQuery(document).ready(function ($) {

    //add title automatically after selecting trip
    jQuery('#select-trip-name select').on('change',function(){
        var title=jQuery(this).val();
        jQuery('#title-prompt-text').html('')
        jQuery('#titlewrap #title').val(title);
    });


    if($('.acf-field-repeater.generate-date').length > 0) {
        //check for status option from plugin
        var trip_status_option_data = trip_departure_plugin.trip_status_option;
        var str_option = trip_status_option_data.split(',');
        console.log(str_option);
        var str = '<div class="bulk-dates"><p>Generate Small Group Journey Departure Dates</p>';
        str += '<label for="generate-trip-days">Days: <select multiple="multiple" id="generate-trip-days"><option value="" selected="selected">- Select Days</option><option value="0">Sunday</option><option value="1">Monday</option><option value="2">Tuesday</option><option value="3">Wednesday</option><option value="4">Thursday</option><option value="5">Friday</option><option value="6">Saturday</option></select></label>';
        str += '<label for="generate-trip-months">Months: <select multiple="multiple" id="generate-trip-months"><option value="" selected="selected">- Select Months</option><option value="0">January</option><option value="1">February</option><option value="2">March</option><option value="3">April</option><option value="4">May</option><option value="5">June</option><option value="6">July</option><option value="7">August</option><option value="8">September</option><option value="9">October</option><option value="10">November</option><option value="11">December</option></select></label>';
        str += '<label for="generate-trip-year">Year: <select id="generate-trip-year"><option value="" selected="selected">- Select Year</option><option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option><option value="2019">2019</option><option value="2020">2020</option></select></label>';
        str += '<p><div>Enter default value. Use default value 0 in case of no price.</div><label for="default-departure-price">Price: <input type="text" id="default-departure-price" value="0"></label>';
        str += '<label for="default-departure-discount">Discount: <input type="text" id="default-departure-discount" value="0"></label>';
        if(str_option[0]){
            str += '<label for="default-departure-status"> Status: <select id="default-departure-status"><option value="'+str_option["0"]+'">'+str_option["0"]+'</option><option value="'+str_option["1"]+'">'+str_option["1"]+'</option><option value="'+str_option["2"]+'">'+str_option["2"]+'</option><option value="'+str_option["3"]+'">'+str_option["3"]+'</option></select></label></p>';
        }else {
            str += '<label for="default-departure-status"> Status: <select id="default-departure-status"><option value="Guaranteed when Book">Guaranteed when Book</option><option value="Fill up fast">Fill up fast</option><option value="4 places left">4 places left</option></select></label></p>';
        }
        str += '<p ><a href="#" class="button button-primary button-large generate-btn">Generate</a></p>';
        //add another new fields for auto apend html here
        str += '</div>';
        $(".acf-field-repeater.generate-date").prepend(str);
    }

    //days in month function
    function daysInMonth(year, month) {
        month++;
        return new Date(year, month, 0).getDate();
    }

    function CompareDates( d1, d2 ) {
        if ( d1 < d2 ) return -1; // d1 is in the past of d2
        if ( d1 > d2 ) return 1;  // d1 is in the future of d2
        return 0;
    }
    //on click generate date
    $(document).on('click', '.generate-btn', function (e) {
        e.preventDefault();
        var i = $(this);
        i.addClass('disable-click');
        var days = $('#generate-trip-days').val();
        var months = $('#generate-trip-months').val();
        var year = $('#generate-trip-year').val();

        if(days == '' || months=='' || year=='' ) {
            alert('Please select days, months and year.');
            i.removeClass('disable-click');
            return false;
        }else {

            var sunday = new Array();
            var monday = new Array();
            var tuesday = new Array();
            var wednesday = new Array();
            var thursday = new Array();
            var friday = new Array();
            var saturday = new Array();
            var m = months.toString().split(',');
            var d = days.toString().split(',');
            var duration = $('.trip-duration input').val();//total days for now 14
            var price = $('#default-departure-price').val();
            var discount = $('#default-departure-discount').val();
            var status = $('#default-departure-status').val();
            //add another new field varible here

            //Duration
            if(duration == '') {
                alert('Please enter Trip total days (duration).');
                i.removeClass('disable-click');
                return false;
            }
            if(price == '' || discount == '' || status == '') {
                alert('Please enter default value for Price, Discount and Status.');
                i.removeClass('disable-click');
                return false;
            }
            //debugger;
            m.forEach(function(month) {
                var inMonth = daysInMonth(year, month);
                for( var i = 1; i <= inMonth; i++ ) {
                    var newDate = new Date(year, month, i);
                    if( newDate.getDay() == 0 && d.indexOf('0') >= 0 ) { // If Sunday
                        var _d = ('0'+i).slice(-2);
                        var _m = ('0'+month).slice(-2);
                        sunday.push(_d+'/'+_m+'/'+year);
                    }
                    if( newDate.getDay() == 1 && d.indexOf('1') >= 0 ) { // If Monday
                        var _d = ('0'+i).slice(-2);
                        var _m = ('0'+month).slice(-2);
                        monday.push(_d+'/'+_m+'/'+year);
                    }
                    if( newDate.getDay() == 2 && d.indexOf('2') >= 0 ) { // If Tuesday
                        var _d = ('0'+i).slice(-2);
                        var _m = ('0'+month).slice(-2);
                        tuesday.push(_d+'/'+_m+'/'+year);
                    }
                    if( newDate.getDay() == 3 && d.indexOf('3') >= 0 ) { // If Wednesday
                        var _d = ('0'+i).slice(-2);
                        var _m = ('0'+month).slice(-2);
                        wednesday.push(_d+'/'+_m+'/'+year);
                    }
                    if( newDate.getDay() == 4 && d.indexOf('4') >= 0 ) { // If Thursday
                        var _d = ('0'+i).slice(-2);
                        var _m = ('0'+month).slice(-2);
                        thursday.push(_d+'/'+_m+'/'+year);
                    }
                    if( newDate.getDay() == 5 && d.indexOf('5') >= 0 ) { // If Friday
                        var _d = ('0'+i).slice(-2);
                        var _m = ('0'+month).slice(-2);
                        friday.push(_d+'/'+_m+'/'+year);
                    }
                    if( newDate.getDay() == 6 && d.indexOf('6') >= 0 ) { // If Saturday
                        var _d = ('0'+i).slice(-2);
                        var _m = ('0'+month).slice(-2);
                        saturday.push(_d+'/'+_m+'/'+year);
                    }
                }
            });

            var all_dates = sunday.concat(monday, tuesday, wednesday, thursday, friday, saturday);

            var allDates = Array();
            all_dates.forEach(function(dt) {
                var _dt = dt.split('/');
                allDates.push(new Date(_dt[2], _dt[1], _dt[0]));
            });
            allDates.sort( CompareDates );
            var date_output = Array();
            allDates.forEach(function(_date) {
                var fDate = new Date(_date);
                var dDate = new Date(_date);
                dDate.setDate(dDate.getDate() + (duration - 1));
                var _month = ('0'+(fDate.getMonth() + 1)).slice(-2);
                var _day = ('0'+(fDate.getDate())).slice(-2);
                var _dMonth = ('0'+(dDate.getMonth() + 1)).slice(-2);
                var _dDay = ('0'+(dDate.getDate())).slice(-2);
                date_output.push(_month+'/'+_day+'/'+fDate.getFullYear()+' - '+_dMonth+'/'+_dDay+'/'+dDate.getFullYear());
            });
            console.log(date_output);
            date_output.forEach(function(i) {
                $('.generate-date .acf-actions .acf-button').trigger('click');
                var tr = $('.generate-date  table.acf-table tr:last').prev();
                tr.children('td:nth-child(2)').find('input').val(i);
                tr.children('td:nth-child(3)').find('input').val(price);
                tr.children('td:nth-child(4)').find('input').val(discount);
                tr.children('td:nth-child(5)').find('input').val(status);
                //add another new field auto insert values here
            });
            $('#generate-trip-days, #generate-trip-months, #generate-trip-year, #default-departure-price, #default-departure-discount, #default-departure-status').val('');
            i.removeClass('disable-click');


        }
    });

    //Add delete buttons
    jQuery('.bulk-dates').append('<div class="remove-all-date">Remove all Dates</div>');
    jQuery('.bulk-dates').append('<div class="remove-old-date">Remove old Dates</div>');

    //remove old dates ajax run
    $(document).on ('click','.remove-old-date',function(event) {
        $(this).html('<span><div class="loader"></div><p>Removing old dates...</p></span>');
        var loc = location.search;
        var post_id=loc.substring(loc.lastIndexOf("?")+1,loc.lastIndexOf("&"));
        var postId = post_id.substring(5);
        $.ajax({ url: ajaxurl,
            method: "POST",
            data: {
                action: 'lds_travel_update_repeater_field_tripdates',
                postId: postId ,

                success: function(data) {
                    console.log(data);
                    function remove_loading(){
                        $('.remove-old-date').html('Trip dates Updating.Please Wait..');
                    }
                    function reload_page(){
                        window.location.reload(true);
                    }
                    setTimeout(function(){ remove_loading(); }, 9000);
                    setTimeout(function(){ reload_page(); }, 10000);
                }
            }
        });

    });

    //remove all dates run
    $(document).on ('click','.remove-all-date',function(event) {
        $(this).html('<span><div class="loader"></div><p>Removing all dates...</p></span>');
        var loc = location.search;
        var post_id=loc.substring(loc.lastIndexOf("?")+1,loc.lastIndexOf("&"));
        var postId = post_id.substring(5);
        $.ajax({ url: ajaxurl,
            method: "POST",
            data: {
                action: 'lds_travel_delete_repeater_field_tripdates',
                postId: postId ,
                success: function(data) {
                    console.log(data);
                    function remove_loading(){
                        $('.remove-all-date').html('Trip dates Updating.Please Wait..');
                    }
                    function reload_page(){
                        window.location.reload(true);
                    }
                    setTimeout(function(){ remove_loading(); }, 9000);
                    setTimeout(function(){ reload_page(); }, 10000);
                }
            }
        });

    });

    //date range js
    $('.date-range-picker input').daterangepicker();
    //load date range js again for new added row
    $(document).on('click','.generate-date .acf-button',function(){
        $('.date-range-picker input').daterangepicker();
    });


});

