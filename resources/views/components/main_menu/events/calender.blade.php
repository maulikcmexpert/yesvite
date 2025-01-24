<div class="col-xl-3">
    <div class="home-main-right">
        <div class="calendar-wrp">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsecalender" aria-expanded="true" aria-controls="collapsecalender">
                      Calendar
                    </button>
                    <span>Today</span>
                  </h2>
                  <div id="collapsecalender" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="calender-main">
                            <div id="calendar" class="calendar"></div>
                        </div>
                        <div class="calender-month-event-stats-wrp">
                            <div class="calender-month-event-stats-title">
                                <h3>
                                  <span>
                                    <svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.16797 18.3334H18.8346" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M8.625 3.33329V18.3333H12.375V3.33329C12.375 2.41663 12 1.66663 10.875 1.66663H10.125C9 1.66663 8.625 2.41663 8.625 3.33329Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M3 8.33329V18.3333H6.33333V8.33329C6.33333 7.41663 6 6.66663 5 6.66663H4.33333C3.33333 6.66663 3 7.41663 3 8.33329Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M14.668 12.5V18.3334H18.0013V12.5C18.0013 11.5834 17.668 10.8334 16.668 10.8334H16.0013C15.0013 10.8334 14.668 11.5834 14.668 12.5Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                  </span>
                                  Month Event Stats:
                                </h3>
                                <i class="fa-solid fa-angle-right"></i>
                            </div>
                            <div class="calender-month-event-stats-inner">
                                <h5>
                                  <span>
                                    <svg viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.33301 1.58331V3.58331" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10.667 1.58331V3.58331" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M2.33301 6.31H13.6663" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14 5.91665V11.5833C14 13.5833 13 14.9166 10.6667 14.9166H5.33333C3 14.9166 2 13.5833 2 11.5833V5.91665C2 3.91665 3 2.58331 5.33333 2.58331H10.6667C13 2.58331 14 3.91665 14 5.91665Z" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10.4635 9.38332H10.4694" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10.4635 11.3833H10.4694" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.99666 9.38332H8.00265" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.99666 11.3833H8.00265" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M5.52987 9.38332H5.53585" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M5.52987 11.3833H5.53585" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                  </span>
                                  Total Events
                                </h5>
                                <h3 class="text-center month_total_event">{{$profileData['total_events_of_current_month']}}</h3>
                            </div>
                            <div class="calender-month-event-stats-inner-wrp">
                              <div class="calender-month-event-stats-inner">
                                <h5>
                                  <span>
                                    <svg viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.33301 1.58331V3.58331" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10.667 1.58331V3.58331" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M2.33301 6.31H13.6663" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14 5.91665V11.5833C14 13.5833 13 14.9166 10.6667 14.9166H5.33333C3 14.9166 2 13.5833 2 11.5833V5.91665C2 3.91665 3 2.58331 5.33333 2.58331H10.6667C13 2.58331 14 3.91665 14 5.91665Z" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10.4635 9.38332H10.4694" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10.4635 11.3833H10.4694" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.99666 9.38332H8.00265" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.99666 11.3833H8.00265" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M5.52987 9.38332H5.53585" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M5.52987 11.3833H5.53585" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                  </span>
                                  Invited To 
                                </h5>
                                <h3 class="text-left month_total_event_invited_to">{{$profileData['invitedTo_count_current_month']}}</h3>
                              </div>
                              <div class="calender-month-event-stats-inner">
                                <h5>
                                  <span>
                                    <svg viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.33301 1.58331V3.58331" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10.667 1.58331V3.58331" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M2.33301 6.31H13.6663" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14 5.91665V11.5833C14 13.5833 13 14.9166 10.6667 14.9166H5.33333C3 14.9166 2 13.5833 2 11.5833V5.91665C2 3.91665 3 2.58331 5.33333 2.58331H10.6667C13 2.58331 14 3.91665 14 5.91665Z" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10.4635 9.38332H10.4694" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10.4635 11.3833H10.4694" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.99666 9.38332H8.00265" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.99666 11.3833H8.00265" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M5.52987 9.38332H5.53585" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M5.52987 11.3833H5.53585" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                  </span>
                                  Hosting
                                </h5>
                                <h3 class="text-left month_total_event_hosting">{{$profileData['hosting_count_current_month']}}</h3>
                              </div>
                            </div>
                            <div class="calender-month-event-stats-inner">
                              <h5>
                                <span>
                                  <svg viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M5.33301 1.58331V3.58331" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M10.667 1.58331V3.58331" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M2.33301 6.31H13.6663" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M14 5.91665V11.5833C14 13.5833 13 14.9166 10.6667 14.9166H5.33333C3 14.9166 2 13.5833 2 11.5833V5.91665C2 3.91665 3 2.58331 5.33333 2.58331H10.6667C13 2.58331 14 3.91665 14 5.91665Z" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M10.4635 9.38332H10.4694" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M10.4635 11.3833H10.4694" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.99666 9.38332H8.00265" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M7.99666 11.3833H8.00265" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M5.52987 9.38332H5.53585" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M5.52987 11.3833H5.53585" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                  </svg>
                                </span>
                                Total Events for 2024
                              </h5>
                               <h3 class="text-center">{{$profileData['total_events_of_year']}}</h3>
                            </div>
                            
                        </div>
                    </div>
                  </div>
                </div>
            </div>
          </div>
          {{-- <div class="calender-month-event-stats-inner">
            <h5>
              <span>
                <svg viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.33301 1.58331V3.58331" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10.667 1.58331V3.58331" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M2.33301 6.31H13.6663" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M14 5.91665V11.5833C14 13.5833 13 14.9166 10.6667 14.9166H5.33333C3 14.9166 2 13.5833 2 11.5833V5.91665C2 3.91665 3 2.58331 5.33333 2.58331H10.6667C13 2.58331 14 3.91665 14 5.91665Z" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10.4635 9.38332H10.4694" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10.4635 11.3833H10.4694" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M7.99666 9.38332H8.00265" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M7.99666 11.3833H8.00265" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5.52987 9.38332H5.53585" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5.52987 11.3833H5.53585" stroke="#F73C71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </span>
              Total Events for 2024
            </h5>
             <h3 class="text-center">{{$profileData['total_events_of_year']}}</h3>
          </div> --}}
    </div>
</div>