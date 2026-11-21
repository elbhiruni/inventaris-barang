<div>
    <flux:modal wire:model="modal['show']" class="md:w-xl" @close="closeModal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $this->title }}</flux:heading>
            </div>

            @if ($modal['action'] === 'store')
                <div class="space-y-4">
                    <div class="flex gap-2">
                        <flux:field class="flex-1">
                            <flux:label badge="Required">Product</flux:label>
                            <livewire:product.select wire:model="product_id" />
                            <flux:error name="product_id" />
                        </flux:field>

                        <flux:field class="flex-1">
                            <flux:label badge="Required">Quantity</flux:label>
                            <flux:input wire:model="quantity" type="number" min="0" max="1000" required />
                            <flux:error name="quantity" />
                        </flux:field>
                    </div>

                    <div class="flex justify-end">
                        <flux:button type="button" variant="primary" wire:click="addProduct">
                            Add Product
                        </flux:button>
                    </div>
                </div>
            @endif

            @if ($modal['action'] === 'store' || $modal['action'] === 'details')
                <flux:card>
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column align="center">#</flux:table.column>
                            <flux:table.column>Product</flux:table.column>
                            <flux:table.column align="center">Quantity</flux:table.column>
                            <flux:table.column align="right">Subtotal</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @foreach ($this->items as $item)
                                <flux:table.row :key="$loop->index">
                                    <flux:table.cell align="center">{{ $loop->index + 1 }}</flux:table.cell>
                                    <flux:table.cell>{{ $item['product_name'] }}</flux:table.cell>
                                    <flux:table.cell align="center">{{ $item['quantity'] }}</flux:table.cell>
                                    <flux:table.cell align="right">
                                        Rp {{ Number::format($item['subtotal'], locale: 'id') }}
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforeach

                            <flux:table.row>
                                <flux:table.cell colspan="3">Total</flux:table.cell>
                                <flux:table.cell align="right">
                                    Rp {{ Number::format($this->totalAmount, locale: 'id') }}
                                </flux:table.cell>
                            </flux:table.row>
                        </flux:table.rows>
                    </flux:table>
                </flux:card>
            @endif

            @if ($modal['action'] === 'store')
                <flux:field>
                    <flux:label badge="Optional">Customer Name</flux:label>
                    <flux:input wire:model="customer_name" type="text" maxlength="255" />
                    <flux:error name="customer_name" />
                </flux:field>
            @endif

            <div class="flex">
                <flux:spacer />

                @if ($modal['action'] === 'store')
                    <flux:button type="submit" variant="primary" wire:click="{{ $modal['action'] }}">
                        {{ $this->labelSubmit }}</flux:button>
                @endif
            </div>
        </div>
    </flux:modal>
</div>
