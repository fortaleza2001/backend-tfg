<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use Illuminate\Http\Request;

use App\Models\Country;

class AirportController extends Controller
{
    public function index()
    {
        return Airport::all();
    }

    public function byCountry($countryCode)
    {
        $airports = Airport::where('iso_country', strtoupper($countryCode))->get();
        return response()->json($airports);
    }

    public function show($id)
    {
        return Airport::findOrFail($id);
    }

    public function store(Request $request)
    {
        $airport = Airport::create($request->all());
        return response()->json($airport, 201);
    }

    public function update(Request $request, $id)
    {
        $airport = Airport::findOrFail($id);
        $airport->update($request->all());
        return response()->json($airport);
    }

    public function destroy($id)
    {
        $airport = Airport::findOrFail($id);
        $airport->delete();
        return response()->json(null, 204);
    }

    public function obtenerPaises()
    {
        $paises = Country::all();
        return response()->json($paises, 200);
    }
    public function obtenerAeropuertosPais($pais)
    {
        $aeropuertos = Airport::where('iso_country', $pais)->get();

        return response()->json($aeropuertos,200);
    }

}
