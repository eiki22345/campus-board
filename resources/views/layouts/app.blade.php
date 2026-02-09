 <!DOCTYPE html>
 <html lang="ja">

 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="csrf-token" content="{{ csrf_token() }}">

     <title>@yield('title')</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <script src="https://kit.fontawesome.com/a251afe25c.js" crossorigin="anonymous"></script>
     <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <link rel="manifest" href="{{ asset('manifest.json') }}">
     <link rel="manifest" href="manifest.json">
     <link href="{{ asset('css/style.css')}}" rel="stylesheet">
 </head>

 <body>
     <div id="flash-message-data"
         data-success="{{ session('success') }}"
         data-message="{{ session('message') }}"
         data-error="{{ session('error') }}"
         style="display: none;">
     </div>

     @yield('content')

     @stack('modals')

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

     <script>
         document.addEventListener('DOMContentLoaded', function() {
             // 隠し要素を取得
             const flashData = document.getElementById('flash-message-data');

             // データがなければ何もしない
             if (!flashData) return;

             // data-属性からメッセージを取り出す
             const msgSuccess = flashData.dataset.success;
             const msgInfo = flashData.dataset.message;
             const msgError = flashData.dataset.error;

             // メッセージが一つもなければ終了
             if (!msgSuccess && !msgInfo && !msgError) return;

             // トースト通知の設定
             const Toast = Swal.mixin({
                 toast: true,
                 position: 'top-end',
                 showConfirmButton: false,
                 timer: 3000,
                 timerProgressBar: true,
                 didOpen: (toast) => {
                     toast.addEventListener('mouseenter', Swal.stopTimer)
                     toast.addEventListener('mouseleave', Swal.resumeTimer)
                 }
             });

             // メッセージの種類に応じて表示
             if (msgSuccess) {
                 Toast.fire({
                     icon: 'success',
                     title: msgSuccess
                 });
             }
             if (msgInfo) {
                 Toast.fire({
                     icon: 'info',
                     title: msgInfo
                 });
             }
             if (msgError) {
                 Toast.fire({
                     icon: 'error',
                     title: msgError
                 });
             }
         });
     </script>
 </body>

 </html>