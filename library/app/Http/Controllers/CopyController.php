<?php

namespace App\Http\Controllers;

use App\Models\Copy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CopyController extends Controller
{
    public function index(){
        return Copy::all();
    }

    public function show($id){
        return Copy::find($id);
    }

    public function destroy($id){
        Copy::find($id)->delete();
    }

    public function update(Request $request, $id){
        $copy = Copy::find($id);
        $copy->book_id = $request->book_id;
        $copy->hardcovered = $request->hardcovered;
        $copy->status = $request->status;
        $copy->publication = $request->publication;
        $copy->save();
    }

    public function store(Request $request){
        $copy = new Copy();
        $copy->book_id = $request->book_id;
        $copy->hardcovered = $request->hardcovered;
        $copy->status = $request->status;
        $copy->publication = $request->publication;
        $copy->save();
        
    }

    public function copyBookLending(){
        //több függvényt is használhatunk
        return Copy::with('book')->with('lending')->get();
    }

    public function moreLending($copy_id, $db){

        $user = Auth::user();
        $lending = DB::table('lending as l')
        -> selectRaw('count(1.copy_id) as number_of_copies, 1.copy_id')
        -> join('copies as c', '1.copy_id', '=', 'c.copy_id')
        -> where('1.user_id', $user->id)
        -> where('1.copy_id', $copy_id)
        -> groupBy('1.copy_id')
        -> having('number_of_copies', '>=', $db)
        -> get();

        return $lending;
    }

}
