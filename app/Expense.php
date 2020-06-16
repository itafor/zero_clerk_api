<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
     use SoftDeletes;

      protected $fillable = [
       'user_id', 'category_id','sub_category_id','tag','description','amount','date', 
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

      public static function createNew($data){

        $expense = self::create([
            'category_id' => $data['category_id'],
            'sub_category_id' =>  $data['sub_category_id'],
            'tag' => $data['tag'],
            'description' => $data['description'],
            'user_id' => authUser()->parent_id == null ? authUser()->id : authUser()->parent_id,
            'amount' => $data['amount'],
            'expense_date' => Carbon::parse(formatDate($data['expense_date'], 'd/m/Y', 'Y-m-d')),
        ]); 
        
        return $expense;
    }

    public static function updateExpense($data)
    {
      $expense = self::where([
        ['id',$data['expense_id']],
        ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id]
    ])->update([
          'category_id' => $data['category_id'],
            'sub_category_id' =>  $data['sub_category_id'],
            'tag' => $data['tag'],
            'description' => $data['description'],
            'amount' => $data['amount'],
            'expense_date' => Carbon::parse(formatDate($data['expense_date'], 'd/m/Y', 'Y-m-d')),
        ]); 

      return $expense;

    }
}
