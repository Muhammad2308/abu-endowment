<?php

namespace App\Livewire\Admin;

use App\Models\Donation;
use App\Models\PaymentTransaction;
use App\Services\SquadService;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentTransactions extends Component
{
    use WithPagination;

    public $search = '';
    public $gateway = '';
    public $status = '';
    public $category = '';
    public $perPage = 15;
    public $selectedTransaction;
    public $showDetailsModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'gateway' => ['except' => ''],
        'status' => ['except' => ''],
        'category' => ['except' => ''],
        'perPage' => ['except' => 15],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingGateway()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->verifyPendingSquadTransactions();
    }

    public function verifyPendingSquadTransactions()
    {
        $squadService = new SquadService();

        $pendingTransactions = PaymentTransaction::where('payment_gateway', 'squad')
            ->where('status', 'pending')
            ->where('event_type', 'payment.initialized')
            ->get();

        foreach ($pendingTransactions as $transaction) {
            $reference = $transaction->gateway_reference ?: $transaction->payment_reference;
            if (!$reference) {
                continue;
            }

            $result = $squadService->verifyTransaction($reference);
            if (!$result['success'] || empty($result['data'])) {
                continue;
            }

            $data = $result['data'];
            $status = strtolower($data['transaction_status'] ?? $data['status'] ?? 'unknown');
            $verifyRef = $data['transaction_ref'] ?? $reference;

            $donation = Donation::find($transaction->donation_id);
            if (!$donation) {
                continue;
            }

            if ($status === 'success') {
                if ($donation->status !== 'completed') {
                    $donation->update([
                        'status' => 'completed',
                        'verified_at' => now(),
                        'paid_at' => $data['transaction_date'] ?? now(),
                    ]);
                }

                $transaction->update([
                    'status' => 'completed',
                    'gateway_status' => 'success',
                    'gateway_reference' => $verifyRef,
                    'response_payload' => json_encode($result['raw'] ?? $data),
                ]);

                if (!PaymentTransaction::where('payment_reference', $transaction->payment_reference)
                    ->where('event_type', 'charge.success')
                    ->exists()) {
                    PaymentTransaction::create([
                        'donation_id' => $donation->id,
                        'donor_id' => $donation->donor_id,
                        'project_id' => $donation->project_id,
                        'payment_gateway' => 'squad',
                        'category' => $donation->project_id ? 'project' : 'general',
                        'event_type' => 'charge.success',
                        'payment_reference' => $transaction->payment_reference,
                        'gateway_reference' => $verifyRef,
                        'amount' => $data['amount'] ?? $donation->amount,
                        'currency' => 'NGN',
                        'status' => 'completed',
                        'gateway_status' => $status,
                        'channel' => $data['payment_method'] ?? null,
                        'fee' => $data['fee'] ?? 0,
                        'response_payload' => json_encode($result['raw'] ?? $data),
                    ]);
                }
            }

            if (in_array($status, ['failed', 'declined'])) {
                if ($donation->status !== 'failed') {
                    $donation->update(['status' => 'failed']);
                }

                $transaction->update([
                    'status' => 'failed',
                    'gateway_status' => $status,
                    'gateway_reference' => $verifyRef,
                    'response_payload' => json_encode($result['raw'] ?? $data),
                ]);

                if (!PaymentTransaction::where('payment_reference', $transaction->payment_reference)
                    ->where('event_type', 'charge.failed')
                    ->exists()) {
                    PaymentTransaction::create([
                        'donation_id' => $donation->id,
                        'donor_id' => $donation->donor_id,
                        'project_id' => $donation->project_id,
                        'payment_gateway' => 'squad',
                        'category' => $donation->project_id ? 'project' : 'general',
                        'event_type' => 'charge.failed',
                        'payment_reference' => $transaction->payment_reference,
                        'gateway_reference' => $verifyRef,
                        'amount' => $data['amount'] ?? $donation->amount,
                        'currency' => 'NGN',
                        'status' => 'failed',
                        'gateway_status' => $status,
                        'channel' => $data['payment_method'] ?? null,
                        'fee' => $data['fee'] ?? 0,
                        'response_payload' => json_encode($result['raw'] ?? $data),
                    ]);
                }
            }
        }
    }

    public function viewTransaction($id)
    {
        $this->selectedTransaction = PaymentTransaction::with(['donation.project', 'donor', 'project'])->find($id);
        $this->showDetailsModal = true;
    }

    public function closeModal()
    {
        $this->showDetailsModal = false;
        $this->selectedTransaction = null;
    }

    public function render()
    {
        $transactions = PaymentTransaction::with(['donation.project', 'donor', 'project'])
            ->when($this->gateway, function ($query) {
                $query->where('payment_gateway', $this->gateway);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->category, function ($query) {
                $query->where('category', $this->category);
            })
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';
                $query->where(function ($sub) use ($search) {
                    $sub->where('payment_reference', 'like', $search)
                        ->orWhere('gateway_reference', 'like', $search)
                        ->orWhere('payment_gateway', 'like', $search)
                        ->orWhere('event_type', 'like', $search)
                        ->orWhere('status', 'like', $search)
                        ->orWhereHas('donor', function ($donorQuery) use ($search) {
                            $donorQuery->where('name', 'like', $search)
                                ->orWhere('surname', 'like', $search)
                                ->orWhere('other_name', 'like', $search)
                                ->orWhere('email', 'like', $search);
                        })
                        ->orWhereHas('project', function ($projectQuery) use ($search) {
                            $projectQuery->where('project_title', 'like', $search);
                        });
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.payments.transactions', [
            'transactions' => $transactions,
        ]);
    }
}
