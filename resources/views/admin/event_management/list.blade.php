<div class="container-fluid">

    <h1 class="m-0 ProductTitle">{{$title}}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div><!-- /.col -->
                <!-- <div class="col-sm-6">
                    <div class="text-right">
                        <a class="btn btn-primary" href="{{URL::to('admin/category/create')}}">Add</a>
                    </div>
                </div> -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="row filter-row">

        <div class="col-sm-6 col-md-3">

            <div class="form-group">



                <input type="date" class="form-control floating" data-name="pname" id="eventDate" placeholder="Date">

            </div>

        </div>

        <div class="col-sm-6 col-md-3">

            <div class="form-group">
                <div class="position-relative w-100 selectArrow">
                    <select name="event_status" id="event_status" class="form-control event_status">
                        <option value="">Event Status</option>
                        <option value="upcoming_events">Upcoming Events</option>
                        <option value="draft_events">Draft Events</option>
                        <option value="past_events">Past Events</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">

            <div class="form-group">
                <div class="position-relative w-100 selectArrow">
                    <select name="event_type" id="event_type" class="form-control event_type">
                        <option value="">Event Type</option>
                        <option value="normal_user_event">Normal User Events</option>
                        <option value="professional_event">Professional Events</option>
                    </select>
                </div>
            </div>
        </div>

    </div>


    <table id="events_table" class="table table-bordered data-table users-data-table">

        <thead>

            <tr>

                <th>No</th>
                <th>Event Name</th>
                <th>Event By</th>
                <th>Email</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Venue</th>
                <th>Event Status</th>
                <th>Action</th>
            </tr>

        </thead>

        <tbody>

        </tbody>

    </table>

</div>