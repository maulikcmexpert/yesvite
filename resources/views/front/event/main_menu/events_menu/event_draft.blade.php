<div class="tab-pane fade" id="nav-drafts" role="tabpanel" aria-labelledby="nav-drafts-tab">
    <div class="all-events-searchbar-wrp">
      <form>
        <div class="position-relative">
          <input type="text" class="form-control" id="text" placeholder="Search event name">
          <span class="search-icon">
            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9.58366 17.5C13.9559 17.5 17.5003 13.9555 17.5003 9.58329C17.5003 5.21104 13.9559 1.66663 9.58366 1.66663C5.2114 1.66663 1.66699 5.21104 1.66699 9.58329C1.66699 13.9555 5.2114 17.5 9.58366 17.5Z" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M18.3337 18.3333L16.667 16.6666" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </span>
        </div>
      </form>
      <button class="mobile-search-filter-icon" type="button" data-bs-toggle="modal" data-bs-target="#all-event-filter-modal">
        <svg  viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M22 7H16" stroke="black" stroke-opacity="0.8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
          <path d="M6 7H2" stroke="black" stroke-opacity="0.8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
          <path d="M10 10.5C11.933 10.5 13.5 8.933 13.5 7C13.5 5.067 11.933 3.5 10 3.5C8.067 3.5 6.5 5.067 6.5 7C6.5 8.933 8.067 10.5 10 10.5Z" stroke="black" stroke-opacity="0.8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
          <path d="M22 18H18" stroke="black" stroke-opacity="0.8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
          <path d="M8 18H2" stroke="black" stroke-opacity="0.8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
          <path d="M14 21.5C15.933 21.5 17.5 19.933 17.5 18C17.5 16.067 15.933 14.5 14 14.5C12.067 14.5 10.5 16.067 10.5 18C10.5 19.933 12.067 21.5 14 21.5Z" stroke="black" stroke-opacity="0.8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
      </button>
    </div>

    
    <div class="all-events-month-wise-wrp">
      <div class="all-events-month-wise-inner"id="scrollStatus2">
      @foreach ($eventDraftdata as $draftEvent)
        <div class="all-events-month-wise-support">
          <a href="{{ route('event', encrypt($draftEvent['id'])) }}" class="home-latest-draf-card">
            <div class="home-latest-draf-card-head">
                <div class="home-latest-draf-card-head-img">
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.99609 9H11.4961" stroke="black" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M5.99609 17H7.99609" stroke="black" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M10.4961 17H14.4961" stroke="black" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M21.9961 12.53V16.61C21.9961 20.12 21.1061 21 17.5561 21H6.43609C2.88609 21 1.99609 20.12 1.99609 16.61V8.39C1.99609 4.88 2.88609 4 6.43609 4H14.4961" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M19.0764 4.63L15.3664 8.34C15.2264 8.48 15.0864 8.76 15.0564 8.96L14.8564 10.38C14.7864 10.89 15.1464 11.25 15.6564 11.18L17.0764 10.98C17.2764 10.95 17.5564 10.81 17.6964 10.67L21.4064 6.96C22.0464 6.32 22.3464 5.58 21.4064 4.64C20.4564 3.69 19.7164 3.99 19.0764 4.63Z" stroke="black" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M18.5459 5.16C18.8659 6.29 19.7459 7.17 20.8659 7.48" stroke="black" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <div class="home-latest-draf-card-head-content">
                  <h3>{{$draftEvent['event_name']}}</h3>
                  <p>Last Save:  {{$draftEvent['saved_date']}}</p>
                </div>
                
            </div>
            @php
            if($draftEvent['step']=="1"){
                $color="red";
                $percent="25";
            }elseif ($draftEvent['step']=="2") {
                $color="yellow";
                $percent="50";
            }elseif ($draftEvent['step']=="3") {
                $color="green";
                $percent="75"; 
            }
            else {
                $color="blue";
                $percent="99";         
            }

            $step_name="";
                              if($draftEvent['step']=='1'){
                                $step_name="Design";
                              }elseif ($draftEvent['step']=='2') {
                                $step_name="Event Details";
                              }elseif ($draftEvent['step']=='3') {
                                $step_name="Guests";
                              }elseif ($draftEvent['step']=='4') {
                                $step_name="Event Settings";
                              }
        @endphp
            <div class="progress-bar__wrapper {{$color}}">
              <progress id="progress-bar" value="{{$percent}}" max="100"></progress>
              <div class="progress-bar-fill"></div>
              <div class="d-flex align-items-center justify-content-between">
                  <h4>{{$draftEvent['step']}}/4 Steps - {{$step_name}}</h4>
                  <label class="progress-bar__value" htmlfor="progress-bar"> {{$percent}}%</label>
                  {{-- <p><span class="prograsbar-hosting">Hosting</span><span class="prograsbar-pro">{{$draftEvent['event_plan_name']}}</span></p> --}}
              </div>
            </div>
          </a>
          <h6 class="all-events-date-show">08</h6>
        </div>
        @endforeach
      </div>
      <div class="all-events-month-show-wrp">
        <h6 class="all-events-month-show" id="tabbtn2">jun</h6>
      </div>
    </div>  

  </div>