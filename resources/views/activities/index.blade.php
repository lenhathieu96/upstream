@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <div class="container mt-5">
      <h2>Laravel Google Maps Multiple Markers Example - ItSolutionStuff.com</h2>
      <div id="map" style="height: 500px;"></div>
    </div>
@stop

@push('scripts')

<script type="text/javascript">
  function initMap() {
    const myLatLng = { lat: 10.7719514, lng: 106.726354 };
    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 15,
      center: myLatLng,
    });

    var locations = {{ Js::from($data_log) }};
  
            var infowindow = new google.maps.InfoWindow();
  
            var marker, i;
            for (i = 0; i < locations.length; i++) {  
             console.log( locations[i]['lat'] + "," +locations[i]['lng'])
                  marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i]['lat'], locations[i]['lng']),
                    map: map
                  });
                    
                  google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                      // infowindow.setContent(locations[i]['action']);
                      infowindow.open(map, marker);
                    }
                  })(marker, i));
            }
        }
        window.initMap = initMap;
</script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false&key=AIzaSyAfLu--o47wFfFifI1F2gnK0T8l7oje08Q&callback=initMap">
</script>

@endpush