<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    public function index()
    {
        $data = Slider::all();
        return response()->json($data, 200);
    }
    public function show($id)
    {
        $data = Slider::find($id);
        return response()->json(
            ['success' => true, 'message' => 'Thành công', 'data' => $data],
            200
        );
    }
    public function slider_list($position){
        $args = [
            ['position','=',$position],
            ['status','=',1]
        ];
        $data = Slider::where($args)->orderBy('sort_order','ASC')->get();
        return response()->json(
            [
                ['success' => true, 'message' => 'Thành công', 'data' => $data],
            200 
            ]
        );
    }
    public function store(Request $request)
    {
        $slider = new Slider();
        $slider->name = $request->name; //form
        //upload image
        $slider->link = $request->link; //form
        $slider->sort_order = $request->sort_order; //form
        $slider->position = $request->position;
        $slider->created_at = date('Y-m-d H:i:s');
        $slider->created_by = 1;
        $slider->status = $request->status; //form
        $slider->save(); //Luuu vao CSDL
        return response()->json(
            ['success' => true, 'message' => 'Thành công', 'data' => $slider],
            201
        );
    }
    public function update(Request $request, $id)
    {
        $slider = Slider::find($id);
        $slider->name = $request->name; //form
        $slug = Str::of($request->name)->slug('-');
        //upload image
        $files = $request->image;
        if($files != null){
            if(is_file(public_path('images/slider/'.$slider->image)))
            {

                unlink(public_path('images/slider/'.$slider->image));
            }
            $extension = $files->getClientOriginalExtension();
            if(in_array($extension, ['jpg','png','gif','webp','jpeg','svg'])){
                $filename = $slug . '.' . $extension;
                $slider->image = $filename;
                $files->move(public_path('images/slider'),$filename);
            }
        } 


        $slider->link = $request->link; //form
        $slider->sort_order = $request->sort_order; //form
        $slider->position = $request->position;
        $slider->updated_at = date('Y-m-d H:i:s');
        $slider->updated_by = 1;
        $slider->status = $request->status; //form
        $slider->save(); //Luuu vao CSDL
        return response()->json(
            ['success' => true, 'message' => 'Thành công', 'data' => $slider],
            201
        );
    }
    public function destroy($id)
    {
        $slider=Category::find($id);
        if($slider==null){
            return response()->json(
                ['success' => false, 'message' => 'Xóa dữ liệu không thành công', 'data' => null],
                404
            );
        }
        $slider->delete();
        return response()->json(
            ['success' => true, 'message' => 'Thành công', 'data' => $slider],
            200
        );
    }
}
