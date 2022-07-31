
@extends('layout')

@section('title', 'Checkout')

                   @section('extra-css')
    <style>
        .mt-32 {
            margin-top: 32px;
        }
    </style>
                            <script src="https://js.stripe.com/v3/"></script>

    @endsection

     @section('content')

                 <div class="container">
                 @if (session()->has('success_message'))
            <div class="spacer"></div>
            <div class="alert alert-success">
                {{ session()->get('success_message') }}
            </div>
        @endif

        @if(count($errors) > 0)
            <div class="spacer"></div>
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif
                   <h1 class="checkout-heading stylish-heading">Checkout</h1>
                       <div class="checkout-section">
                               <div>
                               <form action="{{route('checkout.store')}}" method="post" id="payment-form">
                               {{csrf_field()}} 
                              <h2>Billing Details</h2>

                             <div class="form-group">
                              <label for="email">Email Address</label>
                              @if (auth()->user())
                            <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" readonly>
                        @else
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        @endif
                       
                                    </div>
                              <div class="form-group">
                                 <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" >
                                  </div>
                                            <div class="form-group">
                                              <label for="address">Address</label>
                             <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" >
                                </div>

                    <div class="half-form">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" >
                        </div>
                        <div class="form-group">
                            <label for="province">Province</label>
                            <input type="text" class="form-control" id="province" name="province" value="{{ old('province') }}" >
                        </div>
                    </div> <!-- end half-form -->
                    
                      <div class="half-form">
                        <div class="form-group">
                            <label for="postalcode">Postal Code</label>
                            <input type="text" class="form-control" id="postalcode" name="postalcode" value="{{ old('postalcode') }}" >
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" >
                        </div>
                    </div> <!-- end half-form -->
                    

                    <div class="form-group">
                        <label for="card-element">
                          Credit or debit card
                        </label>
                        <div id="card-element">
                          <!-- a Stripe Element will be inserted here. -->
                        </div>

                        <!-- Used to display form errors -->
                        <div id="card-errors" role="alert"></div>
                    </div>
                    <div class="spacer"></div>


     <button type="submit"  id="complete-order" class="button-primary full-width">Complete Order</button>
                      

                            </form>
                           </div>
                            <script src="https://js.stripe.com/v3/"></script>
                            <script src="https://js.braintreegateway.com/web/dropin/1.13.0/js/dropin.min.js"></script>
                            <script src="https://www.paypal.com/sdk/js?client-id=YOUR_CLIENT_ID&components=buttons"></script>

        <script type="text/javascript">
(function(){
  var stripe = Stripe('pk_test_51LMI2ZGGZOCQZ2zwQsePKEkIhOZe2OAMIO9ym5tNGUlZ1wOY8ga0JMtABBu4xHcPWzok4b75KbwVDFo1NazgyUBV009rK7yMqS');
var elements = stripe.elements();
var card = elements.create('card', {
hidePostalCode:true,
style: {
base: {
  iconColor: '#666EE8',
  color: '#31325F',
  lineHeight: '40px',
  fontWeight: 300,
  fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
  fontSize: '15px',

  '::placeholder': {
    color: '#CFD7E0',
  },
},
}
});

card.mount('#card-element');
// Handle real-time validation errors from the card Element.
card.addEventListener('change', function(event) {
              var displayError = document.getElementById('card-errors');
              if (event.error) {
                displayError.textContent = event.error.message;
              } else {
                displayError.textContent = '';
              }
            });
// Create a token or display an error when the form is submitted.
var form = document.getElementById('payment-form');
form.addEventListener('button', function(event) {
  event.preventDefault();
  var options = {
                name: document.getElementById('name_on_card').value,
                address_line1: document.getElementById('address').value,
                address_city: document.getElementById('city').value,
                address_state: document.getElementById('province').value,
                address_zip: document.getElementById('postalcode').value
              }
  stripe.createToken(card,options).then(function(result) {
    if (result.error) {
      // Inform the customer that there was an error.
      var errorElement = document.getElementById('card-errors');
      errorElement.textContent = result.error.message;
    } else {
      // Send the token to your server.
      stripeTokenHandler(result.token);
    }
  });
});

function stripeTokenHandler(token) {
  // Insert the token ID into the form so it gets submitted to the server
  var form = document.getElementById('payment-form');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form
  form.submit();
}
// PayPal Stuff


             
})();


</script>

                     <div class="checkout-table-container">
                     <h2>Your Order</h2>
                     <div class="checkout-table">
                        @foreach(Cart::content() as $item)
                    
                        <div class="checkout-table-row">
                        <div class="checkout-table-row-left">
                            <img src="{{asset('img/'. $item->model->slug. '.jpg')}}" alt="" class="checkout-table-img">
                            <div class="checkout-item-details">
                                <div class="checkout-table-item">{{$item->model->name}}</div>
                                <div class="checkout-table-description">{{$item->model->details}}</div>
                                <div class="checkout-table-price">{{$item->model->persertPrice()}}</div>
                            </div>
                        </div><!-- end checkout table left-->
                 <div class="checkout-table-row-right">
                            <div class="checkout-table-quantity">{{$item->qty}}</div>
                        </div><!-- end checkout table right-->
                        </div><!-- end checkout-table-row-->

                        @endforeach

                     </div><!-- end checkout-table -->




                     <div class="checkout-totals">
                        <div class="checkout-totals-left">
                        Subtotal <br>
                        @if (session()->has('coupon'))
                        Discount({{ session()->get('coupon')['name'] }})
                            <form action="{{ route('coupon.destroy') }}" method="POST" style="display:inline">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}
                                <button type="submit" style="font-size:14px;">Remove</button>
                            </form>
                            <hr>
                            New Subtotal <br>
                        @endif
                        Tax<br>
                        <span class="checkout-totals-total">Total</span>
                        </div><!-- end checkout totals left-->
                              
                                 <div class="checkout-totals-right">
                                 {{persentPrice(Cart::subtotal())}} <br>
                       
                                @if (session()->has('coupon'))
                            -{{ persentPrice( $discount ) }} <br>
                            <hr>
                            {{ persentPrice($newSubtotal) }} <br>
                            @endif
                            <hr>
                                 {{persentPrice($newTax)}} <br>
                                 <span class="checkout-totals-total">{{ persentPrice($newTotal) }}</span>
                                 </div>
                     </div>
                    
                     @if (! session()->has('coupon'))
<a href="#" class="have-code">Have a Code?</a>

<div class="have-code-container">
    <form action="{{ route('coupon.store') }}" method="POST">
        {{ csrf_field() }}
        <input type="text" name="coupon_code" id="coupon_code">
        <button type="submit" class="button button-plain">Apply</button>
    </form>
</div> <!-- end have-code-container -->
@endif

                     @endsection


                     