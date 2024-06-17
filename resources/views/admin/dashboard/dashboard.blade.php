<div class="container-fluid">

  <!-- Small boxes (Stat box) -->

  <div class="row">

    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 overflow-hidden">

      <!-- small box -->

      <a href="{{URL::to('/admin/users')}}" class="small-box">

        <div class="inner">

          <h3>{{$total_users}}</h3>



          <p>Users</p>

        </div>

        <div class="icon">

          <img src="{{ asset('assets/admin/admin_icons/icon_users.png')}}">

        </div>



      </a>

    </div>

    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 overflow-hidden">

      <!-- small box -->

      <a href="{{ route('events.index', ['eventType' => 'professional_users']) }}" class="small-box">

        <div class="inner">

          <h3>{{$total_professional_users}}</h3>



          <p>Professional Users</p>

        </div>

        <div class="icon">

          <img src="{{ asset('assets/admin/admin_icons/icon_prof_users.png')}}">

        </div>



      </a>

    </div>

    <!-- ./col -->

    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 overflow-hidden">

      <!-- small box -->

      <a href="{{ route('events.index.type', ['eventType' => 'normal_event']) }}" class="small-box">

        <div class="inner">

          <h3>{{$normal_total_events}}</h3>



          <p>Events</p>

        </div>

        <div class="icon">

          <img src="{{ asset('assets/admin/admin_icons/icon_events.png')}}">

        </div>

        <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->

      </a>

    </div>

    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 overflow-hidden">

      <!-- small box -->

      <a href="{{ route('events.index.type', ['eventType' => 'professional_event']) }}" class="small-box">

        <div class="inner">

          <h3>{{$professional_total_events}}</h3>



          <p>Professional Event</p>

        </div>

        <div class="icon">

          <img src="{{ asset('assets/admin/admin_icons/icon_events.png')}}">

        </div>

        <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->

      </a>

    </div>

    <!-- ./col -->

    <div class="col-xl-3 col-lg-4 col-md-4  col-sm-6 col-12 overflow-hidden">

      <!-- small box -->

      <div class="small-box">

        <div class="inner">

          <h3>{{$total_held_events}}</h3>



          <p>Event Held</p>
          <input type="hidden" id="avgHeldEvent" value="{{$total_held_events_avg}}">

        </div>

        <div class="icon">

          <img src="{{ asset('assets/admin/admin_icons/icon_event_helds.png')}}">

        </div>


      </div>

    </div>

  </div>

  <!-- /.row -->

  <!-- Main row -->

  <div class="row">

    <!-- Left col -->

    <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
      <div class="event-calender">
        <!-- <input type='date' class="form-control" id='datetimepicker1'> -->
        <div id="datepicker" class="calendar"></div>
      </div>
    </div>

    <div class="col-xl-8 col-lg-6 col-md-6">
      <div class="upcoming-events-wrp">
        <h3>Upcoming Events</h3>
        <div class="row" id="upcomingEvent">







        </div>

      </div>
    </div>


  </div>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>