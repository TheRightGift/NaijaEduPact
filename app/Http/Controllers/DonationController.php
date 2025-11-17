<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Project;
use App\Models\University;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class DonationController extends Controller
{
    public function startPayment(Request $request)
    {
        // --- 1. New Validation ---
        // We need to check if it's a project or university donation
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:' . config('services.rates.usd_to_ngn', 1450),
            'project_id' => 'nullable|exists:projects,id',
            'university_id' => 'nullable|exists:universities,id',
        ], [
            'amount.min' => 'Your donation must be at least ₦:min (approx. $1 USD).'
        ])->after(function ($validator) use ($request) {
            // Ensure EITHER project_id OR university_id is present, but not both.
            if (!$request->filled('project_id') && !$request->filled('university_id')) {
                $validator->errors()->add('target', 'A donation target (project or university) is required.');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // --- 2. Determine Donation Target & Metadata ---
        $user = Auth::user();
        $amountInKobo = $request->amount * 100;
        $metadata = [
            'user_id' => $user->id,
            'amount' => $request->amount,
        ];
        $productName = '';

        if ($request->filled('project_id')) {
            $project = Project::findOrFail($request->project_id);
            $productName = 'Donation for: ' . $project->title;
            $metadata['project_id'] = $project->id;
            $metadata['university_id'] = $project->university_id; // Get uni from project
        } else {
            $university = University::findOrFail($request->university_id);
            $productName = 'Donation for: ' . $university->name . ' General Fund';
            $metadata['project_id'] = null;
            $metadata['university_id'] = $university->id;
        }

        // 3. Set Stripe API Key
        Stripe::setApiKey(config('services.stripe.secret'));

        // 4. Create Stripe Session
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'ngn',
                    'product_data' => [
                        'name' => $productName, // <-- Dynamic product name
                    ],
                    'unit_amount' => $amountInKobo,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'customer_email' => $user->email,
            'success_url' => route('donate.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('donate.cancel'),
            'metadata' => $metadata // <-- Pass our new metadata
        ]);

        return redirect($session->url);
    }

    public function paymentSuccess(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $sessionId = $request->query('session_id');

        try {
            $session = Session::retrieve($sessionId);
            $metadata = $session->metadata;

            // --- 5. New Success Logic ---
            $amount = (float) $metadata->amount;
            $userId = (int) $metadata->user_id;
            $universityId = (int) $metadata->university_id;
            $projectId = $metadata->project_id ?? null; // Get project_id, or null

            // 1. Create the Donation Record
            Donation::create([
                'user_id' => $userId,
                'university_id' => $universityId, // <-- Always save university_id
                'project_id' => $projectId,       // <-- Save project_id (or null)
                'amount' => $amount,
                'status' => 'successful',
                'payment_gateway' => 'stripe',
                'reference' => $session->id,
            ]);

            // 2. Update the correct balance
            if ($projectId) {
                // It's a Project donation
                $project = Project::find($projectId);
                $project->increment('current_amount', $amount);
            } else {
                // It's a General Fund donation
                $university = University::find($universityId);
                $university->increment('general_fund_balance', $amount);
            }

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