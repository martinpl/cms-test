<?php
 
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
 
new class extends Livewire\Component {
    use WithFileUploads;
 
    #[Validate('required|image|max:1024')]
    public $photo;
 
    public function save()
    {
        $this->validate();
        $img = $this->photo->storeAs('', $this->photo->getClientOriginalName(), 'public');
        App\PostTypes\Attachment::create([
            'title' => $this->photo->getClientOriginalName(),
            'content' => $img,
            'status' => 'publish',
            'user_id' => request()->user()->id, // TODO: we should autofill that
        ]);
        $this->dispatch('refreshList');
        $this->reset('photo');
    }
} ?>

{{-- TODO: support multiple file formats --}}
<form wire:submit="save">
    <input type="file" wire:model="photo">
    @error('photo') <span class="error">{{ $message }}</span> @enderror
    <flux:button type="submit">Upload</flux:button>
</form>