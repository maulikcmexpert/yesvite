{{dd($selectedFilters);}}
<div class="event-center-title">
    <h2>{{$eventDetails['event_name']}}</h2>
    <button class="view_wall_filter {{(Request::segment(1) != 'event_wall')? 'd-none':''}} " data-apply="0" type="button" data-bs-toggle="modal" data-bs-target="#main-center-modal-filter">
      <svg
        viewBox="0 0 20 21"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M18.3359 5.91669H13.3359"
          stroke="#F73C71"
          stroke-width="1.5"
          stroke-miterlimit="10"
          stroke-linecap="round"
          stroke-linejoin="round"
        ></path>
        <path
          d="M4.9974 5.91669H1.66406"
          stroke="#F73C71"
          stroke-width="1.5"
          stroke-miterlimit="10"
          stroke-linecap="round"
          stroke-linejoin="round"
        ></path>
        <path
          d="M8.33073 8.83333C9.94156 8.83333 11.2474 7.5275 11.2474 5.91667C11.2474 4.30584 9.94156 3 8.33073 3C6.7199 3 5.41406 4.30584 5.41406 5.91667C5.41406 7.5275 6.7199 8.83333 8.33073 8.83333Z"
          stroke="#F73C71"
          stroke-width="1.5"
          stroke-miterlimit="10"
          stroke-linecap="round"
          stroke-linejoin="round"
        ></path>
        <path
          d="M18.3333 15.0833H15"
          stroke="#F73C71"
          stroke-width="1.5"
          stroke-miterlimit="10"
          stroke-linecap="round"
          stroke-linejoin="round"
        ></path>
        <path
          d="M6.66406 15.0833H1.66406"
          stroke="#F73C71"
          stroke-width="1.5"
          stroke-miterlimit="10"
          stroke-linecap="round"
          stroke-linejoin="round"
        ></path>
        <path
          d="M11.6667 18C13.2775 18 14.5833 16.6942 14.5833 15.0834C14.5833 13.4725 13.2775 12.1667 11.6667 12.1667C10.0558 12.1667 8.75 13.4725 8.75 15.0834C8.75 16.6942 10.0558 18 11.6667 18Z"
          stroke="#F73C71"
          stroke-width="1.5"
          stroke-miterlimit="10"
          stroke-linecap="round"
          stroke-linejoin="round"
        ></path>
      </svg>
    </button>
  </div>
  <!-- Modal -->
  <div class="modal fade create-post-modal all-events-filtermodal" id="main-center-modal-filter" tabindex="-1"
  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Filter</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <div class="all-events-filter-wrp">
                  <form action="">
                      <div class="form-check">
                          <input class="form-check-input select_all_post" data-post_type="all" type="checkbox" value=""
                              id="flexCheckDefault1" {{ in_array('all', $selectedFilters) ? 'checked' : '' }}>
                          <label class="form-check-label" for="flexCheckDefault1">
                              All
                          </label>
                      </div>
                      <div class="form-check">
                          <input class="form-check-input wall_post" data-post_type="host_update" type="checkbox" value=""
                              id="flexCheckDefault2" {{ in_array('host_update', $selectedFilters) ? 'checked' : '' }}
                              >
                          <label class="form-check-label" for="flexCheckDefault2">
                              Host Updates/Posts
                          </label>
                      </div>
                      <div class="form-check">
                          <input class="form-check-input wall_post" data-post_type="video_uploads" type="checkbox" value=""
                              id="flexCheckDefault3" {{ in_array('video_uploads', $selectedFilters) ? 'checked' : '' }}
                              >
                          <label class="form-check-label" for="flexCheckDefault3">
                              Video Uploads
                          </label>
                      </div>
                      <div class="form-check">
                          <input class="form-check-input wall_post" data-post_type="photo_uploads" type="checkbox" value=""
                              id="flexCheckDefault4" {{ in_array('photo_uploads', $selectedFilters) ? 'checked' : '' }}>
                          <label class="form-check-label" for="flexCheckDefault4">
                              Photo Uploads
                          </label>
                      </div>
                      <div class="form-check">
                          <input class="form-check-input wall_post" data-post_type="polls" type="checkbox" value=""
                              id="flexCheckDefault4"  {{ in_array('polls', $selectedFilters) ? 'checked' : '' }}>
                          <label class="form-check-label" for="flexCheckDefault4">
                              Polls
                          </label>
                      </div>
                      <div class="form-check">
                          <input class="form-check-input wall_post" data-post_type="comments" type="checkbox" value=""
                              id="flexCheckDefault4" {{ in_array('comments', $selectedFilters) ? 'checked' : '' }}>
                          <label class="form-check-label" for="flexCheckDefault4">
                              Comments
                          </label>
                      </div>
                      <div class="form-check">
                          <input class="form-check-input wall_post" data-post_type="rsvp" type="checkbox" value=""
                              id="flexCheckDefault4" {{ in_array('rsvp', $selectedFilters) ? 'checked' : '' }}>
                          <label class="form-check-label" for="flexCheckDefault4">
                              RSVP ‘s
                          </label>
                      </div>
                  </form>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="cmn-btn reset-btn wall_filter_reset">Reset</button>
              <button type="button" class="cmn-btn wall_apply_filter" data-event_id="{{$eventDetails['id']}}">Apply</button>
          </div>
      </div>
  </div>
</div>
