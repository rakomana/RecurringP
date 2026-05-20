<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebhookEventRequest;
use App\Models\WebhookEvent;

class WebhookEventController extends Controller
{
    public function index()
    {
        return WebhookEvent::query()->with('company')->latest()->paginate();
    }

    public function store(WebhookEventRequest $request)
    {
        $webhookEvent = WebhookEvent::create($request->validated());

        return response()->json($webhookEvent->load('company'), 201);
    }

    public function show(WebhookEvent $webhookEvent)
    {
        return $webhookEvent->load('company');
    }

    public function update(WebhookEventRequest $request, WebhookEvent $webhookEvent)
    {
        $webhookEvent->update($request->validated());

        return $webhookEvent->fresh()->load('company');
    }

    public function destroy(WebhookEvent $webhookEvent)
    {
        $webhookEvent->delete();

        return response()->noContent();
    }

}
