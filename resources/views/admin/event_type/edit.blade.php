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



        <div class="card card-primary categoryCard">

            <div class="card-header">

                <h3 class="card-title">Edit Event Type</h3>

            </div>

            <form method="post" action="{{ route('event_type.update',$eventTypeId)}}" id="updateEventTypeForm">

                @csrf

                @method('PUT')

                <input type="hidden" name="id" id="catId" value="{{ $eventTypeId }}">

                <div class="card-body">

                    <div class="form-group">

                        <label for="exampleInputEmail1">Event Type</label>

                        <input type="text" class="form-control event_type" name="event_type" placeholder="Enter Event Type" value="{{ $getEventTypeDetail->event_type}}">

                        <span class="text-danger">{{ $errors->first('event_type') }}</span>



                    </div>

                </div>





                <div class="card-footer">

                    <input type="submit" class="btn btn-primary" value="Update">



                </div>

            </form>

        </div>





    </div>

</div>