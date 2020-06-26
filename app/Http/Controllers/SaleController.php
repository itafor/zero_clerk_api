<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth:api');
    }
       
  public function store(Request $request)
    {
    	$validatedData = $request->validate([
            'industry_id'=>'required',
            'category_id'=>'required',
            'sub_category_id'=>'required',
            'item_id'=>'required',
            'quantity'=>'required',
            'unit_cost'=>'required',
            'location_id'=>'required',
        ]);

        $data = $request->all();

        $inventory = Inventory::where([
            ['item_id',$data['item_id']],
            ['location_id',$data['location_id']],
            ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id],
        ])->first();

        if($inventory){
            if($data['quantity'] > $inventory->quantity){
        return response(['error'=>'Out of stock!! Quantity entered is more than available quantity'],403);
            }

            $sale = Sale::createNew($request->all());

        if($sale){
        return response(['message'=>'Sale created successfully!','sale'=>$sale],200);
        }
        return response(['error'=>'An attempt to create new sale failed!!'],403);
        }

        return response(['error'=>'The selected location and item does not match any record in your inventory!!'],403);
    	
    }

 public function update(Request $request,$sale_id)
    {
    	$validatedData = $request->validate([
            'industry_id'=>'required',
            'category_id'=>'required',
            'sub_category_id'=>'required',
            'item_id'=>'required',
            'quantity'=>'required',
            'unit_cost'=>'required',
            'location_id'=>'required',
        ]);

    	$data = $request->all();
    	$data['sale_id'] = $sale_id;
    	$sale = Sale::updateSale($data);

    	if($sale){
    	return response(['message'=>'Sale updated successfully!'],200);
    	}
    	return response(['error'=>'An attempt to update Sale failed!!'],403);
    }


     public function listSales(Request $request){
    	$sales = Sale::where([
        ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id]
    	])->with(['category','subcategory','user','customer','location','industry','item','payments'])->get();

    	if(count($sales) >=1 ){
    	return response()->json(['Sales'=>$sales]);
    	}
    	return response(['error'=>'Sales not found!!'],401);
    }

     public function fetchSaleById($sale_id){
    	$sale = Sale::where([
        ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id],
    	['id',$sale_id]
    	])->with(['category','subcategory','user','customer','location','industry','item','payments'])->first();

    	if($sale !=''){
    	return response()->json(['Sale'=>$sale]);
    	}
    	return response(['error'=>'Sale not found!!'],401);
    }

   public function destroySale($id){
    	$sale = Sale::find($id);

  if($sale){
  	$sale->delete();
    	return response()->json(['message'=>'Sale deleted successfully']);
    	}
    	return response(['error'=>'Ooops!! Sale not found'],401);
    }

    public function daily_sales_report(){
        $dailySales = Sale::where('sales.user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id)
        ->join('items', 'items.id', '=', 'sales.item_id')
    ->select(
        "items.item",
        "sales.unit_cost",
        "sales.quantity",
        DB::raw("SUM(CASE WHEN sales.created_at >= NOW() - INTERVAL 1 DAY  THEN total_cost ELSE 0 END) daily_sales"),
        DB::raw("SUM(total_cost) total_sales")
    )
    ->groupBy(["sales.quantity","items.item","sales.unit_cost"])
    ->orderByRaw('sales.quantity ASC')->get();

    if(count($dailySales) >=1 ){
        return response()->json(['daily_sales'=>$dailySales]);
        }
        return response(['error'=>'Sales not found!!'],401); 
    }
}
