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
                <div class="col-sm-6">
                    <div class="text-right">
                        <a class="btn btn-primary" href="{{URL::to('admin/design/create')}}">Add</a>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- <div class="text-right mb-2">

        <a class="btn btn-primary" href="{{URL::to('admin/category/create')}}">Add</a>

     </div> -->

    <table id="design_table" class="table table-bordered data-table design-style-data-table">

        <thead>

            <tr>

                <th>No</th>

                <th>Design Category</th>
                <th>Design Subcategory</th>
                <th>Design Style</th>
                <th>template</th>
                <th>Design Colors</th>



                <th width="100px">Action</th>

            </tr>

        </thead>

        <tbody>

        </tbody>

    </table>

</div>