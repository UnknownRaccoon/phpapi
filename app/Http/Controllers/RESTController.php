<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Abstract REST controller
 * Defines basic request handling & authorization
 */
class RESTController extends Controller
{
    protected $model;
    protected $authRequired = ['index', 'store', 'show', 'destroy', 'update'];

    public function index()
    {
        if(in_array('index', $this->authRequired)) {
            $this->authorize('index', $this->model);
        }
        return $this->model::all();
    }

    public function store(Request $request)
    {
        if(in_array('store', $this->authRequired)) {
            $this->authorize('store', $this->model);
        }
        $result = $this->model::create($request->all());
        $status = 201;
        if(array_key_exists('validation_errors', $result)) {
            $status = 406;
        }
        return response()->json($result, $status);
    }

    public function show($id)
    {
        $instance = $this->model::findOrFail($id);
        if(in_array('show', $this->authRequired)) {
            $this->authorize($instance);
        }
        return response()->json($instance, 200);
    }

    public function update(Request $request, $id)
    {
        $instance = $this->model::findOrFail($id);
        if(in_array('update', $this->authRequired)) {
            $this->authorize($instance);
        }
        $result = $instance->update($request->all());
        if($result !== true) {
            return response()->json($result, 406);
        }
        else {
            return response()->json([], 204);
        }
    }

    public function destroy($id)
    {
        $instance = $this->model::findOrFail($id);
        if(in_array('destroy', $this->authRequired)) {
            $this->authorize($instance);
        }
        $this->model::destroy($instance->id);
        return response()->json([], 204);
    }
}
