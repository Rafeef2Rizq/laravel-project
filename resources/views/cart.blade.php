@extends('layout')

@section('title', 'Shopping Cart')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/algolia.css') }}">
@endsection

@section('content')

<div class="breadcrumbs">
    <div class="container">
        <a href="/">Home</a>
        <i class="fa fa-chevron-right breadcrumbs-seperator"></i>
        <a href="{{route('shop.index')}}">shop</a>
        <i class="fa fa-chevron-right breadcrumbs-seperator"></i>
        <span>Macbook Pro</span>
    </div>
</div><!-- end breadcrumbs -->
<div class="cart-section container">
    <div>

@if (session()->has('success-message'))
<div class="alter alter-success">
    {{session()->get('success-message')}}
</div>
@endif
@if(count($errors) >0)
<div class="alter alter-danger">
   <ul>
    @foreach($errors->all() as $error)
    <li>{{$error}}</li>
    @endforeach
   </ul>
</div>
@endif
@if (Cart::count()>0)
<h2>{{Cart::count()}} item(s) in Shopping Cart</h2>

<div class="cart-table">
    @foreach(Cart::content() as $item)
    <div class="cart-table-row">
      <div class="cart-table-row-left">
        <a href="{{route('shop.show',$item->model->slug)}}"><img src="{{asset('img/'. $item->model->slug. '.jpg')}}" alt=" image" class="cart-table-img"></a>
        <div class="cart-item-details">
            <div class="cart-table-item"><a href="{{route('shop.show',$item->model->slug)}}">{{$item->model->name}}</a></div>
            <div class="cart-table-description">{{$item->model->details}}</div>
        </div>
      </div>
<div class="cart-table-row-right">
<div class="cart-table-row-actions">
        <!-- <a href="#">Remove</a> -->
        <form action="{{route('cart.destroy',$item->rowId)}}" method="post">
           {{csrf_field()}} 
           {{method_field('DELETE')}}
           <button type="submit" class="cart-options">Remove</button>
        </form>
        <!-- <a href="#">Save for Later</a> -->
        <form action="{{route('cart.switchToSaveForLater',$item->rowId)}}" method="post">
           {{csrf_field()}} 
         
           <button type="submit" class="cart-options">Save for Later</button>
        </form>
</div>

<select class="quantity" data-id="{{ $item->rowId }}">
                       @for ($i = 1; $i < 5 + 1 ; $i++)
                                    <option {{ $item->qty == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
<!-- <option {{ $item->qty == 1 ? 'selected' : '' }}>1</option>
<option {{ $item->qty == 2 ? 'selected' : '' }}>2</option>
<option {{ $item->qty == 3 ? 'selected' : '' }}>3</option>
<option {{ $item->qty == 4 ? 'selected' : '' }}>4</option>
<option {{ $item->qty == 5 ? 'selected' : '' }}>5</option> -->
</select>

</div>
<div>{{persentPrice($item->subtotal)}}</div>

  </div>
</div> <!-- end cart table row -->
@endforeach



<div class="cart-totals">
    <div class="cart-totals-left">
        Shipping is free because  we're awesome like that. Also because tha's additional stuff I don't feel like figuuring
        out :).
    </div>
    <div class="cart-totals-right">
        <div>
            Subtotal <br>
            Tax(13%) <br>
            <span class="cart-totals-total">Total</span>
        </div>

   <div class="cart-totals-subtotal">
    {{persentPrice(Cart::subtotal())}} <br>
    {{persentPrice(Cart::tax())}} <br>
    <span class="cart-totals-total">{{persentPrice(Cart::total())}}</span>
   </div>


    </div>
</div> <!-- end cart-totals -->

<div class="cart-buttons">
    <a href="{{route('shop.index')}}" class="button">Continue Shopping</a>
    <a href="{{route('checkout.index')}}" class="button">Proceed to Checkout</a>
</div>


@else
<h3>No item in Cart</h3>
<div class="spacer"></div>
<a href="{{route('shop.index')}}" class="button">Continue Shopping</a>
<div class="spacer"></div>
@endif
@if (Cart::instance('SaveForLater')->count() > 0)
<h2>{{Cart::instance('SaveForLater')->count()}} item(s) saved for Later</h2>
<div class="saved-for-later cart-table">
    @foreach(Cart::instance('SaveForLater')->content() as $item)
    <div class="cart-table-row">
        <div class="cart-table-row-left">
            <a href="{{route('shop.show',$item->model->slug)}}"><img src="{{asset('img/'.'macbook-pro'.'.png')}}" alt="item" class="cart-table-img"></a>
            <div class="cart-item-details">
<div class="cart-table-item"><a href="{{route('shop.show',$item->model->slug)}}">{{$item->model->name}}</a></div>
<div class="cart-table-description"><a href="{{route('shop.show',$item->model->slug)}}">{{$item->model->details}}</a></div>
            </div>
        </div>
        <div class="cart-table-row-right">
            <div class="cart-table-actions">
   <!-- <a href="#">Remove</a>
   <a href="#">Move to Cart</a> -->
   <form action="{{route('saveForLater.destroy',$item->rowId)}}" method="post">
           {{csrf_field()}} 
           {{method_field('DELETE')}}
           <button type="submit" class="cart-options">Remove</button>
        </form>
        <!-- <a href="#">Save for Later</a> -->
        <form action="{{route('saveForLater.switchToCart',$item->rowId)}}" method="post">
           {{csrf_field()}} 
         
           <button type="submit" class="cart-options">Move to Cart</button>
        </form>
        </div>

<div>{{$item->model->persertPrice()}}</div>

        </div>

@endforeach
    </div><!-- end cart-table-row -->
  
</div><!-- end saved for later -->
@else
<h3>You have no item saved for later</h3>
@endif
    </div>
    </div>
    </div><!-- end cart section -->
@include('might-like')

@endsection
@section('extra-js')
<script src="{{ asset('js/app.js') }}"></script>
<script>
  
        (function(){
           
            const classname = document.querySelectorAll('.quantity')
            Array.from(classname).forEach(function(element) {
                element.addEventListener('change', function() {
                    const id = element.getAttribute('data-id')
                    axios.patch(`/cart/${id}`, {
                        quantity: this.value
  })
  .then(function (response) {
    // console.log(response);
    window.location.href = '{{ route('cart.index') }}';
  })
  .catch(function (error) {
    console.log(error);
    window.location.href = '{{ route('cart.index') }}';
  });
                })
            })
            //         const id = element.getAttribute('data-id')
            //         const productQuantity = element.getAttribute('data-productQuantity')
            //         axios.patch(`/cart/${id}`, {
            //             quantity: this.value,
            //             productQuantity: productQuantity
            //         })
            //         .then(function (response) {
            //             // console.log(response);
                        // window.location.href = '{{ route('cart.index') }}'
            //         })
            //         .catch(function (error) {
            //             // console.log(error);
            //             window.location.href = '{{ route('cart.index') }}'
            //         });
            //     })
            // })
        })();
    </script>


@endsection