<?php

namespace App\Livewire\Admin;

use App\Models\PaymentTransaction;
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
