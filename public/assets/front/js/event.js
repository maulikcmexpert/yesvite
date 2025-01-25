const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

// Create a request object
const request = new XMLHttpRequest();
request.open('GET', '/your-api-endpoint', true);

// Set the X-User-Timezone header
request.setRequestHeader('X-User-Timezone', userTimezone);

// Send the request
request.send();
function getActiveTabPage() {
    let activeTab = $(".event_nav.active");
    let activePage = activeTab.data("page");
    return activePage;
}
getActiveTabPage();


var base_url=$('#base_url').val();
var busy1 = false;
var busy2 = false;
var busy3 = false;
var busy4=false;
var limit = 10;
var offset1 = 0;
var offset2 = 0;
var offset3 = 0;
var offset4 = 0;


var page = '';
var busy=false;

$(document).on('keydown','input[type="text"], textarea', function(e) {
    var currentValue = $(this).val(); // Get the value of the current input/textarea
    if (currentValue === "") {
        if (e.key === " " || e.keyCode === 32) {
            e.preventDefault(); // Prevent spacebar input if empty
        }
    }
}); 

$(document).on('paste','input[type="text"],textarea', function(e) {
    const clipboardData = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
    if ($.trim(clipboardData) === "") {
        e.preventDefault(); 
    }
});

$('#scrollStatus').scroll(function () {
    if (busy1) return; 
    var scrollTop = $(this).scrollTop(); 
    var scrollHeight = $(this)[0].scrollHeight; 
    var elementHeight = $(this).height();
    if (scrollTop + elementHeight >= scrollHeight) {
        busy1 = true;
        offset1 += limit;
        var current_month1="";

        $('.latest_month').each(function () { 
            current_month1=$(this).val();
        });

        // console.log(current_month);
    
        $('.loader').css('display','block');    
        $.ajax({
            url: `${base_url}fetch_upcoming_event`,
            type: 'GET',
            data: { limit: limit, offset: offset1,current_month:current_month1},
            success: function (response) {
                // if(response.view==""){
                //     $('.loader').css('display','none');    
                //     // return;
                //     busy1 = false;

                // }
                if (response.view &&response.view!="") {
                    $('#scrollStatus').append(response.view);
                    busy1 = false; // Allow further AJAX calls
                }else{
                    $('.loader').css('display', 'none');
                    return;
                }
                // hasMore = response.has_more; // Update the `hasMore` flag
                $('.loader').css('display','none');    
            },
            error: function (xhr, status, error) {
                console.error('Error fetching events:', error);
                busy1 = false;
                $('.loader').css('display', 'none');
            }
        });
    }
});

$('#scrollStatus2').scroll(function () {
    if (busy2) return; 
    var scrollTop = $(this).scrollTop(); 
    var scrollHeight = $(this)[0].scrollHeight; 
    var elementHeight = $(this).height();
    if (scrollTop + elementHeight >= scrollHeight) {
        busy2 = true;
        offset2 += limit;
        var current_month2="";

        $('.latest_month_draft').each(function () { 
            current_month2=$(this).val();
        });
        $('.loader').css('display','block');    
        $.ajax({
            url: `${base_url}fetch_draft_event`,
            type: 'GET',
            data: { limit: limit, offset: offset2,current_month:current_month2 },
            success: function (response) {
                if (response.view && response.view!="") {
                    $('#scrollStatus2').append(response.view);
                    busy2 = false; // Allow further AJAX calls

                }else{
                    $('.loader').css('display', 'none');
                    return; 
                }
                // hasMore = response.has_more; // Update the `hasMore` flag
                $('.loader').css('display','none');    
            },
            error: function (xhr, status, error) {
                console.error('Error fetching events:', error);
                busy2 = false;
                $('.loader').css('display','none');    
            }
        });
    }
});

