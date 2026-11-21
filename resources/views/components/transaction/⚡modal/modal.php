<?php

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Flux\Flux;
use Illuminate\Contracts\Validation\ValidationRule;
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

        $model = Transaction::create($validated);
        $model->transactionDetails()->createMany($this->transaction_details);

        Flux::toast(variant: 'success', heading: __('Success'), text: __('Transaction created'));

        $this->modal['show'] = false;
        $this->dispatch('transaction-refresh');
    }

    public function addProduct(): void
    {
        $validated = $this->validate([
            'product_id' => ['required', 'integer', 'exists:App\Models\Product,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        $productId = (int) $validated['product_id'];
        $quantity = (int) $validated['quantity'];

        /** @var Product $product */
        $product = Product::findOrFail($validated['product_id']);

        array_push($this->transaction_details, [
            'product_id' => $productId,
            'product_name' => $product->name,
            'quantity' => $quantity,
            'subtotal' => $product->price * $quantity,
        ]);

        $this->reset(['product_id', 'quantity']);
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
