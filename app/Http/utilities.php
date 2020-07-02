<?php 

use Carbon\Carbon;
use Illuminate\Support\Str;


function authUser()
{
    return auth()->user();
}

function formatDate($date, $oldFormat, $newFormat)
{
    return Carbon::createFromFormat($oldFormat, $date)->format($newFormat);
}

function generateUUID()
{
    return Str::uuid()->toString();
}