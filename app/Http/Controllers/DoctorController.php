<?php

namespace App\Http\Controllers;

use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DoctorController extends Controller
{
    private function getDoctorWhereName($name){
        return Doctor::where('name','like','%'.$name.'%')->orderBy('id')->paginate(25);
    }

    public function index(Request $request){
        try {
            // التحقق مما إذا كانت هناك عملية بحث
            if($request->search){
                $name = $request->search;
                $doctors = $this->getDoctorWhereName($name);
                $doctors = DoctorResource::collection($doctors);
                return response()->json(['data' => $doctors], 200);
            } else {
                // إرجاع جميع الأطباء إذا لم يتم العثور على بحث
                $doctors = Doctor::select('doctors.*')->orderBy('id')->paginate(25);
                $doctors = DoctorResource::collection($doctors);
                return response()->json(['data' => $doctors], 200);
            }
        } catch (\Exception $e) {
            // تسجيل الخطأ في حالة حدوث استثناء
            Log::error('Error in DoctorController@index: ' . $e->getMessage());
            // إرجاع رسالة خطأ عند حدوث خطأ داخلي
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function show(Request $request , $id){
        try {
            // البحث عن الطبيب باستخدام الهوية
            $doctor = Doctor::find($id);
            if (!$doctor) {
                // إرجاع رسالة عندما لا يتم العثور على الطبيب
                return response()->json(['message' => 'doctor not found'], 404);
            }
            // تحويل البيانات إلى مورد وإرجاعها
            $doctor = new DoctorResource($doctor);
            return response()->json(['data' => $doctor], 200);
        } catch (\Exception $e) {
            // تسجيل الخطأ في حالة حدوث استثناء
            Log::error('Error in DoctorController@show: ' . $e->getMessage());
            // إرجاع رسالة خطأ عند حدوث خطأ داخلي
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function store(Request $request){
        $request ->validate([
            'name' => 'required|string|max:255',
            'email'=> 'required|email|unique:doctors,email',
            'phone_number'=> 'required|numeric|unique:doctors,phone_number|digits:11',
            'clinic_address'=>'required|string',
            'schedule'=>'required|string|max:255',
            'doctor_image'=>'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if($request->file('doctor_image')){
            $path = $request->file('doctor_image')->store('images');
        }
        else{
            // Return error response if doctor image is not provided
            return response()->json(['message' => 'Doctor image is required'], 500);
        }
        $doctor = Doctor::create([
            'name' => $request -> name,
            'email'=> $request -> email,
            'phone_number'=> $request -> phone_number,
            'clinic_address'=>$request -> clinic_address,
            'schedule'=> $request -> schedule,
            'doctor_image'=>$path
            // مسار الصوره
        ]);
        return response()->json(['message' => 'Doctor created'], 200);
    }
    public function update(Request $request,$id){
        $request -> validate([
            'name' => 'nullable|string|max:255',
            'email'=> 'nullable|email|unique:doctors,email',
            'phone_number'=> 'nullable|numeric|unique:doctors,phone_number|digits:11',
            'clinic_address'=>'nullable|string',
            'schedule'=>'nullable|string|max:255',
            'doctor_image'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $doctor = Doctor::find($id);
        if(!$doctor){
            return response()->json(['message' => 'Doctor not found'], 404);
        }
        if($request->file('doctor_image')){
            Storage::delete($doctor -> doctor_image );
            $path = $request->file('doctor_image')->store('images');
            $doctor -> update([
                'doctor_image' => $path
            ]);
        }
        $doctor -> update($request->except('doctor_image'));
        return response()->json(['message' => 'Doctor updated'], 200);
    }
    public function destroy($id){
        $doctor = Doctor::find($id);
        if(!$doctor){
            return response()->json(['message' => 'Doctor not found'], 404);
        }
        if($doctor -> doctor_image){
            Storage::delete($doctor -> doctor_image );
        }
        $doctor -> delete();
        return response()->json(['message' => 'Doctor delete'], 200);


    }

}
