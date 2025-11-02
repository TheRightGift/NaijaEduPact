<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class DonationController extends Controller
{
    /**
     * Create a new Stripe Checkout session and redirect the user.
     */
    public function startPayment(Request $request)
    {
        // --- Dynamic Minimum Calculation ---
        $minimum_usd = config('services.rates.min_usd');
        
        // As of Nov 2025, $1 USD = 1450 NGN.
        // In a production app, fetch this from an API and cache it.
        $usd_to_ngn_rate = config('services.rates.usd_to_ngn'); 
        
        // Use ceil() to round up and ensure we're *over* the minimum
        $minimum_ngn = ceil($minimum_usd * $usd_to_ngn_rate);

        $request->validate([
            'amount' => 'required|numeric|min:' . $minimum_ngn, // Set a minimum equivalent of $1 in local currency
            'project_id' => 'required|exists:projects,id',
        ], [
            'amount.min' => 'Your donation must be at least $1 USD.'
        ]);

        

        $project = Project::findOrFail($request->project_id);
        $user = Auth::user();
        $amountInKobo = $request->amount * 100; // Stripe uses kobo/cents

        // 1. Set Stripe API Key
        Stripe::setApiKey(config('services.stripe.secret'));

        // 2. Create the Stripe Checkout Session
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'ngn', // Use your currency
                    'product_data' => [
                        'name' => 'Donation for: ' . $project->title,
                    ],
                    'unit_amount' => $amountInKobo,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'customer_email' => $user->email,
            'success_url' => route('donate.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('donate.cancel'),
            'metadata' => [
                'user_id' => $user->id,
                'project_id' => $project->id,
                'amount' => $request->amount, // Store the original amount
            ]
        ]);

        // 3. Redirect user to Stripe's hosted checkout page
        return redirect($session->url);
    }

    /**
     * Handle the successful payment redirect from Stripe.
     * This is where we create the donation record and update the project.
     */
    public function paymentSuccess(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $sessionId = $request->query('session_id');

        try {
            $session = Session::retrieve($sessionId);

            // Get metadata we stored
            $metadata = $session->metadata;
            $amount = (float) $metadata->amount;
            $userId = (int) $metadata->user_id;
            $projectId = (int) $metadata->project_id;

            // 1. Create the Donation Record
            Donation::create([
                'user_id' => $userId,
                'project_id' => $projectId,
                'amount' => $amount,
                'status' => 'successful',
                'payment_gateway' => 'stripe',
                'reference' => $session->id,
            ]);

            // 2. Update the Project's Current Amount
            $project = Project::find($projectId);
            $project->increment('current_amount', $amount); // Atomically increments the amount

            return redirect()->route('home')->with('success', 'Thank you for your donation!');

        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Payment processing failed. ' . $e->getMessage());
        }
    }

    /**
     * Handle the cancelled payment redirect from Stripe.
     */
    public function paymentCancel()
    {
        return redirect()->route('home')->with('error', 'Your payment was cancelled.');
    }
}