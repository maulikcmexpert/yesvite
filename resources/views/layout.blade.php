<!DOCTYPE html>
<html lang="en">

<head>
    <x-front.header title={{$title}} />
</head>

<body>

    <x-front.bodyheader title={{$title}} />

    @include($page)

    <x-front.bodyfooter />

    <x-front.footer :js="$js ?? []" />
</body>

</html>