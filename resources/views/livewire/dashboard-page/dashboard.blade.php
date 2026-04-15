<?php
use App\Services\UserServices;
use App\Services\ModeServices;

?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h4 class="m-0"><a href="{{ route('dashboard') }}"> Dashboard </a></h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            {{ config('custom.site_name') }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if (ModeServices::GET() == 'H')
                @if (UserServices::GetUserRightAccess('tracking-branches'))
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-md-3">
                            @livewire('DashBoardPage.PatientStatus')
                        </div>
                        <div class="col-md-3">
                            @livewire('DashBoardPage.TreatmentSummaryStatus')
                        </div>
                        <div class="col-md-3">
                            @livewire('DashBoardPage.PhilhealthStatus')
                        </div>
                        <div class="col-md-3">
                            @livewire('DashBoardPage.DoctorStatus')
                        </div>
                        <div class="col-md-4">
                            @livewire('DashBoardPage.SalesCollection')
                        </div>

                        <div class="col-md-4">
                            @livewire('DashBoardPage.ReceivableStatus')
                        </div>
                        <div class="col-md-4">
                            @livewire('DashBoardPage.PayableStatus')
                        </div>
                        @can('previous-operation-tracking')
                            <div class="col-md-3">
                                @livewire('DashBoardPage.PreviousOperation')
                            </div>
                        @endcan
                    </div>
                @endif
            @endif
        </div>
    </section>
</div>
