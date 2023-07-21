<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Traits\UserRegistrationTrait;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\User;
use App\Models\MenstruationPeriod;
use App\Models\FeminineHealthWorkerGroup;

class BarangayHealthWorkerController extends Controller
{
    use UserRegistrationTrait;

    public function index() {
        
        $assign_feminine_count = FeminineHealthWorkerGroup::where('health_worker_id', Auth::user()->id)->count();
        $count = $this->healthWorkerFeminineCount();

        return view('health_worker.dashboard', compact('assign_feminine_count', 'count'));
    }

    public function feminineList() {
        return view('health_worker.feminine.index');
    }

    public function postFeminine(Request $request) {
        return $this->postForm($request->all());
    }

    public function deleteFeminie(Request $request) {
        try {
            $user = User::findOrFail($request->id);

            if($user) {
                $remove_assigned_feminine = FeminineHealthWorkerGroup::where('feminine_id', $user->id)
                    ->where('health_worker_id', Auth::user()->id)
                    ->delete();
            }

            return response()->json(['status' => 'success', 'message' => 'Feminine successfully removed.']);
        }
        catch(\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong, please try again.']);
        }
    }

    public function feminineData() {

        $feminine_arr = FeminineHealthWorkerGroup::join('users', 'users.id', '=', 'feminine_health_worker_groups.feminine_id')
            ->where('feminine_health_worker_groups.health_worker_id', Auth::user()->id)
            ->where('users.user_role_id', 2)
            ->orderBy('users.last_name', 'ASC')
            ->get(['users.id', 'users.first_name', 'users.last_name', 'users.middle_name', 'users.address', 'users.email', 'users.birthdate', 'users.menstruation_status', 'users.is_active', 'users.remarks'])
            ->toArray();

        $row_count = 0;
        foreach($feminine_arr as $feminine_key => $feminine) {

            $full_name = $feminine['last_name'].', '.$feminine['first_name'].' '.$feminine['middle_name'];
            $last_period_list = MenstruationPeriod::where('user_id', $feminine['id'])->orderBy('menstruation_date', 'DESC')->take(3)->get(['id', 'menstruation_date']);

            $feminine_arr[$feminine_key]['row_count'] = ++$row_count;
            $feminine_arr[$feminine_key]['full_name'] = $full_name;
            $feminine_arr[$feminine_key]['menstruation_status'] = '<span class="text-' . ($feminine['menstruation_status'] === 1 ? 'success' : 'danger') . '"><strong>&bull;</strong> ' . ($feminine['menstruation_status'] === 1 ? 'Active' : 'Inactive') . '</span>';

            if($feminine['is_active'] === 1) {
                $feminine_arr[$feminine_key]['is_active'] = '<span class="text-success"><strong>&bull;</strong> Verified</span>';
            }
            else {
                $feminine_arr[$feminine_key]['is_active'] = '<span class="text-warning"><strong>&bull;</strong> Pending</span>';
            }

            $feminine_arr[$feminine_key]['action'] = '
                <button type="button" class="btn btn-sm btn-secondary" id="period_notif_'. $feminine['id'] .'"
                    data-full_name="'.$full_name.'"
                    data-email="'.$feminine['email'].'"
                    data-address="'.$feminine['address'].'"
                    data-birthdate="'. ($feminine['birthdate'] ? date('F j, Y', strtotime($feminine['birthdate'])) : 'N/A') .'"
                    data-menstruation_status="'.$feminine['menstruation_status'].'"
                    data-is_active="'.$feminine['is_active'].'"
                    data-remarks="'.($feminine['remarks'] ?? 'N/A').'"
                    data-last_period_dates='.(json_encode($last_period_list) ?? 'N/A').'
                    data-toggle="modal" data-target="#viewFeminineModal">
                        <i class="fa-solid fa-magnifying-glass"></i> View
                </button>
                
                <button type="button" class="btn btn-sm btn-primary"
                    data-id="'.$feminine['id'].'"
                    data-first_name="'.$feminine['first_name'].'"
                    data-last_name="'.$feminine['last_name'].'"
                    data-middle_name="'.$feminine['middle_name'].'"
                    data-email="'.$feminine['email'].'"
                    data-address="'.$feminine['address'].'"
                    data-birthdate="'. ($feminine['birthdate'] ? date('m/d/Y', strtotime($feminine['birthdate'])) : null) .'"
                    data-menstruation_status="'.$feminine['menstruation_status'].'"
                    data-remarks="'.($feminine['remarks'] ?? null).'"
                    data-last_period_date="' . (count($last_period_list) != 0 ? date('m/d/Y', strtotime($last_period_list->first()->menstruation_date)) : null ) . '"
                    data-menstruation_period_id="'. (count($last_period_list) != 0 ? $last_period_list->first()->id : null) .'"
                    data-toggle="modal" data-target="#editFeminineModal">
                        <i class="fa-solid fa-user-pen"></i> Edit
                </button>

                <button type="button" class="btn btn-sm btn-warning text-white delete_record" data-id="'.$feminine['id'].'"><i class="fa-solid fa-user-xmark"></i> Unassigned</button>
            ';
        }

        return response()->json(['data'=>$feminine_arr, "recordsFiltered"=>count($feminine_arr), 'recordsTotal'=>count($feminine_arr)]);
    }

