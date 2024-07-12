@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <div class="container-fluid">

        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col">
                        <h5 class="mb-md-0 h6">{{ empty($staff->id) ? 'Create Staff' : 'Edit Staff' }}</h5>
                    </div>
                    @include('shared.form-alerts')
                </div>
              <div class="card-body" >
                <form action="{{ !empty($staff->id) ? route('staff.update', ['staff' => $staff->id]) : route('staff.store') }}" method="POST" data-parsley-validate>
                    {{ $staff->id ? method_field('PUT') : method_field('POST') }}
                    @csrf
                    {{-- First Name --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">First Name</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="first_name" placeholder="First Name" value="{{ $staff->first_name }}" required>
                        </div>
                    </div>
                    {{-- Last Name --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Last Name</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="last_name" placeholder="Last Name" value="{{ $staff->last_name }}" required>
                        </div>
                    </div>
                    {{-- Contact Number --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Contact Number</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="phone_number" placeholder="Contact Number" value="{{ $staff->phone_number }}" required>
                        </div>
                    </div>
                    {{-- Email --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Email</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="email" placeholder="Email" value="{{ $staff->email }}" required>
                        </div>
                    </div>
                    {{-- Gender --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Gender</label>
                        <div class="col-md-8">
                            <select class="form-control aiz-selectpicker" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male" {{ ucwords($staff->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ ucwords($staff->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                    </div>
                    
                    {{-- Gender --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Password</label>
                        <div class="col-md-8">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>
                    </div>

                    {{-- Gender --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Password Confirmation</label>
                        <div class="col-md-8">
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Password Confirmation">
                        </div>
                    </div>
                    
                    {{-- Status --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Status</label>
                        <div class="col-md-8">
                            <div class="d-flex">
                                <div style="display: flex;align-items: center" class="me-5">
                                    <input  class="radio_button_checkout"  type="radio" id="status-active" name="status" value="active" {{ $staff->status != 'inactive' ? 'checked' : '' }} 
                                        required
                                        data-parsley-required-message="Please choose status" 
                                        data-parsley-errors-container="#js-status-errors"/>
                                    <label for="status-active" class="ms-2">Active</label>
                                </div>
    
                                <div style="display: flex;align-items: center">
                                    <input  class="radio_button_checkout" type="radio" id="status-inactive" name="status" value="inactive"  {{ $staff->status == 'inactive' ? 'checked' : '' }} 
                                        required
                                        data-parsley-required-message="Please choose status" 
                                        data-parsley-errors-container="#js-status-errors"/>
                                    <label for="status-inactive" class="ms-2">Inactive</label>
                                </div>
                            </div>
                            <div id="js-status-errors"></div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 col-from-label">
                            <label for="js-user-type">Field Agent Type</label>
                        </div>
                        <div class="col-md-8">
                            <select name="user_type" id="js-user-type" class="form-control" required>
                                <option value="">Select Type</option>
                                @foreach(['staff', 'warehouse_operator'] as $userType)
                                    <option value="{{ $userType }}" {{ $userType ==  $staff->user_type ? 'selected' : '' }}>{{ Str::headline($userType) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row js-warehouse {{ $staff->user_type != 'warehouse_operator' ? 'd-none' : ''}}">
                        <div class="col-md-3 col-from-label">
                            <label for="js-warehouse-id">Warehouse</label>
                        </div>
                        <div class="col-md-8">
                            @php 
                                $warehouses = \App\Models\Warehouse::active()->where(function ($query ) use ($staff) {
                                    $query->whereNull('staff_id')
                                        ->orWhere('staff_id', $staff->id);
                                })->get(); 
                            @endphp
                            <select name="warehouse_id" id="js-warehouse-id" class="form-control" {{ $staff->user_type == 'warehouse_operator' ? 'required' : ''}}>
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ $warehouse->id ==  $staff->warehouse?->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row js-cooperative {{ $staff->user_type != 'staff' ? 'd-none' : ''}}">
                        <div class="col-md-3 col-from-label">
                            <label for="js-cooperative-id">Cooperative</label>
                        </div>
                        <div class="col-md-8">
                            <select name="cooperative_ids[]" id="js-cooperative-id" class="form-control js-select2" multiple>
                                @foreach($availableCooperatives as $cooperative)
                                    <option value="{{ $cooperative->id }}" {{ in_array($cooperative->id, $currentCooperative->pluck('id')->all())  ? 'selected' : '' }}>{{ $cooperative->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="mar-all mb-2" style=" text-align: end;">
                            <button type="submit" name="button"  value="publish"
                                class="btn btn-primary">{{ $staff->id ? 'Edit' : 'Create'}}</button>
                        </div>
                    </div>
                </form>
              </div>
              
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@push('scripts')
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.js-select2').select2();

        $('#js-user-type').change(function () {
            let userType = $(this).val();
            if (userType == 'warehouse_operator') {
                $('.js-warehouse').removeClass('d-none');
                $('.js-cooperative').addClass('d-none');
                $('#js-warehouse-id').attr('required');
            } else if (userType == 'staff') {
                $('.js-warehouse').addClass('d-none');
                $('.js-cooperative').removeClass('d-none');
                $('#js-warehouse-id').removeAttr('required');
            }
        });
    });
</script>
@endpush