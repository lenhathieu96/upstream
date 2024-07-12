<div class="card">
  <h5 class="card-header fw-bold card-header-status" data-bs-toggle="collapse" data-bs-target="#card-body-farmer-information">Farmer Information</h5>
  <div class="collapse show" id="card-body-farmer-information">
      <div class="card-body">
          <div class="form-group row border-bottom">
              <div class="col-md-6 d-flex align-items-center">
                  <label class="col-md-6 col-form-label fw-medium text-heading" for="">Full Name</label>
                  <span class="col-md-6">{{ $farmerDetail->full_name }}</span>
              </div>
              <div class="col-md-6 d-flex align-items-center">
                  <label class="col-md-6 col-form-label fw-medium text-heading" for="">Phone Number</label>
                  <span class="col-md-6">{{ $farmerDetail->phone_number }}</span>
              </div>
          </div>
          <div class="form-group row border-bottom">
              <div class="col-md-6 d-flex align-items-center">
                  <label class="col-md-6 col-form-label fw-medium text-heading" for="">Gender</label>
                  <span class="col-md-6">{{ ucwords($farmerDetail->gender ?? 'N/A') }}</span>
              </div>
              <div class="col-md-6 d-flex align-items-center">
                  <label class="col-md-6 col-form-label fw-medium text-heading" for="">DOB</label>
                  <span class="col-md-6">{{ $farmerDetail->dob ?? 'N/A' }}</span>
              </div>
          </div>
          <div class="form-group row border-bottom">
              <div class="col-md-6 d-flex align-items-center">
                  <label class="col-md-6 col-form-label fw-medium text-heading" for="">Enrollment Date</label>
                  <span class="col-md-6">{{ $farmerDetail->enrollment_date ?? 'N/A' }}</span>
              </div>
              <div class="col-md-6 d-flex align-items-center">
                  <label class="col-md-6 col-form-label fw-medium text-heading" for="">Enrollment Place</label>
                  <span class="col-md-6">{{ $farmerDetail->enrollment_place ?? 'N/A' }}</span>
              </div>
          </div>
      </div>
  </div>
</div>

<div class="card">
  <h5 class="card-header fw-bold card-header-status" data-bs-toggle="collapse" data-bs-target="#card-body-address-information">Address Information</h5>
  <div class="collapse show" id="card-body-address-information">
      <div class="card-body">
          <div class="form-group row border-bottom">
              <div class="col-md-6 d-flex align-items-center">
                  <label class="col-md-6 col-form-label fw-medium text-heading" for="">Country</label>
                  <span class="col-md-6">{{ $farmerDetail->countryRelation?->country_name ?? 'N/A' }}</span>
              </div>
              <div class="col-md-6 d-flex align-items-center">
                  <label class="col-md-6 col-form-label fw-medium text-heading" for="">Province</label>
                  <span class="col-md-6">{{ $farmerDetail->provinceRelation?->province_name ?? 'N/A' }}</span>
              </div>
          </div>

          <div class="form-group row border-bottom">
              <div class="col-md-6 d-flex align-items-center">
                  <label class="col-md-6 col-form-label fw-medium text-heading" for="">District</label>
                  <span class="col-md-6">{{ $farmerDetail->districtRelation?->district_name ?? 'N/A' }}</span>
              </div>
              <div class="col-md-6 d-flex align-items-center">
                  <label class="col-md-6 col-form-label fw-medium text-heading" for="">Commune</label>
                  <span class="col-md-6">{{ $farmerDetail->communeRelation?->commune_name ?? 'N/A' }}</span>
              </div>
          </div>

          <div class="form-group row border-bottom">
              <div class="col-md-6 d-flex align-items-center">
                  <label class="col-md-6 col-form-label fw-medium text-heading" for="">Village</label>
                  <span class="col-md-6">{{ $farmerDetail->village ?? 'N/A' }}</span>
              </div>
          </div>
      </div>
  </div>
</div>

