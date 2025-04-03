<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user?->email) }}" id="email" placeholder="Email">
            {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="two_factor_secret" class="form-label">{{ __('Two Factor Secret') }}</label>
            <input type="text" name="two_factor_secret" class="form-control @error('two_factor_secret') is-invalid @enderror" value="{{ old('two_factor_secret', $user?->two_factor_secret) }}" id="two_factor_secret" placeholder="Two Factor Secret">
            {!! $errors->first('two_factor_secret', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="two_factor_recovery_codes" class="form-label">{{ __('Two Factor Recovery Codes') }}</label>
            <input type="text" name="two_factor_recovery_codes" class="form-control @error('two_factor_recovery_codes') is-invalid @enderror" value="{{ old('two_factor_recovery_codes', $user?->two_factor_recovery_codes) }}" id="two_factor_recovery_codes" placeholder="Two Factor Recovery Codes">
            {!! $errors->first('two_factor_recovery_codes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="two_factor_confirmed_at" class="form-label">{{ __('Two Factor Confirmed At') }}</label>
            <input type="text" name="two_factor_confirmed_at" class="form-control @error('two_factor_confirmed_at') is-invalid @enderror" value="{{ old('two_factor_confirmed_at', $user?->two_factor_confirmed_at) }}" id="two_factor_confirmed_at" placeholder="Two Factor Confirmed At">
            {!! $errors->first('two_factor_confirmed_at', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="current_team_id" class="form-label">{{ __('Current Team Id') }}</label>
            <input type="text" name="current_team_id" class="form-control @error('current_team_id') is-invalid @enderror" value="{{ old('current_team_id', $user?->current_team_id) }}" id="current_team_id" placeholder="Current Team Id">
            {!! $errors->first('current_team_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="profile_photo_path" class="form-label">{{ __('Profile Photo Path') }}</label>
            <input type="text" name="profile_photo_path" class="form-control @error('profile_photo_path') is-invalid @enderror" value="{{ old('profile_photo_path', $user?->profile_photo_path) }}" id="profile_photo_path" placeholder="Profile Photo Path">
            {!! $errors->first('profile_photo_path', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="google_id" class="form-label">{{ __('Google Id') }}</label>
            <input type="text" name="google_id" class="form-control @error('google_id') is-invalid @enderror" value="{{ old('google_id', $user?->google_id) }}" id="google_id" placeholder="Google Id">
            {!! $errors->first('google_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>