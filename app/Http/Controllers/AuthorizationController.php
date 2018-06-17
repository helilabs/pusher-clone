<?php

namespace App\Http\Controllers;

use App\Models\PushRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Authorization;

class AuthorizationController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'channel' => 'required',
            'session_id' => 'required'
        ]);

        $model = Authorization::firstOrCreate([
            'channel' => $request->input('channel'),
            'session_id' => $request->input('session_id')
        ]);

        if(!$model){
            return response()->json(['meta' => generate_meta('failure', 'CANNOT CREATE')], 400);
        }

        return response()->json(['meta' => generate_meta('success')], 200);
    }
}
