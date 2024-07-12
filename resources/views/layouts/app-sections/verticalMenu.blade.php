

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <!-- ! Hide app brand if navbar-full -->
  <div class="app-brand demo">
    <a href="{{url('/')}}" class="app-brand-link">
      <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
      <span class="app-brand-text demo menu-text fw-bold ms-2">Upstream</span>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">
    <li class="aiz-side-nav-item">
        <a class="aiz-side-nav-link">
            <i class="las la-home aiz-side-nav-icon"></i>
            <span class="aiz-side-nav-text">Season Master</span>
        </a>
    </li>
    <li class="aiz-side-nav-item">
        <a href="{{ route('staff.index') }}" class="aiz-side-nav-link">
            <i class="las la-home aiz-side-nav-icon"></i>
            <span class="aiz-side-nav-text">Staff</span>
        </a>
    </li>
    

    {{-- <li class="aiz-side-nav-item">
      <a href="{{ route('farm_land.index') }}" class="aiz-side-nav-link">
          <i class="las la-home aiz-side-nav-icon"></i>
          <span class="aiz-side-nav-text">Farm Land Report</span>
      </a>
    </li> --}}

    <li class="aiz-side-nav-item"> 
        <a class="aiz-side-nav-link" href="{{ route('crop-informations.index') }}"> 
            <i class="las la-home aiz-side-nav-icon"></i> 
            <span class="aiz-side-nav-text">Crop Master</span> 
        </a> 
    </li>

    <li class="aiz-side-nav-item menu-item">
      <div class="aiz-side-nav-link">
          <i class="las la-shopping-cart aiz-side-nav-icon"></i>
          <span class="aiz-side-nav-text">Location Master</span>
          <div id="dropdown-1" class="dropdown"></div>
      </div>
      <ul class="aiz-side-nav-list sub-menu" >
          <li class="aiz-side-nav-item">
              <a href="{{ route('country.index') }}"
                  class="aiz-side-nav-link ">
                  <span class="aiz-side-nav-text">Country</span>
              </a>
          </li>

          <li class="aiz-side-nav-item">
              <a href="{{ route('province.index') }}"
                class="aiz-side-nav-link ">
                <span class="aiz-side-nav-text">Province</span>
              </a>
          </li>
          <li class="aiz-side-nav-item">
              <a href="{{ route('district.index') }}"
                class="aiz-side-nav-link ">
                <span class="aiz-side-nav-text">District</span>
              </a>
          </li>
          <li class="aiz-side-nav-item">
              <a href="{{ route('commune.index') }}"
                class="aiz-side-nav-link ">
                <span class="aiz-side-nav-text">Commune</span>
              </a>
          </li>
      </ul>
    </li>
    <li class="aiz-side-nav-item">
      <a href="{{ route('farmer.index') }}" class="aiz-side-nav-link">
          <i class="las la-home aiz-side-nav-icon"></i>
          <span class="aiz-side-nav-text">Farmer Report</span>
      </a>
    </li>
    
  </ul>
  
</aside>

{{-- 
<style>
  
</style> --}}

@push('scripts')
<script>
$(".menu-item").on('click',function(){
  if($(this).find("ul:first").is(":hidden")){
  $(this).find("ul:first").slideDown();
  return false;
  }
  // else{
  // $(this).find("ul:first").slideUp();
  // return false;
  // }
});
</script>
@endpush