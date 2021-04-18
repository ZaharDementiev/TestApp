<?php

namespace App\Http\Controllers;

use App\Models\Prod;
use App\Models\ProdDoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdController extends Controller
{
    public function getData(Request $request)
    {
        $fileName = time().'_'.$request->jsondata->getClientOriginalName();
        $filePath = $request->file('jsondata')->storeAs('uploads', $fileName, 'public');
        $jsonString = file_get_contents(base_path('storage/app/public/' . $filePath));
        $data = json_decode($jsonString, true);

        self::uploadData($data, 'storage/app/public/' . $filePath);

        return redirect()->back();
    }

    public static function uploadData($data, $filePath)
    {
        $usedIds = [];

        foreach ($data as $item) {
            $prod = Prod::where('id', $item['id'])->first();

            if (!$prod) {
                $prod = new Prod();
                $prod->id = $item['id'];
            }

            $prod->name = $item['name'];
            $prod->price = $item['price'];
            $prod->characteristics = json_encode($item['characteristics']);
            $prod->save();
            array_push($usedIds, $item['id']);
        }

        $nonUsedProds = Prod::whereNotIn('id', $usedIds)->get();
        if ($nonUsedProds->count() > 0) {
            foreach ($nonUsedProds as $prod) {
                $prod->delete();
            }
        }

        $doc = new ProdDoc();
        $doc->path = $filePath;
        $doc->save();
    }

    public function sortByPrice($direction = true)
    {
        if ($direction) {
            $prods = Prod::orderBy('price', "ASC")->get();
        } else {
            $prods = Prod::orderBy('price', "DESC")->get();
        }

        return response()->json([
            'code' => 200,
            'status' => true,
            'errors' => null,
            'data' => [
                'prods' => $prods,
            ]
        ], 200);
    }

    public function searchingProd(Request $request)
    {
        return response()->json([
            'code' => 200,
            'status' => true,
            'errors' => null,
            'data' => [
                'searched' => Prod::where('name', $request->input('name'))->first(),
            ]
        ], 200);
    }

    public function filter(Request $request)
    {
        $prods = DB::table('prods');

        if ($request->input('color')) {
            $prods = $prods->whereNotNull('characteristics->color');
        }
        if ($request->input('taste')) {
            $prods = $prods->whereNotNull('characteristics->taste');
        }
        if ($request->input('size')) {
            $prods = $prods->whereNotNull('characteristics->size');
        }
        if ($request->input('sounds')) {
            $prods = $prods->whereNotNull('characteristics->sounds');
        }
        $prods = $prods->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'errors' => null,
            'data' => [
                'prods' => $prods,
            ]
        ], 200);
    }
}
