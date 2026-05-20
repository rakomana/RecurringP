<?php

namespace App\Http\Controllers;

use App\Models\WebhookEvent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WebhookEventController extends Controller
{
    public function index()
    {
        return WebhookEvent::query()->latest()->paginate();
    }

    public function store(Request $request)
    {
        $webhookEvent = WebhookEvent::create($this->validatedData($request));

        return response()->json($webhookEvent, 201);
    }

    public function show(WebhookEvent $webhookEvent)
    {
        return $webhookEvent;
    }

    public function update(Request $request, WebhookEvent $webhookEvent)
    {
        $webhookEvent->update($this->validatedData($request, $webhookEvent));

        return $webhookEvent->fresh();
    }

    public function destroy(WebhookEvent $webhookEvent)
    {
        $webhookEvent->delete();

        return response()->noContent();
    }

    private function validatedData(Request $request, ?WebhookEvent $webhookEvent = null): array
    {
        return $request->validate([
            'provider' => ['required', 'string', 'max:255'],
            'event_id' => ['required', 'string', 'max:255', Rule::unique('webhook_events', 'event_id')->ignore($webhookEvent)],
            'event_type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:pending,processed,failed'],
            'payload' => ['required', 'array'],
            'processed_at' => ['nullable', 'date'],
        ]);
    }
}
