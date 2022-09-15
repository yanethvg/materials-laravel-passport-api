<?php

namespace App\Http\Controllers;

use App\Models\UnitMeasure;
use Illuminate\Http\Request;
use App\Http\Resources\UnitMeasureResource;
use App\Http\Requests\CreateUnitMeasureRequest;
use App\Http\Requests\UpdateUnitMeasureRequest;

class UnitMeasureController extends Controller
{
    public function index()
    {
        return UnitMeasureResource::collection(UnitMeasure::all());
    }

    public function paginate(Request $request)
    {
        $measures = UnitMeasure::withCount('materials')->orderBy('updated_at', 'desc')->filter($request->search)->paginate(
            $perPage = 6, $columns = ['*']
        );
        return UnitMeasureResource::collection($measures);
    }

    
    public function store(CreateUnitMeasureRequest $request)
    {
        $measure = UnitMeasure::create($request->all());
        return new UnitMeasureResource($measure);
    }

    public function show($id)
    {
        return new UnitMeasureResource(UnitMeasure::findOrFail($id));
    }

    
    public function update(UpdateUnitMeasureRequest $request, $id)
    {
        $measure = UnitMeasure::findOrFail($id)->update($request->all());
        return new UnitMeasureResource(UnitMeasure::findOrFail($id));
    }

    public function destroy($id)
    {
        $measure = UnitMeasure::findOrFail($id);
        $measure->delete();
        return new UnitMeasureResource($measure);
    }
}
