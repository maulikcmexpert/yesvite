    <!-- ============= banner-wrapper ======== -->
    @php
        $getSocialLink = getSocialLink();
    @endphp
    <section class="banner-wrapper">
        <img src="{{ asset('assets/front/image/left-banner.png') }}" alt="left-banner" class="left-img wow fadeInLeft"
            data-wow-duration="5s" data-wow-delay="0" data-wow-offset="0">
        <img src="{{ asset('assets/front/image/right-banner.png') }}" alt="right-banner" class="right-img wow fadeInRight"
            data-wow-duration="5s" data-wow-delay="0" data-wow-offset="0">
        <div class="container">
            <div class="banner-content">
                <h1 class="wow fadeInDown" data-wow-duration="1s" data-wow-delay="0" data-wow-offset="0">Celebrate Every
                    Moment with Ease!</h1>
                <p class="wow fadeInDown" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">Stress-free
                    event planning starts here! Our user-friendly app handles everything from invites to
                    decorations, so you can relax and enjoy your celebration</p>
                <div class="app-store d-flex justify-content-center gap-2">
                    <a href="{{ isset($getSocialLink->playstore_link) && $getSocialLink->playstore_link != null ? $getSocialLink->playstore_link : '#' }}"
                        class="google-app">
                        <img src="{{ asset('assets/front/image/google-app.png') }}" alt="google-app">
                    </a>
                    <a href="{{ isset($getSocialLink->appstore_link) && $getSocialLink->appstore_link != null ? $getSocialLink->appstore_link : '#' }}"
                        class="mobile-app">
                        <img src="{{ asset('assets/front/image/mobile-app.png') }}" alt="mobile-app">
                    </a>
                </div>
            </div>
            <div class="banner-img">
                <img src="{{ asset('assets/front/image/birthday-card.png') }}" alt="birthday-card">
            </div>
        </div>
    </section>

    <!-- ============ category-wrapper ============ -->
    <section class="category-wrappper">
        <div class="container">
            <div class="category-content">
                <h6>A card for every <br /> occasion!</h6>
                <div class="category-tab">
                    <span>Holidays</span>
                    <span>Weddings</span>
                    <span class="active">Birthdays</span>
                    <span>Business</span>
                    <span>Baby Showers</span>
                </div>
            </div>
        </div>
    </section>

    <section class="features-wrapper">
        <div class="container">

            <div id="owl-demo" class="owl-carousel owl-theme ">
                <div class="item" alt="slide">
                    <div class="feature-slide slide-green d-block">
                        <div class="row">
                            <div class="col-lg-6 p-0">
                                <div class="feature-content">
                                    <h2>Say It Better with Video</h2>
                                    <p>With our Video RSVP feature, saying yes or no to invitations is as easy as
                                        recording a 10-second clip. No more typing—just tap, record, and send. Upgrade
                                        your RSVP game today!</p>
                                    <div class="slide-progress"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 p-0">
                                <div class="feature-img">
                                    <img src="{{ asset('assets/front/image/gallery-img1.png') }}" alt="gallery-img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item" alt="slide">
                    <div class="feature-slide slide-green d-block">
                        <div class="row">
                            <div class="col-lg-6 p-0">
                                <div class="feature-content">
                                    <h2>Video RSVP:<span>Say</span> It Better with Video</h2>
                                    <p><strong>*Video RSVP:</strong> A first in the digital invites space. Saying yes or
                                        no to invitations is as easy as recording a 10-second clip. No more typing—just
                                        tap, record, and send. Try it today!</p>
                                    <div class="slide-progress"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 p-0">
                                <div class="feature-img">
                                    <img src="{{ asset('assets/front/image/gallery-img1.png') }}" alt="gallery-img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item" alt="slide">
                    <div class="feature-slide slide-red d-block">
                        <div class="row">
                            <div class="col-lg-6 p-0">
                                <div class="feature-content">
                                    <h2>Potluck:<span>Share</span> the Joy of Gathering with Potluck Planning</h2>
                                    <p><strong>*Potluck:</strong> Stress-free event planning starts here! You can
                                        suggest your guests what to bring or they can bring custom items to the event.
                                    </p>
                                    <div class="slide-progress"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 p-0">
                                <div class="feature-img">
                                    <img src="{{ asset('assets/front/image/gallery-img2.png') }}" alt="gallery-img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item" alt="slide">
                    <div class="feature-slide slide-blue d-block">
                        <div class="row">
                            <div class="col-lg-6 p-0">
                                <div class="feature-content">
                                    <h2><span>Polls:</span><span>Empower</span> Your Guests With Interactive Polls</h2>
                                    <p><strong>*Polls:</strong> Engage guests every step of the way</p>
                                    <div class="slide-progress"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 p-0">
                                <div class="feature-img">
                                    <img src="{{ asset('assets/front/image/gallery-img3.png') }}" alt="gallery-img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item" alt="slide">
                    <div class="feature-slide slide-yellow d-block">
                        <div class="row">
                            <div class="col-lg-6 p-0">
                                <div class="feature-content">
                                    <h2><span>Your</span> Photos, Personalize Your Invites</h2>
                                    <p>Photo Invites: Add your own photos for a personalized touch on the designs you
                                        choose.</p>
                                    <div class="slide-progress"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 p-0">
                                <div class="feature-img">
                                    <img src="{{ asset('assets/front/image/gallery-img4.png') }}" alt="gallery-img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item" alt="slide">
                    <div class="feature-slide slide-green d-block">
                        <div class="row">
                            <div class="col-lg-6 p-0">
                                <div class="feature-content">
                                    <h2><span>Showcase</span> your loved ones with an invite photo slider</h2>
                                    <p>*Invite Photo Slider: Highlight Your Event with a Captivating Photo Slider</p>
                                    <div class="slide-progress"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 p-0">
                                <div class="feature-img">
                                    <img src="{{ asset('assets/front/image/gallery-img5.png') }}" alt="gallery-img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item" alt="slide">
                    <div class="feature-slide slide-red d-block">
                        <div class="row">
                            <div class="col-lg-6 p-0">
                                <div class="feature-content">
                                    <h2><span>Add</span> Co-host to help you manage yours events</h2>
                                    <p>*Co-host: Share planning duties and responsibilities.</p>
                                    <div class="slide-progress"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 p-0">
                                <div class="feature-img">
                                    <img src="{{ asset('assets/front/image/gallery-img6.png') }}" alt="gallery-img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item" alt="slide">
                    <div class="feature-slide slide-yellow d-block">
                        <div class="row">
                            <div class="col-lg-6 p-0">
                                <div class="feature-content">
                                    <h2><span>Enjoy</span> a Distraction With No Ads Event Experience</h2>
                                    <p>*Say Goodbye to Ads and Focus on What Matters</p>
                                    <div class="slide-progress"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 p-0">
                                <div class="feature-img">
                                    <img src="{{ asset('assets/front/image/gallery-img7.png') }}" alt="gallery-img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item" alt="slide">
                    <div class="feature-slide slide-blue d-block">
                        <div class="row">
                            <div class="col-lg-6 p-0">
                                <div class="feature-content">
                                    <h2><span>Connect </span> Personally with Direct Messaging</h2>
                                    <p>*Initiate Direct Conversations with Your Guests</p>
                                    <div class="slide-progress"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 p-0">
                                <div class="feature-img">
                                    <img src="{{ asset('assets/front/image/gallery-img8.png') }}" alt="gallery-img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item" alt="slide">
                    <div class="feature-slide slide-green d-block">
                        <div class="row">
                            <div class="col-lg-6 p-0">
                                <div class="feature-content">
                                    <h2><span>500 </span> Invite limit per event</h2>
                                    <p>Ensure you have the capacity to host large events</p>
                                    <div class="slide-progress"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 p-0">
                                <div class="feature-img">
                                    <img src="{{ asset('assets/front/image/gallery-img9.png') }}" alt="gallery-img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item" alt="slide">
                    <div class="feature-slide slide-red d-block">
                        <div class="row">
                            <div class="col-lg-6 p-0 bg-3">
                                <div class="feature-content">
                                    <h2><span>Send </span>Personalized Thank-You Messages</h2>
                                    <p>Share your appreciation with custom messages after every gathering</p>
                                    <div class="slide-progress"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 p-0">
                                <div class="feature-img">
                                    <img src="{{ asset('assets/front/image/gallery-img10.png') }}" alt="gallery-img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item" alt="slide">
                    <div class="feature-slide slide-blue d-block">
                        <div class="row">
                            <div class="col-lg-6 p-0">
                                <div class="feature-content">
                                    <h2><span>Elevate </span>Your Events with Customizable Activities</h2>
                                    <p>Effortlessly plan and personalize engaging activities for all your events</p>
                                    <div class="slide-progress"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 p-0">
                                <div class="feature-img">
                                    <img src="{{ asset('assets/front/image/gallery-img11.png') }}" alt="gallery-img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======= different-wrapper ======== -->
    <section class="different-wrapper">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-lg-0 mb-4">
                    <div class="diff-content">
                        <h2>What Makes Us Different?</h2>
                        <p>Unlike conventional social tools that measure success by "time in app," we measure success by
                            the quality of time spent together offline. Yesvite is designed to streamline real-world
                            interactions. From private events to curated business events. We provide the tools you need
                            to simplify planning, deepen relationships, and make things happen — together, in person.
                        </p>
                        <div class="review-wrap">
                            <h5>“Yesvite has made planning events fun again. I especially love the event cards and event
                                analytics you get”</h5>
                            <div class="author-wrapper">
                                <div class="author-img">
                                    <img src="{{ asset('assets/front/image/author-img.png') }}" alt="author-img">
                                </div>
                                <div class="author-name">
                                    <h6>Sophia Korhonen</h6>
                                    <p>Team Lead</p>
                                </div>
                                <div class="stars-points ms-auto">
                                    <span>
                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7.24325 6.48543L0.863254 7.41043L0.750254 7.43343C0.579193 7.47884 0.423249 7.56884 0.298346 7.69423C0.173444 7.81962 0.0840577 7.97592 0.0393163 8.14716C-0.00542499 8.31839 -0.00391854 8.49844 0.0436818 8.6689C0.0912821 8.83937 0.183271 8.99415 0.310254 9.11743L4.93225 13.6164L3.84225 19.9714L3.82925 20.0814C3.81878 20.2584 3.85552 20.4349 3.9357 20.5929C4.01589 20.751 4.13664 20.8849 4.28559 20.9809C4.43454 21.077 4.60634 21.1317 4.7834 21.1395C4.96046 21.1474 5.13642 21.108 5.29325 21.0254L10.9993 18.0254L16.6923 21.0254L16.7923 21.0714C16.9573 21.1364 17.1367 21.1564 17.312 21.1292C17.4873 21.102 17.6522 21.0287 17.7898 20.9167C17.9275 20.8047 18.0328 20.6582 18.0951 20.4921C18.1574 20.326 18.1743 20.1463 18.1443 19.9714L17.0533 13.6164L21.6773 9.11643L21.7553 9.03143C21.8667 8.8942 21.9397 8.72988 21.967 8.55523C21.9942 8.38057 21.9747 8.20181 21.9103 8.03716C21.846 7.87251 21.7392 7.72786 21.6007 7.61794C21.4623 7.50803 21.2972 7.43677 21.1223 7.41143L14.7423 6.48543L11.8903 0.70543C11.8077 0.537965 11.68 0.396945 11.5214 0.298335C11.3629 0.199724 11.1799 0.147461 10.9933 0.147461C10.8066 0.147461 10.6236 0.199724 10.4651 0.298335C10.3065 0.396945 10.1788 0.537965 10.0963 0.70543L7.24325 6.48543Z"
                                                fill="#FF4F84" />
                                        </svg>
                                    </span>
                                    <span>
                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7.24325 6.48543L0.863254 7.41043L0.750254 7.43343C0.579193 7.47884 0.423249 7.56884 0.298346 7.69423C0.173444 7.81962 0.0840577 7.97592 0.0393163 8.14716C-0.00542499 8.31839 -0.00391854 8.49844 0.0436818 8.6689C0.0912821 8.83937 0.183271 8.99415 0.310254 9.11743L4.93225 13.6164L3.84225 19.9714L3.82925 20.0814C3.81878 20.2584 3.85552 20.4349 3.9357 20.5929C4.01589 20.751 4.13664 20.8849 4.28559 20.9809C4.43454 21.077 4.60634 21.1317 4.7834 21.1395C4.96046 21.1474 5.13642 21.108 5.29325 21.0254L10.9993 18.0254L16.6923 21.0254L16.7923 21.0714C16.9573 21.1364 17.1367 21.1564 17.312 21.1292C17.4873 21.102 17.6522 21.0287 17.7898 20.9167C17.9275 20.8047 18.0328 20.6582 18.0951 20.4921C18.1574 20.326 18.1743 20.1463 18.1443 19.9714L17.0533 13.6164L21.6773 9.11643L21.7553 9.03143C21.8667 8.8942 21.9397 8.72988 21.967 8.55523C21.9942 8.38057 21.9747 8.20181 21.9103 8.03716C21.846 7.87251 21.7392 7.72786 21.6007 7.61794C21.4623 7.50803 21.2972 7.43677 21.1223 7.41143L14.7423 6.48543L11.8903 0.70543C11.8077 0.537965 11.68 0.396945 11.5214 0.298335C11.3629 0.199724 11.1799 0.147461 10.9933 0.147461C10.8066 0.147461 10.6236 0.199724 10.4651 0.298335C10.3065 0.396945 10.1788 0.537965 10.0963 0.70543L7.24325 6.48543Z"
                                                fill="#FF4F84" />
                                        </svg>
                                    </span>
                                    <span>
                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7.24325 6.48543L0.863254 7.41043L0.750254 7.43343C0.579193 7.47884 0.423249 7.56884 0.298346 7.69423C0.173444 7.81962 0.0840577 7.97592 0.0393163 8.14716C-0.00542499 8.31839 -0.00391854 8.49844 0.0436818 8.6689C0.0912821 8.83937 0.183271 8.99415 0.310254 9.11743L4.93225 13.6164L3.84225 19.9714L3.82925 20.0814C3.81878 20.2584 3.85552 20.4349 3.9357 20.5929C4.01589 20.751 4.13664 20.8849 4.28559 20.9809C4.43454 21.077 4.60634 21.1317 4.7834 21.1395C4.96046 21.1474 5.13642 21.108 5.29325 21.0254L10.9993 18.0254L16.6923 21.0254L16.7923 21.0714C16.9573 21.1364 17.1367 21.1564 17.312 21.1292C17.4873 21.102 17.6522 21.0287 17.7898 20.9167C17.9275 20.8047 18.0328 20.6582 18.0951 20.4921C18.1574 20.326 18.1743 20.1463 18.1443 19.9714L17.0533 13.6164L21.6773 9.11643L21.7553 9.03143C21.8667 8.8942 21.9397 8.72988 21.967 8.55523C21.9942 8.38057 21.9747 8.20181 21.9103 8.03716C21.846 7.87251 21.7392 7.72786 21.6007 7.61794C21.4623 7.50803 21.2972 7.43677 21.1223 7.41143L14.7423 6.48543L11.8903 0.70543C11.8077 0.537965 11.68 0.396945 11.5214 0.298335C11.3629 0.199724 11.1799 0.147461 10.9933 0.147461C10.8066 0.147461 10.6236 0.199724 10.4651 0.298335C10.3065 0.396945 10.1788 0.537965 10.0963 0.70543L7.24325 6.48543Z"
                                                fill="#FF4F84" />
                                        </svg>
                                    </span>
                                    <span>
                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7.24325 6.48543L0.863254 7.41043L0.750254 7.43343C0.579193 7.47884 0.423249 7.56884 0.298346 7.69423C0.173444 7.81962 0.0840577 7.97592 0.0393163 8.14716C-0.00542499 8.31839 -0.00391854 8.49844 0.0436818 8.6689C0.0912821 8.83937 0.183271 8.99415 0.310254 9.11743L4.93225 13.6164L3.84225 19.9714L3.82925 20.0814C3.81878 20.2584 3.85552 20.4349 3.9357 20.5929C4.01589 20.751 4.13664 20.8849 4.28559 20.9809C4.43454 21.077 4.60634 21.1317 4.7834 21.1395C4.96046 21.1474 5.13642 21.108 5.29325 21.0254L10.9993 18.0254L16.6923 21.0254L16.7923 21.0714C16.9573 21.1364 17.1367 21.1564 17.312 21.1292C17.4873 21.102 17.6522 21.0287 17.7898 20.9167C17.9275 20.8047 18.0328 20.6582 18.0951 20.4921C18.1574 20.326 18.1743 20.1463 18.1443 19.9714L17.0533 13.6164L21.6773 9.11643L21.7553 9.03143C21.8667 8.8942 21.9397 8.72988 21.967 8.55523C21.9942 8.38057 21.9747 8.20181 21.9103 8.03716C21.846 7.87251 21.7392 7.72786 21.6007 7.61794C21.4623 7.50803 21.2972 7.43677 21.1223 7.41143L14.7423 6.48543L11.8903 0.70543C11.8077 0.537965 11.68 0.396945 11.5214 0.298335C11.3629 0.199724 11.1799 0.147461 10.9933 0.147461C10.8066 0.147461 10.6236 0.199724 10.4651 0.298335C10.3065 0.396945 10.1788 0.537965 10.0963 0.70543L7.24325 6.48543Z"
                                                fill="#FF4F84" />
                                        </svg>
                                    </span>
                                    <span>
                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7.24325 6.48543L0.863254 7.41043L0.750254 7.43343C0.579193 7.47884 0.423249 7.56884 0.298346 7.69423C0.173444 7.81962 0.0840577 7.97592 0.0393163 8.14716C-0.00542499 8.31839 -0.00391854 8.49844 0.0436818 8.6689C0.0912821 8.83937 0.183271 8.99415 0.310254 9.11743L4.93225 13.6164L3.84225 19.9714L3.82925 20.0814C3.81878 20.2584 3.85552 20.4349 3.9357 20.5929C4.01589 20.751 4.13664 20.8849 4.28559 20.9809C4.43454 21.077 4.60634 21.1317 4.7834 21.1395C4.96046 21.1474 5.13642 21.108 5.29325 21.0254L10.9993 18.0254L16.6923 21.0254L16.7923 21.0714C16.9573 21.1364 17.1367 21.1564 17.312 21.1292C17.4873 21.102 17.6522 21.0287 17.7898 20.9167C17.9275 20.8047 18.0328 20.6582 18.0951 20.4921C18.1574 20.326 18.1743 20.1463 18.1443 19.9714L17.0533 13.6164L21.6773 9.11643L21.7553 9.03143C21.8667 8.8942 21.9397 8.72988 21.967 8.55523C21.9942 8.38057 21.9747 8.20181 21.9103 8.03716C21.846 7.87251 21.7392 7.72786 21.6007 7.61794C21.4623 7.50803 21.2972 7.43677 21.1223 7.41143L14.7423 6.48543L11.8903 0.70543C11.8077 0.537965 11.68 0.396945 11.5214 0.298335C11.3629 0.199724 11.1799 0.147461 10.9933 0.147461C10.8066 0.147461 10.6236 0.199724 10.4651 0.298335C10.3065 0.396945 10.1788 0.537965 10.0963 0.70543L7.24325 6.48543Z"
                                                fill="#FF4F84" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="diff-img">
                        <img class="wow fadeInRight" src="{{ asset('assets/front/image/diff-img.png') }}"
                            alt="diff-img" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="landing-footer">
        <div class="container-fluid">
            <div class="platform-wrp">
                <div class="row">
                    <div class="col-lg-7 mb-lg-0 mb-4">
                        <div class="platform-content">
                            <h2>The best platform to manage all your events</h2>
                            <p>Customizable Designs to Reflect Your Unique Event</p>
                            <div class="app-store d-flex gap-2">
                                <a href="{{ isset($getSocialLink->playstore_link) && $getSocialLink->playstore_link != null ? $getSocialLink->playstore_link : '#' }}"
                                    class="google-app">
                                    <img src="{{ asset('assets/front/image/google-app.png') }}" alt="google-app">
                                </a>
                                <a href="{{ isset($getSocialLink->appstore_link) && $getSocialLink->appstore_link != null ? $getSocialLink->appstore_link : '#' }}"
                                    class="mobile-app">
                                    <img src="{{ asset('assets/front/image/mobile-app.png') }}" alt="mobile-app">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <div class="platform-img">
                                    <img src="{{ asset('assets/front/image/platform-img1.png') }}"
                                        alt="platform-img">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <div class="platform-img"></div>
                                <img src="{{ asset('assets/front/image/platform-img2.png') }}" alt="platform-img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="footer-content d-flex justify-content-between">
                <a href="" class="footer-logo">
                    <svg width="129" height="36" viewBox="0 0 129 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.86965 6.81981H8.48313C8.25473 6.80224 8.35136 6.58262 8.35136 6.58262C8.5007 6.23124 8.6149 6.09947 8.43042 5.95013C8.0966 5.68659 7.8155 5.3967 7.5871 5.08924C6.42753 3.52558 6.71742 1.58418 7.79793 0.837483C8.42163 0.407037 9.33524 0.407037 9.95894 0.837483C11.0395 1.58418 11.3206 3.52558 10.1698 5.08924C9.94137 5.3967 9.66905 5.68659 9.32645 5.95013C9.13319 6.09947 9.25617 6.22245 9.40551 6.58262C9.40551 6.58262 9.50214 6.79345 9.27374 6.81981H8.88722H8.86965Z" fill="#ECB015"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M25.9168 9.75338C25.9168 9.05061 26.1627 8.40934 26.5844 7.9174C27.1115 7.2849 27.9021 6.8896 28.7893 6.8896C30.3706 6.8896 31.6619 8.17215 31.6619 9.76217H34.6575C34.6575 6.52064 32.0309 3.89404 28.7893 3.89404C26.6986 3.89404 24.8714 4.98334 23.826 6.62606C23.7733 6.7139 23.7206 6.80175 23.6679 6.8896C23.1935 7.7417 22.9212 8.7168 22.9212 9.76217H25.9168V9.75338Z" fill="black"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M30.3552 22.1547C29.7227 27.5484 25.146 31.7299 19.5853 31.7299C14.0246 31.7299 9.3073 27.4255 8.78901 21.9175L12.013 21.3465L11.2663 17.1475L0.00439453 19.1416L0.751087 23.3406L4.57239 22.6642C5.45086 30.175 11.8373 35.9992 19.5853 35.9992C27.3333 35.9992 34.0009 29.9115 34.6509 22.1547C34.6861 21.733 34.7036 21.3114 34.7036 20.8809C34.7036 20.4505 34.6861 20.0464 34.6509 19.6423H30.3552C30.3992 20.0464 30.4255 20.4593 30.4255 20.8809C30.4255 21.3026 30.3992 21.733 30.3552 22.1547Z" fill="black"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M115.199 20.3698C115.199 24.4371 117.958 26.8968 121.955 26.8968C123.966 26.8968 126.048 26.0271 127.34 24.1384L124.335 23.0491C123.861 23.5937 123.018 23.9715 121.849 23.9715C120.461 23.9715 119.117 23.1282 118.994 21.3888H128.104C128.35 17.0404 125.846 13.9395 121.797 13.9395C118.195 13.9395 115.19 16.4431 115.19 20.3698H115.199ZM121.902 16.7417C123.369 16.7417 124.555 17.5587 124.608 19.1487H118.994C119.214 17.4621 120.488 16.7417 121.902 16.7417Z" fill="black"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M105.518 17.1664H107.556V23.1224C107.556 26.0301 108.602 26.6011 111.624 26.6011H114.004V23.5265H112.915C111.703 23.5265 111.325 23.3069 111.325 22.1385V17.1752H114.004V14.2939H111.325V11.2192H107.548V14.2939H105.51V17.1752L105.518 17.1664Z" fill="black"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M102.208 12.2458C103.447 12.2458 104.44 11.2268 104.44 10.0145C104.44 8.80222 103.447 7.7832 102.208 7.7832C100.97 7.7832 99.9771 8.77586 99.9771 10.0145C99.9771 11.2531 100.97 12.2458 102.208 12.2458Z" fill="black"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M97.9688 17.1636H100.306V26.5983H104.1V14.2822H97.9688V17.1636Z" fill="black"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M88.2859 26.5983H92.5288L96.9475 14.2822H93.0559L90.4205 22.6715L87.7939 14.2822H83.8936L88.2859 26.5983Z" fill="black"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M77.1936 26.8968C80.4966 26.8968 83.2286 25.4824 83.2286 22.5747C83.2286 20.5103 81.8407 19.6494 78.8627 19.096L76.5787 18.6744C75.4894 18.4723 75.0677 18.1824 75.0677 17.6817C75.0677 17.0404 75.7617 16.689 77.1058 16.689C78.2214 16.689 79.1701 16.935 79.4161 17.831L82.5961 16.6627C81.6738 14.7301 79.4952 13.9307 77.1321 13.9307C74.0751 13.9307 71.4748 15.2484 71.4748 17.831C71.4748 19.8954 73.1175 21.1077 75.4982 21.503L77.7031 21.872C79.1174 22.1179 79.6182 22.4693 79.6182 22.9876C79.6182 23.7606 78.5025 24.1296 77.2814 24.1296C75.8671 24.1296 74.7515 23.6289 74.3737 22.5923L71.1674 23.7607C72.1073 25.825 74.321 26.888 77.2024 26.888L77.1936 26.8968Z" fill="black"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M57.18 20.3698C57.18 24.4371 59.9384 26.8968 63.9354 26.8968C65.947 26.8968 68.029 26.0271 69.3203 24.1384L66.316 23.0491C65.8416 23.5937 64.9983 23.9715 63.8299 23.9715C62.442 23.9715 61.0979 23.1282 60.9749 21.3888H70.0846C70.3306 17.0404 67.8269 13.9395 63.7772 13.9395C60.1755 13.9395 57.1712 16.4431 57.1712 20.3698H57.18ZM63.8826 16.7417C65.3497 16.7417 66.5356 17.5587 66.5883 19.1487H60.9749C61.1946 17.4621 62.4683 16.7417 63.8826 16.7417Z" fill="black"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M43.9792 14.2822L48.6965 26.5192L48.5735 26.8442C48.2046 27.7842 47.9323 27.8632 46.3422 27.8632H45.5956V30.5953H46.8078C50.0142 30.5953 50.9278 29.7256 52.1225 26.5983L56.8399 14.2822H52.7902L50.4359 21.9776L48.0289 14.2822H43.9792Z" fill="black"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.12968 9.63483C3.36687 9.67875 3.32295 9.83688 3.3493 10.2234C3.3493 10.2234 3.36687 10.4518 3.5777 10.364L3.72704 10.2849L6.16038 15.2658H6.22187L6.27458 15.2394L3.83245 10.2322L3.77975 10.2585L3.98179 10.1619C4.17505 10.0477 4.00815 9.89837 4.00815 9.89837C3.72704 9.64361 3.56891 9.58212 3.67433 9.36251C3.86759 8.98477 3.99936 8.60703 4.06964 8.24686C4.45616 6.36695 3.39322 4.7418 2.11067 4.52218C1.7505 4.46069 1.38154 4.50461 1.01259 4.68909C-0.91124 5.64661 -0.0327782 9.06383 3.12968 9.63483Z" fill="#3ABEEA"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.31463 9.63483C9.07745 9.67875 9.12137 9.83688 9.09502 10.2234C9.09502 10.2234 9.07745 10.4518 8.86662 10.364L8.71728 10.2849L6.28394 15.2658H6.22245L6.16974 15.2394L8.61186 10.2322L8.66457 10.2585L8.46252 10.1619C8.26926 10.0477 8.43617 9.89837 8.43617 9.89837C8.71728 9.64361 8.8754 9.58212 8.76998 9.36251C8.57672 8.98477 8.44495 8.60703 8.37468 8.24686C7.98815 6.36695 9.05109 4.7418 10.3336 4.52218C10.6938 4.46069 11.0628 4.50461 11.4317 4.68909C13.3556 5.64661 12.4771 9.06383 9.31463 9.63483Z" fill="#27B076"/>
                        <path opacity="0.21" fill-rule="evenodd" clip-rule="evenodd" d="M8.10115 3.07294C8.21535 2.9763 8.46131 3.08172 8.66336 3.31012C8.85662 3.53852 8.9269 3.80206 8.8127 3.89869C8.6985 3.99532 8.45253 3.8899 8.25048 3.6615C8.05722 3.4331 7.98695 3.16957 8.10115 3.07294Z" fill="white"/>
                        <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd" d="M8.51964 4.21417C8.57235 4.17025 8.68655 4.21417 8.7744 4.31959C8.86224 4.425 8.89738 4.54799 8.84468 4.59191C8.79197 4.63584 8.67777 4.59191 8.58992 4.4865C8.50208 4.38108 8.47572 4.2581 8.51964 4.21417Z" fill="white"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.02645 6.30565H5.63993C5.41153 6.28808 5.50816 6.06846 5.50816 6.06846C5.6575 5.71708 5.7717 5.58531 5.58722 5.43597C5.2534 5.17243 4.9723 4.88254 4.7439 4.57508C3.58433 3.01142 3.87422 1.07002 4.95473 0.323323C5.57843 -0.107123 6.49203 -0.107123 7.11574 0.323323C8.19625 1.07002 8.47736 3.01142 7.32657 4.57508C7.09817 4.88254 6.82585 5.17243 6.48325 5.43597C6.28999 5.58531 6.41297 5.70829 6.56231 6.06846C6.56231 6.06846 6.65894 6.27929 6.43054 6.30565H6.04402H6.02645Z" fill="#ECB015"/>
                        <path d="M6.32789 10.9731H6.22247V15.2688H6.32789V10.9731Z" fill="#ECB015"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4.28744 9.54549C4.88479 10.0462 5.47336 10.4854 5.92138 10.8105C6.17613 11.0037 6.02679 11.1706 5.84232 11.645C5.84232 11.645 5.72812 11.9261 6.02679 11.9525H6.63293C6.94039 11.9349 6.81741 11.645 6.81741 11.645C6.62415 11.1706 6.47481 11.0037 6.73835 10.8105C7.18636 10.4854 7.77493 10.0462 8.37228 9.54549C9.26832 8.78123 10.2522 7.85884 10.7705 6.7959C12.176 3.9409 9.48793 -0.012174 6.33425 2.46509C3.18058 -0.012174 0.483699 3.9409 1.88924 6.7959C2.40753 7.85006 3.40019 8.78123 4.29622 9.54549H4.28744Z" fill="#EA555C"/>
                    </svg>
                </a>
                <ul class="nav">
                    <li class="nav-item">
                      <a class="nav-link" href="about-us.html">About us</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">FAQ</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Help Center</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Terms of Service</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#">Privacy Policy</a>
                    </li>
                </ul>
            </div>
            <div class="footer-copyright">
                <p>Copyright © 2024 Yesvite. All Rights Reserved.</p>
                <div class="footer-bottom d-flex justify-content-between flex-wrap">
                    <ul class="footer-social-link">
                        <li>
                            <a href="#">
                                <span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18.901 1.15381H22.581L14.541 10.3438L24 22.8468H16.594L10.794 15.2628L4.156 22.8468H0.474L9.074 13.0168L0 1.15481H7.594L12.837 8.08681L18.901 1.15381ZM17.61 20.6448H19.649L6.486 3.24081H4.298L17.61 20.6448Z" fill="black"/>
                                    </svg>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span>
                                    <svg width="14" height="24" viewBox="0 0 14 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.34922 13.8015H12.3492L13.5492 9.00146H9.34922V6.60147C9.34922 5.36547 9.34922 4.20147 11.7492 4.20147H13.5492V0.169465C13.158 0.117865 11.6808 0.00146484 10.1208 0.00146484C6.86282 0.00146484 4.54922 1.98986 4.54922 5.64146V9.00146H0.949219V13.8015H4.54922V24.0015H9.34922V13.8015Z" fill="#1877F2"/>
                                    </svg>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span>
                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_3391_343)">
                                        <path d="M2.00026 1.63427C0.11426 3.59327 0.50026 5.67427 0.50026 11.9963C0.50026 17.2463 -0.41574 22.5093 4.37826 23.7483C5.87526 24.1333 19.1393 24.1333 20.6343 23.7463C22.6303 23.2313 24.2543 21.6123 24.4763 18.7893C24.5073 18.3953 24.5073 5.60427 24.4753 5.20227C24.2393 2.19527 22.3883 0.462267 19.9493 0.111267C19.3903 0.0302671 19.2783 0.0062671 16.4103 0.0012671C6.23726 0.0062671 4.00726 -0.446733 2.00026 1.63427Z" fill="url(#paint0_linear_3391_343)"/>
                                        <path d="M12.4984 3.14039C8.86736 3.14039 5.41936 2.81739 4.10236 6.19739C3.55836 7.59339 3.63736 9.40639 3.63736 12.0024C3.63736 14.2804 3.56436 16.4214 4.10236 17.8064C5.41636 21.1884 8.89236 20.8644 12.4964 20.8644C15.9734 20.8644 19.5584 21.2264 20.8914 17.8064C21.4364 16.3964 21.3564 14.6104 21.3564 12.0024C21.3564 8.54039 21.5474 6.30539 19.8684 4.62739C18.1684 2.92739 15.8694 3.14039 12.4944 3.14039H12.4984ZM11.7044 4.73739C19.2784 4.72539 20.2424 3.88339 19.7104 15.5804C19.5214 19.7174 16.3714 19.2634 12.4994 19.2634C5.43936 19.2634 5.23636 19.0614 5.23636 11.9984C5.23636 4.85339 5.79636 4.74139 11.7044 4.73539V4.73739ZM17.2284 6.20839C16.6414 6.20839 16.1654 6.68439 16.1654 7.27139C16.1654 7.85839 16.6414 8.33439 17.2284 8.33439C17.8154 8.33439 18.2914 7.85839 18.2914 7.27139C18.2914 6.68439 17.8154 6.20839 17.2284 6.20839ZM12.4984 7.45139C9.98536 7.45139 7.94836 9.48939 7.94836 12.0024C7.94836 14.5154 9.98536 16.5524 12.4984 16.5524C15.0114 16.5524 17.0474 14.5154 17.0474 12.0024C17.0474 9.48939 15.0114 7.45139 12.4984 7.45139ZM12.4984 9.04839C16.4034 9.04839 16.4084 14.9564 12.4984 14.9564C8.59436 14.9564 8.58836 9.04839 12.4984 9.04839Z" fill="white"/>
                                        </g>
                                        <defs>
                                        <linearGradient id="paint0_linear_3391_343" x1="2.04628" y1="22.4684" x2="24.3517" y2="3.16325" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#FFDD55"/>
                                        <stop offset="0.5" stop-color="#FF543E"/>
                                        <stop offset="1" stop-color="#C837AB"/>
                                        </linearGradient>
                                        <clipPath id="clip0_3391_343">
                                        <rect width="24" height="24" fill="white" transform="translate(0.5 0.00146484)"/>
                                        </clipPath>
                                        </defs>
                                    </svg>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.9148 8.36427H13.3716V10.5843C14.0136 9.30747 15.66 8.16027 18.1332 8.16027C22.8744 8.16027 24 10.7019 24 15.3651V24.0015H19.2V16.4271C19.2 13.7715 18.558 12.2739 16.9236 12.2739C14.6568 12.2739 13.7148 13.8879 13.7148 16.4259V24.0015H8.9148V8.36427ZM0.684 23.7975H5.484V8.16027H0.684V23.7975ZM6.1716 3.06147C6.17178 3.46379 6.09199 3.86215 5.93686 4.23337C5.78174 4.60459 5.55438 4.94128 5.268 5.22387C4.68768 5.80061 3.90217 6.12345 3.084 6.12147C2.26727 6.12092 1.48357 5.7989 0.9024 5.22507C0.617054 4.94152 0.390463 4.60445 0.235612 4.23318C0.0807618 3.86191 0.000694958 3.46373 0 3.06147C0 2.24907 0.324 1.47147 0.9036 0.897865C1.48426 0.323255 2.26829 0.00110579 3.0852 0.00146514C3.9036 0.00146514 4.6884 0.324265 5.268 0.897865C5.8464 1.47147 6.1716 2.24907 6.1716 3.06147Z" fill="#0B66C2"/>
                                    </svg>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div> -->
        </div>
    </section>

    <!-- ====== cookies ====== -->
    <div class="cookies-track">
        <div class="container-fluid">
            <div class="d-flex justify-content-center gap-3 align-items-start">
                <p>We use third-party cookies and tracking technologies that collect information about your use of the
                    site. These third parties may use your information for their own purpose, as described in our <a
                        href="#">Privacy Policy</a></p>
                <button class="close-btn"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.querySelectorAll('.collection-menu').forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.stopPropagation();
                });
            });

            $(document).ready(function() {
                const $cookiesBox = $('.cookies-track');

                if (!localStorage.getItem('cookiesBoxDismissed')) {
                    setTimeout(() => {
                        $cookiesBox.addClass('active');
                    }, 500);
                }

                $('.close-btn').on('click', function() {
                    $cookiesBox.removeClass('active');
                    localStorage.setItem('cookiesBoxDismissed', 'true');
                });
            });
            $(document).ready(function() {
                // Handle the "All Categories" checkbox click
                $('#Allcat').on('change', function() {
                    if ($(this).is(':checked')) {
                        // Check all individual category checkboxes
                        $('input[type="checkbox"]:not(#Allcat)').prop('checked', true);
                        // Show all images
                        $('.image-item').show();
                    } else {
                        // Uncheck all individual category checkboxes
                        $('input[type="checkbox"]:not(#Allcat)').prop('checked', false);
                        // Hide all images
                        $('.image-item').hide();
                    }
                });

                // Handle individual category checkboxes
                $(document).on('change', 'input[type="checkbox"]:not(#Allcat)', function() {
                    // If all individual checkboxes are checked, check "All Categories"
                    const totalCheckboxes = $('input[type="checkbox"]:not(#Allcat)').length;
                    const checkedCheckboxes = $('input[type="checkbox"]:not(#Allcat):checked').length;

                    if (checkedCheckboxes === totalCheckboxes) {
                        $('#Allcat').prop('checked', true);
                    } else {
                        $('#Allcat').prop('checked', false);
                    }

                    // Filter images based on checked categories
                    if (checkedCheckboxes > 0) {
                        $('.image-item').hide(); // Hide all images first
                        $('input[type="checkbox"]:not(#Allcat):checked').each(function() {
                            const categoryId = $(this).data('category-id');
                            const subcategoryId = $(this).data('subcategory-id');

                            // Show images matching the selected categories and subcategories
                            $(`.image-item[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`)
                                .show();
                        });
                    } else {
                        $('.image-item').hide(); // Hide all images if no checkboxes are checked
                    }
                });
                $('#resetCategories').on('click', function(e) {
                    e.preventDefault(); // Prevent default anchor behavior

                    // Uncheck all checkboxes
                    $('#Allcat').prop('checked', false);
                    $('input[type="checkbox"]:not(#Allcat)').prop('checked', false);

                    // Hide all images
                    $('.image-item').hide();
                });
            });
        </script>
    @endpush
