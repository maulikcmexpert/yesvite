{{-- {{dd($eventDetails)}} --}}
<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <!-- Wall Tab -->
        @if (
            ($eventDetails['is_host'] == 1 && $eventDetails['event_wall'] == "0") ||
            ($eventDetails['is_co_host'] == "1" && $eventDetails['event_wall'] == " 0")
        )
            @php $showWall = false; @endphp
        @else
            @php $showWall = true; @endphp
            <a href="{{ route('event.event_wall', ['id' => encrypt($event)]) }}"
                class="nav-link {{ $page == 'wall' ? 'active' : '' }}"
                id="nav-wall-tab"
                role="tab"
                aria-controls="nav-wall"
                aria-selected="{{ $page == 'wall' ? 'true' : 'false' }}">
                    Wall
            </a>
        @endif

        <!-- About Tab -->
        <a href="{{ route('event.event_about', ['id' => encrypt($event)]) }}"
           class="nav-link {{ ($page == 'about' || !$showWall) ? 'active' : '' }}"
           id="nav-about-tab"
           role="tab"
           aria-controls="nav-about"
           aria-selected="{{ ($page == 'about' || !$showWall) ? 'true' : 'false' }}">
            About
        </a>


        <a href="{{ route('event.event_guest', ['id' => encrypt($event)]) }}"
           class="nav-link {{ $page == 'guest' ? 'active' : '' }}"
           id="nav-guests-tab"
           role="tab"
           aria-controls="nav-guests"
           aria-selected="{{ $page == 'guest' ? 'true' : 'false' }}">
            Guests
        </a>


        <!-- Photos Tab -->
        <a href="{{ route('event.event_photos', ['id' => encrypt($event)]) }}"
           class="nav-link {{ $page == 'photos' ? 'active' : '' }}"
           id="nav-photos-tab"
           role="tab"
           aria-controls="nav-photos"
           aria-selected="{{ $page == 'photos' ? 'true' : 'false' }}">
            Photos
        </a>

        @if (
            ($eventDetails['is_host'] == 1 && $eventDetails['podluck'] == 1) ||  // Host and Potluck enabled
            ($eventDetails['is_host'] == 0 && $eventDetails['rsvp_status'] == '1' ) // Not host but RSVP confirmed
        )
        <!-- Potluck Tab -->
        <a href="{{ route('event.event_potluck', ['id' => encrypt($event)]) }}"
           class="nav-link {{ $page == 'potluck' ? 'active' : '' }}"
           id="nav-potluck-tab"
           role="tab"
           aria-controls="nav-potluck"
           aria-selected="{{ $page == 'potluck' ? 'true' : 'false' }}">
            Potluck
        </a>
        @endif
    </div>
</nav>
