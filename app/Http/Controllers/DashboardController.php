<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Traits\ActivityLoggableTrait;
use App\Models\User;
use App\Models\Setting;
use App\Models\Models\KMJ\Customer;
use App\Models\LoanInsurance;
use App\Models\Utility;
use App\Models\SmtpSetting;
use App\Models\CompanySetting;
use App\Models\WasteCollection;
use App\Models\RecyclingWasteCollection;
use App\Models\WasteType;
use App\Models\BeamSetting;
use App\Models\LoanLog;
use App\Models\NextSmsSetting;
use App\Models\Account;
use App\Models\Tenant;
use App\Models\PaymentStatus;
use App\Models\Fine;
use App\Models\LoanPayment;
use App\Models\Transaction;
use App\Models\LoanStatus;
use App\Models\ApprovalLevel;
use App\Models\AccountType;
use App\Models\WastePicker;
use App\Models\LoanPenalty;
use App\Models\Income;
use App\Models\LoanApproval;
use App\Models\LoanType;
use App\Models\Expense;
use App\Models\Unit;
use App\Models\LoanRepaymentSchedule;
use App\Models\DocumentType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Auth;
use Carbon\Carbon;
use App\Models\PusherNotification;

class DashboardController extends Controller
{
    public function index()
    {



        $user = auth()->user();
        $today = Carbon::today();
        $settings = Setting::first();

        //TOTAL CUSOMERS
        $totalCustomers=Customer::count();

        $listCustomers=Customer::all();

        // Recent User Registrations (last 7 days)
        $recentUsers = User::whereBetween('created_at', [now()->subDays(7), now()])
            ->latest()->take(5)->get();

            // Recent User Registrations (last 7 days)
        $activeWastePickers = WastePicker::where('archive',0);

        // Smart Bin Status
        $smartBins = [];

        // Months (1-12)
        $months = collect(range(1, 12))->map(fn($m) => Carbon::create(null, $m)->format('F'))->toArray();

        // Role-based scope
        $centerIds = $facilityIds = $producerIds = null;
        if ($user) {
            if ($user->role == 3) $centerIds = $user->collection_centers()->pluck('collection_center_id');
            if ($user->role == 4) $facilityIds = $user->facilities()->pluck('facility_id');
            if ($user->role == 5) $producerIds = $user->producers()->pluck('producer_id');
        }

        // Waste quantity by type & month
        $getQuantity = function ($typeId, $month = null) use ($centerIds, $facilityIds, $producerIds) {
            if ($centerIds) {
                return WasteCollection::where('waste_type_id', $typeId)
                    ->where('archive', 0)
                    ->when($month, fn($q) => $q->whereMonth('created_at', $month)->whereYear('created_at', now()->year))
                    ->whereIn('collection_center_id', $centerIds)
                    ->sum('quantity');
            }

            $query = RecyclingWasteCollection::where('archive', 0)->whereHas('items', fn($q) => $q->where('waste_type_id', $typeId));
            if ($facilityIds) $query->whereIn('facility_id', $facilityIds);
            if ($producerIds) $query->whereIn('producer_id', $producerIds);
            if ($month) $query->whereMonth('created_at', $month)->whereYear('created_at', now()->year);

            return $query->get()->flatMap->items->where('waste_type_id', $typeId)->sum('quantity');
        };

        // Chart data
        $parentWasteTypes = WasteType::whereNull('parent_id')->get()->map(fn($type) => [
            'name' => $type->name,
            'data' => collect(range(1, 12))->map(fn($m) => $getQuantity($type->id, $m))->toArray()
        ]);

        $childWasteTypes = WasteType::whereNotNull('parent_id')->get()->map(fn($type) => [
            'name' => $type->name,
            'y' => $getQuantity($type->id)
        ]);

        // Reusable function for waste sum by date range
        $getWasteSum = function ($range) use ($centerIds, $facilityIds, $producerIds) {
            if ($centerIds) {
                return WasteCollection::where('archive', 0)
                    ->whereBetween('created_at', $range)
                    ->whereIn('collection_center_id', $centerIds)
                    ->sum('quantity');
            }

            $query = RecyclingWasteCollection::whereBetween('created_at', $range)->where('archive', 0);
            if ($facilityIds) $query->whereIn('facility_id', $facilityIds);
            if ($producerIds) $query->whereIn('producer_id', $producerIds);

            return $query->get()->flatMap->items->sum('quantity');
        };

        // Date ranges
        $thisMonth = [now()->subDays(7), now()];
        $lastMonth = [now()->subDays(14), now()->subDays(7)];

        // Waste stats
        $currentWaste = $getWasteSum($thisMonth);
        $previousWaste = $getWasteSum($lastMonth);
        $totalWasteCollected = $getWasteSum([now()->startOfYear(), now()]);

        $wasteChange = $previousWaste ? (($currentWaste - $previousWaste) / $previousWaste) * 100 : 0;

        // CO2 Reduced
        $co2Reduced = round($totalWasteCollected * 0.49, 2);
        $co2Change = $previousWaste ? (($currentWaste - $previousWaste) / $previousWaste) * 100 : 0;

        // Active Users
        $currentActive = User::where('status', 'active')->whereBetween('created_at', $thisMonth)->count();
        $previousActive = User::where('status', 'active')->whereBetween('created_at', $lastMonth)->count();
        $activeUsers = User::where('status', 'active')->count();
        $activeChange = $previousActive ? (($currentActive - $previousActive) / $previousActive) * 100 : 0;

        // Carbon Credits
        $carbonCredits = round($totalWasteCollected / 1000);
        $carbonChange = $previousWaste ? (($currentWaste - $previousWaste) / $previousWaste) * 100 : 0;

        // Waste collection listing
        $wasteCollections = WasteCollection::with(['wastePicker', 'wasteType', 'unit', 'color'])
            ->where('archive', 0);
        if ($user && $user->role == 3) {
            $centerIds = $user->collection_centers()->pluck('collection_center_id');
            $wasteCollections->whereIn('collection_center_id', $centerIds);
        }

        if(auth()->user()->role == 3){
            $view ="collection_center";
        }
        if(auth()->user()->role == 4){
            $view ="facility";
        }
        else{
            $view ="admin";
        }


        return view('kmj.index', compact(
            'settings', 'recentUsers', 'smartBins',
            'months', 'parentWasteTypes', 'childWasteTypes',
            'totalWasteCollected', 'co2Reduced', 'activeUsers', 'carbonCredits',
            'wasteChange', 'co2Change', 'activeChange', 'carbonChange',
            'wasteCollections','activeWastePickers','totalCustomers','listCustomers'
        ));
    }


    public function notification()
    {
        $notifications =PusherNotification::where('user_id',\Auth::user()->id)->orderBy('id','desc')->get();
        return view('dashboard.notification',compact('notifications'));

    }


}
