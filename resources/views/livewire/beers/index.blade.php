<div>
    <flux:main containe="">
        <div class="flex flex-row items-center justify-between w-full">
            <div>
                <flux:heading size="xl">Cervejas</flux:heading>
                <flux:text class="mt-2 mb-6 text-base">Listagem de cervejas</flux:text>
            </div>

            <flux:button href="{{ route('beers.create')  }}" icon="plus-circle">Criar nova cerveja</flux:button>
        </div>

        <x-section>

            <x-table>

                <x-table.columns>

                    <x-table.column>Nome</x-table.column>
                    <x-table.column
                        wire:click="sort('first_brewed_at')"
                        sortable
                        :sorted="$sortBy === 'first_brewed_at'"
                        :direction="$sortDirection"
                    >Data da primeira receita
                    </x-table.column>

                    <x-table.column
                        wire:click="sort('abv')"
                        sortable
                        :sorted="$sortBy === 'abv'"
                        :direction="$sortDirection"
                    >ABV
                    </x-table.column>

                    <x-table.column
                        wire:click="sort('ibu')"
                        sortable
                        :sorted="$sortBy === 'ibu'"
                        :direction="$sortDirection"
                    >IBU
                    </x-table.column>

                    <x-table.column
                        wire:click="sort('ebc')"
                        sortable
                        :sorted="$sortBy === 'ebc'"
                        :direction="$sortDirection"
                    >EBC
                    </x-table.column>

                    <x-table.column
                        wire:click="sort('ph')"
                        sortable
                        :sorted="$sortBy === 'ph'"
                        :direction="$sortDirection"
                    >PH
                    </x-table.column>

                    <x-table.column
                        wire:click="sort('volume')"
                        sortable
                        :sorted="$sortBy === 'volume'"
                        :direction="$sortDirection"
                    >Volume
                    </x-table.column>
                    <x-table.column></x-table.column>

                    <x-table.rows>
                        <x-table.row>
                            <x-table.cell>

                            </x-table.cell>
                        </x-table.row>

                    </x-table.rows>

                </x-table.columns>

            </x-table>
        </x-section>
    </flux:main>
</div>
