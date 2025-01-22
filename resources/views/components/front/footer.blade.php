<!--Buy-Credits-Modal -->
<div class="modal fade cmn-modal buycreditsmodal" id="buycreditsmodal" tabindex="-1" aria-labelledby="aboutsuccessLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div><h4 class="modal-title" id="aboutsuccessLabel">Buy Credits</h4></div>
                    <div class="totle_credit_buy_wrp">
                        <img src="{{asset('assets')}}/coin.svg" alt="">
                        <p>8</p>
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

                                      <li>
                                          <div class="bulk-credit-options-listing-left">
                                              <h3><span><img src="{{asset('assets')}}/coin.svg" alt=""></span> 15 Credits</h3>
                                              <p>$1.35 per credit</p>
                                          </div>
                                          <div class="bulk-credit-options-listing-right">
                                              <h4>$22.50</h4>
                                              <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                              </div>
                                          </div>
                                      </li>
                                     
                                      <div class="best-deal-wrp">
                                        <div class="best-deal-title">
                                          <h5>80% Saving over the 15 pack</h5>
                                          <h4>BEST DEAL!</h4>
                                        </div>
                                        <li>
                                            <div class="bulk-credit-options-listing-left">
                                                <h3><span><img src="{{asset('assets')}}/coin.svg" alt=""></span> 15 Credits</h3>
                                                <p>$1.35 per credit</p>
                                            </div>
                                            <div class="bulk-credit-options-listing-right">
                                                <h4>$22.50</h4>
                                                <div class="form-check">
                                                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                                </div>
                                            </div>
                                        </li>
                                      </div>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Purchase - $22.50</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/front/js/jquery.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" stylesheet.crossOrigin = "anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fontfaceobserver/2.1.0/fontfaceobserver.standalone.js"></script>

{{-- <script src="{{ asset('assets/event/js/fontLoder.js') }}"></script> --}}
<!-- owl-carousel-js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<!-- custom-js -->
<script src="{{ asset('assets/admin/js/jquery-validate.js') }}"></script>

<script src="{{ asset('assets/admin/js/jquery-validate-additional.js') }}"></script>




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
<script src="{{ asset('assets/front/js/contact_us.js') }}"></script>


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
<script src="{{ asset('assets/front/new_js/slider.js') }}"></script>
<script src="{{ asset('assets/front/new_js/scritp.js')}}"></script>
<script src="{{ asset('assets/front/new_js/calender.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

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
    window.location.href = "events";
});

        const stripe = Stripe('{{ config('services.stripe.public') }}'); // Replace with your public key

        document.getElementById('purchase-form').addEventListener('submit', async (event) => {
            event.preventDefault();

            const priceId = document.getElementById('credits').value;

            try {
                const response = await fetch('{{ route('process.payment') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ priceId }),
                });

                const data = await response.json();

                if (data.url) {
                    // Open Stripe Checkout in a new tab
                    const stripeWindow = window.open(data.url, '_blank');

                    // Poll the Stripe window to detect if it's closed
                    const interval = setInterval(() => {
                        if (stripeWindow.closed) {
                            clearInterval(interval);
                            // Show success modal when the Stripe window is closed
                            document.getElementById('success-modal').style.display = 'block';
                        }
                    }, 1000);
                } else if (data.error) {
                    alert('Error: ' + data.error);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });

        // Close modal logic
        document.getElementById('close-modal').addEventListener('click', () => {
            document.getElementById('success-modal').style.display = 'none';
        });
    </script>