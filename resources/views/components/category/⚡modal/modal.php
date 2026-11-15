<?php

use App\Models\Category;
use Flux\Flux;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Modelable;
use Livewire\Component;

// @phpstan-ignore expr.resultUnused
new class extends Component
{
    /**
     * @var array{
     *     action: string,
     *     category_id: int|null,
     *     show: bool
     * }
     */
    #[Modelable]
    public array $modal = [
        'action' => '',
        'category_id' => null,
        'show' => false,
    ];

    public string $name = '';

    public ?string $description = null;

    public function boot(): void
    {
        if ($this->modal['action'] === 'update') {
            $category = Category::findOrFail($this->modal['category_id']);
            $this->fill($category->only('name', 'description'));
        }
    }

    #[Computed]
    public function title(): string
    {
        if ($this->modal['action'] === 'store') {
            return 'Add Category';
        }

        if ($this->modal['action'] === 'update') {
            return 'Edit Category';
        }

        if ($this->modal['action'] === 'delete') {
            return 'Delete Category';
        }

        return '';
    }

    #[Computed]
    public function labelSubmit(): string
    {
        if ($this->modal['action'] === 'store') {
            return 'Save';
        }

        if ($this->modal['action'] === 'update') {
            return 'Update';
        }

        if ($this->modal['action'] === 'delete') {
            return 'Delete';
        }

        return '';
    }

    /**
     * @return array<string, array<string|ValidationRule>>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($this->modal['category_id'])],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function store(): void
    {
        $validated = $this->validate();
        Category::create($validated);

        Flux::toast(variant: 'success', heading: __('Success'), text: __('Category created'));

        $this->modal['show'] = false;
        $this->dispatch('category-refresh');
    }

    public function update(): void
    {
        $model = Category::findOrFail($this->modal['category_id']);
        $this->authorize('update', $model);

        $validated = $this->validate();
        $model->update($validated);

        Flux::toast(variant: 'success', heading: __('Success'), text: __('Category updated'));

        $this->modal['show'] = false;
        $this->dispatch('category-refresh');
    }

    public function delete(): void
    {
        $model = Category::findOrFail($this->modal['category_id']);
        $this->authorize('delete', $model);

        $model->delete();

        Flux::toast(variant: 'success', heading: __('Success'), text: __('Category deleted'));

        $this->modal['show'] = false;
        $this->dispatch('category-refresh');
    }

    public function closeModal(): void
    {
        $this->reset(['modal', 'name', 'description']);
        $this->resetValidation();
    }
};
