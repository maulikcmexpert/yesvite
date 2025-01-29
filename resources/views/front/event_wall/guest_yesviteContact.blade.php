<div class="tab-pane fade show active" id="home" role="tabpanel"
   aria-labelledby="home-tab">
   <div class="guest-users-wrp selected-contacts-list"  id="selected-contacts">
      {{-- <a href="#" class="guest-user d-block">
         <div class="guest-user-img guest-total">
            <span class="number" id="total-selected">0</span>
            <span class="content">Total</span>
         </div>
         <h6>Sell all</h6>
      </a> --}}
   </div>
   <div class="position-relative">
      <input type="search" placeholder="Search name" class="form-control search-yesvite">
      <span class="input-search">
         <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
               d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
               stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
               stroke-linejoin="round"></path>
            <path d="M22 22L20 20" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
               stroke-linejoin="round"></path>
         </svg>
      </span>
   </div>
   <div class="guest-user-list-wrp invite-contact-wrp yesvite_contact">
      @foreach ($yesviteUsers as $contact)

      <div class="invite-contact yes-contact">
         <a href="#" class="invite-img">
            @if (!empty($contact->profile))
            <img src="{{ $contact->profile }}" alt="invite-img">
            @else
                @php
                $firstInitial = isset($contact->firstname[0]) ? strtoupper($contact->firstname[0]) : '';
                $secondInitial = isset($contact->lastname[0]) ? strtoupper($contact->lastname[0]) : '';
                $initials = strtoupper($firstInitial) . strtoupper($secondInitial);
                $fontColor = 'fontcolor' . strtoupper($firstInitial);
                @endphp
            <h5 class="{{ $fontColor }}">
               {{ $initials }}
            </h5>
            @endif
         </a>
         <div class="w-100">
            <div class="d-flex justify-content-between align-items-center">
               <a href="#" class="invite-user-name yesvite-search" data-bs-toggle="modal" data-bs-target="#editguest" data-search = "{{ $contact->firstname }} {{ $contact->lastname }}">
               {{ $contact->firstname }} {{ $contact->lastname }}</a>
            </div>
            <div class="d-flex align-items-center mt-1">
               <div class="invite-mail-data faild-content">
                  <div class="d-flex align-items-center">
                     <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                           d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                           stroke="black" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                           d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                           stroke="black" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                     </svg>
                     @if (!empty($contact->email))
                     <h6>{{ $contact->email }}</h6>
                     @endif
                  </div>
               </div>
               <div class="ms-auto">
                  <input class="form-check-input failed-checkout contact-checkbox email-checkbox" type="checkbox"
                     data-id="{{ $contact->id }}" data-name="{{ $contact->firstname }}"
                     data-last="{{ $contact->lastname }}" data-email="{{ $contact->email }}"
                     data-phone="{{ $contact->phone_number }}" data-type="email">
               </div>
            </div>
            @if (!empty($contact->phone_number))
            <div class="invite-call-data mt-1">
               <div class="d-flex align-items-center">
                  <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                     <path
                        d="M12.8171 10.6918C12.8171 10.9018 12.7705 11.1177 12.6713 11.3277C12.5721 11.5377 12.4438 11.736 12.2746 11.9227C11.9888 12.2377 11.6738 12.4652 11.318 12.611C10.968 12.7568 10.5888 12.8327 10.1805 12.8327C9.58547 12.8327 8.94963 12.6927 8.2788 12.4068C7.60797 12.121 6.93714 11.736 6.27214 11.2518C5.6013 10.7618 4.96547 10.2193 4.3588 9.61852C3.75797 9.01185 3.21547 8.37602 2.7313 7.71102C2.25297 7.04602 1.86797 6.38102 1.58797 5.72185C1.30797 5.05685 1.16797 4.42102 1.16797 3.81435C1.16797 3.41768 1.23797 3.03852 1.37797 2.68852C1.51797 2.33268 1.73964 2.00602 2.0488 1.71435C2.42214 1.34685 2.83047 1.16602 3.26214 1.16602C3.42547 1.16602 3.5888 1.20102 3.73464 1.27102C3.8863 1.34102 4.02047 1.44602 4.12547 1.59768L5.4788 3.50518C5.5838 3.65102 5.65964 3.78518 5.71214 3.91352C5.76464 4.03602 5.7938 4.15852 5.7938 4.26935C5.7938 4.40935 5.75297 4.54935 5.6713 4.68352C5.59547 4.81768 5.48464 4.95768 5.34464 5.09768L4.9013 5.55852C4.83714 5.62268 4.80797 5.69852 4.80797 5.79185C4.80797 5.83852 4.8138 5.87935 4.82547 5.92602C4.84297 5.97268 4.86047 6.00768 4.87214 6.04268C4.97714 6.23518 5.15797 6.48602 5.41464 6.78935C5.67714 7.09268 5.95713 7.40185 6.26047 7.71102C6.57547 8.02018 6.8788 8.30602 7.18797 8.56852C7.4913 8.82518 7.74213 9.00018 7.94047 9.10518C7.96963 9.11685 8.00464 9.13435 8.04547 9.15185C8.09214 9.16935 8.1388 9.17518 8.1913 9.17518C8.29047 9.17518 8.3663 9.14018 8.43047 9.07602L8.8738 8.63852C9.01964 8.49268 9.15964 8.38185 9.2938 8.31185C9.42797 8.23018 9.56213 8.18935 9.70797 8.18935C9.8188 8.18935 9.93547 8.21268 10.0638 8.26518C10.1921 8.31768 10.3263 8.39352 10.4721 8.49268L12.403 9.86352C12.5546 9.96852 12.6596 10.091 12.7238 10.2368C12.7821 10.3827 12.8171 10.5285 12.8171 10.6918Z"
                        stroke="#0F172A" stroke-miterlimit="10" />
                  </svg>
                  <h6>{{ $contact->phone_number }}</h6>
               </div>
               <input class="form-check-input failed-checkout contact-checkbox phone-checkbox" type="checkbox"
                  data-id="{{ $contact->id }}" data-name="{{ $contact->firstname }}"
                  data-last="{{ $contact->lastname }}" data-email="{{ $contact->email }}"
                  data-phone="{{ $contact->phone_number }}" data-type="phone">
            </div>
            @endif
         </div>
      </div>
      @endforeach
   </div>
