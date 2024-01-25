 <div class="container-fluid">

  <h1 class="m-0 ProductTitle">{{$title}}</h1>
  <!-- <h1>Category List</h1> -->
  <div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right w-100">
          <li class="breadcrumb-item"><a href="{{URL::to('/admin/dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">{{$title}}</li>
        </ol>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <div class="text-right">
          <a class="btn btn-primary" href="{{URL::to('admin/subcategory/create')}}">Add</a>
        </div>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>



     

     <table id="subcategory_table" class="table table-bordered data-table subcategory-data-table">

         <thead>

             <tr>

                 <th>No</th>

                 <th>Category Name</th>

                 <th>Subcategory Name</th>



                 <th width="100px">Action</th>

             </tr>

         </thead>

         <tbody>

         </tbody>

     </table>

 </div>