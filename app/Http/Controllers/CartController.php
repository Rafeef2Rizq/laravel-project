<?php

namespace App\Http\Controllers;
use App\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Validator;
class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mightAlsoLike=Product::MightAlsoLike()->get();
        return view('cart')->with('mightAlsoLike',$mightAlsoLike);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $duplication=Cart::search(function ($cartItem,$rowId) use($request){
  return $cartItem->id === $request->id;
        });
        if($duplication->isNotEmpty()){
            return redirect()->route('cart.index')->with('success-message','The item already exist');    
        }
        Cart::add($request->id,$request->name,1,$request->price)->associate('App\Product');
        return redirect()->route('cart.index')->with('success-message','Item was addded to cart!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric|between:1,5'
        ]);
        if ($validator->fails()) {
            session()->flash('errors', collect(['Quantity must be between 1 and 5.']));
            return response()->json(['success' => false], 400);
        }
        Cart::update($id, $request->quantity);
        session()->flash('success-message', 'Quantity was updated successfully!');
      return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cart::remove($id);
        return back()->with('success-message','It has been removed');
    }

      /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function switchToSaveForLater($id)
    {
      $item=Cart::get($id);
      Cart::remove($id);
      $duplication=Cart::instance('SaveForLater')->search(function ( $cartItem , $rowId )  use($id){
        return $rowId === $id;
              });
              if($duplication->isNotEmpty()){
                  return redirect()->route('cart.index')->with('success-message','Item has been saved for Later !');    
              }
      Cart::instance('SaveForLater')->add($item->id,$item->name,1,$item->price)->associate('App\Product');
      return redirect()->route('cart.index')->with('success-message','Item has been saved for Later !');
    }
}
