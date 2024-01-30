@extends('layouts.app')

@section('content')
<div class="row">
       <div class="col-12 col-md-10 offset-md-1 bg-white card-rounded d-inline-block">
        
             <!-- Start Farm field  -->
                <div class="row mt-3 ms-2 mb-3">
                        <div class="col-10">
                            <h4 class="farm-field">Farm Field</h4> 
                        </div>
                    <!-- end of farm-field -->
                    
                    <!-- Dropdown menu -->
                    <div class="col-2">
                        <button type="button" class="btn bg-light dropdown-toggle" data-bs-toggle="dropdown">
                            Select data
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Daily</a></li>
                            <li><a class="dropdown-item" href="#">Weekly</a></li>
                            <li><a class="dropdown-item" href="#">Monthly</a></li>
                            <li><a class="dropdown-item" href="#">Yearly</a></li>
                        </ul>
                    </div>
            
                    <!-- end if Drop menu -->
                
                 <!-- Temperature & Moisture level -->
                    <div class="row">
                        <div class="col-sm"><!-- spacing --></div>
                        <div class="col-sm d-flex flex-row"><div class="rounded-circle bg-red mt-2 me-1" style="height: 5px; width: 5px; background-color: red;"></div><p class="h6">Soil temperature</p></div>
                        <div class="col-sm d-flex flex-row"><div class="rounded-circle bg-warning mt-2 me-1" style="height: 5px; width: 5px;"></div><p class="h6">Moisture level 2</p></div>
                        <div class="col-sm d-flex flex-row"><div class="rounded-circle bg-info mt-2 me-1" style="height: 5px; width: 5px;"></div><p class="h6">Moisture level 1</p></div>
                    </div>
                    <!-- end of temperature & moisture level -->
                        
                    <!-- Soil, Outdoor & Rainfall section -->
                    <div class="row mt-2">
                        
                        <!-- Soil -->
                        <div class="col border card-rounded p-1 m-1">
                            <!-- Soil image -->
                            <div class="row">
                                <div class="col-3">
                                    <img src="{{URL::asset('/img/profile-mandeep/Soil.svg')}}" alt="Soil Pic" height="50" width="50">
                                </div>
                                <div class="col-6">
                                    <p class="mt-2 fs-4">Soil</p>
                                </div>
                            </div>
                            <!-- End of Soil image -->
                            
                            
                            <!-- Top temperature measure -->
                            <div class="row">
                                <div class="col">
                                    <p class="d-flex justify-content-start mt-1 ms-3 text-muted">32%</p> 
                                </div>
                                <div class="col">
                                    <p class="d-flex justify-content-end mt-1 me-2 h5"><strong>24c</strong></p> 
                                </div>
                                <div class="col">
                                    <p class="d-flex justify-content-end mt-1 me-2 h5"><strong>23c</strong></p> 
                                </div>
                            </div>
                            <!-- End of top temperature measure -->
                            <div class="d-flex justify-content-end">
                                <img src="{{URL::asset('/img/profile-mandeep/Temprature_measuring_dvc.svg')}}" alt="Tmp-device Pic" height="150" width="80">
                            </div>

                            <!-- Bottom temperature measure -->
                            <div class="row">
                                <div class="col">
                                    <p class="d-flex justify-content-start mt-1 ms-2 text-muted">32%</p> 
                                </div>
                                <div class="col">
                                    <p class="d-flex justify-content-end mt-1 me-2 h5"><strong>24c</strong></p> 
                                </div>
                                <div class="col">
                                    <p class="d-flex justify-content-end mt-1 me-2 h5"><strong>23c</strong></p> 
                                </div>
                            </div>
                            <!-- End of bottom temperature measure -->
                            
                            </div>
                            <!--End of Soil -->
                    
                    <!-- Outdoor -->
                        <div class="col border card-rounded p-1 m-1">
                            
                            <!-- Outdoor Image -->
                            <div class="row">
                                <div class="col-3">
                                    <img src="{{URL::asset('/img/profile-mandeep/Soil.svg')}}" alt="Outdoor Pic" height="50" width="50">
                                </div>
                                <div class="col-6">
                                    <p class="mt-2 fs-4">Outdoor</p>
                                </div>
                            </div>
                            <!-- End of Outdoor Image -->
                            <p class="d-flex justify-content-start mt-1 ms-2 text-muted">Temperature</p>
                            <p class="d-flex justify-content-end mt-1 me-2 h5"><strong>26</strong><span class="text-success h6 mt-1">℃</span></p>
                            <p class="d-flex justify-content-start mt-1 ms-2 text-muted">Humidity</p>
                            <p class="d-flex justify-content-end mt-1 me-2 h5"><strong>38</strong><span class="text-success h6 mt-1">%</span></p>
                        </div>
                    <!--End of Outdoor -->

                    <!-- Rainfall -->
                    <div class="col border card-rounded p-1 m-1">
                        <!-- Rainfall Image -->
                        <div class="row">
                                <div class="col-3 mt-2 ms-1">
                                    <img src="{{URL::asset('/img/profile-mandeep/Rainfall.svg')}}" alt="Rainfall Pic" height="40" width="40">
                                </div>
                                <div class="col-6">
                                    <p class="mt-2 fs-4">Rainfall</p>
                                </div>
                            </div>
                            <!-- End of Rainfall Image -->
                    
                        <div class="row">
                            <div class="col-6">
                                <p class="d-flex justify-content-start mt-1 ms-2 text-muted">Daily</p> 
                            </div>
                            <div class="col-6">
                                <p class="d-flex justify-content-end mt-1 me-2 h5"><strong>0.0</strong></p> 
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <p class="d-flex justify-content-start mt-1 ms-2 text-muted">Weekly</p> 
                            </div>
                            <div class="col-6">
                                <p class="d-flex justify-content-end mt-1 me-2 h5"><strong>36.0</strong></p> 
                            </div>
                        </div>
                    
                        <div class="row">
                            <div class="col-6">
                                <p class="d-flex justify-content-start mt-1 ms-2 text-muted">Monthly</p> 
                            </div>
                            <div class="col-6">
                                <p class="d-flex justify-content-end mt-1 me-2 h5"><strong>87.0</strong></p> 
                            </div>
                        </div>
                        
                    </div>
                    <!-- End of Rainfall -->
                </div>
            <!-- End of Soil, Outdoor & Rainfall section -->
         </div>
       <!-- End of Farm Field -->
    </div>
    <!-- end og coloum-12 -->
</div>




@endsection