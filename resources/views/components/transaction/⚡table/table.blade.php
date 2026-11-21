<flux:card>
    <flux:card.header>
        <flux:card.heading size="lg">Transaction</flux:card.heading>
        <flux:card.actions>
            @can('create', App\Models\Transaction::class)
                <flux:button size="sm" icon="plus" wire:click="showModal('store')">Add</flux:button>
            @endcan
        </flux:card.actions>
    </flux:card.header>

    <flux:card.body>
        <flux:table :paginate="$this->items">
            <flux:table.columns>
                <flux:table.column align="center">#</flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'customer_name'" :direction="$sortDirection"
                    wire:click="sort('customer_name')">
                    Customer Name
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'total_amount'" :direction="$sortDirection"
                    wire:click="sort('total_amount')">
                    Total Amount
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'transaction_date'" :direction="$sortDirection"
                    wire:click="sort('transaction_date')">
                    Transaction Date
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'created_by'" :direction="$sortDirection"
                    wire:click="sort('created_by')">
                    Created By
                </flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @if ($this->items->count() === 0)
                    <flux:table.row>
                        <flux:table.cell colspan="6" align="center">No Results</flux:table.cell>
                    </flux:table.row>
                @else
                    @foreach ($this->items as $item)
                        @can('viewAny', App\Models\Transaction::class)
                            <flux:table.row :key="$item->id">
                                <flux:table.cell align="center">
                                    {{ $loop->index + $this->items->firstItem() }}
                                </flux:table.cell>
                                <flux:table.cell>{{ $item->customer_name }}</flux:table.cell>
                                <flux:table.cell>Rp {{ Number::format($item->total_amount, locale: 'id') }}</flux:table.cell>
                                <flux:table.cell>{{ $item->transaction_date }}</flux:table.cell>
                                <flux:table.cell>{{ $item->createdBy?->name }}</flux:table.cell>
                                <flux:table.cell>
                                    @can('update', $item)
                                        <flux:tooltip content="Detail">
                                            <flux:button variant="ghost" color="blue" size="sm" icon="eye"
                                                wire:click="showModal('details', {{ $item->id }})" />
                                        </flux:tooltip>
                                    @endcan
                                </flux:table.cell>
                            </flux:table.row>
                        @endcan
                    @endforeach
                @endif
            </flux:table.rows>
        </flux:table>
    </flux:card.body>

    <livewire:transaction.modal wire:model="modal" />
</flux:card>
