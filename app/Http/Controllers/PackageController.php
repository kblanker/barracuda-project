<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;

class PackageController extends Controller
{
    /**
     * Creates a new package and tracking record.
     * 
     * @param Request $request
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'origin' => 'required',
            'destination' => 'required',
            'weight' => 'required|numeric',
        ]);

        $input = $request->all();

        $package = new Package;
        $package->fill($input);
        $package->save();
        $package->tracking()->create([
            'location' => $input['origin'],
            'status' => 'arrived',
        ]);

        return response()->json(['data' => $package]);
    }

    /**
     * Returns the progress of the package delivery.
     * 
     * @param Request $request
     */
    public function progress(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:packages'
        ]);
        $id = $request->input('id');
        $package = Package::find($id);

        if(!$package) {
            return response()->json(['error' => 'Package not found.']);
        }

        $records = $package->tracking;
        return response()->json(['data' => ['package' => $package, 'tracking' => $records]]);
    }

    /**
     * Creates or updates tracking on a package.
     * 
     * @param Request $request
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:packages',
            'location' => 'required',
            'status' => 'required|in:arrived,departed',
        ]);

        $id = $request->input('id');
        $location = $request->input('location');

        $package = Package::find($id)->first();
        $tracking = $package->tracking()->where('location',$location)->first();

        $input = $request->except(['id']);

        if($tracking) {
            $tracking->fill($input);
            $tracking->save();
        } else {
            $tracking = $package->tracking()->create($input);
        }

        return response()->json(['data' => $tracking]);
    }

    /**
     * Marks package as delivered.
     * 
     * @param Request $request
     */
    public function delivered(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:packages',
        ]);

        $id = $request->input('id');

        $package = Package::find($id);
        $package->delivered = true;
        $package->save();

        return response()->json(['data' => $package]);
    }
}
