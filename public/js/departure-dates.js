//console.log('loading');
jQuery(document).ready(function($){
    function departure_grouping_dates(){
        for(i=2018;i<=2020;i++){
            for(j=0;j<=11;j++){

                var formattedNumber = ("0" + j).slice(-2);
                var months = ['January', 'February', 'March', 'April', 'May','June', 'July', 'August', 'September','October', 'November', 'December'];
                var actual_month = parseInt(j)-1;
                jQuery('[data-year="'+i+'"][data-month="'+formattedNumber+'"]').first().before('<tr class="table-info grp-list"><td>'+months[actual_month]+' '+i+' </td><td></td><td></td></tr>');

            }
        }
    }
    departure_grouping_dates();

    // ajax filter js
    $(document).on('change','.search-ajax-wrp select',function(){
        //debugger;
        var search_year =$('#search-ajax-year').val().replace('yearlist','');
        var search_month =$('#search-ajax-month').val().replace('monthlist','');
        var post_id = admin_ajax.postID ;
        var search_group = $('#search-ajax-group').val();
        var departure_post_id = parseInt($('.departure-pid').html());

        //Reset the months droopdown to avoid conflicts with years
        var detect_selected = $(this).find("option:selected").val();
        if(detect_selected.indexOf('yearlist') != -1){
            $('#search-ajax-month').val("monthlist0");
            var search_month = 0;
        }
        //animate loading
        $('#booking-dates').hide();
        $('.overlay-table').show();

        $.ajax({
            url:'http://localhost/projects/finedine-gutenburg/wp-admin/admin-ajax.php',
            type : 'post',
            data:{
                action:'trip_departure_date_ajax_filter',
                post_id:post_id,
                departure_post_id:departure_post_id,
                search_year:search_year,
                search_month:search_month,
                search_group:search_group
            },
            success:function(response){
                //console.log(response);
                $('.overlay-table').hide();
                $('#booking-dates').show();
                $('.departure-result-table tbody').html(response);
                departure_grouping_dates();

            }
        });
    });

    //reset button
    $(document).on('click','.reset-btn',function(){
        $('#search-ajax-year').val('yearlist0');
        $('#search-ajax-month').val('monthlist0');
        $('#search-ajax-group').val('0');
        $('#search-ajax-year').trigger('change');
    });

});



