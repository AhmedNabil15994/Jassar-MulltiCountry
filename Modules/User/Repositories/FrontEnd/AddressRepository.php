<?php

namespace Modules\User\Repositories\FrontEnd;

use Modules\User\Entities\Address;
use DB;

class AddressRepository
{

    function __construct(Address $address)
    {
        $this->address = $address;
    }

    public function getAllByUsrId()
    {
        $addresses = $this->address->where('user_id', auth()->id())->with(['state' => function ($q) {
            $q->with(['city' => function ($q) {
                $q->with('country');
            }]);
        }])->orderBy('id', 'DESC')->get();
        return $addresses;
    }

    public function findById($id)
    {
        $address = $this->address->where('user_id', auth()->id())->with('state')->find($id);
        return $address;
    }

    public function findByIdWithoutAuth($id)
    {
        $address = $this->address->with('state')->find($id);
        return $address;
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {
            $data = [
                'email' => $request['email'] ?? auth()->user()->email,
                'username' => $request['username'] ?? auth()->user()->name,
                'phone_code' => $request['phone_code'],
                'mobile' => $request['mobile'] ?? auth()->user()->mobile,
                'address' => $request['address'],
                'block' => $request['block'] ?? null,
                'note' => $request['note'] ?? null,
                'is_default' => $request->default ? 1 : 0,
                'street' => $request['street'] ?? null,
                'building' => $request['building'] ?? null,
                'user_id' => auth()->id(),
                'state_id' => $request['addressType'] == 'local' ? $request['state_id'] : null,
                'address_type' => $request['addressType'],
                'json_data' => $request['jsonData'] ?? null,
                'avenue' => $request['avenue'] ?? null,
                'floor' => $request['floor'] ?? null,
                'flat' => $request['flat'] ?? null,
                'automated_number' => $request['automated_number'] ?? null
            ];

            if(session()->has('address_id')){
                $address = $this->address->find(session()->get('address_id'));
                if($address){
                    $address->update($data);
                }else{
                    session()->forget('address_id');
                    $address = $this->address->create($data);
                }
            }else{
                $address = $this->address->create($data);
            }

            if($address->is_default)
                auth()->user()->addresses()->where('id','!=',$address->id)->update(['is_default' => 0]);

            DB::commit();
            $address->refresh();

            return $address;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();

        $address = $this->findById($id);

        try {

            $address->update([
                'email' => $request['email'] ?? auth()->user()->email,
                'username' => $request['username'] ?? auth()->user()->name,
                'mobile' => $request['mobile'] ?? auth()->user()->mobile,
                'address' => $request['address'],
                'is_default' => $request->default ? 1 : 0,
                'phone_code' => $request['phone_code'],
                'block' => $request['block'],
                'street' => $request['street'],
                'building' => $request['building'],
                'state_id' => $request['state_id'],
                'avenue' => $request['avenue'] ?? null,
                'floor' => $request['floor'] ?? null,
                'flat' => $request['flat'] ?? null,
                'automated_number' => $request['automated_number'] ?? null
            ]);

            if($address->is_default)
                auth()->user()->addresses()->where('id','!=',$address->id)->update(['is_default' => 0]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findById($id);
            $model->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function setAddressAsDefault($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findById($id);
            $model->is_default = 1;
            $model->save();
            auth()->user()->addresses()->where('id','!=',$model->id)->update(['is_default' => 0]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
