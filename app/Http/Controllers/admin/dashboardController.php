<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DetailProduct;
use App\Models\Order;
use App\Models\Product;
use App\Models\StoreLocation;
use App\Models\User;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    //
    public function index(Request $request)
    {

        $totalUser = User::count();
        $totalOrder = Order::count();
        $totalProduct = DetailProduct::count();
        $totalCategory = Category::count();
        $totalStore = StoreLocation::count();

        $orderTotalByMonth = Order::selectRaw("COUNT(*) as total, MONTH(created_at) as month")
            ->groupBy("month")
            ->pluck('total', 'month')
            ->toArray();

        // Tạo mảng 12 tháng với giá trị mặc định là 0
        $orderTotalByMonthData = array_fill(1, 12, 0);

        // Gán dữ liệu vào đúng tháng
        foreach ($orderTotalByMonth as $month => $total) {
            $orderTotalByMonthData[$month] = $total;
        }

        $userTotalByMonth = User::selectRaw("COUNT(*) as total , MONTH(created_at) as month")
            ->groupBy("month")
            ->pluck('total', 'month')
            ->toArray();

        $userTotalByMonthData = array_fill(1, 12, 0);

        // Gán dữ liệu vào đúng tháng")
        foreach ($userTotalByMonth as $month => $total) {
            $userTotalByMonthData[$month] = $total;
        }

        $priceByMonth = Order::selectRaw("SUM(total_amount) as total, MONTH(created_at) as month")
            ->groupBy("month")
            ->pluck('total', 'month')
            ->toArray();

        $priceByMonthData = array_fill(1, 12, 0);

        foreach ($priceByMonth as $month => $total) {
            $priceByMonthData[$month] = $total;
        }


        return view('admin.pages.dashboard', compact('totalUser', 'totalOrder', 'totalProduct', 'totalCategory', 'totalStore', 'orderTotalByMonthData', 'userTotalByMonthData', 'priceByMonthData'));
    }
}
