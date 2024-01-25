<div class="container-fluid">
    <h1 class="m-0 ProductTitle">{{$title}}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/event_type')}}">Event Type List</a></li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="col-md-12">



        <div class="card card-primary  categoryCard">

            <div class="card-header">

                <h3 class="card-title">Add Event Type</h3>

            </div>





            <form method="post" action="{{ route('event_type.store')}}" id="eventTypeForm">

                @csrf

                <div class="card-body">

                    <div class="form-group">

                        <label for="exampleInputEmail1">Event Type</label>

                        <input type="text" class="form-control event_type" name="event_type[]" placeholder="Enter Event Type" value="{{ old('event_type.*')}}">

                        <span class="text-danger">{{ $errors->first('event_type.*') }}</span>



                    </div>

                    <div id="appendHtml">



                    </div>

                </div>



                <div class="text-right">

                    <button type="button" class="btn btn-primary" id="addMoreEventType">Add More </button>

                </div>



                <div class="card-footer">

                    <input type="submit" class="btn btn-primary" id="EventTypeAdd" value="Add">



                </div>

            </form>

        </div>





    </div>

</div>



<div style="display: none;" id="AddHtml">



    <div class="form-group">

        <label for="exampleInputEmail1">Event Type</label>
        <input type="text" class="form-control event_type" name="event_type[]" placeholder="Enter Event Type" value="{{ old('event_type.*')}}">

        <span class="text-danger">{{ $errors->first('event_type.*') }}</span>

        <!-- <div class="remove"> -->

        <i class="fa-solid fa-xmark text-danger remove"></i>

        <!-- </div> -->

    </div>

</div>