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
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $provinces = Province::all();
        return view('auth.register', compact('provinces'));
    }

    // API endpoints for dependent dropdown
    public function getCities($provinceCode)
    {
        $cities = City::where('province_code', $provinceCode)->get();
        return response()->json($cities);
    }

    public function getDistricts($cityCode)
    {
        $districts = District::where('city_code', $cityCode)->get();
        return response()->json($districts);
    }

    public function getVillages($districtCode)
    {
        $villages = Village::where('district_code', $districtCode)->get();
        return response()->json($villages);
    }

    public function store(Request $request)
    {
        // Check if seller with this email was previously rejected - allow re-registration
        $existingSeller = Seller::where('pic_email', $request->email)->first();
        if ($existingSeller && $existingSeller->status === 'REJECTED') {
            // Delete old rejected seller and user records
            $existingSeller->delete();
            
            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser) {
                $existingUser->delete();
            }
        }

        $rules = [
            'store_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'name' => ['required', 'string', 'max:100'], // PIC Name
            'phone' => ['required', 'string', 'max:20', 'min:10', 'regex:/^\d+$/', 'unique:sellers,pic_phone'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'unique:sellers,pic_email'],
            
            'jalan' => ['required', 'string'],
            'rt' => ['required', 'string'],
            'rw' => ['required', 'string'],
            'provinsi' => ['required', 'string'],
            'kabupatenkota' => ['required', 'string'],
            'kecamatan' => ['required', 'string'],
            'kelurahan' => ['required', 'string'],

            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'],
            'ktp_number' => ['required', 'string', 'size:16', 'unique:sellers,pic_ktp_number'],
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'ktp_file' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];

        $messages = [
            'phone.regex' => 'Nomor HP hanya boleh berisi angka.',
            'ktp_number.regex' => 'Nomor KTP hanya boleh berisi angka.',
            'password.regex' => 'Password harus mengandung huruf besar, kecil, angka, dan simbol.',
            'provinsi.required' => 'Provinsi wajib dipilih.',
            'kabupatenkota.required' => 'Kabupaten/Kota wajib dipilih.',
            'kecamatan.required' => 'Kecamatan wajib dipilih.',
            'kelurahan.required' => 'Kelurahan wajib dipilih.',
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
            // Upload photo to public/images/profiles
            $photoFile = $request->file('photo');
            $photoFilename = time() . '_' . uniqid() . '.' . $photoFile->getClientOriginalExtension();
            $photoFile->move(public_path('images/profiles'), $photoFilename);
            $photoPath = 'images/profiles/' . $photoFilename;
            
            // Upload KTP to public/images/ktp
            $ktpFile = $request->file('ktp_file');
            $ktpFilename = time() . '_' . uniqid() . '.' . $ktpFile->getClientOriginalExtension();
            $ktpFile->move(public_path('images/ktp'), $ktpFilename);
            $ktpFilePath = 'images/ktp/' . $ktpFilename;

            // Create User for Authentication
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                // 'role' => 'penjual', // Assuming 'role' column exists in users table, if not remove this
                // 'status' => 'aktif', // Assuming 'status' column exists in users table
            ]);

            // Debug: Log received data
            Log::info('Registration data received:', [
                'provinsi' => $request->provinsi,
                'kabupatenkota' => $request->kabupatenkota,
                'kecamatan' => $request->kecamatan,
                'kelurahan' => $request->kelurahan,
            ]);

            // Get region names from codes
            $province = Province::where('code', $request->provinsi)->first();
            $city = City::where('code', $request->kabupatenkota)->first();
            $district = District::where('code', $request->kecamatan)->first();
            $village = Village::where('code', $request->kelurahan)->first();

            // Debug: Log query results
            Log::info('Region query results:', [
                'province' => $province ? $province->name : 'NULL',
                'city' => $city ? $city->name : 'NULL',
                'district' => $district ? $district->name : 'NULL',
                'village' => $village ? $village->name : 'NULL',
            ]);

            // Validate that all region data was found
            if (!$province || !$city || !$district || !$village) {
                throw new \Exception('Data wilayah tidak valid. Pastikan Anda memilih wilayah dari dropdown yang tersedia. (Provinsi: ' . ($province ? '✓' : '✗') . ', Kota: ' . ($city ? '✓' : '✗') . ', Kecamatan: ' . ($district ? '✓' : '✗') . ', Kelurahan: ' . ($village ? '✓' : '✗') . ')');
            }

            // Create Seller (using the sellers table structure)
            Seller::create([
                'id' => \Illuminate\Support\Str::uuid(),
                'store_name' => $request->store_name,
                'store_description' => $request->description,
                'pic_name' => $request->name,
                'pic_phone' => $request->phone,
                'pic_email' => $request->email,
                'password' => Hash::make($request->password),
                'pic_street' => $request->jalan,
                'pic_rt' => $request->rt,
                'pic_rw' => $request->rw,
                'pic_village' => $village->name,
                'pic_district' => $district->name,
                'pic_city' => $city->name,
                'pic_province' => $province->name,
                'pic_ktp_number' => $request->ktp_number,
                'pic_photo_path' => $photoPath,
                'pic_ktp_file_path' => $ktpFilePath,
                'status' => 'PENDING',
            ]);

            // Store::create(...) removed because stores table does not exist.

            // DO NOT send email verification yet - it will be sent after owner approval
            // event(new Registered($user));

            DB::commit();

            return redirect()->route('seller.login')->with('status', 'Registrasi berhasil! Mohon tunggu verifikasi dari admin.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($photoPath) {
                $fullPath = public_path($photoPath);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
            if ($ktpFilePath) {
                $fullPath = public_path($ktpFilePath);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }

            return redirect()->back()
                        ->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Coba lagi. ' . $e->getMessage()])
                        ->withInput();
        }
    }
}