<x-front.advertise />
<section class="contact-details profile-privacy-wrp">
    <div class="container">
        <div class="row">
            <x-front.sidebar :profileData="$user" />
            <div class="col-xxl-9 col-xl-8 col-lg-8 col-md-7">
                <div class="contact-list">
                    <nav class="breadcrumb-nav" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('profile')}}">Profile</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Privacy</li>
                        </ol>
                    </nav>
                    <div class="contact-title">
                        <h3>
                            Profile Privacy</h3>
                        <button type="button" class="cmn-btn save-btn" id="profilePrivacySave" data-bs-toggle="modal" data-bs-target="#myModal3">
                            Save Changes</button>
                    </div>
                    <div class="privacy-wrapper">

                        <p class="border-bottom pt-0">Who can see your profile?</p>
                        <form method="POST" action="" id="profile_privacy">
                            <div class="radio-wrapper">
                                <div>
                                    <div class="form-check d-flex align-items-center justify-content-between border-bottom">
                                        <label class="form-check-label d-flex mb-0" for="flexRadioDefault1">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10.0007 10.6248C7.35898 10.6248 5.20898 8.47484 5.20898 5.83317C5.20898 3.1915 7.35898 1.0415 10.0007 1.0415C12.6423 1.0415 14.7923 3.1915 14.7923 5.83317C14.7923 8.47484 12.6423 10.6248 10.0007 10.6248ZM10.0007 2.2915C8.05065 2.2915 6.45898 3.88317 6.45898 5.83317C6.45898 7.78317 8.05065 9.37484 10.0007 9.37484C11.9507 9.37484 13.5423 7.78317 13.5423 5.83317C13.5423 3.88317 11.9507 2.2915 10.0007 2.2915Z" fill="#64748B" />
                                                <path d="M2.8418 18.9583C2.50013 18.9583 2.2168 18.675 2.2168 18.3333C2.2168 14.775 5.70846 11.875 10.0001 11.875C10.8418 11.875 11.6668 11.9834 12.4668 12.2084C12.8001 12.3 12.9918 12.6417 12.9001 12.975C12.8085 13.3083 12.4668 13.5 12.1335 13.4084C11.4501 13.2167 10.7335 13.125 10.0001 13.125C6.40013 13.125 3.4668 15.4583 3.4668 18.3333C3.4668 18.675 3.18346 18.9583 2.8418 18.9583Z" fill="#64748B" />
                                                <path d="M14.9993 18.9582C13.616 18.9582 12.316 18.2248 11.616 17.0332C11.241 16.4332 11.041 15.7248 11.041 14.9998C11.041 13.7832 11.5827 12.6582 12.5243 11.9082C13.2243 11.3498 14.1077 11.0415 14.9993 11.0415C17.1827 11.0415 18.9577 12.8165 18.9577 14.9998C18.9577 15.7248 18.7577 16.4332 18.3827 17.0415C18.1743 17.3915 17.9077 17.7082 17.591 17.9748C16.8993 18.6082 15.9743 18.9582 14.9993 18.9582ZM14.9993 12.2915C14.3827 12.2915 13.7994 12.4998 13.3077 12.8915C12.666 13.3998 12.291 14.1748 12.291 14.9998C12.291 15.4915 12.4244 15.9748 12.6827 16.3915C13.166 17.2082 14.0577 17.7082 14.9993 17.7082C15.6577 17.7082 16.291 17.4665 16.7744 17.0332C16.991 16.8498 17.1743 16.6332 17.3077 16.3998C17.5743 15.9748 17.7077 15.4915 17.7077 14.9998C17.7077 13.5082 16.491 12.2915 14.9993 12.2915Z" fill="#64748B" />
                                                <path d="M14.5241 16.4501C14.3657 16.4501 14.2074 16.3918 14.0824 16.2668L13.2574 15.4418C13.0158 15.2002 13.0158 14.8001 13.2574 14.5584C13.4991 14.3168 13.8991 14.3168 14.1408 14.5584L14.5408 14.9585L15.8741 13.7251C16.1241 13.4918 16.5241 13.5085 16.7574 13.7585C16.9907 14.0085 16.9741 14.4085 16.7241 14.6418L14.9491 16.2835C14.8241 16.3918 14.6741 16.4501 14.5241 16.4501Z" fill="#64748B" />
                                            </svg>
                                            <div>
                                                <h6>Guests from events</h6>
                                            </div>
                                        </label>
                                        <input class="form-check-input" type="radio" name="visible" value="1" id="flexRadioDefault1" {{($user->visible == '1')?'checked':''}}>
                                    </div>
                                    <div class="border-bottom d-flex align-items-center">
                                        <p>Choose what to show on your profile</p>
                                        <svg class="ms-auto" width="16" height="9" viewBox="0 0 16 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14.6004 1.4585L9.16706 6.89183C8.52539 7.5335 7.47539 7.5335 6.83372 6.89183L1.40039 1.4585" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <h6>Gender</h6>
                                            <div class="toggle-button-cover ">
                                                <div class="button-cover">
                                                    <div class="button r" id="button-1">
                                                        <input type="hidden" name="profile_privacy[gender]" value="0" />
                                                        <input type="checkbox" name="profile_privacy[gender]" value="1" class="checkbox" checked />
                                                        <div class="knobs"></div>
                                                        <div class="layer"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <h6>Photo</h6>
                                            <div class="toggle-button-cover ">
                                                <div class="button-cover">
                                                    <div class="button r" id="button-1">
                                                        <input type="hidden" name="profile_privacy[photo]" value="0" />
                                                        <input type="checkbox" name="profile_privacy[photo]" value="1" class="checkbox" checked />
                                                        <div class="knobs"></div>
                                                        <div class="layer"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <h6>Location</h6>
                                            <div class="toggle-button-cover ">
                                                <div class="button-cover">
                                                    <div class="button r" id="button-1">
                                                        <input type="hidden" name="profile_privacy[location]" value="0" />
                                                        <input type="checkbox" name="profile_privacy[location]" value="1" class="checkbox" checked />
                                                        <div class="knobs"></div>
                                                        <div class="layer"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <h6>Event Profile Stats</h6>
                                            <div class="toggle-button-cover ">
                                                <div class="button-cover">
                                                    <div class="button r" id="button-1">
                                                        <input type="hidden" name="profile_privacy[event_stat]" value="0" />
                                                        <input type="checkbox" name="profile_privacy[event_stat]" value="1" class="checkbox" checked />
                                                        <div class="knobs"></div>
                                                        <div class="layer"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="form-check d-flex align-items-center justify-content-between border-bottom">
                                        <label class="form-check-label d-flex mb-0" for="flexRadioDefault2">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10.0007 10.6248C7.35898 10.6248 5.20898 8.47484 5.20898 5.83317C5.20898 3.1915 7.35898 1.0415 10.0007 1.0415C12.6423 1.0415 14.7923 3.1915 14.7923 5.83317C14.7923 8.47484 12.6423 10.6248 10.0007 10.6248ZM10.0007 2.2915C8.05065 2.2915 6.45898 3.88317 6.45898 5.83317C6.45898 7.78317 8.05065 9.37484 10.0007 9.37484C11.9507 9.37484 13.5423 7.78317 13.5423 5.83317C13.5423 3.88317 11.9507 2.2915 10.0007 2.2915Z" fill="#64748B" />
                                                <path d="M2.8418 18.9583C2.50013 18.9583 2.2168 18.675 2.2168 18.3333C2.2168 14.775 5.70846 11.875 10.0001 11.875C10.8418 11.875 11.6668 11.9834 12.4668 12.2084C12.8001 12.3 12.9918 12.6417 12.9001 12.975C12.8085 13.3083 12.4668 13.5 12.1335 13.4084C11.4501 13.2167 10.7335 13.125 10.0001 13.125C6.40013 13.125 3.4668 15.4583 3.4668 18.3333C3.4668 18.675 3.18346 18.9583 2.8418 18.9583Z" fill="#64748B" />
                                                <path d="M14.9993 18.9582C14.016 18.9582 13.0827 18.5915 12.3577 17.9332C12.066 17.6832 11.8077 17.3748 11.6077 17.0332C11.241 16.4332 11.041 15.7248 11.041 14.9998C11.041 13.9582 11.441 12.9832 12.1577 12.2415C12.9077 11.4665 13.916 11.0415 14.9993 11.0415C16.1327 11.0415 17.2077 11.5249 17.941 12.3582C18.591 13.0832 18.9577 14.0165 18.9577 14.9998C18.9577 15.3165 18.916 15.6332 18.8327 15.9332C18.7494 16.3082 18.591 16.6998 18.3743 17.0415C17.6827 18.2248 16.3827 18.9582 14.9993 18.9582ZM14.9993 12.2915C14.2577 12.2915 13.5744 12.5832 13.0577 13.1082C12.566 13.6165 12.291 14.2832 12.291 14.9998C12.291 15.4915 12.4244 15.9748 12.6827 16.3915C12.816 16.6248 12.991 16.8331 13.191 17.0081C13.691 17.4665 14.3327 17.7165 14.9993 17.7165C15.941 17.7165 16.8327 17.2165 17.316 16.4082C17.4577 16.1748 17.566 15.9082 17.6243 15.6499C17.6827 15.4332 17.7077 15.2248 17.7077 15.0082C17.7077 14.3415 17.4577 13.6999 17.0077 13.1999C16.5077 12.6165 15.7743 12.2915 14.9993 12.2915Z" fill="#64748B" />
                                                <path d="M14.0995 16.4999C13.9411 16.4999 13.7828 16.4416 13.6578 16.3166C13.4161 16.0749 13.4161 15.6749 13.6578 15.4333L15.4161 13.6749C15.6578 13.4332 16.0578 13.4332 16.2995 13.6749C16.5411 13.9166 16.5411 14.3166 16.2995 14.5582L14.5411 16.3166C14.4161 16.4416 14.2578 16.4999 14.0995 16.4999Z" fill="#64748B" />
                                                <path d="M15.8832 16.5165C15.7249 16.5165 15.5665 16.4582 15.4415 16.3332L13.6832 14.5748C13.4415 14.3332 13.4415 13.9332 13.6832 13.6915C13.9249 13.4498 14.3249 13.4498 14.5665 13.6915L16.3249 15.4498C16.5665 15.6915 16.5665 16.0915 16.3249 16.3332C16.1999 16.4582 16.0415 16.5165 15.8832 16.5165Z" fill="#64748B" />
                                            </svg>
                                            <div>
                                                <h6 class="mb-1">No One</h6>
                                                <span>Your profile will be private to everyone</span>
                                            </div>
                                        </label>
                                        <input class="form-check-input" type="radio" value="2" name="visible" id="flexRadioDefault2" {{($user->visible == '2')?'checked':''}}>
                                    </div>
                                    <a href="{{route('profile.public_profile')}}" class="d-flex align-items-center public-view">
                                        <h6>Public Profile View</h6>
                                        <svg class="ms-auto" width="16" height="9" viewBox="0 0 16 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14.6004 1.4585L9.16706 6.89183C8.52539 7.5335 7.47539 7.5335 6.83372 6.89183L1.40039 1.4585" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>