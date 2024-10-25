<div class="container-fluid">
    <div id="loader" style="display: none;">
        <img src="{{asset('assets/front/loader.gif')}}" alt="loader" style="width:146px;height:146px;z-index:1000">
    </div>
    <h1 class="m-0 ProductTitle">{{ $title }}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{ URL::to('/admin/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div><!-- /.col -->
                <!-- <div class="col-sm-6">
                    <div class="text-right">
                        <a class="btn btn-primary" href="{{ URL::to('admin/category/create') }}">Add</a>
                    </div>
                </div> -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="col-md-12 pl-0">

        <div class="card card-primary mt-4 categoryCard">

            <div class="card-header">

                <h3 class="card-title">{{ $title }}</h3>

            </div>

            <form method="post" action="" id="notificationForm" enctype="multipart/form-data">

                @csrf

                <div class="card-body row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="form-group">
                            <label for="notification_title">Notification Title:</label>
                            <input type="text" class="form-control" name="title"  id="title"
                                placeholder="Enter your notification title ">
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="form-group">
                            <label for="notification_message">Notification Message:</label>
                            <textarea   id="message" class="form-control" rows="4"
                                placeholder="Enter your notification message here..."></textarea>
                                <span class="text-danger">{{ $errors->first('message') }}</span>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <input type="button" class="btn btn-primary" id="send_bluk_message" value="Send">
                </div>

            </form>

        </div>





    </div>
