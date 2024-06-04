<x-front.advertise />
<!-- ============ contact-details ========== -->
<section class="contact-details profile-details">
    <div class="container">
        <div class="row">
            <x-front.sidebar :profileData="$user" />
            <div class="col-xxl-9 col-xl-8 col-lg-8 col-md-7">
                <div class="contact-list notification-wrap edit-profile-wrp">
                    <nav class="breadcrumb-nav" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('profile')}}">Profile</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
                        </ol>
                    </nav>
                    <div class="contact-title">
                        <h3>Edit Profile</h3>
                        <div class="d-flex align-items-center gap-3">
                            <a href="change-password.html" class="cmn-btn edit-btn">
                                Edit Password</a>
                            <a href="#" class="btn cmn-btn" data-bs-toggle="modal" data-bs-target="#myModal1">Save Changes</a>
                        </div>
                    </div>
                    <div class="profile-wrapper edit-profile">
                        <div class="profile-img">
                            <form>
                                <div class="position-relative">
                                    <div id="img-preview">
                                        <img src="{{$user->bg_profile}}" alt="" class="bg-img">
                                    </div>
                                    <input type="file" id="choose-file" name="choose-file" accept="image/*" />
                                    <a href="#" class="Edit-img" for="choose-file" data-bs-toggle="modal" data-bs-target="#coverImg-modal">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8.84006 3.73332L3.36673 9.52665C3.16006 9.74665 2.96006 10.18 2.92006 10.48L2.6734 12.64C2.58673 13.42 3.14673 13.9533 3.92006 13.82L6.06673 13.4533C6.36673 13.4 6.78673 13.18 6.9934 12.9533L12.4667 7.15998C13.4134 6.15998 13.8401 5.01998 12.3667 3.62665C10.9001 2.24665 9.78673 2.73331 8.84006 3.73332Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M7.92676 4.7002C8.21342 6.5402 9.70676 7.94686 11.5601 8.13353" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                </div>
                            </form>
                            <div class="user-img">
                                <img src="{{$user->profile}}" alt="user-img">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#Edit-modal">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.05 4.66652L4.20829 11.9082C3.94996 12.1832 3.69996 12.7249 3.64996 13.0999L3.34162 15.7999C3.23329 16.7749 3.93329 17.4415 4.89996 17.2749L7.58329 16.8165C7.95829 16.7499 8.48329 16.4749 8.74162 16.1915L15.5833 8.94985C16.7666 7.69985 17.3 6.27485 15.4583 4.53319C13.625 2.80819 12.2333 3.41652 11.05 4.66652Z" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M9.9082 5.875C10.2665 8.175 12.1332 9.93333 14.4499 10.1667" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </div>

                        </div>
                        <div class="profile-content login-form-wrap">
                            <form method="POST" action="{{route('profile.update',encrypt($user->id))}}" id="updateUserForm">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="input-form">
                                            <input type="text" class="form-control inputText" id="text" name="text" value="{{$user->firstname}}">
                                            <label for="text" class="form-label input-field floating-label">First Name <span class="required">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-form ">
                                            <input type="text" class="form-control inputText" id="text" name="text" value="{{$user->lastname}}">
                                            <label for="text" class="form-label input-field floating-label">Last Name</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="main-radio input-form">
                                            <label for="Gender" class="form-label radio-label">Gender</label>
                                            <div class="radio-wrapper">
                                                <div class="row">
                                                    <div class="col-lg-6 col-6">
                                                        <div class="form-check d-flex align-items-center justify-content-between">
                                                            <label class="form-check-label mb-0" for="flexRadioDefault1">
                                                                <h6>Male</h6>
                                                            </label>
                                                            <input class="form-check-input inputText" type="radio" name="flexRadioDefault" id="flexRadioDefault1" {{($user->gender == 'male')?'checked':''}}>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-6">
                                                        <div class="form-check d-flex align-items-center justify-content-between">
                                                            <label class="form-check-label  mb-0" for="flexRadioDefault2">
                                                                <h6>Female</h6>
                                                            </label>
                                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" {{($user->gender == 'female')?'checked':''}}>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="input-form">
                                            <input type="date" class="form-control inputText" id="birthday" name="birthday" value="{{$user->birth_date}}">
                                            <label for="birthday" class="form-label input-field floating-label">Birthday</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-form">
                                            <input type="email" class="form-control inputText" id="email" name="email" value="{{ $user->email}}">
                                            <label for="email" class="form-label input-field floating-label">Email Address</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-form">
                                            <input type="tel" class="form-control inputText" id="phone" name="phone" value="{{ $user->phone_number}}">
                                            <label for="phone" class="form-label input-field floating-label">Phone Number</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="input-form">
                                            <input type="text" class="form-control inputText" id="code" name="code" value="{{ $user->zip_code}}">
                                            <label for="code" class="form-label input-field floating-label required">Zip Code <span>*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="input-form mb-0">
                                            <textarea name="" class="inputText" id="">{{ $user->about_me}}</textarea>
                                            <label for="code" class="form-label input-field floating-label about-label">About Me</label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ======== coverprofile-model ========= -->
<div class="modal fade" id="Edit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header align-items-center">
                <div>
                    <h4 class="modal-title">Edit Photo Profile</h4>
                    <span>Recomendation Size : 730x200</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="cover-img user-cover-img" id="cover-img">
                    <img src="./assets/image/user-img.svg" alt="cover-img">
                </div>
                <div>
                    <div class="slidecontainer">
                        <h5>Zoom</h5>
                        <input type="range" min="1" max="100" value="50">
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-end">
                    <label class="choosen-file cmn-btn edit-btn mb-0" for="choose-file">
                        Upload New Image
                    </label>
                    <button type="button" class="cmn-btn bg-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======== coverBg-model ========= -->
<div class="modal fade" id="coverImg-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header align-items-center">
                <div>
                    <h4 class="modal-title">Edit Cover Photo</h4>
                    <span>Recomendation Size : 730x200</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="cover-img" id="cover-img">
                    <img src="./assets/image/Frame 1000005835.png" alt="cover-img">
                </div>
                <div>
                    <div class="slidecontainer">
                        <h5>Zoom</h5>
                        <input type="range" min="1" max="100" value="50">
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-end">
                    <label class="choosen-file cmn-btn edit-btn mb-0" for="choose-file">
                        Upload New Image
                    </label>
                    <button type="button" class="cmn-btn bg-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========== delete-model ========== -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog delete-model-wrap">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="delete-icon">
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22.5C17.5 22.5 22 18 22 12.5C22 7 17.5 2.5 12 2.5C6.5 2.5 2 7 2 12.5C2 18 6.5 22.5 12 22.5Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 8.5V13.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M11.9946 16.5H12.0036" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="contents">
                    <h4>Delete Account</h4>
                    <p>Are you sure want to delete your account? You will lose all your data, photos, messages. and
                        can’t be recovered.</p>
                    <p>Please confirm by typing <strong>“DELETE”</strong> in the text box below then tapping
                        Confirm.</p>
                    <input type="text" placeholder="DELETE">
                </div>
                <div class="d-flex justify-content-between gap-3">
                    <button class="cmn-btn cancel-btn" type="button">Cancel</button>
                    <button class="cmn-btn confirm-btn" type="button">Delete Account</button>
                </div>
            </div>
        </div>
    </div>
</div>