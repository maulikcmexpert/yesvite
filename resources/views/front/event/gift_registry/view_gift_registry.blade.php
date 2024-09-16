@if(isset($registry)&&!empty($registry))
@foreach ($registry as $data )
<div class="d-flex align-items-center">
    <span class="me-2">
        <a href="{{ $data['registry_link'] }}">
            <img src="{{ asset('assets/' . $data['recipient_name'] . '.jpg') }}" alt="eventpic" style="max-width: 145px;">
        </a>
    </span>
    {{-- <h6>Target</h6> --}}
</div>
@endforeach
@endif