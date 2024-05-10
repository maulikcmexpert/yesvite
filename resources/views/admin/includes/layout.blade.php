<!DOCTYPE html>

<html lang="en">

<x-admin.header title={{$title}} />



<body class="hold-transition sidebar-mini layout-fixed">

  <div class="wrapper">



    <!-- Preloader -->

    <!-- <div class="preloader flex-column justify-content-center align-items-center">

      <img class="animation__shake" src="{{asset('assets/admin/assets/img/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60" width="60">

    </div> -->



    <!-- Navbar -->

    <x-admin.navbar />

    <!-- /.navbar -->



    <!-- Main Sidebar Container -->





    <x-admin.sidebar />





    <!-- Content Wrapper. Contains page content -->

    <div class="content-wrapper">

      <!-- Content Header (Page header) -->



      <!-- /.content-header -->



      <!-- Main content -->

      <section class="content">

        @include('flashmessage')

        @include($page)

      </section>

      <!-- /.content -->

    </div>

    <!-- /.content-wrapper -->

    <x-admin.footer />



    <!-- Control Sidebar -->

    <aside class="control-sidebar control-sidebar-dark">

      <!-- Control sidebar content goes here -->

    </aside>

    <!-- /.control-sidebar -->

  </div>

  <!-- ./wrapper -->



  <x-admin.footerscript />


  @if(isset($js))
  @include($js)
  @endif
</body>



</html>