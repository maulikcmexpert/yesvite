@foreach ($eventList as $event)
  <div class="form-check">
                        <input class="form-check-input" name="selectedEvents[]" data-event_name="{{$event['event_name']}}" data-event_id="{{$event['id']}}" type="checkbox" value="" id="flexCheckDefault2">
                        <label class="form-check-label invited_to_chk_lbl" for="flexCheckDefault2">
                          {{$event['event_name']}}
                        </label>
  </div>
@endforeach
