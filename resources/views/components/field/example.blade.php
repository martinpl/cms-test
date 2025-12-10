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
                        <x-input
                            id="checkout-7j9-card-name-43j"
                            placeholder="Evil Rabbit"
                            required
                        />
                        </x-field>
                        <x-field>
                        <x-field.label for="checkout-7j9-card-number-uw1">
                            Card Number
                        </x-field.label>
                        <x-input
                            id="checkout-7j9-card-number-uw1"
                            placeholder="1234 5678 9012 3456"
                            required
                        />
                        <x-field.description>
                            Enter your 16-digit card number
                        </x-field.description>
                    </x-field>
                    <div class="grid grid-cols-3 gap-4">
                    <x-field>
                        <x-field.label for="checkout-exp-month-ts6">
                            Month
                        </x-field.label>
                        {{-- <x-select defaultValue="">
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
                        </x-select> --}}
                    </x-field>
                    <x-field>
                        <x-field.label for="checkout-7j9-exp-year-f59">
                            Year
                        </x-field.label>
                        {{-- <x-select defaultValue="">
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
                        </x-select> --}}
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
                        <x-checkbox
                            id="checkout-7j9-same-as-shipping-wgm"
                            defaultChecked
                        />
                        <x-field.label
                            for="checkout-7j9-same-as-shipping-wgm"
                            class="font-normal"
                        >
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
                        <x-textarea
                            id="checkout-7j9-optional-comments"
                            placeholder="Add any additional comments"
                            class="resize-none"
                        />
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