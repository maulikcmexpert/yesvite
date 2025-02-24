@php
$getSocialLink = getSocialLink();
@endphp
<x-front.advertise />
<section class="home-web-tabs-wrp">
    <div class="container">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" data-tab="explore-designs-tab" id="nav-explore-designs-tab" data-bs-toggle="tab" data-bs-target="#nav-explore-designs" type="button" role="tab" aria-controls="nav-explore-designs" aria-selected="true">
                    Profile Home
                </button>

                <button class="nav-link" id="nav-profile-home-tab" data-bs-toggle="tab" data-bs-target="#nav-profile-home" type="button" role="tab" aria-controls="nav-profile-home" aria-selected="false" tabindex="-1">
                    Explore Designs
                </button>
            </div>
        </nav>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="nav-explore-designs" role="tabpanel" aria-labelledby="explore-designs-tab">
                
                <!-- ============= contact-details ============ -->
                <section class="contact-details profile-details supportive-home-wrp">
                    <div class="container">
                        <div class="row">
                            <x-front.sidebar :profileData="[]" />
                
                            <div class="calender-supportive-div col-xl-9 col-lg-9 col-md-8">
                                <div class="row">
                                    <div class="col-xl-7">
                                        <div class="home-center-main">
                                            <div class="home-center-content">
                                                <x-main_menu.home.profile :profileData="$profileData" :draftEventArray="$draftEventArray" />
                
                                                <x-main_menu.home.event_upcoming :eventList="$eventList" />
                                            </div>
                                            <button type="button" class="mobile-calender-btn">
                                            <span class="responsive-text">Calendar</span>
                                            <span class="responsive-icon">
                                                <svg viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                                </svg>
                
                                                {{-- <svg class="d-none" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3 5.83398H18" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M3 10H18" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M3 14.166H18" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                                </svg> --}}
                                            </span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-xl-5">
                                        <input type="hidden" id="totalmonths" value="{{$numMonths}}"/>
                                        <input type="hidden" id="startmonths" value="{{$startMonth}}"/>
                                        <input type="hidden" id="diffmonth" value="{{$diffmonth}}"/>
                                        <input type="hidden" id="calender_json" value="{{$events_calender_json}}"/>
                                        <input type="hidden" id="current_page" value="{{ (Request::segment(1)) }}"/>
                
                
                
                                        <div class="home-main-right right-bar-menu-home">
                                            <x-main_menu.calender :profileData="$profileData" />
                                            <x-main_menu.home.event_drafts :draftEventArray="$draftEventArray" />
                                        </div>
                
                                        <div class="responsive-calender-month-wrp">
                                            <h3 class="responsive-calender-month-text" style="display:none;">{{$startMonthCalender}}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div id="responsive-calendar" class="responsive-calendar" style="display:none;">
                                    <h2 class="calendar-heading">All Events</h2>
                                <div class="weekdays" style="position: sticky;"><div class="day">S</div><div class="day">M</div><div class="day">T</div><div class="day">W</div><div class="day">T</div><div class="day">F</div><div class="day">S</div></div>
                                <div id="responsive-calender-months" class="responsive-calender-months"></div>
                            </div>
                            </div>
                          </div>
                    </div>
                </section>
            </div>
            <div class="tab-pane fade" id="nav-profile-home" role="tabpanel" aria-labelledby="nav-profile-home-tab">
               @include('front.event.step2')
            </div>
        </div>
    </div>
</section>

@push('scripts')


<script>
    var base_url = $("#base_url").val()
    $('.custome-designcategory').hide();
    $(document).on("click",".edit_design_tem",function(){
        var id=$(this).attr('data-id');
        window.location.href=base_url+"events?design_id="+id;
    })
//   $(document).ready(function () {
//     toastr.options = {
//                     "closeButton": true,
//                     "progressBar": true,
//                     "positionClass": "toast-top-right",
//                 };
//             @if (session('msg'))
//                 toastr.options = {
//                     "closeButton": true,
//                     "progressBar": true,
//                     "positionClass": "toast-top-right",
//                 };
//                 toastr.success("{{ session('msg') }}");
//             @endif
//         });
</script>
@endpush
