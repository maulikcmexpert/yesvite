

<x-front.advertise />
<!-- ============= contact-details ============ -->
<section class="contact-details profile-details">
    <div class="container">
        <div class="row">
            <x-front.sidebar :profileData="[]" />
            <div class="col-xl-6 col-lg-9 col-md-8">
                <div class="home-center-main">
                    <div class="home-center-content">
                        <x-main_menu.home.profile :profileData="$profileData" />
                      {{-- <div class="home-latest-draf-wrp mobile-latest-draf">
                        <div class="home-center-upcoming-events-title">
                          <h3>Latest Drafts</h3>
                          <a href="#">All Drafts</a>
                        </div>
                        <div class="mobile-draf-slider">
                          <div class="swiper latest-draf-slider">
                            <div class="swiper-wrapper">
                              <!-- Slides -->
                              <div class="swiper-slide">
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
                                        <h3>Brenden’s BBQ</h3>
                                        <p>Last Save:  December 23, 2022 - 8:31 PM</p>
                                      </div>
                                      
                                  </div>
                                  <div class="progress-bar__wrapper">
                                    <progress id="progress-bar" value="75" max="100"></progress>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4>3/4 Steps - Guest</h4>
                                        <label class="progress-bar__value" htmlfor="progress-bar"> 75%</label>
                                        <p><span class="prograsbar-hosting">Hosting</span><span class="prograsbar-pro">Pro</span></p>
                                    </div>
                                  </div>
                                </a>
                              </div>
                              <div class="swiper-slide">
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
                                        <h3>Brenden’s BBQ</h3>
                                        <p>Last Save:  December 23, 2022 - 8:31 PM</p>
                                      </div>
                                      
                                  </div>
                                  <div class="progress-bar__wrapper">
                                    <progress id="progress-bar" value="75" max="100"></progress>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4>3/4 Steps - Guest</h4>
                                        <label class="progress-bar__value" htmlfor="progress-bar"> 75%</label>
                                        <p><span class="prograsbar-hosting">Hosting</span><span class="prograsbar-pro">Pro</span></p>
                                    </div>
                                  </div>
                                </a>
                              </div>
                              <div class="swiper-slide">
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
                                        <h3>Brenden’s BBQ</h3>
                                        <p>Last Save:  December 23, 2022 - 8:31 PM</p>
                                      </div>
                                      
                                  </div>
                                  <div class="progress-bar__wrapper">
                                    <progress id="progress-bar" value="75" max="100"></progress>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4>3/4 Steps - Guest</h4>
                                        <label class="progress-bar__value" htmlfor="progress-bar"> 75%</label>
                                        <p><span class="prograsbar-hosting">Hosting</span><span class="prograsbar-pro">Pro</span></p>
                                    </div>
                                  </div>
                                </a>
                              </div>
                              <div class="swiper-slide">
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
                                        <h3>Brenden’s BBQ</h3>
                                        <p>Last Save:  December 23, 2022 - 8:31 PM</p>
                                      </div>
                                      
                                  </div>
                                  <div class="progress-bar__wrapper">
                                    <progress id="progress-bar" value="75" max="100"></progress>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4>3/4 Steps - Guest</h4>
                                        <label class="progress-bar__value" htmlfor="progress-bar"> 75%</label>
                                        <p><span class="prograsbar-hosting">Hosting</span><span class="prograsbar-pro">Pro</span></p>
                                    </div>
                                  </div>
                                </a>
                              </div>
                              <div class="swiper-slide">
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
                                        <h3>Brenden’s BBQ</h3>
                                        <p>Last Save:  December 23, 2022 - 8:31 PM</p>
                                      </div>
                                      
                                  </div>
                                  <div class="progress-bar__wrapper">
                                    <progress id="progress-bar" value="75" max="100"></progress>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4>3/4 Steps - Guest</h4>
                                        <label class="progress-bar__value" htmlfor="progress-bar"> 75%</label>
                                        <p><span class="prograsbar-hosting">Hosting</span><span class="prograsbar-pro">Pro</span></p>
                                    </div>
                                  </div>
                                </a>
                              </div>
                              <div class="swiper-slide">
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
                                        <h3>Brenden’s BBQ</h3>
                                        <p>Last Save:  December 23, 2022 - 8:31 PM</p>
                                      </div>
                                      
                                  </div>
                                  <div class="progress-bar__wrapper">
                                    <progress id="progress-bar" value="75" max="100"></progress>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4>3/4 Steps - Guest</h4>
                                        <label class="progress-bar__value" htmlfor="progress-bar"> 75%</label>
                                        <p><span class="prograsbar-hosting">Hosting</span><span class="prograsbar-pro">Pro</span></p>
                                    </div>
                                  </div>
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div> --}}
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
              <div class="col-xl-3">
                <input type="hidden" id="totalmonths" value="{{$numMonths}}"/>
                <input type="hidden" id="startmonths" value="{{$startMonth}}"/>
                <input type="hidden" id="diffmonth" value="{{$diffmonth}}"/>
                <input type="hidden" id="calender_json" value="{{$events_calender_json}}"/>


                  <div class="home-main-right">
                      <x-main_menu.calender :profileData="$profileData" />
                      <x-main_menu.home.event_drafts :draftEventArray="$draftEventArray" />
                  </div>
                  <div id="responsive-calendar" class="responsive-calendar" style="display:none;">
                    <div id="responsive-calender-months" class="responsive-calender-months"></div>
                  </div>
                  <div class="responsive-calender-month-wrp">
                    <h3 class="responsive-calender-month-text" style="display:none;">{{$startMonthCalender}}</h3>
                  </div>
              </div>
          </div>
    </div>
</section>