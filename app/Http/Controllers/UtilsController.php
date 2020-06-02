<?php

namespace App\Http\Controllers;

use App\Country;
use App\Industry;
use App\State;
use Illuminate\Http\Request;

class UtilsController extends Controller
{
    function getCountries()
   {
    $countries = Country::orderBy('name')->get();

    return response()->json(['countries'=>$countries],200);

}

function getStates($countryId)
{
    $states = State::where('country_id', $countryId)
    ->orderBy('name')->get();

    return response()->json(['states'=>$states],200);

}

function getIndustries()
{
    $industries = Industry::orderBy('name')->get();

    return response()->json(['Industries'=>$industries],200);
	}
}
