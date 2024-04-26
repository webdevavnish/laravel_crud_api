<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
// for hashing
use Illuminate\Support\Facades\Hash;
// for log
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Admin::all();
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
        // dd($request->input());
        $validatedData = $request->validate([
            'name'=>'required|min:5|max:20',
            'mobile'=>'required|min:10',
            'password'=>'required|min:8|max:20'
        ]);
        $admin = new Admin();
        // can use here create, make method
        $admin->name = $validatedData['name'];
        $admin->mobile = $validatedData['mobile'];
        $admin->password = Hash::make($validatedData['password']);
        $admin->save();
        return response()->json(['message' => 'Admin created successfully'], 201);
    }
    catch(ValidationException $e){
        return response()->json(['errors' => $e->errors()], 422);
    }
       

    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        try{
        // $admin = Admin::findOrFail($admin);
        // it is done by laravel
        return response()->json(['Message'=>$admin],201);
        }
        catch(\Exception $e){
            return response()->json(['Message'=>$e->getMessage()],500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $adminId)
    {
        try {
            // Find the admin record by ID
            $admin = Admin::findOrFail($adminId);
    
            // Validate the incoming request data
            $validatedData = $request->validate([
                'name' => 'required|min:5|max:20',
            ]);
    
            // Update the admin record with the validated data
            $admin->name = $validatedData['name'];
            $admin->save();
    
            // Optionally, you can return a response to the client indicating success
            // Log::info('Update Admin Response: ' . json_encode(['message' => 'Admin updated successfully']));
           Log::channel('mainlog')->info(['Message'=>'Admin saved',"Status"=>0]);
            return response()->json(['message' => 'Admin updated successfully'], 200);
        } catch (ValidationException $e) {
            // If validation fails, return a JSON response with validation errors
            return response()->json(['errors' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            // If the admin record with the specified ID is not found, return a 404 response
            return response()->json(['message' => 'Admin not found'], 404);
        } catch (\Exception $e) {
            Log::error('Failed to update admin: ' . $e->getMessage());
            // Handle any other exceptions and return an appropriate response
            return response()->json(['message' => 'Failed to update admin'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        try{
        $admin->delete();
        return response()->json(['Status'=>1,'Message'=>$admin->name." deleted successfullyy"]);
        }
        catch(\Exception $e){
            Log::channel('mainlog')->info(['Message'=> "deleted successfully"]);
            return response()->json(['Message'=>$e->getMessage()],200);
        }
        
    }
}
