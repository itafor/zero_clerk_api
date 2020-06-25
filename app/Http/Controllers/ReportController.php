<?php

namespace App\Http\Controllers;

use App\Purchase;
use App\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function purchaseReport(Request $request){
    	
      $data = $request->all();

      $start_date = Carbon::parse(formatDate($data['startDate'], 'd/m/Y', 'Y-m-d'));
      $end_date   = Carbon::parse(formatDate($data['endDate'], 'd/m/Y', 'Y-m-d'));

    	$purchase_reports = Purchase::where('user_id',authUser()->parent_id == null ? authUser()->id : authUser()->parent_id)
    	 ->whereBetween('purchases.created_at',[$start_date,$end_date])
    	 ->with(['category','subcategory','supplier','location','industry','item','payments'])->get();

    if(count($purchase_reports) >= 1){
    	return response()->json(['purchase_reports'=>$purchase_reports],200);
    }
    	return response()->json(['error'=>'Purchase not found'],404);

    }

      public function salesReport(Request $request){
    	
      $data = $request->all();

      $start_date = Carbon::parse(formatDate($data['startDate'], 'd/m/Y', 'Y-m-d'));
      $end_date   = Carbon::parse(formatDate($data['endDate'], 'd/m/Y', 'Y-m-d'));

    	$sale_reports = Sale::where('user_id',authUser()->parent_id == null ? authUser()->id : authUser()->parent_id)
    	 ->whereBetween('sales.created_at',[$start_date,$end_date])
    	 ->with(['category','subcategory','customer','location','industry','item','payments'])->get();

    if(count($sale_reports) >= 1){
    	return response()->json(['sale_reports'=>$sale_reports],200);
    }
    	return response()->json(['error'=>'sale not found'],404);

    }
}
