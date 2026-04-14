 <div class="row">
     <div class="col-md-4">
         @livewire('Patient.AssistanceRecordSum', ['CONTACT_ID' => $CONTACT_ID, 'LOCK_LOCATION_ID' => $LOCK_LOCATION_ID])
     </div>
     <div class="col-md-8">
         <div class="card card-primary card-outline card-outline-tabs">
             <div class="card-header p-0 border-bottom-0" wire:loading.class='loading-form'>
                 <ul class="nav text-xs nav-tabs" id="custom-tabs-four-tab" role="tablist">
                     <li class="nav-item">
                         <a class="nav-link @if ($tab == 'dswd') active @endif"
                             id="custom-tabs-four-dswd-tab" wire:click="SelectTab('dswd')" data-toggle="pill"
                             href="#custom-tabs-four-dswd" role="tab" aria-controls="custom-tabs-four-dswd"
                             aria-selected="true">
                             DSWD
                         </a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link @if ($tab == 'pcso') active @endif"
                             id="custom-tabs-four-pcso-tab" wire:click="SelectTab('pcso')" data-toggle="pill"
                             href="#custom-tabs-four-pcso" role="tab" aria-controls="custom-tabs-four-pcso"
                             aria-selected="true">
                             PCSO
                         </a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link @if ($tab == 'lingap') active @endif"
                             id="custom-tabs-four-lingap-tab" wire:click="SelectTab('lingap')" data-toggle="pill"
                             href="#custom-tabs-four-lingap" role="tab" aria-controls="custom-tabs-four-lingap"
                             aria-selected="true">
                             LINGAP
                         </a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link @if ($tab == 'op') active @endif"
                             id="custom-tabs-four-op-tab" wire:click="SelectTab('op')" data-toggle="pill"
                             href="#custom-tabs-four-op" role="tab" aria-controls="custom-tabs-four-op"
                             aria-selected="true">
                             OP
                         </a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link @if ($tab == 'ovp') active @endif"
                             id="custom-tabs-four-ovp-tab" wire:click="SelectTab('ovp')" data-toggle="pill"
                             href="#custom-tabs-four-ovp" role="tab" aria-controls="custom-tabs-four-ovp"
                             aria-selected="true">
                             OVP
                         </a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link @if ($tab == 'others') active @endif"
                             id="custom-tabs-four-others-tab" wire:click="SelectTab('others')" data-toggle="pill"
                             href="#custom-tabs-four-others" role="tab" aria-controls="custom-tabs-four-others"
                             aria-selected="true">
                             OTHERS
                         </a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link @if ($tab == 'balance') active @endif"
                             id="custom-tabs-four-balance-tab" wire:click="SelectTab('balance')" data-toggle="pill"
                             href="#custom-tabs-four-balance" role="tab" aria-controls="custom-tabs-four-balance"
                             aria-selected="true">
                             Balance
                         </a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link @if ($tab == 'cash') active @endif"
                             id="custom-tabs-four-cash-tab" wire:click="SelectTab('cash')" data-toggle="pill"
                             href="#custom-tabs-four-cash" role="tab" aria-controls="custom-tabs-four-cash"
                             aria-selected="true">
                             Cash
                         </a>
                     </li>
                     <li wire:loading.delay>
                         <span class="spinner"></span>
                     </li>
                 </ul>
             </div>
             <div class="card-body">
                 <div class="tab-content" id="custom-tabs-four-tabContent">
                     <div class="tab-pane fade @if ($tab == 'dswd') show active @endif"
                         id="custom-tabs-four-dswd" role="tabpanel" aria-labelledby="custom-tabs-four-dswd-tab">
                         @if ($tab == 'dswd')
                             @livewire('Patient.AssistanceRecordDswd', ['CONTACT_ID' => $CONTACT_ID, 'LOCK_LOCATION_ID' => $LOCK_LOCATION_ID])
                         @endif
                     </div>
                     <div class="tab-pane fade @if ($tab == 'pcso') show active @endif"
                         id="custom-tabs-four-pcso" role="tabpanel" aria-labelledby="custom-tabs-four-pcso-tab">
                         @if ($tab == 'pcso')
                             @livewire('Patient.AssistanceRecordPcso', ['CONTACT_ID' => $CONTACT_ID, 'LOCK_LOCATION_ID' => $LOCK_LOCATION_ID])
                         @endif
                     </div>
                     <div class="tab-pane fade @if ($tab == 'lingap') show active @endif"
                         id="custom-tabs-four-lingap" role="tabpanel" aria-labelledby="custom-tabs-four-lingap-tab">
                         @if ($tab == 'lingap')
                             @livewire('Patient.AssistanceRecordLingap', ['CONTACT_ID' => $CONTACT_ID, 'LOCK_LOCATION_ID' => $LOCK_LOCATION_ID])
                         @endif
                     </div>
                     <div class="tab-pane fade @if ($tab == 'op') show active @endif"
                         id="custom-tabs-four-op" role="tabpanel" aria-labelledby="custom-tabs-four-op-tab">
                         @if ($tab == 'op')
                             @livewire('Patient.AssistanceRecordOp', ['CONTACT_ID' => $CONTACT_ID, 'LOCK_LOCATION_ID' => $LOCK_LOCATION_ID])
                         @endif
                     </div>
                     <div class="tab-pane fade @if ($tab == 'ovp') show active @endif"
                         id="custom-tabs-four-ovp" role="tabpanel" aria-labelledby="custom-tabs-four-ovp-tab">
                         @if ($tab == 'ovp')
                             @livewire('Patient.AssistanceRecordOvp', ['CONTACT_ID' => $CONTACT_ID, 'LOCK_LOCATION_ID' => $LOCK_LOCATION_ID])
                         @endif
                     </div>
                     <div class="tab-pane fade @if ($tab == 'others') show active @endif"
                         id="custom-tabs-four-others" role="tabpanel" aria-labelledby="custom-tabs-four-others-tab">
                         @if ($tab == 'others')
                             @livewire('Patient.AssistanceRecordOthers', ['CONTACT_ID' => $CONTACT_ID, 'LOCK_LOCATION_ID' => $LOCK_LOCATION_ID])
                         @endif
                     </div>
                     <div class="tab-pane fade @if ($tab == 'balance') show active @endif"
                         id="custom-tabs-four-balance" role="tabpanel"
                         aria-labelledby="custom-tabs-four-balance-tab">
                         @if ($tab == 'balance')
                             @livewire('Patient.AssistanceRecordBalance', ['CONTACT_ID' => $CONTACT_ID, 'LOCK_LOCATION_ID' => $LOCK_LOCATION_ID])
                         @endif
                     </div>
                     <div class="tab-pane fade @if ($tab == 'cash') show active @endif"
                         id="custom-tabs-four-cash" role="tabpanel" aria-labelledby="custom-tabs-four-cash-tab">
                         @if ($tab == 'cash')
                             @livewire('Patient.AssistanceRecordCash', ['CONTACT_ID' => $CONTACT_ID, 'LOCK_LOCATION_ID' => $LOCK_LOCATION_ID])
                         @endif
                     </div>


                 </div>
             </div>

         </div>
     </div>
 </div>
