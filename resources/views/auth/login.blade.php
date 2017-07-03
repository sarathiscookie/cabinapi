@extends('layouts.app')

@section('title', 'Cabin API | Sign In')

@section('css')
@endsection

@section('content')

    <div class="login-box">
        <div class="login-logo">
            <b>Cabin</b> API
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Log in to cabin api</p>

            <form role="form" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('usrName') ? ' has-error' : '' }} has-feedback">
                    <input id="usrName" type="text" class="form-control" name="usrName" placeholder="Username" value="{{ old('usrName') }}" required autofocus>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>

                    @if ($errors->has('usrName'))
                        <span class="help-block">
                            <strong>{{ $errors->first('usrName') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('usrPassword') ? ' has-error' : '' }} has-feedback">
                    <input id="usrPassword" type="password" class="form-control" name="usrPassword" placeholder="Password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                    @if ($errors->has('usrPassword'))
                        <span class="help-block">
                            <strong>{{ $errors->first('usrPassword') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <a href="{{ route('password.request') }}"> Forgot password? </a><br>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

@endsection

@section('scripts')
    <!-- iCheck -->
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endsection
