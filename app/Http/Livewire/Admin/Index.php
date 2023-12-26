<?php

namespace App\Http\Livewire\Admin;

use Carbon\Carbon;
use App\Models\Buyer;
use App\Models\PurchasedBuyer;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use LivewireAlert;

    protected $layout = null;
    
    public $buyerLineChartFilter='hourly', $buyerFilterArray = ['hourly','weekly','monthly'],$buyerLineChartRecords;

    public $propertyBarChartDetails, $propertyTimeFilter='hourly', $propertyFilter='location';

    public function mount(){
       
        $this->buyerLineChartRecords = $this->getDetailsBuyerLineChart();
        
        $this->propertyBarChartDetails = $this->getDetailsPropertyBarChart();
    }
    
    public function updatedBuyerLineChartFilter($value){
        $this->reset('buyerLineChartRecords');
        
        if(in_array($value,$this->buyerFilterArray)){
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

        if($this->buyerLineChartFilter == 'hourly'){
            $chartRecords['xAxisTitle'] = 'Last 24 hour';

            // Query the database for data in 2-hour intervals
            $activeRecords =User::query()->whereHas('roles',function($query){
                $query->where('id',3);
            })->whereNotNull('login_at')
            ->where('login_at', '>', now()->subHours(24))
            ->select('id',
                DB::raw('DATE_FORMAT(login_at, "%H") as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->whereRaw('EXTRACT(HOUR FROM login_at) % 2 = 0')
            ->whereNotNull('login_at')
            ->groupBy(DB::raw('EXTRACT(HOUR FROM login_at) % 2 = 0'))
            ->orderBy(DB::raw('EXTRACT(HOUR FROM login_at) % 2 = 0'), 'asc')
            ->get();

            $inactiveRecords = User::query()->whereHas('roles',function($query){
                $query->where('id',3);
            })->whereNotIN('id', $activeRecords->pluck('id')->toArray())
            ->select(
                DB::raw('DATE_FORMAT(login_at, "%H") as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('EXTRACT(HOUR FROM login_at) % 2 = 0'))
            ->orderBy(DB::raw('EXTRACT(HOUR FROM login_at) % 2 = 0'), 'asc')
            ->get();

            // dd($inactiveRecords);
         
            $intervals = $this->hourlyInterval();
            $chartRecords['bottomLabels'] = $intervals;

            $dateRange = collect($intervals);
            $chartRecords['activeUserRecords'] = $dateRange->map(function ($hour) use ($activeRecords) {
                $record = $activeRecords->firstWhere('hour', $hour);
                return $record ? $record->count : 0;
            })->toArray();

            $chartRecords['inactiveUserRecords'] = $dateRange->map(function ($hour) use ($inactiveRecords) {
                $record = $inactiveRecords->firstWhere('hour', $hour);
                // return $record ? $record->count : 0;

                if(!$record){
                    $fetchRecord = $inactiveRecords->where('hour',null)->first();
                    if(($hour=='24') && $fetchRecord){
                        return $fetchRecord->count;
                    }else{
                        return 0;
                    }
                }else{
                    $fetchRecord = $inactiveRecords->where('hour',null)->first();
                    if(($hour=='24') && $fetchRecord){
                        return $record->count+$fetchRecord->count;
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
            ->whereDate('login_at', '>', $sevenDaysAgo)
            ->groupBy('date')
            ->orderBy('date')->get();

            $inactiveRecords = User::query()->whereHas('roles',function($query){
                $query->where('id',3);
            })->selectRaw('DATE(login_at) as date, COUNT(*) as count')
            ->whereNotIn('id',$activeRecords->pluck('id')->toArray())
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

                if(!$record){
                    $fetchRecord = $inactiveRecords->where('date',null)->first();
                    if(($sevenDaysAgo->format('Y-m-d')==$dateItem) && $fetchRecord){
                        return $fetchRecord->count;
                    }else{
                        return 0;
                    }
                }else{
                    $fetchRecord = $inactiveRecords->where('date',null)->first();
                    if(($sevenDaysAgo->format('Y-m-d')==$dateItem) && $fetchRecord){
                        return $record->count+$fetchRecord->count;
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
            ->whereDate('login_at', '>', $thirtyDaysAgo)
            ->groupBy('date')
            ->orderBy('date','desc')->get();

            $inactiveRecords = User::query()->whereHas('roles',function($query){
                $query->where('id',3);
            })->selectRaw('DATE(login_at) as date, COUNT(*) as count')
            ->whereNotIn('id',$activeRecords->pluck('id')->toArray())
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

                if(!$record){
                    $fetchRecord = $inactiveRecords->where('date',null)->first();
                    if(($thirtyDaysAgo->format('Y-m-d')==$dateItem) && $fetchRecord){
                        return $fetchRecord->count;
                    }else{
                        return 0;
                    }
                }else{
                    $fetchRecord = $inactiveRecords->where('date',null)->first();
                    if(($thirtyDaysAgo->format('Y-m-d')==$dateItem) && $fetchRecord){
                        return $record->count+$fetchRecord->count;
                    }else{
                        return $record->count;
                    }
                }
            })->toArray();

            $chartRecords['bottomLabels'] = $dateRange->toArray();
         
        }

        //  dd($chartRecords);
         
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
        $this->reset(['propertyBarChartDetails']);

        if(in_array($value,['hourly','weekly','monthly'])){
            $this->propertyTimeFilter = $value;
        }else{
            $this->propertyTimeFilter = 'hourly';
            $this->alert('error','Invalid Value Selected!');
        }

        $this->propertyBarChartDetails = $this->getDetailsPropertyBarChart();

        $this->dispatchBrowserEvent('renderBuyerPropertyBarChart',$this->propertyBarChartDetails); 
    }

    public function updatedPropertyFilter($value){
        $this->reset(['propertyBarChartDetails']);

        if(in_array($value,['location','type'])){
            $this->propertyFilter = $value;
        }else{
            $this->propertyFilter = 'location';
            $this->alert('error','Invalid Value Selected!');
        }

        $this->propertyBarChartDetails = $this->getDetailsPropertyBarChart();

        $this->dispatchBrowserEvent('renderBuyerPropertyBarChart',$this->propertyBarChartDetails); 
    }

    public function getDetailsPropertyBarChart(){

        $chartRecords['bottomLabels'] = [];
        $chartRecords['topTitle'] = 'Property Metric';
        $chartRecords['xAxisTitle'] = '';  
        $chartRecords['yAxisTitle'] = 'Number of search';
        $chartRecords['activeUserRecords'] =[10,20,30,40,50,60,70,80,100];
        $chartRecords['inactiveUserRecords'] =[10,20,30,10,20,20,40,50,60]; 
        $chartRecords['bottomLabels'] = [10,20,30,10,20,20,40,50,60];

        if($this->propertyTimeFilter == 'hourly'){
            $chartRecords['xAxisTitle'] = 'Last 24 Hours';  
        
            $chartRecords['bottomLabels'] = $this->hourlyInterval();

        }elseif($this->propertyTimeFilter == 'weekly'){
            $chartRecords['xAxisTitle'] = 'Last 7 days';  

            $dateRange = $this->weeklyInterval();
            
            $chartRecords['bottomLabels'] = $dateRange->map(function($dateValue){
                return  Carbon::parse($dateValue)->format('l');
            })->toArray();

        }elseif($this->propertyTimeFilter == 'monthly'){
            $chartRecords['xAxisTitle'] = 'Last 30 days';  

            $chartRecords['bottomLabels'] = $this->monthlyInterval();

        }

        $filterQuery = Buyer::query();

        
        return $chartRecords;
    }


    public function hourlyInterval(){
        $start = Carbon::parse('00:00:00'); 
        $end = Carbon::parse('23:59:59');   

        $intervals = [];
        while ($start <= $end) {
          
            $start->format('H');
            $intervalValue = $start->addHours(2)->format('H');

            if($intervalValue != '00'){
                $intervals[] = $intervalValue;
            }else{
                $intervals[] = "24";
            }
        }

        return $intervals;
    }

    public function weeklyInterval(){
        // Create a date range for the last 7 days
        $dateRange = collect();
        $startDate = now()->subDays(7);
        $endDate = now()->subDays(1);
        
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dateRange->push($date->format('Y-m-d'));
        }

        return $dateRange;
    }

    public function monthlyInterval(){
        $dateRange = collect();
        $startDate = now()->subDays(30);
        $endDate = now()->subDays(1);
        
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dateRange->push($date->format('Y-m-d'));
        }

        return $dateRange;
    }



}
