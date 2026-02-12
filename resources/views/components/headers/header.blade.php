 @props([
 'user_university',
 'university_boards',
 'common_boards',
 'major_categories',
 ])


 <div class="col-md-8 header-img-container mx-auto">
   <img src="{{ asset('img/header.png') }}" class="w-100">
   <div class="header-text-overlay">
     <a href="{{ route('dashboard') }}" class="header-text-link prevent-double-click">
       <h1 class="header-text" data-text="STUDENT BBS">STUDENT BBS</h1>
       <h1 class="header-text" data-text="CAMPUS BOARD">CAMPUS BOARD</h1>
     </a>
   </div>
   <a class="board-index-offcanvas-link" data-bs-toggle="offcanvas" href="#offcanvasTop" role="button" aria-controls="offcanvasTop">
     <img class="offcanvas-img" src="{{ asset('img/offcanvas.png') }}">
   </a>
   <div class="offcanvas offcanvas-top board-index-offcanvas col-md-7 text-white fw-bold mx-auto" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
     <div class="offcanvas-body pt-0 ps-3">
       <div>
         <div class="d-flex justify-content-end">

           <button type="button" class="btn-close mt-3" data-bs-dismiss="offcanvas" aria-label="Close"></button>
         </div>

         <a href="{{ route('mypage') }}" class="d-flex align-items-center board-index-offcanvas-a prevent-double-click">

           {{-- 文字を基準にバッジを配置するためのラッパー --}}
           <div class="position-relative">
             <span>お知らせ</span>

             {{-- 未読がある場合のみバッジを表示 --}}
             @if(isset($unread_count) && $unread_count > 0)
             <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
               {{-- 99件以上は '99+' と表示 --}}
               {{ $unread_count > 99 ? '99+' : $unread_count }}
               <span class="visually-hidden">未読のお知らせ</span>
             </span>
             @endif
           </div>
         </a>

         <a href="#" class="board-index-offcanvas-a prevent-double-click" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
           <div>ログアウト</div>
         </a>

         <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
           @csrf
         </form>


         <a href="#" class="board-index-offcanvas-a prevent-double-click">
           <div>ユーザー設定</div>
         </a>



         <hr>

       </div>
       <div class="row mt-2">
         <div class="accordion" id="accordionPanelsStayOpenExample">
           <div class="accordion-item">
             <div class="accordion-header">
               <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                 <div>
                   <span class="board-index-offcanvas-span fw-bold">(your campus)</span>
                   <div class="fw-bold">{{ $user_university->name }}専用</div>
                 </div>
               </button>
             </div>
             <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse">
               <div class="row accordion-body p-0 mt-2">
                 @php
                 // 1. 板があるカテゴリだけ抽出
                 $active_cats = $major_categories->filter(function ($cat) use ($university_boards) {
                 return $university_boards->contains('major_category_id', $cat->id);
                 })->values();

                 // 2. 左右の列にデータを振り分け
                 $columns = [
                 $active_cats->filter(fn($c, $k) => $k % 2 === 0), // 左列
                 $active_cats->filter(fn($c, $k) => $k % 2 !== 0) // 右列
                 ];
                 @endphp

                 {{-- ▼ row で左右の枠を作る --}}
                 <div class="row align-items-start">

                   @foreach ($columns as $cats)
                   {{-- ▼ col-6 の中にアコーディオンを縦積みする (flex-column) --}}
                   <div class="col-6 d-flex flex-column">

                     @foreach ($cats as $major_category)
                     @php
                     // このカテゴリに属する板を取得
                     $my_boards = $university_boards->where('major_category_id', $major_category->id);
                     @endphp

                     <div class="accordion-item border">
                       <h2 class="accordion-header" id="heading-cat-{{ $major_category->id }}">
                         <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-cat-{{ $major_category->id }}" aria-expanded="false" aria-controls="collapse-cat-{{ $major_category->id }}">
                           <span class="fw-bold list-group-item">{{ $major_category->name }}</span>
                         </button>
                       </h2>

                       <div id="collapse-cat-{{ $major_category->id }}" class="accordion-collapse collapse" aria-labelledby="heading-cat-{{ $major_category->id }}">
                         <div class="accordion-body p-0">
                           <div class="d-flex flex-column">
                             @foreach ($my_boards as $board)
                             <a href="{{ route('threads.index', $board->id) }}" class="board-index-offcanvas-a board-index-accordion-a list-group-item mb-2 fw-bold prevent-double-click">
                               {{ Str::after($board->name, '/') }}
                             </a>
                             @endforeach
                           </div>
                         </div>
                       </div>
                     </div>
                     @endforeach

                   </div>
                   @endforeach

                 </div>
               </div>
             </div>
           </div>
         </div>
       </div>

       <hr>

       <div class="row mt-2">
         <div class="accordion" id="accordionCommon">
           <div class="accordion-item">
             <div class="accordion-header">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                 <div>
                   <div class="fw-bold">全国の大学生と交流しよう!</div>
                 </div>
               </button>
             </div>
             <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
               <div class="row accordion-body p-0 mt-2">
                 @php
                 $active_common_cats = $major_categories->filter(function ($cat) use ($common_boards) {
                 return $common_boards->contains('major_category_id', $cat->id);
                 })->values();

                 $common_columns = [
                 $active_common_cats->filter(fn($c, $k) => $k % 2 === 0),
                 $active_common_cats->filter(fn($c, $k) => $k % 2 !== 0)
                 ];
                 @endphp

                 <div class="row align-items-start">
                   @foreach ($common_columns as $cats)
                   <div class="col-6 d-flex flex-column">
                     @foreach ($cats as $major_category)
                     @php
                     $my_common_boards = $common_boards->where('major_category_id', $major_category->id);
                     @endphp
                     <div class="accordion-item border">
                       <h2 class="accordion-header" id="heading-common-cat-{{ $major_category->id }}">
                         {{-- ★修正: no-accordion-arrow を削除したので矢印が出るはずです --}}
                         <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-common-cat-{{ $major_category->id }}">
                           <span class="fw-bold list-group-item">{{ $major_category->name }}</span>
                         </button>
                       </h2>
                       <div id="collapse-common-cat-{{ $major_category->id }}" class="accordion-collapse collapse">
                         <div class="accordion-body p-0 mt-3">
                           <div class="d-flex flex-column">
                             @foreach ($my_common_boards as $board)
                             <a href="{{ route('threads.index', $board->id) }}" class="board-index-offcanvas-a board-index-accordion-a list-group-item mb-2 fw-bold prevent-double-click">
                               {{ $board->name }}
                             </a>
                             @endforeach
                           </div>
                         </div>
                       </div>
                     </div>
                     @endforeach
                   </div>
                   @endforeach
                 </div>
               </div>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>