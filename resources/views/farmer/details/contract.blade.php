@php 
    $farmLandIds = $farmerDetail->farm_lands->pluck('id');
    $cultivations = \App\Models\Cultivations::whereIn('farm_land_id', $farmLandIds)->get();
@endphp

@if (!empty($cultivations))
    <table class="table table-bordered js-crop-table">
        <thead>
        <tr style="background-color: #2E7F25 !important;">
            <th class="text-white">Contract</th>
            <th class="text-white">View Details</th>
        </tr>
        </thead>
        <tbody>
            {{-- @foreach($cultivations as $crop) --}}
                <tr>
                    <td>First Contract</td>
                    <td>
                        <a href="https://up.farm-angel.com/storage/uploads/all/z1oPcA1UcaazPluOvwNUYQzfpvWAXK5qt5jDuV0o.pdf" target="_blank class="js-view-crop" >View Contract</a>
                    </td>
                </tr>
                <tr>
                    <td>Second Contract</td>
                    <td>
                        <a href="https://up.farm-angel.com/storage/uploads/all/FxkV4uJvklnJeBTogs0Cpwubn0sKTsnf8NgIppqb.pdf" target="_blank class="js-view-crop" >View Contract</a>
                    </td>
                </tr>
            {{-- @endforeach --}}
        </tbody>
    </table>
@endif

@foreach($cultivations as $crop)
    <div class="crop-detail d-none" data-crop-id={{ $crop->id }}>
        <div class="text-end mb-3">
            <a class="back" href="javascript:void(0)"><span class="mdi mdi-arrow-left"></span> Back</a>
        </div>
        <div class="card">
            <h5 class="card-header fw-bold card-header-status" data-bs-toggle="collapse" data-bs-target="#card-body-cultivation-information-{{ $crop->id}}">Cultivation Information</h5>
            <div class="" id="card-body-cultivation-information-{{ $crop->id}}">
                <div class="card-body border-bottom">
                    <div class="form-group row border-bottom">
                        <div class="col-md-6 d-flex align-items-center">
                            <label class="col-md-6 col-form-label fw-medium text-heading" for="">Cultivated Crop</label>
                            <span class="col-md-6">{{ $crop?->crops_master?->name }}</span>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <label class="col-md-6 col-form-label fw-medium text-heading" for="">Variety</label>
                            <span class="col-md-6">{{ $crop?->crop_variety }}</span>
                        </div>
                    </div>          
                    <div class="form-group row border-bottom">
                        <div class="col-md-6 d-flex align-items-center">
                            <label class="col-md-6 col-form-label fw-medium text-heading" for="">Cultivation Area HA</label>
                            <span class="col-md-6">{{ $crop->farm_land?->total_land_holding . " Ha" }}</span>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <label class="col-md-6 col-form-label fw-medium text-heading" for="">Sowing Date</label>
                            <span class="col-md-6">{{ $crop->sowing_date }}</span>
                        </div>
                    </div>    
                    <div class="form-group row border-bottom">
                        <div class="col-md-6 d-flex align-items-center">
                            <label class="col-md-6 col-form-label fw-medium text-heading" for="">Est Yield</label>
                            <span class="col-md-6">{{ ucwords($crop?->est_yield) . ' Kg'}}</span>
                        </div>
                    </div>            
                </div>
            </div>
        </div>
    </div>

    <div class="crop-detail d-none" data-crop-id={{ $crop->id }}>
        <div class="card">
            <h5 class="card-header fw-bold card-header-status collapsed" data-bs-toggle="collapse" data-bs-target="#card-body-cultivation-photo-{{ $crop->id}}">Cultivation Photo</h5>
            <div class="collapse" id="card-body-cultivation-photo-{{ $crop->id}}">
                <div class="card-body border-bottom">
                    @php $photoUrls = !empty($crop->crop_photo_url) ? $crop->crop_photo_url : []; @endphp
                    @foreach($photoUrls as $photoUrl)
                        <img src="{{ $photoUrl }}" class="d-block mb-3" style="max-width: 100%;">
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="crop-detail d-none" data-crop-id={{ $crop->id }}>
        <div class="card">
            <h5 class="card-header fw-bold card-header-status collapsed" data-bs-toggle="collapse" data-bs-target="#card-body-cultivation-plot-area-{{ $crop->id}}">Cultivation Plot Area</h5>
            <div class="collapse" id="card-body-cultivation-plot-area-{{ $crop->id}}">
                <div class="card-body border-bottom">
                    Implement later
                </div>
            </div>
        </div>
    </div>


@endforeach

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function()
        {   
            $('.js-view-crop').click(function () {
                var dataCropId = $(this).attr('data-crop-id');
                $(`[data-crop-id=${dataCropId}]`).removeClass('d-none');
                $('.js-crop-table').hide();
            });

            $('.back').click(function() {
                $('.crop-detail').addClass('d-none');
                $('.js-crop-table').show();
            });
        });
    </script>
@endpush