$('#scrollStatus3').scroll(function () {
    if (busy3) return; 
    var scrollTop = $(this).scrollTop(); 
    var scrollHeight = $(this)[0].scrollHeight; 
    var elementHeight = $(this).height();
    if (scrollTop + elementHeight >= scrollHeight) {
        busy3 = true;
        offset3 += limit;
        var current_month3="";
        $('.latest_month_past').each(function () { 
            current_month3=$(this).val();
        });
        $('.loader').css('display','block');    

        $.ajax({
            url: `${base_url}fetch_past_event`,
            type: 'GET',
            data: { limit: limit, offset: offset3,current_month:current_month3},
            success: function (response) {
                if (response.view && response.view!="") {
                    $('#scrollStatus3').append(response.view);
                    busy3 = false;
                }else{
                    $('.loader').css('display', 'none');
                    return;  
                }
                $('.loader').css('display','none');    
            },
            error: function (xhr, status, error) {
                console.error('Error fetching events:', error);
                busy3 = false;
                $('.loader').css('display','none');    
            }
        });
    }
});

$('#all-months-upcoming').css('display','block');

var search_user_ajax_timer = 0;
$(document).on('input','#search_upcoming_event',function(){
    var searchValue = $(this).val();
    var current_month="";

    $('.latest_month').each(function () { 
        current_month=$(this).val();
    });
    clearTimeout(search_user_ajax_timer);

    search_user_ajax_timer = setTimeout(function () {
        $('.loader_up').css('display','block');    
    $.ajax({
        url: `${base_url}search_upcoming_event`,
        type: 'GET',
        data: { searchValue: searchValue,current_month:current_month},
        success: function (response) {
            if (response.view) {
                $('#scrollStatus').html('');
                $('#scrollStatus').html(response.view);
                $('#tabbtn1').text(response.last_month);
                $('#all-months-upcoming').css('display','block');

            }else{
                $('#scrollStatus').html('');
                $('#scrollStatus').html('No Data Found');
                $('#all-months-upcoming').css('display','none');
            }
            // hasMore = response.has_more; // Update the `hasMore` flag
            busy = false;
            $('#loader').hide();
        },
        error: function (xhr, status, error) {
            console.error('Error fetching events:', error);
            busy = false;
            $('#loader').hide();
        },
        complete: function () {
            $('.loader_up').css('display','none');    
           }
    });
}, 750);
});


$(document).on('input','#search_draft_event',function(){
    var searchValue = $(this).val();
    var current_month="";

    $('.latest_month_draft').each(function () { 
        current_month=$(this).val();
    });

    clearTimeout(search_user_ajax_timer);
    search_user_ajax_timer = setTimeout(function () {
        $('.loader_up').css('display','block');    
    $.ajax({
        url: `${base_url}search_draft_event`,
        type: 'GET',
        data: { searchValue: searchValue,current_month:current_month},
        success: function (response) {

            if (response.view) {
                $('#scrollStatus2').html('');
                $('#scrollStatus2').html(response.view);
                $('#tabbtn2').text(response.last_month);

            }else{
                $('#scrollStatus2').html('');
                $('#scrollStatus2').html('No Data Found');
            }
            // hasMore = response.has_more; // Update the `hasMore` flag
            busy = false;
            $('#loader').hide();
        },
        error: function (xhr, status, error) {
            console.error('Error fetching events:', error);
            busy = false;
            $('#loader').hide();
        },
        complete: function () {
            $('.loader_up').css('display','none');    
           }
    })
 }, 750);
});

$(document).on('input','#search_past_event',function(e){
    // e.preventDefault();

    var searchValue = $(this).val();
    var current_month="";

    $('.latest_month_past').each(function () { 
        current_month=$(this).val();
    });

    clearTimeout(search_user_ajax_timer);
    
    
    search_user_ajax_timer = setTimeout(function () {
        $('.loader_up').css('display','block');    
    $.ajax({
        url: `${base_url}search_past_event`,
        type: 'GET',
        data: { searchValue: searchValue,current_month:current_month},
        success: function (response) {

            if (response.view) {
                
                $('#scrollStatus3').html('');
                $('#scrollStatus3').html(response.view);
                $('#tabbtn3').css('display','block');
                $('#tabbtn3').text(response.last_month);
                
                // $('.loader').css('display','none');    

            }else{
                
                $('#scrollStatus3').html('');
                $('#scrollStatus3').html('No Data Found');
                $('#tabbtn3').css('display','none');

                // $('.loader').css('display','none');    
            }
            // hasMore = response.has_more; // Update the `hasMore` flag
            busy = false;
        },
        error: function (xhr, status, error) {
            console.error('Error fetching events:', error);
            busy = false;
            // $('.loader').css('display','none');    
        },
        complete: function () {
         $('.loader_up').css('display','none');    
        }

    });
        }, 750);
});


