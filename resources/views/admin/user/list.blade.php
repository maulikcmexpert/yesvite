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
                </div>
                <div class="col-sm-6">
                    <div class="text-right">
                        <a class="btn btn-primary" href="{{URL::to('admin/users/create')}}">Add</a>
                    </div>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- <table id="users_table" class="table table-bordered data-table users-data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Profile</th>
                <th>Username</th>
                <th>App User</th>
            </tr>

        </thead>

        <tbody>

        </tbody>

    </table> -->
    {{ $dataTable->table() }}

</div>
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}