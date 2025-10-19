<?php
 
use App\PostTypes\AnyPost;
use Livewire\Volt\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
 
new class extends Component {
    #[Locked]
    public $id;

    #[Locked]
    public $postType;

    public $title = '';

    public $content = []; 

    public $meta = [];

    public $terms = [];

    public function mount() 
    {
        $postType = app(App\PostTypeRegistry::class)->find($this->postType);
        abort_if(!$postType, 404);

        if ($this->post) {
            $this->content = json_decode($this->post->getRawOriginal('content'), true) ?: [];
        }
    }

    #[Computed]
    public function post() 
    {
        return $this->id ? AnyPost::with('terms')->find($this->id) : null;
    }

    public function add($name) 
    {
        $this->content[] = [
            'name' => $name,
            'data' => []
        ];
    }

    public function remove($index) 
    {
        array_splice($this->content, $index, 1);
    }

    public function save() 
    {
        $this->post = AnyPost::updateOrCreate(
            ['id' => $this->id],
            [
                'type' => $this->postType,
                'title' => $this->title,
                'status' => 'publish',
                'user_id' => request()->user()->id,
                'content' => $this->content,
            ]
        );
        $this->post->terms()->sync($this->terms);

        if ($this->post->wasRecentlyCreated) {
            $this->redirectRoute('editor', [
                'postType' => $this->postType,
                'id' => $this->post->id
            ]);
        } 
    }

    public function setAsHomePage($id) 
    {
        set_option('home_page', $id, true);
    }
} ?>

<div class="flex gap-6">
    <aside class="flex-2/12">
        Add block:<br>
        @foreach (app(\App\BlockType::class)->list as $slug => $block)
            <flux:button wire:click="add(`{{ $slug }}`)">
                {{ $block['name'] }}
            </flux:button>
        @endforeach
        <div>
            Structure:
            @foreach ($content as $block)
                <div>
                    {{ $block['name'] }}
                    <flux:button wire:click="remove({{ $loop->index }})">
                        Remove
                    </flux:button>
                </div>
            @endforeach
        </div>
    </aside>
    <main class="flex-8/12">
        <input name="title" type="text" placeholder="Title" wire:model.fill="title" value="{{ $this->post?->title }}"><br>
        @foreach ($content as $block)
            @php
                $class = 'Components\\' . Str::studly($block['name']).'\Schema';
            @endphp
            {{-- TODO: update only one block or cache another? --}}
            <div class="editor-block" tabindex="0" @focusout="if (!$event.currentTarget.contains($event.relatedTarget)) { $wire.$refresh(); }">
                @if (method_exists($class, 'fields'))
                    <div class="fields">
                        @foreach($class::fields() as $field)
                            @php
                                // TODO: validation
                                $field->model("content.{$loop->parent->index}.data");
                                $field->render();
                            @endphp
                        @endforeach
                    </div>
                @endif
                <div class="preview">
                    {!! \App\BlockEditor::resolveComponent($block['name'], $content[$loop->index]['data']) !!}
                </div>
            </div>
        @endforeach
    </main>
    <aside class="flex-2/12">
        <flux:button wire:click="save">
            Save
        </flux:button>
        <br>
        @if ($this->post?->link())
            <flux:button href="{{ $this->post->link() }}">
                Preview
            </flux:button>
            <br>
            <flux:button wire:click="setAsHomePage({{ $this->post->id }})">Set as homepage</flux:button>
            <br>
        @endif
        @foreach(app(App\TaxonomyType::class)->findForPostType($this->postType) as $taxonomy)
            @php
                $taxonomies = App\Models\Taxonomy::where('type', $taxonomy['name'])
                    ->orderBy('title')
                    ->get();
                $selectedTaxonomies = $this->post?->terms->where('type', $taxonomy['name'])->pluck('id')->toArray() ?? [];
            @endphp
            <div>
                {{ $taxonomy['plural'] }}
            </div>
            @foreach($taxonomies as $taxonomy)
                <label>
                    <input type="checkbox" 
                        value="{{ $taxonomy->id }}"
                        wire:model.fill="terms"
                        @checked(in_array($taxonomy->id, $selectedTaxonomies))>
                    {{ $taxonomy->title }}
                </label>
            @endforeach
        @endforeach
    </aside>
    <style>
        .editor-block {
            padding: 1rem;
            border: 1px dashed black;
            margin-bottom: 1rem;
        }

        .editor-block:not(:focus-within) .fields { display: none; }
        .editor-block:focus-within .preview { display: none; }
    </style>
</div>