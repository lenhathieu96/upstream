<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffRequest;
use App\Models\Cooperative;
use App\Models\Staff;
use App\Models\StaffCooperative;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected function validator(array $data)
    {
        
    }

    public function index(Request $request)
    {
        $staffs = Staff::latest()->paginate(12);

        $isEditableByCurrentUser = Gate::allows('super-admin');

        return view('staff.index', compact('staffs', 'isEditableByCurrentUser'));
    }

    public function create()
    {
        $staff = new Staff();

        return $this->edit($staff);
    }

    public function edit(Staff $staff)
    {
        Gate::authorize('super-admin');

        $currentCooperative = $staff->cooperatives()->where('status', 'active')->get();
        $notUseCooperative = Cooperative::whereNull('staff_id')->where('status', 'active')->get();
        $availableCooperatives = $currentCooperative->merge($notUseCooperative);
        
        return view('staff.form', compact('staff', 'availableCooperatives', 'currentCooperative'));
    }

    public function store(StaffRequest $staffRequest)
    {
        return $this->createOrUpdate($staffRequest, new Staff());
    }


    public function update(StaffRequest $staffRequest, Staff $staff)
    {
        return $this->createOrUpdate($staffRequest, $staff);
    }

    private function createOrUpdate(StaffRequest $staffRequest, Staff $staff)
    {
        $isNewStaff = empty($staff->id);

        if (!$isNewStaff) {
            // process update staff
            $staff->user_type = $staffRequest->user_type;
            $staff->first_name = $staffRequest->first_name;
            $staff->last_name = $staffRequest->last_name;
            $staff->gender = $staffRequest->gender;
            $staff->email = $staffRequest->email;
            $staff->phone_number = $staffRequest->phone_number;
            $staff->status = $staffRequest->status;
            $staff->save();

            $user = User::findOrFail($staff->user_id);
            $user->user_type = $staffRequest->user_type; 
            $user->phone_number = $staffRequest->phone_number; 
            if (!empty($staffRequest->password)) {
                $user->password = Hash::make($staffRequest->password); 
            }
            $user->save();

        } else {
            // process with create staff

            $username = Str::slug($staffRequest->first_name . $staffRequest->last_name, '');
            if ($this->isExistUsername($username)) {
                $username = $username . rand(10000, 99999);
            }

            $user = new User(); 
            $user->name = $staffRequest->first_name . " " . $staffRequest->last_name; 
            $user->user_type = $staffRequest->user_type; 
            $user->username = $username; 
            $user->email = $staffRequest->email; 
            $user->password = Hash::make($staffRequest->password); 
            $user->phone_number = $staffRequest->phone_number; 
            $user->email_verified_at = ""; 
            $user->save();

            $data_staff = [
                'user_id'=>$user->id,
                'user_type'=>$staffRequest->user_type,
                'first_name'=>$staffRequest->first_name,
                'last_name'=>$staffRequest->last_name,
                'gender'=>$staffRequest->gender,
                'email'=>$staffRequest->email,
                'phone_number'=>$staffRequest->phone_number,
                'status'=>$staffRequest->status,
            ];

            $staff = Staff::create($data_staff);

            if ($staff) {
                $staff->faAccount()->create([
                    'typee' => 2,
                    'acc_type' => 'FOA'
                ]);
            }
        }

        // delete old cooperative of staff
        Cooperative::where('staff_id', $staff->id)->update(['staff_id' => null]);

        // delete old warehouse of staff
        Warehouse::where('staff_id', $staff->id)->update(['staff_id' => null]);

        if ($staff->user_type == 'staff') {
            // map new cooperatives of staff
            foreach($staffRequest->cooperative_ids ?? [] as $cooperativeId) {
                Cooperative::find($cooperativeId)->update(['staff_id' => $staff->id]);
            }
        } else if ($staff->user_type == 'warehouse_operator') {
            // map new warehouse of staff
            Warehouse::where('id', $staffRequest->warehouse_id)->update(['staff_id' => $staff->id]);
        }

        $message = $isNewStaff ? 'Staff created successfull' : 'Staff updated successfull';

        return redirect()->route('staff.edit', ['staff' => $staff->id])->with('success', $message);
    }

    private function isExistUsername($username)
    {
        return User::where('username', $username)->exists();
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        return redirect()->route('staff.edit', ['staff' => $staff]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        abort(500);
    }
}