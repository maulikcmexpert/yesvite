<div class="md-5">

    <form action="{{ route('import.csv') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file">
        <button type="submit">Import CSV</button>
    </form>
    <?php
    $id = Auth::guard('web')->user()->id;

    $userId = encrypt(327);
    $eventId = encrypt(1505);
    ?>
    <a href="{{route('rsvp',['userId' => $userId,'eventId' =>$eventId])}}">RSVP</a>
</div>