@extends('layout')

@section('title', 'Shopping Cart')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/algolia.css') }}">
@endsection

@section('content')

@component('components.breadcrumbs')
<div class="breadcrumbs">
    <div class="container">
        <a href="/">Home</a>
        <i class="fa fa-chevron-right breadcrumbs-seperator"></i>
        <a href="{{route('shop.index')}}">shop</a>
        <i class="fa fa-chevron-right breadcrumbs-seperator"></i>
        <span>$product->name</span>
        @endcomponent
        <div class="container">
        @if (session()->has('success_message'))
            <div class="alert alert-success">
                {{ session()->get('success_message') }}
            </div>
        @endif

        @if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    </div>
</div><!-- end breadcrumbs -->
<div class="product-section container">
    <div class="product-section-image">
        <img src="{{asset('img/'. $product->slug. '.jpg')}}" alt="product">
    </div>
    <div class="product-section-information">
        <h1 class="product-section-title">{{$product->name}}</h1>
        <div class="product-section-subtitle">{{$product->details}}</div>
        <div class="product-section-price">{{$product->persertPrice()}}</div>
        <p>{!! $product->description !!}</p>
        <p>&nbsp;</p>
        <!-- <a href="#" class="button">Addn to Cart</a> -->
        <form action="{{route('cart.store')}}" method="post">
        {{csrf_field()}}
        <input type="hidden" name="id" value="{{$product->id}}">
        <input type="hidden" name="name" value="{{$product->name}}">
        <input type="hidden" name="price" value="{{$product->price}}">
        <button type="submit" class="button button-plain">Add to Cart</button>

        </form>
    </div>
</div><!--end product section -->


@endsection
