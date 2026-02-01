<?php

use App\Models\User;
use App\Role;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

new class extends \Livewire\Component
{
    use App\Livewire\Table;

    public function views()
    {
        $views = [
            'all' => 'All',
        ];

        foreach (app(Role::class)->list as $role) {
            $views[$role['name']] = $role['title'];
        }

        return $views;
    }

    private function counts()
    {
        $counts = [];

        foreach (app(Role::class)->list as $role) {
            $counts[$role['name']] = DB::table('meta')->where('metable_type', 'App\Models\User')->whereJsonContains('meta.value', $role['name'])->count();
        }

        $counts['all'] = array_sum($counts);

        return $counts;
    }

    public function columns()
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'role' => 'Role',
        ];
    }

    public function items()
    {
        return User::query()
            ->when($this->view != 'all', fn ($q) => $q->whereMetaIn('roles', $this->view))
            ->latest()
            ->paginate(10);
    }

    public function columnName($user)
    {
        $title = Blade::render(
            <<<'BLADE'
                <x-button :href="route('user', $user->id)" variant="link" class="text-foreground w-fit p-0 h-auto">
                    {{ $user->name }}
                </x-button>
                {{ $actions }}
            BLADE
            ,
            [
                'user' => $user,
                'actions' => $this->actions($user),
            ],
        );

        return new HtmlString($title);
    }

    public function columnRole($user)
    {
        $roles = [];
        foreach ($user->meta('roles', []) as $role) {
            // TODO: Move to user method
            $roles[] = app(Role::class)->list[$role]['title'];
        }

        return implode(', ', $roles);
    }

    private function actions($user)
    {
        $actions = [
            'edit' => '<a href="'.route('user', $user->id).'">Edit</a>',
        ];

        return $this->rowActions($actions);
    }
}; ?>

<x-slot:title>
    Users
</x-slot>

<x-slot:action>
    <x-button :href="route('user')" size="xs">
        <x-icon name="circle-plus" class="text-black fill-white" />
        Create
    </x-button>
</x-slot>

{{ $this->table() }}
