<div>

    <h4 class="card-title"> {{ strtolower(__('cruds.buyer.red_flag_view.buyer_info_heading')) }}</h4>

    <table class="table mb-3">
        <tr>
            <td> <b>Buyer Name: </b> {{ $data->first_name }} {{ $data->last_name }}</td>
            <td> <b>Buyer Email: </b> {{ $data->email }}</td>
            <td> <b>Buyer Phone No.: </b> {{ $data->phone }}</td>
        </tr>
        <tr>
            <td> <b>Buyer Address: </b> {{ $data->address }} </td>
            <td> <b>Buyer City: </b> {{ $data->city }}</td>
            <td> <b>Buyer State: </b> {{ $data->state }}</td>
        </tr>
    </table>

    <div class="card-title top-box-set mt-2">
        <h4 class="card-title"> {{ strtolower(__('cruds.buyer.red_flag_view.heading')) }}</h4>
        <div class="card-top-box-item">
        <button type="button" id="resolveAllFlag" data-buyer_id="{{ $data->id }}" class="btn btn-sm btn-success btn-icon-text btn-header"> {{ __('cruds.buyer.red_flag_view.resolve_all_btn') }}</button>
        </div>
    </div>
    
    @php 
        $buyerFlagData = $data->redFlagedData()->where('status', 0)->get();            
    @endphp

    <table class="table">
        <thead>
            <th style="width: 25%;">Seller Name</th>
            <th style="width: 60%;">Reason</th>
            <th style="width: 15%;">Action</th>
        </thead>
        <tbody>
            @foreach($buyerFlagData as $key => $flagData)
                <tr>
                    <td >
                        <div class="row align-items-center">
                        <div class="img-user"><img src="{{ isset($flagData->profile_image_url) && !empty($flagData->profile_image_url) ? $flagData->profile_image_url : asset(config('constants.default.profile_image')) }}"  class="img-fluid" alt=""></div>
                        <span>{{ $flagData->name }}</span>
                        </div>
                    </td>
                    <td style="text-wrap: wrap;line-height: 23px;font-size: 17px;">
                        {!! $flagData->pivot->reason !!}
                    </td>
                    <td>
                        <div class="d-flex mb-2">
                            <button class="btn btn-outline-success ms-1 mr-1 resolve_flag" title="{{ __('cruds.buyer.red_flag_view.resolve_flag_btn') }}" data-buyer_id="{{ $data->id }}" data-seller_id="{{ $flagData->id }}"><i class="fa fa-check"></i></button>

                            <button class="btn btn-outline-danger ms-1 reject_flag" title="{{ __('cruds.buyer.red_flag_view.reject_flag_btn') }}" data-buyer_id="{{ $data->id }}" data-seller_id="{{ $flagData->id }}" ><i class="fa fa-close"></i></button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-right">
        <button wire:click.prevent="cancel" class="btn btn-fill btn-dark">
            {{ __('global.back')}}
            <span wire:loading wire:target="cancel">
                <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
            </span>
        </button>
    </div>
</div>