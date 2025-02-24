<div class="container-fluid">
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
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="col-md-12">
        <div class="card card-primary categoryCard">
            <div class="card-header">
                <h3 class="card-title">{{ $privacyPolicies->isEmpty() ? 'Add Terms and Conditions' : 'Edit Terms and Conditions' }}</h3>
            </div>

            <form method="post" action="{{ route('terms_condition.store') }}" id="categoryForm">
                @csrf
                <div class="card-body row" id="appendHtml">
                    @if ($privacyPolicies->isEmpty())
                        <!-- Display empty fields if there are no privacy policies -->
                        <div class="col-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Title</label>
                                <input type="hidden" name="id[]" value=""> <!-- No ID for new entry -->
                                <input type="text" class="form-control title" name="title[]" placeholder="Enter Title">
                                <span class="text-danger">{{ $errors->first('title.*') }}</span>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control description" id="description" rows="7" name="description[]" placeholder="Enter Description"></textarea>
                                <span class="text-danger">{{ $errors->first('description.*') }}</span>
                            </div>
                        </div>
                    @else
                        <!-- Loop through and display existing privacy policies for editing -->
                        @foreach ($privacyPolicies as $policy)
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Title</label>
                                    <input type="hidden" name="id[]" value="{{ $policy->id }}">
                                    <input type="text" class="form-control title" name="title[]" placeholder="Enter Title" value="{{ $policy->title }}">
                                    <span class="text-danger">{{ $errors->first('title.*') }}</span>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control description" id="description" rows="7" name="description[]" placeholder="Enter Description">{{ $policy->description }}</textarea>
                                    <span class="text-danger">{{ $errors->first('description.*') }}</span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="card-footer">
                    <input type="submit" class="btn btn-primary" id="cateAdd" value="{{ $privacyPolicies->isEmpty() ? 'Add' : 'Update' }}">
                </div>
            </form>
        </div>
    </div>
</div>
