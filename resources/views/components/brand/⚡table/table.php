<?php

use App\Models\Brand;
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
     *     brand_id: int|null,
     *     show: bool
     * }
     */
    public array $modal = [
        'action' => '',
        'brand_id' => null,
        'show' => false,
    ];

    /** @return LengthAwarePaginator<int, Brand> */
    #[Computed]
    public function brands(): LengthAwarePaginator
    {
        return Brand::with(['createdBy:id,name', 'updatedBy:id,name'])
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

    #[On('brand-refresh')]
    public function refreshTable(): void {}

    public function showModal(string $action = '', ?int $brand_id = null): void
    {
        $this->modal = [
            'action' => $action,
            'brand_id' => $brand_id,
            'show' => true,
        ];
    }
};
