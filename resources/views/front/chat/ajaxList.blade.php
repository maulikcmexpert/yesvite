@php
    use Carbon\Carbon;
@endphp
<ul class="msg-lists">
    @foreach ($chatLists as $k =>  $messages)
    <input type="hidden" class="selected_conversasion" value="{{$k}}"/> 
        @foreach ($messages as $messageLists)  
            @foreach ($messageLists as $Key => $messageList)   
            {{-- @dd($Key) --}}
            @if (is_array($messageList))
                  

            <li class="{{$userId == @$messageList['senderId'] ? 'receiver':'sender' }}" id="message-{{$Key}}">
                <p>{{@$messageList["data"]}}.</p>
                <span class="time"> 
                @php
                    $timestamp = $messageList["timeStamp"] ?? now()->timestamp;
                    $timeAgo = Carbon::createFromTimestampMs($timestamp)->diffForHumans();
                @endphp
                {{ $timeAgo }}
                </span>
            </li>
            
            @endif
        @endforeach
    @endforeach
    @endforeach
    <div id="msgbox">123 </div>
</ul>