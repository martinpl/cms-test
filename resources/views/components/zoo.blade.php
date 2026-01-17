<x-dashboard title="Zoo" class="p-0 md:p-0">
    <x-sidebar.provider>
        <x-sidebar.inset>
            {{-- TODO: twMerge shadow merge issue --}}
            <x-card id="button" class="border-0 border-b rounded-none shadow-none">
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
            <x-card id="badge" class="border-0 border-b rounded-none shadow-none">
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
            {{-- TODO: Support submenus, inputs --}}
            <x-card id="menubar" class="border-0 border-b rounded-none shadow-none">
                <x-card.header>
                    <x-card.title>Menubar</x-card.title>
                    {{-- <x-card.description>TODO</x-card.description> --}}
                </x-card.header>
                <x-card.content>
                    <x-menubar>
                        <x-menubar.menu>
                            <x-menubar.trigger>File</x-menubar.trigger>
                            <x-menubar.content>
                                <x-menubar.item>
                                    New Tab <x-menubar.shortcut>⌘T</x-menubar.shortcut>
                                </x-menubar.item>
                                <x-menubar.item>
                                    New Window <x-menubar.shortcut>⌘N</x-menubar.shortcut>
                                </x-menubar.item>
                                <x-menubar.item disabled>New Incognito Window</x-menubar.item>
                                <x-menubar.separator />
                                <x-menubar.sub>
                                    <x-menubar.sub-trigger>Share</x-menubar.sub-trigger>
                                    <x-menubar.sub-content>
                                        <x-menubar.item>Email link</x-menubar.item>
                                        <x-menubar.item>Messages</x-menubar.item>
                                        <x-menubar.item>Notes</x-menubar.item>
                                    </x-menubar.sub-content>
                                </x-menubar.sub>
                                <x-menubar.separator />
                                <x-menubar.item>
                                    Print... <x-menubar.shortcut>⌘P</x-menubar.shortcut>
                                </x-menubar.item>
                            </x-menubar.content>
                        </x-menubar.menu>
                        <x-menubar.menu>
                            <x-menubar.trigger>Edit</x-menubar.trigger>
                            <x-menubar.content>
                                <x-menubar.item>
                                    Undo <x-menubar.shortcut>⌘Z</x-menubar.shortcut>
                                </x-menubar.item>
                                <x-menubar.item>
                                    Redo <x-menubar.shortcut>⇧⌘Z</x-menubar.shortcut>
                                </x-menubar.item>
                                <x-menubar.separator />
                                <x-menubar.sub>
                                    <x-menubar.sub-trigger>Find</x-menubar.sub-trigger>
                                    <x-menubar.sub-content>
                                        <x-menubar.item>Search the web</x-menubar.item>
                                        <x-menubar.separator />
                                        <x-menubar.item>Find...</x-menubar.item>
                                        <x-menubar.item>Find Next</x-menubar.item>
                                        <x-menubar.item>Find Previous</x-menubar.item>
                                    </x-menubar.sub-content>
                                </x-menubar.sub>
                                <x-menubar.separator />
                                <x-menubar.item>Cut</x-menubar.item>
                                <x-menubar.item>Copy</x-menubar.item>
                                <x-menubar.item>Paste</x-menubar.item>
                            </x-menubar.content>
                        </x-menubar.menu>
                        <x-menubar.menu>
                            <x-menubar.trigger>View</x-menubar.trigger>
                            <x-menubar.content>
                                <x-menubar.checkbox-item>Always Show Bookmarks Bar</x-menubar.checkbox-item>
                                <x-menubar.checkbox-item checked>
                                    Always Show Full URLs
                                </x-menubar.checkbox-item>
                                <x-menubar.separator />
                                <x-menubar.item inset>
                                    Reload <x-menubar.shortcut>⌘R</x-menubar.shortcut>
                                </x-menubar.item>
                                <x-menubar.item disabled inset>
                                    Force Reload <x-menubar.shortcut>⇧⌘R</x-menubar.shortcut>
                                </x-menubar.item>
                                <x-menubar.separator />
                                <x-menubar.item inset>Toggle Fullscreen</x-menubar.item>
                                <x-menubar.separator />
                                <x-menubar.item inset>Hide Sidebar</x-menubar.item>
                            </x-menubar.content>
                        </x-menubar.menu>
                        <x-menubar.menu>
                            <x-menubar.trigger>Profiles</x-menubar.trigger>
                            <x-menubar.content>
                                <x-menubar.radio-group value="benoit">
                                    <x-menubar.radio-item value="andy">Andy</x-menubar.radio-item>
                                    <x-menubar.radio-item value="benoit">Benoit</x-menubar.radio-item>
                                    <x-menubar.radio-item value="Luis">Luis</x-menubar.radio-item>
                                </x-menubar.radio-group>
                                <x-menubar.separator />
                                <x-menubar.item inset>Edit...</x-menubar.item>
                                <x-menubar.separator />
                                <x-menubar.item inset>Add Profile...</x-menubar.item>
                            </x-menubar.content>
                        </x-menubar.menu>
                    </x-menubar>
                </x-card.content>
            </x-card>
            <x-card id="field" class="border-0 border-b rounded-none shadow-none">
                <x-card.header>
                    <x-card.title>Field</x-card.title>
                    {{-- <x-card.description>TODO</x-card.description> --}}
                </x-card.header>
                <x-card.content>
                    <div class="w-full max-w-md">
                        <form>
                            <x-field.group>
                                <x-field.set>
                                    <x-field.legend>Payment Method</x-field.legend>
                                    <x-field.description>
                                        All transactions are secure and encrypted
                                    </x-field.description>
                                    <x-field.group>
                                        <x-field>
                                            <x-field.label for="checkout-7j9-card-name-43j">
                                                Name on Card
                                            </x-field.label>
                                            <x-input id="checkout-7j9-card-name-43j" placeholder="Evil Rabbit" required />
                                        </x-field>
                                        <x-field>
                                            <x-field.label for="checkout-7j9-card-number-uw1">
                                                Card Number
                                            </x-field.label>
                                            <x-input id="checkout-7j9-card-number-uw1" placeholder="1234 5678 9012 3456" required />
                                            <x-field.description>
                                                Enter your 16-digit card number
                                            </x-field.description>
                                        </x-field>
                                        <div class="grid grid-cols-3 gap-4">
                                            <x-field>
                                                <x-field.label for="checkout-exp-month-ts6">
                                                    Month
                                                </x-field.label>
                                                <x-select defaultValue="">
                                                    <x-select.trigger id="checkout-exp-month-ts6">
                                                        <x-select.value placeholder="MM" />
                                                    </x-select.trigger>
                                                    <x-select.content>
                                                        <x-select.item value="01">01</x-select.item>
                                                        <x-select.item value="02">02</x-select.item>
                                                        <x-select.item value="03">03</x-select.item>
                                                        <x-select.item value="04">04</x-select.item>
                                                        <x-select.item value="05">05</x-select.item>
                                                        <x-select.item value="06">06</x-select.item>
                                                        <x-select.item value="07">07</x-select.item>
                                                        <x-select.item value="08">08</x-select.item>
                                                        <x-select.item value="09">09</x-select.item>
                                                        <x-select.item value="10">10</x-select.item>
                                                        <x-select.item value="11">11</x-select.item>
                                                        <x-select.item value="12">12</x-select.item>
                                                    </x-select.content>
                                                </x-select>
                                            </x-field>
                                            <x-field>
                                                <x-field.label for="checkout-7j9-exp-year-f59">
                                                    Year
                                                </x-field.label>
                                                <x-select defaultValue="">
                                                    <x-select.trigger id="checkout-7j9-exp-year-f59">
                                                        <x-select.value placeholder="YYYY" />
                                                    </x-select.trigger>
                                                    <x-select.content>
                                                        <x-select.item value="2024">2024</x-select.item>
                                                        <x-select.item value="2025">2025</x-select.item>
                                                        <x-select.item value="2026">2026</x-select.item>
                                                        <x-select.item value="2027">2027</x-select.item>
                                                        <x-select.item value="2028">2028</x-select.item>
                                                        <x-select.item value="2029">2029</x-select.item>
                                                    </x-select.content>
                                                </x-select>
                                            </x-field>
                                            <x-field>
                                                <x-field.label for="checkout-7j9-cvv">CVV</x-field.label>
                                                <x-input id="checkout-7j9-cvv" placeholder="123" required />
                                            </x-field>
                                        </div>
                                    </x-field.group>
                                </x-field.set>
                                <x-field.separator />
                                <x-field.set>
                                    <x-field.legend>Billing Address</x-field.legend>
                                    <x-field.description>
                                        The billing address associated with your payment method
                                    </x-field.description>
                                    <x-field.group>
                                        <x-field orientation="horizontal">
                                            <x-checkbox id="checkout-7j9-same-as-shipping-wgm" checked />
                                            <x-field.label for="checkout-7j9-same-as-shipping-wgm" class="font-normal">
                                                Same as shipping address
                                            </x-field.label>
                                        </x-field>
                                    </x-field.group>
                                </x-field.set>
                                <x-field.set>
                                    <x-field.group>
                                        <x-field>
                                            <x-field.label for="checkout-7j9-optional-comments">
                                                Comments
                                            </x-field.label>
                                            <x-textarea id="checkout-7j9-optional-comments" placeholder="Add any additional comments"
                                                class="resize-none" />
                                        </x-field>
                                    </x-field.group>
                                </x-field.set>
                                <x-field orientation="horizontal">
                                    <x-button type="submit">Submit</x-button>
                                    <x-button variant="outline" type="button">
                                        Cancel
                                    </x-button>
                                </x-field>
                            </x-field.group>
                        </form>
                    </div>
                    <div class="w-full max-w-4xl mt-4">
                        <form>
                            <x-field.set>
                                <x-field.legend>Profile</x-field.legend>
                                <x-field.description>Fill in your profile information.</x-field.description>
                                <x-field.separator />
                                <x-field.group>
                                    <x-field orientation="responsive">
                                        <x-field.content>
                                            <x-field.label for="name">Name</x-field.label>
                                            <x-field.description>
                                                Provide your full name for identification
                                            </x-field.description>
                                        </x-field.content>
                                        <x-input id="name" placeholder="Evil Rabbit" required />
                                    </x-field>
                                    <x-field.separator />
                                    <x-field orientation="responsive">
                                        <x-field.content>
                                            <x-field.label for="lastName">Message</x-field.label>
                                            <x-field.description>
                                                You can write your message here. Keep it short, preferably
                                                under 100 characters.
                                            </x-field.description>
                                        </x-field.content>
                                        <x-textarea id="message" placeholder="Hello, world!" required
                                            class="min-h-[100px] resize-none sm:min-w-[300px]" />
                                    </x-field>
                                    <x-field.separator />
                                    <x-field orientation="responsive">
                                        <x-button type="submit">Submit</x-button>
                                        <x-button type="button" variant="outline">
                                            Cancel
                                        </x-button>
                                    </x-field>
                                </x-field.group>
                            </x-field.set>
                        </form>
                    </div>
                </x-card.content>
            </x-card>
            <x-card id="dialog" class="border-0 border-b rounded-none shadow-none">
                <x-card.header>
                    <x-card.title>Dialog</x-card.title>
                    {{-- <x-card.description>TODO</x-card.description> --}}
                </x-card.header>
                <x-card.content>
                    <x-dialog name="dialog-name">
                        <form>
                            <x-dialog.trigger>
                                <x-button variant="outline">
                                    Open Dialog
                                </x-button>
                            </x-dialog.trigger>
                            <x-dialog.content class="sm:max-w-[425px]">
                                <x-dialog.header>
                                    <x-dialog.title>Edit profile</x-dialog.title>
                                    <x-dialog.description>
                                        Make changes to your profile here. Click save when you&apos;re
                                        done.
                                    </x-dialog.description>
                                </x-dialog.header>
                                <div class="grid gap-4">
                                    <div class="grid gap-3">
                                        <x-label for="name-1">Name</x-label>
                                        <x-input id="name-1" name="name" value="Pedro Duarte" />
                                    </div>
                                    <div class="grid gap-3">
                                        <x-label for="username-1">Username</x-label>
                                        <x-input id="username-1" name="username" value="@peduarte" />
                                    </div>
                                </div>
                                <x-dialog.footer>
                                    <x-dialog.close>
                                        <x-button variant="outline">Cancel</x-button>
                                    </x-dialog.close>
                                    <x-button type="submit">Save changes</x-button>
                                </x-dialog.footer>
                            </x-dialog.content>
                        </form>
                    </x-dialog>
                </x-card.content>
            </x-card>
            <x-card id="tabs" class="border-0 border-b rounded-none shadow-none">
                <x-card.header>
                    <x-card.title>Tabs</x-card.title>
                    {{-- <x-card.description>TODO</x-card.description> --}}
                </x-card.header>
                <x-card.content>
                    <div class="flex w-full max-w-sm flex-col gap-6">
                        <x-tabs defaultValue="account">
                            <x-tabs.list>
                                <x-tabs.trigger value="account">Account</x-tabs.trigger>
                                <x-tabs.trigger value="password">Password</x-tabs.trigger>
                            </x-tabs.list>
                            <x-tabs.content value="account">
                                <x-card>
                                    <x-card.header>
                                        <x-card.title>Account</x-card.title>
                                        <x-card.description>
                                            Make changes to your account here. Click save when you&apos;re
                                            done.
                                        </x-card.description>
                                    </x-card.header>
                                    <x-card.content class="grid gap-6">
                                        <div class="grid gap-3">
                                            <x-label for="tabs-demo-name">Name</x-label>
                                            <x-input id="tabs-demo-name" value="Pedro Duarte" />
                                        </div>
                                        <div class="grid gap-3">
                                            <x-label for="tabs-demo-username">Username</x-label>
                                            <x-input id="tabs-demo-username" value="@peduarte" />
                                        </div>
                                    </x-card.content>
                                    <x-card.footer>
                                        <x-button>Save changes</x-button>
                                    </x-card.footer>
                                </x-card>
                            </x-tabs.content>
                            <x-tabs.content value="password">
                                <x-card>
                                    <x-card.header>
                                        <x-card.title>Password</x-card.title>
                                        <x-card.description>
                                            Change your password here. After saving, you&apos;ll be logged
                                            out.
                                        </x-card.description>
                                    </x-card.header>
                                    <x-card.content class="grid gap-6">
                                        <div class="grid gap-3">
                                            <x-label for="tabs-demo-current">Current password</x-label>
                                            <x-input id="tabs-demo-current" type="password" />
                                        </div>
                                        <div class="grid gap-3">
                                            <x-label for="tabs-demo-new">New password</x-label>
                                            <x-input id="tabs-demo-new" type="password" />
                                        </div>
                                    </x-card.content>
                                    <x-card.footer>
                                        <x-button>Save password</x-button>
                                    </x-card.footer>
                                </x-card>
                            </x-tabs.content>
                        </x-tabs>
                    </div>
                </x-card.content>
            </x-card>
            <x-card id="collapsible" class="border-0 border-b rounded-none shadow-none">
                <x-card.header>
                    <x-card.title>Collapsible</x-card.title>
                    {{-- <x-card.description>TODO</x-card.description> --}}
                </x-card.header>
                <x-card.content>
                    <x-collapsible class="flex w-[350px] flex-col gap-2">
                        <div class="flex items-center justify-between gap-4 px-4">
                            <h4 class="text-sm font-semibold">
                                @peduarte starred 3 repositories
                            </h4>
                            <x-collapsible.trigger>
                                <x-button variant="ghost" size="icon" class="size-8">
                                    <x-icon name="chevrons-up-down" />
                                    <span class="sr-only">Toggle</span>
                                </x-button>
                            </x-collapsible.trigger>
                        </div>
                        <div class="rounded-md border px-4 py-2 font-mono text-sm">
                            @radix-ui/primitives
                        </div>
                        <x-collapsible.content class="flex flex-col gap-2">
                            <div class="rounded-md border px-4 py-2 font-mono text-sm">
                                @radix-ui/colors
                            </div>
                            <div class="rounded-md border px-4 py-2 font-mono text-sm">
                                @stitches/react
                            </div>
                        </x-collapsible.content>
                    </x-collapsible>
                </x-card.content>
            </x-card>
        </x-sidebar.inset>
        <x-sidebar side="right">
            <x-sidebar.content>
                <x-sidebar.group>
                    <x-sidebar.group-content>
                        <x-sidebar.menu>
                            @foreach (['Button', 'Badge', 'Menubar', 'Field', 'Dialog', 'Tabs', 'Collapsible'] as $item)
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
