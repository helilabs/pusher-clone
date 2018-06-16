<?php

namespace App\Http\Controllers;

use App\Models\PushRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PushController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'channel' => 'required'
        ]);

        $model = PushRequest::create([
            'channel' => $request->input('channel'),
            'payload' => $request->input('data')
        ]);

        if(!$model){
            return response()->json(['meta' => generate_meta('failure', 'Can not send push request')], 403);
        }
        $model->queue($request->header('X-APP-ID'));
        $model->sent();
        return response()->json(['meta' => generate_meta('success')], 200);
    }
}
