<x-dashboard title="Zoo" class="p-0 md:p-0">
    <x-sidebar.provider>
        <x-sidebar.inset class="p-4 md:p-6">
            <main class="space-y-8">
                <x-card id="button">
                    <x-card.header>
                        <x-card.title>Button</x-card.title>
                        <x-card.description>Interactive buttons and links</x-card.description>
                    </x-card.header>
                    <x-card.content class="space-y-4">
                        <div class="space-y-2">
                            <p class="text-sm font-medium">Variants</p>
                            <div class="flex flex-wrap gap-2">
                                <x-button variant="default">Default</x-button>
                                <x-button variant="secondary">Secondary</x-button>
                                <x-button variant="destructive">Destructive</x-button>
                                <x-button variant="outline">Outline</x-button>
                                <x-button variant="ghost">Ghost</x-button>
                                <x-button variant="link">Link</x-button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm font-medium">Sizes</p>
                            <div class="flex flex-wrap items-center gap-2">
                                <x-button size="xs">Extra Small</x-button>
                                <x-button size="sm">Small</x-button>
                                <x-button size="default">Default</x-button>
                                <x-button size="lg">Large</x-button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm font-medium">With Icon</p>
                            <div class="flex flex-wrap gap-2">
                                <x-button variant="outline" size="icon-sm">
                                    <x-icon name="circle-fading-arrow-up" />
                                </x-button>
                                <x-button variant="outline" size="icon">
                                    <x-icon name="circle-fading-arrow-up" />
                                </x-button>
                                <x-button variant="outline" size="icon-lg">
                                    <x-icon name="circle-fading-arrow-up" />
                                </x-button>
                                <x-button variant="default"><x-icon name="git-branch" /> Default</x-button>
                                <x-button variant="secondary"><x-icon name="git-branch" /> Secondary</x-button>
                                <x-button variant="destructive"><x-icon name="git-branch" /> Destructive</x-button>
                                <x-button variant="outline"><x-icon name="git-branch" /> Outline</x-button>
                                <x-button variant="ghost"><x-icon name="git-branch" /> Ghost</x-button>
                                <x-button variant="link"><x-icon name="git-branch" /> Link</x-button>
                                <x-button variant="outline" size="icon" class="rounded-full">
                                    <x-icon name="arrow-up" />
                                </x-button>
                                <x-button size="sm" variant="outline" disabled>
                                    <x-spinner />
                                    Submit
                                </x-button>
                            </div>
                        </div>
                    </x-card.content>
                </x-card>
                <x-card id="badge">
                    <x-card.header>
                        <x-card.title>Badge</x-card.title>
                        <x-card.description>Status indicators and labels</x-card.description>
                    </x-card.header>
                    <x-card.content>
                        <div class="flex flex-wrap gap-2">
                            <x-badge>Default</x-badge>
                            <x-badge variant="secondary">Secondary</x-badge>
                            <x-badge variant="destructive">Destructive</x-badge>
                            <x-badge variant="outline">Outline</x-badge>
                            {{-- TODO: twMerge bug --}}
                            <x-badge variant="secondary" class="bg-blue-500 text-white dark:bg-blue-600">
                                <x-icon name="check" />
                                Verified
                            </x-badge>
                            <x-badge>8</x-badge>
                            <x-badge variant="destructive">99</x-badge>
                            <x-badge variant="outline">+20</x-badge>
                        </div>
                    </x-card.content>
                </x-card>
            </main>
        </x-sidebar.inset>
        <x-sidebar side="right">
            <x-sidebar.content>
                <x-sidebar.group>
                    <x-sidebar.group-content>
                        <x-sidebar.menu>
                            @foreach (['Button', 'Badge'] as $item)
                                <x-sidebar.menu-item>
                                    <x-sidebar.menu-button href="#{{ Str::slug($item) }}"
                                        class="font-medium">{{ $item }}</x-sidebar.menu-button>
                                </x-sidebar.menu-item>
                            @endforeach
                        </x-sidebar.menu>
                    </x-sidebar.group-content>
                </x-sidebar.group>
            </x-sidebar.content>
            <x-sidebar.rail />
        </x-sidebar>
    </x-sidebar.provider>
</x-dashboard>