</div>
<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
   <div class="guest-users-wrp selected-phone-list">
      <a href="#" class="guest-user d-block">
         <div class="guest-user-img guest-total">
            <span class="number">10</span>
            <span class="content">Total</span>
         </div>
         <h6>Sell all</h6>
      </a>
   </div>
   <div class="position-relative">
      <input type="search" placeholder="Search name" class="form-control search-phone">
      <span class="input-search">
         <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
               d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
               stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
               stroke-linejoin="round"></path>
            <path d="M22 22L20 20" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
               stroke-linejoin="round"></path>
         </svg>
      </span>
   </div>
   <div class="guest-user-list-wrp invite-contact-wrp phone_contact">
      @foreach($phone_contact as $contact)

      <div class="invite-contact phone-contact">
         <a href="#" class="invite-img">
            @if (!empty($contact->photo))
            <img src="{{$contact->photo}}"
               alt="invite-img">
            @else
            @php

                $firstInitial = isset($contact->firstName[0]) ? strtoupper($contact->firstName[0]) : '';
                $secondInitial = isset($contact->lastName[0]) ? strtoupper($contact->lastName[0]) : '';
                $initials = strtoupper($firstInitial) . strtoupper($secondInitial);
                $fontColor = 'fontcolor' . strtoupper($firstInitial);
            @endphp
            <h5 class="{{ $fontColor }}">
               {{ $initials }}
            </h5>
            @endif
         </a>
         <div class="w-100">
            <div class="d-flex justify-content-between align-items-center">
               <a href="#" class="invite-user-name phone-search" data-bs-toggle="modal"
                  data-bs-target="#editguest" data-search="{{ $contact->firstName }}{{ $contact->lastName }}"> {{ $contact->firstName }} {{ $contact->lastName }}</a>
            </div>
            @if (!empty( $contact->email ))
            <div class="d-flex align-items-center mt-1">
               <div class="invite-mail-data faild-content">
                  <div class="d-flex align-items-center">
                     <svg width="14" height="14" viewBox="0 0 14 14"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                           d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                           stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                           stroke-linejoin="round" />
                        <path
                           d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                           stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                           stroke-linejoin="round" />
                     </svg>
                     <h6>{{  $contact->email  }}</h6>
                  </div>
               </div>
               <div class="ms-auto">
                  <input class="form-check-input failed-checkout phoneContact-checkbox" type="checkbox"
                     data-id="{{ $contact->id }}"  data-name="{{  $contact->firstName  }}"
                     data-last="{{ $contact->lastName }}"
                     data-email="{{ $contact->email }}"
                     data-phone="{{ $contact->phoneWithCode }}"  data-type="email">
               </div>
            </div>
            @endif
            @if (!empty($contact->phoneWithCode))
            <div class="invite-call-data mt-1">
               <div class="d-flex align-items-center">
                  <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                     <path
                        d="M12.8171 10.6918C12.8171 10.9018 12.7705 11.1177 12.6713 11.3277C12.5721 11.5377 12.4438 11.736 12.2746 11.9227C11.9888 12.2377 11.6738 12.4652 11.318 12.611C10.968 12.7568 10.5888 12.8327 10.1805 12.8327C9.58547 12.8327 8.94963 12.6927 8.2788 12.4068C7.60797 12.121 6.93714 11.736 6.27214 11.2518C5.6013 10.7618 4.96547 10.2193 4.3588 9.61852C3.75797 9.01185 3.21547 8.37602 2.7313 7.71102C2.25297 7.04602 1.86797 6.38102 1.58797 5.72185C1.30797 5.05685 1.16797 4.42102 1.16797 3.81435C1.16797 3.41768 1.23797 3.03852 1.37797 2.68852C1.51797 2.33268 1.73964 2.00602 2.0488 1.71435C2.42214 1.34685 2.83047 1.16602 3.26214 1.16602C3.42547 1.16602 3.5888 1.20102 3.73464 1.27102C3.8863 1.34102 4.02047 1.44602 4.12547 1.59768L5.4788 3.50518C5.5838 3.65102 5.65964 3.78518 5.71214 3.91352C5.76464 4.03602 5.7938 4.15852 5.7938 4.26935C5.7938 4.40935 5.75297 4.54935 5.6713 4.68352C5.59547 4.81768 5.48464 4.95768 5.34464 5.09768L4.9013 5.55852C4.83714 5.62268 4.80797 5.69852 4.80797 5.79185C4.80797 5.83852 4.8138 5.87935 4.82547 5.92602C4.84297 5.97268 4.86047 6.00768 4.87214 6.04268C4.97714 6.23518 5.15797 6.48602 5.41464 6.78935C5.67714 7.09268 5.95713 7.40185 6.26047 7.71102C6.57547 8.02018 6.8788 8.30602 7.18797 8.56852C7.4913 8.82518 7.74213 9.00018 7.94047 9.10518C7.96963 9.11685 8.00464 9.13435 8.04547 9.15185C8.09214 9.16935 8.1388 9.17518 8.1913 9.17518C8.29047 9.17518 8.3663 9.14018 8.43047 9.07602L8.8738 8.63852C9.01964 8.49268 9.15964 8.38185 9.2938 8.31185C9.42797 8.23018 9.56213 8.18935 9.70797 8.18935C9.8188 8.18935 9.93547 8.21268 10.0638 8.26518C10.1921 8.31768 10.3263 8.39352 10.4721 8.49268L12.403 9.86352C12.5546 9.96852 12.6596 10.091 12.7238 10.2368C12.7821 10.3827 12.8171 10.5285 12.8171 10.6918Z"
                        stroke="#0F172A" stroke-miterlimit="10" />
                  </svg>
                  <h6>{{ $contact->phoneWithCode }}</h6>
               </div>
               <input class="form-check-input failed-checkout phoneContact-checkbox" type="checkbox"
                  data-id="{{ $contact->id }}"  data-name="{{   $contact->firstName  }}"
                  data-last="{{ $contact->lastName}}"
                  data-email="{{ $contact->email }}"
                  data-phone="{{ $contact->phoneWithCode }}"  data-type="phone">
            </div>
            @endif
         </div>
      </div>
      @endforeach
   </div>
</div>
