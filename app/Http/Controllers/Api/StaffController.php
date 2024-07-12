<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected function validator(array $data)
    {
        
    }

    public function index()
    {
        $staff = Auth::user()->staff;
        return response()->json([
            'result' => true,
            'message' => 'Get All Staff Successfully',
            'data' =>[
                'staff_data'=> $staff
            ]
        ]);
    }

}