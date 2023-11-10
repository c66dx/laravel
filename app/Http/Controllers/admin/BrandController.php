<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Brand;
use Illuminate\Support\Facades\Session;

class BrandController extends Controller
{
    public function index(Request $request){
        $brands = Brand::latest('id');

        if($request->get('keyword')){
            $brands = $brands->where('name','like','%' .$request->keyword.'%');
        }

        $brands = $brands->paginate(10);

        return view('admin.brands.list',compact('brands'));
    }

    public function create() {
        return view('admin.brands.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands',
        ]);

        if($validator->passes()){
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status= $request->status;
            $brand->save();

            Session::flash('success', 'Brand added successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Brand added successfully.'
            ]);

        } else {
            Session::flash('error', 'Error adding brand. Please check the form.');

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request) {
        $brand = Brand::find($id);

        if (empty($brand)) {
            $request->session()->flash('error','Record not found.');
            return redirect()->route('brands.index');
        }
        $data['brand'] = $brand;
        return view('admin.brands.edit',$data);
    }

    public function update($id, Request $request) {
        $brand = Brand::find($id);
    
        if (empty($brand)) {
            $request->session()->flash('error', 'Record not found.');
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $brand->id . ',id',
        ]);
    
        if ($validator->passes()) {
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Brand updated successfully'
            ]);
    
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    
    public function destroy($id, Request $request) {
        $brand = Brand::find($id);

        if (empty($brand)) {
            Session::flash('error', 'Record not found.');

            return response([
                'status' => false,
                'notFound' => true
            ]);
        }

        if ($brand->delete()) {
            Session::flash('success', 'Brand deleted successfully.');

            return response([
                'status' => true,
                'message' => 'Brand deleted successfully.'
            ]);
        } else {
            Session::flash('error', 'Failed to delete brand.');

            return response([
                'status' => false,
                'message' => 'Failed to delete brand.'
            ]);
        }
    }
}
    


