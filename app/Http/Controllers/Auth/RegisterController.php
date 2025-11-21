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
            'store_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'name' => ['required', 'string', 'max:100'], // PIC Name
            'phone' => ['required', 'string', 'max:20', 'min:10', 'regex:/^\d+$/', 'unique:sellers,pic_phone'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'unique:sellers,pic_email'],
            
            'jalan' => ['required', 'string'],
            'rt' => ['required', 'string'],
            'rw' => ['required', 'string'],
            'kelurahan' => ['required', 'string'],
            'kabupatenkota' => ['required', 'string'],
            'provinsi' => ['required', 'string'],

            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'],
            'ktp_number' => ['required', 'string', 'size:16', 'unique:sellers,pic_ktp_number'],
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'ktp_file' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];

        $messages = [
            'phone.regex' => 'Nomor HP hanya boleh berisi angka.',
            'ktp_number.regex' => 'Nomor KTP hanya boleh berisi angka.',
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

            // Create User for Authentication
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                // 'role' => 'penjual', // Assuming 'role' column exists in users table, if not remove this
                // 'status' => 'aktif', // Assuming 'status' column exists in users table
            ]);

            // Create Seller (using the sellers table structure)
            // Note: The sellers table migration does not have user_id, so we cannot link it here unless the migration is updated.
            // We will save the data as per the sellers table schema.
            Seller::create([
                'id' => \Illuminate\Support\Str::uuid(),
                'store_name' => $request->store_name,
                'store_description' => $request->description,
                'pic_name' => $request->name,
                'pic_phone' => $request->phone,
                'pic_email' => $request->email,
                'password' => Hash::make($request->password), // Storing password in sellers table too as per schema
                'pic_street' => $request->jalan,
                'pic_rt' => $request->rt,
                'pic_rw' => $request->rw,
                'pic_village' => $request->kelurahan,
                'pic_city' => $request->kabupatenkota,
                'pic_province' => $request->provinsi,
                'pic_ktp_number' => $request->ktp_number,
                'pic_photo_path' => $photoPath,
                'pic_ktp_file_path' => $ktpFilePath,
                'status' => 'PENDING',
            ]);

            // Store::create(...) removed because stores table does not exist.

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