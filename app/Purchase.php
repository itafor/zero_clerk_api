<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;

      protected $fillable = [
        'category_id','sub_category_id','item','quantity','industry_id','balance','status',
        'unit_cost','payment_type','user_id','supplier_id','location_id', 
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

     public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }

     public function location(){
        return $this->belongsTo(Location::class,'location_id','id');
    }

    public function industry(){
        return $this->belongsTo(Industry::class,'industry_id','id');
    }


        $purchase = self::create([
            'category_id' => $data['category_id'],
            'sub_category_id' =>  $data['sub_category_id'],
            'item' => $data['item'],
            'quantity' => $data['quantity'],
            'user_id' => authUser()->id,
            'industry_id' => $data['industry_id'],
            'unit_cost' => $data['unit_cost'],
            'balance' => $data['unit_cost'],
            'status' => 'Pending',
            'payment_type' => $data['payment_type'],
            'supplier_id' => $data['supplier_id'],
            'location_id' => $data['location_id'],
        ]); 
        
        return $purchase;
    }

  public static function updateSupplier($data)
    {
      $purchase = self::where([
        ['id',$data['purchase_id']],
        ['user_id', authUser()->id]
    ])->update([
           'category_id' => $data['category_id'],
            'sub_category_id' =>  $data['sub_category_id'],
            'item' => $data['item'],
            'quantity' => $data['quantity'],
            'industry_id' => $data['industry_id'],
            'balance' => $data['balance'],
            'status' => $data['status'],
            'unit_cost' => $data['unit_cost'],
            'payment_type' => $data['payment_type'],
            'supplier_id' => $data['supplier_id'],
            'location_id' => $data['location_id'],
        ]); 

      return $purchase;

    }

}
