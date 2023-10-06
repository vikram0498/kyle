<div>

    <h4 class="card-title"> {{ strtolower(__('cruds.buyer.red_flag_view.buyer_info_heading')) }}</h4>

    <table class="table mb-3">
        <tr>
            <td> <b>Buyer Name: </b> {{ ucwords($data->first_name.' '.$data->last_name) }}</td>
            <td> <b>Buyer Email: </b> {{ $data->email }}</td>
            <td> <b>Buyer Phone No.: </b> {{ $data->phone }}</td>
        </tr>
        <tr>
            {{--<td> <b>Buyer Address: </b> {{ $data->address }} </td>--}}
            <td> <b>Buyer City: </b> 
                 @php
                  $AllCities = [];
                  if($data->city){
                    $AllCities = \DB::table('cities')->whereIn('id', $data->city)->pluck('name')->toArray();
                  }
                @endphp
                {{  count($AllCities) > 0 ? implode(',',$AllCities) : 'N/A'   }}
            </td>
            <td> <b>Buyer State: </b> 
                @php
                
                  $AllStates = [];
                  if($data->state){
                    $AllStates = \DB::table('states')->whereIn('id', $data->state)->pluck('name')->toArray();
                  }
                @endphp
                 {{  count($AllStates) > 0 ? implode(',',$AllStates) : 'N/A'   }}
            </td>
            <td></td>
        </tr>
    </table>

    <div class="card-title top-box-set mt-2">
        <h4 class="card-title"> {{ strtolower(__('cruds.buyer.red_flag_view.heading')) }}</h4>
        <div class="card-top-box-item">
        {{--<button type="button" id="resolveAllFlag" data-buyer_id="{{ $data->id }}" class="btn btn-sm btn-success btn-icon-text btn-header"> {{ __('cruds.buyer.red_flag_view.resolve_all_btn') }}</button>--}}
        </div>
    </div>
    
    @php 
        $buyerFlagData = $data->redFlagedData()->where('status', 0)->get();            
    @endphp

    <table class="table">
        <thead>
            <th style="width: 25%;">User Name</th>
            <th style="width: 60%;">Message</th>
            <th style="width: 60%;">Incorrect Information</th>
            <th style="width: 15%;">Action</th>
        </thead>
        <tbody>
            @foreach($buyerFlagData as $key => $flagData)
                <tr>
                    <td >
                        <div class="row align-items-center">
                        <div class="img-user"><img src="{{ isset($flagData->profile_image_url) && !empty($flagData->profile_image_url) ? $flagData->profile_image_url : asset(config('constants.default.profile_image')) }}"  class="img-fluid" alt=""></div>
                        <span>{{ ucwords($flagData->name) }}</span>
                        </div>
                    </td>
                    <td style="text-wrap: wrap;line-height: 23px;font-size: 17px;">
                        {!! $flagData->pivot->reason !!}
                    </td>
                    <td style="text-wrap: wrap;line-height: 23px;font-size: 17px;">
                     @php
                       $view = '';
                       foreach(json_decode($flagData->pivot->incorrect_info,true) as $name=>$value){
                        if($value){
                            $view .= $name.', ';
                        }
                       }
                       
                       $modifiedString = rtrim($view, ', ');
                     @endphp

                     {{ ucwords($modifiedString) }}
                    </td>
                    <td>
                        <div class="d-flex mb-2">
                            <button class="btn btn-outline-success ms-1 mr-1 resolve_flag" data-edit-column="{{$modifiedString}}" title="{{ __('cruds.buyer.red_flag_view.resolve_flag_btn') }}" data-buyer_id="{{ $data->id }}" data-user="{{ $flagData->id }}"><i class="fa fa-check"></i></button>

                            <button class="btn btn-outline-danger ms-1 reject_flag" title="{{ __('cruds.buyer.red_flag_view.reject_flag_btn') }}" data-buyer_id="{{ $data->id }}" data-user="{{ $flagData->id }}" ><i class="fa fa-close"></i></button>
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


    {{-- Flag Resolve Modal --}}
    <div wire:ignore.self class="modal fade" id="flagResolveModal" tabindex="-1" role="dialog" aria-labelledby="resolveModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resolveModalLabel">Resolve Issue</h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form wire:submit.prevent="resolveAllFlag" class="forms-sample" autocomplete="off">
                <div class="modal-body">
                    @if($isNameUpdate)
                    <div class="form-group">
                        <input type="text" wire:model.defer="first_name" class="form-control" placeholder="First Name">
                        @error('first_name') <span class="error text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <input type="text" wire:model.defer="last_name" class="form-control" placeholder="Last Name">
                        @error('last_name') <span class="error text-danger">{{ $message }}</span>@enderror
                    </div>
                    @endif
                    
                    @if($isEmailUpdate)
                    <div class="form-group">
                        <input type="text" wire:model.defer="email" placeholder="Email" class="form-control">
                        @error('email') <span class="error text-danger">{{ $message }}</span>@enderror
                    </div>
                    @endif

                    @if($isPhoneUpdate)
                    <div class="form-group">
                        <input type="text" wire:model.defer="phone" placeholder="Phone" class="form-control" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length < 10 ">
                        @error('phone') <span class="error text-danger">{{ $message }}</span>@enderror
                    </div>
                    @endif

                    <div class="form-group">
                        <textarea  cols="30" rows="4" wire:model.defer="message" class="form-control" placeholder="Message"></textarea>
                        @error('message') <span class="error text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                    <button type="submit" wire:loading.attr="disabled" class="btn btn-sm btn-success">
                        Submit
                        <span wire:loading wire:target="resolveAllFlag">
                            <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                        </span>
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>

    {{-- Reject Form --}}
    <div  wire:ignore.self class="modal fade" id="flagRejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Issue</h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form wire:submit.prevent="rejectFlag" class="forms-sample" autocomplete="off">
                    <div class="modal-body">
                        <div class="form-group">
                            <textarea  cols="30" rows="10" wire:model.defer="message" class="form-control" placeholder="Message"></textarea>
                            @error('message') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-sm btn-success">
                            Submit
                            <span wire:loading wire:target="rejectFlag">
                                <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>