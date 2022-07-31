<?php

namespace App\Http\Controllers;
use App\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Validator;
class saveForLaterController extends Controller
{


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cart::instance('SaveForLater')->remove($id);
        return back()->with('success-message','It has been removed');
    }

    
    /**
     * Switch item from saved to later to cart
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function switchToCart($id)
    {
        $item=Cart::instance('SaveForLater')->get($id);
        Cart::instance('SaveForLater')->remove($id);
        $duplication=Cart::instance('default')->search(function ( $cartItem , $rowId )  use($id){
          return $rowId === $id;
                });
                if($duplication->isNotEmpty()){
                    return redirect()->route('cart.index')->with('success-message','Item ia already in your cart !');    
                }
        Cart::instance('default')->add($item->id,$item->name,1,$item->price)->associate('App\Product');
        return redirect()->route('cart.index')->with('success-message','Item has been moved to cart !');
    }
}
