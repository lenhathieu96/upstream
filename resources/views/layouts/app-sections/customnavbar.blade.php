

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <!-- ! Hide app brand if navbar-full -->
    <div class="app-brand demo">
      <a href="{{url('/')}}" class="app-brand-link">
        <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
        <span class="app-brand-text demo menu-text fw-bold ms-2">Upstream</span>
      </a>
    </div>
  
    <div class="menu-inner-shadow"></div>
  
    <div class="aiz-sidebar-wrap">
        <div class="aiz-sidebar left c-scrollbar">
            <div class="aiz-side-nav-wrap">
                <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">
                    
                    {{-- Dashboard --}}
                        <li class="aiz-side-nav-item">
                            <a href="{{route('dashboard')}}" class="aiz-side-nav-link">
                                <i class="las la-home aiz-side-nav-icon"></i>
                                <span class="aiz-side-nav-text">Dashboard</span>
                            </a>
                        </li>
                    {{-- @endcan --}}
    
    
                    <!-- Product -->
                   
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-shopping-cart aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">Products</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">Add New product</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-shopping-cart aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">Products</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">Add New product</span>
                                </a>
                            </li>
                        </ul>
                    </li>
    
                   
                </ul>
                <!-- .aiz-side-nav -->
            </div><!-- .aiz-side-nav-wrap -->
        </div><!-- .aiz-sidebar -->
        <div class="aiz-sidebar-overlay"></div>
    </div>
    
  </aside>
  
  {{-- 
  <style>
    
  </style> --}}
  
  @push('scripts')
  <script src="{{ asset('js/aiz-core.js') }}" ></script>
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