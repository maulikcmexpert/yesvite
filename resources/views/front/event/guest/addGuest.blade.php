

@foreach ( $data as $guest_user)
@php
    dd($guest_user['is_duplicate']);
@endphp
@if($guest_user['userdata']['prefer_by']=="email")
<div class="users-data invited_user" data-id={{$guest_user['userdata']['id']}} id="user-{{$guest_user['userdata']['id']}}">
    {{-- <input type="hidden" class="duplicate" value="{{$is_duplicate}}"/> --}}
    <div class="d-flex align-items-start">
        <div class="contact-img">
            @if ($guest_user['userdata']['profile'] != '')
            <img src="{{ asset('storage/profile/' . $guest_user['userdata']['profile']) }}" alt="user-img">
            @else
            @php
             $firstInitial = !empty($guest_user['userdata']['firstname']) ? strtoupper($guest_user['userdata']['firstname'][0]) : '';
             $lastInitial = !empty($guest_user['userdata']['lastname']) ? strtoupper($guest_user['userdata']['lastname'][0]) : '';
             $initials = $firstInitial . $lastInitial;
             $fontColor = 'fontcolor' . $firstInitial;
             @endphp
            <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
            @endif        </div>
            <div class="text-start">
                <h5>{{$guest_user['userdata']['firstname']}} {{$guest_user['userdata']['lastname']}}</h5>
            <div>
                <a href="mailto:silvia@gmail.com"><svg class="me-1" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.91602 11.9582H4.08268C2.33268 11.9582 1.16602 11.0832 1.16602 9.0415V4.95817C1.16602 2.9165 2.33268 2.0415 4.08268 2.0415H9.91602C11.666 2.0415 12.8327 2.9165 12.8327 4.95817V9.0415C12.8327 11.0832 11.666 11.9582 9.91602 11.9582Z" stroke="black" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M9.91732 5.25L8.09148 6.70833C7.49065 7.18667 6.50482 7.18667 5.90398 6.70833L4.08398 5.25" stroke="black" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    {{$guest_user['userdata']['invited_by']}}</a>
                </div>
            </div>
        </div>
        <div>
            <a href="#" id="delete_invited_user" data-id="user-{{$guest_user['userdata']['id']}}" data-userid="{{$guest_user['userdata']['id']}}">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.5584 4.35866C16.2167 4.22533 14.875 4.12533 13.525 4.05033V4.04199L13.3417 2.95866C13.2167 2.19199 13.0334 1.04199 11.0834 1.04199H8.90005C6.95838 1.04199 6.77505 2.14199 6.64172 2.95033L6.46672 4.01699C5.69172 4.06699 4.91672 4.11699 4.14172 4.19199L2.44172 4.35866C2.09172 4.39199 1.84172 4.70033 1.87505 5.04199C1.90838 5.38366 2.20838 5.63366 2.55838 5.60033L4.25838 5.43366C8.62505 5.00033 13.0251 5.16699 17.4417 5.60866C17.4667 5.60866 17.4834 5.60866 17.5084 5.60866C17.8251 5.60866 18.1 5.36699 18.1334 5.04199C18.1584 4.70033 17.9084 4.39199 17.5584 4.35866Z" fill="#F73C71"></path>
                <path d="M16.025 6.78301C15.825 6.57467 15.55 6.45801 15.2666 6.45801H4.73329C4.44995 6.45801 4.16662 6.57467 3.97495 6.78301C3.78329 6.99134 3.67495 7.27467 3.69162 7.56634L4.20829 16.1163C4.29995 17.383 4.41662 18.9663 7.32495 18.9663H12.675C15.5833 18.9663 15.7 17.3913 15.7916 16.1163L16.3083 7.57467C16.325 7.27467 16.2166 6.99134 16.025 6.78301ZM11.3833 14.7913H8.60829C8.26662 14.7913 7.98329 14.508 7.98329 14.1663C7.98329 13.8247 8.26662 13.5413 8.60829 13.5413H11.3833C11.725 13.5413 12.0083 13.8247 12.0083 14.1663C12.0083 14.508 11.725 14.7913 11.3833 14.7913ZM12.0833 11.458H7.91662C7.57495 11.458 7.29162 11.1747 7.29162 10.833C7.29162 10.4913 7.57495 10.208 7.91662 10.208H12.0833C12.425 10.208 12.7083 10.4913 12.7083 10.833C12.7083 11.1747 12.425 11.458 12.0833 11.458Z" fill="#F73C71"></path>
            </svg>
        </a>
    </div>
