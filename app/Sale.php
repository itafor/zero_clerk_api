<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
     use SoftDeletes;

      protected $fillable = [
        'category_id','sub_category_id','item_id','quantity','industry_id','balance','status',
        'unit_cost','total_cost','payment_type','user_id','customer_id','location_id','inventory_id' 
    ];


    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function subcategory(){
        return $this->belongsTo(SubCategory::class,'sub_category_id','id');
    }

     public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

     public function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }

     public function location(){
        return $this->belongsTo(Location::class,'location_id','id');
    }

     public function inventory(){
        return $this->belongsTo(Inventory::class,'inventory_id','id');
    }

    public function industry(){
        return $this->belongsTo(Industry::class,'industry_id','id');
    }

     public function payments()
    {
        return $this->morphMany('App\Payment', 'payment');
    }

      public function item(){
        return $this->belongsTo(Item::class,'item_id','id');
    }

  public static function createNew($data){

    $totalCost = $data['unit_cost'] * $data['quantity'];

        $sale = self::create([
            'industry_id' => $data['industry_id'],
            'category_id' => $data['category_id'],
            'sub_category_id' =>  $data['sub_category_id'],
            'item_id' =>  $data['item_id'],
            'quantity' => $data['quantity'],
            'user_id' => authUser()->parent_id == null ? authUser()->id : authUser()->parent_id,
            'industry_id' => $data['industry_id'],
            'unit_cost' => $data['unit_cost'],
            'total_cost' => $totalCost,
            'balance' => $totalCost,
            'status' => 'Pending',
            'payment_type' => 'Sale',
            'customer_id' => $data['customer_id'],
            'location_id' => $data['location_id'],
        ]); 

         if($sale){
            Inventory::updateInventoryAfterSale($sale);
        }
        
        return $sale;
    }

    public static function updateSale($data)
    {
      $sale = self::where([
        ['id',$data['sale_id']],
        ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id]
    ])->update([
           'category_id' => $data['category_id'],
            'sub_category_id' =>  $data['sub_category_id'],
            'inventory_id' => $data['inventory_id'],
            'quantity' => $data['quantity'],
            'industry_id' => $data['industry_id'],
            //'unit_cost' => $data['unit_cost'],
            //'status' => $data['status'],
            'payment_type' => 'Sale',
            'customer_id' => $data['customer_id'],
            'location_id' => $data['location_id'],
        ]); 

      return $sale;

    }
}
