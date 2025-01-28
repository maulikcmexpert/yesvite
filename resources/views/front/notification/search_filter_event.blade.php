@foreach ($eventList as $event)
{{dd($event)}}
<div class="form-check">
                      <input class="form-check-input" name="selectedEvents[]" data-event_name="{{$event['name']}}" data-event_id="{{$event['if']}}" type="checkbox" value="" id="flexCheckDefault2">
                      <label class="form-check-label invited_to_chk_lbl" for="flexCheckDefault2">
                        {{$event['name']}}
                      </label>
</div>
@endforeach
