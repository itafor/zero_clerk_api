<?php

namespace App\Http\Controllers;

use App\City;
use App\Country;
use App\Location;
use App\State;
use Illuminate\Http\Request;

class LocationController extends Controller
{
  
  public function __construct()
    {
        $this->middleware('auth:api');
    }
      
  public function store(Request $request)
    {
    	$validatedData = $request->validate([
            'name'=>'required',
            'type'=>'required',
            'area'=>'required',
            'street_address'=>'required',
            'country_id'=>'required',
            'state_id'=>'required',
        ]);

    	$location = Location::createNew($request->all());

    	if($location){
    	return response(['message'=>'location created successfully!','location'=>$location],200);
    	}
    	return response(['error'=>'An attempt to create new location failed!!'],403);
    }

     public function update(Request $request,$location_id)
    {
    	$validatedData = $request->validate([
            'name'=>'required',
            'type'=>'required',
            'area'=>'required',
            'street_address'=>'required',
            'country_id'=>'required',
            'state_id'=>'required',
        ]);

    	$data = $request->all();
    	$data['location_id'] = $location_id;
    	$location = Location::update_location($data);

    	if($location){
    	return response(['message'=>'Location updated successfully!'],200);
    	}
    	return response(['error'=>'An attempt to update location failed!!'],403);
    }


     public function listlocations(Request $request){
    	$locations = Location::where([
    		['user_id',authUser()->id]
    	])->with(['country','state','user'])->get();

    	if(count($locations) >=1 ){
    	return response()->json(['locations'=>$locations]);
    	}
    	return response(['error'=>'locations not found!!'],401);
    }

     public function fetchlocationById($location_id){
    	$location = Location::where([
    		['user_id',authUser()->id],
    		['id',$location_id]
    	])->with(['country','state','user'])->first();

    	if($location !=''){
    	return response()->json(['location'=>$location]);
    	}
    	return response(['error'=>'location not found!!'],401);
    }

   public function destroylocation($id){
    	$location = Location::find($id);

  if($location){
  	$location->delete();
    	return response()->json(['message'=>'location deleted successfully']);
    	}
    	return response(['error'=>'Ooops!! location not found'],401);
    }


      /**
     * @return Json
     */
    public function getCountries() {
        $countries = Country::get(['id', 'sortname', 'name', 'phonecode']);
        return response()->json(['countries'=>$countries], 200);
    }

    /**
     * @param $id
     * @return Json
     */
    public function getCountry($id) {
        $country = Country::where('id', $id)->get(['id', 'sortname', 'name', 'phonecode']);
        return response()->json(['country',$country],200);
    }

    /**
     * @return Json
     */
    public function getStates() {
        $states = State::get(['id', 'name', 'country_id']);
        return response()->json(['states'=>$states], 200);
    }

    /**
     * @param $id
     * @return Json
     */
    public function getState($id) {
        $states = State::where('id', $id)->get(['id', 'name', 'country_id']);
        return response()->json(['state'=>$states], 200);
    }

    /**
     * @return Json
     */
    public function getCities() {
        $cities = City::get(['id', 'name', 'state_id']);
        return response()->json($cities, 200);
    }

    /**
     * @param $id
     * @return Json
     */
    public function getCity($id) {
        $country = City::where('id', $id)->get(['id', 'name', 'state_id']);
        return response()->json($country, 200);
    }

    /**
     * @param $countryId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatesByCountry($countryId) {
        $countryStates = State::where('country_id', $countryId)->get(['id', 'name']);
        return response()->json(['countrystates'=>$countryStates], 200);
    }

    /**
     * @param $stateId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCitiesByStates($stateId) {
        $stateCities = City::where('state_id', $stateId)->get(['id', 'name']);
        return response()->json($stateCities, 200);
    }
}
