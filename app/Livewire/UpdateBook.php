<?php

namespace App\Livewire;

use App\Livewire\Forms\BookForm;
use App\Models\Book;
use Livewire\Attributes\Rule;
use Livewire\Component;

class UpdateBook extends Component
{
    public Book $book;

    public BookForm $form;

    public function mount()
    {
        $this->form->setBook($this->book);
    }

    public function submit()
    {
        $this->form->validate();

        $this->form->update();

        $this->dispatch('book.' . $this->book->id . '.updated');
    }

    public function render()
    {
        return view('livewire.book.update-book');
    }
}
