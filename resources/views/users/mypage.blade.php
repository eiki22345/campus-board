@extends('layouts.app')

@section('content')

<header>
  <x-headers.header :major_categories='$major_categories' :user_university=' $user_university' :university_boards='$university_boards' :common_boards='$common_boards' />
</header>
<main>

</main>

@endsection