<?php

namespace App\Http\Controllers;

use App\Expense;
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

        public function expenseReport(Request $request){
      
      $data = $request->all();

      $start_date = Carbon::parse(formatDate($data['startDate'], 'd/m/Y', 'Y-m-d'));
      $end_date   = Carbon::parse(formatDate($data['endDate'], 'd/m/Y', 'Y-m-d'));

      $expense_reports = Expense::where('user_id',authUser()->parent_id == null ? authUser()->id : authUser()->parent_id)
       ->whereBetween('expenses.created_at',[$start_date,$end_date])
       ->with(['category','subcategory'])->get();


       $total_expense = Expense::where('user_id',authUser()->parent_id == null ? authUser()->id : authUser()->parent_id)
       ->whereBetween('expenses.created_at',[$start_date,$end_date])
        ->sum('expenses.amount');



    if(count($expense_reports) >= 1){
      return response()->json([
        'total_expense'=>$total_expense,
        'expense_reports'=>$expense_reports,
      ],200);
    }
      return response()->json(['error'=>'expense not found'],404);

    }

     public function grossProfit(Request $request){

      $data = $request->all();

      $start_date = Carbon::parse(formatDate($data['startDate'], 'd/m/Y', 'Y-m-d'));
      $end_date   = Carbon::parse(formatDate($data['endDate'], 'd/m/Y', 'Y-m-d'));

       $total_purchase = Purchase::where('user_id',authUser()->parent_id == null ? authUser()->id : authUser()->parent_id)
       ->whereBetween('purchases.created_at',[$start_date,$end_date])
        ->sum('purchases.total_cost');

         $total_sales = Sale::where('user_id',authUser()->parent_id == null ? authUser()->id : authUser()->parent_id)
       ->whereBetween('sales.created_at',[$start_date,$end_date])
        ->sum('sales.total_cost');

         
      return response()->json([
        'total_sales'=> $total_sales,
        'total_purchase'=> $total_purchase,
        'gross_profit'=> $total_sales - $total_purchase,
      ],200);
  

     }

          public function netProfit(Request $request){

      $data = $request->all();

      $start_date = Carbon::parse(formatDate($data['startDate'], 'd/m/Y', 'Y-m-d'));
      $end_date   = Carbon::parse(formatDate($data['endDate'], 'd/m/Y', 'Y-m-d'));

       $total_purchase = Purchase::where('user_id',authUser()->parent_id == null ? authUser()->id : authUser()->parent_id)
       ->whereBetween('purchases.created_at',[$start_date,$end_date])
        ->sum('purchases.total_cost');

         $total_sales = Sale::where('user_id',authUser()->parent_id == null ? authUser()->id : authUser()->parent_id)
       ->whereBetween('sales.created_at',[$start_date,$end_date])
        ->sum('sales.total_cost');

         $total_expense = Expense::where('user_id',authUser()->parent_id == null ? authUser()->id : authUser()->parent_id)
       ->whereBetween('expenses.created_at',[$start_date,$end_date])
        ->sum('expenses.amount');
         
      return response()->json([
        'total_sales'=> $total_sales,
        'total_purchase'=> $total_purchase,
        'total_expense'=> $total_expense,
        'net_profit'=> $total_sales - $total_expense,
      ],200);
  

     }
}
