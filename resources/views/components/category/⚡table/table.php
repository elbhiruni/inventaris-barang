<?php

use App\Models\Category;
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

    public string $sortBy = 'name';

    /** @var 'asc'|'desc' */
    public string $sortDirection = 'asc';

    /**
     * @var array{
     *     action: string,
     *     category_id: int|null,
     *     show: bool
     * }
     */
    public array $modal = [
        'action' => '',
        'category_id' => null,
        'show' => false,
    ];

    /** @return LengthAwarePaginator<int, Category> */
    #[Computed]
    public function categories(): LengthAwarePaginator
    {
        return Category::with(['createdBy:id,name', 'updatedBy:id,name'])
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

    #[On('category-refresh')]
    public function refreshTable(): void {}

    public function showModal(string $action = '', ?int $category_id = null): void
    {
        $this->modal = [
            'action' => $action,
            'category_id' => $category_id,
            'show' => true,
        ];
    }
};
