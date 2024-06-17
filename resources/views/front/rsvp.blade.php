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
                            <img src="{{ $event->profile}}" alt="">
                        </div>
                        <div class="author-title">
                            <h4>{{$event->event_name}}</h4>
                            <p><span>Hosted by:</span>{{ $event->hosted_by}}</p>
                        </div>
                    </div>
                    @if($event->message_to_guests != null || $event->message_to_guests != "")
                    <div class="thank-card">
                        <p>{{$event->message_to_guests}}</p>
                    </div>
                    @endif
                    <div class="event-detail">
                        <h5>Event Details</h5>
                        <div class="d-flex flex-wrap">
                            <?php
                            $i = 1;
                            ?>
                            @if($event->event_detail)
                            <div class="d-flex align-items-center justify-content-between {{($i<= 3)?'w-100 mb-2':'w-100'}}">
                                @foreach($event->event_detail as $val)

                                <h6>{{$val}}</h6>
                                <?php $i++; ?>
                                @endforeach
                            </div>
                            @endif

                        </div>
                    </div>
                    <form method="POST" accept="{{ route('rsvp.store') }}" id="rsvpForm">

                        <div class="rsvp-radio">
                            <h5>RSVP</h5>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="input-form">
                                        <input type="radio" id="yes" name="rsvp_status" value="1" {{($isInvited->rsvp_status == '1')?'checked':''}} />
                                        <label for="yes"><svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10.5001 18.3334C15.0834 18.3334 18.8334 14.5834 18.8334 10.0001C18.8334 5.41675 15.0834 1.66675 10.5001 1.66675C5.91675 1.66675 2.16675 5.41675 2.16675 10.0001C2.16675 14.5834 5.91675 18.3334 10.5001 18.3334Z" stroke="#23AA26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M6.95825 9.99993L9.31659 12.3583L14.0416 7.6416" stroke="#23AA26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg> YES</label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="input-form">
                                        <input type="radio" id="no" name="rsvp_status" value="0" {{($isInvited->rsvp_status == '0')?'checked':''}} name="foo" />
                                        <label for="no"><svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                        <input type="number" name="adults" value="{{($isInvited->adults != '0')?$isInvited->adults:'0'}}" class="input-qty" />
                                        <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <h6>Kids</h6>
                                    <div class="qty-container">
                                        <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                        <input type="number" name="kids" value="{{($isInvited->kids != '0')?$isInvited->kids:'0'}}" class="input-qty" />
                                        <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="massage-box">
                            <textarea name="message_to_host" id="" placeholder="Message to host......">{{ ($isInvited->message_to_host != NULL)?$isInvited->message_to_host:""}}</textarea>
                        </div>
                        <div class="rsvp-btns d-flex">
                            <button type="button" class="cancel-btn">Cancel</button>
                            <button type="submit" class="send-btn">Send</button>
                        </div>
                    </form>

                    <div class="rsvp-app">
                        @if(count($giftRegistryDetails) != 0)

                        <div class="row">
                            @foreach($giftRegistryDetails as $value)
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <div class="target d-flex gap-3 align-items-center">
                                    <img src="./assets/image/Pic.png" alt="">
                                    <h5>{{$value['registry_recipient_name']}}</h5>
                                </div>
                            </div>
                            @endforeach

                        </div>
                        @endif
                    </div>
                    <div class="note-wrp">
                        <h5><span>Note:</span> This is a Potluck Event</h5>
                        <p>Sign Up on iOS or Android Apps to let them know what you will be brining.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>