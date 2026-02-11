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

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

     <script>
         document.addEventListener('DOMContentLoaded', function() {
             // --- 1. 連打防止の設定 ---

             // フォーム送信時の処理
             document.querySelectorAll('form').forEach(function(form) {
                 form.addEventListener('submit', function(e) {
                     const button = form.querySelector('button[type="submit"]');
                     if (button && !button.disabled) { // まだ無効化されていない場合のみ
                         setTimeout(() => {
                             button.disabled = true;
                             button.dataset.disabledByJs = "true"; // ★復活用の目印をつける
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
                         link.dataset.disabledByJs = "true"; // ★復活用の目印をつける
                         link.style.opacity = '0.6';
                         link.style.cursor = 'not-allowed';
                     }, 10);
                 });
             });
         });

         // --- 2. 「戻る」ボタンで戻ってきた時の復活処理 (bfcache対策) ---
         window.addEventListener('pageshow', function(event) {
             // ページがキャッシュから読み込まれた場合、または通常の読み込みでも念の為実行

             // JavaScriptで無効化したボタンを元に戻す
             const disabledButtons = document.querySelectorAll('button[data-disabled-by-js="true"]');
             disabledButtons.forEach(btn => {
                 btn.disabled = false;
                 btn.style.opacity = '';
                 btn.style.cursor = '';
                 delete btn.dataset.disabledByJs; // 目印を消す
             });

             // JavaScriptで無効化したリンクを元に戻す
             const disabledLinks = document.querySelectorAll('a[data-disabled-by-js="true"]');
             disabledLinks.forEach(link => {
                 link.style.pointerEvents = 'auto';
                 link.style.opacity = '';
                 link.style.cursor = '';
                 delete link.dataset.disabledByJs; // 目印を消す
             });
         });
     </script>

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