<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-12">
                    <div class="card">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <h3 class="card-title">
                                Option
                            </h3>
                        </div>
                        <div class="container-fluid">
                            <div class="card card-tabs mt-2 ">
                                <div class="card-header p-0 pt-1 border-bottom-0">
                                    <ul class="nav nav-tabs text-sm" id="custom-tabs-three-tab" role="tablist">
                                        <li class="nav-item">
                                            <a wire:click="SelectTab('com')"
                                                class="nav-link @if ($activeTab == 'com') active @endif"
                                                id="custom-tabs-three-com-tab" data-toggle="pill"
                                                href="#custom-tabs-three-com" role="tab"
                                                aria-controls="custom-tabs-three-com" aria-selected="true">
                                                Company
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a wire:click="SelectTab('gen')"
                                                class="nav-link @if ($activeTab == 'gen') active @endif"
                                                id="custom-tabs-three-gen-tab" data-toggle="pill"
                                                href="#custom-tabs-three-gen" role="tab"
                                                aria-controls="custom-tabs-three-gen" aria-selected="false">
                                                General
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a wire:click="SelectTab('acct')"
                                                class="nav-link @if ($activeTab == 'acct') active @endif"
                                                id="custom-tabs-three-acct-tab" data-toggle="pill"
                                                href="#custom-tabs-three-acct" role="tab"
                                                aria-controls="custom-tabs-three-acct"
                                                aria-selected="false">Accounting</a>
                                        </li>

                                        <li class="nav-item">
                                            <a wire:click="SelectTab('sales')"
                                                class="nav-link @if ($activeTab == 'sales') active @endif"
                                                id="custom-tabs-three-sales-tab" data-toggle="pill"
                                                href="#custom-tabs-three-sales" role="tab"
                                                aria-controls="custom-tabs-three-sales" aria-selected="false">
                                                Sales
                                            </a>
                                        </li>


                                        <li class="nav-item">
                                            <a wire:click="SelectTab('fin')"
                                                class="nav-link @if ($activeTab == 'fin') active @endif"
                                                id="custom-tabs-three-fin-tab" data-toggle="pill"
                                                href="#custom-tabs-three-fin" role="tab"
                                                aria-controls="custom-tabs-three-fin" aria-selected="false">
                                                Finance

                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a wire:click="SelectTab('inv')"
                                                class="nav-link @if ($activeTab == 'inv') active @endif"
                                                id="custom-tabs-three-inv-tab" data-toggle="pill"
                                                href="#custom-tabs-three-inv" role="tab"
                                                aria-controls="custom-tabs-three-inv" aria-selected="false">
                                                Inventory

                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a wire:click="SelectTab('tax')"
                                                class="nav-link @if ($activeTab == 'tax') active @endif"
                                                id="custom-tabs-three-tax-tab" data-toggle="pill"
                                                href="#custom-tabs-three-tax" role="tab"
                                                aria-controls="custom-tabs-three-tax" aria-selected="false">
                                                Tax
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content text-sm" id="custom-tabs-three-tabContent">
                                        <div class="tab-pane fade @if ($activeTab == 'com') show active @endif"
                                            id="custom-tabs-three-com" role="tabpanel"
                                            aria-labelledby="custom-tabs-three-com-tab">
                                            @livewire('Option.OptionSettingsCompany', ['systemSetting' => $systemSetting])
                                        </div>
                                        <div class="tab-pane fade @if ($activeTab == 'gen') show active @endif"
                                            id="custom-tabs-three-gen" role="tabpanel"
                                            aria-labelledby="custom-tabs-three-gen-tab">
                                            @livewire('Option.OptionSettingsGeneral', ['systemSetting' => $systemSetting])
                                        </div>
                                        <div class="tab-pane fade @if ($activeTab == 'acct') show active @endif"
                                            id="custom-tabs-three-acct" role="tabpanel"
                                            aria-labelledby="custom-tabs-three-acct-tab">
                                            @livewire('Option.OptionSettingsAccounting', ['systemSetting' => $systemSetting])
                                        </div>
                                        <div class="tab-pane fade @if ($activeTab == 'sales') show active @endif"
                                            id="custom-tabs-three-sales" role="tabpanel"
                                            aria-labelledby="custom-tabs-three-sales-tab">
                                            @livewire('Option.OptionSettingsSales', ['systemSetting' => $systemSetting])
                                        
                                        </div>
                                        <div class="tab-pane fade @if ($activeTab == 'fin') show active @endif"
                                            id="custom-tabs-three-fin" role="tabpanel"
                                            aria-labelledby="custom-tabs-three-fin-tab">
                                            @livewire('Option.OptionSettingsFinance', ['systemSetting' => $systemSetting])
                                        </div>
                                        <div class="tab-pane fade @if ($activeTab == 'inv') show active @endif"
                                            id="custom-tabs-three-inv" role="tabpanel"
                                            aria-labelledby="custom-tabs-three-inv-tab">

                                            @livewire('Option.OptionSettingsInventory', ['systemSetting' => $systemSetting])
                                           
                                        </div>
                                        <div class="tab-pane fade @if ($activeTab == 'tax') show active @endif"
                                            id="custom-tabs-three-tax" role="tabpanel"
                                            aria-labelledby="custom-tabs-three-tax-tab">
                                            @livewire('Option.OptionSettingsTax', ['systemSetting' => $systemSetting])
                                        </div>

                                    </div>
                                </div>

                                <!-- /.card -->
                            </div>
                        </div>




                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
