<?php

namespace App\Livewire;

use Illuminate\Support\HtmlString;
use Livewire\Attributes\Url;

// TODO: Bulk actions
trait Table
{
    use \Livewire\WithPagination;

    #[Url]
    public $view;

    #[Url]
    public $search = '';

    public function mountTable()
    {
        $this->view = $this->view ?? array_key_first($this->views);
    }

    protected function views()
    {
        return [];
    }

    protected function counts()
    {
        return [];
    }

    abstract protected function columns();

    abstract protected function items();

    protected function rowActions($actions)
    {
        return new HtmlString(
            '<div class="text-xs text-muted-foreground [&>a:hover]:text-primary flex gap-1 opacity-0 group-hover:opacity-100 mt-1">
                '.implode(' | ', $actions).'
            </div>'
        );
    }

    // TODO: Builder?
    protected function table($search = false)
    {
        return view('livewire.table', compact('search'));
    }

    public function getViewsProperty()
    {
        return $this->views();
    }

    public function getCountsProperty()
    {
        return $this->counts();
    }
}
