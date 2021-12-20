<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Live;

class LiveController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $lives = Live::all(); // mengambil semua data live

        if(count($lives) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $lives
            ], 200);
        } // return data semua live dalam bentuk json
            
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data live kosong
    }

    // method untuk menampilkan 1 data live (search)
    public function show($id)
    {
        $live = Live::find($id); // mencari data live berdasarkan id

        if(!is_null($live)){
            return response([
                'message' => 'Retrieve Live Success',
                'data' => $live
            ], 200);
        }// return data live yang ditemukan dalam bentuk json

        return response([
            'message' => 'Live Not Found',
            'data' => null
        ], 404); // return message saat data live tidak ditemukan       
    }

    // method untuk menambah 1 data live baru (create)
    public function store(Request $request)
    {
        $storeData = $request->all();// mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'nama_modul' => 'required|max:60|unique:lives',
            'sesi' => 'required',
            'tanggal' => 'required',
            'url' => 'required'
        ]);// rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);//error invalid input

        $live = Live::create($storeData);
        return response([
            'message' => 'Add Live Success',
            'data' => $live
        ], 200);// return data live baru dalam bentuk json
    }

    // method untuk menghapus 1 data product (delete)
    public function destroy($id)
    {
        $live = Live::find($id);//cari data product berdasarkan id

        if(is_null($live)){
            return response([
                'message' => 'Live Not Found',
                'data' => null
            ], 404);
        }// message saat data live tidak dapat ditemukan
        
        if($live->delete()){
            return response([
                'message' => 'Delete Live Success',
                'data' => $live
            ], 200);
        }//message saat berhasil menghapus data live

        return response([
            'message' => 'Delete Live Failed',
            'data' => null
        ], 400);// message saat gagal menghapus data live
    }

    // method untuk mengubah 1 data live (update)
    public function update(Request $request, $id)
    {
        $live = Live::find($id);//mencari data live berdasarkan id
        if(is_null($live)){
            return response([
                'message' => 'Live Not Found',
                'data' => null
            ], 404);
        }//message saat data live tidak ditemukan

        $updateData = $request->all();// mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'nama_modul' => ['required', 'max:60', Rule::unique('lives')->ignore($live)],
            'sesi' => 'required',
            'tanggal' => 'required',
            'url' => 'required'
        ]);//rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);//rule invalid input

        $live->nama_modul = $updateData['nama_modul'];// edit nama kelas
        $live->sesi = $updateData['sesi'];
        $live->tanggal = $updateData['tanggal'];
        $live->url = $updateData['url'];

        if($live->save()){
            return response([
                'message' => 'Update Live Success',
                'data' => $live
            ], 200);
        }//return data live yang telah di edit dalam bentuk json
        return response([
            'message' => 'Update Live Failed',
            'data' => null
        ], 400);//return message saat live gagal di edit
    }
}