$(document).on('click','.filter_apply_btn',function(e){
    e.preventDefault();
    var hosting= $(".hosting_chk").is(":checked") ? 1 : 0;
    var invited_to= $(".invited_to_chk").is(":checked") ? 1 : 0;
    var need_to_rsvp= $(".need_to_rsvp_chk").is(":checked") ? 1 : 0;
    var past_event= $(".past_event_chk").is(":checked") ? 1 : 0;

    let page = getActiveTabPage();
    console.log(page);

    if(hosting==0&&invited_to==0&&need_to_rsvp==0){
         toastr.error('Please select any one');
         return;
    }
    $('.loader_filter').css('display','block');
    // console.log(hosting+','+invited_to+','+need_to_rsvp+','+past_event);
    $.ajax({
        url: `${base_url}event_filter`,
        type: 'GET',
        data: { hosting: hosting,invited_to:invited_to,need_to_rsvp:need_to_rsvp,past_event:past_event,page:page},
        success: function (response) {

            console.log(response);
            if(response.page=="upcoming"){
                if (response.view) {
                    $('.all-events-month-show-wrp').css('display','block');
                    $('#scrollStatus').html('');
                    $('#scrollStatus').html(response.view);
                    $('#tabbtn2').text(response.last_month);
                    $('#all-event-filter-modal').modal('hide');
                    $('.loader_filter').css('display','none');

                }else{
                    $('#scrollStatus').html('');
                    $('#scrollStatus').html('No Data Found');
                    $('.all-events-month-show-wrp').css('display','none');
                    $('#all-event-filter-modal').modal('hide');
                    $('.loader_filter').css('display','none');

                }
            }
            if(response.page=="past"){
                if (response.view) {
                    $('.all-events-month-show-wrp').css('display','block');
                    $('#scrollStatus3').html('');
                    $('#scrollStatus3').html(response.view);
                    $('#tabbtn3').text(response.last_month);
                    $('#all-event-filter-modal').modal('hide');
                    $('.loader_filter').css('display','none');

                }else{
                    $('#scrollStatus3').html('');
                    $('#scrollStatus3').html('No Data Found');
                    $('.all-events-month-show-wrp').css('display','none');
                    $('#all-event-filter-modal').modal('hide');
                    $('.loader_filter').css('display','none');

                }
            }
            // hasMore = response.has_more; // Update the `hasMore` flag
            busy = false;
            $('#loader').hide();

        },
        error: function (xhr, status, error) {
            console.error('Error fetching events:', error);
            busy = false;
            $('#loader').hide();
            $('.loader_filter').css('display','none');

        }
    });
    
})


$(document).on('input','#search_draft_event_page',function () {
    var searchValue = $(this).val();

    console.log(searchValue);
    clearTimeout(search_user_ajax_timer);
    
    
    search_user_ajax_timer = setTimeout(function () {
        $('.loader').css('display','block');    
    $.ajax({
        url: `${base_url}search_draft_event`,
        type: 'GET',
        data: { searchValue: searchValue,is_draft_page:1},
        success: function (response) {
            console.log(response);
            
            if (response.view) {
                
                $('.all_drafts_list').html('');
                $('.all_drafts_list').html(response.view);
                $('#draft_page_count').text('('+response.draft_count+')');
                // $('.loader').css('display','none');    

            }else{
                
                $('.all_drafts_list').html('');
                $('.all_drafts_list').html('No Data Found');
                $('#draft_page_count').text('(0)');

                // $('.loader').css('display','none');    
            }
            // hasMore = response.has_more; // Update the `hasMore` flag
            busy = false;
        },
        error: function (xhr, status, error) {
            console.error('Error fetching events:', error);
            busy = false;
            // $('.loader').css('display','none');    
        },
        complete: function () {
         $('.loader').css('display','none');    
        }

    });
        }, 750);
  })


