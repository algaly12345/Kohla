<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Coupon;
use App\Deal;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use App\Media;
use App\Spotlight;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BackendController extends Controller
{
    public function Categories(Request $request )
    {
//        $couponData = json_decode($request->couponData, true);
//        if ($couponData) {
////            setcookie("couponData", "", time() - 3600);
//        }

            /* LOCATION */
            $location_id = $request->location_id;

            /* CATRGORIES */
            $categories = Category::active()->withoutGlobalScope('company')
                ->activeCompanyService()
                ->with(['services' => function ($query)  use($location_id) {
                    $query->active()->withoutGlobalScope('company')->where('location_id', $location_id);
                }])
                ->withCount(['services' => function ($query) use($location_id) {
                    $query->withoutGlobalScope('company')->where('location_id', $location_id);
                }]);

            $total_categories_count = $categories->count();
            $categories = $categories->take(8)->get();


            /* DEALS */
            $deals = Deal::withoutGlobalScope('company')
                ->active()
                ->activeCompany()
                ->with(['location', 'services', 'company'=> function($q) { $q->withoutGlobalScope('company'); } ])
////                ->where('start_date_time', '<=', Carbon::now()->setTimezone($this->settings->timezone))
//                ->where('end_date_time', '>=', Carbon::now()->setTimezone($this->settings->timezone))
                ->where('location_id', $location_id);

            $total_deals_count = $deals->count();
            $deals = $deals->take(10)->get();

            $spotlight = Spotlight::with(['deal', 'company'=> function($q) { $q->withoutGlobalScope('company'); } ])
                ->activeCompany()
                ->whereHas('deal', function($q) use($location_id){
                    $q->whereHas('location', function ($q) use($location_id) {
                        $q->where('location_id', $location_id);
                    });
                })
                ->orderBy('sequence', 'asc')->get();

            return Reply::dataOnly(['categories' => $categories, 'total_categories_count' => $total_categories_count, 'deals' => $deals, 'total_deals_count' => $total_deals_count, 'spotlight' => $spotlight]);






        /* COUPON */
        $coupons = Coupon::active();

        $this->coupons = $coupons->take(12)->get();

        $this->sliderContents = Media::all();


        return response()->json($categories);

     //   return view('front.index', $this->data);
        }}
