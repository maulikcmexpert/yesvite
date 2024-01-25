<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{URL::to('/admin/dashboard')}}" class="brand-link">
    <img src="{{asset('storage/yesvitelogo_white.png')}}" alt="Yesvite Logo" class="brand-image logo-full elevation-3" style="opacity: .8'">
    <img src="{{asset('storage/logo2.png')}}" alt="Yesvite Logo" class="brand-image logo-short elevation-3" style="opacity: .8'">
  </a>
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{asset('assets/admin/assets/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image'">
      </div>
      <div class="info">
        <a href="#" class="d-block">Alexander Pierce</a>
      </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">


      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item">
          <a href="{{URL::to('/admin/dashboard')}}" class="nav-link {{ (Request::segment(2) == 'dashboard')? 'active':'' }}">
            <i class="fas fa-tachometer-alt nav-icon"></i>
            <p>Dashboard</p>
          </a>
        </li>



        <li class="nav-item">
          <p class="asideTitle">Design Template</p>
          <ul class="pl-0">
            <li class="nav-item {{ (Request::segment(2) == 'category' || Request::segment(2) == 'subcategory' || Request::segment(2) == 'design_style')? 'menu-open':'' }}">
              <a href="#" class="nav-link  {{ (Request::segment(2) == 'category' || Request::segment(2) == 'subcategory' || Request::segment(2) == 'design_style')? 'active':'' }}">
                <i class="nav-icon fas fa-copy"></i>
                <p>
                  Category Setup
                  <i class="fas fa-angle-left right"></i>
                  <!-- <span class="badge badge-info right">6</span> -->
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{URL::to('/admin/category')}}" class="nav-link  {{ (Request::segment(2) == 'category')? 'active':'' }}">
                    <span class="dot"></span>
                    <p>Category</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{URL::to('/admin/subcategory')}}" class="nav-link {{ (Request::segment(2) == 'subcategory')? 'active':'' }}">
                    <span class="dot"></span>
                    <p>Sub Category</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{URL::to('/admin/design_style')}}" class="nav-link {{ (Request::segment(2) == 'design_style')? 'active':'' }}">
                    <span class="dot"></span>
                    <p>Design Style</p>
                  </a>
                </li>

              </ul>
            </li>
            <li class="nav-item">
              <a href="{{URL::to('/admin/design')}}" class="nav-link {{ (Request::segment(2) == 'design')? 'active':'' }}">
                <i class="fas fa-layer-group"></i>
                <p>
                  Design
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{URL::to('/admin/event_type')}}" class="nav-link {{ (Request::segment(2) == 'event_type')? 'active':'' }}">
                <i class="fas fa-layer-group"></i>
                <p>
                  Event Type
                </p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <p class="asideTitle">User Management</p>
          <ul class="nav">
            <li class="nav-item">
              <a href="{{URL::to('/admin/users')}}" class="nav-link {{ (Request::segment(2) == 'users')? 'active':'' }}">
                <i class="fas fa-users nav-icon"></i>
                <p>Users</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{URL::to('/admin/professional_users')}}" class="nav-link {{ (Request::segment(2) == 'professional_users')? 'active':'' }}">
                <i class="fas fa-user-tie nav-icon"></i>
                <p>Professional Users</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{URL::to('/admin/user_post_report')}}" class="nav-link {{ (Request::segment(2) == 'user_post_report')? 'active':'' }}">
                <i class="fas fa-user-tie nav-icon"></i>
                <p>Users Post Reports</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <p class="asideTitle">Event management</p>
          <ul class="nav">
            <li class="nav-item">
              <a href="{{URL::to('/admin/events')}}" class="nav-link {{ (Request::segment(2) == 'events')? 'active':'' }}">
                <i class="fas fa-list nav-icon"></i>
                <p>Event Lists</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- <li class="nav-item">
            <a href="{{URL::to('/admin/users')}}" class="nav-link {{ (Request::segment(2) == 'users')? 'active':'' }}">
              <i class="fas fa-users nav-icon"></i>
              <p>Users</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{URL::to('/admin/professional_users')}}" class="nav-link {{ (Request::segment(2) == 'professional_users')? 'active':'' }}">
              <i class="fas fa-user-tie nav-icon"></i>
              <p>Professional Users</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{URL::to('/admin/event_lists')}}" class="nav-link {{ (Request::segment(2) == 'event_lists')? 'active':'' }}">
              <i class="fas fa-list nav-icon"></i>
              <p>Event Lists</p>
            </a>
          </li> -->




        <!-- <li class="nav-item menu-open">
          <a href="#" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{URL::to('/admin')}}" class="nav-link active">
                <i class="far fa-circle nav-icon"></i>
                <p>Dashboard v1</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./index2.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Dashboard v2</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./index3.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Dashboard v3</p>
              </a>
            </li>
          </ul>
        </li> -->

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>