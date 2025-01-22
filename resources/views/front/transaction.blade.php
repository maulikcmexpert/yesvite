{{-- {{dd($transcation);}} --}}
<x-front.advertise />
<section class="transaction_history_wrp">
    <div class="container">
        <div class="row">
            {{-- <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-5 mb-md-0 mb-4"> --}}
                <x-front.sidebar1 :profileData="$user" />
            {{-- </div> --}}
            <div class="col-xxl-9 col-xl-8 col-lg-8 col-md-7">
                <div class="transaction_history-info">
                    <nav class="breadcrumb-nav" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('profile')}}">Profile</a></li>
                            <li class="breadcrumb-item"><a href="{{route('profile.account_settings')}}">Account Setting</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Transactions</li>
                        </ol>
                    </nav>
                    <div class="transaction_history_info_title">
                        <h4>Transaction History</h4>
                    </div>
                    <input type="hidden" id="graph_data" value="{{$data['graph_data']}}"/>
                    <div class="credits_balance_wrp">
                        <h3 class="credits_balance_title">Credits Balance</h3>
                        <div class="credits_balance_amout_wrp">
                            <h5>
                                <img src="{{asset('assets/front/image/credit-coin-img.png')}}" alt="">
                                <span class="available-coins">{{$coins}}</span>
                            </h5>
                            <h6><span><i class="fa-solid fa-arrow-up"></i> {{$data['last_month_comparison_percentage']}}%</span>Compared to {{$data['last_month_balance']}} credits last month</h6>
                        </div>
                        <canvas id="creditChart"></canvas>
                        <div class="credits_balance_used_wrp">
                            <img src="{{asset('assets/front/image/credit-coin-img.png')}}" alt="">
                            <h6>Credits used this year</h6>
                        </div>
                        <div class="credits_balance_amout_wrp credits_balance_amout_after">
                            <h5>
                                <span>{{$data['credit_use_this_year']}}</span>
                            </h5>
                            <h6><span><i class="fa-solid fa-arrow-up"></i> {{$data['last_year_comparison']}}%</span>Over last year</h6>
                        </div>
                        <div class="transaction_detail_wrp">
                            <div class="transaction_detail_title">
                                <h3>Transactions</h3>
                            </div>
                            <ul class="transaction_detail_list">
                                @if(!empty($transcation))
                                        @foreach ($transcation as $transaction_data ) 
                                                @php
                                        
                                                    $type=$transaction_data['type'];
                                                    $status="";
                                                    $status_color="";
                                                    if($type=="credit"){
                                                        $status="+";
                                                        $status_color="amount-plus";
                                                    }elseif($type=="debit"){
                                                        $status="-";
                                                        $status_color="amount-minus";
                                                    }
                                                @endphp
                                                <li>
                                                    <div class="transaction_detail_list_left">
                                                        <h3>{{$transaction_data['description']}}</h3>
                                                        <ul>
                                                            <li>{{$transaction_data['current_balance']}} balance</li>
                                                            <li>{{$transaction_data['date']}}</li>
                                                            <li>{{$transaction_data['time']}}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="transaction_detail_list_right">
                                                        <div class="transaction_detail_amount {{$status_color}}">
                                                            <img src="{{asset('assets/front/image/credit-coin-img.png')}}" alt="">
                                                            <p>{{$status}}{{$transaction_data['coins']}}</p>
                                                        </div>
                                                    </div>
                                                </li>                               
                                        @endforeach

                                @endif
                                {{-- <li>
                                    <div class="transaction_detail_list_left">
                                        <h3>500 Credits Bulk Credits</h3>
                                        <ul>
                                            <li>500 balance</li>
                                            <li>Aug 30, 2024</li>
                                            <li>6:00PM</li>
                                        </ul>
                                    </div>
                                    <div class="transaction_detail_list_right">
                                        <div class="transaction_detail_amount amount-plus">
                                            <img src="{{asset('assets/front/image/credit-coin-img.png')}}" alt="">
                                            <p>+500</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="transaction_detail_list_left">
                                        <h3>500 Credits Bulk Credits</h3>
                                        <ul>
                                            <li>500 balance</li>
                                            <li>Aug 30, 2024</li>
                                            <li>6:00PM</li>
                                        </ul>
                                    </div>
                                    <div class="transaction_detail_list_right">
                                        <div class="transaction_detail_amount amount-plus">
                                            <img src="{{asset('assets/front/image/credit-coin-img.png')}}" alt="">
                                            <p>+500</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="transaction_detail_list_left">
                                        <h3>500 Credits Bulk Credits</h3>
                                        <ul>
                                            <li>500 balance</li>
                                            <li>Aug 30, 2024</li>
                                            <li>6:00PM</li>
                                        </ul>
                                    </div>
                                    <div class="transaction_detail_list_right">
                                        <div class="transaction_detail_amount amount-minus">
                                            <img src="{{asset('assets/front/image/credit-coin-img.png')}}" alt="">
                                            <p>-500</p>
                                        </div>
                                    </div>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>

</section>