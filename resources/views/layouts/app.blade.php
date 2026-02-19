 <!DOCTYPE html>
 <html lang="ja">

 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="csrf-token" content="{{ csrf_token() }}">

     <title>@yield('title')</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
         rel="stylesheet"
         integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
         crossorigin="anonymous">
     <!-- Font Awesome: Kit-based loading doesn't support SRI. Consider self-hosting for production. -->
     <script src="https://kit.fontawesome.com/a251afe25c.js" crossorigin="anonymous"></script>
     <script defer
         src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"
         integrity="sha384-1W3L+8YbGR2dykMqvho9h6aT7HJ0IF7TqhtFKh+lkQKTSqmQ1RRjGT9D2c8i9D3p"
         crossorigin="anonymous"></script>
     <link rel="manifest" href="{{ asset('manifest.json') }}">
     <link href="{{ asset('css/style.css')}}" rel="stylesheet">
     @vite(['resources/js/app.js'])
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

     <x-footer.footer />

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
         integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
         crossorigin="anonymous"></script>

     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"
         integrity="sha384-dW6V7h/IthF5FSyrlxs3bLwFHgRKNEQq7xqLLBM3RIzqIBwE8iVyKX2pZLqvTJBz"
         crossorigin="anonymous"></script>
     <script>
         document.addEventListener('DOMContentLoaded', function() {

             const flashData = document.getElementById('flash-message-data');


             if (!flashData) return;


             const msgSuccess = flashData.dataset.success;
             const msgInfo = flashData.dataset.message;
             const msgError = flashData.dataset.error;

             if (!msgSuccess && !msgInfo && !msgError) return;


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

             if (msgSuccess) {
                 Toast.fire({
                     icon: 'success',
                     titleText: msgSuccess
                 });
             }
             if (msgInfo) {
                 Toast.fire({
                     icon: 'info',
                     titleText: msgInfo
                 });
             }
             if (msgError) {
                 Toast.fire({
                     icon: 'error',
                     titleText: msgError
                 });
             }
         });
     </script>

     <script>
         document.addEventListener('DOMContentLoaded', function() {
             // --- 1. 連打防止の設定 ---

             document.querySelectorAll('form').forEach(function(form) {
                 form.addEventListener('submit', function(e) {
                     const button = form.querySelector('button[type="submit"]');
                     if (button && !button.disabled) {
                         setTimeout(() => {
                             button.disabled = true;
                             button.dataset.disabledByJs = "true";
                             button.style.opacity = '0.6';
                             button.style.cursor = 'not-allowed';
                         }, 10);
                     }
                 });
             });

             // リンククリック時の処理
             document.querySelectorAll('a.prevent-double-click').forEach(function(link) {
                 link.addEventListener('click', function(e) {
                     if (link.style.pointerEvents === 'none') {
                         e.preventDefault();
                         return;
                     }
                     setTimeout(() => {
                         link.style.pointerEvents = 'none';
                         link.dataset.disabledByJs = "true";
                         link.style.opacity = '0.6';
                         link.style.cursor = 'not-allowed';
                     }, 10);
                 });
             });
         });

         // --- 2. 「戻る」ボタンで戻ってきた時の復活処理 (bfcache対策) ---
         window.addEventListener('pageshow', function(event) {

             const disabledButtons = document.querySelectorAll('button[data-disabled-by-js="true"]');
             disabledButtons.forEach(btn => {
                 btn.disabled = false;
                 btn.style.opacity = '';
                 btn.style.cursor = '';
                 delete btn.dataset.disabledByJs;
             });


             const disabledLinks = document.querySelectorAll('a[data-disabled-by-js="true"]');
             disabledLinks.forEach(link => {
                 link.style.pointerEvents = 'auto';
                 link.style.opacity = '';
                 link.style.cursor = '';
                 delete link.dataset.disabledByJs;
             });
         });
     </script>



     @if ($errors->has('title') || $errors->has('content'))
     <script>
         document.addEventListener('DOMContentLoaded', function() {
             var myModal = new bootstrap.Modal(document.getElementById('createThreadModal'));
             myModal.show();
         });
     </script>
     @endif

     @if ($errors->has('content'))
     <script>
         document.addEventListener('DOMContentLoaded', function() {
             var myModal = new bootstrap.Modal(document.getElementById('createPostModal'));
             myModal.show();
         });
     </script>
     @endif

 </body>

 </html>