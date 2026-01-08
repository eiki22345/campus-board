 @props([
 'user_university',
 'university_boards',
 'common_boards'
 ])


 <div class="col-md-8 header-img-container mx-auto">
   <img src="{{ asset('img/header.png') }}" class="w-100">
   <div class="header-text-overlay">
     <a href="{{ route('dashboard') }}" class="header-text-link">
       <h1 class="header-text" data-text="STUDENT BBS">STUDENT BBS</h1>
       <h1 class="header-text" data-text="HOKKAI BOARD">HOKKAI BOARD</h1>
     </a>
   </div>
   <a class="board-index-offcanvas-link" data-bs-toggle="offcanvas" href="#offcanvasTop" role="button" aria-controls="offcanvasTop">
     <img class="offcanvas-img" src="{{ asset('img/offcanvas.png') }}">
   </a>
   <div class="offcanvas offcanvas-top board-index-offcanvas col-md-7 text-white fw-bold mx-auto" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
     <div class="offcanvas-body py-0 ps-3">
       <div>
         <div class="d-flex justify-content-end">

           <button type="button" class="btn-close mt-3" data-bs-dismiss="offcanvas" aria-label="Close"></button>
         </div>
         <a href="#" class="board-index-offcanvas-a">
           <div>ログアウト</div>
         </a>
         <a href="#" class="board-index-offcanvas-a">
           <div>ユーザー設定</div>
         </a>

         <hr>

       </div>
       <div class="row mt-2">
         <div class="col-6">
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
                 <div class="accordion-body ps-0">
                   @foreach ($university_boards as $university_board)
                   <div class="col-12 mb-2">
                     <a href="{{route('threads.index', $university_board->id )}}" class="board-index-offcanvas-a board-index-accordion-a fw-bold">
                       <span class="fw-bold">{{ Str::after($university_board->name , '/') }}</span>
                     </a>
                   </div>
                   @endforeach
                 </div>
               </div>
             </div>
           </div>
         </div>
       </div>

       <hr>

       <div class="row">

       </div>

     </div>
   </div>
 </div>