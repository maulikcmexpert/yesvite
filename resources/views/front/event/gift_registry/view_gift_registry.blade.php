@if(isset($registry)&&!empty($registry))

@foreach ($registry as $data )
<div class="d-flex align-items-center justify-content-center">
    <span class="me-2">
        @if (strpos($data['registry_link'], 'amazon.com') !== false) 
            <a href="{{ $data['registry_link'] }}">
                <img src="{{ asset('assets/amazon.jpg') }}" alt="eventpic" style="max-width: 145px;">
            </a>
        @endif
        @if (strpos($data['registry_link'], 'target.com') !== false) 
            <a href="{{ $data['registry_link'] }}">
                <img src="{{ asset('assets/target.jpg') }}" alt="eventpic" style="max-width: 145px;">
            </a>
        @endif
    </span>
    {{-- <h6>Target</h6> --}}
</div>
@endforeach
@endif