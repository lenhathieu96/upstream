<?php

namespace App\Http\Controllers;

use App\Http\Requests\CooperativeRequest;
use App\Models\Cooperative;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Str;

class CooperativeController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->input('name');
        $formationDate = $request->input('formation_date');
        $cooperativeCode = $request->input('cooperative_code');
        $status = $request->input('status');

        $cooperativeQuery = Cooperative::orderByDesc('id');


        if ($name) {
            $cooperativeQuery->where('name', 'like', '%' . $name . '%');
        }

        if ($formationDate) {
            $cooperativeQuery->where('formation_date', $formationDate);
        }

        if ($cooperativeCode) {
            $cooperativeQuery->where('cooperative_code', $cooperativeCode);
        }

        if ($status) {
            $cooperativeQuery->where('status', $status);
        }

        $cooperatives = $cooperativeQuery->paginate()->appends($request->except('page'));

        return view('cooperative.index', compact('cooperatives', 'name', 'formationDate', 'cooperativeCode', 'status'));
    }

    public function create()
    {
        $cooperative = new Cooperative();

        return $this->edit($cooperative);
    }

    public function edit(Cooperative $cooperative)
    {
        return view('cooperative.form', compact('cooperative'));
    }

    public function show(Cooperative $cooperative)
    {
        return redirect()->route('cooperative.edit', ['cooperative' => $cooperative]);
    }
    
    public function store(CooperativeRequest $cooperativeRequest)
    {
        return $this->createOrUpdate($cooperativeRequest, new Cooperative());
    }


    public function update(CooperativeRequest $cooperativeRequest, Cooperative $cooperative)
    {
        return $this->createOrUpdate($cooperativeRequest, $cooperative);
    }

    private function createOrUpdate(CooperativeRequest $cooperativeRequest, Cooperative $cooperative)
    {
        $isNewCooperative = empty($cooperative->id);

        // update email and phone number
        if (!$isNewCooperative && !empty($cooperative->email)) {
            $newEmail = $cooperativeRequest->email ?? $cooperative->email;
            $phone = !empty($cooperativeRequest->phone_number) ? $cooperativeRequest->phone_number : $cooperative->phone_number;

            $this->updateEnterpriseUser($cooperative->email, $newEmail, $phone);
            $this->upstreamUpdateUserFromCooperative($cooperative, $newEmail, $phone);
        }

        $cooperative->name = $cooperativeRequest->name;
        $cooperative->formation_date = $cooperativeRequest->formation_date;
        $cooperative->status = $cooperativeRequest->status;
        $cooperative->email = $cooperativeRequest->email;
        $cooperative->phone_number = $cooperativeRequest->phone_number;
        $cooperative->services = implode(',', $cooperativeRequest->services);
        $cooperative->touch();
        $cooperative->save();

        if ($cooperative) {
            $cooperative->generateCooperativeCode();
            $cooperative->save();
        }

        // create enterprise
        if ($isNewCooperative) {
            $this->createEnterprise($cooperative);
            $this->upstreamCreateUserFromCooperative($cooperative);
        }

        return redirect()->route('cooperative.edit', ['cooperative' => $cooperative->id])->with([
            'success' => $isNewCooperative ? 'Cooperative has been created!' : 'Cooperative has been updated!',
        ]);
    }

    public function destroy(Cooperative $cooperative)
    {
        abort(500);
    }

    public function createEnterprise(Cooperative $cooperative)
    {
        $storeEnterpriseUrl = config('upstream.HEROMARKET_URL') . '/api/v2/auth/store-enterprise-from-cooperative';

        try {
            $bodyData = [
                'upstream_cooperative_id' => $cooperative->id,
                'email' => $cooperative->email,
                'phone' => $cooperative->phone_number,
                'legal_name' => $cooperative->name,
                'bussiness_name' => $cooperative->name,
            ];
            $response = Http::withOptions(['verify' => false])->post($storeEnterpriseUrl, $bodyData);
            $response = json_decode($response->getBody(), true);
            if (isset($response['message'])) {
                \Log::info('cooperative migrate: ' . $response['message']);
            }
        } catch (\Exception $exception) {  
            \Log::info($exception->getMessage());
        }
    }

    public function updateEnterpriseUser($oldEmail, $newEmail, $newPhone) 
    {
        $updateEnterpriseUserUrl = config('upstream.HEROMARKET_URL') . '/api/v2/auth/update-enterprise-user';

        try {
            $bodyData = [
                'oldEmail' => $oldEmail,
                'newEmail' => $newEmail,
                'newPhone' => $newPhone,
            ];
            $response = Http::withOptions(['verify' => false])->post($updateEnterpriseUserUrl, $bodyData);
            $response = json_decode($response->getBody(), true);
            if (isset($response['message'])) {
                \Log::info('cooperative update: ' . $response['message']);
            }
        } catch (\Exception $exception) {  
            \Log::info($exception->getMessage());
        }
    }

    public function upstreamCreateUserFromCooperative(Cooperative $cooperative)
    {
        $user = new User();
        $user->name = $cooperative->name;
        $user->user_type = 'cooperative';
        $user->username = \Str::slug($cooperative->name, '') . rand(1000, 9999);
        $user->email = $cooperative->email;
        $user->password = Hash::make('12345678');
        $user->phone_number = $this->isPhoneExists($cooperative->phone_number) ? ('duplicate_' . $cooperative->phone_number . '_' . uniqid()) : $cooperative->phone_number;
        $user->email_verified_at = now();
        $user->banned = 0;
        $user->save();

        $cooperative->user_id = $user->id;
        $cooperative->save();
    }

    public function upstreamUpdateUserFromCooperative(Cooperative $cooperative, $newEmail, $newPhone)
    {
        $user = $cooperative->user;
        if ($user) {
            $user->email = $newEmail;
            $user->phone_number = $this->isPhoneExists($newPhone) ? ('duplicate_' . $newPhone . '_' . uniqid()) : $newPhone;
            $user->save();
        }
    }

    public function isPhoneExists($phoneNumber)
    {
        $phone1 = (int) $phoneNumber;
        $phone2 = '0' . $phone1;

        return User::where('phone_number', $phone1)->orWhere('phone_number', $phone2)->exists();
    }
}
