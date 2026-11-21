<?php

use App\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

// @phpstan-ignore expr.resultUnused
new class extends Component
{
    use WithoutUrlPagination, WithPagination;

    public string $sortBy = 'transaction_date';

    /** @var 'asc'|'desc' */
    public string $sortDirection = 'desc';

    /**
     * @var array{
     *     action: string,
     *     transaction_id: int|null,
     *     show: bool
     * }
     */
    public array $modal = [
        'action' => '',
        'transaction_id' => null,
        'show' => false,
    ];

    /** @return LengthAwarePaginator<int, Transaction> */
    #[Computed]
    public function items(): LengthAwarePaginator
    {
        return Transaction::with(['createdBy:id,name'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(5);
    }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[On('transaction-refresh')]
    public function refreshTable(): void {}

    public function showModal(string $action = '', ?int $transaction_id = null): void
    {
        $this->modal = [
            'action' => $action,
            'transaction_id' => $transaction_id,
            'show' => true,
        ];
    }
};
