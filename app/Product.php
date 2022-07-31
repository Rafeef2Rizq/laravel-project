<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
class Product extends Model
{
    use SearchableTrait;
    protected $fillable = ['quantity'];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'products.name' => 10,
            'products.details' => 5,
            'products.description' => 2,
        ],
    ];
    public function Categories()
    {
        return $this->belongsToMany('App\Category');
    }
    public function persertPrice(){
   return sprintf('$%01.2f', $this->price / 100);
    }
    public function scopeMightAlsoLike($query){
        return $query->inRandomOrder()->take(4);
         }
}
