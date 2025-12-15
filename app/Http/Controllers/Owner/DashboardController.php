<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Check if user is owner
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!$user || $user->role !== 'owner') {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }
    public function index()
    {
        $activeSellers = Seller::where('status', 'ACTIVE')->count();
        $nonActiveSellers = Seller::whereIn('status', ['PENDING', 'REJECTED'])->count();

        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalReviews = Review::count();

        $commentersCount = Review::whereNotNull('comment')
            ->where('comment', '!=', '')
            ->distinct('email')
            ->count('email');

        $ratersCount = Review::whereNotNull('rating')
            ->distinct('email')
            ->count('email');

        $reviewsByMonth = Review::selectRaw('MONTH(created_at) as m, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('m')
            ->pluck('total', 'm');

        $productByCategory = Category::withCount('products')
            ->orderByDesc('products_count')
            ->get();

        $sellerByLocation = Seller::select('pic_province', DB::raw('count(*) as total'))
            ->groupBy('pic_province')
            ->orderBy('total', 'desc')
            ->get();

        $allProvinces = [
            'Aceh','Sumatera Utara','Sumatera Barat','Riau','Jambi','Sumatera Selatan',
            'Bengkulu','Lampung','Kepulauan Bangka Belitung','Kepulauan Riau',
            'DKI Jakarta','Jawa Barat','Jawa Tengah','DI Yogyakarta','Jawa Timur','Banten',
            'Bali','Nusa Tenggara Barat','Nusa Tenggara Timur',
            'Kalimantan Barat','Kalimantan Tengah','Kalimantan Selatan','Kalimantan Timur','Kalimantan Utara',
            'Sulawesi Utara','Sulawesi Tengah','Sulawesi Selatan','Sulawesi Tenggara','Gorontalo','Sulawesi Barat',
            'Maluku','Maluku Utara',
            'Papua','Papua Barat','Papua Tengah','Papua Pegunungan','Papua Selatan','Papua Barat Daya'
        ];

        $sellerMap = $sellerByLocation->pluck('total', 'pic_province');

        $sellerByProvince = collect($allProvinces)->map(function ($prov) use ($sellerMap) {
            return [
                'name'  => $prov,
                'count' => (int) ($sellerMap[$prov] ?? 0)
            ];
        });


        $chartData = [];
        for ($month = 1; $month <= 12; $month++) {
            $chartData[] = (int) ($reviewsByMonth[$month] ?? 0);
        }

        return view('owner.dashboard', compact(
            'activeSellers',
            'nonActiveSellers',
            'totalProducts',
            'totalCategories',
            'totalReviews',
            'commentersCount',
            'ratersCount',
            'productByCategory',
            'sellerByLocation',
            'sellerByProvince',
            'chartData'
        ));
    }

}