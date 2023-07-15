<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Traits\UserRegistrationTrait;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\User;
use App\Models\MenstruationPeriod;

class AdminController extends Controller {

    use UserRegistrationTrait;

    public function index() {

        if(!Auth::check()) return redirect()->route('login')->with('error', 'You don\'t have authorization to access admin portal, please try again.');

        $new_notification = $this->signupNotification();
        $new_period_notification = $this->newMenstrualPeriodNotification();
        $count = $this->feminineCount();

        return view('admin/dashboard', compact('count', 'new_notification', 'new_period_notification'));
    }

    public function feminineList() {
        $new_notification = $this->signupNotification();
        $new_period_notification = $this->newMenstrualPeriodNotification();
        return view('admin/feminine/index', compact('new_notification', 'new_period_notification'));
    }

    public function postFeminine(Request $request) {
        return $this->postForm($request->all());
    }

    public function deleteFeminie(Request $request) {
        try {
            $user = User::findOrFail($request->id);

            if($user) {
                $user->last_periods()->delete();
                $user->delete();
            }

            return response()->json(['status' => 'success', 'message' => 'Feminine successfully deleted.']);
        }
        catch(\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong, please try again.']);
        }
    }

    public function confirmFeminine(Request $request) {
        try {
            $post_confirm = User::findOrFail($request->id);
            $post_confirm->is_active = 1;
            $post_confirm->save();

            return response()->json(['status' => 'success', 'message' => 'Feminine successfully confirmed.', 'new_notification_count' => count($this->signupNotification())]);
        }
        catch(\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong, please refresh your browser and try again.']);
        }
        
    }
    
    public function calendarIndex() {
        $new_notification = $this->signupNotification();
        $new_period_notification = $this->newMenstrualPeriodNotification();
        return view('admin/calendar/index', compact('new_notification', 'new_period_notification'));
    }

    public function calendarData() {

        // only the active accounts will be processed in the calendar
        $user_list = User::where('user_role_id', 2)
            ->where('is_active', 1)
            ->get(['id', 'first_name', 'last_name']);

        $last_period_arr = [];
        foreach($user_list as $user_key => $user) {
            $last_period_arr[$user_key]['name'] = $user->last_name.', '.$user->first_name;
            $last_period_arr[$user_key]['period_date'] = $user->last_periods()->first();
        }

        return response()->json($last_period_arr);
    }

    public function feminineData() {

        $feminine_arr = User::with('last_periods')
            ->where('user_role_id', 2)
            ->orderBy('last_name', 'ASC')
            ->get(['id', 'first_name', 'last_name', 'middle_name', 'email', 'birthdate', 'menstruation_status', 'is_active', 'remarks'])
            ->toArray();

        $row_count = 0;
        foreach($feminine_arr as $feminine_key => $feminine) {

            $full_name = $feminine['last_name'].', '.$feminine['first_name'].' '.$feminine['middle_name'];

            $feminine_arr[$feminine_key]['row_count'] = ++$row_count;
            $feminine_arr[$feminine_key]['full_name'] = $full_name;
            $feminine_arr[$feminine_key]['menstruation_status'] = '<span class="text-' . ($feminine['menstruation_status'] === 1 ? 'success' : 'danger') . '"><strong>&bull;</strong> ' . ($feminine['menstruation_status'] === 1 ? 'Active' : 'Inactive') . '</span>';

            if($feminine['is_active'] === 1) {
                $feminine_arr[$feminine_key]['is_active'] = '<span class="text-success"><strong>&bull;</strong> Verified</span>';
            }
            else {
                $feminine_arr[$feminine_key]['is_active'] = '<button type="button" class="btn btn-sm btn-success verify_account" id="notif_'. $feminine['id'] .'" data-id="'. $feminine['id'] .'" data-full_name="'. $full_name .'" ><i class="fa-solid fa-user-check"></i> Verify Account</button';
            }

            $feminine_arr[$feminine_key]['action'] = '
                <button type="button" class="btn btn-sm btn-secondary" id="period_notif_'. $feminine['id'] .'"
                    data-full_name="'.$full_name.'"
                    data-email="'.$feminine['email'].'"
                    data-birthdate="'. ($feminine['birthdate'] ? date('F j, Y', strtotime($feminine['birthdate'])) : 'N/A') .'"
                    data-menstruation_status="'.$feminine['menstruation_status'].'"
                    data-is_active="'.$feminine['is_active'].'"
                    data-remarks="'.($feminine['remarks'] ?? 'N/A').'"
                    data-last_period_dates='.(json_encode(array_slice($feminine['last_periods'], 0, 3)) ?? 'N/A').'
                    data-toggle="modal" data-target="#viewFeminineModal">
                        <i class="fa-solid fa-magnifying-glass"></i> View
                </button>
                
                <button type="button" class="btn btn-sm btn-primary"
                    data-id="'.$feminine['id'].'"
                    data-first_name="'.$feminine['first_name'].'"
                    data-last_name="'.$feminine['last_name'].'"
                    data-middle_name="'.$feminine['middle_name'].'"
                    data-email="'.$feminine['email'].'"
                    data-birthdate="'. ($feminine['birthdate'] ? date('m/d/Y', strtotime($feminine['birthdate'])) : null) .'"
                    data-menstruation_status="'.$feminine['menstruation_status'].'"
                    data-remarks="'.($feminine['remarks'] ?? null).'"
                    data-last_period_date="' . (empty($feminine['last_periods']) ? null : date('m/d/Y', strtotime($feminine['last_periods'][0]['menstruation_date']))) . '"
                    data-menstruation_period_id="'. (empty($feminine['last_periods']) ? null : $feminine['last_periods'][0]['id']) .'"
                    data-toggle="modal" data-target="#editFeminineModal">
                        <i class="fa-solid fa-user-pen"></i> Edit
                </button>

                <button type="button" class="btn btn-sm btn-danger delete_record" data-id="'.$feminine['id'].'"><i class="fa-solid fa-trash"></i> Delete</button>
            ';
        }

        return response()->json(['data'=>$feminine_arr, "recordsFiltered"=>count($feminine_arr), 'recordsTotal'=>count($feminine_arr)]);
    }

