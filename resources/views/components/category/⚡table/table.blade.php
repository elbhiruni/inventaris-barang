<flux:card>
    <flux:card.header>
        <flux:card.heading size="lg">Category</flux:card.heading>
        <flux:card.actions>
            @can('create', App\Models\Category::class)
                <flux:button size="sm" icon="plus" wire:click="showModal('store')">Add</flux:button>
            @endcan
        </flux:card.actions>
    </flux:card.header>

    <flux:card.body>
        <flux:table :paginate="$this->categories">
            <flux:table.columns>
                <flux:table.column align="center">#</flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection"
                    wire:click="sort('name')">
                    Name
                </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'description'" :direction="$sortDirection"
                    wire:click="sort('description')">
                    Description
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
                @if ($this->categories->count() === 0)
                    <flux:table.row>
                        <flux:table.cell colspan="8" align="center">No Results</flux:table.cell>
                    </flux:table.row>
                @else
                    @foreach ($this->categories as $category)
                        @can('viewAny', App\Models\Category::class)
                            <flux:table.row :key="$category->id">
                                <flux:table.cell align="center">
                                    {{ $loop->index + $this->categories->firstItem() }}
                                </flux:table.cell>
                                <flux:table.cell>{{ $category->name }}</flux:table.cell>
                                <flux:table.cell>{{ $category->description }}</flux:table.cell>
                                <flux:table.cell>{{ $category->createdBy?->name }}</flux:table.cell>
                                <flux:table.cell>{{ $category->updatedBy?->name }}</flux:table.cell>
                                <flux:table.cell>{{ $category->created_at }}</flux:table.cell>
                                <flux:table.cell>{{ $category->updated_at }}</flux:table.cell>
                                <flux:table.cell class="py-0" align="center">
                                    @can('update', $category)
                                        <flux:tooltip content="Edit">
                                            <flux:button variant="ghost" color="blue" size="sm" icon="pencil-square"
                                                wire:click="showModal('update', {{ $category->id }})" />
                                        </flux:tooltip>
                                    @endcan

                                    @can('delete', $category)
                                        <flux:tooltip content="Delete">
                                            <flux:button variant="ghost" color="red" size="sm" icon="trash"
                                                wire:click="showModal('delete', {{ $category->id }})" />
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

    <livewire:category.modal wire:model="modal" />
</flux:card>