$(document).on('click','.prev',function(){
    var current_month= $('.month').data('year_month');
    console.log(current_month);
    get_month_data(current_month);
});

$(document).on('click','.next',function(){
    var current_month= $('.month').data('year_month');
    console.log(current_month);
    get_month_data(current_month);
});

function get_month_data(current_month){
    $.ajax({
        url: `${base_url}get_total_month_data`,
        type: 'GET',
        data: { current_month: current_month},
        success: function (response) {
            // console.log(response);
            var monthData = response.month_data;

            var totalInvites = monthData.total_month_inivte;
            var totalHosting = monthData.total_month_hosting;
            var totalEvents = monthData.total_event_month;

            $('.month_total_event_invited_to').text(totalInvites);
            $('.month_total_event').text(totalEvents);
            $('.month_total_event_hosting').text(totalHosting);

    
        },
        error: function (xhr, status, error) {
            console.error('Error fetching events:', error);
            busy = false;
            // $('.loader').css('display','none');    
        },
        complete: function () {
         $('.loader').css('display','none');    
        }

    });

}

$(document).on('click','.cancel_event_option',function () {
    var event_id=$(this).data('event_id');
    console.log(event_id);
    $('#reason_to_cancel_event').val('');
    $('#type_cancel').val('');
    $('#cancel_event_id').val(event_id);
});

$(document).on('input', '#type_cancel', function () {
    let value = $(this).val().toUpperCase(); // Convert to uppercase
    value = value.replace(/\s/g, ''); // Remove any spaces
    if (value.length > 6) {
        value = value.substring(0, 6); // Restrict to 6 characters
    }
    $(this).val(value); // Update the value of the input
});


$(document).on('click','#confirm_cancel_event_btn',function () {
    var event=parseInt($('#cancel_event_id').val());
    var reason=$('#reason_to_cancel_event').val();
    var cancel=$('#type_cancel').val();


    if(reason==""){
        toastr.error("Please Enter Reason");
        return;
    }

    if(cancel==""||cancel!="CANCEL"){
        toastr.error("Please Enter CANCEL");
        return;
    }
    $('#home_loader').css('display','block');
    console.log(event);
    $.ajax({
        url: `${base_url}event/cancel_event`,
        type: 'POST',
        data: { event_id: event,reason:reason, _token: $('meta[name="csrf-token"]').attr("content")},
        success: function (response) {
            console.log(response)
            if(response.status==1){
                console.log('upcoming_event_'+response.event_id);
                $('.upcoming_event_' + response.event_id).fadeOut(500, function() {
                    $(this).remove();
                });
                toastr.success("Event Cancelled successfully");
                $('#cancelevent').modal('hide');
                window.location.reload();
                $('#home_loader').css('display','none');


            }
        },
        error: function (xhr, status, error) {
            console.error('Error fetching events:', error);
            busy = false;
            // $('.loader').css('display','none');    
        },
        complete: function () {
         $('#home_loader').css('display','none');    
        }

    });
    
})

$(document).on('click','.all-event-filter-reset',function(){
    // alert();
    $(".hosting_chk").prop('checked', false);
    $(".invited_to_chk").prop('checked', false);
    $('.need_to_rsvp_chk').prop('checked', false);
});

$(document).on("click",".event_nav",function () {
    var page= $(this).data('page');
      if(page=="draft"){
             $('.filter_open').css('display','none');
      }else{
             $('.filter_open').css('display','block');
     }

     $.ajax({
        url: `${base_url}event_filter_data`,
        type: 'GET',
        data: { page: page},
        success: function (response) {
            $(".hosting_chk_lbl strong").text('('+response.hosting+')');
            $(".invited_to_chk_lbl strong").text('('+response.invited_to+')');
            $('.need_to_rsvp_chk_lbl strong').text('('+response.need_to_rsvp+')');
        },
        error: function (xhr, status, error) {
            console.error('Error fetching events:', error);
            // $('.loader').css('display','none');    
        },
        complete: function () {
        }

    });

  })

