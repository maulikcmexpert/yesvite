<x-mail::message>
 
Please click on url for update password.

<x-mail::button :url="URL::to('admin/updatePassword/'.$token)">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
