<?php

namespace App\Http\Controllers;

use App\Models\Listings;
use App\Http\Requests\StoreListingsRequest;
use App\Http\Requests\UpdateListingsRequest;
use Exception;
use Illuminate\Http\Request;

class ListingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Listings::query();
        return response()->json($query->get());
    }

    public function show($id)
    {
        try{
           return Listings::findOrFail($id); 
        }catch(Exception $e){
            return $e->getMessage();
        }
        
    }

    public function shop($id)
    {
        try{
           return Listings::where('shopId',$id)->get(); 
        }catch(Exception $e){
            return $e->getMessage();
        }
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try{
            $listing = new Listings;
            $body = $request->json()->all();
            $listing->name =$body['name'];
            $listing->imageSrc =$body['imageSrc'];
            $listing->imageHref =$body['imageHref'];
            $listing->price =$body['price'];
            $listing->stock =$body['stock'];
            $listing->shopId =$body['shopId'];
            $listing->save();
            return 'listing created';
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($id, Request $request)
    {
        //
        try{
            $listing = Listings::findOrFail($id);
            $body = $request->json()->all();
            $listing->name =$body['name'];
            $listing->imageSrc =$body['imageSrc'];
            $listing->imageHref =$body['imageHref'];
            $listing->price =$body['price'];
            $listing->stock =$body['stock'];
            $listing->save();
            return 'listing created';
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listings $listings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateListingsRequest $request, Listings $listings)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $listing = Listings::findOrFail($id)->delete();
    }
}
