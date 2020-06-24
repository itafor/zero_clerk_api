<?php

namespace App;

use App\Inventory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transfer extends Model
{
     use SoftDeletes;
   
    protected $fillable = ['transfering_location_id','receiving_location_id','user_id','category_id','sub_category_id','item_id','quantity'];

   public function user(){
       	return $this->belongsTo(User::class,'user_id','id');
       }

   public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

   public function subcategory()
    {
        return $this->belongsTo(SubCategory::class,'sub_category_id','id');
    }

     public function item()
    {
        return $this->belongsTo(Item::class,'item_id','id');
    }

    public function transfering_location(){
        return $this->belongsTo(Location::class,'transfering_location_id','id');
    }

    public function receiving_location(){
        return $this->belongsTo(Location::class,'receiving_location_id','id');
    }

    public static function createNew($data,$transferingItem,$receivingItem)
    {
        $transfer = self::create([
            'transfering_location_id' => $data['transfering_location_id'],
            'receiving_location_id' => $data['receiving_location_id'],
            'category_id' => $data['category_id'],
            'sub_category_id' => $data['sub_category_id'],
            'item_id' =>  $data['item_id'],
            'quantity' =>  $data['quantity'],
            'user_id' => authUser()->parent_id == null ? authUser()->id : authUser()->parent_id,
        ]); 


        self::transferItem($data['quantity'],$transferingItem);
        self::receiveItem($data['quantity'],$receivingItem);
        
        return $transfer;
    }

    public static function transferItem($itemQuantity,$itemTransferred){
    	 $inventory = Inventory::where([
        	['item_id', $itemTransferred->item_id],
            ['location_id', $itemTransferred->location_id],
            ['user_id', $itemTransferred->user_id],
        ])->first();

    	 if($inventory){
    		$inventory->quantity -= $itemQuantity;
    		$inventory->save(); 
    	}
    }

     public static function receiveItem($itemQuantity,$itemReceived){
    	 $inventory = Inventory::where([
        	['item_id', $itemReceived->item_id],
            ['location_id', $itemReceived->location_id],
            ['user_id', $itemReceived->user_id],
        ])->first();

    	 if($inventory){
    		$inventory->quantity += $itemQuantity;
    		$inventory->save(); 
    	}
    }
}