$(document).on('click',".day",function () {
    // alert();
    var search_date=$(this).data('date');
    console.log(search_date);
    var searchValue = $(this).val();
    var current_month="";
    var page=getActiveTabPage();
   
    $('.latest_month').each(function () { 
        current_month=$(this).val();
    });
    clearTimeout(search_user_ajax_timer);
    if(page=="upcoming"){
        var ajax_base_url=`${base_url}search_upcoming_event`;
        var scrollStatus="#scrollStatus";
        var tabbtn="#tabbtn1";
    }
    if(page=="draft"){
        var ajax_base_url=`${base_url}search_draft_event`;
        var scrollStatus="#scrollStatus2";
        var tabbtn="#tabbtn2";
    }
    if(page=="past"){
        var ajax_base_url=`${base_url}search_past_event`;
        var scrollStatus="#scrollStatus3";
        var tabbtn="#tabbtn3";
    }
    search_user_ajax_timer = setTimeout(function () {
        $('.loader_up').css('display','block');    
        console.log(ajax_base_url)
        console.log(scrollStatus);
        console.log(tabbtn);
        
    $.ajax({
        url: ajax_base_url,
        type: 'GET',
        data: { searchValue: searchValue,current_month:current_month,search_date:search_date},
        success: function (response) {
            console.log(response);
            
            if (response.view) {
                $(scrollStatus).html('');
                $(scrollStatus).html(response.view);
                $('#tabbtn3').css('display','block');
                $(tabbtn).text(response.last_month);
                $('#all-months-upcoming').css('display','block');

            }else{
                $(scrollStatus).html('');
                $(scrollStatus).html('No Data Found');
                $('#all-months-upcoming').css('display','none');
                if(response.page=="upcoming"){

                    $('#tabbtn1').css('display','none');
                }

                if(response.page=="past"){

                    $('#tabbtn3').css('display','none');
                }
                if(response.page=="draft"){

                    $('#tabbtn2').css('display','none');
                }

            }
            // hasMore = response.has_more; // Update the `hasMore` flag
            busy = false;
            $('#loader').hide();
           
            var $textSpan = $('.responsive-text');
            var $iconSpan = $('.responsive-icon'); 
            var calendarSvg=`<svg viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.16406 1.66602V4.16602" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M13.8359 1.66602V4.16602" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M3.41406 7.57422H17.5807" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M18 7.08268V14.166C18 16.666 16.75 18.3327 13.8333 18.3327H7.16667C4.25 18.3327 3 16.666 3 14.166V7.08268C3 4.58268 4.25 2.91602 7.16667 2.91602H13.8333C16.75 2.91602 18 4.58268 18 7.08268Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M13.5762 11.4167H13.5836" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M13.5762 13.9167H13.5836" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M10.498 11.4167H10.5055" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M10.498 13.9167H10.5055" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M7.41209 11.4167H7.41957" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M7.41209 13.9167H7.41957" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>`;
            $textSpan.text("Calendar");
            $iconSpan.html(calendarSvg);
            $('.responsive-calendar').css('display','none');
            $('.responsive-calender-month-text').css('display','none');
        },
        error: function (xhr, status, error) {
            console.error('Error fetching events:', error);
            busy = false;
            $('#loader').hide();
        },
        complete: function () {
            $('.loader_up').css('display','none');    
           }
    });
}, 750);
  })

