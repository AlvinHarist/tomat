<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller; // Kita HANYA pakai model ini sekarang
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth; // Untuk auto-login jika perlu
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function store(Request $request)
{
    // 1. Validasi (Sesuaikan dengan name="" di HTML Anda)
    $validator = Validator::make($request->all(), [
        'store_name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        
        // Input HTML Anda pakai Bahasa Indonesia
        'provinsi' => ['required', 'string'],
        'kabupatenkota' => ['required', 'string'],
        'kelurahan' => ['required', 'string'],
        'jalan' => ['required', 'string'],
        'rt' => ['required', 'string', 'max:3'],
        'rw' => ['required', 'string', 'max:3'],

        'name' => ['required', 'string', 'max:100'], // Nama PIC
        'phone' => ['required', 'string', 'min:10', 'max:20', 'regex:/^\d+$/', 'unique:sellers,pic_phone'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:sellers,pic_email'],
        
        'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'],
        
        'ktp_number' => ['required', 'string', 'size:16', 'regex:/^\d+$/', 'unique:sellers,pic_ktp_number'],
        'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        'ktp_file' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
    ], [
        // Pesan Error Bahasa Indonesia Kustom
        'phone.regex' => 'Nomor telepon harus berupa angka.',
        'phone.unique' => 'Nomor telepon sudah terdaftar.',
        'ktp_number.regex' => 'Nomor KTP harus berupa angka.',
        'ktp_number.unique' => 'Nomor KTP sudah terdaftar.',
        'password.regex' => 'Password harus mengandung huruf besar, kecil, angka, dan simbol.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);
    
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $photoPath = null;
    $ktpFilePath = null;

    DB::beginTransaction();
    try {
        // Upload File
        $photoPath = $request->file('photo')->store('profiles', 'public');
        $ktpFilePath = $request->file('ktp_file')->store('ktp', 'public');

        // Simpan ke Database (Mapping Input Indo -> Kolom DB)
        $seller = Seller::create([
            'store_name' => $request->store_name,
            'store_description' => $request->description,
            
            'pic_name' => $request->name,
            'pic_email' => $request->email,
            'pic_phone' => $request->phone,
            'password' => Hash::make($request->password),
            
            // Mapping Alamat (PENTING!)
            'pic_street' => $request->jalan,       // Input 'jalan' -> Kolom 'pic_street'
            'pic_rt' => $request->rt,              // Input 'rt' -> Kolom 'pic_rt'
            'pic_rw' => $request->rw,              // Input 'rw' -> Kolom 'pic_rw'
            'pic_village' => $request->kelurahan,  // Input 'kelurahan' -> Kolom 'pic_village'
            'pic_city' => $request->kabupatenkota, // Input 'kabupatenkota' -> Kolom 'pic_city'
            'pic_province' => $request->provinsi,  // Input 'provinsi' -> Kolom 'pic_province'
            
            'pic_ktp_number' => $request->ktp_number,
            'pic_photo_path' => $photoPath,
            'pic_ktp_file_path' => $ktpFilePath,
            'status' => 'PENDING',
        ]);

        event(new Registered($seller));
        DB::commit();

        // Redirect dengan pesan Sukses
        return redirect()->route('login')->with('status', 'Registrasi Berhasil! Silakan cek email Anda.');

    } catch (\Exception $e) {
        DB::rollBack();
        // Hapus file jika gagal
        if ($photoPath) Storage::disk('public')->delete($photoPath);
        if ($ktpFilePath) Storage::disk('public')->delete($ktpFilePath);

        return redirect()->back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()])->withInput();
    }
}
}