<div class="card">
  <h5 class="card-header fw-bold card-header-status" data-bs-toggle="collapse" data-bs-target="#card-body-family-information">Family Information</h5>
  <div class="collapse show" id="card-body-family-information">
      @if (!empty($farmerDetail->family_info))
          <div class="card-body">
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Education</label>
                      <span class="col-md-6">{{ ucwords($farmerDetail->family_info?->education) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Marial Status</label>
                      <span class="col-md-6">{{ ucwords($farmerDetail->family_info?->marial_status) }}</span>
                  </div>
              </div>
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Parent Name</label>
                      <span class="col-md-6">{{ ucwords($farmerDetail->family_info?->parent_name) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Spouse Name</label>
                      <span class="col-md-6">{{ ucwords($farmerDetail->family_info?->spouse_name) }}</span>
                  </div>
              </div>
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">No Of Family</label>
                      <span class="col-md-6">{{ ucwords($farmerDetail->family_info?->no_of_family) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Total children under 18</label>
                      <span class="col-md-6">{{ ucwords($farmerDetail->family_info?->total_child_under_18) }}</span>
                  </div>
              </div>
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-5 col-form-label fw-medium text-heading" for="">Total children under 18 going school</label>
                      <span class="col-md-6 offset-1">{{ ucwords($farmerDetail->family_info?->total_child_under_18_going_school) }}</span>
                  </div>
              </div>
          </div>
      @else
          <div class="card-body">
              <p>No data found</p>
          </div>
      @endif
  </div>
</div>

<div class="card">
  <h5 class="card-header fw-bold card-header-status collapsed" data-bs-toggle="collapse" data-bs-target="#card-body-asset-information">Asset Information</h5>
  <div class="collapse" id="card-body-asset-information">
      @if (!empty($farmerDetail->asset_info))
          <div class="card-body">
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Housing Ownership</label>
                      <span class="col-md-6">{{ ucwords($farmerDetail->asset_info?->housing_ownership) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">House Type</label>
                      <span class="col-md-6">{{ ucwords($farmerDetail->asset_info?->house_type) }}</span>
                  </div>
              </div>
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Consumer Electronic</label>
                      <span class="col-md-6">{{ ucwords($farmerDetail->asset_info?->consumer_electronic) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">vehicle</label>
                      <span class="col-md-6">{{ ucwords($farmerDetail->asset_info?->vehicle) }}</span>
                  </div>
              </div>                                    
          </div>
      @else
          <div class="card-body">
              <p>No data found</p>
          </div>
      @endif
  </div>
</div>

<div class="card">
  <h5 class="card-header fw-bold card-header-status collapsed" data-bs-toggle="collapse" data-bs-target="#card-body-bank-information">Bank Information</h5>
  <div class="collapse" id="card-body-bank-information">
      @php $bankInfors = $farmerDetail->bank_info->count() ? $farmerDetail->bank_info : []; @endphp
      @forelse($bankInfors as $bankInfo)
          <div class="card-body border-bottom  pb-5">
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Account Type</label>
                      <span class="col-md-6">{{ ucwords($bankInfo?->accout_type) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Account No</label>
                      <span class="col-md-6">{{ ucwords($bankInfo?->accout_no) }}</span>
                  </div>
              </div>
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Bank Name</label>
                      <span class="col-md-6">{{ ucwords($bankInfo?->bank_name) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Branch Details</label>
                      <span class="col-md-6">{{ ucwords($bankInfo?->branch_details) }}</span>
                  </div>
              </div>  
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Sort Code</label>
                      <span class="col-md-6">{{ ucwords($bankInfo?->sort_code) }}</span>
                  </div>
              </div>                                  
          </div>
      @empty
          <div class="card-body">
              <p>No data found</p>
          </div>
      @endforelse
  </div>
</div>

<div class="card">
  <h5 class="card-header fw-bold card-header-status collapsed" data-bs-toggle="collapse" data-bs-target="#card-body-insurance-information">Insurance Information</h5>
  <div class="collapse" id="card-body-insurance-information">
      @php $insuranceInfors = $farmerDetail->insurance_info->count() ? $farmerDetail->insurance_info : []; @endphp
      @forelse($insuranceInfors as $insuranceInfor)
          <div class="card-body border-bottom pb-5">
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Life Insurance</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->life_insurance) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Provider Life Insurance</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->provider_life_insurance) }}</span>
                  </div>
              </div>
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Life Insurance Amount</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->life_insurance_amount) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Life Insurance Enrolled Date</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->life_insurance_enrolled_date) }}</span>
                  </div>
              </div>  
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Life Insurance End Date</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->life_insurance_end_date) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Health Insurance</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->health_insurance) }}</span>
                  </div>
              </div>                                  
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Provider Health Insurance</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->provider_health_insurance) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Health Insurance Amount</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->health_insurance_amount) }}</span>
                  </div>
              </div>
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Health Insurance Enrolled Date</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->health_insurance_enrolled_date) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Health Insurance End Date</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->health_insurance_end_date) }}</span>
                  </div>
              </div>
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Health Insurance Enrolled Date</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->health_insurance_enrolled_date) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Crop Insurance</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->crop_insurance) }}</span>
                  </div>
              </div>
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Provider Crop Insurance</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->provider_crop_insurance) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Crop Insured</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->crop_insured) }}</span>
                  </div>
              </div>
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">No Of Area Insured</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->no_of_area_insured) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Crop Insurance Enrolled Date</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->crop_insurance_enrolled_date) }}</span>
                  </div>
              </div>
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Crop Insurance End Date</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->crop_insurance_end_date) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Social Insurance</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->social_insurance) }}</span>
                  </div>
              </div>
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Provider Social Insurance</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->provider_social_insurance) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Social Insurance Enrolled Date</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->social_insurance_enrolled_date) }}</span>
                  </div>
              </div>
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Social Insurance End Date</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->social_insurance_end_date) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Other Insurance</label>
                      <span class="col-md-6">{{ ucwords($insuranceInfor?->other_insurance) }}</span>
                  </div>
              </div>
          </div>
      @empty
          <div class="card-body">
              <p>No data found</p>
          </div>
      @endforelse
  </div>
