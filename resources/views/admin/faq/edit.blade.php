

<style>
    .ck-editor__editable {
      white-space: pre-wrap;
    }
        </style>
<div class="container-fluid">
    <h1 class="m-0 ProductTitle">{{ $title }}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{ URL::to('/admin/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ URL::to('/admin/faq') }}">FAQ List</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card card-primary categoryCard faq-footer">
            <div class="card-header">
                <h3 class="card-title">Edit FAQ</h3>
            </div>

            <form method="post" action="{{ route('faq.update',$getTemData->id) }}" id="templateForm" enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- Use PUT for updating -->

                <div class="card-body row">
                    <div class="col-lg-12 mb-3">
                        <div class="form-group">
                            <label for="question">Question</label>
                            <textarea class="form-control question" id="question" name="question" placeholder="Enter question">{{ old('question', $getTemData->question) }}</textarea>
                            <span class="text-danger err_question">{{ $errors->first('question') }}</span>
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <div class="form-group">
                            <label for="answer">Answer</label>
                            <textarea class="form-control answer" id="answer" name="answer" placeholder="Enter answer">{{ old('answer', $getTemData->answer) }}</textarea>
                            <span class="text-danger err_answer">{{ $errors->first('answer') }}</span>
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
