<?php
 
use App\Models\Post;
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
        $postType = app(App\PostType::class)->find($this->postType);
        abort_if(!$postType, 404);

        if ($this->post) {
            $this->content = json_decode($this->post->getRawOriginal('content'), true);
        }
    }

    #[Computed]
    public function post() 
    {
        return $this->id ? App\Models\Post::with('terms')->find($this->id) : null;
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
        $this->post = App\Models\Post::updateOrCreate(
            ['id' => $this->id],
            [
                'title' => $this->title,
                'content' => $this->content,
                'status' => 'publish',
                'type' => $this->postType,
                'user_id' => request()->user()->id,
            ]
        );
        $this->post->terms()->sync($this->terms);
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
            <button wire:click="add(`{{ $slug }}`)">
                {{ $block['name'] }}
            </button>
        @endforeach
        <div>
            Structure:
            @foreach ($content as $block)
                <div>
                    {{ $block['name'] }}
                    <button wire:click="remove({{ $loop->index }})">
                        Remove
                    </button>
                </div>
            @endforeach
        </div>
    </aside>
    <main class="flex-8/12">
        <input name="title" type="text" placeholder="Title" wire:model.fill="title" value="{{ $this->post?->title }}"><br>
        @foreach ($content as $block)
            @php
                $class = 'App\Schema\\' . Str::studly($block['name'])
            @endphp
            {{-- TODO: update only one block or cache another? --}}
            <div class="block" tabindex="0" @focusout="if (!$event.currentTarget.contains($event.relatedTarget)) { $wire.$refresh(); }">
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
                    @php 
                        echo view('components.'.Str::slug($block['name']), $content[$loop->index]['data']);
                    @endphp
                </div>
            </div>
        @endforeach
    </main>
    <aside class="flex-2/12">
        <button wire:click="save">
            Save
        </button>
        <br>
        @if ($this->post?->link())
            <a href="{{ $this->post->link() }}">
                Preview
            </a>
            <br>
            <button wire:click="setAsHomePage({{ $this->post->id }})">Set as homepage</button>
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
        .block:not(:focus-within) .fields { display: none; }
        .block:focus-within .preview { display: none; }
    </style>
</div>