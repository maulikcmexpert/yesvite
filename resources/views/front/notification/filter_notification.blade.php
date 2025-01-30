@php
$i=0;
@endphp

@foreach ($notfication_data as $key=>$value)
@php
$i++;
@endphp
<div class="accordion-item">
<h2 class="accordion-header">
  <button
    class="accordion-button collapsed"
    type="button"
    data-bs-toggle="collapse"
    data-bs-target="#collapseOne{{$i}}"
    aria-expanded="true"
    aria-controls="collapseOne"
  >
    <div class="accordion-button-wrp">
      <div class="accordion-button-img-wrp">
        <img
          src="{{$value[0]['event_image']}}"
          alt=""
          loading="lazy"
        />
      </div>
      <div class="accordion-button-content-wrp">
        <h3>{{$key}}</h3>
        <p>{{$value[0]['event_date']}}</p>
      </div>
    </div>
    
  </button>
</h2>
<div
  id="collapseOne{{$i}}"
  class="accordion-collapse collapse"
  data-bs-parent="#accordionExample">
  
  @foreach ($value as $inner_data )
    
        <div class="accordion-body">
                            @if($inner_data['notification_type']=="invite")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                        @php
                                        $f = isset($inner_data['first_name']) && !empty($inner_data['first_name'])
                                            ? strtoupper($inner_data['first_name'][0])
                                            : '';

                                        $l = isset($inner_data['last_name']) && !empty($inner_data['last_name'])
                                             ? strtoupper($inner_data['last_name'][0])
                                            : '';

                                        $initials = $f . $l;

                                        $fontColor = !empty($f) ? "fontcolor" . $f : 'defaultFontColor';
                                        $userProfile = "<h5 class='" . $fontColor . "'>" . $initials . "</h5>";
                                    @endphp

                                      @if($inner_data['profile']!="")
                                      <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                      <span class="active-dot"></span>

                                     @else
                                       {!! $userProfile !!}
                                    <span class="active-dot"></span>
                                    @endif
                                    </div>
                                    <div class="notification-drodown-body-inner-content">
                                      <div>

                                      @if($inner_data['co_host_notification']=="1")
                                        <h3>
                                          {{-- {{$inner_data['notification_message']}} --}}
                                          <span>You have been made a co-host for <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span></span>
                                        </h3>
                                       @else
                                        <h3>
                                            {{-- {{$inner_data['notification_message']}} --}}
                                            {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                            <span> has invited you to <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span></span>
                                          </h3>
                                       @endif 

                                        <div>
                                          <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                          @if($inner_data['read']=="0")
                                              <h6 class="notification-read-dot mt-1 text-right"></h6>
                                          @endif
                                        </div>

                                      
                                      </div>
                                        @if($inner_data['co_host_notification']!="1")
                                          <div>
                                              <button class="notification-rsvp-btn" type="button" data-bs-toggle="modal" data-bs-target="#aboutsuccess" data-profile="{{$inner_data['profile']}}" data-firstname="{{$inner_data['first_name']}}" data-lastname="{{$inner_data['last_name']}}" data-event_name="{{$inner_data['event_name']}}" data-event_id="{{$inner_data['event_id']}}" data-user_id="{{$inner_data['user_id']}}">RSVP</button>
                                          </div>
                                        @endif  
                                     <div>
                                        <!-- <p>{{$inner_data['event_name']}}</p>
                                          @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif -->
                                      </div>
                                      {{--  <div class="notification-accept-invite-btn-wrp">
                                        <button class="accept-btn">
                                          <i class="fa-regular fa-circle-check"></i>
                                          Accept
                                        </button>
                                        <button class="decline-btn">
                                          <i class="fa-regular fa-circle-xmark"></i>
                                          Decline
                                        </button>
                                      </div> --}}
                                    </div>
                                  </div>
                            @elseif($inner_data['notification_type']=="update_event")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php
                                                  $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                                  $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                                  $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                      <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                      <span class="active-dot"></span>

                                      @else
                                       {!! $userProfile !!}
                                    <span class="active-dot"></span>
                                    @endif
                                      {{-- <span class="active-dot"></span> --}}
                                    </div>
                                    <div class="notification-drodown-body-inner-content">
                                      <div>
                                        <h3>
                                          {{-- {{$inner_data['notification_message']}} --}}
                                          {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                          <span> Has updated the event details for <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span></span>
                                        </h3>
                                        <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                      </div>
                                      <div>
                                        <!-- <p>{{$inner_data['event_name']}}</p> -->
                                        @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif
                                                                              </div>
                                      {{-- <div class="notification-accept-invite-btn-wrp">
                                        <button class="accept-btn">
                                          <i class="fa-regular fa-circle-check"></i>
                                          Accept
                                        </button>
                                        <button class="decline-btn">
                                          <i class="fa-regular fa-circle-xmark"></i>
                                          Decline
                                        </button>
                                      </div> --}}
                                    </div>
                                  </div>
                            @elseif($inner_data['notification_type']=="update_date")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php
                                                  $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                                  $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                                  $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                      <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                      <span class="active-dot"></span>

                                      @else
                                       {!! $userProfile !!}
                                    <span class="active-dot"></span>
                                    @endif
                                      {{-- <span class="active-dot"></span> --}}
                                    </div>
                                    <div class="notification-drodown-body-inner-content">
                                      <div>
                                        <h3>
                                          {{-- {{$inner_data['notification_message']}} --}}
                                          {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                          <span> Has updated the event date for <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span></span>
                                        </h3>
                                        <div>
                                          <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                          @if($inner_data['read']=="0")  
                                          <h6 class="notification-read-dot mt-1 text-right"></h6>
                                          @endif
                                        </div>
                                      </div>
                                      <div>
                                        <!-- <p>{{$inner_data['event_name']}}</p> -->
                                      </div>
                                      <div class="d-block">
                                        <h3 class="mb-1">Date from : <span style="font-family: var(--SFProDisplay-Regular);"> {{$inner_data['old_start_end_date']}}</span></h3>
                                        <h3>Date To : <span style="font-family: var(--SFProDisplay-Regular);"> {{$inner_data['new_start_end_date']}}</span></h3>
                                      </div>
                                      {{-- <div class="notification-accept-invite-btn-wrp">
                                        <button class="accept-btn">
                                          <i class="fa-regular fa-circle-check"></i>
                                          Accept
                                        </button>
                                        <button class="decline-btn">
                                          <i class="fa-regular fa-circle-xmark"></i>
                                          Decline
                                        </button>
                                      </div> --}}
                                    </div>
                                  </div>  
                            @elseif($inner_data['notification_type']=="update_potluck")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php
                                                  $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                                  $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                                  $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                      <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                      <span class="active-dot"></span>

                                      @else
                                       {!! $userProfile !!}
                                    <span class="active-dot"></span>
                                    @endif
                                      {{-- <span class="active-dot"></span> --}}
                                    </div>
                                    <div class="notification-drodown-body-inner-content">
                                      <div>
                                        <h3>
                                          {{-- {{$inner_data['notification_message']}} --}}
                                          {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                          <span> Has updated the event potluck for <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span></span>
                                        </h3>
                                        <div>
                                          <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                          @if($inner_data=="0")
                                          <h6 class="notification-read-dot mt-1 text-right"></h6>
                                          @endif
                                        </div>
                                      </div>
                                      <div>
                                        <!-- <p>{{$inner_data['event_name']}}</p> -->
                                      </div>
                                      
                                      {{-- <div class="notification-accept-invite-btn-wrp">
                                        <button class="accept-btn">
                                          <i class="fa-regular fa-circle-check"></i>
                                          Accept
                                        </button>
                                        <button class="decline-btn">
                                          <i class="fa-regular fa-circle-xmark"></i>
                                          Decline
                                        </button>
                                      </div> --}}
                                    </div>
                                  </div> 
                            @elseif($inner_data['notification_type']=="update_time")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php
                                                  $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                                  $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                                  $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                      <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                      <span class="active-dot"></span>

                                      @else
                                       {!! $userProfile !!}
                                    <span class="active-dot"></span>
                                    @endif
                                      {{-- <span class="active-dot"></span> --}}
                                    </div>
                                    <div class="notification-drodown-body-inner-content">
                                      <div>
                                        <h3>
                                          {{-- {{$inner_data['notification_message']}} --}}
                                          {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                          <span> has updated the event time for <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span></span>
                                        </h3>
                                        <div>
                                          <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                          @if($inner_data=="0")
                                          <h6 class="notification-read-dot mt-1 text-right"></h6>
                                          @endif
                                        </div>
                                      </div>
                                      <div>
                                        <!-- <p>{{$inner_data['event_name']}}</p> -->
                                      </div>
                                      <div class="d-block">
                                        <h3 class="mb-1">Time : <span style="font-family: var(--SFProDisplay-Regular);">From {{$inner_data['from_time']}} To {{$inner_data['to_time']}}</span></h3>
                                      </div>
                                      
                                      {{-- <div class="notification-accept-invite-btn-wrp">
                                        <button class="accept-btn">
                                          <i class="fa-regular fa-circle-check"></i>
                                          Accept
                                        </button>
                                        <button class="decline-btn">
                                          <i class="fa-regular fa-circle-xmark"></i>
                                          Decline
                                        </button>
                                      </div> --}}
                                    </div>
                                  </div>  
                            @elseif($inner_data['notification_type']=="update_address")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php
                                                  $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                                  $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                                  $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                      <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                      <span class="active-dot"></span>

                                      @else
                                       {!! $userProfile !!}
                                    <span class="active-dot"></span>
                                    @endif
                                      {{-- <span class="active-dot"></span> --}}
                                    </div>
                                    <div class="notification-drodown-body-inner-content">
                                      <div>
                                        <h3>
                                          {{-- {{$inner_data['notification_message']}} --}}
                                          {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                          <span> has updated the event address for <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span></span>
                                        </h3>
                                        <div>
                                          <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                          @if($inner_data=="0")
                                          <h6 class="notification-read-dot mt-1 text-right"></h6>
                                          @endif
                                        </div>
                                      </div>
                                      <div>
                                        <!-- <p>{{$inner_data['event_name']}}</p> -->
                                      </div>
                                      <div class="d-block">
                                        <h3 class="mb-1">Location</h3>
                                        <h3 class="mb-1">From : <span style="font-family: var(--SFProDisplay-Regular);">{{$inner_data['from_addr']}}</span></h3>
                                        <h3 class="mb-1">To: <span style="font-family: var(--SFProDisplay-Regular);">{{$inner_data['to_addr']}}</span></h3>
                                      </div>
                                      
                                      {{-- <div class="notification-accept-invite-btn-wrp">
                                        <button class="accept-btn">
                                          <i class="fa-regular fa-circle-check"></i>
                                          Accept
                                        </button>
                                        <button class="decline-btn">
                                          <i class="fa-regular fa-circle-xmark"></i>
                                          Decline
                                        </button>
                                      </div> --}}
                                    </div>
                                  </div>                       
                            @elseif($inner_data['notification_type']=="potluck_bring")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php
                                                  $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                                  $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                                  $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                      <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                     @else
                                       {!! $userProfile !!}
                                    @endif
                                    <span class="active-dot"></span>

                                      {{-- <span class="active-dot"></span> --}}
                                    </div>
                                    <div class="notification-drodown-body-inner-content">
                                      <div>
                                        <h3>
                                          {{-- {{$inner_data['notification_message']}} --}}
                                          {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                          <!-- <span> will bring the item below for <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span>Potluck -->
                                          <span> will bring the item below for <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span>
                                          </span>
                                        </h3>
                                        <div>
                                          <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                          @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot mt-1 text-right"></h6>
                                          @endif
                                        </div>
                                      </div>
                                      <div>
                                        <!-- <p>{{$inner_data['event_name']}} Potluck</p> -->
                                            
                                      </div>
                                      <div class="d-flex align-items-center justify-content-between">
                                        <h3>{{$inner_data['potluck_item']}}</h3>
                                        <h3>Count: {{$inner_data['count']}}</h3>
                                      </div>
                                      {{-- <div class="notification-accept-invite-btn-wrp">
                                        <button class="accept-btn">
                                          <i class="fa-regular fa-circle-check"></i>
                                          Accept
                                        </button>
                                        <button class="decline-btn">
                                          <i class="fa-regular fa-circle-xmark"></i>
                                          Decline
                                        </button>
                                      </div> --}}
                                    </div>
                                  </div>            
                            @elseif($inner_data['notification_type']=="update_event"&& $inner_data['is_co_host']=="1"&&$inner_data['accept_as_co_host']=="0")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      <img
                                        src="{{$inner_data['profile']}}"
                                        alt=""
                                        loading="lazy"

                                      />
                                      <span class="active-dot"></span>
                                    </div>
                                    <div class="notification-drodown-body-inner-content">
                                      <div>
                                        <h3>
                                          {{$inner_data['notification_message']}}
                                          {{-- James Clark
                                          <span> Invited you to co-host</span> --}}
                                        </h3>
                                        <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                      </div>
                                      <div>
                                        <p>{{$inner_data['event_name']}} <span>Accept? </span></p>
                                        @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif
                                                                              </div>
                                      <div class="notification-accept-invite-btn-wrp">
                                        <button class="accept-btn">
                                          <i class="fa-regular fa-circle-check"></i>
                                          Accept
                                        </button>
                                        <button class="decline-btn">
                                          <i class="fa-regular fa-circle-xmark"></i>
                                          Decline
                                        </button>
                                      </div>
                                    </div>
                                  </div>
                            @elseif($inner_data['notification_type']=="comment_post")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php
                                          $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                          $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                          $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                          <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                          <span class="active-dot"></span>

                                      @else
                                          {!! $userProfile !!}
                                        <span class="active-dot"></span>
                                      @endif
                                    </div>
                                    <div
                                      class="notification-drodown-body-inner-content"
                                    >
                                      <div>
                                        <h3>
                                          {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                          <span> commented on your post on <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span></span>
                                        </h3>
                                        <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                      </div>
                                      <div>
                                        <p>
                                          <!-- {{$inner_data['event_name']}} -->
                                          <span><strong>Wall</strong></span>
                                        </p>
                                        @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif
                                      </div>
                                      {{-- <div class="notification-video-comment-wrp">
                                        <h6>That’s was great! love it ❤️</h6>
                                        <div class="notification-video-wrp">
                                          <a href="./assets/img/sample-video.mp4" class="notification-video popup-videos">
                                            <video>
                                              <source src="./assets/img/sample-video.mp4" type="video/mp4" />
                                            </video>
                                            <span class="notification-video_play-icon"
                                              ><img
                                                src="./assets/img/notification-video_play-icon.png"
                                                alt=""
                                                loading="lazy"

                                            /></span>
                                          </a>
                                          <div class="notification-video-content">
                                            <p>
                                              Thanks everyone for RSVP'ing on time. I
                                              hope everyone can make it to this special
                                              day of ours”
                                            </p>
                                          </div>
                                        </div>
                                      </div> --}}
                                    </div>
                                  </div>
                            @elseif($inner_data['notification_type']=="upload_post")
                                <div class="notification-drodown-body-inner">
                                  <div class="notification-drodown-body-inner-img">
                                    @php
                                        $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                        $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                        $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                    @endphp
                                    @if($inner_data['profile']!="")
                                        <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                        <span class="active-dot"></span>

                                    @else
                                        {!! $userProfile !!}
                                      <span class="active-dot"></span>
                                    @endif
                                  </div>
                                  <div
                                    class="notification-drodown-body-inner-content"
                                  >
                                    <div>
                                      <h3>
                                        {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                        {{-- <span> posted on wall at </span> --}}
                                        @if($inner_data['media_type']=="photo")
                                            <span> posted new photo on wall at </span>
                                        @elseif($inner_data['media_type']=="video")
                                          <span> posted video on wall at <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span><strong></strong></span>
                                        @else
                                        <span> posted on wall at <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span><strong></strong></span>
                                        @endif
                                      </h3>
                                      <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                    </div>
                                    <div>
                                      <p>
                                        <!-- {{$inner_data['event_name']}} -->

                                        <!-- <span><strong>Wall</strong></span> -->
                                      </p>
                                      @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif
                                    </div>
                                    {{-- <div class="notification-video-comment-wrp"> --}}
                                      {{-- <h6>That’s was great! love it ❤️</h6> --}}
                                      <div class="notification-video-wrp">
                                        @if($inner_data['media_type']!="")
                                          <div class="notification-video">
                                                @if($inner_data['media_type']=="photo")
                                                  <img src="{{$inner_data['post_image']}}" alt=""/>
                                                @elseif($inner_data['media_type']=="video")
                                                  <a href="{{$inner_data['post_image']}}" class="notification-video popup-videos">
                                                  <video>
                                                    <source src="{{$inner_data['post_image']}}" type="video/mp4" />
                                                  </video>
                                                  <span class="notification-video_play-icon"
                                                    ><img
                                                      src="{{asset('assets/front/image/notification-video_play-icon.png')}}"
                                                      alt=""
                                                      loading="lazy"

                                                  /></span>
                                                </a>
                                              @endif
                                          </div>
                                          <div class="notification-video-content">
                                            <p>
                                              {{($inner_data['post_message']!="")?$inner_data['post_message']:"See detail post"}}
                                            </p>
                                          </div>
                                        @elseif($inner_data['media_type']=="")
                                            <div class="noification-simple-text-wrp">
                                              <p>{{($inner_data['post_message']!="")?$inner_data['post_message']:"See detail post"}}</p>
                                            </div>
                                        @endif
                                      </div>
                                    {{-- </div> --}}
                                  </div>
                                </div>
                            @elseif($inner_data['notification_type']=="first")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      <img
                                        src="./assets/img/header-profile-img.png"
                                        alt=""
                                        loading="lazy"

                                      />
                                      <span class="active-dot"></span>
                                    </div>
                                    <div
                                      class="notification-drodown-body-inner-content"
                                    >
                                      <div>
                                        <h3>
                                          James Clark
                                          <span> RSVP’d <strong>NO</strong> for </span>
                                        </h3>
                                        <h6 class="notification-time-count">10min</h6>
                                      </div>
                                      <div>
                                        <p>
                                          Sarah’s Birthday
                                          <span><strong>Wall</strong></span>
                                        </p>
                                        @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif
                                      </div>
                                      <div class="notification-rsvp-wrp">
                                        <h4>RSVP’d <span>NO</span></h4>
                                        <a href="#" class="chat-icon">
                                          <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                          >
                                            <path
                                              d="M8.5 19H8C4 19 2 18 2 13V8C2 4 4 2 8 2H16C20 2 22 4 22 8V13C22 17 20 19 16 19H15.5C15.19 19 14.89 19.15 14.7 19.4L13.2 21.4C12.54 22.28 11.46 22.28 10.8 21.4L9.3 19.4C9.14 19.18 8.77 19 8.5 19Z"
                                              stroke="#94A3B8"
                                              stroke-width="1.5"
                                              stroke-miterlimit="10"
                                              stroke-linecap="round"
                                              stroke-linejoin="round"
                                            />
                                            <path
                                              d="M7 8H17"
                                              stroke="#94A3B8"
                                              stroke-width="1.5"
                                              stroke-linecap="round"
                                              stroke-linejoin="round"
                                            />
                                            <path
                                              d="M7 13H13"
                                              stroke="#94A3B8"
                                              stroke-width="1.5"
                                              stroke-linecap="round"
                                              stroke-linejoin="round"
                                            />
                                          </svg>
                                        </a>
                                      </div>
                                    </div>
                                  </div>
                            @elseif($inner_data['notification_type']=="sent_rsvp" && $inner_data['rsvp_detail']['rsvpd_status']=="1" )
                              <div class="notification-drodown-body-inner">
                                <div class="notification-drodown-body-inner-img">
                                  @php
                                              $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                              $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                              $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                  @endphp
                                  @if($inner_data['profile']!="")
                                  <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                  <span class="active-dot"></span>

                                @else
                                  {!! $userProfile !!}
                                <span class="active-dot"></span>
                                @endif
                                </div>
                                <div class="notification-drodown-body-inner-content">
                                  <div>
                                    <h3>
                                      {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                      @if($inner_data['rsvp_attempt']=="no_to_yes")
                                        <span> changed RSVP response from <strong>NO</strong> to <strong>YES</strong> for </span>
                                      @else
                                        <span> RSVP’d <strong>YES</strong> for </span>
                                      @endif                                    </h3>
                                    <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                  </div>
                                  <div>
                                    <p>
                                      {{$inner_data['event_name']}}
                                      <span><strong>Wall</strong></span>
                                    </p>
                                    @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif
                                  </div>
                                </div>
                              </div>
                            @elseif($inner_data['notification_type']=="sent_rsvp" && $inner_data['rsvp_detail']['rsvpd_status']=="0" )
                              <div class="notification-drodown-body-inner">
                                <div class="notification-drodown-body-inner-img">
                                  @php
                                    $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                    $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                    $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                  @endphp
                                  @if($inner_data['profile']!="")
                                      <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                      <span class="active-dot"></span>
                                @else
                                        {!! $userProfile !!}
                                      <span class="active-dot"></span>
                                @endif
                                </div>
                                <div
                                  class="notification-drodown-body-inner-content">
                                  <div>
                                    <h3>
                                      {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                      @if($inner_data['rsvp_attempt']=="yes_to_no")
                                        <span> changed RSVP response from <strong>YES</strong> to <strong>NO</strong> for </span>
                                      @else
                                        <span> RSVP’d <strong>NO</strong> for </span>
                                      @endif
                                    </h3>
                                    <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                  </div>
                                  <div>
                                    <p>
                                      {{$inner_data['event_name']}}
                                      <span><strong>Wall</strong></span>
                                    </p>
                                    @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif
                                  </div>
                                  <div class="notification-rsvp-wrp">
                                    <h4>RSVP’d <span>NO</span></h4>
                                    <a href="#" class="chat-icon">
                                      <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                      >
                                        <path
                                          d="M8.5 19H8C4 19 2 18 2 13V8C2 4 4 2 8 2H16C20 2 22 4 22 8V13C22 17 20 19 16 19H15.5C15.19 19 14.89 19.15 14.7 19.4L13.2 21.4C12.54 22.28 11.46 22.28 10.8 21.4L9.3 19.4C9.14 19.18 8.77 19 8.5 19Z"
                                          stroke="#94A3B8"
                                          stroke-width="1.5"
                                          stroke-miterlimit="10"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"
                                        />
                                        <path
                                          d="M7 8H17"
                                          stroke="#94A3B8"
                                          stroke-width="1.5"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"
                                        />
                                        <path
                                          d="M7 13H13"
                                          stroke="#94A3B8"
                                          stroke-width="1.5"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"
                                        />
                                      </svg>
                                    </a>
                                  </div>
                                </div>
                              </div>
                            @elseif($inner_data['notification_type']=="photos")
                                <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php
                                                  $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                                  $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                                  $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                      <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                      <span class="active-dot"></span>

                                    @else
                                      {!! $userProfile !!}
                                    <span class="active-dot"></span>
                                    @endif
                                    </div>
                                    <div class="notification-drodown-body-inner-content">
                                      <div>

                                        <h3>
                                          {{-- {{$inner_data['notification_message']}} --}}
                                          {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                          <span> posted new photo on photos for <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span></span>
                                        </h3>
                                        <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                      </div>
                                    <div>
                                        <!-- <p>{{$inner_data['event_name']}}</p> -->
                                        @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif
                                      </div>
                                        {{-- <div class="notification-video-comment-wrp">
                                        <div class="notification-video-wrp">
                                          <a href="#" class="notification-video popup-videos">
                                              <img src="{{$inner_data['post_image']}}" />
                                          </a>
                                          <div class="notification-video-content">
                                            <p>
                                                See detail post
                                            </p>
                                          </div>
                                        </div>
                                      </div> --}}
                                      {{--  <div class="notification-accept-invite-btn-wrp">
                                        <button class="accept-btn">
                                          <i class="fa-regular fa-circle-check"></i>
                                          Accept
                                        </button>
                                        <button class="decline-btn">
                                          <i class="fa-regular fa-circle-xmark"></i>
                                          Decline
                                        </button>
                                      </div> --}}
                                    </div>
                                  </div>

                            @elseif($inner_data['notification_type']=="accept_reject_co_host")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php
                                          $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                          $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                          $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                          <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                          <span class="active-dot"></span>

                                      @else
                                          {!! $userProfile !!}
                                        <span class="active-dot"></span>
                                      @endif
                                    </div>
                                    <div
                                      class="notification-drodown-body-inner-content"
                                    >
                                      <div>
                                        <h3>
                                          {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                          <span> Accepted your invitation to co-host <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span></span>
                                        </h3>
                                        <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                      </div>
                                      <div>
                                        <p>
                                          <!-- {{$inner_data['event_name']}} -->
                                          <span><strong>Wall</strong></span>
                                        </p>
                                        @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif
                                      </div>
                                      {{-- <div class="notification-video-comment-wrp">
                                        <h6>That’s was great! love it ❤️</h6>
                                        <div class="notification-video-wrp">
                                          <a href="./assets/img/sample-video.mp4" class="notification-video popup-videos">
                                            <video>
                                              <source src="./assets/img/sample-video.mp4" type="video/mp4" />
                                            </video>
                                            <span class="notification-video_play-icon"
                                              ><img
                                                src="./assets/img/notification-video_play-icon.png"
                                                alt=""
                                                loading="lazy"

                                            /></span>
                                          </a>
                                          <div class="notification-video-content">
                                            <p>
                                              Thanks everyone for RSVP'ing on time. I
                                              hope everyone can make it to this special
                                              day of ours”
                                            </p>
                                          </div>
                                        </div>
                                      </div> --}}
                                    </div>
                                  </div>
                        @endif

          {{-- <div class="notification-drodown-body-inner">
            <div class="notification-drodown-body-inner-img">
              <img
                src="./assets/img/header-profile-img.png"
                alt=""
              />
              <span class="active-dot"></span>
            </div>
            <div
              class="notification-drodown-body-inner-content"
            >
              <div>
                <h3>
                  James Clark
                  <span> posted a comment with video on </span>
                </h3>
                <h6 class="notification-time-count">10min</h6>
              </div>
              <div>
                <p>
                  Sarah’s Birthday
                  <span><strong>Wall</strong></span>
                </p>
                <h6 class="notification-read-dot"></h6>
              </div>
              <div class="notification-video-wrp">
                <a href="{{asset('assets/front/image/sample-video.mp4')}}" class="notification-video popup-videos">
                  <video>
                    <source src="{{asset('assets/front/image/sample-video.mp4')}}" type="video/mp4" />
                  </video>
                  <span class="notification-video_play-icon"
                    ><img
                      src="{{asset('assets/front/image/notification-video_play-icon.png')}}"
                      alt=""
                  /></span>
                </a>
                <div class="notification-video-content">
                  <p>
                    Thanks everyone for RSVP'ing on time. I hope
                    everyone can make it to this special day of
                    ours”
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="notification-drodown-body-inner">
            <div class="notification-drodown-body-inner-img">
              <img
                src="./assets/img/header-profile-img.png"
                alt=""
              />
              <span class="active-dot"></span>
            </div>
            <div
              class="notification-drodown-body-inner-content"
            >
              <div>
                <h3>
                  James Clark
                  <span> commented on your post on </span>
                </h3>
                <h6 class="notification-time-count">10min</h6>
              </div>
              <div>
                <p>
                  Sarah’s Birthday
                  <span><strong>Wall</strong></span>
                </p>
                <h6 class="notification-read-dot"></h6>
              </div>
              <div class="notification-video-comment-wrp">
                <h6>That’s was great! love it ❤️</h6>
                <div class="notification-video-wrp">
                  <a href="./assets/img/sample-video.mp4" class="notification-video popup-videos">
                    <video>
                      <source src="./assets/img/sample-video.mp4" type="video/mp4" />
                    </video>
                    <span class="notification-video_play-icon"
                      ><img
                        src="./assets/img/notification-video_play-icon.png"
                        alt=""
                    /></span>
                  </a>
                  <div class="notification-video-content">
                    <p>
                      Thanks everyone for RSVP'ing on time. I
                      hope everyone can make it to this special
                      day of ours”
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="notification-drodown-body-inner">
            <div class="notification-drodown-body-inner-img">
              <img
                src="./assets/img/header-profile-img.png"
                alt=""
              />
              <span class="active-dot"></span>
            </div>
            <div
              class="notification-drodown-body-inner-content"
            >
              <div>
                <h3>
                  James Clark
                  <span> RSVP’d <strong>NO</strong> for </span>
                </h3>
                <h6 class="notification-time-count">10min</h6>
              </div>
              <div>
                <p>
                  Sarah’s Birthday
                  <span><strong>Wall</strong></span>
                </p>
                <h6 class="notification-read-dot"></h6>
              </div>
              <div class="notification-rsvp-wrp">
                <h4>RSVP’d <span>NO</span></h4>
                <a href="#" class="chat-icon">
                  <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      d="M8.5 19H8C4 19 2 18 2 13V8C2 4 4 2 8 2H16C20 2 22 4 22 8V13C22 17 20 19 16 19H15.5C15.19 19 14.89 19.15 14.7 19.4L13.2 21.4C12.54 22.28 11.46 22.28 10.8 21.4L9.3 19.4C9.14 19.18 8.77 19 8.5 19Z"
                      stroke="#94A3B8"
                      stroke-width="1.5"
                      stroke-miterlimit="10"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                    <path
                      d="M7 8H17"
                      stroke="#94A3B8"
                      stroke-width="1.5"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                    <path
                      d="M7 13H13"
                      stroke="#94A3B8"
                      stroke-width="1.5"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                  </svg>
                </a>
              </div>
            </div>
          </div> --}}
        </div>
  @endforeach

</div>
</div>
@endforeach
