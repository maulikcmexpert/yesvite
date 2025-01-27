<div class="container-fluid">
    <h1 class="m-0 ProductTitle">{{$title}}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/users')}}">Users</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transcation.index', ['user_id' => encrypt($userId)]) }}">Coin Transaction</a></li>

                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="col-md-12 pl-0">

        <div class="card card-primary mt-4 categoryCard">

            <div class="card-header">

                <h3 class="card-title">{{$title}}</h3>

            </div>

            <form method="post" action="{{ route('transcation.store')}}" id="addCoin_form" enctype="multipart/form-data">

                @csrf

                <div class="card-body row">

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="form-group">
                            <label for="firstname">Credit Coins</label>
                            <input type="number" class="form-control firstname" id="credit_coin"name="credit_coin" placeholder="Credit coin here"  value="" min="1">
                            <span class="text-danger">{{ $errors->first('credit_coin') }}</span>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="form-group">
                            <label for="lastname">Description</label>
                            <input type="text" class="form-control lastname" name="description" placeholder="Enter the description" value="">
                            <input type="hidden" class="form-control lastname" name="user_id" id="user_id" placeholder="Enter the description" value="{{$userId}}">
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                        </div>
                    </div>




                </div>

                <div class="card-footer">
                    <input type="button" class="btn btn-primary" id="addCoin" value="Add">
                </div>

            </form>

        </div>





    </div>

</div>