</div>

<div class="card">
  <h5 class="card-header fw-bold card-header-status collapsed" data-bs-toggle="collapse" data-bs-target="#card-body-farm-equipment">Farm Equipment</h5>
  <div class="collapse" id="card-body-farm-equipment">
      @php $farmEquipments = $farmerDetail->farm_equipment->count() ? $farmerDetail->farm_equipment : []; @endphp
      @forelse($farmEquipments as $farmEquipment)
          <div class="card-body border-bottom  pb-5">
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Farm Equipment Items</label>
                      <span class="col-md-6">{{ ucwords($farmEquipment?->farm_equipment_items) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Farm Equipment Items Count</label>
                      <span class="col-md-5 offset-1">{{ ucwords($farmEquipment?->farm_equipment_items_count) }}</span>
                  </div>
              </div>
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Year of Manufacture</label>
                      <span class="col-md-6">{{ ucwords($farmEquipment?->year_of_manufacture) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Year of Purchase</label>
                      <span class="col-md-5 offset-1">{{ ucwords($farmEquipment?->year_of_purchase) }}</span>
                  </div>
              </div>                            
          </div>
      @empty
          <div class="card-body">
              <p>No data found</p>
          </div>
      @endforelse
  </div>
</div>

<div class="card">
  <h5 class="card-header fw-bold card-header-status collapsed" data-bs-toggle="collapse" data-bs-target="#card-body-animal-husbandry">Animal Husbandry</h5>
  <div class="collapse" id="card-body-animal-husbandry">
      @php $animalHusbandries = $farmerDetail->animal_husbandry->count() ? $farmerDetail->animal_husbandry : []; @endphp
      @forelse($animalHusbandries as $animalHusbandry)
          <div class="card-body border-bottom  pb-5">
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Farm Animal</label>
                      <span class="col-md-6">{{ ucwords($animalHusbandry?->farm_animal) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Animal Count</label>
                      <span class="col-md-6">{{ ucwords($animalHusbandry?->animal_count) }}</span>
                  </div>
              </div>
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Fodder</label>
                      <span class="col-md-6">{{ ucwords($animalHusbandry?->fodder) }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Animal Housing</label>
                      <span class="col-md-6">{{ ucwords($animalHusbandry?->animal_housing) }}</span>
                  </div>
              </div>          
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Revenue</label>
                      <span class="col-md-6">{{ number_format((int) $animalHusbandry?->revenue) . ' VND' }}</span>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Breed Name</label>
                      <span class="col-md-6">{{ ucwords($animalHusbandry?->breed_name) }}</span>
                  </div>
              </div>    
              <div class="form-group row border-bottom">
                  <div class="col-md-6 d-flex align-items-center">
                      <label class="col-md-6 col-form-label fw-medium text-heading" for="">Animal For Growth</label>
                      <span class="col-md-6">{{ ucwords($animalHusbandry?->animal_for_growth) }}</span>
                  </div>
              </div>                 
          </div>
      @empty
          <div class="card-body">
              <p>No data found</p>
          </div>
      @endforelse
  </div>
</div>
