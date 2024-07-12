@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <div class="container-fluid">

        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col">
                        <h5 class="mb-md-0 h6">{{ empty($warehouse->id) ? 'Create Warehouse' : 'Edit Warehouse' }}</h5>
                    </div>
                    @include('shared.form-alerts')
                </div>
              <div class="card-body" >
                <form action="{{ !empty($warehouse->id) ? route('warehouse.update', ['warehouse' => $warehouse->id]) : route('warehouse.store') }}" method="POST" data-parsley-validate>
                    {{ $warehouse->id ? method_field('PUT') : method_field('POST') }}
                    @csrf
                    {{-- Name --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Name</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="name" placeholder="Name" value="{{ $warehouse->name }}" required>
                        </div>
                    </div>
                    {{-- Capacity --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Capacity(MT)</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control js-currency-input" name="capacity" placeholder="capacity" value="{{ $warehouse->capacity }}" required>
                        </div>
                    </div>
                    {{-- Type --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Type</label>
                        <div class="col-md-8">
                            <select name="type" class="form-control">
                                <option value="">Select Type</option>
                                @foreach(['Internal', 'External', 'Procurement Center', 'Distribution Center', 'Cooperative'] as $type)
                                    @php 
                                        $value = Str::lower($type);
                                        $value = Str::replace(' ', '_', $value);
                                    @endphp
                                    <option value="{{ $value }}" {{ $value == $warehouse->type ? 'selected': ''}}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Address --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Address</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="address" placeholder="Address" value="{{ $warehouse->address }}">
                        </div>
                    </div>
                    
                    {{-- Status --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Status</label>
                        <div class="col-md-8">
                            <div class="d-flex">
                                <div style="display: flex;align-items: center" class="me-5">
                                    <input  class="radio_button_checkout"  type="radio" id="status-active" name="status" value="active" {{ $warehouse->status != 'inactive' ? 'checked' : '' }} 
                                        required
                                        data-parsley-required-message="Please choose status" 
                                        data-parsley-errors-container="#js-status-errors"/>
                                    <label for="status-active" class="ms-2">Active</label>
                                </div>
    
                                <div style="display: flex;align-items: center">
                                    <input  class="radio_button_checkout" type="radio" id="status-inactive" name="status" value="inactive"  {{ $warehouse->status == 'inactive' ? 'checked' : '' }} 
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
                        <label class="col-md-3 col-from-label"></label>
                        <div class="col-md-8">
                            <button type="submit" name="button"  class="btn btn-primary mt-2">{{ $warehouse->id ? 'Edit' : 'Create'}}</button>
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


@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        
    });
</script>
@endpush