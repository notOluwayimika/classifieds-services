<?php

namespace App\Http\Controllers;

use App\Models\Listings;
use App\Http\Requests\StoreListingsRequest;
use App\Http\Requests\UpdateListingsRequest;
use Exception;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreListingsRequest $request)
    {
        //
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
    public function destroy(Listings $listings)
    {
        //
    }
}
