 <div class="col-md-8 mx-auto">
   <form action="{{ $action }}" method="GET" class="search-section d-flex justify-content-center py-2 mb-0">
     @csrf
     <button type="submit"><i class="fas fa-search header-search-icon"></i></button>
     <input type="text" name="keyword" class="header-search-input" placeholder="{{ $placeholder }}" value="{{ $keyword ?? ''}}">
   </form>
 </div>