<div class="container-fluid">
    <div class="row p-2">
        <div class="col-md-12">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a wire:click="tabSelect('related')"
                                class="nav-link  @if ($unitTabSelect == 'related') active @endif"
                                id="custom-tabs-four-unit-related-tab" data-toggle="pill"
                                href="#custom-tabs-four-unit-related" role="tab"
                                aria-controls="custom-tabs-four-unit-related" aria-selected="true">Unit Related</a>
                        </li>
                        <li class="nav-item">
                            <a wire:click="tabSelect('pricelevel')"
                                class="nav-link  @if ($unitTabSelect == 'pricelevel') active @endif"
                                id="custom-tabs-four-unit-price-level-tab" data-toggle="pill"
                                href="#custom-tabs-four-unit-price-level" role="tab"
                                aria-controls="custom-tabs-four-unit-price-level" aria-selected="false">Unit Price
                                Level</a>
                        </li>
                        <li class="nav-item">
                            <a wire:click="tabSelect('location')"
                                class="nav-link  @if ($unitTabSelect == 'location') active @endif"
                                id="custom-tabs-four-location-default-tab" data-toggle="pill"
                                href="#custom-tabs-four-location-default" role="tab"
                                aria-controls="custom-tabs-four-location-default" aria-selected="false">Location
                                Default</a>
                        </li>

                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade @if ($unitTabSelect == 'related') show active @endif"
                            id="custom-tabs-four-unit-related" role="tabpanel"
                            aria-labelledby="custom-tabs-four-unit-related-tab">
                            <div class="row"
                                @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                <div class="col-md-12">
                                    @livewire('ItemPage.ItemUnitRelatedUnit', ['itemId' => $itemId])
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade @if ($unitTabSelect == 'pricelevel') show active @endif"
                            id="custom-tabs-four-unit-price-level" role="tabpanel"
                            aria-labelledby="custom-tabs-four-unit-price-level-tab">
                            <div class="row"
                                @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                <div class="col-md-12">
                                    @livewire('ItemPage.ItemUnitPriceLevelUnit', ['itemId' => $itemId])
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade @if ($unitTabSelect == 'location') show active @endif"
                            id="custom-tabs-four-location-default" role="tabpanel"
                            aria-labelledby="custom-tabs-four-location-default-tab">
                            <div class="row"
                                @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                <div class="col-md-12">
                                    @livewire('ItemPage.ItemUnitLocationDefault', ['itemId' => $itemId])
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
