@php $layout = 'layouts.super_user_layout'; @endphp
@auth
    @php
    if (Auth::user()->user_level_id == 1) {
        $layout = 'layouts.super_user_layout';
    } elseif (Auth::user()->user_level_id == 2) {
        $layout = 'layouts.admin_layout';
    } elseif (Auth::user()->user_level_id == 3) {
        $layout = 'layouts.user_layout';
    }
    @endphp
@endauth

@extends($layout)

@section('title', 'Dashboard')
@section('content_page')
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <h2 class="my-3">Dashboard</h2>
                    <div class="col-xl-4 col-lg-4">
                        <div class="info-box shadow bg-white rounded">
                            <span class="info-box-icon bg-warning"><i class="fa-solid fa-wrench fa-beat-fade"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Pending Requests</span>
                                <span class="info-box-number" id="totalPendingRequest">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4">
                        <div class="info-box shadow bg-white rounded">
                            <span class="info-box-icon bg-success"><i class="fa-solid fa-file-circle-check fa-beat-fade"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Completed Requests</span>
                                <span class="info-box-number" id="totalCompletedRequest">0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h2 class="my-3">Pendings</h2>
                    <div class="col-xl-4 col-lg-4">
                        <div class="info-box shadow bg-white rounded">
                            <span class="info-box-icon bg-warning">P1</span>
                            <div class="info-box-content">
                                <span class="info-box-text">Product Identification</span>
                                <span class="info-box-number" id="PendingProductIdentification">0</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4">
                        <div class="info-box shadow bg-white rounded">
                            <span class="info-box-icon bg-warning">P2-3</span>
                            <div class="info-box-content">
                                <span class="info-box-text">Dieset Condition Checking</span>
                                <span class="info-box-number" id="PendingDiesetConditionChecking">0</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4">
                        <div class="info-box shadow bg-white rounded">
                            <span class="info-box-icon bg-warning">P4</span>
                            <div class="info-box-content">
                                <span class="info-box-text">Machine Setup</span>
                                <span class="info-box-number" id="PendingMachineSetup">0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4">
                        <div class="info-box shadow bg-white rounded">
                            <span class="info-box-icon bg-warning">P5</span>
                            <div class="info-box-content">
                                <span class="info-box-text">Product Requirement Checking</span>
                                <span class="info-box-number" id="PendingProductRequirementChecking">0</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4">
                        <div class="info-box shadow bg-white rounded">
                            <span class="info-box-icon bg-warning">P6</span>
                            <div class="info-box-content">
                                <span class="info-box-text">Machine Parameter Checking</span>
                                <span class="info-box-number" id="PendingMachineParameterChecking">0</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4">
                        <div class="info-box shadow bg-white rounded">
                            <span class="info-box-icon bg-warning">P7</span>
                            <div class="info-box-content">
                                <span class="info-box-text">Specification</span>
                                <span class="info-box-number" id="PendingSpecification">0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4">
                        <div class="info-box shadow bg-white rounded">
                            <span class="info-box-icon bg-warning">P8</span>
                            <div class="info-box-content">
                                <span class="info-box-text">Completion Activity</span>
                                <span class="info-box-number" id="PendingCompletionActivity">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

<!--     {{-- JS CONTENT --}} -->
@section('js_content')
    <script type="text/javascript">
        $(document).ready(function(){
            function getDataForDashboard(){
                $.ajax({
                    url: "get_data_for_dashboard",
                    method: "get",
                    dataType: "json",
                    success: function(response){
                        console.log('response ', response['totalUsers']);
                        $('#totalPendingRequest').text(response['TotalPendingRequests']);
                        $('#totalCompletedRequest').text(response['TotalCompletedRequests']);

                        $('#PendingProductIdentification').text(response['ProductIdentification']);
                        $('#PendingDiesetConditionChecking').text(response['DiesetConditionChecking']);
                        $('#PendingMachineSetup').text(response['MachineSetup']);
                        $('#PendingProductRequirementChecking').text(response['ProductRequirementChecking']);
                        $('#PendingMachineParameterChecking').text(response['MachineParameterChecking']);
                        $('#PendingSpecification').text(response['Specification']);
                        $('#PendingCompletionActivity').text(response['CompletionActivity']);
                    },
                });
            }
            getDataForDashboard(); //comment for now
        });
    </script>
@endsection
