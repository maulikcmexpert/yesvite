<!--rsvp-notification-Modal -->
<div class="modal fade cmn-modal about-rsvp" id="rsvp_by_notification" tabindex="-1" aria-labelledby="rsvp_by_notificationLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"> 
                <form id="notification_rsvp" action="{{route('store_rsvp')}}" method="POST">
                    @csrf
                <div class="modal-header">
                      <div class="d-flex align-items-center">
                        <img src="" alt="rs-img" class="about-rs-img" id="notification_rsvp_profile">
                        <div>
                          <h4 class="modal-title" id="notification_rsvp_eventName"></h4>
                          <span>Hosted by: <span id="notification_rsvp_host"></span></span>
                          <input type="hidden" name="rsvp_user_id" id="rsvp_user_id" />
                          <input type="hidden" name="rsvp_event_id" id="rsvp_event_id" />
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="rsvp-custom-radio guest-rsvp-attend">
                    <h6>RSVP</h6>
                    <div class="rsvp-input-form">
                      <div class="input-form rsvp-yes-checkbox">
                        <input type="radio" id="rsvp_yes" name="rsvp_status" value="1"  name="foo" checked>
                        <label for="rsvp_yes">
                          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M10.0013 18.3346C14.5846 18.3346 18.3346 14.5846 18.3346 10.0013C18.3346 5.41797 14.5846 1.66797 10.0013 1.66797C5.41797 1.66797 1.66797 5.41797 1.66797 10.0013C1.66797 14.5846 5.41797 18.3346 10.0013 18.3346Z" stroke="#23AA26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                          <path d="M6.45703 9.99896L8.81536 12.3573L13.5404 7.64062" stroke="#23AA26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg>
                          YES</label>
                      </div>
                      <div class="input-form rsvp-no-checkbox">
                          <input type="radio" id="rsvp_no" name="rsvp_status" value="0" name="foo">
                          <label for="rsvp_no"><svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M10.4974 18.3346C15.0807 18.3346 18.8307 14.5846 18.8307 10.0013C18.8307 5.41797 15.0807 1.66797 10.4974 1.66797C5.91406 1.66797 2.16406 5.41797 2.16406 10.0013C2.16406 14.5846 5.91406 18.3346 10.4974 18.3346Z" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          <path d="M8.14062 12.3573L12.8573 7.64062" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          <path d="M12.8573 12.3573L8.14062 7.64062" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          </svg>NO</label>
                      </div>
                    </div>
                  </div>
                  <div class="rsvp-guest">
                    <h5>Guests</h5>
                    <div class="rsvp-guest-count">
                      <div>
                        <h6>Adults</h6>
                        <div class="qty-container ms-auto">
                          <button class="qty-btn-minus rsvp_minus_notify" type="button"><i class="fa fa-minus"></i></button>
                          <input type="number" name="rsvp_notification_adult" id="rsvp_notification_adult" value="0" class="input-qty" readonly>
                          <button class="qty-btn-plus rsvp_plus_notify" type="button"><i class="fa fa-plus"></i></button>
                        </div>
                      </div>
                      <div>
                        <h6>Kids</h6>
                        <div class="qty-container ms-auto">
                          <button class="qty-btn-minus rsvp_minus_notify" type="button"><i class="fa fa-minus"></i></button>
                          <input type="number" name="rsvp_notification_kids" id="rsvp_notification_kids"value="0" class="input-qty" readonly>
                          <button class="qty-btn-plus rsvp_plus_notify" type="button"><i class="fa fa-plus"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="rsvp-msgbox">
                      <h5>Message</h5>
                      <div class="input-form">
                        <textarea name="rsvp_notification_message" id="rsvp_notification_message" class="form-control inputText" id="Fname" name="Fname" required=""></textarea>
                        <label for="Fname" class="form-label input-field floating-label">Message with your RSVP</label>
                      </div>
                      <div class="d-flex align-items-center">
                        <span class="d-flex align-items-center">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.9987 1.3335C4.32536 1.3335 1.33203 4.32683 1.33203 8.00016C1.33203 11.6735 4.32536 14.6668 7.9987 14.6668C11.672 14.6668 14.6654 11.6735 14.6654 8.00016C14.6654 4.32683 11.672 1.3335 7.9987 1.3335ZM7.4987 5.3335C7.4987 5.06016 7.72536 4.8335 7.9987 4.8335C8.27203 4.8335 8.4987 5.06016 8.4987 5.3335V8.66683C8.4987 8.94016 8.27203 9.16683 7.9987 9.16683C7.72536 9.16683 7.4987 8.94016 7.4987 8.66683V5.3335ZM8.61203 10.9202C8.5787 11.0068 8.53203 11.0735 8.47203 11.1402C8.40536 11.2002 8.33203 11.2468 8.25203 11.2802C8.17203 11.3135 8.08536 11.3335 7.9987 11.3335C7.91203 11.3335 7.82536 11.3135 7.74536 11.2802C7.66536 11.2468 7.59203 11.2002 7.52536 11.1402C7.46536 11.0735 7.4187 11.0068 7.38536 10.9202C7.35203 10.8402 7.33203 10.7535 7.33203 10.6668C7.33203 10.5802 7.35203 10.4935 7.38536 10.4135C7.4187 10.3335 7.46536 10.2602 7.52536 10.1935C7.59203 10.1335 7.66536 10.0868 7.74536 10.0535C7.90536 9.98683 8.09203 9.98683 8.25203 10.0535C8.33203 10.0868 8.40536 10.1335 8.47203 10.1935C8.53203 10.2602 8.5787 10.3335 8.61203 10.4135C8.64536 10.4935 8.66536 10.5802 8.66536 10.6668C8.66536 10.7535 8.64536 10.8402 8.61203 10.9202Z" fill="#E2E8F0"/>
                        </svg></span>
                        <h6>This message will be visible to all guests.</h6>
                      </div>
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button"id="notification_rsvp_btn" class="btn btn-secondary">Send</button>
                </div>
                </form>
            </div>
        </div>
