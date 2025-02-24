@if(!session('advertisement_closed'))
<div class="google-add">
    @php
        $getSocialLink = getSocialLink();
    @endphp
    {{-- <p>Full web functionality will be available in Late Q1 {{date('Y')}} - for full functionality use our apps</p> --}}
    <p>Our website is now live, please report any bugs to <a href="mailto:support@yesvite.com">support@yesvite.com</a></p>

    <div class="app-store ms-auto d-flex gap-2">
        <a href="{{isset($getSocialLink->playstore_link) && $getSocialLink->playstore_link != null ? $getSocialLink->playstore_link : "#"}}" class="google-app"  target="_blank">
            <img src="{{asset('assets/front/image/google-app.png')}}" alt="google-app">
        </a>
        <a href="{{isset($getSocialLink->appstore_link) && $getSocialLink->appstore_link !=null ? $getSocialLink->appstore_link : "#"}}" class="mobile-app"  target="_blank">
            <img src="{{asset('assets/front/image/mobile-app.png')}}" alt="mobile-app">
        </a>
    </div>
    {{-- <a href="{{route('get_all_notification')}}">test</a> --}}
    <a href="#" class="close_advertise">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1.16748 1.1665L12.8334 12.8324" stroke="#E2E8F0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M1.16664 12.8324L12.8325 1.1665" stroke="#E2E8F0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </a>
</div>
@endif
