@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <div class="container-fluid">

        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col">
                        <h5 class="mb-md-0 h6">Create Staff</h5>
                    </div>
                    
                </div>
              <div class="card-body" >
                <form action="{{route('staff.store')}}" method="POST" id="country_from">
                    @csrf
                    {{-- First Name --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">First Name</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="first_name" placeholder="First Name">
                        </div>
                    </div>
                    {{-- Last Name --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Last Name</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="last_name" placeholder="Last Name">
                        </div>
                    </div>
                    {{-- Contact Number --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Contact Number</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="phone_number" placeholder="Contact Number">
                        </div>
                    </div>
                    {{-- Email --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Email</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="email" placeholder="Email">
                        </div>
                    </div>
                    {{-- Gender --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Gender</label>
                        <div class="col-md-8">
                            <select class="form-control aiz-selectpicker" name="gender" id="">
                                <option value="" checked>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
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
                            <div style="display: flex;align-items: center">
                                <input  class="radio_button_checkout"  type="radio" id="status" name="status" value="active"  checked/>
                                <div style="position: relative;left: 16px;">
                                    <span>Active</span>
                                </div>
                            </div>

                            <div style="display: flex;align-items: center">
                                <input  class="radio_button_checkout" type="radio" id="status" name="status" value="block"   />
                                <div style="position: relative;left: 16px;">
                                    <span>Block</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mar-all mb-2" style=" text-align: end;">
                            <button type="submit" name="button"  value="publish"
                                class="btn btn-primary">Create</button>
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
   
@stop
@push('scripts')
<script type="text/javascript">
    $(document).ready(function()
    {   
        
    });
    function myFunction() {
            alert('aaa')
        }
</script>
@endpush