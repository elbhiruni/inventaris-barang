<div>
    <flux:modal wire:model="modal['show']" class="md:w-96" @close="closeModal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $this->title }}</flux:heading>

                @if ($modal['action'] === 'delete')
                    <flux:text class="mt-2">
                        You're about to delete this category.<br>
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
                    <flux:label badge="Optional">Description</flux:label>
                    <flux:textarea wire:model="description" maxlength="255" />
                    <flux:error name="description" />
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
