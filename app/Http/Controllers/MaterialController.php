<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use App\Http\Resources\MaterialResource;
use App\Http\Requests\CreateMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::with('measure','category')->paginate(
            $perPage = 6, $columns = ['*']
        );
        return MaterialResource::collection($materials);
    }

    public function store(CreateMaterialRequest $request)
    {
        $material = Material::create($request->all());
        return new MaterialResource($material);
    }

    public function show($id)
    {
        return new MaterialResource(material::findOrFail($id));
    }

    public function update(UpdateMaterialRequest $request, $id)
    {
        $material = Material::findOrFail($id)->update($request->all());
        return new MaterialResource(Material::findOrFail($id));
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();
        return new MaterialResource($material);
    }
}
