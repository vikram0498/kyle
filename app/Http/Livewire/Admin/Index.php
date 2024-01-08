<?php

namespace App\Http\Livewire\Admin;

use Carbon\Carbon;
use App\Models\Buyer;
use App\Models\BuyerPlan;
use App\Models\PurchasedBuyer;
use App\Models\ProfileVerification;
use App\Models\SearchLog;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use LivewireAlert;

    protected $layout = null;
    
    public $buyerLineChartFilter='hourly', $timeFilterArray = ['hourly','weekly','monthly'],$buyerLineChartRecords;

    public $propertyChartDetails, $propertyTimeFilter='hourly', $propertyFilter='location';

    public $allProfileTags, $profileTimeFilter = 'hourly', $profileFilter = 'profile-tags', $profileChartDetails;

    public  $userTimeFilter = 'hourly',  $userChartDetails;

    public $sellerTimeFilter = 'hourly', $sellerLineChartRecords; 

    public function mount(){
       
        $this->userChartDetails = $this->getDetailsUserChart();

        $this->buyerLineChartRecords = $this->getDetailsBuyerLineChart();
        
        $this->sellerLineChartRecords = $this->getDetailsSellerLineChart();

        $this->propertyChartDetails = $this->getDetailsPropertyChart();

        $this->allProfileTags = BuyerPlan::where('status',1)->get();

        $this->profileChartDetails = $this->getDetailsProfileChart();

    }
    
    public function updatedBuyerLineChartFilter($value){
        $this->reset('buyerLineChartRecords');
        
        if(in_array($value,$this->timeFilterArray)){
            $this->buyerLineChartFilter = $value;
        }else{
            $this->buyerLineChartFilter = 'hourly';
            $this->alert('error','Invalid Value Selected!');
        }

        $this->buyerLineChartRecords = $this->getDetailsBuyerLineChart();

        $this->dispatchBrowserEvent('renderBuyerLineChart',$this->buyerLineChartRecords); 

    }

    public function getDetailsBuyerLineChart(){
        
        $chartRecords['bottomLabels'] = [];
        $chartRecords['topTitle'] = 'Buyer Metric';
        $chartRecords['xAxisTitle'] = '';  
        $chartRecords['yAxisTitle'] = 'Number of buyers';
        $chartRecords['activeUserRecords'] =[];
        $chartRecords['inactiveUserRecords'] =[]; 
        $chartRecords['buyerLineChartFilter']=$this->buyerLineChartFilter;

        if($this->buyerLineChartFilter == 'hourly'){
            $chartRecords['xAxisTitle'] = 'Last 24 hour';
            
            $activeRecords = User::select(
                DB::raw('IF(TIMESTAMPDIFF(HOUR, login_at, NOW()) = 23, 24, TIMESTAMPDIFF(HOUR, login_at, NOW())) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->whereHas('roles',function($query){
                $query->where('id',3);
            })
            ->where('login_at', '>=', DB::raw('NOW() - INTERVAL 24 HOUR'))
            ->where('login_at', '<=', DB::raw('NOW()'))
            ->groupBy(DB::raw('TIMESTAMPDIFF(HOUR, login_at, NOW())'))
            ->orderByRaw('HOUR(login_at) DESC')
            ->get();

            $inactiveRecords = User::selectRaw('IF(TIMESTAMPDIFF(HOUR, login_at, NOW()) = 23, 24, TIMESTAMPDIFF(HOUR, login_at, NOW())) as hour')
            ->selectRaw('COUNT(*) as count')
            ->where(function ($query) {
                $query->where('login_at', '<=', DB::raw('NOW() - INTERVAL 24 HOUR'))
                ->orWhereNull('login_at');
            })
            ->whereHas('roles',function($query){
                $query->where('id',3);
            })
            ->groupBy(DB::raw('TIMESTAMPDIFF(HOUR, login_at, NOW())'))
            ->orderByRaw('HOUR(login_at) DESC')
            ->get();


            $intervals = $this->hourInterval();

            $chartRecords['bottomLabels'] = $intervals;

            $dateRange = collect($intervals);
            $chartRecords['activeUserRecords'] = $dateRange->map(function ($hour) use ($activeRecords) {
                $record = $activeRecords->firstWhere('hour', $hour);
                
                return $record ? $record->count : 0;
            })->toArray();


            $chartRecords['inactiveUserRecords'] = $dateRange->map(function ($hour) use ($inactiveRecords) {
                $record = $inactiveRecords->where('hour',$hour)->where('hour','!=',null)->first();
              
                $above24HourRecordCount = $inactiveRecords->where('hour','>',24)->sum('count');
                $nullRecordCount = $inactiveRecords->where('hour',null)->value('count');

                if(!$record){      
                    if($hour === 24){
                        return (int)$nullRecordCount + (int)$above24HourRecordCount;
                    }else{
                        return 0;
                    }

                }else{
                    if($hour === 24){
                        return (int)$nullRecordCount + (int)$above24HourRecordCount;
                    }else{
                        return $record->count;
                    }                   
                }
              
            })->toArray();

        }elseif($this->buyerLineChartFilter == 'weekly'){

            $chartRecords['xAxisTitle'] = 'Last 7 days';

            $sevenDaysAgo = Carbon::now()->subDays(7);

            $activeRecords = User::query()->whereHas('roles',function($query){
                $query->where('id',3);
            })->selectRaw('id,DATE(login_at) as date, COUNT(*) as count')
            ->whereDate('login_at', '>=', $sevenDaysAgo)
            ->groupBy('date')
            ->orderBy('date')->get();

            $inactiveRecords = User::query()->whereHas('roles',function($query){
                $query->where('id',3);
            })->selectRaw('DATE(login_at) as date, COUNT(*) as count')
            ->where(function ($query) use($sevenDaysAgo) {
                $query->where('login_at', '<=', $sevenDaysAgo)
                ->orWhereNull('login_at');
            })
            ->groupBy('date')
            ->orderBy('date')->get();

            // Create a date range for the last 7 days
            $dateRange = $this->weeklyInterval();

            $chartRecords['activeUserRecords'] = $dateRange->map(function ($dateItem) use ($activeRecords) {
                $record = $activeRecords->firstWhere('date', $dateItem);
                return $record ? $record->count : 0;
            })->toArray();

            $chartRecords['inactiveUserRecords'] = $dateRange->map(function ($dateItem) use ($inactiveRecords,$sevenDaysAgo) {
                $record = $inactiveRecords->firstWhere('date', $dateItem);
                // return $record ? $record->count : 0;

                $above7DaysRecordCount = $inactiveRecords->where('date','<',$sevenDaysAgo)->sum('count');
                $nullRecordCount = $inactiveRecords->where('date',null)->value('count');

                if(!$record){      
                    if($dateItem === $sevenDaysAgo->format('Y-m-d')){
                        return (int)$nullRecordCount + (int)$above7DaysRecordCount;
                    }else{
                        return 0;
                    }

                }else{
                    if($dateItem === $sevenDaysAgo->format('Y-m-d')){
                        return (int)$nullRecordCount + (int)$above7DaysRecordCount;
                    }else{
                        return $record->count;
                    }                   
                }
                
                
            })->toArray();

            $chartRecords['bottomLabels'] = $dateRange->map(function($dateValue){
                return  Carbon::parse($dateValue)->format('l');
            })->toArray();

          

        }elseif($this->buyerLineChartFilter == 'monthly'){
          
            $chartRecords['xAxisTitle'] = 'Last 30 days';

            // Get the current date and time
            $current = Carbon::now();

            // Get the date and time 30 days ago
            $thirtyDaysAgo = $current->copy()->subDays(30);

            $activeRecords = User::query()->whereHas('roles',function($query){
                $query->where('id',3);
            })->selectRaw('id,DATE(login_at) as date, COUNT(*) as count')
            ->whereDate('login_at', '>=', $thirtyDaysAgo)
            ->groupBy('date')
            ->orderBy('date','desc')->get();

            $inactiveRecords = User::query()->whereHas('roles',function($query){
                $query->where('id',3);
            })->selectRaw('DATE(login_at) as date, COUNT(*) as count')
            ->where(function ($query) use($thirtyDaysAgo) {
                $query->where('login_at', '<=', $thirtyDaysAgo)
                ->orWhereNull('login_at');
            })
            ->groupBy('date')
            ->orderBy('date','desc')->get();

            // Create a date range for the last 30 days
            $dateRange = $this->monthlyInterval();

            $chartRecords['activeUserRecords'] = $dateRange->map(function ($dateItem) use ($activeRecords) {
                $record = $activeRecords->firstWhere('date', $dateItem);
                return $record ? $record->count : 0;
                
            })->toArray();

            $chartRecords['inactiveUserRecords'] = $dateRange->map(function ($dateItem) use ($inactiveRecords,$thirtyDaysAgo) {
                $record = $inactiveRecords->firstWhere('date', $dateItem);
                // return $record ? $record->count : 0;

                $above30DaysRecordCount = $inactiveRecords->where('date','<',$thirtyDaysAgo)->sum('count');
                $nullRecordCount = $inactiveRecords->where('date',null)->value('count');

                if(!$record){      
                    if($dateItem === $thirtyDaysAgo->format('Y-m-d')){
                        return (int)$nullRecordCount + (int)$above30DaysRecordCount;
                    }else{
                        return 0;
                    }

                }else{
                    if($dateItem === $thirtyDaysAgo->format('Y-m-d')){
                        return (int)$nullRecordCount + (int)$above30DaysRecordCount;
                    }else{
                        return $record->count;
                    }                   
                }
                
            })->toArray();

            $chartRecords['bottomLabels'] = $dateRange->toArray();
         
        }

        //  dd($inactiveRecords,$chartRecords);
         
      return $chartRecords;
    }
    
    public function updatedSellerTimeFilter($value){
        $this->reset('sellerLineChartRecords');
        
        if(in_array($value,$this->timeFilterArray)){
            $this->sellerTimeFilter = $value;
        }else{
            $this->sellerTimeFilter = 'hourly';
            $this->alert('error','Invalid Value Selected!');
        }

        $this->sellerLineChartRecords = $this->getDetailsSellerLineChart();

        $this->dispatchBrowserEvent('renderSellerLineChart',$this->sellerLineChartRecords); 

    }

    public function getDetailsSellerLineChart(){
        
        $chartRecords['bottomLabels'] = [];
        $chartRecords['topTitle'] = 'Whole seller Metric';
        $chartRecords['xAxisTitle'] = '';  
        $chartRecords['yAxisTitle'] = 'Number of whole sellers';
        $chartRecords['activeUserRecords'] =[];
        $chartRecords['inactiveUserRecords'] =[]; 
        $chartRecords['sellerTimeFilter']=$this->sellerTimeFilter;

        if($this->sellerTimeFilter == 'hourly'){
            $chartRecords['xAxisTitle'] = 'Last 24 hour';
            
            $activeRecords = User::select(
                DB::raw('IF(TIMESTAMPDIFF(HOUR, login_at, NOW()) = 23, 24, TIMESTAMPDIFF(HOUR, login_at, NOW())) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->whereHas('roles',function($query){
                $query->where('id',2);
            })
            ->where('login_at', '>=', DB::raw('NOW() - INTERVAL 24 HOUR'))
            ->where('login_at', '<=', DB::raw('NOW()'))
            ->groupBy(DB::raw('TIMESTAMPDIFF(HOUR, login_at, NOW())'))
            ->orderByRaw('HOUR(login_at) DESC')
            ->get();

            $inactiveRecords = User::selectRaw('IF(TIMESTAMPDIFF(HOUR, login_at, NOW()) = 23, 24, TIMESTAMPDIFF(HOUR, login_at, NOW())) as hour')
            ->selectRaw('COUNT(*) as count')
            ->where(function ($query) {
                $query->where('login_at', '<=', DB::raw('NOW() - INTERVAL 24 HOUR'))
                ->orWhereNull('login_at');
            })
            ->whereHas('roles',function($query){
                $query->where('id',2);
            })
            ->groupBy(DB::raw('TIMESTAMPDIFF(HOUR, login_at, NOW())'))
            ->orderByRaw('HOUR(login_at) DESC')
            ->get();


            $intervals = $this->hourInterval();

            $chartRecords['bottomLabels'] = $intervals;

            $dateRange = collect($intervals);
            $chartRecords['activeUserRecords'] = $dateRange->map(function ($hour) use ($activeRecords) {
                $record = $activeRecords->firstWhere('hour', $hour);
                
                return $record ? $record->count : 0;
            })->toArray();


            $chartRecords['inactiveUserRecords'] = $dateRange->map(function ($hour) use ($inactiveRecords) {
                $record = $inactiveRecords->where('hour',$hour)->where('hour','!=',null)->first();
              
                $above24HourRecordCount = $inactiveRecords->where('hour','>',24)->sum('count');
                $nullRecordCount = $inactiveRecords->where('hour',null)->value('count');

                if(!$record){      
                    if($hour === 24){
                        return (int)$nullRecordCount + (int)$above24HourRecordCount;
                    }else{
                        return 0;
                    }

                }else{
                    if($hour === 24){
                        return (int)$nullRecordCount + (int)$above24HourRecordCount;
                    }else{
                        return $record->count;
                    }                   
                }
              
            })->toArray();

        }elseif($this->sellerTimeFilter == 'weekly'){

            $chartRecords['xAxisTitle'] = 'Last 7 days';

            $sevenDaysAgo = Carbon::now()->subDays(7);

            $activeRecords = User::query()->whereHas('roles',function($query){
                $query->where('id',2);
            })->selectRaw('id,DATE(login_at) as date, COUNT(*) as count')
            ->whereDate('login_at', '>=', $sevenDaysAgo)
            ->groupBy('date')
            ->orderBy('date')->get();

            $inactiveRecords = User::query()->whereHas('roles',function($query){
                $query->where('id',2);
            })->selectRaw('DATE(login_at) as date, COUNT(*) as count')
            ->where(function ($query) use($sevenDaysAgo) {
                $query->where('login_at', '<=', $sevenDaysAgo)
                ->orWhereNull('login_at');
            })
            ->groupBy('date')
            ->orderBy('date')->get();

            // Create a date range for the last 7 days
            $dateRange = $this->weeklyInterval();

            $chartRecords['activeUserRecords'] = $dateRange->map(function ($dateItem) use ($activeRecords) {
                $record = $activeRecords->firstWhere('date', $dateItem);
                return $record ? $record->count : 0;
            })->toArray();

            $chartRecords['inactiveUserRecords'] = $dateRange->map(function ($dateItem) use ($inactiveRecords,$sevenDaysAgo) {
                $record = $inactiveRecords->firstWhere('date', $dateItem);
                // return $record ? $record->count : 0;

                $above7DaysRecordCount = $inactiveRecords->where('date','<',$sevenDaysAgo)->sum('count');
                $nullRecordCount = $inactiveRecords->where('date',null)->value('count');

                if(!$record){      
                    if($dateItem === $sevenDaysAgo->format('Y-m-d')){
                        return (int)$nullRecordCount + (int)$above7DaysRecordCount;
                    }else{
                        return 0;
                    }

                }else{
                    if($dateItem === $sevenDaysAgo->format('Y-m-d')){
                        return (int)$nullRecordCount + (int)$above7DaysRecordCount;
                    }else{
                        return $record->count;
                    }                   
                }
                
                
            })->toArray();

            $chartRecords['bottomLabels'] = $dateRange->map(function($dateValue){
                return  Carbon::parse($dateValue)->format('l');
            })->toArray();

          

        }elseif($this->sellerTimeFilter == 'monthly'){
          
            $chartRecords['xAxisTitle'] = 'Last 30 days';

            // Get the current date and time
            $current = Carbon::now();

            // Get the date and time 30 days ago
            $thirtyDaysAgo = $current->copy()->subDays(30);

            $activeRecords = User::query()->whereHas('roles',function($query){
                $query->where('id',2);
            })->selectRaw('id,DATE(login_at) as date, COUNT(*) as count')
            ->whereDate('login_at', '>=', $thirtyDaysAgo)
            ->groupBy('date')
            ->orderBy('date','desc')->get();

            $inactiveRecords = User::query()->whereHas('roles',function($query){
                $query->where('id',2);
            })->selectRaw('DATE(login_at) as date, COUNT(*) as count')
            ->where(function ($query) use($thirtyDaysAgo) {
                $query->where('login_at', '<=', $thirtyDaysAgo)
                ->orWhereNull('login_at');
            })
            ->groupBy('date')
            ->orderBy('date','desc')->get();

            // Create a date range for the last 30 days
            $dateRange = $this->monthlyInterval();

            $chartRecords['activeUserRecords'] = $dateRange->map(function ($dateItem) use ($activeRecords) {
                $record = $activeRecords->firstWhere('date', $dateItem);
                return $record ? $record->count : 0;
                
            })->toArray();

            $chartRecords['inactiveUserRecords'] = $dateRange->map(function ($dateItem) use ($inactiveRecords,$thirtyDaysAgo) {
                $record = $inactiveRecords->firstWhere('date', $dateItem);
                // return $record ? $record->count : 0;

                $above30DaysRecordCount = $inactiveRecords->where('date','<',$thirtyDaysAgo)->sum('count');
                $nullRecordCount = $inactiveRecords->where('date',null)->value('count');

                if(!$record){      
                    if($dateItem === $thirtyDaysAgo->format('Y-m-d')){
                        return (int)$nullRecordCount + (int)$above30DaysRecordCount;
                    }else{
                        return 0;
                    }

                }else{
                    if($dateItem === $thirtyDaysAgo->format('Y-m-d')){
                        return (int)$nullRecordCount + (int)$above30DaysRecordCount;
                    }else{
                        return $record->count;
                    }                   
                }
                
            })->toArray();

            $chartRecords['bottomLabels'] = $dateRange->toArray();
         
        }

        //  dd($inactiveRecords,$chartRecords);
         
      return $chartRecords;
    }

    public function render()
    {
        $sellerCount = User::whereHas('roles', function($q){
            $q->where('id', 2);
        })->count();
        $buyerCount = Buyer::count();

        $purchasedBuyers = DB::table('purchased_buyers')
        ->join('buyers', function ($join) {
            $join->on('purchased_buyers.buyer_id', '=', 'buyers.id')
                 ->where('buyers.user_id', '=', 1)->where('buyers.deleted_at','=',null);
        })
        ->join('users', 'purchased_buyers.user_id', '=', 'users.id')
        ->join('users AS buyer_user', 'buyers.buyer_user_id', '=', 'buyer_user.id')
        ->where('purchased_buyers.user_id', '!=', 1)
        ->groupBy('purchased_buyers.buyer_id')
        ->select(
            'purchased_buyers.buyer_id',
            'purchased_buyers.user_id',
            'users.first_name AS user_first_name',
            'users.last_name AS user_last_name',
            'buyer_user.first_name AS buyer_first_name',
            'buyer_user.last_name AS buyer_last_name',
            DB::raw('MAX(purchased_buyers.created_at) AS max_created_at')
        )
        ->orderByDesc('max_created_at')
        ->limit(5)
        ->get();

        return view('livewire.admin.index', compact('buyerCount', 'sellerCount','purchasedBuyers'));
    }

    public function updatedPropertyTimeFilter($value){
        $this->reset(['propertyChartDetails']);

        if(in_array($value,$this->timeFilterArray)){
            $this->propertyTimeFilter = $value;
        }else{
            $this->propertyTimeFilter = 'hourly';
            $this->alert('error','Invalid Value Selected!');
        }

        $this->propertyChartDetails = $this->getDetailsPropertyChart();
        $this->dispatchBrowserEvent('renderPropertyChart',$this->propertyChartDetails); 
    }

    public function updatedPropertyFilter($value){
        $this->reset(['propertyChartDetails']);

        if(in_array($value,['location','type'])){
            $this->propertyFilter = $value;
        }else{
            $this->propertyFilter = 'location';
            $this->alert('error','Invalid Value Selected!');
        }

        $this->propertyChartDetails = $this->getDetailsPropertyChart();
        $this->dispatchBrowserEvent('renderPropertyChart',$this->propertyChartDetails); 
    }

    public function getDetailsPropertyChart(){

        $chartRecords['bottomLabels'] = [];
        $chartRecords['topTitle'] = 'Property Metric';
        $chartRecords['xAxisTitle'] = '';  
        $chartRecords['yAxisTitle'] = 'Number of search';
        $chartRecords['propertyFilter']=$this->propertyFilter;
        $chartRecords['propertyTimeFilter']=$this->propertyTimeFilter;

        if($this->propertyTimeFilter == 'hourly'){
            $chartRecords['xAxisTitle'] = 'Last 24 Hours'; 

            $intervals = $this->hourInterval();

            $chartRecords['bottomLabels'] = $intervals;

            $dateRange = collect($intervals);

            if($this->propertyFilter == 'type'){

                $recordCollection = collect($chartRecords);
                $newValues = $this->getPropertyTypeDetails($dateRange);
                $chartRecords = $recordCollection->merge($newValues);

            }elseif($this->propertyFilter == 'location'){

                $recordCollection = collect($chartRecords);
                $newValues = $this->getPropertyLocationDetails($dateRange);
                $chartRecords = $recordCollection->merge($newValues);
                
            }
        }elseif($this->propertyTimeFilter == 'weekly'){
            $chartRecords['xAxisTitle'] = 'Last 7 days';  

            $dateRange = $this->weeklyInterval();
            
            $chartRecords['bottomLabels'] = $dateRange->map(function($dateValue){
                return  Carbon::parse($dateValue)->format('l');
            })->toArray();

            if($this->propertyFilter == 'type'){

                $recordCollection = collect($chartRecords);
                $newValues = $this->getPropertyTypeDetails($dateRange);
                $chartRecords = $recordCollection->merge($newValues);

            }elseif($this->propertyFilter == 'location'){

                $recordCollection = collect($chartRecords);
                $newValues = $this->getPropertyLocationDetails($dateRange);
                $chartRecords = $recordCollection->merge($newValues);

            }


        }elseif($this->propertyTimeFilter == 'monthly'){
            $chartRecords['xAxisTitle'] = 'Last 30 days';  

            $dateRange = $this->monthlyInterval();

            $chartRecords['bottomLabels'] = $dateRange;

            if($this->propertyFilter == 'type'){

                $recordCollection = collect($chartRecords);
                $newValues = $this->getPropertyTypeDetails($dateRange);
                $chartRecords = $recordCollection->merge($newValues);

            }elseif($this->propertyFilter == 'location'){

                $recordCollection = collect($chartRecords);
                $newValues = $this->getPropertyLocationDetails($dateRange);
                $chartRecords = $recordCollection->merge($newValues);

            }

        }

        // dd($chartRecords);

        return $chartRecords;
    }


    public function getPropertyLocationDetails($dateRange){
      
        $chartData['railroad'] = $this->fetchPropertyLocationQuery($dateRange,1);
        $chartData['major_road'] = $this->fetchPropertyLocationQuery($dateRange,2);
        $chartData['boarders_non_residential'] = $this->fetchPropertyLocationQuery($dateRange,3);

        return $chartData;
    }

    public function fetchPropertyLocationQuery($dateRange,$value){
        if($this->propertyTimeFilter == 'hourly'){
 
            $reqQuery = SearchLog::select(
                DB::raw('IF(TIMESTAMPDIFF(HOUR, created_at, NOW()) = 23, 24, TIMESTAMPDIFF(HOUR, created_at, NOW())) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                      ->from('search_logs')
                      ->where('created_at', '>=', DB::raw('NOW() - INTERVAL 24 HOUR'))
                      ->where('created_at', '<=', DB::raw('NOW()'))
                      ->groupBy(DB::raw('TIMESTAMPDIFF(HOUR, created_at, NOW())'));
            })
            ->where(function ($query) use ($value) {
                $query->orWhereJsonContains("property_flaw", $value);
            })
            ->orderByRaw('HOUR(created_at) DESC')
            ->groupBy(DB::raw('hour(created_at)'))
            ->get();

            $data = $dateRange->map(function ($hour) use ($reqQuery) {
                $record = $reqQuery->firstWhere('hour', $hour);
                return $record ? $record->count : 0;
            })->toArray();

            return $data;

        }else if($this->propertyTimeFilter == 'weekly'){

            $sevenDaysAgo = Carbon::now()->subDays(7);

            $reqQuery = SearchLog::query()->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereDate('created_at', '>=', $sevenDaysAgo)
            ->where(function ($query) use ($value) {
                $query->orWhereJsonContains("property_flaw", $value);
            })
            ->groupBy('date')
            ->orderBy('date')->get();

            $data = $dateRange->map(function ($dateItem) use ($reqQuery) {
                $record = $reqQuery->firstWhere('date', $dateItem);
                return $record ? $record->count : 0;
            })->toArray();

            return $data;
             
        }else if($this->propertyTimeFilter == 'monthly'){

            $current = Carbon::now();
            $thirtyDaysAgo = $current->copy()->subDays(30);

            $reqQuery = SearchLog::query()->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where(function ($query) use ($value) {
                $query->orWhereJsonContains("property_flaw", $value);
            })
            ->whereDate('created_at', '>=', $thirtyDaysAgo)
            ->groupBy('date')
            ->orderBy('date','desc')->get();

            $data = $dateRange->map(function ($dateItem) use ($reqQuery) {
                $record = $reqQuery->firstWhere('date', $dateItem);
                return $record ? $record->count : 0;
            })->toArray();

            return $data;
        }
    }

    public function getPropertyTypeDetails($dateRange){
      
        $chartData['commercial_retail'] = $this->fetchPropertyTypeQuery($dateRange,3);
        $chartData['condo'] = $this->fetchPropertyTypeQuery($dateRange,4);
        $chartData['land'] = $this->fetchPropertyTypeQuery($dateRange,7);
        $chartData['manufactured'] = $this->fetchPropertyTypeQuery($dateRange,8);
        $chartData['multi_family_commercial'] = $this->fetchPropertyTypeQuery($dateRange,10);
        $chartData['multi_family_residential'] = $this->fetchPropertyTypeQuery($dateRange,11);
        $chartData['single_family'] = $this->fetchPropertyTypeQuery($dateRange,12);
        $chartData['townhouse'] = $this->fetchPropertyTypeQuery($dateRange,13);
        $chartData['mobile_home_park'] = $this->fetchPropertyTypeQuery($dateRange,14);
        $chartData['hotel_motel'] = $this->fetchPropertyTypeQuery($dateRange,15);

        return $chartData;
    }

    public function fetchPropertyTypeQuery($dateRange,$value){

        if($this->propertyTimeFilter == 'hourly'){

            $reqQuery = SearchLog::select(
                DB::raw('IF(TIMESTAMPDIFF(HOUR, created_at, NOW()) = 23, 24, TIMESTAMPDIFF(HOUR, created_at, NOW())) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                      ->from('search_logs')
                      ->where('created_at', '>=', DB::raw('NOW() - INTERVAL 24 HOUR'))
                      ->where('created_at', '<=', DB::raw('NOW()'))
                      ->groupBy(DB::raw('TIMESTAMPDIFF(HOUR, created_at, NOW())'));
            })
            ->where('property_type',$value)
            ->orderByRaw('HOUR(created_at) DESC')
            ->groupBy(DB::raw('hour(created_at)'))
            ->get();

            $data = $dateRange->map(function ($hour) use ($reqQuery) {
                $record = $reqQuery->firstWhere('hour', $hour);
                return $record ? $record->count : 0;
            })->toArray();

            return $data;

        }else if($this->propertyTimeFilter == 'weekly'){

            $sevenDaysAgo = Carbon::now()->subDays(7);

            $reqQuery = SearchLog::query()->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereDate('created_at', '>=', $sevenDaysAgo)
            ->where('property_type',$value)
            ->groupBy('date')
            ->orderBy('date')->get();

            $data = $dateRange->map(function ($dateItem) use ($reqQuery) {
                $record = $reqQuery->firstWhere('date', $dateItem);
                return $record ? $record->count : 0;
            })->toArray();

            return $data;
             
        }else if($this->propertyTimeFilter == 'monthly'){

            $current = Carbon::now();
            $thirtyDaysAgo = $current->copy()->subDays(30);

            $reqQuery = SearchLog::query()->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('property_type',$value)
            ->whereDate('created_at', '>=', $thirtyDaysAgo)
            ->groupBy('date')
            ->orderBy('date','desc')->get();

            $data = $dateRange->map(function ($dateItem) use ($reqQuery) {
                $record = $reqQuery->firstWhere('date', $dateItem);
                return $record ? $record->count : 0;
            })->toArray();

            return $data;
        }

    }


    public function updatedProfileTimeFilter($value){
        $this->reset(['profileChartDetails']);

        if(in_array($value,$this->timeFilterArray)){
            $this->profileTimeFilter = $value;
        }else{
            $this->profileTimeFilter = 'hourly';
            $this->alert('error','Invalid Value Selected!');
        }

        $this->profileChartDetails = $this->getDetailsProfileChart();
        $this->dispatchBrowserEvent('renderProfileChart',$this->profileChartDetails); 
    }

    public function updatedProfileFilter($value){
        $this->reset(['profileChartDetails']);

        if(in_array($value,['profile-tags','verification-levels'])){
            $this->profileFilter = $value;
        }else{
            $this->profileFilter = 'profile-tags';
            $this->alert('error','Invalid Value Selected!');
        }

        $this->profileChartDetails = $this->getDetailsProfileChart();
        $this->dispatchBrowserEvent('renderProfileChart',$this->profileChartDetails); 
    }

    public function getDetailsProfileChart(){
        $chartRecords['bottomLabels'] = [];
        $chartRecords['topTitle'] = 'Profile Metric';
        $chartRecords['xAxisTitle'] = '';  
        $chartRecords['yAxisTitle'] = 'Number of buyers';
        $chartRecords['profileFilter'] = $this->profileFilter;
        $chartRecords['profileTimeFilter'] = $this->profileTimeFilter;
       
        if($this->profileTimeFilter == 'hourly'){
            $chartRecords['xAxisTitle'] = 'Last 24 Hours';  
        
            $intervals = $this->hourInterval();

            $chartRecords['bottomLabels'] = $intervals;

            $dateRange = collect($intervals);

            if($this->profileFilter == 'profile-tags'){

                $recordCollection = collect($chartRecords);
                $newValues = $this->getProfileTagsDetails($dateRange);
                $chartRecords = $recordCollection->merge($newValues);

            }elseif($this->profileFilter == 'verification-levels'){

                $recordCollection = collect($chartRecords);
                $newValues = $this->getVerificationLevelsDetails($dateRange);
                $chartRecords = $recordCollection->merge($newValues);
                
            }
        }elseif($this->profileTimeFilter == 'weekly'){
            $chartRecords['xAxisTitle'] = 'Last 7 days';  

            $dateRange = $this->weeklyInterval();
            
            $chartRecords['bottomLabels'] = $dateRange->map(function($dateValue){
                return  Carbon::parse($dateValue)->format('l');
            })->toArray();

            if($this->profileFilter == 'profile-tags'){

                $recordCollection = collect($chartRecords);
                $newValues = $this->getProfileTagsDetails($dateRange);
                $chartRecords = $recordCollection->merge($newValues);

            }elseif($this->profileFilter == 'verification-levels'){

                $recordCollection = collect($chartRecords);
                $newValues = $this->getVerificationLevelsDetails($dateRange);
                $chartRecords = $recordCollection->merge($newValues);

            }


        }elseif($this->profileTimeFilter == 'monthly'){
            $chartRecords['xAxisTitle'] = 'Last 30 days';  

            $dateRange = $this->monthlyInterval();

            $chartRecords['bottomLabels'] = $dateRange;

            if($this->profileFilter == 'profile-tags'){

                $recordCollection = collect($chartRecords);
                $newValues = $this->getProfileTagsDetails($dateRange);
                $chartRecords = $recordCollection->merge($newValues);

            }elseif($this->profileFilter == 'verification-levels'){

                $recordCollection = collect($chartRecords);
                $newValues = $this->getVerificationLevelsDetails($dateRange);
                $chartRecords = $recordCollection->merge($newValues);

            }

        }

        // dd($chartRecords);
        return $chartRecords;
       
    }

    public function getProfileTagsDetails($dateRange){
      
        if(isset($this->allProfileTags)){

            foreach($this->allProfileTags as $tag){
                $chartData[$tag->plan_stripe_id] = $this->fetchProfileTagsQuery($dateRange,$tag->id);
            }

            return $chartData;
        }
      
    }


    public function getVerificationLevelsDetails($dateRange){
      
        $chartData['verified_user'] = $this->fetchProfileVerificationQuery($dateRange);

        return $chartData;
    }

    public function fetchProfileTagsQuery($dateRange,$value){

        if($this->profileTimeFilter == 'hourly'){

            $reqQuery = Buyer::query()->select(
                DB::raw('IF(TIMESTAMPDIFF(HOUR, updated_at, NOW()) = 23, 24, TIMESTAMPDIFF(HOUR, updated_at, NOW())) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                      ->from('buyers')
                      ->where('updated_at', '>=', DB::raw('NOW() - INTERVAL 24 HOUR'))
                      ->where('updated_at', '<=', DB::raw('NOW()'))
                      ->groupBy(DB::raw('TIMESTAMPDIFF(HOUR, updated_at, NOW())'));
            })
            ->where('plan_id', $value)
            ->whereNotNull('plan_id')
            ->whereNotNull('updated_at')
            ->orderByRaw('HOUR(updated_at) DESC')
            ->groupBy(DB::raw('hour(updated_at)'))
            ->get();

            $data = $dateRange->map(function ($hour) use ($reqQuery) {
                $record = $reqQuery->firstWhere('hour', $hour);
                return $record ? $record->count : 0;
            })->toArray();

            return $data;
          

        }else if($this->profileTimeFilter == 'weekly'){

            $sevenDaysAgo = Carbon::now()->subDays(7);

            $reqQuery = Buyer::query()->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
            ->whereDate('updated_at', '>=', $sevenDaysAgo)
            ->where('plan_id', $value)
            ->whereNotNull('plan_id')
            ->groupBy('date')
            ->orderBy('date')->get();

            $data = $dateRange->map(function ($dateItem) use ($reqQuery) {
                $record = $reqQuery->firstWhere('date', $dateItem);
                return $record ? $record->count : 0;
            })->toArray();

            return $data;
          
             
        }else if($this->profileTimeFilter == 'monthly'){

            $current = Carbon::now();
            $thirtyDaysAgo = $current->copy()->subDays(30);

            $reqQuery = Buyer::query()->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
            ->where('plan_id', $value)
            ->whereNotNull('plan_id')
            ->whereDate('updated_at', '>=', $thirtyDaysAgo)
            ->groupBy('date')
            ->orderBy('date','desc')->get();

            $data = $dateRange->map(function ($dateItem) use ($reqQuery) {
                $record = $reqQuery->firstWhere('date', $dateItem);
                return $record ? $record->count : 0;
            })->toArray();

            return $data;
          
        }

    }

    public function fetchProfileVerificationQuery($dateRange){

        if($this->profileTimeFilter == 'hourly'){

            $reqQuery = ProfileVerification::query()
            ->select(
                DB::raw('IF(TIMESTAMPDIFF(HOUR, updated_at, NOW()) = 23, 24, TIMESTAMPDIFF(HOUR, updated_at, NOW())) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                      ->from('profile_verifications')
                      ->where('updated_at', '>=', DB::raw('NOW() - INTERVAL 24 HOUR'))
                      ->where('updated_at', '<=', DB::raw('NOW()'))
                      ->groupBy(DB::raw('TIMESTAMPDIFF(HOUR, updated_at, NOW())'));
            })
            ->where('is_phone_verification', 1)
            ->where('is_driver_license',1)->where('driver_license_status','verified')
            ->where('is_proof_of_funds', 1)->where('proof_of_funds_status','verified')
            ->where('is_llc_verification',1)->where('llc_verification_status','verified')
            ->where('is_application_process',1)
            // ->whereRaw('EXTRACT(HOUR FROM updated_at) % 2 = 0')
            ->whereNotNull('updated_at')
            ->orderByRaw('HOUR(updated_at) DESC')
            ->groupBy(DB::raw('hour(updated_at)'))
            ->get();

            $data = $dateRange->map(function ($hour) use ($reqQuery) {
                $record = $reqQuery->firstWhere('hour', $hour);
                return $record ? $record->count : 0;
            })->toArray();

            return $data;
          

        }else if($this->profileTimeFilter == 'weekly'){

            $sevenDaysAgo = Carbon::now()->subDays(7);

            $reqQuery = ProfileVerification::query()->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
            ->whereDate('updated_at', '>=', $sevenDaysAgo)
            ->where('is_phone_verification', 1)
            ->where('is_driver_license',1)->where('driver_license_status','verified')
            ->where('is_proof_of_funds', 1)->where('proof_of_funds_status','verified')
            ->where('is_llc_verification',1)->where('llc_verification_status','verified')
            ->where('is_application_process',1)
            ->groupBy('date')
            ->orderBy('date')->get();

            $data = $dateRange->map(function ($dateItem) use ($reqQuery) {
                $record = $reqQuery->firstWhere('date', $dateItem);
                return $record ? $record->count : 0;
            })->toArray();

            return $data;
          
             
        }else if($this->profileTimeFilter == 'monthly'){

            $current = Carbon::now();
            $thirtyDaysAgo = $current->copy()->subDays(30);

            $reqQuery = ProfileVerification::query()->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
            ->where('is_phone_verification', 1)
            ->where('is_driver_license',1)->where('driver_license_status','verified')
            ->where('is_proof_of_funds', 1)->where('proof_of_funds_status','verified')
            ->where('is_llc_verification',1)->where('llc_verification_status','verified')
            ->where('is_application_process',1)
            ->whereDate('updated_at', '>=', $thirtyDaysAgo)
            ->groupBy('date')
            ->orderBy('date','desc')->get();

            $data = $dateRange->map(function ($dateItem) use ($reqQuery) {
                $record = $reqQuery->firstWhere('date', $dateItem);
                return $record ? $record->count : 0;
            })->toArray();

            return $data;
        }

    }

    public function updatedUserTimeFilter($value){
        $this->reset(['userChartDetails']);

        if(in_array($value,$this->timeFilterArray)){
            $this->userTimeFilter = $value;
        }else{
            $this->userTimeFilter = 'hourly';
            $this->alert('error','Invalid Value Selected!');
        }

        $this->userChartDetails = $this->getDetailsUserChart();
        $this->dispatchBrowserEvent('renderUserChart',$this->userChartDetails); 
    }

    public function getDetailsUserChart(){
      
        $chartRecords['bottomLabels'] = [];
        $chartRecords['topTitle'] = 'User Metric';
        $chartRecords['xAxisTitle'] = '';  
        $chartRecords['yAxisTitle'] = 'Number of users';
        $chartRecords['userTimeFilter']=$this->userTimeFilter;

        if($this->userTimeFilter == 'hourly'){
            $chartRecords['xAxisTitle'] = 'Last 24 Hours'; 

            $intervals = $this->hourInterval();

            $chartRecords['bottomLabels'] = $intervals;

            $dateRange = collect($intervals);

            $recordCollection = collect($chartRecords);
            $newValues = $this->getUserChartData($dateRange);
            $chartRecords = $recordCollection->merge($newValues);

        }elseif($this->userTimeFilter == 'weekly'){
            $chartRecords['xAxisTitle'] = 'Last 7 days';  

            $dateRange = $this->weeklyInterval();
            
            $chartRecords['bottomLabels'] = $dateRange->map(function($dateValue){
                return  Carbon::parse($dateValue)->format('l');
            })->toArray();

            
            $recordCollection = collect($chartRecords);
            $newValues = $this->getUserChartData($dateRange);
            $chartRecords = $recordCollection->merge($newValues);

        }elseif($this->userTimeFilter == 'monthly'){
            $chartRecords['xAxisTitle'] = 'Last 30 days';  

            $dateRange = $this->monthlyInterval();

            $chartRecords['bottomLabels'] = $dateRange;

            $recordCollection = collect($chartRecords);
            $newValues = $this->getUserChartData($dateRange);
            $chartRecords = $recordCollection->merge($newValues);

        }


        return $chartRecords;
    }

    public function getUserChartData($dateRange){
      
        $chartData['seller_user'] = $this->fetchUserQuery($dateRange);
        $chartData['buyer_user'] = $this->fetchUserQuery($dateRange);


        return $chartData;
    }

    public function fetchUserQuery($dateRange){
        if($this->userTimeFilter == 'hourly'){
            $chartRecords['xAxisTitle'] = 'Last 24 hour';
            
            $sellerUserRecords = User::select(
                DB::raw('IF(TIMESTAMPDIFF(HOUR, created_at, NOW()) = 23, 24, TIMESTAMPDIFF(HOUR, created_at, NOW())) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->whereHas('roles',function($query){
                $query->where('id',2);
            })
            ->where('created_at', '>=', DB::raw('NOW() - INTERVAL 24 HOUR'))
            ->where('created_at', '<=', DB::raw('NOW()'))
            ->groupBy(DB::raw('TIMESTAMPDIFF(HOUR, created_at, NOW())'))
            ->orderByRaw('HOUR(created_at) DESC')
            ->get();

            $buyerUserRecords = User::select(
                DB::raw('IF(TIMESTAMPDIFF(HOUR, created_at, NOW()) = 23, 24, TIMESTAMPDIFF(HOUR, created_at, NOW())) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->whereHas('roles',function($query){
                $query->where('id',3);
            })
            ->where('created_at', '>=', DB::raw('NOW() - INTERVAL 24 HOUR'))
            ->where('created_at', '<=', DB::raw('NOW()'))
            ->groupBy(DB::raw('TIMESTAMPDIFF(HOUR, created_at, NOW())'))
            ->orderByRaw('HOUR(created_at) DESC')
            ->get();


            $intervals = $this->hourInterval();

            $chartRecords['bottomLabels'] = $intervals;

            $dateRange = collect($intervals);
            $chartRecords['sellerUserRecords'] = $dateRange->map(function ($hour) use ($sellerUserRecords) {
                $record = $sellerUserRecords->firstWhere('hour', $hour);
                
                return $record ? $record->count : 0;
            })->toArray();

            $chartRecords['buyerUserRecords'] = $dateRange->map(function ($hour) use ($buyerUserRecords) {
                $record = $buyerUserRecords->firstWhere('hour', $hour);
                
                return $record ? $record->count : 0;
            })->toArray();



        }elseif($this->userTimeFilter == 'weekly'){

            $chartRecords['xAxisTitle'] = 'Last 7 days';

            $sevenDaysAgo = Carbon::now()->subDays(7);

            $sellerUserRecords = User::query()->whereHas('roles',function($query){
                $query->where('id',2);
            })->selectRaw('id,DATE(created_at) as date, COUNT(*) as count')
            ->whereDate('created_at', '>=', $sevenDaysAgo)
            ->groupBy('date')
            ->orderBy('date')->get();


            $buyerUserRecords = User::query()->whereHas('roles',function($query){
                $query->where('id',3);
            })->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereDate('created_at', '>=', $sevenDaysAgo)
            ->groupBy('date')
            ->orderBy('date')->get();

            // Create a date range for the last 7 days
            $dateRange = $this->weeklyInterval();

            $chartRecords['sellerUserRecords'] = $dateRange->map(function ($dateItem) use ($sellerUserRecords) {
                $record = $sellerUserRecords->firstWhere('date', $dateItem);
                return $record ? $record->count : 0;
            })->toArray();

            $chartRecords['buyerUserRecords'] = $dateRange->map(function ($dateItem) use ($buyerUserRecords) {
                $record = $buyerUserRecords->firstWhere('date', $dateItem);
                return $record ? $record->count : 0;
            })->toArray();


            $chartRecords['bottomLabels'] = $dateRange->map(function($dateValue){
                return  Carbon::parse($dateValue)->format('l');
            })->toArray();


        }elseif($this->userTimeFilter == 'monthly'){
          
            $chartRecords['xAxisTitle'] = 'Last 30 days';

            // Get the current date and time
            $current = Carbon::now();

            // Get the date and time 30 days ago
            $thirtyDaysAgo = $current->copy()->subDays(30);

            $sellerUserRecords = User::query()->whereHas('roles',function($query){
                $query->where('id',2);
            })->selectRaw('id,DATE(created_at) as date, COUNT(*) as count')
            ->whereDate('created_at', '>=', $thirtyDaysAgo)
            ->groupBy('date')
            ->orderBy('date')->get();


            $buyerUserRecords = User::query()->whereHas('roles',function($query){
                $query->where('id',3);
            })->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereDate('created_at', '>=', $thirtyDaysAgo)
            ->groupBy('date')
            ->orderBy('date')->get();

            // Create a date range for the last 7 days
            $dateRange = $this->monthlyInterval();

            $chartRecords['sellerUserRecords'] = $dateRange->map(function ($dateItem) use ($sellerUserRecords) {
                $record = $sellerUserRecords->firstWhere('date', $dateItem);
                return $record ? $record->count : 0;
            })->toArray();

            $chartRecords['buyerUserRecords'] = $dateRange->map(function ($dateItem) use ($buyerUserRecords) {
                $record = $buyerUserRecords->firstWhere('date', $dateItem);
                return $record ? $record->count : 0;
            })->toArray();


            $chartRecords['bottomLabels'] = $dateRange->toArray();
         
        }

        // dd( $chartRecords );
        return $chartRecords;
    }

    public function hourInterval(){
      
        $intervals = [24,23,22,21,20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1,0];

        return $intervals;
    }


    public function weeklyInterval(){
        // Create a date range for the last 7 days
        $dateRange = collect();
        $startDate = now()->subDays(7);
        $endDate = now();
        
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dateRange->push($date->format('Y-m-d'));
        }

        return $dateRange;
    }

    public function monthlyInterval(){
        $dateRange = collect();
        $startDate = now()->subDays(30);
        $endDate = now();
        
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dateRange->push($date->format('Y-m-d'));
        }

        return $dateRange;
    }




}
