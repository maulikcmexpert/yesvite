<!-- ============ contact-wrapper ============ -->
<section class="about-banner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="content">
                    <h1 class="contact-u-form-title">Contact Us</h1>
                    <form action="{{ route('contact.submit') }}" id="contact_us_form" method="POST" class="mt-sm-4 mt-0 mb-5 mb-md-0">
                        @csrf
                        <div class="mb-3">
                            <div class="input-form">
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="name" name="name">
                                    <label for="name" class="floating-label">Name<span>*</span></label>
                                </div>
                                <label id="name-error" class="error" for="name"></label>
                            </div>
                            <span class="text-danger" style="font-size: 12px">{{ $errors->first('name') }}</span>
                        </div>
                        <div class="mb-3">
                            <div class="input-form">   
                                <div class="position-relative">
                                    <input type="email" class="form-control" id="email" name="email">
                                    <label for="email" class="floating-label">Email<span>*</span></label>
                                </div>
                                <label id="email-error" class="error contact_us_email_err" for="email"></label>
                            </div>
                            <span class="text-danger" style="font-size: 12px">{{ $errors->first('email') }}</span>
                        </div>
                        <div class="mb-3">
                            <div class="input-form">
                                <div class="position-relative">
                                    <textarea class="form-control" id="message" name="message" rows="4"></textarea>
                                    <label for="message" class="floating-label">Message <span>*</span></label>
                                </div>
                                <label id="message-error" class="error" for="message"></label>
                            </div>
                            <span class="text-danger" style="font-size: 12px">{{ $errors->first('message') }}</span>
                        </div>

                        <br>
                        <button type="submit" class="cmn-btn">Send Message</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="about-img wow fadeInRight text-center" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">
                    <img src="{{ asset('assets/front/image/about-img.png') }}" alt="about-img">
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ========== event-planning =========== -->
<section class="event-planing">
    <div class="container">
        <div class="content">
            <h2 class="wow fadeInDown" data-wow-duration="8s" data-wow-delay="0" data-wow-offset="0">Event Planning
                2.0</h2>
            <h6 class="wow fadeInDown" data-wow-duration="10s" data-wow-delay="0" data-wow-offset="0">At
                <span>Yesvite</span>, we believe in the power of personal connections and the importance of
                cherishing every moment with family and friends. In a world dominated by screens, our mission is to
                create opportunities for people to gather and celebrate in person. Our platform is designed to make
                planning events simple and enjoyable, encouraging meaningful interactions and lasting memories. We
                are dedicated to helping you make the most of your precious time, fostering real-life experiences
                that bring loved ones together, away from the digital distractions of everyday life. Start planning
                today!
            </h6>
            <h6 class="wow fadeInDown" data-wow-duration="12s" data-wow-delay="0" data-wow-offset="0">Start planning
                today!</h6>
        </div>
    </div>
</section>

<!-- =========== app-store ======== -->
<section class="app-store-wrap">
    <div class="container">
        <div class="app-store d-flex justify-content-center gap-2">
            <a href="#" class="google-app">
                <img src="{{ asset('assets/front/image/google-app.png') }}" alt="google-app">
            </a>
            <a href="#" class="mobile-app">
                <img src="{{ asset('assets/front/image/mobile-app.png') }}" alt="mobile-app">
            </a>
        </div>
    </div>
</section>

<!-- ======== join-us ======= -->
<section class="join-us">
    <div class="container">
        <div class="content">
            <h2>Join us in taking event planning to the next level</h2>
            <p>In a world where digital connections can sometimes feel impersonal, we believe in the magic of
                face-to-face interactions. . Join today and create your next event with your friends and loved ones.
            </p>
            <div class="d-flex align-items-center justify-content-center">
                <button type="button" class="cmn-btn">Join Us</button>
            </div>
        </div>
    </div>
    <img class="left-img wow fadeInLeft" data-wow-duration="3s" data-wow-delay="0" data-wow-offset="0"
        src="{{ asset('assets/front/image/left-banner.png') }}" alt="left-banner">
    <img class="right-img wow fadeInRight" data-wow-duration="3s" data-wow-delay="0" data-wow-offset="0"
        src="{{ asset('assets/front/image/right-banner.png') }}" alt="right-banner">
</section>
