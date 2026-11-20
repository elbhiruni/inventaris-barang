<?php

use Illuminate\Support\Number;
use Livewire\Component;

// @phpstan-ignore expr.resultUnused
new class extends Component {};
?>

<flux:card>
    <flux:card.header>
        <flux:card.heading size="lg">Product</flux:card.heading>
        <flux:card.actions>
            @can('create', App\Models\Product::class)
                <flux:button size="sm" icon="plus" wire:click="showModal('store')">Add</flux:button>
            @endcan
        </flux:card.actions>
    </flux:card.header>

    <flux:card.body>
        <flux:table :paginate="$this->products">
            <flux:table.columns>
                <flux:table.column align="center">#</flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection"
                    wire:click="sort('name')">
                    Name
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'brand'" :direction="$sortDirection"
                    wire:click="sort('brand')">
                    Brand
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'category'" :direction="$sortDirection"
                    wire:click="sort('category')">
                    Category
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'price'" :direction="$sortDirection"
                    wire:click="sort('price')">
                    Price
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'stock_quantity'" :direction="$sortDirection"
                    wire:click="sort('stock_quantity')">
                    Stock
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'created_by'" :direction="$sortDirection"
                    wire:click="sort('created_by')">
                    Created By
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'updated_by'" :direction="$sortDirection"
                    wire:click="sort('updated_by')">
                    Updated By
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection"
                    wire:click="sort('created_at')">
                    Created At
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'updated_at'" :direction="$sortDirection"
                    wire:click="sort('updated_at')">
                    Updated At
                </flux:table.column>
                <flux:table.column align="center">Action</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @if ($this->products->count() === 0)
                    <flux:table.row>
                        <flux:table.cell colspan="10" align="center">No Results</flux:table.cell>
                    </flux:table.row>
                @else
                    @foreach ($this->products as $data)
                        @can('viewAny', App\Models\Product::class)
                            <flux:table.row :key="$data->id">
                                <flux:table.cell align="center">
                                    {{ $loop->index + $this->products->firstItem() }}
                                </flux:table.cell>
                                <flux:table.cell>{{ $data->name }}</flux:table.cell>
                                <flux:table.cell>{{ $data->brand?->name }}</flux:table.cell>
                                <flux:table.cell>{{ $data->category?->name }}</flux:table.cell>
                                <flux:table.cell>Rp {{ Number::format($data->price, locale: 'id') }}</flux:table.cell>
                                <flux:table.cell>{{ $data->stock_quantity }}</flux:table.cell>
                                <flux:table.cell>{{ $data->createdBy?->name }}</flux:table.cell>
                                <flux:table.cell>{{ $data->updatedBy?->name }}</flux:table.cell>
                                <flux:table.cell>{{ $data->created_at }}</flux:table.cell>
                                <flux:table.cell>{{ $data->updated_at }}</flux:table.cell>
                                <flux:table.cell class="py-0" align="center">
                                    @can('update', $data)
                                        <flux:tooltip content="Add Stock Quantity">
                                            <flux:button variant="ghost" color="green" size="sm" icon="plus-circle"
                                                wire:click="showModal('addQuantity', {{ $data->id }})" />
                                        </flux:tooltip>
                                    @endcan

                                    @can('update', $data)
                                        <flux:tooltip content="Edit">
                                            <flux:button variant="ghost" color="blue" size="sm" icon="pencil-square"
                                                wire:click="showModal('update', {{ $data->id }})" />
                                        </flux:tooltip>
                                    @endcan

                                    @can('delete', $data)
                                        <flux:tooltip content="Delete">
                                            <flux:button variant="ghost" color="red" size="sm" icon="trash"
                                                wire:click="showModal('delete', {{ $data->id }})" />
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

    <livewire:product.modal wire:model="modal" />
</flux:card>
