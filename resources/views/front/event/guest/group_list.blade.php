@foreach ($groups as $group)
<div class="group-card added_group{{ $group->id }} listgroups view_members" data-id="{{ $group->id }}">
    <div class="view_members" data-id="{{ $group->id }}">
        <h4>{{ $group->name }}</h4>
        <p>{{ $group->group_members_count }} Guests</p>
    </div>
    <span class="ms-auto me-3">
        {{-- <svg width="16" id="delete_group" data-id="{{ $group->id }}" height="17"
            viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M14 4.48665C11.78 4.26665 9.54667 4.15332 7.32 4.15332C6 4.15332 4.68 4.21999 3.36 4.35332L2 4.48665"
                stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round">
            </path>
            <path
                d="M5.66699 3.81301L5.81366 2.93967C5.92033 2.30634 6.00033 1.83301 7.12699 1.83301H8.87366C10.0003 1.83301 10.087 2.33301 10.187 2.94634L10.3337 3.81301"
                stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round">
            </path>
            <path
                d="M12.5669 6.59375L12.1336 13.3071C12.0603 14.3537 12.0003 15.1671 10.1403 15.1671H5.86026C4.00026 15.1671 3.94026 14.3537 3.86693 13.3071L3.43359 6.59375"
                stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round">
            </path>
            <path d="M6.88672 11.5H9.10672" stroke="#94A3B8" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M6.33301 8.83301H9.66634" stroke="#94A3B8" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round"></path>
        </svg> --}}
    </span>
    <span>
        <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M5.94043 13.7797L10.2871 9.43306C10.8004 8.91973 10.8004 8.07973 10.2871 7.56639L5.94043 3.21973"
                stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10"
                stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
    </span>
</div>
@endforeach