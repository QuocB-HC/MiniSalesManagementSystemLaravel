<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home Page</title>
    <link rel="stylesheet" href="{{ asset('css/pages/home.css') }}">
</head>

<body>
    <x-header />

    <x-product-list :products="$products" />
</body>

</html>