    public function calendarIndex() {
        return view('health_worker/calendar/index');
    }

    public function calendarData() {

        // only the active accounts and those who are under to this bhw will be processed in the calendar
        $user_list = FeminineHealthWorkerGroup::join('users', 'users.id', '=', 'feminine_health_worker_groups.feminine_id')
            ->where('feminine_health_worker_groups.health_worker_id', Auth::user()->id)
            ->where('users.user_role_id', 2)
            ->where('users.is_active', 1)
            ->get(['users.id', 'users.first_name', 'users.last_name']);

        $last_period_arr = [];
        foreach($user_list as $user_key => $user) {
            $last_period_arr[$user_key]['name'] = 'Active: ' . $user->last_name.', '.$user->first_name;
            $last_period_arr[$user_key]['period_date'] = User::findOrfail($user->id)->last_periods()->first();
        }

        return response()->json($last_period_arr);
    }

    public function healthWorkerFeminineList() {

        $assigned_feminine_list = $this->assignedFeminineList(Auth::user()->id);

        $feminine_list = User::where('user_role_id', 2)
            ->where('is_active', 1)
            ->whereNotIn('id', $assigned_feminine_list->pluck('id')->toArray())
            ->select(['id',  \DB::raw("CONCAT(users.last_name,', ',users.first_name) AS full_name")])->get();

        $data = [
            'assigned_feminine_list' => $assigned_feminine_list,
            'feminine_list' => $feminine_list
        ];

        return response()->json($data);
    }

    public function postAssignFeminine(Request $request) {
        if($request['feminine_id'] && count($request['feminine_id']) != 0) {
            foreach($request['feminine_id'] as $user_id) {
                $post_assign_health_worker = FeminineHealthWorkerGroup::firstOrCreate([
                    'feminine_id' => $user_id,
                    'health_worker_id' => $request->id
                ]);
            }
            return response()->json(['status' => 'success', 'message' => count($request['feminine_id']).' Feminine successfully assigned.']);
        }
        else {
            return response()->json(['status' => 'error', 'message' => 'Please select at least one feminine.']);
        }
    }

    public function accountSettings() {
        abort(503);
    }

    private function assignedFeminineList($health_worker_id) {
        return $assigned_feminine_list = FeminineHealthWorkerGroup::where('health_worker_id', $health_worker_id)
            ->with('feminine:id,last_name,first_name')
            ->get(['feminine_id', 'feminine_health_worker_groups.id as feminine_health_worker_group_id'])
            ->map(function ($item) {
                return [
                    'id' => $item->feminine->id,
                    'feminine_health_worker_group_id' => $item->feminine_health_worker_group_id,
                    'full_name' => $item->feminine->full_name(),
                ];
            });
    }
}
