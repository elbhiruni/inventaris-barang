<?php

use App\Models\Brand;
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
     *     brand_id: int|null,
     *     show: bool
     * }
     */
    #[Modelable]
    public array $modal = [
        'action' => '',
        'brand_id' => null,
        'show' => false,
    ];

    public string $name = '';

    public ?string $description = null;

    public function boot(): void
    {
        if ($this->modal['action'] === 'update') {
            $brand = Brand::findOrFail($this->modal['brand_id']);
            $this->fill($brand->only('name', 'description'));
        }
    }

    #[Computed]
    public function title(): string
    {
        if ($this->modal['action'] === 'store') {
            return 'Add Brand';
        }

        if ($this->modal['action'] === 'update') {
            return 'Edit Brand';
        }

        if ($this->modal['action'] === 'delete') {
            return 'Delete Brand';
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
            'name' => ['required', 'string', 'max:255', Rule::unique('brands', 'name')->ignore($this->modal['brand_id'])],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function store(): void
    {
        $validated = $this->validate();
        Brand::create($validated);

        Flux::toast(variant: 'success', heading: __('Success'), text: __('Brand created'));

        $this->modal['show'] = false;
        $this->dispatch('brand-refresh');
    }

    public function update(): void
    {
        $model = Brand::findOrFail($this->modal['brand_id']);
        $this->authorize('update', $model);

        $validated = $this->validate();
        $model->update($validated);

        Flux::toast(variant: 'success', heading: __('Success'), text: __('Brand updated'));

        $this->modal['show'] = false;
        $this->dispatch('brand-refresh');
    }

    public function delete(): void
    {
        $model = Brand::findOrFail($this->modal['brand_id']);
        $this->authorize('delete', $model);

        $model->delete();

        Flux::toast(variant: 'success', heading: __('Success'), text: __('Brand deleted'));

        $this->modal['show'] = false;
        $this->dispatch('brand-refresh');
    }

    public function closeModal(): void
    {
        $this->reset(['modal', 'name', 'description']);
        $this->resetValidation();
    }
};
