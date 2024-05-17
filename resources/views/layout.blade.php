<!DOCTYPE html>
<html lang="en">

<head>
    <x-front.header title={{$title}} />
</head>

<body>

    <x-front.bodyheader title={{$title}} />

    @include($page)


    <x-front.footer :js="json_encode($js)" />
</body>

</html>