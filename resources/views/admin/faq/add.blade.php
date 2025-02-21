
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
                        <li class="breadcrumb-item"><a href="{{ URL::to('/admin/faq') }}">FAQ</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="col-md-12">



        <div class="card card-primary categoryCard faq-footer">

            <div class="card-header">

                <h3 class="card-title">Add FAQ</h3>

            </div>





            <form method="post" action="{{ route('faq.store') }}" id="faqAddForm"  >

                @csrf

                <div class="card-body row">
                    <div class="col-lg-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Question</label>
                            <textarea class="form-control question" id="question" name="question" placeholder="Enter question"></textarea>
                            <span class="text-danger err_question">{{ $errors->first('question') }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body row">
                    <div class="col-lg-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Answer</label>
                            <textarea class="form-control answer" id="answer" name="answer" placeholder="Enter answer"></textarea>
                            <span class="text-danger err_answer">{{ $errors->first('answer') }}</span>
                        </div>
                    </div>
                </div>





                {{-- <div class="text-center">

                    <button type="button" class="btn btn-primary" id="addMoreTemplate">Add More </button>

                </div> --}}



                <div class="card-footer">
                    <input type="submit" class="btn btn-primary" id="add_faq" value="Add">
                </div>

            </form>

        </div>

    </div>

</div>



<div style="display: none;" id="AddHtml">
    <div class="col-lg-3 mb-3">
        <div class="form-group">
            <label for="">Image</label>
            <input type="file" class="form-control image" name="image" placeholder="Enter image ">
            <span class="text-danger">{{ $errors->first('image.*') }}</span>
            <i class="fa-solid fa-xmark text-danger remove"></i> <!-- Remove button -->
        </div>
    </div>
</div>
