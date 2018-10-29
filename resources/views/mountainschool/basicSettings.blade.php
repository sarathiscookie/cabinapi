@extends('mountainschool.layouts.app')

@section('title', 'Cabin API - Mountain School: Basic Settings')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('tours.basicSetHeading')
                <small>@lang('tours.basicSetsmallHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/mountainschool/bookings"><i class="fa fa-dashboard"></i> @lang('tours.breadcrumbOne')</a></li>             
                <li class="fa fa-edit active">@lang('tours.breadcrumbbasicSet')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                @lang('tours.basicSetBoxHeading')
                            </h4>
                        </div>

                        @if (session('failure'))
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ session('failure') }}
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ session('success') }}
                            </div>
                        @endif

                        @php
                            if(isset($basicSettings)) {
                               $contactPerson = ($basicSettings->contact_person) ? $basicSettings->contact_person : '';
                               $guides        = ($basicSettings->no_guides) ? $basicSettings->no_guides : '';
                               $halfBoard     = ($basicSettings->half_board) ? $basicSettings->half_board : '';
                               $beds          = ($basicSettings->beds) ? $basicSettings->beds : '';
                               $dorms         = ($basicSettings->dorms) ? $basicSettings->dorms : '';
                               $sleeps        = ($basicSettings->sleeps) ? $basicSettings->sleeps : '';
                               $guest         = ($basicSettings->guests) ? $basicSettings->guests : '';
                            }
                            else {
                               $contactPerson = '';
                               $guides        = '';
                               $halfBoard     = '';
                               $beds          = '';
                               $dorms         = '';
                               $sleeps        = '';
                               $guest         = '';
                            }
                        @endphp
                        <form role="form" method="post" action="{{ route('mountainschool.updatebasicsettings') }}">

                            {{ csrf_field() }}

                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group {{ $errors->has('contact_person') ? ' has-error' : '' }}">
                                            <label>@lang('tours.lblContactPerson')<span class="required">*</span></label>
                                            <input type="text" class="form-control" id="contact_person" name="contact_person" placeholder="@lang('tours.lblContactPersonPH')" value="{{old('contact_person' ,$contactPerson)}}" maxlength="100">
                                            @if ($errors->has('contact_person'))
                                                <span class="help-block"><strong>{{ $errors->first('contact_person') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group {{ $errors->has('no_guides') ? ' has-error' : '' }}">
                                            <label>@lang('tours.lblNoGuides')</label>
                                            <select class="form-control" id="no_guides" name="no_guides">
                                                <option value="">@lang('tours.lblNoGuidesPH')</option>
                                                @for($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}" @if(old('no_guides', $guides) == $i) selected @endif>{{ $i }}</option>
                                                @endfor
                                            </select>

                                            @if ($errors->has('no_guides'))
                                                <span class="help-block"><strong>{{ $errors->first('no_guides') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group {{ $errors->has('guests') ? ' has-error' : '' }}">
                                            <label>@lang('mountainschool.lblNoOfGuests')<span class="required">*</span></label>
                                            <select class="form-control guestsInputCls" id="guests" name="guests">
                                                <option value="0">@lang('mountainschool.lblNoOfGuestsPH')</option>
                                                @for ($i=1; $i<=30; $i++)
                                                    <option value="{{$i}}" @if(old('guests', $guest) == $i) selected @endif>{{$i}} @lang('mountainschool.lblOptGuests')</option>
                                                @endfor
                                            </select>

                                            <span class="help-block"><strong>{{ $errors->first('guests') }}</strong></span>

                                        </div>
                                    </div>
                                </div>
                                <!-- /.row-one -->

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group {{ $errors->has('beds') ? ' has-error' : '' }}">
                                            <label>@lang('tours.beds') <span class="required">*</span></label>
                                            <select class="form-control" id="beds" name="beds">
                                                <option value="">@lang('tours.bedsPH')</option>
                                                @for($i = 1; $i <= 30; $i++)
                                                    <option value="{{ $i }}" @if(old('beds', $beds) == $i) selected @endif>{{ $i }}</option>
                                                @endfor
                                            </select>

                                            @if ($errors->has('beds'))
                                                <span class="help-block"><strong>{{ $errors->first('beds') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group {{ $errors->has('dorms') ? ' has-error' : '' }}">
                                            <label>@lang('tours.dorms') <span class="required">*</span></label>
                                            <select class="form-control" id="dorms" name="dorms">
                                                <option value="">@lang('tours.dormsPH')</option>
                                                @for($i = 1; $i <= 30; $i++)
                                                    <option value="{{ $i }}" @if(old('dorms', $dorms) == $i) selected @endif>{{ $i }}</option>
                                                @endfor
                                            </select>

                                            @if ($errors->has('dorms'))
                                                <span class="help-block"><strong>{{ $errors->first('dorms') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group{{ $errors->has('sleeps') ? ' has-error' : '' }}">
                                            <label>@lang('tours.sleeps') <span class="required">*</span></label>
                                            <select class="form-control" id="sleeps" name="sleeps">
                                                <option value="">@lang('tours.sleepsPH')</option>
                                                @for($i = 1; $i <= 30; $i++)
                                                    <option value="{{ $i }}" @if(old('sleeps', $sleeps) == $i) selected @endif>{{ $i }}</option>
                                                @endfor
                                            </select>

                                            @if ($errors->has('sleeps'))
                                                <span class="help-block"><strong>{{ $errors->first('sleeps') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- /.row-two -->

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('cabins.lblHalfboard')</label>
                                        <div class=" checkbox">
                                            <label><input type="checkbox" id="half_board" class="halfboardCls" name="half_board" @if(old('half_board' , $halfBoard) == '1') checked @endif value="1">@lang('cabins.half_board_available')</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary" name="updateBasicSettings" value="updateBasicSettings"><i class="fa fa-fw fa-save"></i>@lang('tours.frmUpdateBtn')</button>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-footer -->
                        </form>
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>

    </div>
    <!-- /.content-wrapper -->
@endsection