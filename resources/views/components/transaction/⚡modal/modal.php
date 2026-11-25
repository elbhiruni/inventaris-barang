<?php

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Flux\Flux;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Modelable;
use Livewire\Component;

// @phpstan-ignore expr.resultUnused
new class extends Component
{
    /**
     * @var array{
     *     action: string,
     *     transaction_id: int|null,
     *     show: bool
     * }
     */
    #[Modelable]
    public array $modal = [
        'action' => '',
        'transaction_id' => null,
        'show' => false,
    ];

    public ?int $product_id = null;

    public int $quantity = 0;

    /**
     * @var array<int, array{
     *     product_id: int,
     *     product_name: string,
     *     quantity: int,
     *     subtotal: int,
     * }>
     */
    public array $transaction_details = [];

    public ?string $customer_name = null;

    #[Computed]
    public function title(): string
    {
        if ($this->modal['action'] === 'store') {
            return 'Add Transaction';
        }

        if ($this->modal['action'] === 'details') {
            return 'Transaction Details';
        }

        return '';
    }

    #[Computed]
    public function labelSubmit(): string
    {
        if ($this->modal['action'] === 'store') {
            return 'Save';
        }

        return '';
    }

    /**
     * @return array<string, array<string|ValidationRule>>
     */
    protected function rules(): array
    {
        return [
            'customer_name' => ['string', 'max:255', 'nullable'],
        ];
    }

    public function store(): void
    {
        if (empty($this->transaction_details)) {
            return;
        }

        $validated = $this->validate();
        $validated['total_amount'] = $this->totalAmount();

        DB::transaction(function () use ($validated) {
            $model = Transaction::create($validated);
            $model->transactionDetails()->createMany($this->transaction_details);

            foreach ($this->aggregateQuantities() as $productId => $quantity) {
                /** @var Product|null $product */
                $product = Product::whereKey($productId)->lockForUpdate()->first();

                if ($product === null || $product->stock_quantity < $quantity) {
                    throw ValidationException::withMessages([
                        'quantity' => __('Stok tidak cukup'),
                    ]);
                }

                $product->decrement('stock_quantity', $quantity);
            }
        });

        Flux::toast(variant: 'success', heading: __('Success'), text: __('Transaction created'));

        $this->modal['show'] = false;
        $this->dispatch('transaction-refresh');
    }

    public function addProduct(): void
    {
        $validatedProduct = $this->validate([
            'product_id' => ['required', 'integer', 'exists:App\Models\Product,id'],
        ]);

        $productId = (int) $validatedProduct['product_id'];

        if (collect($this->transaction_details)->contains('product_id', $productId)) {
            throw ValidationException::withMessages([
                'product_id' => __('Produk ini sudah ditambahkan.'),
            ]);
        }

        /** @var Product $product */
        $product = Product::findOrFail($productId);

        $validated = $this->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:'.$product->stock_quantity],
        ]);

        $quantity = (int) $validated['quantity'];

        array_push($this->transaction_details, [
            'product_id' => $productId,
            'product_name' => $product->name,
            'quantity' => $quantity,
            'subtotal' => $product->price * $quantity,
        ]);

        $this->reset(['product_id', 'quantity']);
    }

    public function changeQuantity(int $index, int $delta): void
    {
        if (! isset($this->transaction_details[$index])) {
            return;
        }

        /** @var Product $product */
        $product = Product::findOrFail($this->transaction_details[$index]['product_id']);

        $quantity = $this->transaction_details[$index]['quantity'] + $delta;

        if ($quantity < 1 || $quantity > $product->stock_quantity) {
            return;
        }

        $this->transaction_details[$index]['quantity'] = $quantity;
        $this->transaction_details[$index]['subtotal'] = $product->price * $quantity;
    }

    public function removeProduct(int $index): void
    {
        if (! isset($this->transaction_details[$index])) {
            return;
        }

        unset($this->transaction_details[$index]);

        $this->transaction_details = array_values($this->transaction_details);
    }

    /**
     * @return array<int, int>
     */
    #[Computed]
    public function productStocks(): array
    {
        $productIds = [];

        foreach ($this->transaction_details as $detail) {
            $productIds[] = $detail['product_id'];
        }

        if ($productIds === []) {
            return [];
        }

        $stocks = [];

        /** @var Product $product */
        foreach (Product::whereIn('id', $productIds)->get() as $product) {
            $stocks[$product->id] = $product->stock_quantity;
        }

        return $stocks;
    }

    /**
     * @return array<int, int>
     */
    #[Computed]
    public function addedProductIds(): array
    {
        $productIds = [];

        foreach ($this->transaction_details as $detail) {
            $productIds[] = $detail['product_id'];
        }

        return $productIds;
    }

    /**
     * @return array<int, int>
     */
    private function aggregateQuantities(): array
    {
        $quantities = [];

        foreach ($this->transaction_details as $detail) {
            $productId = $detail['product_id'];
            $quantities[$productId] = ($quantities[$productId] ?? 0) + $detail['quantity'];
        }

        return $quantities;
    }

    #[Computed]
    public function items(): mixed
    {
        if ($this->modal['action'] === 'store') {
            return $this->transaction_details;
        }

        if ($this->modal['action'] === 'details') {
            return TransactionDetail::where('transaction_id', $this->modal['transaction_id'])->get();
        }

        return [];
    }

    #[Computed]
    public function totalAmount(): int
    {
        if ($this->modal['action'] === 'store') {
            $total = 0;

            foreach ($this->transaction_details as $value) {
                $total += $value['subtotal'];
            }

            return $total;
        }

        if ($this->modal['action'] === 'details') {
            $model = Transaction::findOrFail($this->modal['transaction_id']);

            return $model->total_amount;
        }

        return 0;
    }

    public function closeModal(): void
    {
        $this->reset(['modal', 'customer_name', 'product_id', 'quantity', 'transaction_details']);
        $this->resetValidation();
    }
};
