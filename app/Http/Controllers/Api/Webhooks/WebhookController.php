<?php

namespace App\Http\Controllers\Api\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class WebhookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // check if user token have ability to view webhooks
        if(!$user->tokenCan('webhooks-read')){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $webhooks = Webhook::where('created_by', $user->id)->get();
        return response()->json($webhooks);
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
    public function store(Request $request)
    {   
        if(!$request->user()->tokenCan('webhooks-create')){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'for' => 'required',
            'event' => 'required',
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = $request->user();

        $webhook = new Webhook();
        $webhook->uuid = (string) Str::uuid();
        $webhook->created_by = $user->id;
        $webhook->org_id = $user->org_id;
        $webhook->name = $request->name;
        $webhook->type = $request->type;
        $webhook->for = $request->for;
        $webhook->event = $request->event;
        $webhook->url = $request->url;
        $webhook->status = 'active';

        try {
            $webhook->save();
            return response()->json($webhook);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }



    }

    /**
     * Display the specified resource.
     */
    public function show(Webhook $webhook)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Webhook $webhook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Webhook $webhook)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Webhook $webhook)
    {
        //
    }
}
