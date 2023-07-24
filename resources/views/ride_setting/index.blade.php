@extends('layouts.app')
@section('content')
<div class="page-wrapper">
   <div class="row page-titles">
      <div class="col-md-5 align-self-center">
         <h3 class="text-themecolor">Ride Setting</h3>
      </div>
      <div class="col-md-7 align-self-center">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href= "{{ url('vehicle-rental-type/index') }}" >Ride Setting</a></li>
            <li class="breadcrumb-item active">Create Ride Setting</li>
         </ol>
      </div>
   </div>
   <div class="container-fluid">
      <div class="row">
         <div class="col-12">
            <div class="card pb-4">
               <div class="card-body">
                  <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">{{trans('lang.processing')}}</div>
                  <div class="error_top"></div>
                  @if($errors->any())
                  <div class="alert alert-danger">
                     <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                     </ul>
                  </div>
                  @endif
                  <form action="{{route('ride_setting_save')}}" method="post" enctype="multipart/form-data">
                     @csrf
                     <div class="row restaurant_payout_create">
                        <div class="restaurant_payout_create-inner">
                           <fieldset>
                              <legend>Ride Setting</legend>
                              <div class="form-group row width-50">
                                 <label class="col-3 control-label">Per Token Price</label>
                                 <div class="col-7">
                                    <input type="text" class="form-control"  name="token_price" value='{{ $setting->token_price ?? '1'}}'>
                                    <!-- <div class="form-text text-muted"></div> -->
                                 </div>
                              </div>
                              <div class="form-group row width-50">
                                 <label class="col-3 control-label">Per Ride Customize Token</label>
                                 <div class="col-7">
                                    <input type="text" class="form-control" name="ride_token"  value='{{ $setting->ride_token ?? '1'}}'>
                                 </div>
                              </div>
                              <div class="form-group row width-50">
                                 <label class="col-3 control-label">More Than 4 Passenger Fare</label>
                                 <div class="col-7">
                                    <input type="text" class="form-control" name="passenger_more" value='{{ $setting->passenger_more ?? '1'}}'>
                                 </div>
                              </div>
                              <div class="form-group row width-50">
                                 <label class="col-3 control-label">More Than One luggage Fare</label>
                                 <div class="col-7">
                                    <input type="text" class="form-control" name="luggage_more" value='{{ $setting->luggage_more ?? '1' }}'>
                                 </div>
                              </div>
                              <div class="form-group row width-50">
                                <label class="col-3 control-label">More Than One Pet Fare</label>
                                <div class="col-7">
                                   <input type="text" class="form-control" name="pet_more" value='{{ $setting->pet_more ?? '1'}}'>
                                </div>
                             </div>
                             <div class="form-group row width-50">
                                <label class="col-3 control-label">More Than One Package Fare</label>
                                <div class="col-7">
                                   <input type="text" class="form-control" name="package_more"  value='{{ $setting->package_more ?? '1'}}'>
                                </div>
                             </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">Coupon Award Token</label>
                                <div class="col-7">
                                   <input type="text" class="form-control" name="gift_token"  value='{{ $setting->gift_token ?? '0'}}'>
                                </div>
                             </div>
                              {{-- <div class="form-group row width-50">
                                 <div class="form-check">
                                    <input type="checkbox" class="user_active" id="user_active" name="status">
                                    <label class="col-3 control-label" for="user_active">{{trans('lang.active')}}</label>
                                 </div>
                              </div> --}}
                        </div>
                        </fieldset>
                     </div>
                     <div class="form-group col-12 text-center btm-btn" >
                        <button type="submit" class="btn btn-primary  save_user_btn" ><i class="fa fa-save"></i>{{ trans('lang.save')}}</button>
                        <a href="{{ url('vehicle-rental-type/index') }}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel')}}</a>
                     </div>
               </div>
            </div>
         </div>
         </form>
      </div>
   </div>
</div>
</div>
@endsection
@section('scripts')
@endsection
