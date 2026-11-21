<?php

use App\Models\Product;
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
     *     product_id: int|null,
     *     show: bool
     * }
     */
    #[Modelable]
    public array $modal = [
        'action' => '',
        'product_id' => null,
        'show' => false,
    ];

    public string $name = '';

    public ?int $brand_id = null;

    public ?int $category_id = null;

    public int $price = 0;

    public int $stock_quantity = 0;

    public function boot(): void
    {
        if ($this->modal['action'] === 'update') {
            $model = Product::findOrFail($this->modal['product_id']);
            $this->fill($model->only('name', 'brand_id', 'category_id', 'price'));
        }
    }

    #[Computed]
    public function title(): string
    {
        if ($this->modal['action'] === 'store') {
            return 'Add Product';
        }

        if ($this->modal['action'] === 'addQuantity') {
            return 'Add Stock Quantity';
        }

        if ($this->modal['action'] === 'update') {
            return 'Edit Product';
        }

        if ($this->modal['action'] === 'delete') {
            return 'Delete Product';
        }

        return '';
    }

    #[Computed]
    public function labelSubmit(): string
    {
        if ($this->modal['action'] === 'store' || $this->modal['action'] === 'addQuantity') {
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($this->modal['product_id']),
            ],
            'brand_id' => ['required', 'integer', 'exists:App\Models\Brand,id'],
            'category_id' => ['required', 'integer', 'exists:App\Models\Category,id'],
            'price' => ['required', 'integer', 'min:0', 'max:9999999999'],
        ];
    }

    public function store(): void
    {
        $validated = $this->validate();
        Product::create($validated);

        Flux::toast(variant: 'success', heading: __('Success'), text: __('Product created'));

        $this->modal['show'] = false;
        $this->dispatch('product-refresh');
    }

    public function update(): void
    {
        $model = Product::findOrFail($this->modal['product_id']);
        $this->authorize('update', $model);

        $validated = $this->validate();
        $model->update($validated);

        Flux::toast(variant: 'success', heading: __('Success'), text: __('Product updated'));

        $this->modal['show'] = false;
        $this->dispatch('product-refresh');
    }

    public function addQuantity(): void
    {
        $model = Product::findOrFail($this->modal['product_id']);
        $this->authorize('update', $model);

        $validated = $this->validate([
            'stock_quantity' => ['required', 'integer', 'min:1', 'max: 9999'],
        ]);
        $validated['stock_quantity'] = $validated['stock_quantity'] + $model->stock_quantity;
        $model->update($validated);

        Flux::toast(variant: 'success', heading: __('Success'), text: __('Stock updated'));

        $this->modal['show'] = false;
        $this->dispatch('product-refresh');
    }

    public function delete(): void
    {
        $model = Product::findOrFail($this->modal['product_id']);
        $this->authorize('delete', $model);

        $model->delete();

        Flux::toast(variant: 'success', heading: __('Success'), text: __('Product deleted'));

        $this->modal['show'] = false;
        $this->dispatch('product-refresh');
    }

    public function closeModal(): void
    {
        $this->reset(['modal', 'name', 'brand_id', 'category_id', 'price', 'stock_quantity']);
        $this->resetValidation();
    }
};
