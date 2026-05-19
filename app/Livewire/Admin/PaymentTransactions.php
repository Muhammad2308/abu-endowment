<?php

namespace App\Livewire\Admin;

use App\Models\Donation;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
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

    public function mount()
    {
        // Only backfill if there are donations with no matching transaction record.
        // Uses a fast subquery count instead of loading all refs into PHP memory.
        $missing = Donation::whereNotNull('payment_reference')
            ->whereNotExists(function ($q) {
                $q->from('payment_transactions')
                  ->whereColumn('payment_transactions.payment_reference', 'donations.payment_reference');
            })
            ->count();

        if ($missing > 0) {
            $this->backfillDonationsToTransactions();
        }
    }

    public function backfillDonationsToTransactions(): void
    {
        Donation::with('donor', 'project')
            ->whereNotNull('payment_reference')
            ->whereNotExists(function ($q) {
                $q->from('payment_transactions')
                  ->whereColumn('payment_transactions.payment_reference', 'donations.payment_reference');
            })
            ->each(function ($donation) {
                $ref     = $donation->payment_reference;
                $gateway = str_contains($ref, '_SQUAD_') ? 'squad' : 'paystack';
                $status  = match($donation->status) {
                    'completed' => 'completed',
                    'failed'    => 'failed',
                    default     => 'pending',
                };
                $event = match($status) {
                    'completed' => 'charge.success',
                    'failed'    => 'charge.failed',
                    default     => 'payment.initialized',
                };

                PaymentTransaction::create([
                    'donation_id'       => $donation->id,
                    'donor_id'          => $donation->donor_id,
                    'project_id'        => $donation->project_id,
                    'payment_gateway'   => $gateway,
                    'category'          => $donation->project_id ? 'project' : 'general',
                    'event_type'        => $event,
                    'payment_reference' => $ref,
                    'gateway_reference' => $ref,
                    'amount'            => $donation->amount,
                    'currency'          => 'NGN',
                    'status'            => $status,
                    'gateway_status'    => $status,
                    'channel'           => null,
                    'fee'               => 0,
                    'response_payload'  => null,
                ]);
            });
    }

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

    public function getChartData(): array
    {
        $days   = 30;
        $start  = now()->subDays($days - 1)->startOfDay();
        $labels = [];
        for ($i = 0; $i < $days; $i++) {
            $labels[] = now()->subDays($days - 1 - $i)->format('M d');
        }

        $rows = PaymentTransaction::select(
                DB::raw("strftime('%Y-%m-%d', created_at) as day"),
                'payment_gateway',
                DB::raw('SUM(amount) as total')
            )
            ->where('created_at', '>=', $start)
            ->whereIn('status', ['completed', 'success'])
            ->groupBy('day', 'payment_gateway')
            ->get()
            ->groupBy('payment_gateway');

        $fill = function ($gateway) use ($labels, $rows, $days) {
            $map  = [];
            foreach ($rows[$gateway] ?? [] as $row) {
                $map[$row->day] = (float) $row->total;
            }
            $data = [];
            for ($i = 0; $i < $days; $i++) {
                $key    = now()->subDays($days - 1 - $i)->format('Y-m-d');
                $data[] = $map[$key] ?? 0;
            }
            return $data;
        };

        $totalPaystack = array_sum($fill('paystack'));
        $totalSquad    = array_sum($fill('squad'));
        $totalAll      = $totalPaystack + $totalSquad;

        return [
            'labels'   => $labels,
            'paystack' => $fill('paystack'),
            'squad'    => $fill('squad'),
            'totals'   => [
                'all'      => number_format($totalAll, 2),
                'paystack' => number_format($totalPaystack, 2),
                'squad'    => number_format($totalSquad, 2),
                'count'    => PaymentTransaction::whereIn('status', ['completed', 'success'])->count(),
            ],
        ];
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
            'chartData'    => $this->getChartData(),
        ]);
    }
}
