<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Profile;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class ReviewController extends Controller
{
     public function store(Request $request){
        if(!$request->profile_id){
            return response()->json([
                'success' => false,
                'message' => 'il campo profile_id è obbligatorio',
                ]);
        }
        if(!Profile::where('id', $request->profile_id)->exists()){
            return response()->json([
                'success' => false,
                'message' => "l'id del profilo inserito non esiste",
                ]);
        }
        if($request->title && strlen($request->title) > 255){
            return response()->json([
                'success' => false,
                'message' => 'il campo title se esiste deve essere inferiore a 255 caratteri',
                ]);
        }
        if($request->body && strlen($request->body) > 65535){
            return response()->json([
                'success' => false,
                'message' => 'il campo body deve essere inferiore a 65535 caratteri',
                ]);
        }
        if(!$request->email){
            return response()->json([
                'success' => false,
                'message' => 'il campo email è obbligatorio',
                ]);
        }
        if(strlen($request->email) > 255){
            return response()->json([
                'success' => false,
                'message' => 'il campo email deve essere inferiore a 255 caratteri',
                ]);
        }
        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            return response()->json([
                'success' => false,
                'message' => 'il campo email deve essere una email valida',
                ]);
        }
        if(!$request->name){
            return response()->json([
                'success' => false,
                'message' => 'il campo name è obbligatorio',
                ]);
        }
        if(strlen($request->name) > 255){
            return response()->json([
                'success' => false,
                'message' => 'il campo name deve essere inferiore a 255 caratteri',
                ]);
        }
        if(!preg_match('/^[a-zA-Z\s]+$/', $request->name)){
            return response()->json([
                'success' => false,
                'message' => 'il campo name deve essere testuale',
                ]);
        }
        $now = Carbon::now();
        $new_review= new Review();
        $new_review->profile_id = $request->profile_id;
        if($request->title)$new_review->title= $request->title;
        if($request->body)$new_review->body = $request->body; 
        $new_review->email = $request->email;
        $new_review->name = $request->name;
        $new_review->created_at= $now;
        $new_review->updated_at = $now;
        $new_review->save();

        return response()->json([
            'success' => true,
            'message' => 'Recensione salvata con successo',
        ]);
     }
}