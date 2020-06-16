<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
     use SoftDeletes;

      protected $fillable = [
        'item','quantity','location_id','user_id','purchase_id','status', 
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

     public function purchase(){
        return $this->belongsTo(Purchase::class,'purchase_id','id');
    }

     public function location(){
        return $this->belongsTo(Location::class,'location_id','id');
    }

    public static function createNew($purchase){

    	$check_inventory = self::where([
    		['user_id', $purchase->user_id],
    		['location_id',$purchase->location_id],
    		['item',$purchase->item]
    	])->first();

    	if($check_inventory){
    		$check_inventory->quantity += $purchase->quantity;
    		$check_inventory->save(); 
    	}else{

        $inventory = self::create([
            'item' => $purchase->item,
            'quantity' => $purchase->quantity,
            'user_id' => $purchase->user_id,
            'location_id' => $purchase->location_id,
            'purchase_id' => $purchase->id,
            'status' => 'In stock',
        ]); 

        return $inventory;

        }

    }

    public static function updateInventoryAfterSale($sale){
    		$inventory = self::where([
            ['id',$sale->inventory_id],
            ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id],
        ])->first();
      if($inventory){
    		$inventory->quantity -= $sale->quantity;
    		$inventory->save(); 
    	}

    	if($inventory){
            self::updateInventoryStatus($inventory);
        }
    	return $inventory;
    }

    public static function updateInventoryStatus($inventoryData){
    		$inventory = self::where([
            ['id',$inventoryData->id],
            ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id],
        ])->first();
      if($inventory){
    		$inventory->status = $inventoryData->quantity == 0 ? 'Out of stock' : 'In stock';
    		$inventory->save(); 
    	}

    	return $inventory;
    }

}
