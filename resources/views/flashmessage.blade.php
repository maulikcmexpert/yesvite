@if (Session::get('success') || Session::get('error') || Session::get('warning') || Session::get('info'))
<div class="alert alert-{{(Session::get('success'))?'success':'danger'}} alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>    
    <strong>{{ (Session::get('success'))?Session::get('success'):Session::get('error') }}</strong>
</div>
@endif  