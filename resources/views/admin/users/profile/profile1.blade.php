@extends('admin.layouts.app')
@section('title')
| Profile
@endsection
@push('css_lib')
<style>

  #datepicker input {
    border: 1px solid #9c9c9c;
    margin-right: 5px;
    border-radius: 4px;
    padding: 2px 0px;
    text-align: center;
  }

  .distance_100 {
    width: 100% !important;
    height: 100% !important;
    position: relative;
  }
  .bg_red {
    background: #EA2027 !important;
  }
  .bg_yellow {
    background: #F79F1F !important;
  }
  .text_white {
    color: #fff !important;
  }
  .tr_bg_gray {
    background: #eee !important;
  }
  .bg_green {
    background: #009432 !important;
  }
  .bg_gray {
    background: #718093 !important;
  }
  .bg_blue {
    background: #0652DD !important;
  }
  .bg_melody {
    background: #f8c291 !important;
  }
  .nav-tabs-custom>.nav-tabs>li.active {
    border-top-color: #3c8dbc !important;
    background-color: #ffffff !important;
    color: #fff !important;
  }

  .nav-tabs-custom>.nav-tabs>li.active>a,
  .nav-tabs-custom>.nav-tabs>li.active:hover>a {
    background-color: transparent !important;
    color: #222 !important;
  }

  .nav-tabs-custom>.nav-tabs>li {
    background-color: #0073b7;
    color: #fff;
    border-radius: 3px;
    /* padding: 10px 20px; */
  }

  .nav-tabs-custom>.nav-tabs>li>a {
    color: #fff !important;
  }

  .nav>li>a {
    position: relative;
    display: block;
    padding: 8px 20px;
  }

  .pic_bg{
    background:url('{{ asset('backend/images/bg-ice.jpg') }}') center center no-repeat;
    width: 140px;
    height: 140px;
    background-size: cover;
    border-radius: 50%;
    margin: auto;
    position: relative;
    min-height: 200px;
  }
  .pic_bg .profile_pic {
    position: absolute;
    height: 120px;
    width: 120px;
    background-color: #ffffffbf;
    border-radius: 50%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  .pic_bg .profile_pic img{
    border-radius: 50%;
    height: 100px;
    width: 100px;
    top: 50%;
    left: 50%;
    position: absolute;
    transform: translate(-50%, -50%);
  }
  .profile_infos{
    padding: 0px 0px 10px 0px;
    border-radius: 5px;
    text-align: center;
  }

  .profile_infos h3{
    margin: 0px;
    /*margin-bottom: 5px;*/
    /*border-bottom: 1.5px solid #fff;*/
    padding-bottom: 5px;
    background-color: #22222252;
    border-radius: 5px 5px 0px 0px;
    padding: 8px;
    margin-bottom: 5px;
    font-size: 18px;
  }

  .profile_infos span{
    font-size: 16px;
  }

  .header_padding{
    padding: 12px 10px;
  }
  .avatar {
    vertical-align: middle;
    width: 50px;
    height: 50px;
    border-radius: 50%;
  }

  .mb-30{margin-bottom: 30px;}
  .bg-light-green {
    background-color: #4caf50;
    color: #fff;
  }
</style>

@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Profile Info</h1>
</section>

@php
use Carbon\Carbon;
$profile = $user->profile;
@endphp

<!-- Main content -->
<section class="content">
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title" style="display: block;">{{$user->name}}<strong class="text-maroon"> @if($user->id) (EMP-{{$user->id}})@endif</strong>
        @if($user->id == Auth::user()->id )
        <button type="button" class="btn bg-purple btn-xs pull-right" data-toggle="modal" data-target="#editProfileModal">
          <i class="fa fa-pencil"></i>
        </button>
        <!--a href="#editProfileModal" data-toggle="tooltip" data-route="{{route('users.edit',$user->id)}}" data-id="{{$user->id}}" data-original-title="Edit" class="btn bg-purple btn-xs edit editData pull-right"><i class="fa fa-pencil"></i></a-->
        @endif
      </h3>
    </div>
    <div class="box-body">
      <div class="row mb-30">
        <div class="col-md-2">
          <div class="pic_bg" >
            <div class="profile_pic">
              <a href="#" data-toggle="modal" data-target="#modalProfile" title="Update Your Profile"><img src="{{ URL::asset(Auth::user()->profile ?Auth::user()->profile->image : 'backend/images/avatar.jpg' ) }}" alt="Avatar" class="avatar" style="margin-right: 20px;"></a>
            </div>
          </div>
        </div>
        <div class="col-md-10">
          <div class="row" style="vertical-align: middle; margin-top: 30px;">
            <div class="col-md-3 mb-30" >
              <div style="min-height:74px;" class="bg-blue profile_infos">
                <h3>Name</h3> <span>{{$user->name}}</span>
              </div>
            </div>
            <div class="col-md-3 mb-30" >
              <div style="min-height:74px;" class="bg-maroon profile_infos">
                <h3>E-Mail Address</h3> <span>{{$user->email}}</span>
              </div>
            </div>
{{--            <div class="col-md-3 mb-30" >--}}
{{--              <div style="min-height:74px;" class="bg-aqua profile_infos">--}}
{{--                <h3>Designation</h3> <span>{{$user->designation->name}}</span>--}}
{{--              </div>--}}
{{--            </div>--}}
          </div>
        </div>
      </div>
</div>
</div>
</section>

<!-- Modal -->
<div class="modal fade" id="modalProfile" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <form method="POST" enctype="multipart/form-data" id="upload_image_form" action="{{ route('users.profile-photo') }}">
        @csrf
        <input type="hidden" value="{{ $user->id }}" name="id" id="id">
        <div class="modal-header bg-green">
          <h5 class="modal-title" id="modelHeading">Update Profile Photo</h5>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="box-body">
                <div class="form-group">
                  <label for="preview">Profile Photo Preview : </label>
                  <label id="preview-error" class="error" for="preview"></label>
                  <img id="image_preview_container" src="{{ URL::asset(Auth::user()->profile ? Auth::user()->profile->image : 'backend/images/avatar.jpg' ) }}"
                  alt="preview image" style="max-height: 150px;">
                </div>
                <div class="form-group">
                  <label for="profile_photo">Profile Photo :</label>
                  <label id="profile_photo-error" class="error" for="Profile Photo"></label>
                  <input type="file" name="profile_photo" placeholder="Choose image" id="profile_photo">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-danger pull-left" data-dismiss="modal">Close</button>
          <button type="submit" id="saveBtn" class="btn btn-success btn-sm">Update Profile</button>
          <input type="hidden" value="{{ $user->id }}">
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editProfileModal" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <form role="form" action="{{route('profile.update')}}" method="post" data-submit="ajax">
    @csrf
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header bg-green">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Profile |
            <span>{{$user->name}}<strong class="text-maroon"> @if($user->id) (EMP-{{$user->id}})@endif</strong></span>
          </h4>
        </div>
        <div class="modal-body">
          <div class="row">

            <div class="col-md-6">
              <div class="form-group">
                <label for="user_name" class=" control-label">Name: <small style="color:red">*</small></label>
                <input type="text" class="form-control" name="full_name" id="full_name" placeholder="User Name" required value="{{$user->name}}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="email" class=" control-label">Email: <small style="color:red">*</small></label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" required value="{{$user->email}}">
              </div>
            </div>
            <div class="col-md-6 password_el">
              <div class="form-group">
                <label for="password" class="text-md-right">New Password:</label>
                <input id="password" type="password" class="form-control" name="password" placeholder="Minimum 6 character">
              </div>
            </div>
            <div class="col-md-6 password_el">
              <div class="form-group">
                <label for="confirm-password" class="text-md-right">Confirm New Password: </label>
                <input id="confirm-password" type="password" class="form-control" name="password_confirmation" placeholder="Minimum 6 character">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
          <button type="submit" name="submit" class="btn btn-success pull-right" id="saveBtn">Save</button>
        </div>
      </div>
    </div>
  </form>
</div>

@endsection

@push('js_lib')

@endpush

@push('js_custom')
<script>
  $(function(){
    // $(document).ready(function (e) {
            //Datepicker
            $('#datepicker').datepicker({
             autoclose: true,
             format: "yyyy-mm",
             viewMode: "months",
             minViewMode: "months",
           });

            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });

            $('#profile_photo').change(function(){
              let reader = new FileReader();
              reader.onload = (e) => {
                $('#image_preview_container').attr('src', e.target.result);
              }
              reader.readAsDataURL(this.files[0]);

            });

            // $('#upload_image_form').submit(function(e) {
            //     e.preventDefault();

            //     var formData = new FormData(this);

            //     $.ajax({
            //         type:'POST',
            //         url: "{{ url('users.profile-photo') }}",
            //         data: formData,
            //         cache:false,
            //         contentType: false,
            //         processData: false,
            //         success: (data) => {
            //             this.reset();
            //             alert('Image has been uploaded successfully');
            //         },
            //         error: function(data){
            //             console.log(data);
            //         }
            //     });
            // });
          });

          $(document).on('click', '#createNewUpazila', function (e) {
              // $('#createNewUpazila').click(function () {
              $('#saveBtn').val("create-upazila");
              $('#workstation_id').val('');
              $('#upazilaForm').trigger("reset");
              $('#modelHeading').html("Create New Upazila");
              $('#add_upazila').modal('show');
          });

          $(document).on('submit', '#return_form', function(e){
              e.preventDefault();
              if (confirm("Are You sure?")){
                  ajaxSubmit(this);
              }
          });

        </script>

        @endpush
