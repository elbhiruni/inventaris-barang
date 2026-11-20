<?php

use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Modelable;
use Livewire\Component;

// @phpstan-ignore expr.resultUnused
new class extends Component
{
    #[Modelable]
    public ?int $value = null;

    /** @return Collection<int, Brand> */
    #[Computed]
    public function items(): Collection
    {
        return Brand::all();
    }
};
?>

<div>
    <flux:select wire:model="value">
        <flux:select.option value="null" disabled>Pilih Brand</flux:select.option>
        @foreach ($this->items as $item)
            <flux:select.option :key="$item->id" :value="$item->id">
                {{ $item->name }}
            </flux:select.option>
        @endforeach
    </flux:select>
</div>
