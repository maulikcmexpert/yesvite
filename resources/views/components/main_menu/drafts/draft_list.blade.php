<div class="col-xl-9 col-lg-9 col-md-8">
    <div class="home-center-main draft-center-main">
        <div class="home-center-content">
          <div class="home-center-content-head">
              <h1>Event Drafts</h1>
          </div>
          <div class="all-events-searchbar-wrp">
              <form>
                <div class="position-relative">
                  <input type="text" class="form-control" id="search_draft_event_page" placeholder="Search event name">
                  <span class="search-icon">
                    <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.58366 17.5C13.9559 17.5 17.5003 13.9555 17.5003 9.58329C17.5003 5.21104 13.9559 1.66663 9.58366 1.66663C5.2114 1.66663 1.66699 5.21104 1.66699 9.58329C1.66699 13.9555 5.2114 17.5 9.58366 17.5Z" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M18.3337 18.3333L16.667 16.6666" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </span>
                </div>
              </form>
          </div>
          <div class="unfinish-draft-wrp">
              <div class="home-center-upcoming-events-title">
                  <h3>Unfinished Draft <span id="draft_page_count">({{count($eventDraftdata)}})</span></h3>
              </div>
              <div class="loader"></div>
              <div class="row all_drafts_list">
                    @foreach ($eventDraftdata as $draft )
                    <div class="col-xl-4 col-lg-6 col-md-12 col-sm-6 col-12">
                      <a href="" class="home-latest-draf-card">
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
                              <h3>{{$draft['event_name']}}</h3>
                              <!-- <p>Last Save:  {{$draft['saved_date']}}</p> -->
                              <p class="last-save" data-save-date="{{$draft['saved_date']}}"></p>

                            </div>
                            
                        </div>
                        @php
                        if($draft['step']=="1"){
                            $color="red";
                            $percent="25";
                        }elseif ($draft['step']=="2") {
                            $color="yellow";
                            $percent="50";
                        }elseif ($draft['step']=="3") {
                            $color="green";
                            $percent="75"; 
                        }
                        else {
                            $color="blue";
                            $percent="99";         
                        }
                    @endphp
                        <div class="progress-bar__wrapper {{$color}}">
                          <progress id="progress-bar" value="{{$percent}}" max="100"></progress>
                          <div class="d-flex align-items-center justify-content-between">
                            @php
                            $step_name="";
                              if($draft['step']=='1'){
                                $step_name="Design";
                              }elseif ($draft['step']=='2') {
                                $step_name="Event Details";
                              }elseif ($draft['step']=='3') {
                                $step_name="Guests";
                              }elseif ($draft['step']=='4') {
                                $step_name="Event Settings";
                              }
                            @endphp
                              <h4>{{$draft['step']}}/4 Steps - {{$step_name}}</h4>
                              <label class="progress-bar__value" htmlfor="progress-bar"> {{$percent}}%</label>
                              {{-- <p><span class="prograsbar-hosting">Hosting</span><span class="prograsbar-pro">{{$draft['event_plan_name']}}</span></p> --}}
                          </div>
                        </div>
                      </a>
                    </div>                                    
                    @endforeach
              </div>
          </div>
        </div>
    </div>
  </div>
  <script>
document.addEventListener("DOMContentLoaded", function () {
  const saveDates = document.querySelectorAll('.last-save');

  saveDates.forEach(function (saveDateElement) {
    const savedDate = saveDateElement.getAttribute('data-save-date');
    const losAngelesTime = new Date(savedDate + ' GMT-0800'); // Adjusting for LA timezone

    // Format the date according to the required format
    const options = {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: 'numeric',
      minute: 'numeric',
      hour12: true
    };

    const formattedDate = new Intl.DateTimeFormat('en-US', options).format(losAngelesTime);

    if (formattedDate) {
      // Ensure AM/PM is uppercase by replacing only the AM/PM part
      const finalDate = formattedDate.replace(/\b(am|pm)\b/i, match => match.toUpperCase());
      saveDateElement.innerHTML = `Last Save: ${finalDate}`;
    } else {
      console.error('Date formatting failed:', savedDate);
    }
  });
});
</script>







