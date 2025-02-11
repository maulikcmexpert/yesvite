
<div class="home-center-content-head user-name-title">
    <h1>Hi, {{$profileData['firstname']}}!</h1>


    <span class="home-center-content-head-calender-icon profile-calender-view">
      <svg viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M8 2.5V5.5" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M16 2.5V5.5" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M3.5 9.58984H20.5" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M21 9V17.5C21 20.5 19.5 22.5 16 22.5H8C4.5 22.5 3 20.5 3 17.5V9C3 6 4.5 4 8 4H16C19.5 4 21 6 21 9Z" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M15.6976 14.2002H15.7066" stroke="#292D32" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M15.6976 17.2002H15.7066" stroke="#292D32" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M11.9945 14.2002H12.0035" stroke="#292D32" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M11.9945 17.2002H12.0035" stroke="#292D32" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M8.29138 14.2002H8.30036" stroke="#292D32" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M8.29138 17.2002H8.30036" stroke="#292D32" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </span>
</div>
<div class="view-calender-heading home-center-content-head d-none">
    <nav class="breadcrumb-nav" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
            {{-- <li class="breadcrumb-item"><a href="{{route('profile')}}">Profile</a></li> --}}
            <li class="breadcrumb-item active" aria-current="page">Detail  Calender</li>
        </ol>
    </nav>
    <h1 class="d-flex align-items-center gap-3"><i class="fa-solid fa-arrow-left get-back-home-calender" style="font-size:20px;cursor: pointer;"></i> Calender View</h1>

