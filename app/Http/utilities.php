<?php 

use Carbon\Carbon;


function authUser()
{
    return auth()->user();
}

function formatDate($date, $oldFormat, $newFormat)
{
    return Carbon::createFromFormat($oldFormat, $date)->format($newFormat);
}