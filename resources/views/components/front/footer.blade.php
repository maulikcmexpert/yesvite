<!--Buy-Credits-Modal -->
@if(isset($prices)&&count($prices)>0)
    

<button type="button" data-bs-toggle="modal" data-bs-target="#buycreditsmodal">buycreditsmodal</button>
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

<script src="{{ asset('assets/front/js/design.js') }}"></script>

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
     let page = "{{ isset($title) && $title != '' ? addslashes($title) : '' }}";
    
    if(page!=undefined && page!="Messages"){    
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
        import {
            getDatabase,
            ref,
            get,
            onValue,
        } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-database.js";

        (async function() {
            const userId = {{$UserId}}; // Make sure this is correctly injected from Laravel

            if (userId != undefined && userId !=null) {
                try {
                    // Fetch Firebase configuration from firebase_js.json
                    const response = await fetch("/firebase_js.json");
                    const firebaseConfig = await response.json();

                    // Initialize Firebase app
                    const app = initializeApp(firebaseConfig);
                    const database = getDatabase(app);

                    // Reference to the user's overview data in Firebase
                    const overviewRef = ref(database, `overview/${userId}`);

                    // Function to calculate unread count
                    function updateUnreadCountG(snapshot) {
                        let totalUnreadCount = 0;

                        // Check if data exists
                        if (snapshot.exists()) {
                            const conversations = snapshot.val();

                            for (let conversationId in conversations) {
                                const conversation = conversations[conversationId];

                                if (conversation.unReadCount && conversation.contactName) {
                                    totalUnreadCount += parseInt(conversation.unReadCount, 10);
                                }
                            }
                        }

                        if (parseInt(totalUnreadCount) > 0) {
                            $(".badge").show();
                            $(".g-badge").show();
                            $(".g-badge").html(parseInt(totalUnreadCount));
                            $(".badge").html(parseInt(totalUnreadCount));
                        } else {
                            $(".g-badge").hide();
                            $(".badge").hide();
                            $(".g-badge").html("");
                            $(".badge").html("");
                        }
                    }

                    // Listen for real-time changes in the overview data
                    onValue(overviewRef, (snapshot) => {
                        updateUnreadCountG(snapshot);
                    });

                    // Initial fetch of the data (optional)
                    const snapshot = await get(overviewRef);
                    updateUnreadCountG(snapshot);

                } catch (error) {
                    console.error("Error fetching data from Firebase:", error);
                }
            }
        })();
    }
</script>
