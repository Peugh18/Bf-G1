<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class PreferencesController extends Controller
{
    public function show(Request $request)
    {
        return response()->json(['preferences' => $request->user()->preferences ?? ['theme' => 'system', 'density' => 'comfortable']]);
    }
    public function update(Request $request)
    {
        $data = $request->validate(['theme' => 'required|in:light,dark,system', 'density' => 'required|in:comfortable,compact']);
        $user = $request->user(); $user->preferences = $data; $user->save();
        return response()->json(['preferences' => $user->preferences]);
    }
}
