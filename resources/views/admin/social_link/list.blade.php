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
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div id="user-table_wrapper" class="dataTables_wrapper no-footer">
    <table id="" class="table table-bordered data-table users-data-table dataTable no-footer">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Link</th>
                <th>Action</th>
            </tr>

        </thead>

        <tbody>
            <tr class="odd">
                <td>1</td>
                <td>X Link</td>
                <td>{{isset($data->x_link) && $data->x_link != null ? $data->x_link : ""}}</td>
                <td>
                    @php
                        $edit_url = route('social_link.edit', encrypt('x_link'));
                    @endphp
                    <div class="action-icon">
                        <a href="{{ $edit_url }}" title="Edit"><i class="fa fa-edit"></i></a>
                    </div>
                </td>
                
            </tr>
            <tr>
                <td>2</td>
                <td>Facebook Link</td>
                <td>{{isset($data->facebook_link) && $data->facebook_link != null ? $data->facebook_link : "";}}</td>
                <td>  @php
                    $edit_url = route('social_link.edit', encrypt('facebook_link'));
                @endphp
                <div class="action-icon">
                    <a href="{{ $edit_url }}" title="Edit"><i class="fa fa-edit"></i></a>
                </div></td>
            </tr>
            <tr class="odd">
                <td>3</td>
                <td>Instagram Link</td>
                <td>{{isset($data->instagram_link) && $data->instagram_link != null ? $data->instagram_link : ""}}</td>
                <td>  @php
                    $edit_url = route('social_link.edit', encrypt('instagram_link'));
                @endphp
                <div class="action-icon">
                    <a href="{{ $edit_url }}" title="Edit"><i class="fa fa-edit"></i></a>
                </div></td>
            </tr>
            <tr>
                <td>4</td>
                <td >Linkedin Link</td>
                <td>{{isset($data->linkedin_link) && $data->linkedin_link != null ? $data->linkedin_link : ""}}</td>
                <td>  @php
                    $edit_url = route('social_link.edit', encrypt('linkedin_link'));
                @endphp
                <div class="action-icon">
                    <a href="{{ $edit_url }}" title="Edit"><i class="fa fa-edit"></i></a>
                </div></td>
            </tr>
          
            <tr class="odd">
                <td>5</td>
                <td>Play Store Link</td>
                <td>{{isset($data->playstore_link) && $data->playstore_link != null ? $data->playstore_link : ""}}</td>
                <td>  @php
                    $edit_url = route('social_link.edit', encrypt('playstore_link'));
                @endphp
                <div class="action-icon">
                    <a href="{{ $edit_url }}" title="Edit"><i class="fa fa-edit"></i></a>
                </div></td>
            </tr>
            <tr>
                <td>6</td>
                <td>App Store Link</td>
                <td>{{isset($data->appstore_link) && $data->appstore_link !=null ? $data->appstore_link : ""}}</td>
                <td>  @php
                    $edit_url = route('social_link.edit', encrypt('appstore_link'));
                @endphp
                <div class="action-icon">
                    <a href="{{ $edit_url }}" title="Edit"><i class="fa fa-edit"></i></a>
                </div></td>
            </tr>
        </tbody>

    </table>
    </div>
</div>