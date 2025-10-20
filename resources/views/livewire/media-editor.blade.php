<?php
 
use Livewire\Volt\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
 
new class extends Component {
    #[Locked]
    public $id;

    #[Locked]
    public $postType;

    public function mount() 
    {
        $postType = app(App\PostTypeRegistry::class)->find($this->postType);
        abort_if(!$postType, 404);

        if (!$this->post) {
            // TODO: Should be proper link not redirect when clicking add new page
            $this->redirectRoute('list', 'attachment');
        }
    }

    #[Computed]
    public function post() 
    {
        return App\PostTypes\Attachment::find($this->id);
    }
} ?>

<div>
    <img src="{{ Storage::url($this->post->content) }}">
</div>