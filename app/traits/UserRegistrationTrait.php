<?php

namespace App\Traits;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\MenstruationPeriod;

trait UserRegistrationTrait {
     public function postForm($form_data) {
        try {

            $check_validation = Validator::make($form_data, [ 
                'first_name' => 'required|max:100',
                'last_name' => 'required|max:100',
                'email' => 'required|email|max:100',
                'menstruation_status' => 'required|boolean',
                'last_period_date' => 'required|date',
                'birthdate' => 'required|date|before:today'
            ]);

            if($check_validation->fails()) return response()->json(['success' => false, 'message' => 'Something went wrong, failed to save data. Please try again.'], 500);

            $user_data = isset($form_data['id'])
                ? User::findOrFail($form_data['id'])
                : new User;

            $user_data->fill([
                'first_name' => $form_data['first_name'],
                'middle_name' => $form_data['middle_name'] ?? null,
                'last_name' => $form_data['last_name'],
                'email' => $form_data['email'] ?? null,
                'birthdate' => date('Y-m-d', strtotime($form_data['birthdate'])),
                'menstruation_status' => $form_data['menstruation_status'] ?? null,
                'user_role_id' => 2, // 2 = user feminine and default role
                'remarks' => $form_data['remarks'] ?? null,
            ]);

            if(!isset($form_data['id'])) $user_data->fill(['password' => Hash::make('password')]); // if new user, set default password
            $user_data->save();

            if($user_data) {
                if(isset($form_data['edit_menstruation_period_id'])) {
                    $post_menstruation_period = MenstruationPeriod::findOrFail($form_data['edit_menstruation_period_id']);
                    $post_menstruation_period->menstruation_date = date('Y-m-d', strtotime($form_data['last_period_date']));
                    $post_menstruation_period->save();
                }
                else {
                    $post_menstruation_period = MenstruationPeriod::firstOrCreate([
                        'user_id' => $user_data->id,
                        'menstruation_date' => date('Y-m-d', strtotime($form_data['last_period_date'])),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'New feminine user successfully saved.',
                'feminine_count' => $this->feminineCount()
            ], 200);
        }
        catch(\Exception $e) {
            return response()->json(['status'=>'error', 'message'=>$e->getMessage()], 500);
        }
    }

    private function feminineCount() {
        return [
            'feminine_count' => User::where('user_role_id', 2)->count(),
            'active_feminine_count' => User::where('user_role_id', 2)->where('menstruation_status', 1)->count(),
            'inactive_feminine_count' => User::where('user_role_id', 2)->where('menstruation_status', 0)->count(),
        ];
    }
}