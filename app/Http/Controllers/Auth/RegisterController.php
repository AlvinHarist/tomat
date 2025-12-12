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
        // =========================
        // 1) VALIDATION
        // =========================
        $rules = [
            'store_name'    => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],

            // PIC name disimpan di users.name
            'name'          => ['required', 'string', 'max:100'],

            // di sellers kolomnya pic_phone
            'phone'         => ['required', 'string', 'min:10', 'max:20', 'regex:/^\d+$/', 'unique:sellers,pic_phone'],

            // email hanya di users
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email'],

            'jalan'         => ['required', 'string'],
            'rt'            => ['required', 'string'],
            'rw'            => ['required', 'string'],
            'provinsi'      => ['required'],
            'kabupatenkota' => ['required'],
            'kecamatan'     => ['required'],
            'kelurahan'     => ['required'],

            'password'      => ['required', 'string', 'min:8', 'confirmed'],

            // di sellers kolomnya pic_ktp_number
            'ktp_number'    => ['required', 'string', 'size:16', 'regex:/^\d+$/', 'unique:sellers,pic_ktp_number'],

            'photo'         => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'ktp_file'      => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];

        $messages = [
            'phone.regex'      => 'Nomor HP hanya boleh berisi angka.',
            'ktp_number.regex' => 'Nomor KTP hanya boleh berisi angka.',
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

        // =========================
        // 2) OPTIONAL: RE-REGISTER JIKA SELLER SEBELUMNYA REJECTED
        //    (status ada di sellers, bukan users)
        // =========================
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            $existingSeller = $existingUser->seller; // relasi hasOne di User

            if ($existingSeller && $existingSeller->status === 'REJECTED') {
                // hapus file lama jika ada (opsional)
                if (!empty($existingSeller->pic_photo_path)) {
                    Storage::disk('public')->delete($existingSeller->pic_photo_path);
                }
                if (!empty($existingSeller->pic_ktp_file_path)) {
                    Storage::disk('public')->delete($existingSeller->pic_ktp_file_path);
                }

                // hapus seller & user lama
                $existingSeller->delete();
                $existingUser->delete();
            } else {
                // kalau user sudah ada dan bukan rejected, jangan lanjut
                return redirect()->back()
                    ->withErrors(['email' => 'Email sudah terdaftar. Silakan login atau gunakan email lain.'])
                    ->withInput();
            }
        }

        // =========================
        // 3) TRANSACTION CREATE USER + SELLER
        // =========================
        $photoPath = null;
        $ktpPath = null;

        DB::beginTransaction();
        try {
            // Upload files ke storage/app/public/...
            // Pastikan sudah: php artisan storage:link
            $photoPath = Storage::disk('public')->putFile('profiles', $request->file('photo'));
            $ktpPath   = Storage::disk('public')->putFile('ktp', $request->file('ktp_file'));

            // ambil nama wilayah dari code
            $province = Province::where('code', $request->provinsi)->first();
            $city     = City::where('code', $request->kabupatenkota)->first();
            $district = District::where('code', $request->kecamatan)->first();
            $village  = Village::where('code', $request->kelurahan)->first();

            if (!$province || !$city || !$district || !$village) {
                throw new \Exception('Data wilayah tidak valid. Pastikan memilih dari dropdown yang tersedia.');
            }

            // Create User
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'seller',
            ]);

            // Create Seller (UUID akan otomatis via boot() Seller)
            Seller::create([
                'user_id'            => $user->id,
                'store_name'         => $request->store_name,
                'store_description'  => $request->description,

                'pic_phone'          => $request->phone,
                'pic_street'         => $request->jalan,
                'pic_rt'             => $request->rt,
                'pic_rw'             => $request->rw,

                'pic_village'        => $village->name,
                'pic_district'       => $district->name,
                'pic_city'           => $city->name,
                'pic_province'       => $province->name,

                'pic_ktp_number'     => $request->ktp_number,
                'pic_photo_path'     => $photoPath,
                'pic_ktp_file_path'  => $ktpPath,

                'status'             => 'PENDING',
            ]);

            DB::commit();

            return redirect()->route('login')
                ->with('status', 'Registrasi berhasil! Mohon tunggu verifikasi dari admin.');

        } catch (\Throwable $e) {
            DB::rollBack();

            // hapus file yang sudah terlanjur terupload
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            if ($ktpPath) {
                Storage::disk('public')->delete($ktpPath);
            }

            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat registrasi. ' . $e->getMessage()])
                ->withInput();
        }
    }
}