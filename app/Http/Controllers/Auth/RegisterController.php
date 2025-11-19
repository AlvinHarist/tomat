<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use App\Models\Seller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'],

            'store_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'province' => ['required', 'string'],
            'city' => ['required', 'string'],
            'district' => ['required', 'string'],
            'address_details' => ['required', 'string'],

            'phone' => ['required', 'string', 'max:20', 'min:10', 'regex:/^\d+$/', 'unique:sellers,phone'],
            'ktp_number' => ['required', 'string', 'size:16', 'unique:sellers,ktp_number'],
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'ktp_file' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'pic_address' => ['required', 'string'],
            'pic_rt' => ['required', 'string', 'max:3'],
            'pic_rw' => ['required', 'string', 'max:3'],
            'pic_village' => ['required', 'string'],
        ];

        $messages = [
            'phone.regex' => 'Nomor HP hanya boleh berisi angka.',
            'phone.unique' => 'Nomor HP ini sudah terdaftar.',
            'ktp_number.regex' => 'Nomor KTP hanya boleh berisi angka.',
            'ktp_number.unique' => 'Nomor KTP ini sudah terdaftar.',
            'password.regex' => 'Password harus mengandung huruf besar, kecil, angka, dan simbol.',
        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $photoPath = null;
        $ktpFilePath = null;

        DB::beginTransaction();
        try {
            $photoPath = $request->file('photo')->store('profiles', 'public');
            $ktpFilePath = $request->file('ktp_file')->store('ktp', 'public');

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'penjual',
                'status' => 'aktif',
            ]);

            Store::create([
                'user_id' => $user->id,
                'store_name' => $request->store_name,
                'description' => $request->description,
                'province' => $request->province,
                'city' => $request->city,
                'district' => $request->district,
                'address_details' => $request->address_details,
            ]);

            Seller::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'ktp_number' => $request->ktp_number,
                'photo' => $photoPath,
                'ktp_file' => $ktpFilePath,
                'address' => $request->pic_address,
                'rt' => $request->pic_rt,
                'rw' => $request->pic_rw,
                'village' => $request->pic_village,
            ]);

            event(new Registered($user));

            DB::commit();

            return redirect()->route('login')->with('status', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            if ($ktpFilePath) {
                Storage::disk('public')->delete($ktpFilePath);
            }

            return redirect()->back()
                        ->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Coba lagi. ' . $e->getMessage()])
                        ->withInput();
        }
    }
}