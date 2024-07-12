@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <div class="container-fluid">

        <div class="row">
              <div class="col-md-3">
                <!-- User Card -->
                <div class="card mb-4">
                  <div class="card-body">
                    <div class="user-avatar-section">
                      <div class=" d-flex align-items-center flex-column">
                        <img class="img-fluid rounded mb-3 mt-4" src="{{ $farmerDetail->avatar_url }}" height="120" width="120" alt="User avatar">
                        <div class="user-info text-center">
                          <h4>{{ $farmerDetail->full_name }}</h4>
                        </div>
                      </div>
                    </div>

                    <h5 class="pb-3 border-bottom mt-4 mb-3">Farmer Details</h5>
                    <div class="info-container">
                      <ul class="list-unstyled mb-4">
                        <li class="mb-3">
                            <span class="fw-medium text-heading me-2">Code:</span>
                            <span>{{ $farmerDetail->farmer_code }}</span>
                          </li>
                        <li class="mb-3">
                          <span class="fw-medium text-heading me-2">Full Name:</span>
                          <span>{{ $farmerDetail->full_name }}</span>
                        </li>
                        <li class="mb-3">
                          <span class="fw-medium text-heading me-2">Phone Number:</span>
                          <span>{{ ucwords($farmerDetail->phone_number) }}</span>
                        </li>
                        <li class="mb-3">
                          <span class="fw-medium text-heading me-2">Gender:</span>
                          <span>{{ $farmerDetail->gender }}</span>
                        </li>
                        <li class="mb-3">
                          <span class="fw-medium text-heading me-2">DOB:</span>
                          <span>{{ $farmerDetail->dob }}</span>
                        </li>
                        <li class="mb-3">
                          <span class="fw-medium text-heading me-2">Enrollment Date:</span>
                          <span>{{ $farmerDetail->enrollment_date }}</span>
                        </li>
                        <li class="mb-3">
                          <span class="fw-medium text-heading me-2">Enrollment Place:</span>
                          <span>{{ $farmerDetail->enrollment_place }}</span>
                        </li>
                        <li class="mb-3">
                          <span class="fw-medium text-heading me-2">Country:</span>
                          <span>{{ $farmerDetail->countryRelation?->country_name }}</span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div>
                  <div class="card mb-4">
                    <div class="tag_discount">
                      <span>Thẻ Nông Dân</span>
                    </div>
                    <div class="card-body">
                        <div class="logo_img">
                          <img src="https://up.farm-angel.com/storage/uploads/all/XQN6SflKUccoOVilsBg0oNdowwYmOeR35uKmKzH5.png" alt="">
                        </div>

                        <div class="form-group" style="display: flex;align-items: center">
                          <div class="col-4">
                              <img width="100%" src="{{ $farmerDetail->avatar_url }}" alt="">
                          </div>
                          <div class="col-8 farmer_total_info">
                            <div>
                              <label for="">Tên</label>
                            </div>
                            <div class="info_farmer">
                              <span>{{$farmerDetail->full_name}}</span>
                            </div>

                            <div>
                              <label for="">Mã nông dân</label>
                            </div>
                            <div class="info_farmer">
                              <span>{{$farmerDetail->farmer_code}}</span>
                            </div>

                            <div>
                              <label for="">Ngày đăng ký</label>
                            </div>
                            <div class="info_farmer">

                              <span>{{ date('d M Y', $farmerDetail->created_at->timestamp) }}</span>
                            </div>
                          </div>
                        </div>

                        <div class="qrcode">
                          {!! $qrcode !!}
                        </div>
                    </div>
                  </div>

                  <div class="carb mb-4">
                      <img src="https://up.farm-angel.com/storage/uploads/all/ujPmTHrbPuIX4gWbYWEsnis9S7YHusZZ7qUYP9PP.png" width="100%" alt="">

                  </div>
                  {{-- {!! $qrcode !!} --}}
                </div>
              </div>

              <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <!-- User Tabs -->
                <ul class="nav nav-tabs mb-3">
                  <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);" data-bs-toggle="tab" data-bs-target="#farmer-detail">
                            <i class="mdi mdi-account-cowboy-hat mdi-20px me-1"></i>Farmer Detail
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" data-bs-toggle="tab" data-bs-target="#farm-detail">
                            <i class="mdi mdi-snowflake mdi-20px me-1"></i>Farm Detail
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" data-bs-toggle="tab" data-bs-target="#cultivation-detail">
                            <i class="mdi mdi-barley mdi-20px me-1"></i>Cultivation Detail
                        </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="javascript:void(0);" data-bs-toggle="tab" data-bs-target="#transaction">
                          <i class="mdi mdi-chart-timeline mdi-20px me-1"></i>Transaction
                      </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0);" data-bs-toggle="tab" data-bs-target="#contract">
                        <i class="mdi mdi-file-document-edit mdi-20px me-1"></i>Contract
                    </a>
                </li>
                    <span class="tab-slider" style="left: 0px; width: 145.609px; bottom: 0px;"></span>
                </ul>
                <!--/ User Tabs -->

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="farmer-detail">
                        @include('farmer.details.farmer_details')
                    </div>

                    <div class="tab-pane fade" id="farm-detail">
                        @include('farmer.details.farm_details')
                    </div>

                    <div class="tab-pane fade" id="cultivation-detail">
                        @include('farmer.details.cultivation_details')

                    </div>

                    <div class="tab-pane fade" id="transaction">
                      @include('farmer.details.transaction')

                  </div>

                  <div class="tab-pane fade" id="contract">
                    @include('farmer.details.contract')

                </div>
                </div>

              </div>
            </div>
        </div>
    </div>
  </div>
  <style>
    .table th {
      text-transform: none;
    }
    .info_farmer
    {
      font-family: 'Roboto',sans-serif;
      font-size: 16px;
      font-weight: 700;
      line-height: 19px;
      letter-spacing: 0px;
      text-align: left;
      color:rgba(121, 121, 121, 1);
      margin-bottom: 8px;
    }
    .farmer_total_info{
      padding: 0px 16px;
    }
    .qrcode{
      position: absolute;
      right:16px;
      bottom:16px;
    }
    .logo_img
    {
      position: absolute;
      right:16px;
      top:10px;
    }
    .tag_discount
    {
      padding: 6px;
      font-family: 'Quicksand',sans-serif;
      color: rgba(255, 255, 255, 1);
      font-size: 12px;
      font-weight: 700;
      line-height: 15px;
      letter-spacing: 0px;
      text-align: center;
      position: relative;
      width: 103px;
      height: 28px;
      top: 15px;
      border-radius: 0px 0px 15px 0px;
      background: rgba(13, 198, 25, 1);
    }
  </style>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function()
    {

    });
</script>
@endpush
