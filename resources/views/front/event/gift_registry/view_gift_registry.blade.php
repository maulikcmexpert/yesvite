@if(isset($registry)&&!empty($registry))

<!-- {dd($registry)}     -->
@foreach ($registry as $data )
<div class="d-flex align-items-center justify-content-center">
    <span class="gift-registry-icons">
         @php
                                                    $url=$data['registry_link'];
                                                    $logo="";
                                                    if(strpos($url, 'amazon') != false ||strpos($url, 'Amazon') != false){
                                                        $logo=asset('assets/create_amazon.png');
                                                    }elseif (strpos($url, 'target') != false ||strpos($url, 'Target') != false) {
                                                        $logo=asset('assets/create_target.png');
                                                    }else{
                                                        $logo=asset('assets/other.png');
                                                    }

                                                    @endphp

            <a href="{{ $data['registry_link'] }}" target="_blank">
                <img src="{{ $logo }}" alt="eventpic" style="max-width: 145px;">
            </a>                                          
    </span>
    {{-- <h6>Target</h6> --}}
</div>
@endforeach
@endif