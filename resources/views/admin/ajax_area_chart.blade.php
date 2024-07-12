<table id="datatable" style="display:none;">
    <thead>
        <tr>
            <th></th>
            <th>Total Land Holding</th>
            <th>Actual Area</th>
        </tr>
    </thead>
    <tbody>
        @foreach($communeByFarmAreas as $communeByFarmArea) 
          @if ($communeByFarmArea['total_land_holding_ha'] > 0 && $communeByFarmArea['actual_area_ha'] > 0)
            <tr>
                <td>{{ $communeByFarmArea['name'] }}</td>
                <td>{{ $communeByFarmArea['total_land_holding_ha'] }}</td>
                <td>{{ $communeByFarmArea['actual_area_ha'] }}</td>
            </tr>
          @endif
        @endforeach
    </tbody>
</table>