</div>


<!--Buy-Credits-Modal -->
@if(isset($prices)&&count($prices)>0)
    

<button type="button" data-bs-toggle="modal" data-bs-target="#buycreditsmodal" class="buynow d-none">buycreditsmodal</button>
<div class="modal fade cmn-modal buycreditsmodal" id="buycreditsmodal" tabindex="-1" aria-labelledby="aboutsuccessLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div><h4 class="modal-title" id="aboutsuccessLabel">Buy Credits</h4></div>
                    <div class="totle_credit_buy_wrp">
                        <img src="{{asset('assets')}}/coin.svg" alt="">
                        <span class="available-coins">{{$coins}}</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="reactions-info-main event-center-tabs-main">
                    <!-- ===tabs=== -->
                    <nav>
                      <div class="nav nav-tabs reaction-nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-bulk-credit-options-tab" data-bs-toggle="tab" data-bs-target="#nav-bulk-credit-options" type="button" role="tab" aria-controls="nav-bulk-credit-options" aria-selected="true">
                            Bulk Credit Options
                        </button>
                        <button class="nav-link" id="nav-credit-feature-details-tab" data-bs-toggle="tab" data-bs-target="#nav-credit-feature-details" type="button" role="tab" aria-controls="nav-credit-feature-details" aria-selected="false" tabindex="-1">
                            Feature Details
                        </button>
                      </div>
                    </nav>
                    <!-- ===tabs=== -->

                    <!-- ===Tab-content=== -->
                    <div class="tab-content" id="myTabContent">

                      <div class="tab-pane fade active show" id="nav-bulk-credit-options" role="tabpanel" aria-labelledby="nav-bulk-credit-options-tab">
                          <div class="bulk-credit-options-wrp">
                              <div class="bulk-credit-options-head">
                                  <h5>1 Credit = 1 Invite</h5>
                                  <p>Unused credits can be applied to future events</p>
                              </div>
                              
                              <div class="bulk-credit-options-listing">
                                <ul>
                                    @foreach($prices as $key => $price)
                                    @if(!$loop->last)
                                        <li class="best-deal-item">
                                            <div class="bulk-credit-options-listing-left">
                                                <h3>
                                                    <span><img src="{{ asset('assets') }}/coin.svg" alt=""></span>
                                                    {{ $price['coins'] }} Credits
                                                </h3>
                                                <p>${{ number_format($price['price'] / $price['coins'], 2) }} per credit</p>
                                            </div>
                                            <div class="bulk-credit-options-listing-right">
                                                <h4>${{ number_format($price['price'], 2) }}</h4>
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input price-option" 
                                                        type="radio" 
                                                        name="priceId" 
                                                        data-price="{{ $price['price'] }}" 
                                                        data-price-id="{{ $price['priceId'] }}" 
                                                        data-coins="{{ $price['coins'] }}" 
                                                        id="price-{{ $key }}"
                                                    >
                                                </div>
                                            </div>
                                        </li>
                                    @else
                                        <div class="best-deal-wrp">
                                            <div class="best-deal-title">
                                                <h5>80% Saving over the 15 pack</h5>
                                                <h4>BEST DEAL!</h4>
                                            </div>
                                            <li>
                                                <div class="bulk-credit-options-listing-left">
                                                    <h3>
                                                        <span><img src="{{ asset('assets') }}/coin.svg" alt=""></span>
                                                        {{ $price['coins'] }} Credits
                                                    </h3>
                                                    <p>${{ number_format($price['price'] / $price['coins'], 2) }} per credit</p>
                                                </div>
                                                <div class="bulk-credit-options-listing-right">
                                                    <h4>${{ number_format($price['price'], 2) }}</h4>
                                                    <div class="form-check">
                                                        <input 
                                                            class="form-check-input price-option" 
                                                            type="radio" 
                                                            name="priceId" 
                                                            data-price="{{ $price['price'] }}" 
                                                            data-price-id="{{ $price['priceId'] }}" 
                                                            data-coins="{{ $price['coins'] }}" 
                                                            id="price-{{ $key }}"
                                                        >
                                                    </div>
                                                </div>
                                            </li>
                                        </div>
                                    @endif
                                @endforeach
                                </ul>
                              </div>
                          </div>
                      </div>

                      <div class="tab-pane fade" id="nav-credit-feature-details" role="tabpanel" aria-labelledby="nav-credit-feature-details-tab">
                          <div class="credit-feature-details-wrp">
                            <div class="credit-feature-details-note">
                                <p>Straightforward transparent prices.  All features included.  No hidden costs or gimmicks.</p>
                            </div>
                            <div class="bulk-credit-options-head">
                              <h5>1 Credit = 1 Invite</h5>
                              <p>Unused credits can be applied to future events</p>
                            </div>
                            <ul class="credit-feature-details-listing">
                                <li><span><i class="fa-solid fa-circle-check"></i></span>No monthly/yearly subscriptions</li>
                                <li><span><i class="fa-solid fa-circle-check"></i></span>No gimmicky extra fees</li>
                                <li><span><i class="fa-solid fa-circle-check"></i></span>No Ads</li>
                                <li><span><i class="fa-solid fa-circle-check"></i></span>Set a schedule for event activities </li>
                                <li><span><i class="fa-solid fa-circle-check"></i></span>Create Potluck </li>
                                <li><span><i class="fa-solid fa-circle-check"></i></span>Guests can leave video RSVP’s</li>
                                <li><span><i class="fa-solid fa-circle-check"></i></span>Guest polls on event wall</li>
                                <li><span><i class="fa-solid fa-circle-check"></i></span>Add a 3 photos slider with invite </li>
                                <li><span><i class="fa-solid fa-circle-check"></i></span>Co-host Option</li>
                                <li><span><i class="fa-solid fa-circle-check"></i></span>Thank you messages after event</li>
                                <li><span><i class="fa-solid fa-circle-check"></i></span>Guests can leave you video RSVP’s</li>
                                <li><span><i class="fa-solid fa-circle-check"></i></span>Unlimited Events</li>
                                <li><span><i class="fa-solid fa-circle-check"></i></span>Event Analytics</li>
                                <li><span><i class="fa-solid fa-circle-check"></i></span>Direct message (DM) guests</li>
                            </ul>
                          </div>
                      </div>

                    </div>
                    <!-- ===Tab-content=== -->  
                </div>
            </div>
            <div class="modal-footer">
                <button 
                    type="button" 
                    class="btn btn-secondary purchase-button" 
                    data-price-id="" 
                    data-price="0" 
                    disabled
                >
                    Purchase - $0.00
                </button>
            </div>
        </div>
    </div>
