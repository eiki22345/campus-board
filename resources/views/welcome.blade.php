@extends('layouts.app')

@section('content')
<main>
    <div class="hero-section">
        <div class="hero-img d-flex justify-content-center">
            <img class="img-fluid" src="{{ asset('img/hero-1.png') }}" alt="同じ空の下で本音で語り合おう。北海道の大学生限定匿名SNS">
        </div>
        <div class="hero-img d-flex justify-content-center">
            <img class="img-fluid" src="{{ asset('img/hero-2.png') }}" alt="同じ空の下で本音で語り合おう。北海道の大学生限定匿名SNS">
        </div>
        <div class="hero-img d-flex justify-content-center">
            <img class="img-fluid" src="{{ asset('img/hero-3.png') }}" alt="同じ空の下で本音で語り合おう。北海道の大学生限定匿名SNS">
        </div>
        <div class="hero-img d-flex justify-content-center">
            <img class="img-fluid" src="{{ asset('img/hero-4.png') }}" alt="同じ空の下で本音で語り合おう。北海道の大学生限定匿名SNS">
        </div>
        <div class="hero-img-agree d-flex justify-content-center">
            <img class="img-fluid hero-agree" src="{{ asset('img/hero5.png') }}" alt="同じ空の下で本音で語り合おう。北海道の大学生限定匿名SNS">
            <a href="{{route('login')}}" class="agree-link">
                <img src="{{ asset('img/agree.png') }}">
            </a>
        </div>
    </div>
</main>
@endsection