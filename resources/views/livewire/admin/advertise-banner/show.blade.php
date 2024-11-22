
<h4 class="card-title">
    {{__('global.show')}}
    {{ strtolower(__('cruds.adBanner.title_singular'))}}</h4>

    <table class="table table-design mb-4">
        <tr>
            <th width="25%">{{ __('cruds.adBanner.fields.image')}}</th>
            <td><img class="rounded img-thumbnail" src="{{ $details->adBannerImage ? $details->image_url : asset('images/default-img.jpg') }}" style="width:100px; height: auto;"/></td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.adBanner.fields.advertiser_name')}}</th>
            <td>{{ $details->advertiser_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.adBanner.fields.page_type')}}</th>
            <td>{{ config('constants.banner_page_type.' . $details->page_type, 'N/A') }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.adBanner.fields.ad_name')}}</th>
            <td>{{ $details->ad_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.adBanner.fields.target_url')}}</th>
            <td>{{ $details->target_url ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.adBanner.fields.impressions_purchased')}}</th>
            <td> {{ $details->impressions_purchased ?? 0 }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.adBanner.fields.impressions_served')}}</th>
            <td> {{ $details->impressions_served ?? 0 }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.adBanner.fields.impressions_count')}}</th>
            <td> {{ $details->impressions_count ?? 0 }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.adBanner.fields.click_count')}}</th>
            <td> {{ $details->click_count ?? 0 }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.adBanner.fields.start_date')}}</th>
            <td> {{ $details->start_date->format(config('constants.date_format')) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.adBanner.fields.end_date')}}</th>
            <td> {{ $details->end_date->format(config('constants.date_format')) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.adBanner.fields.start_time')}}</th>
            <td> {{ $details->start_time->format(config('constants.time_format')) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.adBanner.fields.end_time')}}</th>
            <td> {{ $details->end_time->format(config('constants.time_format')) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.adBanner.fields.status')}}</th>
            <td>{{ ucwords(config('constants.ad_banner_status')[$details->status] ?? 'N/A') }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('global.created_at')}}</th>
            <td> {{ $details->created_at->format(config('constants.datetime_format')) }}</td>
        </tr>
    </table>
    <div class="text-right">
        <button wire:click.prevent="cancel" class="btn btn-fill btn-dark">
            {{ __('global.back')}}
            <span wire:loading wire:target="cancel">
                <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
            </span>
        </button>
    </div>

               