</div>


@endif

<script src="{{ asset('assets/front/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery-validate.js') }}"></script>

<script src="{{ asset('assets/admin/js/jquery-validate-additional.js') }}"></script>
<script src="{{ asset('assets/front/js/contact_us.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" stylesheet.crossOrigin = "anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fontfaceobserver/2.1.0/fontfaceobserver.standalone.js"></script>

{{-- <script src="{{ asset('assets/event/js/fontLoder.js') }}"></script> --}}
<!-- owl-carousel-js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<!-- custom-js -->





<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js" integrity="sha512-Gs+PsXsGkmr+15rqObPJbenQ2wB3qYvTHuJO6YJzPe/dTLvhy0fmae2BcnaozxDo5iaF8emzmCZWbQ1XXiX2Ig==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- <script src="https://hammerjs.github.io/dist/hammer.min.js"></script> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>

<script src="{{ asset('assets/front/js/common.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.min.js" integrity="sha512-TToQDr91fBeG4RE5RjMl/tqNAo35hSRR4cbIFasiV2AAMQ6yKXXYhdSdEpUcRE6bqsTiB+FPLPls4ZAFMoK5WA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{ asset('assets/front/js/script.js') }}"></script>

<!-- ======== wow-js ======== -->
<script src="{{ asset('assets/front/js/animation.js') }}"></script>

