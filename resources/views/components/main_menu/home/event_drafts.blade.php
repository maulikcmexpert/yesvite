@if(!empty($draftEventArray))
<div class="home-latest-draf-wrp">
    <div class="home-center-upcoming-events-title">
      <h3>Latest Drafts</h3>
      <a href="{{route('event.event_drafts')}}">All Drafts</a>
    </div>
    @foreach ($draftEventArray as $draft )
    <a href="{{ route('event', encrypt($draft['id'])) }}" class="home-latest-draf-card">
        <div class="home-latest-draf-card-head">
            <div class="home-latest-draf-card-head-img">
                <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.99609 9H11.4961" stroke="black" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5.99609 17H7.99609" stroke="black" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10.4961 17H14.4961" stroke="black" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M21.9961 12.53V16.61C21.9961 20.12 21.1061 21 17.5561 21H6.43609C2.88609 21 1.99609 20.12 1.99609 16.61V8.39C1.99609 4.88 2.88609 4 6.43609 4H14.4961" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M19.0764 4.63L15.3664 8.34C15.2264 8.48 15.0864 8.76 15.0564 8.96L14.8564 10.38C14.7864 10.89 15.1464 11.25 15.6564 11.18L17.0764 10.98C17.2764 10.95 17.5564 10.81 17.6964 10.67L21.4064 6.96C22.0464 6.32 22.3464 5.58 21.4064 4.64C20.4564 3.69 19.7164 3.99 19.0764 4.63Z" stroke="black" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M18.5459 5.16C18.8659 6.29 19.7459 7.17 20.8659 7.48" stroke="black" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="home-latest-draf-card-head-content">
              <h3>{{$draft['event_name']}}</h3>
              <!-- <p>Last Save: {{$draft['saved_date']}}</p> -->
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
        <div class="progress-bar__wrapper {{$color}}">
          <progress id="progress-bar" value="{{$percent}}" max="100">
          </progress>
          <div class="progress-bar-fill"></div>
          <div class="d-flex align-items-center justify-content-between">
              <h4>{{$draft['step']}}/4 Steps - {{$step_name}}</h4>
              {{-- <span class="prograsbar-pro">{{$draft['event_plan_name']}}</span> --}}
              <label class="progress-bar__value" htmlfor="progress-bar"> {{$percent}}%</label>
          </div>
        </div>
      </a>
      @endforeach
  </div>
  @endif


  <script>
 document.addEventListener("DOMContentLoaded", function () {
  const saveDates = document.querySelectorAll('.last-save'); // Assuming you have elements with this class

  saveDates.forEach(function (saveDateElement) {
    const savedDate = saveDateElement.getAttribute('data-save-date'); // Get the saved date attribute

    if (!savedDate) {
      console.error('Missing date attribute');
      return;
    }

    // Convert the savedDate to a Date object and adjust it to Los Angeles time zone
    // For example, if the saved date is '2025-02-18 04:33:08' in Los Angeles time, we will add the timezone info
    const losAngelesDate = new Date(savedDate + ' GMT-0800'); // Assume input is in Los Angeles time (PST/PDT)

    // Check if the date is valid
    if (isNaN(losAngelesDate.getTime())) {
      console.error('Invalid date format:', savedDate);
      return;
    }

    // Format the date to the user's local time zone
    const options = {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: 'numeric',
      minute: 'numeric',
      second: 'numeric',
      hour12: true // Use AM/PM format
    };

    // Get the formatted date string using the user's local time zone
    const formattedDate = new Intl.DateTimeFormat(navigator.language, options).format(losAngelesDate);

    // If formatting is successful, update the element with the formatted date
    if (formattedDate) {
      // Adjusting the format (e.g., replace " at " with " - " and uppercase AM/PM)
      const finalDate = formattedDate.replace(' at ', ' - ').replace(/\b(am|pm)\b/i, match => match.toUpperCase());
      saveDateElement.innerHTML = `Last Save: ${finalDate}`; // Display it in the element
    } else {
      console.error('Date formatting failed:', savedDate);
    }
  });
});
  
</script>


