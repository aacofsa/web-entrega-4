<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Dog;
use App\Models\Interaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DogController extends Controller
{
    function create(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:255',
                'description' => 'required',
                'photo' => 'required',
                'gender' => 'required|in:Male,Female,Not Defined',
            ],   
        );
        if($validator->fails()){
            return response()->json([
                'error' => 'Bad request',
                'message' => $validator->errors()
            ], 400);
        }
        $valid = $validator->valid();
        $dog = Dog::factory()->create($valid);
        $dog->save();
        return response()->json($dog);
    }
    
    function findAll(){
        $dogs = Dog::all();
        return response()->json([
            "count" => count($dogs),
            "dogs" =>  $dogs
        ]);
    }

    function findOne($id) : Dog | JsonResponse{
        $dog = Dog::find($id);
        if(!$dog){
            return response()->json([
                "error"=> "Not Found",
                "message" => "Dog $id not found"
            ]);
        }
        return $dog;
    }

    function findRemains($id){
        $dog = $this->findOne($id);
        $interactions = $dog->interactions->all();
        $ids = array_map(
            fn($value) => $value->selected_dog,
            $interactions
        );
        array_push($ids, $id);
        $remains = Dog::whereNotIn('id', $ids)
            ->inRandomOrder()
            ->get()
            ->all();
            
        return response()->json([
            "count" => count($remains),
            "dogs" =>  $remains
        ]);
    }

    function likeDog($id, $likedDog){
        $validation = $this->validateInteraction($id, $likedDog);
        if($validation){
            return $validation;
        }
        $interaction = new Interaction([
            'action' => "like",
            'selected_dog' => $likedDog,
            'giver_dog' => $id
        ]);
        $interaction->save();
        return response()->json([
            "message" => "Dog $likedDog liked successfully"
        ]);
    }

    function rejectDog($id, $rejectedDog){
        $validation = $this->validateInteraction($id, $rejectedDog);
        if($validation){
            return $validation;
        }
        $interaction = new Interaction([
            'action' => "rejected",
            'selected_dog' => $rejectedDog,
            'giver_dog' => $id
        ]);
        $interaction->save();
        return response()->json([
            "message" => "Dog $rejectedDog rejected successfully"
        ]);
    }

    function findRejected($id){
        $interactions = Interaction::where('action',"rejected")
            ->where('giver_dog', $id)
            ->get()
            ->all();
        $dogs = array_map(
            fn($value) => $value->selected,
            $interactions
        );

        return response()->json([
            "count" => count($dogs),
            "dogs" =>  $dogs
        ]);
    }

    function findApproved($id){
        $interactions = Interaction::where('action',"like")
            ->where('giver_dog', $id)
            ->get()
            ->all();

        $dogs = array_map(
            fn($value) => $value->selected,
            $interactions
        );

        return response()->json([
            "count" => count($dogs),
            "dogs" =>  $dogs
        ]);
    }

    private function validateInteraction($id, $selected){
        if($id == $selected){
            return response()->json([
                "error"=> "Bad Request",
                "message" => "The dogs can't interact with themselves"
            ], 400);
        }

        $dog1 = $this->findOne($id);
        $dog2 = $this->findOne($selected);
        if($dog1 instanceof JsonResponse || $dog2 instanceof JsonResponse){
            return response()->json([
                "error"=> "Not Found",
                "message" => "Dog $id or $selected not found"
            ], 404);
        }

        $interaction = Interaction::where('giver_dog', $id)
            ->where('selected_dog', $selected)
            ->get()
            ->first();
        if($interaction){
            return response()->json([
                "error"=> "Conflict",
                "message" => "Already exist an interaction between those dogs"
            ], 409);
        }
    }

}
