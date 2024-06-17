<div class="md-5">

    <form action="{{ route('import.csv') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file">
        <button type="submit">Import CSV</button>
    </form>
    <?php
    $id = Auth::guard('web')->user()->id;
    dd($id);
    $userId = encrypt(1);
    $eventId = encrypt(2);
    ?>
    <a href="{{route('rsvp',['userId' => $userId,'eventId' =>$eventId])}}">RSVP</a>
</div>