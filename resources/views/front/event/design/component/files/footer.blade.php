


        <script src="{{ asset('assets/js/script.js') }}"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

            @if(isset($js))

            @foreach($js as $value)

            <script src="{{ asset('assets') }}/js/{{$value}}.js"></script>

            @endforeach

            @endif
