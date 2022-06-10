<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Address;
use App\Http\Resources\AddressResource;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function index()
    {
        
        $address = Address::all();

        return new AddressResource(true, 'List Data Address', $address);
    }

    public function store(Request $request)
    {
        // rules validasi data
        $validator = Validator::make($request->all(), [
            'address'   => 'required|max:255',
            'city'      => 'required',
            'province'  => 'required',
            'zip_code'  => 'required|integer',
        ]);

        // cek validasi jika gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        // create address
        $address = Address::create([
            'address'       => $request->address,
            'city'          => $request->city,
            'province'      => $request->province,
            'zip_code'      => $request->zip_code,
        ]);

        // return response
        return new AddressResource(true, 'Data Address Berhasil Ditambahkan!', $address);
    }

    public function show(Address $address)
    {
        // menampilkan detail data berdasarkan id
        return new AddressResource(true, 'Data Address Ditemukan!', $address);
    }

    public function update(Request $request, Address $address)
    {
        // rules validasi data
        $validator = Validator::make($request->all(), [
            'address'   => 'required|max:255',
            'city'      => 'required',
            'province'  => 'required',
            'zip_code'  => 'required|integer',
        ]);

        // cek validasi jika gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        // update data address
        $address->update([
            'address'       => $request->address,
            'city'          => $request->city,
            'province'      => $request->province,
            'zip_code'      => $request->zip_code
        ]);

        return new AddressResource(true, 'Data Address Berhasil Diubah!', $address);
    }

    public function destroy(Address $address)
    {
        $address->delete();

        return new AddressResource(true, 'Data Address Berhasil Dihapus!', null);
    }
}
