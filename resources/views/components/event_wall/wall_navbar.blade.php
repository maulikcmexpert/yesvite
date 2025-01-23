{{-- {{dd($rsvpSent)}} --}}
<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <!-- Wall Tab -->
        <a href="{{ route('event.event_wall', ['id' => encrypt($event)]) }}"
           class="nav-link {{ $page == 'wall' ? 'active' : '' }}"
           id="nav-wall-tab"
           role="tab"
           aria-controls="nav-wall"
           aria-selected="{{ $page == 'wall' ? 'true' : 'false' }}">
            Wall
        </a>

        <!-- About Tab -->
        <a href="{{ route('event.event_about', ['id' => encrypt($event)]) }}"
           class="nav-link {{ $page == 'about' ? 'active' : '' }}"
           id="nav-about-tab"
           role="tab"
           aria-controls="nav-about"
           aria-selected="{{ $page == 'about' ? 'true' : 'false' }}">
            About
        </a>

        <!-- Guests Tab -->
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

        <!-- Potluck Tab -->
        <a href="{{ route('event.event_potluck', ['id' => encrypt($event)]) }}"
           class="nav-link {{ $page == 'potluck' ? 'active' : '' }}"
           id="nav-potluck-tab"
           role="tab"
           aria-controls="nav-potluck"
           aria-selected="{{ $page == 'potluck' ? 'true' : 'false' }}">
            Potluck
        </a>
    </div>
</nav>
