@if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <strong>{!! session()->get('success') !!}</strong>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        <ul class="mb-0 pl-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