</div>
@elseif ($guest_user['userdata']['prefer_by']=="phone")
<div class="users-data invited_user" data-id={{$guest_user['userdata']['id']}} id="user_tel-{{$guest_user['userdata']['id']}}">
    {{-- <input type="hidden" class="duplicate" value="{{$is_duplicate}}"/> --}}
    <div class="d-flex align-items-start">
        <div class="contact-img">
            @if ($guest_user['userdata']['profile'] != '')
            <img src="{{ asset('storage/profile/' . $guest_user['userdata']['profile']) }}" alt="user-img">
            @else
            @php
             $firstInitial = !empty($guest_user['userdata']['firstname']) ? strtoupper($guest_user['userdata']['firstname'][0]) : '';
             $lastInitial = !empty($guest_user['userdata']['lastname']) ? strtoupper($guest_user['userdata']['lastname'][0]) : '';
             $initials = $firstInitial . $lastInitial;
             $fontColor = 'fontcolor' . $firstInitial;
             @endphp
            <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
            @endif               </div>
            <div class="text-start">
                <h5>{{$guest_user['userdata']['firstname']}} {{$guest_user['userdata']['lastname']}}</h5>
                <div>
                    
                    <a href="tel">
                        <svg width="14" class="me-1" height="14"
                        viewBox="0 0 14 14" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.1805 13.2712C9.5213 13.2712 8.82714 13.1137 8.10964 12.8103C7.40964 12.5128 6.7038 12.1045 6.01547 11.6087C5.33297 11.107 4.6738 10.547 4.04964 9.93449C3.4313 9.31033 2.8713 8.65116 2.37547 7.97449C1.8738 7.27449 1.4713 6.57449 1.18547 5.89783C0.882135 5.17449 0.730469 4.47449 0.730469 3.81533C0.730469 3.36033 0.812135 2.92866 0.969635 2.52616C1.13297 2.11199 1.39547 1.72699 1.7513 1.39449C2.20047 0.951159 2.7138 0.729492 3.26214 0.729492C3.48964 0.729492 3.72297 0.781992 3.9213 0.875326C4.1488 0.980326 4.3413 1.13783 4.4813 1.34783L5.83464 3.25533C5.95714 3.42449 6.05047 3.58783 6.11464 3.75116C6.19047 3.92616 6.2313 4.10116 6.2313 4.27033C6.2313 4.49199 6.16714 4.70783 6.04464 4.91199C5.95714 5.06949 5.82297 5.23866 5.6538 5.40783L5.25714 5.82199C5.26297 5.83949 5.2688 5.85116 5.27464 5.86283C5.34464 5.98533 5.48464 6.19533 5.75297 6.51033C6.0388 6.83699 6.30714 7.13449 6.57547 7.40866C6.91964 7.74699 7.20547 8.01533 7.4738 8.23699C7.8063 8.51699 8.02214 8.65699 8.15047 8.72116L8.1388 8.75033L8.56464 8.33033C8.74547 8.14949 8.92047 8.01533 9.08964 7.92783C9.41047 7.72949 9.8188 7.69449 10.2271 7.86366C10.3788 7.92783 10.5421 8.01533 10.7171 8.13783L12.6538 9.51449C12.8696 9.66033 13.0271 9.84699 13.1205 10.0687C13.208 10.2903 13.2488 10.4945 13.2488 10.6987C13.2488 10.9787 13.1846 11.2587 13.0621 11.5212C12.9396 11.7837 12.788 12.0112 12.5955 12.2212C12.263 12.5887 11.9013 12.8512 11.4813 13.0203C11.0788 13.1837 10.6413 13.2712 10.1805 13.2712ZM3.26214 1.60449C2.9413 1.60449 2.6438 1.74449 2.35797 2.02449C2.08964 2.27533 1.90297 2.54949 1.7863 2.84699C1.6638 3.15033 1.60547 3.47116 1.60547 3.81533C1.60547 4.35783 1.7338 4.94699 1.99047 5.55366C2.25297 6.17199 2.62047 6.81366 3.08714 7.45533C3.5538 8.09699 4.08464 8.72116 4.66797 9.31033C5.2513 9.88783 5.8813 10.4245 6.5288 10.897C7.1588 11.3578 7.8063 11.7312 8.44797 11.9995C9.44547 12.4253 10.3788 12.5245 11.1488 12.2037C11.4463 12.0812 11.7088 11.8945 11.948 11.6262C12.0821 11.4803 12.1871 11.3228 12.2746 11.1362C12.3446 10.9903 12.3796 10.8387 12.3796 10.687C12.3796 10.5937 12.3621 10.5003 12.3155 10.3953C12.298 10.3603 12.263 10.2962 12.1521 10.2203L10.2155 8.84366C10.0988 8.76199 9.9938 8.70366 9.89464 8.66283C9.7663 8.61033 9.7138 8.55783 9.51547 8.68033C9.3988 8.73866 9.2938 8.82616 9.17714 8.94283L8.7338 9.38033C8.5063 9.60199 8.1563 9.65449 7.88797 9.55533L7.73047 9.48533C7.4913 9.35699 7.2113 9.15866 6.90214 8.89616C6.62214 8.65699 6.3188 8.37699 5.9513 8.01533C5.66547 7.72366 5.37964 7.41449 5.08214 7.07033C4.80797 6.74949 4.60964 6.47533 4.48714 6.24783L4.41714 6.07283C4.38214 5.93866 4.37047 5.86283 4.37047 5.78116C4.37047 5.57116 4.4463 5.38449 4.59214 5.23866L5.02964 4.78366C5.1463 4.66699 5.2338 4.55616 5.29214 4.45699C5.3388 4.38116 5.3563 4.31699 5.3563 4.25866C5.3563 4.21199 5.3388 4.14199 5.30964 4.07199C5.2688 3.97866 5.20464 3.87366 5.12297 3.76283L3.76964 1.84949C3.7113 1.76783 3.6413 1.70949 3.5538 1.66866C3.46047 1.62783 3.3613 1.60449 3.26214 1.60449ZM8.1388 8.75616L8.04547 9.15283L8.20297 8.74449C8.1738 8.73866 8.15047 8.74449 8.1388 8.75616Z"fill="black" />
                    </svg>
                    {{$guest_user['userdata']['invited_by']}}</a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="#" id="delete_invited_user_tel" data-id="user_tel-{{$guest_user['userdata']['id']}}" data-userid="{{$guest_user['userdata']['id']}}">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.5584 4.35866C16.2167 4.22533 14.875 4.12533 13.525 4.05033V4.04199L13.3417 2.95866C13.2167 2.19199 13.0334 1.04199 11.0834 1.04199H8.90005C6.95838 1.04199 6.77505 2.14199 6.64172 2.95033L6.46672 4.01699C5.69172 4.06699 4.91672 4.11699 4.14172 4.19199L2.44172 4.35866C2.09172 4.39199 1.84172 4.70033 1.87505 5.04199C1.90838 5.38366 2.20838 5.63366 2.55838 5.60033L4.25838 5.43366C8.62505 5.00033 13.0251 5.16699 17.4417 5.60866C17.4667 5.60866 17.4834 5.60866 17.5084 5.60866C17.8251 5.60866 18.1 5.36699 18.1334 5.04199C18.1584 4.70033 17.9084 4.39199 17.5584 4.35866Z" fill="#F73C71"></path>
                <path d="M16.025 6.78301C15.825 6.57467 15.55 6.45801 15.2666 6.45801H4.73329C4.44995 6.45801 4.16662 6.57467 3.97495 6.78301C3.78329 6.99134 3.67495 7.27467 3.69162 7.56634L4.20829 16.1163C4.29995 17.383 4.41662 18.9663 7.32495 18.9663H12.675C15.5833 18.9663 15.7 17.3913 15.7916 16.1163L16.3083 7.57467C16.325 7.27467 16.2166 6.99134 16.025 6.78301ZM11.3833 14.7913H8.60829C8.26662 14.7913 7.98329 14.508 7.98329 14.1663C7.98329 13.8247 8.26662 13.5413 8.60829 13.5413H11.3833C11.725 13.5413 12.0083 13.8247 12.0083 14.1663C12.0083 14.508 11.725 14.7913 11.3833 14.7913ZM12.0833 11.458H7.91662C7.57495 11.458 7.29162 11.1747 7.29162 10.833C7.29162 10.4913 7.57495 10.208 7.91662 10.208H12.0833C12.425 10.208 12.7083 10.4913 12.7083 10.833C12.7083 11.1747 12.425 11.458 12.0833 11.458Z" fill="#F73C71"></path>
            </svg>
        </a>
    </div>
</div>
@endif
@endforeach