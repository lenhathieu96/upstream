<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $warehouses = Warehouse::with('staff')->paginate(12);

        return view('warehouse.index', compact('warehouses'));
    }

    public function create()
    {
        $warehouse = new Warehouse();

        return $this->edit($warehouse);
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouse.form', compact('warehouse'));
    }

    public function store(WarehouseRequest $warehouseRequest)
    {
        return $this->createOrUpdate($warehouseRequest, new Warehouse());
    }


    public function update(WarehouseRequest $warehouseRequest, Warehouse $warehouse)
    {
        return $this->createOrUpdate($warehouseRequest, $warehouse);
    }

    private function createOrUpdate(WarehouseRequest $staffRequest, Warehouse $warehouse)
    {
        $isNewWarehouse = empty($warehouse->id);

        $warehouse->name = $staffRequest->name;
        $warehouse->capacity = $staffRequest->capacity;
        $warehouse->type = $staffRequest->type;
        $warehouse->lat = $staffRequest->lat;
        $warehouse->lng = $staffRequest->lng;
        $warehouse->address = $staffRequest->address;
        $warehouse->status = $staffRequest->status;
        $warehouse->save();

        if (empty($warehouse->code)) {
            $warehouse->generateCode();
            $warehouse->save();
        }

        $message = $isNewWarehouse ? 'Warehouse created successfull' : 'Warehouse updated successfull';

        return redirect()->route('warehouse.edit', ['warehouse' => $warehouse->id])->with('success', $message);
    }

    public function show(Warehouse $warehouse)
    {
        return redirect()->route('warehouse.edit', ['warehouse' => $warehouse]);
    }

    public function destroy(Warehouse $warehouse)
    {
        abort(500);
    }
}
