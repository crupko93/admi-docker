@php
    $config = [
        'tinymceKey'    => config('services.tinymce.key'),
        'siteName'      => config('app.name'),
        'siteUrl'       => config('app.url'),
        'apiUrl'        => config('app.url') . '/api',
        'env'           => config('env'),
        'locale'        => $locale = app()->getLocale(),
        'locales'       => config('app.locales')
    ];
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="author" content="Vitaly Kasymov">
        <meta name="description" content="">

        <title>{{ config('app.name') }}</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins:300,400,500,700|Material+Icons' rel='stylesheet'>
        <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600' rel='stylesheet' type='text/css'>
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">

        <script>
            window.Laravel = @json($config);
        </script>
    </head>
    <body>
        <div id="app"></div>

        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
