<?php
use Settings\Route;
Route::group(function (){
    Route::get("/", "Home::index"); // url /
    //Route::get("/(int id=userid::id)a(int id=userid::id)", "Home::home", "Home::index"); url /1a1
}, "Group::index");
