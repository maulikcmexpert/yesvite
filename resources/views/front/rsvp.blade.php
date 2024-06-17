<x-front.advertise />
<section class="rsvp-wrp">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 mb-lg-0 mb-sm-4 mb-md-4 mb-0">
                <div class="rsvp-slider owl-carousel owl-theme">

                    <?php
                    if ($event->event_image->isNotEmpty()) { ?>
                        @foreach($event->event_image as $value)
                        <div class="item">
                            <div class="rsvp-img">
                                <img src="{{ asset('storage/event_images/'.$value->image)}}" alt="birth-card">
                            </div>
                        </div>
                        @endforeach
                    <?php
                    }
                    ?>

                </div>
            </div>
            <div class="col-lg-7">
                <div class="rsvp-form">
                    <h5 class="title">RSVP</h5>
                    <div class="author-wrp">
                        <div class="author-img">
                            <img src="" alt="">
                        </div>
                        <div class="author-title">
                            <h4>{{$event->event_name}}</h4>
                            <p><span>Hosted by:</span>{{$event->hosted_by}}</p>
                        </div>
                    </div>
                    <div class="thank-card">
                        <p>{{$event->message_to_guests}}</p>
                    </div>
                    <div class="event-detail">
                        <h5>Event Details</h5>
                        <div class="d-flex flex-wrap">
                            <?php

                            dd($event->event_detail);

                            ?>
                            <div class="d-flex align-items-center justify-content-between w-100 mb-2">
                                <h6>RSVP By Sept 20</h6>
                                <h6>+1 (Limit 5)</h6>
                            </div>
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <h6>Adults & Kids</h6>
                                <h6>Potluck Event</h6>
                            </div>
                        </div>
                    </div>
                    <div class="rsvp-radio">
                        <h5>RSVP</h5>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <div class="input-form">
                                    <input type="radio" id="option1" name="foo" checked />
                                    <label for="option1"><svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10.5001 18.3334C15.0834 18.3334 18.8334 14.5834 18.8334 10.0001C18.8334 5.41675 15.0834 1.66675 10.5001 1.66675C5.91675 1.66675 2.16675 5.41675 2.16675 10.0001C2.16675 14.5834 5.91675 18.3334 10.5001 18.3334Z" stroke="#23AA26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M6.95825 9.99993L9.31659 12.3583L14.0416 7.6416" stroke="#23AA26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg> YES</label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <div class="input-form">
                                    <input type="radio" id="option2" name="foo" />
                                    <label for="option2"><svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10.4974 18.3346C15.0807 18.3346 18.8307 14.5846 18.8307 10.0013C18.8307 5.41797 15.0807 1.66797 10.4974 1.66797C5.91406 1.66797 2.16406 5.41797 2.16406 10.0013C2.16406 14.5846 5.91406 18.3346 10.4974 18.3346Z" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M8.14062 12.3573L12.8573 7.64062" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12.8573 12.3573L8.14062 7.64062" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg> NO</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="count-wrp">
                        <h5>Guest Count:</h5>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <h6>Adults</h6>
                                <div class="qty-container">
                                    <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                    <input type="number" name="qty" value="0" class="input-qty" />
                                    <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <h6>Kids</h6>
                                <div class="qty-container">
                                    <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                    <input type="number" name="qty" value="0" class="input-qty" />
                                    <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="massage-box">
                        <textarea name="Message to host......" id="" placeholder="Message to host......"></textarea>
                    </div>
                    <div class="rsvp-btns d-flex">
                        <button type="button" class="cancel-btn">Cancel</button>
                        <button type="button" class="send-btn">Send</button>
                    </div>
                    <div class="calender d-flex align-items-center justify-content-center">
                        <h5 class="me-2">Add to calendar</h5>
                        <span>
                            <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.5026 14.6654C12.1693 14.6654 15.1693 11.6654 15.1693 7.9987C15.1693 4.33203 12.1693 1.33203 8.5026 1.33203C4.83594 1.33203 1.83594 4.33203 1.83594 7.9987C1.83594 11.6654 4.83594 14.6654 8.5026 14.6654Z" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M5.83594 8H11.1693" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M8.5 10.6654V5.33203" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </div>
                    <div class="rsvp-app">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <div class="target d-flex gap-3 align-items-center">
                                    <img src="./assets/image/Pic.png" alt="">
                                    <h5>Target</h5>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <div class="app-img">
                                    <img src="./assets/image/app-img.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="note-wrp">
                        <h5><span>Note:</span> THis is a Potluck Event</h5>
                        <p>Sign Up on iOS or Android Apps to let them know what you will be brining.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>