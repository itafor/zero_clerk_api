<?php

namespace App\Http\Controllers;

use App\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth:api');
    }

      
  public function store(Request $request)
    {
    	$validatedData = $request->validate([
            'category_id'=>'required',
            'sub_category_id'=>'required',
            'tag'=>'required',
            'amount'=>'required',
        ]);

    	$expense = Expense::createNew($request->all());

    	if($expense){
    	return response(['message'=>'Expense created successfully!','expense'=>$expense],200);
    	}
    	return response(['error'=>'An attempt to create new expense failed!!'],403);
    }

     public function update(Request $request,$expense_id)
    {
    	$validatedData = $request->validate([
            'category_id'=>'required',
            'sub_category_id'=>'required',
            'tag'=>'required',
            'amount'=>'required',
        ]);

    	$data = $request->all();
    	$data['expense_id'] = $expense_id;
    	$expense = Expense::updateExpense($data);

    	if($expense){
    	return response(['message'=>'Expense updated successfully!'],200);
    	}
    	return response(['error'=>'An attempt to update expense failed!!'],403);
    }


     public function listExpenses(Request $request){
    	$expenses = Expense::where([
    		['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id]
    	])->with(['category','subcategory','user'])->get();

    	if(count($expenses) >=1 ){
    	return response()->json(['expenses'=>$expenses]);
    	}
    	return response(['error'=>'expenses not found!!'],401);
    }

     public function fetchExpenseById($expense_id){
    	$expense = Expense::where([
    		['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id],
    		['id',$expense_id]
    	])->with(['category','subcategory','user'])->first();

    	if($expense !=''){
    	return response()->json(['expense'=>$expense]);
    	}
    	return response(['error'=>'expense not found!!'],401);
    }

   public function destroyExpense($id){
    	$expense = Expense::find($id);

  if($expense){
  	$expense->delete();
    	return response()->json(['message'=>'expense deleted successfully']);
    	}
    	return response(['error'=>'Ooops!! expense not found'],401);
    }
}
