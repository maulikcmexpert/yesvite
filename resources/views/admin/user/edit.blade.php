<div class="container-fluid">
    <h1 class="m-0 ProductTitle">{{ $title }}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{ URL::to('/admin/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ URL::to('/admin/template') }}">Template List</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card card-primary categoryCard">
            <div class="card-header">
                <h3 class="card-title">Edit User</h3>
            </div>

            <!-- Form for editing user -->
            <form method="post" action="{{ route('users.update', $getTemData->id) }}" id="templateEditForm"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body row" id="appendHtml">
                    <!-- First Name -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="form-group">
                            <label for="firstname">First Name</label>
                            <input type="text" class="form-control" name="firstname" placeholder="Enter First Name"
                                value="{{ $getTemData->firstname }}">
                            <span class="text-danger">{{ $errors->first('firstname') }}</span>
                        </div>
                    </div>

                    <!-- Last Name -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" class="form-control" name="lastname" placeholder="Enter Last Name"
                                value="{{ $getTemData->lastname }}">
                            <span class="text-danger">{{ $errors->first('lastname') }}</span>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" name="email" placeholder="Enter Email Address"
                                value="{{ $getTemData->email }}">
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input type="text" class="form-control" name="phone_number"
                                placeholder="Enter Phone Number" value="{{ $getTemData->phone_number }}">
                            <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" class="form-control" name="city" placeholder="Enter City"
                                value="{{ $getTemData->city }}">
                            <span class="text-danger">{{ $errors->first('city') }}</span>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="form-group">
                            <label for="state">State</label>
                            <input type="text" class="form-control" name="state" placeholder="Enter State"
                                value="{{ $getTemData->state }}">
                            <span class="text-danger">{{ $errors->first('state') }}</span>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <input type="submit" class="btn btn-primary" value="Update">
                </div>
            </form>
        </div>
    </div>
</div>
