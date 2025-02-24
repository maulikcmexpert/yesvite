<div class="container-fluid">
    <h1 class="m-0 ProductTitle">{{$title}}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/social_link')}}">Social Link</a></li>
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

            <form method="post" action="{{ route('social_link.store')}}" id="saveLink_form" enctype="">

                @csrf

                <div class="card-body row">

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="form-group">
                            <label for="url">{{$lable}}</label>
                            <input type="hidden" class="form-control url" name="column_name" value="{{$link}}">
                            <input type="text" class="form-control url" name="url" id="url" placeholder="Enter the Url" value="{{$value}}">
                            <span class="text-danger">{{ $errors->first('url') }}</span>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <input type="submit" class="btn btn-primary" id="save_link" value="Save">
                </div>

            </form>

        </div>





    </div>

</div>