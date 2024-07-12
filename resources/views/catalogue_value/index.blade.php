@extends('layouts.app')

@section('content')
    <div class="container p-0">
        <form action="{{ route('catalogue-values.index')}}" class="mb-5" id="js-catalogue-form">
            {{-- @csrf --}}
            <div class="form-group row align-items-center">
                <div class="col">
                    <div>
                        <label for="js-code" class="form-input-label">Code</label>
                        <input type="text" id="js-code" class="form-control" name="code" value="{{ $code }}" />
                    </div>
                </div>
                <div class="col">
                    <label for="js-name" class="form-input-label">Name</label>
                    <input id="js-name" name="name" type="text" class="form-control" value="{{ $name }}" autocomplete="off" placeholder="Name">
                </div>
                <div class="col">
                    <label for="js-display-name" class="form-input-label">Display Name</label>
                    <input id="js-display-name" name="display_name" type="text" class="form-control" value="{{ $display_name }}" autocomplete="off" placeholder="Display Name">
                </div>
                <div class="col">
                    <label for="">Status</label>
                    <div class="d-flex mt-2">
                        <div class="form-check" style="margin-right: 1rem;">
                            <label for="js-status-active" class="form-input-label">Active</label>
                            <input id="js-status-active" name="status" type="radio" class="form-check-input" value="1" {{ $status === '1' ? 'checked' : '' }}>
                        </div>
                        <div class="form-check">
                            <label for="js-status-inactive" class="form-input-label">Inactive</label>
                            <input id="js-status-inactive" name="status" type="radio" class="form-check-input" value="0" {{ $status === '0' ? 'checked' : '' }}>
                        </div>
                    </div>
                    
                </div>
                <div style="width: 260px;">
                    <button type="submit" class="btn btn-primary" style="margin-right: 1rem;">Search</button>
                    <button type="button" class="btn btn-secondary js-reset">Reset</button>
                </div>
            </div>
        </form>

        
        <table class="table table-bordered">
            <thead>
              <tr style="background-color: #2E7F25;">
                <th scope="col" style="color:white;">Code</th>
                <th scope="col" style="color:white;">Name</th>
                <th scope="col" style="color:white;">Display Name</th>
                <th scope="col" style="color:white;">Status</th>
              </tr>
            </thead>
            <tbody>
                @if ($catalogueValues->count())
                    @foreach($catalogueValues as $catalogueValue)
                        <tr>
                            <td>{{ $catalogueValue->CODE}}</td>
                            <td>{{ $catalogueValue->NAME}}</td>
                            <td>{{ $catalogueValue->DISP_NAME}}</td>
                            <td>{{ $catalogueValue->catalogue_status}}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <div class="position-relative" style="min-height: 30px">
            {{ $catalogueValues->links('shared.paginator') }}

            <div style="position: absolute;right: 19px; top:0"><span class="font-weight-bold">{{ $catalogueValues->total() }}</span> results found</div>
        </div>
    </div>
@endsection 

@section('style')

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.js-reset').click(function(){
                $('input[type="text"]').val('');
                document.querySelector('input[name="status"]:checked').checked = false;
            });
        });
    </script>
@endpush