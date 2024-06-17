
@foreach($yesviteGroups as $value)
<div class="users-data">
    <div class="d-flex align-items-start">

        <div class="text-start">
            <h5>{{$value->name}}</h5>
            <div>
                <span>{{$value->group_members_count.' Guests'}}</span>
            </div>
            <div>

            </div>
        </div>
    </div>
</div>
@endforeach