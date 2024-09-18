<div class="step_4" style="display:none;width:100%;">
    <div class="setting-wrp">
        <div class="setting-content">
            <h4>Settings</h4>
            <div class="setting-accordion-wrp">
                <div class="accordion" id="settingaccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="general">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#general-collapseOne" aria-expanded="true" aria-controls="general-collapseOne">
                                <div class="d-flex align-items-center w-100">
                                    <div class="d-flex align-items-center">
                                        <span><img src="{{asset('assets/event/image/accordion1-img.svg')}}" alt=""></span>
                                        <h5>General</h6>
                                    </div>
                                    <h6>0/6</span>
                                </div>
                            </button>
                        </h2>
                        <div id="general-collapseOne" class="accordion-collapse collapse show" aria-labelledby="general">
                            <div class="accordion-body">
                                <div class="accordion-con">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex">
                                            <p>Allow For +1</p>
                                            <a href="#">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.99984 18.3337C14.5832 18.3337 18.3332 14.5837 18.3332 10.0003C18.3332 5.41699 14.5832 1.66699 9.99984 1.66699C5.4165 1.66699 1.6665 5.41699 1.6665 10.0003C1.6665 14.5837 5.4165 18.3337 9.99984 18.3337Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M10 6.66699V10.8337" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9.99561 13.333H10.0031" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="toggle-button-cover">
                                            <div class="button-cover">
                                                <div class="button r" id="button-1">
                                                    <input type="checkbox" id="allow_for_1_more" name="allow_for_1_more" onchange="savePage4Data()" value="1" class="checkbox" />
                                                    <div class="knobs"></div>
                                                    <div class="layer"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#" class="add-limit allow_limit_toggle" onclick="toggleSidebar('sidebar_allow_limit')" style="display: none;">
                                        <div class="d-flex align-items-center justify-content-between w-100">
                                            <div class="d-flex align-items-center add_new_limit">
                                                <span class="me-3">
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
                                                    </svg>
                                                </span>
                                                <h5>Add +1 limit</h5>
                                            </div>
                                            <span>
                                                <svg width="9" height="16" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1.42505 14.6004L6.85838 9.16706C7.50005 8.52539 7.50005 7.47539 6.85838 6.83372L1.42505 1.40039" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </div>
                                    </a>
                                </div>
                                <div class="accordion-con">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex">
                                            <p>Adults only party</p>
                                            <a href="#">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.99984 18.3337C14.5832 18.3337 18.3332 14.5837 18.3332 10.0003C18.3332 5.41699 14.5832 1.66699 9.99984 1.66699C5.4165 1.66699 1.6665 5.41699 1.6665 10.0003C1.6665 14.5837 5.4165 18.3337 9.99984 18.3337Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M10 6.66699V10.8337" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9.99561 13.333H10.0031" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="toggle-button-cover">
                                            <div class="button-cover">
                                                <div class="button r" id="button-1">
                                                    <input type="checkbox" id="only_adults" name="only_adults" value="1" class="checkbox" />
                                                    <div class="knobs"></div>
                                                    <div class="layer"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="accordion-con">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <p>RSVP by date</p>
                                        <a href="#">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.99984 18.3337C14.5832 18.3337 18.3332 14.5837 18.3332 10.0003C18.3332 5.41699 14.5832 1.66699 9.99984 1.66699C5.4165 1.66699 1.6665 5.41699 1.6665 10.0003C1.6665 14.5837 5.4165 18.3337 9.99984 18.3337Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M10 6.66699V10.8337" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M9.99561 13.333H10.0031" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="toggle-button-cover">
                                        <div class="button-cover">
                                            <div class="button r" id="button-1">
                                                <input type="checkbox" class="checkbox" />
                                                <div class="knobs"></div>
                                                <div class="layer"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="#" class="add-limit" onclick="toggleSidebar()">
                                    <div class="d-flex align-items-center justify-content-between w-100">
                                        <div class="d-flex align-items-center">
                                            <span class="me-3">
                                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137"/>
                                                </svg>
                                            </span>
                                            <h5>Select RSVP date</h5>
                                        </div>
                                        <span>
                                            <svg width="9" height="16" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1.42505 14.6004L6.85838 9.16706C7.50005 8.52539 7.50005 7.47539 6.85838 6.83372L1.42505 1.40039" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                    </div>
                                </a>
                            </div> --}}
                                <div class="accordion-con">
                                    <div class="d-flex justify-content-between ">
                                        <div class="d-flex">
                                            <p><strong>Kid drop off event</strong></p>
                                            <a href="#">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.99984 18.3337C14.5832 18.3337 18.3332 14.5837 18.3332 10.0003C18.3332 5.41699 14.5832 1.66699 9.99984 1.66699C5.4165 1.66699 1.6665 5.41699 1.6665 10.0003C1.6665 14.5837 5.4165 18.3337 9.99984 18.3337Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M10 6.66699V10.8337" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9.99561 13.333H10.0031" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="toggle-button-cover">
                                            <div class="button-cover">
                                                <div class="button r" id="button-1">
                                                    <input type="checkbox" id="kid_off_event" name="kid_off_event" value="1" class="checkbox" checked />
                                                    <div class="knobs"></div>
                                                    <div class="layer"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-con">
                                    <div class="d-flex justify-content-between ">
                                        <div class="d-flex">
                                            <p><strong>Thank you messages</strong></p>
                                            <a href="#" onclick="toggleSidebar()">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.99984 18.3337C14.5832 18.3337 18.3332 14.5837 18.3332 10.0003C18.3332 5.41699 14.5832 1.66699 9.99984 1.66699C5.4165 1.66699 1.6665 5.41699 1.6665 10.0003C1.6665 14.5837 5.4165 18.3337 9.99984 18.3337Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M10 6.66699V10.8337" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9.99561 13.333H10.0031" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="toggle-button-cover">
                                            <div class="button-cover">
                                                <div class="button r" id="button-1">
                                                    <input type="checkbox" id="thankyou_message" name="thankyou_message" onchange="savePage4Data()" class="checkbox" />
                                                    <div class="knobs"></div>
                                                    <div class="layer"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#" class="add-limit thankyou_card" onclick="toggleSidebar('sidebar_thankyou_card')" style="display: none;">
                                        <div class="d-flex align-items-center justify-content-between w-100">
                                            <div class="d-flex align-items-center add_new_thankyou_card">
                                                <span class="me-3">
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
                                                    </svg>
                                                </span>
                                                <h5>Select thank you card</h5>
                                            </div>
                                            <span>
                                                <svg width="9" height="16" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1.42505 14.6004L6.85838 9.16706C7.50005 8.52539 7.50005 7.47539 6.85838 6.83372L1.42505 1.40039" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </div>
                                    </a>
                                </div>
                                <div class="accordion-con">
                                    <div class="d-flex justify-content-between ">
                                        <div class="d-flex">
                                            <p><strong>Add Co-Host</strong></p>
                                            <a href="#">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.99984 18.3337C14.5832 18.3337 18.3332 14.5837 18.3332 10.0003C18.3332 5.41699 14.5832 1.66699 9.99984 1.66699C5.4165 1.66699 1.6665 5.41699 1.6665 10.0003C1.6665 14.5837 5.4165 18.3337 9.99984 18.3337Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M10 6.66699V10.8337" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9.99561 13.333H10.0031" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="toggle-button-cover">
                                            <div class="button-cover">
                                                <div class="button r" id="button-1">
                                                    <input type="checkbox" id="add_co_host" onchange="savePage4Data()" class="checkbox" />
                                                    <div class="knobs"></div>
                                                    <div class="layer"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#" class="add-limit add_co_host" onclick="toggleSidebar('sidebar_add_co_host')" style="display:none;">
                                        <div class="d-flex align-items-center justify-content-between w-100">
                                            <div class="d-flex align-items-center">
                                                <span class="me-3">
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
                                                    </svg>
                                                </span>
                                                <h5>Select your co-host</h5>
                                            </div>
                                            <span>
                                                <svg width="9" height="16" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1.42505 14.6004L6.85838 9.16706C7.50005 8.52539 7.50005 7.47539 6.85838 6.83372L1.42505 1.40039" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </div>
                                    </a>
                                </div>
                                <div class="accordion-con">
                                    <div class="d-flex justify-content-between ">
                                        <div class="d-flex">
                                            <p><strong>Gift Registry</strong></p>
                                            <a href="#" onclick="toggleSidebar()">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.99984 18.3337C14.5832 18.3337 18.3332 14.5837 18.3332 10.0003C18.3332 5.41699 14.5832 1.66699 9.99984 1.66699C5.4165 1.66699 1.6665 5.41699 1.6665 10.0003C1.6665 14.5837 5.4165 18.3337 9.99984 18.3337Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M10 6.66699V10.8337" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9.99561 13.333H10.0031" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="toggle-button-cover">
                                            <div class="button-cover">
                                                <div class="button r" id="button-1">
                                                    <input type="checkbox" id="gift_registry" onchange="savePage4Data()" class="checkbox" />
                                                    <div class="knobs"></div>
                                                    <div class="layer"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#" class="add-limit add_gift_registry" onclick="toggleSidebar('sidebar_gift_registry')" style="display: none;">
                                        <div class="d-flex align-items-center justify-content-between w-100">
                                            <div class="d-flex align-items-center add_gift_registry_count">
                                                <span class="me-3">
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
                                                    </svg>
                                                </span>
                                                <h5>Add gift registry</h5>
                                            </div>
                                            <span>
                                                <svg width="9" height="16" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1.42505 14.6004L6.85838 9.16706C7.50005 8.52539 7.50005 7.47539 6.85838 6.83372L1.42505 1.40039" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </div>
                                    </a>
                                </div>
                                <div class="accordion-con">
                                    <div class="d-flex justify-content-between ">
                                        <div class="d-flex">
                                            <p><strong>Guest list visible to guests</strong></p>
                                            <a href="#">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.99984 18.3337C14.5832 18.3337 18.3332 14.5837 18.3332 10.0003C18.3332 5.41699 14.5832 1.66699 9.99984 1.66699C5.4165 1.66699 1.6665 5.41699 1.6665 10.0003C1.6665 14.5837 5.4165 18.3337 9.99984 18.3337Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M10 6.66699V10.8337" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9.99561 13.333H10.0031" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="toggle-button-cover">
                                            <div class="button-cover">
                                                <div class="button r" id="button-1">
                                                    <input type="checkbox" id="guest_list_visible_to_guest" class="checkbox" />
                                                    <div class="knobs"></div>
                                                    <div class="layer"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="event">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#event-collapseTwo" aria-expanded="false" aria-controls="event-collapseTwo">
                                <div class="d-flex align-items-center w-100">
                                    <div class="d-flex align-items-center">
                                        <span><img src="{{asset('assets/event/image/file.svg')}}" alt=""></span>
                                        <h5>Event Page Extras</h6>
                                    </div>
                                    <h6>0/2</span>
                                </div>
                            </button>
                        </h2>
                        <div id="event-collapseTwo" class="accordion-collapse collapse show" aria-labelledby="event">
                            <div class="accordion-body">
                                <div class="accordion-con">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex">
                                            <p><strong>Event Wall</strong></p>
                                            <a href="#">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.99984 18.3337C14.5832 18.3337 18.3332 14.5837 18.3332 10.0003C18.3332 5.41699 14.5832 1.66699 9.99984 1.66699C5.4165 1.66699 1.6665 5.41699 1.6665 10.0003C1.6665 14.5837 5.4165 18.3337 9.99984 18.3337Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M10 6.66699V10.8337" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9.99561 13.333H10.0031" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="toggle-button-cover">
                                            <div class="button-cover">
                                                <div class="button r" id="button-1">
                                                    <input type="checkbox" id="eventwall" name="notification_setting[]" onchange="savePage4Data()" class="checkbox" />
                                                    <div class="knobs"></div>
                                                    <div class="layer"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-con">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex">
                                            <p><strong>Potluck</strong></p>
                                            <a href="#">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.99984 18.3337C14.5832 18.3337 18.3332 14.5837 18.3332 10.0003C18.3332 5.41699 14.5832 1.66699 9.99984 1.66699C5.4165 1.66699 1.6665 5.41699 1.6665 10.0003C1.6665 14.5837 5.4165 18.3337 9.99984 18.3337Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M10 6.66699V10.8337" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9.99561 13.333H10.0031" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="toggle-button-cover">
                                            <div class="button-cover">
                                                <div class="button r" id="button-1">
                                                    <input type="checkbox" id="potluck" name="potluck" class="checkbox" />
                                                    <div class="knobs"></div>
                                                    <div class="layer"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#" class="add-limit potluck" style="display: none" onclick="toggleSidebar('sidebar_potluck')">
                                        <div class="d-flex align-items-center justify-content-between w-100">
                                            <div class="d-flex align-items-center potluck_count">
                                                <span class="me-3">
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
                                                    </svg>
                                                </span>
                                                <h5>Select potluck</h5>
                                            </div>
                                            <span>
                                                <svg width="9" height="16" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1.42505 14.6004L6.85838 9.16706C7.50005 8.52539 7.50005 7.47539 6.85838 6.83372L1.42505 1.40039" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="notification">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#notification-collapseThree" aria-expanded="false" aria-controls="notification-collapseThree">
                                <div class="d-flex align-items-center w-100">
                                    <div class="d-flex align-items-center">
                                        <span><img src="{{asset('assets/event/image/notification-acc.svg')}}" alt=""></span>
                                        <h5>Notifications & Reminders</h6>
                                    </div>
                                    <h6>0/4</span>
                                </div>
                            </button>
                        </h2>
                        <div id="notification-collapseThree" class="accordion-collapse collapse show" aria-labelledby="notification">
                            <div class="accordion-body">
                                <div class="d-flex justify-content-between accordion-con">
                                    <div class="d-flex">
                                        <p class="title"><strong>Notifications</strong></p>
                                    </div>
                                </div>
                                <div class="accordion-con">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex">
                                            <p><strong>Event wall posts</strong></p>
                                            <a href="#">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.99984 18.3337C14.5832 18.3337 18.3332 14.5837 18.3332 10.0003C18.3332 5.41699 14.5832 1.66699 9.99984 1.66699C5.4165 1.66699 1.6665 5.41699 1.6665 10.0003C1.6665 14.5837 5.4165 18.3337 9.99984 18.3337Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M10 6.66699V10.8337" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9.99561 13.333H10.0031" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="toggle-button-cover">
                                            <div class="button-cover">
                                                <div class="button r" id="button-1">
                                                    <input type="checkbox" id="event_wall_post" name="event_wall_post" class="checkbox" />
                                                    <div class="knobs"></div>
                                                    <div class="layer"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-con">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex">
                                            <p><strong>RSVP updates</strong></p>
                                            <a href="#">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.99984 18.3337C14.5832 18.3337 18.3332 14.5837 18.3332 10.0003C18.3332 5.41699 14.5832 1.66699 9.99984 1.66699C5.4165 1.66699 1.6665 5.41699 1.6665 10.0003C1.6665 14.5837 5.4165 18.3337 9.99984 18.3337Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M10 6.66699V10.8337" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9.99561 13.333H10.0031" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="toggle-button-cover">
                                            <div class="button-cover">
                                                <div class="button r" id="button-1">
                                                    <input type="checkbox" id="rsvp_update" name="rsvp_update" class="checkbox" />
                                                    <div class="knobs"></div>
                                                    <div class="layer"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between accordion-con">
                                    <div class="d-flex">
                                        <p class="title"><strong>Reminders</strong></p>
                                    </div>
                                </div>
                                <div class="accordion-con">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex">
                                            <p><strong>Request event photos from guests</strong></p>
                                            <a href="#">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.99984 18.3337C14.5832 18.3337 18.3332 14.5837 18.3332 10.0003C18.3332 5.41699 14.5832 1.66699 9.99984 1.66699C5.4165 1.66699 1.6665 5.41699 1.6665 10.0003C1.6665 14.5837 5.4165 18.3337 9.99984 18.3337Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M10 6.66699V10.8337" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9.99561 13.333H10.0031" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="toggle-button-cover">
                                            <div class="button-cover">
                                                <div class="button r" id="button-1">
                                                    <input type="checkbox" id="request_photo" name="request_photo" class="checkbox" />
                                                    <div class="knobs"></div>
                                                    <div class="layer"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-con">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex">
                                            <p><strong>RSVP reminder</strong></p>
                                            <a href="#">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.99984 18.3337C14.5832 18.3337 18.3332 14.5837 18.3332 10.0003C18.3332 5.41699 14.5832 1.66699 9.99984 1.66699C5.4165 1.66699 1.6665 5.41699 1.6665 10.0003C1.6665 14.5837 5.4165 18.3337 9.99984 18.3337Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M10 6.66699V10.8337" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9.99561 13.333H10.0031" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="toggle-button-cover">
                                            <div class="button-cover">
                                                <div class="button r" id="button-1">
                                                    <input type="checkbox" id="rsvp_remainder" name="rsvp_remainder" class="checkbox notification_setting" />
                                                    <div class="knobs"></div>
                                                    <div class="layer"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>



                    </div>
                </div>
            </div>
            <div class="guest-checkout">
                <div class="d-flex align-items-center">
                    <span class="me-2"><svg width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.00022 12.2797L1.65355 7.93306C1.14022 7.41973 1.14022 6.57973 1.65355 6.06639L6.00022 1.71973" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <h5 class="li_guest">Guests</h5>
                </div>
                <div>
                    <a href="#" class="cmn-btn final_checkout" onclick="savePage4Data()">Final Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade deleteModal" id="deleteModal_potluck" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <div class="delete-img">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z"
                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M12 8V13" stroke="white" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M11.9946 16H12.0036" stroke="white" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>

                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body">
                <h5>Potluck data will be deleted</h5>
                <p>If you turn off the potluck option.</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="delete_potluck_category_id" name="delete_potluck_category_id">
                <button type="button" class="cmn-btn cancel-btn"
                    data-bs-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="cmn-btn " id="delete_potluck_category_btn"
                    data-bs-dismiss="modal">Continue</button>
            </div>
        </div>
    </div>
