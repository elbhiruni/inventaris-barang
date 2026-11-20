<div>
    <flux:modal wire:model="modal['show']" class="md:w-96" @close="closeModal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $this->title }}</flux:heading>

                @if ($modal['action'] === 'delete')
                    <flux:text class="mt-2">
                        You're about to delete this product.<br>
                        This action cannot be reversed.
                    </flux:text>
                @endif
            </div>

            @if ($modal['action'] === 'store' || $modal['action'] === 'update')
                <flux:field>
                    <flux:label badge="Required">Name</flux:label>
                    <flux:input wire:model="name" type="text" maxlength="255" required />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label badge="Required">Brand</flux:label>
                    <livewire:brand.select wire:model="brand_id" />
                    <flux:error name="brand_id" />
                </flux:field>

                <flux:field>
                    <flux:label badge="Required">Category</flux:label>
                    <livewire:category.select wire:model="category_id" />
                    <flux:error name="category_id" />
                </flux:field>

                <flux:field>
                    <flux:label badge="Required">Price</flux:label>
                    <flux:input.group>
                        <flux:input.group.prefix>Rp</flux:input.group.prefix>
                        <flux:input wire:model="price" type="number" min="1" max="9999999999"
                            placeholder="10000" required />
                    </flux:input.group>
                    <flux:error name="price" />
                </flux:field>
            @elseif ($modal['action'] === 'addQuantity')
                <flux:field>
                    <flux:label badge="Required">Stock Quantity</flux:label>
                    <flux:input wire:model="stock_quantity" type="number" min="1" max="9999"
                        placeholder="100" required />
                    <flux:error name="stock_quantity" />
                </flux:field>
            @endif

            <div class="flex">
                <flux:spacer />

                @if ($modal['action'] === 'delete')
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                @endif

                @if ($modal['action'])
                    <flux:button type="submit" variant="{{ $modal['action'] === 'delete' ? 'danger' : 'primary' }}"
                        wire:click="{{ $modal['action'] }}">{{ $this->labelSubmit }}</flux:button>
                @endif
            </div>
        </div>
    </flux:modal>
</div>
