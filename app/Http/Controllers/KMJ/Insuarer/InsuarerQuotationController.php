<?php

namespace App\Http\Controllers\KMJ\Insuarer;

use App\Http\Controllers\Controller;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Models\KMJ\Quotation;

class InsuarerQuotationController extends Controller
{
    protected WhatsAppService $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    public function index(Request $request)
{
    $insuarerId = Auth::guard('insuarer')->id();
    $search = $request->input('search');

    // Base query (important to reuse)
    $baseQuery = Quotation::where('insuarer_id', $insuarerId);

    // STATISTICS
    $totalQuotations = (clone $baseQuery)->count();

    $pendingQuotations = (clone $baseQuery)
        ->where('status', 'pending')
        ->count();

    $approvedQuotations = (clone $baseQuery)
        ->where('status', 'approved')
        ->count();

    $cancelledQuotations = (clone $baseQuery)
        ->where('status', 'cancelled')
        ->count();

    // LISTING (with search)
    $quotations = $baseQuery
        ->when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('coverage', function ($coverageQuery) use ($search) {
                        $coverageQuery->where('risk_name', 'like', "%{$search}%");
                    });
            });
        })
        ->latest()
        ->paginate(15);

    return view('insuarer.quotation.index', compact(
        'quotations',
        'search',
        'totalQuotations',
        'pendingQuotations',
        'approvedQuotations',
        'cancelledQuotations'
    ));
}

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:approved,cancelled',
                'rejection_reason' => 'required_if:status,cancelled|string|max:500',
            ]);

            $insuarerId = Auth::guard('insuarer')->id();

            $quotation = Quotation::where('id', $id)
                                  ->where('insuarer_id', $insuarerId)
                                  ->firstOrFail();

            $oldStatus = $quotation->status;
            $newStatus = $request->status;

            // Update quotation status
            $quotation->status = $newStatus;

            if ($newStatus === 'approved') {
                $quotation->approved_by = $insuarerId;
                $quotation->approved_at = now();
            } elseif ($newStatus === 'cancelled') {
                $quotation->rejection_reason = $request->rejection_reason;
                $quotation->rejected_by = $insuarerId;
                $quotation->rejected_at = now();
            }

            $quotation->save();

            // Send WhatsApp notifications
            if ($newStatus === 'approved' && $oldStatus !== 'approved') {
                $this->sendApprovalNotifications($quotation);
            } elseif ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                $this->sendRejectionNotifications($quotation);
            }

            $message = $newStatus === 'approved'
                ? 'Quotation approved successfully! Customer and broker have been notified.'
                : 'Quotation cancelled. Customer and broker have been notified.';

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Error updating quotation status: ' . $e->getMessage());

            return redirect()->back()->with('error', 'An error occurred while updating the quotation status.');
        }
    }

    public function show($id)
    {
        $insuarerId = Auth::guard('insuarer')->id();

        $quotation = Quotation::where('id', $id)
                              ->where('insuarer_id', $insuarerId)
                              ->firstOrFail();

        return view('insuarer.quotation.show', compact('quotation'));
    }

    /**
     * Send WhatsApp notifications when quotation is approved
     */
    private function sendApprovalNotifications($quotation)
    {
        try {
            // Notify Customer
            $this->sendApprovalMessageToCustomer($quotation);

            // Notify Broker
            $this->sendApprovalMessageToBroker($quotation);

        } catch (\Exception $e) {
            Log::error('Error sending approval notifications: ' . $e->getMessage());
        }
    }

    /**
     * Send WhatsApp notifications when quotation is rejected/cancelled
     */
    private function sendRejectionNotifications($quotation)
    {
        try {
            // Notify Customer
            $this->sendRejectionMessageToCustomer($quotation);

            // Notify Broker
            $this->sendRejectionMessageToBroker($quotation);

        } catch (\Exception $e) {
            Log::error('Error sending rejection notifications: ' . $e->getMessage());
        }
    }

    /**
     * Send WhatsApp message to customer when quotation is approved
     */
    private function sendApprovalMessageToCustomer($quotation)
    {
        try {
            $customer = $quotation->customer;

            if (!$customer || !$customer->phone) {
                Log::warning('Customer ' . ($customer->id ?? 'unknown') . ' does not have a phone number');
                return;
            }

            $phoneNumber = preg_replace('/[^0-9]/', '', $customer->phone);

            $message = "Hello " . $customer->name . ",\n\n";
            $message .= "Great news! Your quotation has been approved.\n\n";
            $message .= "Quotation Summary:\n";
            $message .= "- Insurance Type: " . ($quotation->coverage->product->name ?? 'N/A') . "\n";
            $message .= "- Premium Amount: " . number_format($quotation->total_premium_including_tax, 2) . " " . ($quotation->currency->code ?? 'TZS') . "\n";
            $message .= "- Coverage Start: " . \Carbon\Carbon::parse($quotation->cover_note_start_date)->format('d-m-Y') . "\n";
            $message .= "- Coverage End: " . \Carbon\Carbon::parse($quotation->cover_note_end_date)->format('d-m-Y') . "\n\n";
            $message .= "Please proceed with payment to finalize your policy.\n";
            $message .= "Contact us for any questions.\n\n";
            $message .= "Quotation Reference: #" . $quotation->id;

            $result = $this->whatsAppService->sendTextMessage($phoneNumber, $message);

            if ($result['success']) {
                Log::info('WhatsApp approval message sent to customer ' . $customer->id);
            } else {
                Log::error('Failed to send WhatsApp approval message: ' . json_encode($result));
            }
        } catch (\Exception $e) {
            Log::error('Error sending WhatsApp approval message to customer: ' . $e->getMessage());
        }
    }

    /**
     * Send WhatsApp message to broker when quotation is approved
     */
    private function sendApprovalMessageToBroker($quotation)
    {
        try {
            $broker = $quotation->createdBy; // assuming this relationship exists

            if (!$broker || !$broker->phone) {
                Log::warning('Broker for quotation ' . $quotation->id . ' does not have a phone number');
                return;
            }

            $phoneNumber = preg_replace('/[^0-9]/', '', $broker->phone);

            $message = "Hello " . $broker->name . ",\n\n";
            $message .= "Good news! Quotation #" . $quotation->id . " has been APPROVED by the insurer.\n\n";
            $message .= "Customer: " . ($quotation->customer->name ?? 'N/A') . "\n";
            $message .= "Premium Amount: " . number_format($quotation->total_premium_including_tax, 2) . " " . ($quotation->currency->code ?? 'TZS') . "\n\n";
            $message .= "You can now proceed to the next step (TIRA submission).\n\n";
            $message .= "Quotation Reference: #" . $quotation->id;

            $result = $this->whatsAppService->sendTextMessage($phoneNumber, $message);

            if ($result['success']) {
                Log::info('WhatsApp approval message sent to broker for quotation ' . $quotation->id);
            } else {
                Log::error('Failed to send WhatsApp approval message to broker: ' . json_encode($result));
            }
        } catch (\Exception $e) {
            Log::error('Error sending WhatsApp approval message to broker: ' . $e->getMessage());
        }
    }

    /**
     * Send WhatsApp message to customer when quotation is rejected
     */
    private function sendRejectionMessageToCustomer($quotation)
    {
        try {
            $customer = $quotation->customer;

            if (!$customer || !$customer->phone) {
                Log::warning('Customer ' . ($customer->id ?? 'unknown') . ' does not have a phone number');
                return;
            }

            $phoneNumber = preg_replace('/[^0-9]/', '', $customer->phone);

            $message = "Hello " . $customer->name . ",\n\n";
            $message .= "We regret to inform you that your quotation could not be approved at this time.\n\n";
            $message .= "Reason: " . ($quotation->rejection_reason ?? 'No reason provided') . "\n\n";
            $message .= "Please contact us for more information or to discuss alternative options.\n\n";
            $message .= "Thank you for your understanding.\n\n";
            $message .= "Quotation Reference: #" . $quotation->id;

            $result = $this->whatsAppService->sendTextMessage($phoneNumber, $message);

            if ($result['success']) {
                Log::info('WhatsApp rejection message sent to customer ' . $customer->id);
            } else {
                Log::error('Failed to send WhatsApp rejection message: ' . json_encode($result));
            }
        } catch (\Exception $e) {
            Log::error('Error sending WhatsApp rejection message to customer: ' . $e->getMessage());
        }
    }

    /**
     * Send WhatsApp message to broker when quotation is rejected
     */
    private function sendRejectionMessageToBroker($quotation)
    {
        try {
            $broker = $quotation->createdBy; // assuming this relationship exists

            if (!$broker || !$broker->phone) {
                Log::warning('Broker for quotation ' . $quotation->id . ' does not have a phone number');
                return;
            }

            $phoneNumber = preg_replace('/[^0-9]/', '', $broker->phone);

            $message = "Hello " . $broker->name . ",\n\n";
            $message .= "Unfortunately, quotation #" . $quotation->id . " has been REJECTED by the insurer.\n\n";
            $message .= "Customer: " . ($quotation->customer->name ?? 'N/A') . "\n";
            $message .= "Reason: " . ($quotation->rejection_reason ?? 'No reason provided') . "\n\n";
            $message .= "Please review the feedback and consider resubmitting with adjustments if applicable.\n\n";
            $message .= "Quotation Reference: #" . $quotation->id;

            $result = $this->whatsAppService->sendTextMessage($phoneNumber, $message);

            if ($result['success']) {
                Log::info('WhatsApp rejection message sent to broker for quotation ' . $quotation->id);
            } else {
                Log::error('Failed to send WhatsApp rejection message to broker: ' . json_encode($result));
            }
        } catch (\Exception $e) {
            Log::error('Error sending WhatsApp rejection message to broker: ' . $e->getMessage());
        }
    }

    //Hii ni method ya kuset ela ambayo itakua autoapproved bila kupita kwa insuarer
    public function editAgreement()
    {
        $insurerId = Auth::guard('insuarer')->id();

        $insurer = \App\Models\Models\KMJ\Insuarer::find($insurerId);

        return view('insuarer.agreements.edit', compact('insurer'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'auto_approval_limit' => 'nullable|numeric|min:0',
        ]);

        $insurerId = Auth::guard('insuarer')->id();

        $insurer = \App\Models\Models\KMJ\Insuarer::find($insurerId);

        $insurer->update([
            'auto_approval_limit' => $request->auto_approval_limit,
        ]);

        return redirect()
            ->route('insuarer.agreements.show')
            ->with('success', 'Auto approval limit updated successfully');
    }
}
