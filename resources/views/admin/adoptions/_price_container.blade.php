<div id="priceContainer" class="mb-5">
        @foreach ($adoption->prices as $price)
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg col-3">
                            Days {!! add_help('How many days old should a stock be for this price change to take effect?') !!}
                            <div class="days">
                                {!! Form::text('days[' . $price->id . ']', $price->days, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-lg col-4">
                            Currency {!! add_help('Enter a new currency, or the old one if it remains the same.') !!}
                            <div class="currency">    
                                {!! Form::select('currency_id[' . $price->id . ']', $currencies, $price->currency_id, ['class' => 'form-control currency-select', 'placeholder' =>'Select Currency']) !!}
                            </div>
                        </div>
                        <div class="col-lg col-4">
                            Species (Optional)  {!! add_help('Add a species here if this price change should only affect a specific species.') !!}
                            <div class="species">    
                                {!! Form::select('species_id[' . $price->id . ']', $specieses, $price->species_id, ['class' => 'form-control species-select', 'placeholder' =>'Select Species']) !!}
                            </div>
                        </div>
                        <div class="col-lg col-4">
                            Price 
                            <div class="price">{!! Form::text('prices[' . $price->id . ']', $price->amount, ['class' => 'form-control']) !!}</div>
                        </div>
                        <div class="col-1">
                            Delete
                            <a href="#" class="btn btn-danger remove-price-button w-100">X</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="card hide mb-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg col-3">
                        Days {!! add_help('How many days old should a stock be for this price change to take effect?') !!}
                        <div class="days">
                            {!! Form::text('days[default]', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-lg col-4">
                        Currency {!! add_help('Enter a new currency, or the old one if it remains the same.') !!}
                        <div class="currency">    
                            {!! Form::select('currency_id[default]', $currencies, null, ['class' => 'form-control currency-select', 'placeholder' =>'Select Currency']) !!}
                        </div>
                    </div>
                    <div class="col-lg col-4">
                        Species (Optional)  {!! add_help('Add a species here if this price change should only affect a specific species.') !!}
                        <div class="species">    
                            {!! Form::select('species_id[default]', $specieses, null, ['class' => 'form-control species-select', 'placeholder' =>'Select Species']) !!}
                        </div>
                    </div>
                    <div class="col-lg col-4">
                        Price
                        <div class="price">{!! Form::text('prices[default]', null, ['class' => 'form-control']) !!}</div>
                    </div>
                    <div class="col-1">
                        Delete
                        <a href="#" class="btn btn-danger remove-price-button w-100">X</a>
                    </div>
                </div>
            </div>
        </div>
</div>

<div class="text-right mb-3">
    <a href="#" class="btn btn-outline-info" id="addPrice">Add Price</a>
</div>