<script src="{{ asset('assets/front/js/wow.min.js') }}"></script>

{{-- <script src="{{ asset('assets/front/js/design.js') }}"></script> --}}

<script src="{{ asset('assets/front/js/map.js') }}"></script>
{{-- <script src="{{ asset('assets/front/js/map.js') }}"></script> --}}



<script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html-to-image/1.7.0/html-to-image.js" integrity="sha512-uIP4HNFMvnpcVpzoE17SkA3zrdBkNZy2G6t65q2m44kDBHd6emnvYOuXxtPdudQOsCMxO0HuhBnYm1ao9GvJWw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<script src="{{ asset('assets/front/js/dom-to-image.min.js') }}"></script>


{{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> --}}

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script src="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/bootstrap.timepicker/0.2.6/js/bootstrap-timepicker.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js'></script>

<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js'></script>

<script src='https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js'></script>


{{-- <script type="text/javascript" async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBW43KgTNs_Kusuvbian6KYGi_QzXOLS4w&libraries=places&callback=initMap" ></script> --}}
<script
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPyeIABhKFMSFXrrcR1IE8jBUXJt-2bG4&callback=initMap&v=weekly&libraries=places"
defer
></script>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- {{-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}} -->
<script src="{{ asset('assets/front/new_js/scritp.js')}}"></script>
<script src="{{ asset('assets/front/walljs/scritp.js')}}"></script>
<script src="{{ asset('assets/front/new_js/slider.js') }}"></script>

<script src="{{ asset('assets/front/new_js/calender.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

{{-- <script src="{{ asset('assets/front/js/event_wall.js')}}"></script > --}}
<script src="{{ asset('assets/front/walljs/slider.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/4.0.0/apexcharts.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('assets/front/walljs/guest.js')}}"></script>
<script src="{{ asset('assets/front/walljs/potluck.js')}}"></script>

<script src="{{asset('assets/front/js/event.js') }}"></script>

<script>
    new WOW().init()
</script>

@if(isset($js))

@foreach($js as $value)

<script src="{{ asset('assets/front') }}/js/{{$value}}.js"></script>

@endforeach

@endif

@stack('scripts')

<script>

$(document).on("click", ".buynow", function () {
    $("input.price-option").prop("checked", false);
});
$("#buycreditsmodal").on("shown.bs.modal", function () {
    $("input.price-option").prop("checked", false);
});
    $(document).on('click','.create_event_with_plan',function(){
    // toggleSidebar('sidebar_change_plan_create');
    // $('input[name="plan_check"]:checked').each(function () {
    //     var plan = $(this).data('plan');
    //     eventData.plan_selected=plan;
    //     alert(plan);
    //     window.location.href="event";
    // });

    var checkedPlan = $('input[name="plan_check"]:checked'); // Select the checked one
    if (checkedPlan.length) { // Ensure there is a checked checkbox
        var plan = checkedPlan.data('plan');
        // alert(plan);
    }
    // eventData.plan_selected = 'Free';
    // window.location.href = "events";
});

      
       
    document.addEventListener('DOMContentLoaded', () => {
        const priceOptions = document.querySelectorAll('.price-option');
        const purchaseButton = document.querySelector('.purchase-button');
        let dotCount = 0; // Start with no dots
        const maxDots = 4; // Maximum dots (after 3 dots, reset)

        function updateButtonText() {
            // Update the button text based on the number of dots
            let dots = '.'.repeat(dotCount); 
            purchaseButton.textContent = `Payment Processing${dots}`;

            // Increment dot count
            dotCount = (dotCount + 1) % (maxDots + 1); // Reset after 3 dots
        }
        priceOptions.forEach(option => {
            console.log("added")
            option.addEventListener('change', () => {
            console.log("change")

                const price = option.getAttribute('data-price');
                const priceId = option.getAttribute('data-price-id');

            console.log(parseFloat(price).toFixed(2))
            // Update the button with the selected price
                purchaseButton.textContent = `Purchase - $${parseFloat(price).toFixed(2)}`;
                purchaseButton.setAttribute('data-price-id', priceId);
                purchaseButton.disabled = false;
            });
        });
        if(purchaseButton){           
        
            purchaseButton.addEventListener('click', () => {
                const selectedPriceId = purchaseButton.getAttribute('data-price-id');
               
                if (selectedPriceId) {
                    const testTimer = setInterval(updateButtonText, 1000);

                    // Use Laravel's route in a Blade directive to inject the base URL
                    const url = `{{ url('payment-start') }}/${selectedPriceId}`;
                    
                    // Open the Stripe Checkout in a new tab
                    window.open(url, '_blank');

                    const checkPaymentStatus = (priceId) => {
                    fetch("{{ route('payment.checkPay') }}", {
                        method: 'POST',
                        
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({ priceId }),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                clearInterval(pollingInterval); // Stop polling
                                clearInterval(testTimer); // Stop polling
                                purchaseButton.textContent = `Purchase - $0.00`;
                                const coinsSpans = document.querySelectorAll('.available-coins');
                                const coinsInputs = document.querySelectorAll('.hidden-coins');
                                
                                // Update the text content for each element
                                coinsSpans.forEach(span => {
                                    span.textContent = data.data;
                                });
                                coinsInputs.forEach(inp => {
                                    inp.value = data.data; 
                                });
                                $(".invite-left_d").text(
                                    "Invites | " + data.data + " Left"
                                );
                                $('#buycreditsmodal').modal('hide');
                                setTimeout(() => {
                                    toastr.success("Payment Successful!");                                    
                                }, 1000);
                                
                            } else if (data.status === 'failed') {
                                purchaseButton.textContent = `Purchase - $0.00`;
                                clearInterval(pollingInterval); // Stop polling
                                clearInterval(testTimer); // Stop polling
                                
                                $('#buycreditsmodal').modal('hide');
                                toastr.error("Payment Failed!");
                            }
                        })
                        .catch(error => console.error('Error:', error));
                };

                // Start polling every 5 seconds
                const pollingInterval = setInterval(() => checkPaymentStatus(selectedPriceId), 5000);
                }
            });
        }
    });
    </script>
<script type="module">
    let currentPage = @json(request()->segment(count(request()->segments())));
    console.log(currentPage); // Output: messages

    if (currentPage !== "messages") {
        (async function () {
            const userId = {{ json_encode($UserId ?? null) }}; // Ensure proper Laravel to JS conversion
            const { initializeApp } = await import("https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js");
            const { getDatabase, ref, get, onValue,update,onDisconnect } = await import("https://www.gstatic.com/firebasejs/10.12.2/firebase-database.js");

            // Fetch Firebase configuration
            const response = await fetch("/firebase_js.json");
            const firebaseConfig = await response.json();

            // Initialize Firebase app
            const app = initializeApp(firebaseConfig);
            const database = getDatabase(app);
            if (userId !== null) {
                try {
                  
                    // Dynamically import Firebase modules
                    

                    // Reference to the user's overview data in Firebase
                    const overviewRef = ref(database, `overview/${userId}`);

                    const isOnlineForDatabase = {
                        userStatus: "Online",
                        userLastSeen: Date.now(),
                    };

                    // Object representing the user's status when offline
                    const isOfflineForDatabase = {
                        userStatus: "Offline",
                        userLastSeen: Date.now(),
                    };

                    // Set up the connection status listener
                    const connectedRef = ref(database, ".info/connected");
                    onValue(connectedRef, async (snapshot) => {
                        if (snapshot.val() === true) {
                            // User is connected
                            await update(overviewRef, isOnlineForDatabase);

                            // Set up the onDisconnect function to set status to offline
                            await onDisconnect(overviewRef).update(isOfflineForDatabase);
                        } else {
                            // User is disconnected (note: this could be triggered before onDisconnect)
                            await update(overviewRef, isOfflineForDatabase);
                        }
                    });

                    // Function to calculate unread count
                    function updateUnreadCountG(snapshot) {
                        let totalUnreadCount = 0;

                        if (snapshot.exists()) {
                            const conversations = snapshot.val();
                            for (let conversationId in conversations) {
                                const conversation = conversations[conversationId];

                                if (conversation.unReadCount && conversation.contactName) {
                                    totalUnreadCount += parseInt(conversation.unReadCount, 10);
                                }
                            }
                        }

                        // Update badge display
                        document.querySelectorAll(".badge, .g-badge").forEach(el => {
                            el.style.display = totalUnreadCount > 0 ? "inline" : "none";
                            el.innerHTML = totalUnreadCount > 0 ? totalUnreadCount : "";
                        });
                    }

                    // Listen for real-time updates
                    onValue(overviewRef, (snapshot) => {
                        updateUnreadCountG(snapshot);
                    });

                    // Initial fetch
                    const snapshot = await get(overviewRef);
                    updateUnreadCountG(snapshot);
                } catch (error) {
                    console.error("Error fetching Firebase data:", error);
                }
            }
            
        async function getUser(userId) {
            const userRef = ref(database, "users/" + userId);
            try {
                const snapshot = await get(userRef);
                return snapshot.val();
            } catch (error) {
                console.error("Error retrieving user data:", error);
                return null;
            }
        }

        async function updateUserStatuses() {
           // console.log("updating status")
            const users = document.querySelectorAll(".guest-users");
            let i = 0
            for (const userElement of users) {
                const userid = userElement.getAttribute("data-userid");
               // console.log({userId})
                if (!userid) continue; // Skip if no userId

                let userData = await getUser(userid);
                let statusClass;
                if(userData?.userStatus?.toLowerCase() === "online"){
                    i = i+1;
                    statusClass = "active-dot";
                } else{
                     statusClass =  "inactive-dot";
                } 
                if(userId==userid){
                    i = i+1;

                    statusClass = "active-dot";
                }
                // Find the span inside the user element and update its class
                let statusSpan = userElement.querySelector("span");
                if (statusSpan) {
                    statusSpan.className = statusClass; // Replace class
                }
            }

            $('.main-right-guests-head').find('p').html(`${i} Active`);
        }

        // Call the function to update statuses
        updateUserStatuses();
        $('.see-all-guest-right-btn').on("click",function(){
            //console.log("updateUser Status")
            setTimeout(function(){
                updateUserStatuses();
            },2500)
          
        })
        })();
    }
</script>