</div>

<div id="sidebar_addcategoryitem" class="sidebar setting-side-wrp">
    <div class="item-dishesh-sidebar add_sub_category ">
        <div class="d-flex align-items-center justify-content-between toggle-wrp">
            <div class="d-flex align-items-center add-item-head-wrp">
                <a href="#" class="me-3" onclick="toggleSidebar('sidebar_potluck')">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.57 5.92969L3.5 11.9997L9.57 18.0697" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M20.4999 12H3.66992" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <h5 class="category_heading">Add Item Under: Main Dishes</h5>
            </div>

            <button class="close-btn" onclick="toggleSidebar()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
        <div class="guest-group-name login-form-wrap no-border-css">
            <form action="" class="registry-form">
                <div class="input-form">
                    <input class="form-control" type="text" id="item_name" name="text1" onkeyup="clearError()" placeholder="Description">
                    <span class="sub-con">9/30</span>
                    <label for="item_name" id="item_name_error"></label>
                </div>
            </form>
        </div>
        <div class="d-flex align-items-center justify-content-between item-dishes-toggle" id="self_bring_toggle">
            <h5>I'll bring this item</h5>
            <div class="toggle-button-cover">
                <div class="button-cover">
                    <div class="button r" id="button-1">
                        <input type="checkbox" id="self_bring" class="checkbox" />
                        <div class="knobs"></div>
                        <div class="layer"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="item-on" id="self_bring_quantity_toggle" style="display: none;">
            <img src="{{asset('assets/event/image/user-img.svg')}}" alt="">
            <h5>{{$user->firstname.' '.$user->lastname}}</h5>
            <div class="qty-container ms-auto">
                <button class=" self_bring_quantity" data-type="minus" type="button"><i class="fa fa-minus"></i></button>
                <input type="number" name="qty" id="self_bring_qty" value="0" class="input-qty" />
                <button class=" self_bring_quantity" data-type="plus" type="button"><i class="fa fa-plus"></i></button>
            </div>
            <div class="d-flex">
                <a href="#" class="me-3">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.84006 3.73283L3.36673 9.52616C3.16006 9.74616 2.96006 10.1795 2.92006 10.4795L2.6734 12.6395C2.58673 13.4195 3.14673 13.9528 3.92006 13.8195L6.06673 13.4528C6.36673 13.3995 6.78673 13.1795 6.9934 12.9528L12.4667 7.15949C13.4134 6.15949 13.8401 5.01949 12.3667 3.62616C10.9001 2.24616 9.78673 2.73283 8.84006 3.73283Z" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M7.92657 4.69922C8.21324 6.53922 9.70657 7.94588 11.5599 8.13255" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <a href="#" id="delete-self-bring">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 3.98763C11.78 3.76763 9.54667 3.6543 7.32 3.6543C6 3.6543 4.68 3.72096 3.36 3.8543L2 3.98763" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M5.66669 3.31398L5.81335 2.44065C5.92002 1.80732 6.00002 1.33398 7.12669 1.33398H8.87335C10 1.33398 10.0867 1.83398 10.1867 2.44732L10.3334 3.31398" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12.5667 6.09375L12.1334 12.8071C12.06 13.8537 12 14.6671 10.14 14.6671H5.86002C4.00002 14.6671 3.94002 13.8537 3.86668 12.8071L3.43335 6.09375" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M6.88666 11H9.10666" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M6.33331 8.33398H9.66665" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
        </div>
        <div class="total-quantity">
            <div class="d-flex">
                <h5 class="me-2">Total quantity for item you’d like (You + Guests)</h5>
                <a href="#">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.99984 18.3337C14.5832 18.3337 18.3332 14.5837 18.3332 10.0003C18.3332 5.41699 14.5832 1.66699 9.99984 1.66699C5.4165 1.66699 1.6665 5.41699 1.6665 10.0003C1.6665 14.5837 5.4165 18.3337 9.99984 18.3337Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M10 6.66699V10.8337" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M9.99561 13.333H10.0031" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
            <div class="qty-container">
                <button class="qty-btn-minus-qty" type="button" onclick="clearError()"><i class="fa fa-minus"></i></button>
                <input type="number" name="qty" id="item_quantity" value="1" class="input-qty" />
                <input type="hidden" name="category_index" id="category_index">
                <button class="qty-btn-plus-qty" type="button" onclick="clearError()"><i class="fa fa-plus"></i></button>
                <label for="item_quantity" id="item_quantity_error"></label>
            </div>
        </div>
        <div class="new-event-btn">
            <a href="#" class="cmn-btn add_category_item_btn">Save</a>
        </div>
    </div>
</div>