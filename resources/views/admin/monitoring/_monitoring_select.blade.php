<table class="table table-sm" id="lootTable">
    <thead>
        <tr>
            <th width="40%">Object Type</th>
            <th width="40%">Object</th>
            <th width="20%">Sort</th>
        </tr>
    </thead>
    <tbody id="lootRow">
        <tr class="loot-row">
            <td>{!! Form::select('object_type',
                ['Item' => 'Item', 'Currency' => 'Currency', 'Raffle' => 'Raffle Ticket'],
                $requestParams['object_type'] ?? null ,
                ['class' => 'form-control reward-type w-100', 'placeholder' => 'Select Object Type']) !!}</td>
            <td class="loot-row-select">
                @isset($requestParams['object_type'])
                    @if($requestParams['object_type'] == 'Item')
                        {!! Form::select('object_id', $items, $requestParams['object_id'] ?? null, ['class' => 'form-control item-select selectize w-100', 'placeholder' => 'Select Item']) !!}
                    @elseif($requestParams['object_type'] == 'Currency')
                        {!! Form::select('object_id', $currencies, $requestParams['object_id'] ?? null, ['class' => 'form-control currency-select selectize w-100', 'placeholder' => 'Select Currency']) !!}
                    @elseif($requestParams['object_type'] == 'Raffle')
                        {!! Form::select('object_id', $raffles, $requestParams['object_id'] ?? null, ['class' => 'form-control raffle-select selectize w-100', 'placeholder' => 'Select Raffle']) !!}
                    @endif
                @endisset
            </td>
            <td>{!! Form::select('sort',
                ['all' => 'All Time', 'current' => 'Current'],
                $requestParams['sort'] ?? 'all' ,
                ['class' => 'form-control w-100']) !!}</td>
        </tr>
    </tbody>
</table>