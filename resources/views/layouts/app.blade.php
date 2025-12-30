 <!DOCTYPE html>
 <html lang="ja">

 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="csrf-token" content="{{ csrf_token() }}">

     <title>@yield('title')</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <script src="https://kit.fontawesome.com/a251afe25c.js" crossorigin="anonymous"></script>
     <meta name="csrf-token" content="{{ csrf_token() }}">

     <link href="{{ asset('css/style.css')}}" rel="stylesheet">
 </head>

 <body>
     @yield('content')

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 </body>

 </html>