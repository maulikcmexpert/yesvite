<x-front.advertise />

<section class="contact-details change-password-wrap">
    <div class="container">
        <div class="row">
            <x-front.sidebar :profileData="$user" />

            <div class="col-xxl-9 col-xl-8 col-lg-8 col-md-7">
                <div class="contact-list">
                    <nav class="breadcrumb-nav" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('profile')}}">Profile</a></li>
                            <li class="breadcrumb-item"><a href="{{route('profile.edit')}}">Edit Profile</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Change Password</li>
                        </ol>
                    </nav>
                    <div class="contact-title">
                        <h3>Change Password</h3>
                        <a href="#" class="cmn-btn" id="save_password_changes">Save Changes</a>

                    </div>
                  
                </div>
                <div class="change-password">
                    <div class="content">
                        <h6>{{$user->firstname}} | {{ $user->email}}</h6>
                        <p>For security purposes you’ll be logged out of all sessions except this one if you change
                            your password.</p>
                        <p>Your password must be 8 characters long and must contain at least one number and one
                            special character</p>
                    </div>
                    <div class="login-form-wrap">
                        <form method="post" id="updateUserPassword" action="{{route('profile.update_password')}}">
                            @csrf
                            <div class="input-form">
                                <input type="password" class="form-control inputText" id="current_password" name="current_password" required>

                                @php
                                use Carbon\Carbon;
                                @endphp
                                <label for="password" class="form-label input-field floating-label">Current password
                                    (Updated {{ Carbon::parse($user->password_updated_date)->format('d/m/Y') }})</label>
                                @error('current_password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-form">
                                <input type="password" class="form-control inputText" id="new_password" name="new_password" required>
                                <label for="password" class="form-label input-field floating-label">New
                                    Password</label>
                                @error('new_password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-form">
                                <input type="password" class="form-control inputText" id="conform_password" name="conform_password" required>
                                <label for="password" class="form-label input-field floating-label">Re-type New
                                    Password</label>
                                @error('conform_password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <a href="#">Forgot Password</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>