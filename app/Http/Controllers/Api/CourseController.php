<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Course;

class CourseController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $courses = Course::all(); // mengambil semua data course

        if(count($courses) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $courses
            ], 200);
        } // return data semua course dalam bentuk json
            
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data course kosong
    }

    // method untuk menampilkan 1 data course (search)
    public function show($id)
    {
        $course = Course::find($id); // mencari data course berdasarkan id

        if(!is_null($course)){
            return response([
                'message' => 'Retrieve Course Success',
                'data' => $course
            ], 200);
        }// return data course yang ditemukan dalam bentuk json

        return response([
            'message' => 'Course Not Found',
            'data' => null
        ], 404); // return message saat data course tidak ditemukan       
    }

    // method untuk menambah 1 data course baru (create)
    public function store(Request $request)
    {
        $storeData = $request->all();// mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'nama_modul' => 'required|max:60|unique:courses',
            'kode' => 'required',
            'desc' => 'required',
            'url' => 'required'
        ]);// rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);//error invalid input

        $course = Course::create($storeData);
        return response([
            'message' => 'Add Course Success',
            'data' => $course
        ], 200);// return data course baru dalam bentuk json
    }

    // method untuk menghapus 1 data product (delete)
    public function destroy($id)
    {
        $course = Course::find($id);//cari data product berdasarkan id

        if(is_null($course)){
            return response([
                'message' => 'Course Not Found',
                'data' => null
            ], 404);
        }// message saat data course tidak dapat ditemukan
        
        if($course->delete()){
            return response([
                'message' => 'Delete Course Success',
                'data' => $course
            ], 200);
        }//message saat berhasil menghapus data course

        return response([
            'message' => 'Delete Course Failed',
            'data' => null
        ], 400);// message saat gagal menghapus data course
    }

    // method untuk mengubah 1 data course (update)
    public function update(Request $request, $id)
    {
        $course = Course::find($id);//mencari data course berdasarkan id
        if(is_null($course)){
            return response([
                'message' => 'Course Not Found',
                'data' => null
            ], 404);
        }//message saat data course tidak ditemukan

        $updateData = $request->all();// mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'nama_modul' => ['required', 'max:60', Rule::unique('courses')->ignore($course)],
            'kode' => 'required',
            'desc' => 'required',
            'url' => 'required'
        ]);//rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);//rule invalid input

        $course->nama_modul = $updateData['nama_modul'];// edit nama kelas
        $course->kode = $updateData['kode'];
        $course->desc = $updateData['desc'];
        $course->url = $updateData['url'];

        if($course->save()){
            return response([
                'message' => 'Update Course Success',
                'data' => $course
            ], 200);
        }//return data course yang telah di edit dalam bentuk json
        return response([
            'message' => 'Update Course Failed',
            'data' => null
        ], 400);//return message saat course gagal di edit
    }
}
