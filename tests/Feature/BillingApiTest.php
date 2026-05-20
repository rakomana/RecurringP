<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BillingApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Passport::actingAs(User::factory()->create());
    }

    public function test_companies_can_be_created_with_users(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/companies', [
            'name' => 'Acme Billing',
            'slug' => 'acme-billing',
            'email' => 'billing@example.com',
            'default_currency' => 'USD',
            'timezone' => 'UTC',
            'user_ids' => [$user->id],
        ]);

        $response->assertCreated()
            ->assertJsonPath('slug', 'acme-billing')
            ->assertJsonPath('users.0.id', $user->id);

        $this->assertDatabaseHas('companies', [
            'slug' => 'acme-billing',
        ]);

        $this->assertDatabaseHas('company_user', [
            'company_id' => $response->json('id'),
            'user_id' => $user->id,
            'role' => 'member',
        ]);
    }

    public function test_plan_slugs_are_unique_per_company(): void
    {
        $firstCompany = $this->createCompany('first-company');
        $secondCompany = $this->createCompany('second-company');

        $payload = [
            'name' => 'Starter',
            'slug' => 'starter',
            'amount' => 1000,
            'currency' => 'USD',
            'billing_interval' => 'month',
        ];

        $this->postJson('/api/plans', $payload + [
            'company_id' => $firstCompany->id,
        ])->assertCreated();

        $this->postJson('/api/plans', $payload + [
            'company_id' => $firstCompany->id,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('slug');

        $this->postJson('/api/plans', $payload + [
            'company_id' => $secondCompany->id,
        ])->assertCreated();
    }

    public function test_subscription_invoice_and_payment_flow_can_be_created(): void
    {
        $company = $this->createCompany();
        $user = User::factory()->create();
        $company->users()->attach($user->id, ['role' => 'member']);
        $plan = $this->createPlan($company);

        $subscriptionResponse = $this->postJson('/api/subscriptions', [
            'company_id' => $company->id,
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'current_period_starts_at' => now()->toISOString(),
            'current_period_ends_at' => now()->addMonth()->toISOString(),
        ]);

        $subscriptionResponse->assertCreated()
            ->assertJsonPath('company.id', $company->id)
            ->assertJsonPath('plan.id', $plan->id);

        $invoiceResponse = $this->postJson('/api/invoices', [
            'company_id' => $company->id,
            'user_id' => $user->id,
            'subscription_id' => $subscriptionResponse->json('id'),
            'number' => 'INV-0001',
            'status' => 'open',
            'subtotal' => 1000,
            'tax' => 150,
            'total' => 1150,
            'currency' => 'USD',
            'issued_at' => now()->toISOString(),
            'due_at' => now()->addWeek()->toISOString(),
        ]);

        $invoiceResponse->assertCreated()
            ->assertJsonPath('number', 'INV-0001')
            ->assertJsonPath('company.id', $company->id);

        $paymentResponse = $this->postJson('/api/payments', [
            'company_id' => $company->id,
            'invoice_id' => $invoiceResponse->json('id'),
            'user_id' => $user->id,
            'provider' => 'manual',
            'status' => 'succeeded',
            'amount' => 1150,
            'currency' => 'USD',
            'paid_at' => now()->toISOString(),
        ]);

        $paymentResponse->assertCreated()
            ->assertJsonPath('status', 'succeeded')
            ->assertJsonPath('invoice.id', $invoiceResponse->json('id'));

        $this->assertDatabaseHas('subscriptions', [
            'company_id' => $company->id,
            'user_id' => $user->id,
            'plan_id' => $plan->id,
        ]);

        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoiceResponse->json('id'),
            'amount' => 1150,
        ]);
    }

    public function test_payment_attempt_usage_record_and_webhook_event_can_be_created(): void
    {
        $company = $this->createCompany();
        $user = User::factory()->create();
        $plan = $this->createPlan($company);
        $subscription = $this->createSubscription($company, $user, $plan);
        $invoice = $this->createInvoice($company, $user, $subscription);
        $payment = $this->createPayment($company, $user, $invoice);

        $this->postJson('/api/payment-attempts', [
            'company_id' => $company->id,
            'payment_id' => $payment->id,
            'invoice_id' => $invoice->id,
            'provider' => 'manual',
            'status' => 'succeeded',
            'amount' => 1150,
            'currency' => 'USD',
            'attempted_at' => now()->toISOString(),
            'response_payload' => ['ok' => true],
        ])->assertCreated()
            ->assertJsonPath('payment.id', $payment->id);

        $this->postJson('/api/usage-records', [
            'company_id' => $company->id,
            'subscription_id' => $subscription->id,
            'user_id' => $user->id,
            'metric' => 'api_calls',
            'quantity' => 250,
            'recorded_at' => now()->toISOString(),
        ])->assertCreated()
            ->assertJsonPath('metric', 'api_calls');

        $this->postJson('/api/webhook-events', [
            'company_id' => $company->id,
            'provider' => 'stripe',
            'event_id' => 'evt_test_123',
            'event_type' => 'invoice.paid',
            'status' => 'pending',
            'payload' => ['id' => 'evt_test_123'],
        ])->assertCreated()
            ->assertJsonPath('event_id', 'evt_test_123');
    }

    public function test_plan_can_be_updated_and_deleted(): void
    {
        $company = $this->createCompany();
        $plan = $this->createPlan($company);

        $this->patchJson("/api/plans/{$plan->id}", [
            'company_id' => $company->id,
            'name' => 'Starter Plus',
            'slug' => 'starter-plus',
            'amount' => 1500,
            'currency' => 'USD',
            'billing_interval' => 'month',
            'is_active' => true,
        ])->assertOk()
            ->assertJsonPath('slug', 'starter-plus');

        $this->assertDatabaseHas('plans', [
            'id' => $plan->id,
            'slug' => 'starter-plus',
            'amount' => 1500,
        ]);

        $this->deleteJson("/api/plans/{$plan->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('plans', [
            'id' => $plan->id,
        ]);
    }

    private function createCompany(string $slug = 'acme'): Company
    {
        return Company::create([
            'name' => str($slug)->headline()->toString(),
            'slug' => $slug,
            'default_currency' => 'USD',
            'timezone' => 'UTC',
        ]);
    }

    private function createPlan(Company $company): Plan
    {
        return Plan::create([
            'company_id' => $company->id,
            'name' => 'Starter',
            'slug' => 'starter',
            'amount' => 1000,
            'currency' => 'USD',
            'billing_interval' => 'month',
        ]);
    }

    private function createSubscription(Company $company, User $user, Plan $plan): Subscription
    {
        return Subscription::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'current_period_starts_at' => now(),
            'current_period_ends_at' => now()->addMonth(),
        ]);
    }

    private function createInvoice(Company $company, User $user, Subscription $subscription): Invoice
    {
        return Invoice::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'number' => 'INV-0001',
            'status' => 'open',
            'subtotal' => 1000,
            'tax' => 150,
            'total' => 1150,
            'currency' => 'USD',
        ]);
    }

    private function createPayment(Company $company, User $user, Invoice $invoice): Payment
    {
        return Payment::create([
            'company_id' => $company->id,
            'invoice_id' => $invoice->id,
            'user_id' => $user->id,
            'provider' => 'manual',
            'status' => 'succeeded',
            'amount' => 1150,
            'currency' => 'USD',
        ]);
    }
}
