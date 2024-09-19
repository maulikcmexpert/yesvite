<div class="activity-main-wrp mb-3 new_append_activity {{ $newClass }}" data-id="{{ $dataid }}"
    id="{{ $dataid }}">
    <h3>
        Activity <span class="activity-count-{{ $newClass }} activity-count">{{ $count }}</span>
        <span class="ms-auto">
            <svg class="delete_activity" data-id="{{ $dataid }}" data-class="{{ $newClass }}"
                data-total_activity="{{ $id }}" width="20" height="20" viewBox="0 0 20 20" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M17.5 4.98356C14.725 4.70856 11.9333 4.56689 9.15 4.56689C7.5 4.56689 5.85 4.65023 4.2 4.81689L2.5 4.98356"
                    stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path
                    d="M7.08325 4.1415L7.26659 3.04984C7.39992 2.25817 7.49992 1.6665 8.90825 1.6665H11.0916C12.4999 1.6665 12.6083 2.2915 12.7333 3.05817L12.9166 4.1415"
                    stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path
                    d="M15.7084 7.6167L15.1667 16.0084C15.0751 17.3167 15.0001 18.3334 12.6751 18.3334H7.32508C5.00008 18.3334 4.92508 17.3167 4.83341 16.0084L4.29175 7.6167"
                    stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M8.6084 13.75H11.3834" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M7.91675 10.4165H12.0834" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </span>
    </h3>
    <div class="row all_activity">
        <div class="col-12 mb-4">
            <div class="input-form">
                <input type="text" class="form-control inputText" id="description" name="description[]"
                    required="" />
                <label for="description" class="form-label input-field floating-label select-label">Description</label>
                <label class="error-message" id="desc-error-{{ $dataid }}"></label>

            </div>
        </div>
        <div class="col-6 mb-4">
            {{-- <div class="input-form">
                    <input
                      type="time"
                      class="form-control inputText activity_start_time"
                      id="activity-start-time"
                      name="activity-start-time[]"
                      required=""
                    />
                    <label
                      for="start-time"
                      class="form-label input-field floating-label select-label"
                      >Start Time</label
                    >
                    <label class="error-message" id="start-error-{{$dataid}}"></label>

                  </div> --}}

            <div class="form-group">
                <label>Start Time</label>
                <div class="input-group time ">
                    <input class="form-control timepicker activity_start_time" id="activity-start-time"
                        name="activity-start-time[]" placeholder="HH:MM AM/PM" required="" readonly/><span
                        class="input-group-append input-group-addon"><span class="input-group-text"><svg width="21"
                                height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M18.8334 9.99984C18.8334 14.5998 15.1 18.3332 10.5 18.3332C5.90002 18.3332 2.16669 14.5998 2.16669 9.99984C2.16669 5.39984 5.90002 1.6665 10.5 1.6665C15.1 1.6665 18.8334 5.39984 18.8334 9.99984Z"
                                    stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M13.5917 12.65L11.0083 11.1083C10.5583 10.8416 10.1917 10.2 10.1917 9.67497V6.2583"
                                    stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg></span></span>
                          </div>
                          <label class="error-message" id="start-error-{{ $dataid }}"></label>
            </div>


        </div>
        <div class="col-6 mb-4">


            <div class="form-group">
                <label>End Time</label>
                <div class="input-group time ">
                    <input class="form-control timepicker activity_end_time" id="activity-end-time"
                        name="activity-end-time[]" placeholder="HH:MM AM/PM" required="" readonly/><span
                        class="input-group-append input-group-addon"><span class="input-group-text"><svg width="21"
                                height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M18.8334 9.99984C18.8334 14.5998 15.1 18.3332 10.5 18.3332C5.90002 18.3332 2.16669 14.5998 2.16669 9.99984C2.16669 5.39984 5.90002 1.6665 10.5 1.6665C15.1 1.6665 18.8334 5.39984 18.8334 9.99984Z"
                                    stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M13.5917 12.65L11.0083 11.1083C10.5583 10.8416 10.1917 10.2 10.1917 9.67497V6.2583"
                                    stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg></span></span>
                          </div>
                          <label class="error-message" id="end-error-{{ $dataid }}"></label>
            </div>
            {{-- <div class="input-form">
                    <input
                      type="time"
                      class="form-control inputText activity_end_time"
                      id="activity-end-time"
                      name="activity-end-time[]"
                      required=""
                    />
                    <label
                      for="start-time"
                      class="form-label input-field floating-label select-label"
                      >End Time</label
                    >
                    <label class="error-message" id="end-error-{{$dataid}}"></label>

                  </div> --}}
        </div>
    </div>
</div>

