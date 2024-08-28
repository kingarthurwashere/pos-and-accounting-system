<?php

namespace App\Livewire;

use App\Livewire\Forms\BookForm;
use Livewire\Component;

class CreateBook extends Component
{
    public BookForm $form;

    public function submit()
    {
        $this->form->validate();

        // sleep(3);

        $book = $this->form->create();

        $this->dispatch('stock-product.created');

        $this->dispatch('alert', [
            'body' => 'Inventory item `' . $book->title . '` was created'
        ]);
    }

    public function render()
    {
        return view('livewire.book.create-book');
    }
}
