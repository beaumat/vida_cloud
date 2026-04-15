<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <h5 class="m-0"> <a href="{{ route('maintenancecontactcustomer') }}"> Customer </a></h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-md-12">
                    <div class="card card-sm">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <h3 class="card-title"> {{ $ID === 0 ? 'Create' : 'Edit' }}</h3>
                        </div>
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body bg-light">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <livewire:text-input name="NAME" titleName="Name" wire:model='NAME'
                                                maxlength='80' isDisabled="{{ false }}" />
                                        </div>
                                        <div class="col-md-4">
                                            <livewire:text-input name="ACCOUNT_NO" titleName="Account No."
                                                isDisabled="{{ false }}" wire:model='ACCOUNT_NO' />
                                        </div>
                                        <div class="col-nd-2">
                                            <div class="row">
                                                <div class="col-md-12"><br /></div>
                                                <div class="col-md-12 text-right">
                                                    <livewire:custom-check-box name="INACTIVE" titleName="Inactive"
                                                        isDisabled="{{ false }}" wire:model='INACTIVE' />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card text-xs card-tabs mt-2">
                                    <div class="card-header p-0 pt-1 border-bottom-0">
                                        <ul class="nav nav-tabs p-1" id="custom-content-below-tab"
                                            role="tablist">
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('gen')"
                                                    class="nav-link @if ($selectTab == 'gen') active @endif"
                                                    id="custom-content-below-general-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-general-info" role="tab"
                                                    aria-controls="custom-content-below-general-info"
                                                    aria-selected="true">General Info</a>
                                            </li>
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('tax')"
                                                    class="nav-link @if ($selectTab == 'tax') active @endif"
                                                    id="custom-content-below-tax-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-tax-info" role="tab"
                                                    aria-controls="custom-content-below-tax-info"
                                                    aria-selected="false">Tax
                                                    Info</a>
                                            </li>
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('add')"
                                                    class="nav-link @if ($selectTab == 'add') active @endif"
                                                    id="custom-content-below-add-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-add-info" role="tab"
                                                    aria-controls="custom-content-below-add-info" aria-selected="false">
                                                    Addional Info
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('sales-receipt')"
                                                    class="nav-link @if ($selectTab == 'sales-receipt') active @endif"
                                                    id="custom-content-below-sales-receipt-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-sales-receipt-info" role="tab"
                                                    aria-controls="custom-content-below-sales-receipt-info"
                                                    aria-selected="false">
                                                    Sales Receipts
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('invoice')"
                                                    class="nav-link @if ($selectTab == 'invoice') active @endif"
                                                    id="custom-content-below-invoice-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-invoice-info" role="tab"
                                                    aria-controls="custom-content-below-invoice-info"
                                                    aria-selected="false">
                                                    Invoices
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('payment')"
                                                    class="nav-link @if ($selectTab == 'payment') active @endif"
                                                    id="custom-content-below-payment-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-payment-info" role="tab"
                                                    aria-controls="custom-content-below-payment-info"
                                                    aria-selected="false">
                                                    Payments
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('tax-credit')"
                                                    class="nav-link @if ($selectTab == 'tax-credit') active @endif"
                                                    id="custom-content-below-tax-credit-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-tax-credit-info" role="tab"
                                                    aria-controls="custom-content-below-tax-credit-info"
                                                    aria-selected="false">
                                                    Tax Credits
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body bg-light">
                                        <div class="tab-content text-sm" id="custom-content-below-tabContent">
                                            <div class="tab-pane fade @if ($selectTab == 'gen') show active @endif"
                                                id="custom-content-below-general-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-general-info-tab">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <livewire:text-input name="NAME"
                                                                titleName="Company Name" maxlength='80'
                                                                isDisabled="{{ false }}"
                                                                wire:model='COMPANY_NAME' />
                                                        </div>
                                                        <div class="col-md-2">

                                                            <div class="mt-2">
                                                                <label for="title" class="text-sm">Title</label>
                                                                <select wire:model='SALUTATION'
                                                                    class="form-control form-control-sm"
                                                                    name="SALUTATION">
                                                                    <option value=""></option>
                                                                    <option value="Dr">Dr</option>
                                                                    <option value="Miss">Miss</option>
                                                                    <option value="Mr.">Mr.</option>
                                                                    <option value="Mr.">Ms.</option>
                                                                    <option value="Mr.">Prof</option>
                                                                </select>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-4">
                                                            <livewire:text-input name="FIRST_NAME" maxlength='80'
                                                                isDisabled="{{ false }}"
                                                                titleName="First Name" wire:model='FIRST_NAME' />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <livewire:text-input name="MIDDLE_NAME" titleName="M.I"
                                                                isDisabled="{{ false }}"
                                                                wire:model='MIDDLE_NAME' />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <livewire:text-input name="LAST_NAME" maxlength='80'
                                                                isDisabled="{{ false }}" titleName="Last Name"
                                                                wire:model='LAST_NAME' />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <livewire:text-input name="PRINT_NAME_AS" maxlength='80'
                                                                isDisabled="{{ false }}" titleName="Print As"
                                                                wire:model='PRINT_NAME_AS' />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="mt-2">
                                                                                <label for="postal-address"
                                                                                    class="text-sm">Postal
                                                                                    Address</label>
                                                                                <textarea type="text" autocomplete="off" wire:model='POSTAL_ADDRESS' class="text-sm form-control form-control-sm"
                                                                                    id="pos_tal_address" rows="7"></textarea>
                                                                            </div>

                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <livewire:text-input name="EMAIL"
                                                                                isDisabled="{{ false }}"
                                                                                titleName="Email"
                                                                                wire:model='EMAIL' />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <livewire:text-input name="CONTACT_PERSON"
                                                                                maxlength='80'
                                                                                isDisabled="{{ false }}"
                                                                                titleName="Contact Person"
                                                                                wire:model='CONTACT_PERSON' />
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <livewire:text-input name="TELEPHONE_NO"
                                                                                isDisabled="{{ false }}"
                                                                                titleName="Telephone Number"
                                                                                wire:model='TELEPHONE_NO' />
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <livewire:text-input name="FAX_NO"
                                                                                isDisabled="{{ false }}"
                                                                                titleName="Fax Number"
                                                                                wire:model='FAX_NO' />

                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <livewire:text-input name="MOBILE_NO"
                                                                                isDisabled="{{ false }}"
                                                                                titleName="Mobile Number"
                                                                                wire:model='MOBILE_NO' />

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="tab-pane fade @if ($selectTab == 'tax') show active @endif"
                                                id="custom-content-below-tax-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-tax-info-tab">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <livewire:text-input name="TAXPAYER_ID"
                                                                isDisabled="{{ false }}"
                                                                titleName="Taxpayer ID No."
                                                                wire:model='TAXPAYER_ID' />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <livewire:select-option name="TAX_ID" :options="$taxList"
                                                                isDisabled="{{ false }}" :zero="true"
                                                                titleName="Output Tax" wire:model='TAX_ID'
                                                                :key="$taxList->pluck('ID')->join('_')" />

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade @if ($selectTab == 'add') show active @endif"
                                                id="custom-content-below-add-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-add-info-tab">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <livewire:select-option name="GROUP_ID" :options="$contactGroup"
                                                                isDisabled="{{ false }}" :zero="true"
                                                                titleName="Group" wire:model='GROUP_ID' />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <livewire:select-option name="SALES_REP_ID"
                                                                isDisabled="{{ false }}" :options="$salesMan"
                                                                :zero="true" titleName="Salesman"
                                                                wire:model='SALES_REP_ID' :key="$salesMan->pluck('ID')->join('_')" />
                                                        </div>


                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <livewire:select-option name="PAYMENT_TERMS_ID"
                                                                :options="$paymentTermList" :zero="true"
                                                                isDisabled="{{ false }}"
                                                                titleName="Payment Terms"
                                                                wire:model='PAYMENT_TERMS_ID' :key="$paymentTermList->pluck('ID')->join('_')" />
                                                        </div>

                                                        <div class="col-md-4">
                                                            <livewire:number-input name="CREDIT_LIMIT"
                                                                isDisabled="{{ false }}"
                                                                titleName="Credit Limit" wire:model='CREDIT_LIMIT' />
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <livewire:select-option name="PREF_PAYMENT_METHOD_ID"
                                                                :options="$paymentMethod" :zero="true"
                                                                titleName="Payment Method"
                                                                isDisabled="{{ false }}"
                                                                wire:model='PREF_PAYMENT_METHOD_ID'
                                                                :key="$paymentMethod->pluck('ID')->join('_')" />
                                                        </div>

                                                        <div class="col-md-2">
                                                            <livewire:text-input name="CREDIT_CARD_NO"
                                                                titleName="Credit Card No"
                                                                isDisabled="{{ false }}"
                                                                wire:model='CREDIT_CARD_NO' />
                                                        </div>

                                                        <div class="col-md-2">
                                                            <livewire:date-input name="CREDIT_CARD_EXPIRY_DATE"
                                                                titleName="Expiry Date"
                                                                isDisabled="{{ false }}"
                                                                wire:model='CREDIT_CARD_EXPIRY_DATE' />

                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <livewire:select-option name="PRICE_LEVEL_ID"
                                                                :options="$priceLevels" :zero="true"
                                                                isDisabled="{{ false }}"
                                                                titleName="Price Level" wire:model='PRICE_LEVEL_ID' />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade @if ($selectTab == 'sales-receipt') show active @endif"
                                                id="custom-content-below-sales-receipt-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-sales-receipt-info-tab">
                                                  @livewire('Customer.CustomerSalesReceipt', ['id' => $ID])
                                            </div>
                                            <div class="tab-pane fade @if ($selectTab == 'invoice') show active @endif"
                                                id="custom-content-below-invoice-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-invoice-info-tab">                                    
                                                    @livewire('Customer.CustomerInvoice', ['id' => $ID])                                     
                                            </div>
                                            <div class="tab-pane fade @if ($selectTab == 'payment') show active @endif"
                                                id="custom-content-below-payment-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-payment-info-tab">
                                                @livewire('Customer.CustomerPayments', ['id' => $ID])
                                            </div>
                                            <div class="tab-pane fade @if ($selectTab == 'tax-credit') show active @endif"
                                                id="custom-content-below-tax-credit-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-tax-credit-info-tab">
                                                 @livewire('Customer.CustomerTaxCredit', ['id' => $ID])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-6">
                                            <button type="submit"
                                                class="btn btn-sm btn-success">{{ $ID === 0 ? 'Save' : 'Update' }}</button>
                                        </div>
                                        <div class="text-right col-6 col-md-6">
                                            @if ($ID > 0)
                                                <a id="new" title="Create"
                                                    href="{{ route('maintenancecontactcustomer_create') }}"
                                                    class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i></a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