</div>
<div class="home-center-profile-wrp">
    <div class="home-center-profile-head">
      <div class="home-center-profile-img">
        @php
                // if ($profileData['profile'] != NULL || $profileData['profile'] != "") {
                //     // $image = $profileData['profile'];
                //     $userProfile =   $profileData['profile'];
                // } else {
                    $initials = strtoupper($profileData['firstname'][0]) . strtoupper($profileData['lastname'][0]);
                    $fontColor = "fontcolor" . strtoupper($profileData['firstname'][0]);
                    $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                // }
        @endphp
        @if($profileData['profile']!="")
          <img src="{{$profileData['profile']}}" class="lazy" alt="">
        @else
        {!! $userProfile !!}
        <span class="active-dot"></span>
        @endif
      </div>
      <div class="home-center-profile-content">
          <h3>{{$profileData['firstname']}} {{$profileData['lastname']}}</h3>
          <p>Member Since: {{$profileData['created_at']}}</p>
      </div>
    </div>
    <div class="home-center-profile-info">
        <div class="home-center-profile-info-inner">
            <h3>{{$profileData['total_events']}}</h3>
            <h5>Events</h5>
        </div>
        <div class="home-center-profile-info-inner">
            <h3>{{$profileData['total_photos']}}</h3>
            <h5>Photos</h5>
        </div>
        <div class="home-center-profile-info-inner">
            <h3>{{$profileData['comments']}}</h3>
            <h5>Comments</h5>
        </div>
    </div>
    <div class="home-center-profile-status">
        <div class="home-center-profile-status-inner">
            <h5>
              <span><svg viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9.77162 2.82589V1.91589C9.77162 1.67673 9.57328 1.47839 9.33412 1.47839C9.09495 1.47839 8.89662 1.67673 8.89662 1.91589V2.79089H5.10495V1.91589C5.10495 1.67673 4.90662 1.47839 4.66745 1.47839C4.42828 1.47839 4.22995 1.67673 4.22995 1.91589V2.82589C2.65495 2.97173 1.89078 3.91089 1.77412 5.30506C1.76245 5.47423 1.90245 5.61423 2.06578 5.61423H11.9358C12.1049 5.61423 12.245 5.46839 12.2275 5.30506C12.1108 3.91089 11.3466 2.97173 9.77162 2.82589Z" fill="#F73C71"></path>
              <path d="M11.0833 9.5C9.79417 9.5 8.75 10.5442 8.75 11.8333C8.75 12.2708 8.8725 12.685 9.08833 13.035C9.49083 13.7117 10.2317 14.1667 11.0833 14.1667C11.935 14.1667 12.6758 13.7117 13.0783 13.035C13.2942 12.685 13.4167 12.2708 13.4167 11.8333C13.4167 10.5442 12.3725 9.5 11.0833 9.5ZM12.2908 11.5825L11.0483 12.7317C10.9667 12.8075 10.8558 12.8483 10.7508 12.8483C10.64 12.8483 10.5292 12.8075 10.4417 12.72L9.86417 12.1425C9.695 11.9733 9.695 11.6933 9.86417 11.5242C10.0333 11.355 10.3133 11.355 10.4825 11.5242L10.7625 11.8042L11.6958 10.9408C11.8708 10.7775 12.1508 10.7892 12.3142 10.9642C12.4775 11.1392 12.4658 11.4133 12.2908 11.5825Z" fill="#F73C71"></path>
              <path d="M11.6667 6.48853H2.33333C2.0125 6.48853 1.75 6.75103 1.75 7.07186V10.6652C1.75 12.4152 2.625 13.5819 4.66667 13.5819H7.5425C7.945 13.5819 8.225 13.191 8.09667 12.8119C7.98 12.4735 7.88083 12.1002 7.88083 11.8319C7.88083 10.0644 9.32167 8.62353 11.0892 8.62353C11.2583 8.62353 11.4275 8.63519 11.5908 8.66436C11.9408 8.71686 12.2558 8.44269 12.2558 8.09269V7.07769C12.25 6.75103 11.9875 6.48853 11.6667 6.48853ZM5.3725 11.371C5.26167 11.476 5.11 11.5402 4.95833 11.5402C4.80667 11.5402 4.655 11.476 4.54417 11.371C4.43917 11.2602 4.375 11.1085 4.375 10.9569C4.375 10.8052 4.43917 10.6535 4.54417 10.5427C4.6025 10.4902 4.66083 10.4494 4.73667 10.4202C4.9525 10.3269 5.20917 10.3794 5.3725 10.5427C5.4775 10.6535 5.54167 10.8052 5.54167 10.9569C5.54167 11.1085 5.4775 11.2602 5.3725 11.371ZM5.3725 9.32936C5.34333 9.35269 5.31417 9.37603 5.285 9.39936C5.25 9.42269 5.215 9.44019 5.18 9.45186C5.145 9.46936 5.11 9.48102 5.075 9.48686C5.03417 9.49269 4.99333 9.49853 4.95833 9.49853C4.80667 9.49853 4.655 9.43436 4.54417 9.32936C4.43917 9.21853 4.375 9.06686 4.375 8.91519C4.375 8.76353 4.43917 8.61186 4.54417 8.50103C4.67833 8.36686 4.8825 8.30269 5.075 8.34353C5.11 8.34936 5.145 8.36103 5.18 8.37853C5.215 8.39019 5.25 8.40769 5.285 8.43103C5.31417 8.45436 5.34333 8.47769 5.3725 8.50103C5.4775 8.61186 5.54167 8.76353 5.54167 8.91519C5.54167 9.06686 5.4775 9.21853 5.3725 9.32936ZM7.41417 9.32936C7.30333 9.43436 7.15167 9.49853 7 9.49853C6.84833 9.49853 6.69667 9.43436 6.58583 9.32936C6.48083 9.21853 6.41667 9.06686 6.41667 8.91519C6.41667 8.76353 6.48083 8.61186 6.58583 8.50103C6.8075 8.28519 7.19833 8.28519 7.41417 8.50103C7.51917 8.61186 7.58333 8.76353 7.58333 8.91519C7.58333 9.06686 7.51917 9.21853 7.41417 9.32936Z" fill="#F73C71"></path>
            </svg></span>
            Pending your RSVP
          </h5>
          <h3>{{$profileData['pending_rsvp_count']}}</h3>
        </div>
        <div class="home-center-profile-status-inner">
            <h5>
              <span><svg viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.4" d="M11.2021 12.23C10.8812 13.0525 10.0996 13.5833 9.21875 13.5833H5.77708C4.89041 13.5833 4.11458 13.0525 3.79375 12.23C3.47291 11.4017 3.69458 10.4858 4.34791 9.89083L6.71041 7.75H8.29125L10.6479 9.89083C11.3012 10.4858 11.5171 11.4017 11.2021 12.23Z" fill="#F73C71"></path>
                <path d="M8.56297 11.3313H6.43964C6.21797 11.3313 6.04297 11.1505 6.04297 10.9346C6.04297 10.713 6.2238 10.538 6.43964 10.538H8.56297C8.78464 10.538 8.95964 10.7188 8.95964 10.9346C8.95964 11.1505 8.7788 11.3313 8.56297 11.3313Z" fill="#F73C71"></path>
                <path d="M11.2032 3.26996C10.8824 2.44746 10.1007 1.91663 9.21991 1.91663H5.77824C4.89741 1.91663 4.11574 2.44746 3.79491 3.26996C3.47991 4.09829 3.69574 5.01413 4.35491 5.60913L6.71158 7.74996H8.29241L10.6491 5.60913C11.3024 5.01413 11.5182 4.09829 11.2032 3.26996ZM8.56074 4.96746H6.43741C6.21574 4.96746 6.04074 4.78663 6.04074 4.57079C6.04074 4.35496 6.22158 4.17413 6.43741 4.17413H8.56074C8.78241 4.17413 8.95741 4.35496 8.95741 4.57079C8.95741 4.78663 8.77658 4.96746 8.56074 4.96746Z" fill="#F73C71"></path>
              </svg></span>
            Upcoming Events
          </h5>
          <h3>{{$profileData['total_upcoming_events']}}</h3>
        </div>
    </div>

</div>
