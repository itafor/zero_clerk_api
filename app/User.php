<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_name','first_name','last_name','phone_number','country_id',
        'state_id','city_id','parent_id','role','area','address','email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    
     public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }

    public function state(){
        return $this->belongsTo(State::class,'state_id','id');
    }

    public function city(){
        return $this->belongsTo(City::class,'city_id','id');
    }

    public function role(){
        return $this->belongsTo(Role::class,'role_id','id');
    }

    public function myIndustry(){
            return $this->hasMany(MyIndustry::class,'user_id','id');
        }

    public function locations(){
            return $this->hasMany(Location::class);
        }

    public function customers(){
            return $this->hasMany(Customer::class);
        }

    public function suppliers(){
            return $this->hasMany(Supplier::class);
        }

   public function productCategories(){
            return $this->hasMany(ProductCategory::class);
        }

  public function productSubCategories(){
            return $this->hasMany(ProductSubCategory::class);
        }

   public function purchase(){
            return $this->hasMany(Purchase::class,'user_id','id');
        }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function subUsers()
    {
        return $this->hasMany(User::class, 'parent_id')->with(['country','state']);
    }


     public static function updateUser($userId,$data)
    {
        
     $user =  self::where('id', $userId)->update([
            'business_name' => $data['business_name'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone_number' => $data['phone_number'],
            'country_id' => $data['country_id'],
            'state_id' => $data['state_id'],
            //'role' => $data['role'],
            'address' => $data['address'],
            'area' => $data['area'],
        ]); 
 return $user;
    }

}
