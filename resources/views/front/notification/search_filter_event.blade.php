@php
$i=0
@endphp
@foreach ($eventList as $event)
@php
$i++;
@endphp
  <div class="form-check">
                        <input class="form-check-input selectedEvents" name="selectedEvents[]" data-event_name="{{$event['event_name']}}" data-event_id="{{$event['id']}}" type="checkbox" value=""
                        id="flexCheckDefault{{$i}}"
                        @if(in_array($event['id'], session()->get('notification_event_ids', []))) checked @endif>
                        <label class="form-check-label invited_to_chk_lbl" for="flexCheckDefault{{$i}}">
                          {{$event['event_name']}}
                        </label>
  </div>
@endforeach
  