    public function accountSettings() {
        $new_notification = $this->signupNotification();
        $new_period_notification = $this->newMenstrualPeriodNotification();
        return view('admin/account_settings/index', compact('new_notification', 'new_period_notification'));
    }

    public function accountReset(Request $request) {
        try {
            $user = User::findOrFail($request->id);
            $user->password = Hash::make('password'); // reset the password to "password"
            $user->save();

            return response()->json(['status' => 'success', 'message' => 'Password successfully reset.']);
        }
        catch(\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'User not found, please try again.']);
        }
        catch(\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong, please try again.']);
        }
    }

    public function accountData() {
        
        $feminine_arr = User::where('user_role_id', 2)
            ->where('is_active', 1)
            ->orderBy('last_name', 'ASC')
            ->get(['id', 'first_name', 'last_name', 'middle_name', 'email', 'menstruation_status'])
            ->toArray();

        $row_count = 0;
        foreach($feminine_arr as $feminine_key => $feminine) {

            $full_name = $feminine['last_name'].', '.$feminine['first_name'].' '.$feminine['middle_name'];

            $feminine_arr[$feminine_key]['row_count'] = ++$row_count;
            $feminine_arr[$feminine_key]['full_name'] = $full_name;
            $feminine_arr[$feminine_key]['menstruation_status'] = '<span class="text-' . ($feminine['menstruation_status'] === 1 ? 'success' : 'danger') . '"><strong>&bull;</strong> ' . ($feminine['menstruation_status'] === 1 ? 'Active' : 'Inactive') . '</span>';

            $feminine_arr[$feminine_key]['action'] = '
                <button type="button" class="btn btn-sm btn-primary reset_password" data-id="'.$feminine['id'].'" data-full_name="'.$full_name.'"><i class="fa-solid fa-key"></i> Reset Password</button>
            ';
        }

        return response()->json(['data'=>$feminine_arr, "recordsFiltered"=>count($feminine_arr), 'recordsTotal'=>count($feminine_arr)]);
    }

    public function postSeenPeriodNotification(Request $request) {
        if($request->id) {
            try {
                $post_notification_seen = MenstruationPeriod::findOrFail($request->id);
                $post_notification_seen->is_seen = 1;
                $post_notification_seen->save();

                // return $post_notification_seen->id;
                return response()->json(['status' => 'success', 'id' => $post_notification_seen->id, 'new_notification_count' => count($this->newMenstrualPeriodNotification())]);
            }
            catch(\ModelNotFoundException $e) {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong.']);
            }
        }
    }
}
