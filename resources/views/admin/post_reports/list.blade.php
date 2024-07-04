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

    <!-- <div class="text-right mb-2">

        <a class="btn btn-primary" href="{{URL::to('admin/category/create')}}">Add</a>

     </div> -->

    {{-- <table id="user_post_report_table" class="table table-bordered data-table users-data-table">

        <thead>

            <tr>

                <th>No</th>
                <th>Username (Reported By)</th>
                <th>Event Name</th>
                <th>Post type</th>
                <th>Action</th>

            </tr>

        </thead>

        <tbody>

        </tbody>

    </table> --}}

    {{ $dataTable->table() }}

    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}


</div>