$(document).on('click','.notification-filter-events',function () {
    $('.all-events-filter-info').addClass('d-none');
    $('.notification-all-event-wrp').removeClass('d-none');
    $('.notification-back').removeClass('d-none');
  })

  $(document).on('click','.notification-back',function () {
    $('.all-events-filter-info').removeClass('d-none');
    $('.notification-all-event-wrp').addClass('d-none');
    $('.notification-back').addClass('d-none');
  })

  $('.notification_div').scroll(function () {
    if (busy4) return; 
    var scrollTop = $(this).scrollTop(); 
    var scrollHeight = $(this)[0].scrollHeight; 
    var elementHeight = $(this).height();
    if (scrollTop + elementHeight >= scrollHeight) {
        busy4 = true;
        offset4 += limit;
        // var current_month2="";

        // $('.latest_month_draft').each(function () { 
        //     current_month2=$(this).val();
        // });
        // $('.loader').css('display','block');    
        $.ajax({
            url: `${base_url}fetch_notification`,
            type: 'GET',
            data: { limit: limit, offset: offset4 },
            success: function (response) {
                if (response.view && response.view!="") {
                    $('#notification_div').append(response.view);
                    busy4 = false; // Allow further AJAX calls

                }else{
                    // $('.loader').css('display', 'none');
                    return; 
                }
                // hasMore = response.has_more; // Update the `hasMore` flag
                // $('.loader').css('display','none');    
            },
            error: function (xhr, status, error) {
                console.error('Error fetching events:', error);
                busy4 = false;
                // $('.loader').css('display','none');      
            }
        });
    }
});

$(document).on('click', '.notification_filter_apply_btn', function () {
    var notificationTypes = [];
    var selectedEvents= [];
    var activityTypes= [];

    $("input[name='notificationTypes[]']:checked").each(function () {
        notificationTypes.push($(this).data("name"));
    });

    $("input[name='activityTypes[]']:checked").each(function () {
        activityTypes.push($(this).data("name"));
    });

    $("input[name='selectedEvents[]']:checked").each(function () {
        selectedEvents.push($(this).data("event_id"));
    });

    console.log(notificationTypes); 
    console.log(activityTypes); 
    console.log(selectedEvents); 

    $('#home_loader').css('display','block');

  $.ajax({
        url: `${base_url}notification_filter`,
        type: 'GET',
        data: {notificationTypes:notificationTypes,activityTypes:activityTypes,selectedEvents:selectedEvents},
        success: function (response) {
           if(response.view!=""){
            $(".notification_div").html('');
            $(".notification_div").append(response.view);
            $("#all-notification-filter-modal").modal('hide');
            $('#home_loader').css('display','none');

           }else{
            $(".notification_div").html('');
            $("#all-notification-filter-modal").modal('hide');
            $('#home_loader').css('display','none');

           }

        },
        error: function (xhr, status, error) {
    
        },
        complete: function () {
        }
    });
});

$(document).on('change', 'input[data-name="all"]', function () {
    var isChecked = $(this).is(':checked');
    $("input[name='activityTypes[]']").prop('checked', isChecked);
});
$(document).on('change', 'input[name="selectedEvents[]"]', function () {
    var eventname=$(this).data('event_name');
    if ($(this).is(':checked')) {
        $('.notification-selected-events-wrp').append('<span class="selected-event">' + eventname + '</span>');
    } else {
        $('.notification-selected-events-wrp .selected-event:contains(' + eventname + ')').remove();
    }   
});
$(document).on('click', '.all-event-notification-filter-reset', function () {
    $("input[name='selectedEvents[]']:checked").each(function () {
        $(this).prop('checked', false);
    });
    $("input[name='activityTypes[]']:checked").each(function () {
        $(this).prop('checked', false);
    });
    $("input[name='notificationTypes[]']:checked").each(function () {
        $(this).prop('checked', false);
    });
    $('.notification-selected-events-wrp').html('');

    $('#home_loader').css('display','block');

 $.ajax({
        url: `${base_url}notification_all`,
        type: 'GET',
        data: {},
        success: function (response) {
           if(response.view!=""){
            $(".notification_div").html('');
            $(".notification_div").append(response.view);
            $("#all-notification-filter-modal").modal('hide');
            $('#home_loader').css('display','none');
           }else{
            $(".notification_div").html('');
            $("#all-notification-filter-modal").modal('hide');
            $('#home_loader').css('display','none');

           }

        },
        error: function (xhr, status, error) {
    
        },
        complete: function () {
        }
    });

});




