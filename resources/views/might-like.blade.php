<div class="might-like-section">
    <div class="container">
        <h2>You might also like...</h2>
        <div class="might-like-grid">
            @foreach($mightAlsoLike as $mightAlsoLikes)
<a href="{{route('shop.show',$mightAlsoLikes->slug)}}" class="might-like-product">
    <img src="{{asset('img/'. $mightAlsoLikes->slug. '.jpg')}}" alt="product">
    <div class="might-like-product-name">{{$mightAlsoLikes->name}}</div>
    <div class="might-like-product-price">{{$mightAlsoLikes->persertPrice()}}</div>

</a>
            @endforeach
        </div>
    </div>
</div>