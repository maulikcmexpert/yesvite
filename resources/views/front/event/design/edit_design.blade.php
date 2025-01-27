
<main class="main-content-wrp edit-imag-main-content-wrp edit_design_template">
    <div class="main-content-right edit-image-main-wrp">
        <div class="edit-image-main-top">
            <div class="edit-image-main-top-icons">
                <button id="undoButton">
                    <svg width="14" height="7" viewBox="0 0 14 7" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7.3335 1.33366C5.56683 1.33366 3.96683 1.99366 2.7335 3.06699L0.333496 0.666992V6.66699H6.3335L3.92016 4.25366C4.84683 3.48033 6.02683 3.00033 7.3335 3.00033C9.6935 3.00033 11.7002 4.54033 12.4002 6.66699L13.9802 6.14699C13.0535 3.35366 10.4335 1.33366 7.3335 1.33366Z"
                            fill="#CBD5E1" />
                    </svg>
                </button>
                <button id="redoButton">
                    <svg width="14" height="7" viewBox="0 0 14 7" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11.2669 3.06699C10.0335 1.99366 8.43352 1.33366 6.66686 1.33366C3.56686 1.33366 0.946855 3.35366 0.0268555 6.14699L1.60019 6.66699C1.95131 5.60032 2.63009 4.67166 3.53983 4.01329C4.44958 3.35492 5.54388 3.00044 6.66686 3.00033C7.96686 3.00033 9.15352 3.48033 10.0802 4.25366L7.66686 6.66699H13.6669V0.666992L11.2669 3.06699Z"
                            fill="#CBD5E1" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="image-edit-section-wrp">
            <div class="image-edit-inner-img">
                <div class="canvas-container" id="border">
                    <div id="imageWrapper" style="position:fixed; display:none;z-index:10000">
                        <div class="canvas-top-icon-wrp">

                            <div class="removeShapImage" style="display: none">
                                <svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g filter="url(#filter0_d_5633_67674)">
                                        <rect x="2.70312" y="2.37207" width="23.9674" height="23.9674" rx="11.9837" fill="white" shape-rendering="crispEdges" />
                                        <path d="M19.1807 11.3502C17.5179 11.1855 15.8452 11.1006 14.1775 11.1006C13.1888 11.1006 12.2001 11.1505 11.2115 11.2504L10.1929 11.3502" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M12.939 10.8463L13.0488 10.1922C13.1287 9.7178 13.1886 9.36328 14.0325 9.36328H15.3407C16.1846 9.36328 16.2495 9.73777 16.3244 10.1971L16.4342 10.8463" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M18.1073 12.9277L17.7827 17.9559C17.7278 18.7398 17.6829 19.349 16.2898 19.349H13.0841C11.691 19.349 11.6461 18.7398 11.5912 17.9559L11.2666 12.9277" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M13.853 16.6035H15.5158" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M13.4385 14.6055H15.9351" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round" />
                                    </g>
                                </svg>
                            </div>
                        </div>

                        <div class="uploadShapImage">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                <path d="M246.6 9.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 109.3 192 320c0 17.7 14.3 32 32 32s32-14.3 32-32l0-210.7 73.4 73.4c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-128-128zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 64c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-64c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 64c0 17.7-14.3 32-32 32L96 448c-17.7 0-32-14.3-32-32l0-64z" />
                            </svg>
                            <input type="file" name="selectShapeImage" accept="image/*">
                        </div>

                        <img id="user_image" src="" style="height: 100px; width: 100px; background-color: black;" />
                        <div class="resize-handle top-left"></div>
                        <div class="resize-handle top-center"></div>
                        <div class="resize-handle top-right"></div>
                        <div class="resize-handle bottom-left"></div>
                        <div class="resize-handle bottom-center"></div>
                        <div class="resize-handle bottom-right"></div>
                        <div class="resize-handle left-center"></div> <!-- Left-center handle -->
                        <div class="resize-handle right-center"></div> <!-- Right-center handle -->
                    </div>
                    <input type="file" id="image" accept="image/*" style="display: none" />
                    <img id="first_shape_img" src="" style="display: none;">

                    <img id="shape_img" src="" style="display: none;">
                    <canvas id="imageEditor1" class="canvas new"></canvas>
                </div>
                {{-- <img src="{{ $textData->image}}" alt=""> --}}
            </div>

        </div>
        <div class="edit-images-button-wrp">
            <div class="edit-images-button-inner text-box-wrp">
                <button class="design-sidebar-action" id="addTextButton">
                    <span>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.5 3.75H17.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M2.5 7.91699H17.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M2.5 12.083H17.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M2.5 16.25H17.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                    <h6>+ Text Box</h6>
                </button>
            </div>
            <div class="edit-images-button-inner fonts-edit-wrp">
                <button class="design-sidebar-action" design-id="2" onclick="toggleSidebar('sidebar')">
                    <span>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2.2251 5.97506V4.4584C2.2251 3.50006 3.0001 2.7334 3.9501 2.7334H16.0501C17.0084 2.7334 17.7751 3.5084 17.7751 4.4584V5.97506"
                                stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M10 17.2665V3.4248" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M6.7168 17.2666H13.2835" stroke="#0F172A" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <h6>Font</h6>
                </button>
            </div>
            <div class="edit-images-button-inner text-edit-wrp">
                <button class="design-sidebar-action" design-id="3" onclick="toggleSidebar('sidebar')">
                    <span>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M1.6582 4.9416V3.68327C1.6582 2.83327 2.34987 2.1416 3.19987 2.1416H13.9665C14.8165 2.1416 15.5082 2.83327 15.5082 3.68327V4.9416"
                                stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M8.5835 15.0833V2.7666" stroke="#0F172A" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M5.75 15.083H10.4" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M11.3999 8.61621H17.2416C17.8499 8.61621 18.3416 9.10788 18.3416 9.71621V10.3829"
                                stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M13.3999 17.8586V9.05859" stroke="#0F172A" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M11.6167 17.8584H15.1834" stroke="#0F172A" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <h6>Edit Text</h6>
                </button>
            </div>
            <div class="edit-images-button-inner color-edit-wrp">
                <button class="design-sidebar-action" design-id="4" onclick="toggleSidebar('sidebar')">
                    <span>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.33317 3.75033V15.0003C8.33317 15.9003 7.96649 16.7253 7.38316 17.3253L7.34984 17.3587C7.27484 17.4337 7.19151 17.5087 7.11651 17.567C6.86651 17.7837 6.58316 17.9503 6.29149 18.067C6.19983 18.1087 6.10817 18.142 6.0165 18.1753C5.6915 18.2837 5.3415 18.3337 4.99984 18.3337C4.77484 18.3337 4.54985 18.3087 4.33318 18.267C4.22485 18.242 4.1165 18.217 4.00817 18.1837C3.87484 18.142 3.74984 18.1003 3.62484 18.042C3.62484 18.0337 3.62483 18.0337 3.6165 18.042C3.38317 17.9253 3.15818 17.792 2.94984 17.6337L2.9415 17.6253C2.83317 17.542 2.73318 17.4587 2.64152 17.3587C2.54985 17.2587 2.45817 17.1587 2.3665 17.0503C2.20817 16.842 2.07484 16.617 1.95818 16.3837C1.96651 16.3753 1.96651 16.3753 1.95818 16.3753C1.95818 16.3753 1.95817 16.367 1.94983 16.3587C1.89983 16.242 1.85816 16.117 1.8165 15.992C1.78316 15.8837 1.75816 15.7753 1.73316 15.667C1.69149 15.4503 1.6665 15.2253 1.6665 15.0003V3.75033C1.6665 2.50033 2.49984 1.66699 3.74984 1.66699H6.24984C7.49984 1.66699 8.33317 2.50033 8.33317 3.75033Z"
                                stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M18.3333 13.7503V16.2503C18.3333 17.5003 17.5 18.3337 16.25 18.3337H5C5.34167 18.3337 5.69167 18.2837 6.01667 18.1753C6.10833 18.142 6.19999 18.1087 6.29166 18.067C6.58332 17.9503 6.86667 17.7837 7.11667 17.567C7.19167 17.5087 7.27501 17.4337 7.35001 17.3587L7.38332 17.3253L13.05 11.667H16.25C17.5 11.667 18.3333 12.5003 18.3333 13.7503Z"
                                stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M4.00857 18.1829C3.50857 18.0329 3.03358 17.7579 2.64191 17.3579C2.24191 16.9662 1.96689 16.4912 1.81689 15.9912C2.14189 17.0329 2.9669 17.8579 4.00857 18.1829Z"
                                stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M15.3083 9.40845L13.05 11.6668L7.3833 17.3251C7.96663 16.7251 8.33331 15.9001 8.33331 15.0001V6.95012L10.5916 4.6918C11.475 3.80846 12.6583 3.80846 13.5416 4.6918L15.3083 6.45846C16.1916 7.34179 16.1916 8.52512 15.3083 9.40845Z"
                                stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M4.99984 15.8337C5.46007 15.8337 5.83317 15.4606 5.83317 15.0003C5.83317 14.5401 5.46007 14.167 4.99984 14.167C4.5396 14.167 4.1665 14.5401 4.1665 15.0003C4.1665 15.4606 4.5396 15.8337 4.99984 15.8337Z"
                                stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                    <h6>Color</h6>
                </button>
            </div>

            
            <div class="edit-images-button-inner format-edit-wrp">
                <button class="design-sidebar-action" design-id="5" onclick="toggleSidebar('sidebar')">
                    <span>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.5 3.75H17.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M6.0498 7.91699H13.9498" stroke="#0F172A" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M2.5 12.083H17.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M6.0498 16.25H13.9498" stroke="#0F172A" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <h6>Format</h6>
                </button>
            </div>
            <div class="edit-images-button-inner photo-slider-wrp">
                <button class="design-sidebar-action" design-id="6" data-temp_id="{{$tempId}}" onclick="toggleSidebar('sidebar')">
                    <span>
                        <svg width="21" height="20" viewBox="0 0 21 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12.9998 18.3337H7.99984C5.9165 18.3337 4.6665 16.8337 4.6665 15.0003V5.00033C4.6665 3.16699 5.9165 1.66699 7.99984 1.66699H12.9998C15.0832 1.66699 16.3332 3.16699 16.3332 5.00033V15.0003C16.3332 16.8337 15.0832 18.3337 12.9998 18.3337Z"
                                stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M4.6665 13.3418H16.3332" stroke="#0F172A" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M2.1665 3.33301V16.6663" stroke="#0F172A" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M18.8335 3.33301V16.6663" stroke="#0F172A" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <h6>Photo Slider</h6>
                </button>
            </div>
            <div class="edit-images-button-inner edit-images-button-more dropdown">
                <button class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown">
                    <span>
                        <svg width="15" height="8" viewBox="0 0 15 8" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M14.0999 7.04154L8.66657 1.6082C8.0249 0.966536 6.9749 0.966536 6.33324 1.6082L0.899902 7.04154"
                                stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                    <h6>More</h6>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <div class="edit-images-button-dropdown">
                        <div class="edit-images-button-inner ">
                            <button class="design-sidebar-action" design-id="5" onclick="toggleSidebar('sidebar')">
                                <span>
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2.5 3.75H17.5" stroke="#0F172A" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M6.0498 7.91699H13.9498" stroke="#0F172A" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2.5 12.083H17.5" stroke="#0F172A" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M6.0498 16.25H13.9498" stroke="#0F172A" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <h6>Format</h6>
                            </button>
                        </div>
                        <div class="edit-images-button-inner">
                            <button class="design-sidebar-action" design-id="6" data-temp_id="{{$tempId}}" onclick="toggleSidebar('sidebar')">
                                <span>
                                    <svg width="21" height="20" viewBox="0 0 21 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12.9998 18.3337H7.99984C5.9165 18.3337 4.6665 16.8337 4.6665 15.0003V5.00033C4.6665 3.16699 5.9165 1.66699 7.99984 1.66699H12.9998C15.0832 1.66699 16.3332 3.16699 16.3332 5.00033V15.0003C16.3332 16.8337 15.0832 18.3337 12.9998 18.3337Z"
                                            stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M4.6665 13.3418H16.3332" stroke="#0F172A" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2.1665 3.33301V16.6663" stroke="#0F172A" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M18.8335 3.33301V16.6663" stroke="#0F172A" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <h6>Photo Slider</h6>
                            </button>
                        </div>
                    </div>
                </ul>
            </div>
        </div>

      
        @if ($eventID =="" || $eventID ==null || $isDraft ==null || $isDraft =="1" )    
        <div class="design-seting">
            <a href="#" class="d-flex">
                {{-- <span>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.0002 13.2797L5.65355 8.93306C5.14022 8.41973 5.14022 7.57973 5.65355 7.06639L10.0002 2.71973" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
                <h5 class="ms-2">Edit Design</h5> --}}
            </a>
            {{-- <button type="button" class="d-flex footer-bottom-btn li_event_detail" id="next_design"> --}}
            <button type="button" class="d-flex footer-bottom-btn li_event_details">
                <h5 class="me-2">Next: Event Details</h5>
                <span><svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M5.93994 13.2797L10.2866 8.93306C10.7999 8.41973 10.7999 7.57973 10.2866 7.06639L5.93994 2.71973"
                            stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
            </button>
        </div>
        @else
        <div class="guest-checkout">
            <div>
                <a href="#" class="cmn-btn edit_checkout" onclick="savePage4Data()">Save Changes</a>
            </div>
        </div>
        @endif
    </div>

    <div id="sidebar" class="sidebar choose-design-sidebar" style="right: -200%; width: 0px;">
        <!-- =====fonts-family-options==== -->
        <div class="design-sidebar design-sidebar_2 setting-category-wrp choose-design-form activity-schedule-inner ">
            <div class="d-flex align-items-center justify-content-between toggle-wrp new-event-sidebar-head">
                <h5>Change Font</h5>
                <button class="close-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
            
            @php
            $fonts = [
                "AbrilFatface-Regular" => "AbrilFatface",
                "AdleryPro-Regular" => "AdleryPro",
                "AgencyFB-Bold" => "AgencyFB",
                "AlexBrush-Regular" => "AlexBrush",
                "Allura-Regular" => "Allura",
                "ArcherBold" => "Archer",
                "Archer-Book" => "Archer-Book",
                "Archer-BookItalic" => "Archer-BookItalic",
                "Archer-ExtraLight" => "Archer-ExtraLight",
                "Archer-Hairline" => "Archer-Hairline",
                "Bebas-Regular" => "Bebas-Regular",
                "BookAntiqua" => "BookAntiqua",
                "Bungee-Regular" => "Bungee-Regular",
                "CandyCaneUnregistered" => "CandyCaneUnregistered",
                "CarbonBl-Regular" => "CarbonBl-Regular",
                "CarmenSans-ExtraBold" => "CarmenSans-ExtraBold",
                "CarmenSans-Regular" => "CarmenSans-Regular",
                "ChristmasCookies" => "ChristmasCookies",
                "ArialRoundedMTBold" => "ArialRoundedMTBold",
                "BebasNeue-Regular" => "BebasNeue-Regular",
                "Calibri" => "Calibri",
                "CCHammerHorror-BoldItalic" => "CCHammerHorror-BoldItalic",
                "CCThatsAllFolks-Bold" => "CCThatsAllFolks-Bold",
                "Changa-ExtraBold" => "Changa-ExtraBold",
                "ChristmasMint-Regular" => "ChristmasMint-Regular",
                "CinderelaPersonalUse-Regular" => "CinderelaPersonalUse-Regular",
                "Claiborne" => "Claiborne",
                "Corporative-Regular" => "Corporative-Regular",
                "foolish-Regular" => "foolish-Regular",
                "GrandHotel-Regular" => "GrandHotel-Regular",
                "Joshico-Regular" => "Joshico-Regular",
                "KaffeesatzEF-Schwarz" => "KaffeesatzEF-Schwarz",
                "KanedaGothic-Bold" => "KanedaGothic-Bold",
                "KanedaGothic-Medium" => "KanedaGothic-Medium",
                "KavarianSerif" => "KavarianSerif",
                "MelonScriptW00-CondRegular" => "MelonScriptW00-CondRegular",
                "Montserrat-Regular" => "Montserrat-Regular",
                "NautilusPompilius" => "NautilusPompilius",
                "OvinkRegular" => "OvinkRegular",
                "Rither" => "Rither",
                "Semringah" => "Semringah",
                "SittingDuckDEMO-Regular" => "SittingDuckDEMO-Regular",
                "SundayBest-Regular" => "SundayBest-Regular",
                "YippieYeah-Sans" => "YippieYeah-Sans",
                "Arboria-Bold" => "Arboria-Bold",
                "Arboria-Book" => "Arboria-Book",
                "Arboria-Light" => "Arboria-Light",
                "Arboria-Medium" => "Arboria-Medium",
                "Arboria-Thin" => "Arboria-Thin",
                "Cabin-Regular" => "Cabin-Regular",
                "Candara-Bold" => "Candara-Bold",
                "Candara" => "Candara",
                "CrimsonText-Bold" => "CrimsonText-Bold",
                "CrimsonText-BoldItalic" => "CrimsonText-BoldItalic",
                "CrimsonText-Italic" => "CrimsonText-Italic",
                "CrimsonText-Regular" => "CrimsonText-Regular",
                "CrimsonText-SemiBold" => "CrimsonText-SemiBold",
                "CrimsonText-SemiBoldItalic" => "CrimsonText-SemiBoldItalic",
                "QTMerryScript" => "QTMerryScript",
                "Rockwell-Condensed" => "Rockwell-Condensed",
                "Rockwell-CondensedBold" => "Rockwell-CondensedBold",
                "Tangerine-Bold" => "Tangerine-Bold",
                "Tangerine-Regular" => "Tangerine-Regular",
                "AlternateGotNo2D" => "AlternateGotNo2D",
                "CaveatBrush-Regular" => "CaveatBrush-Regular",
                "ModernLove-Regular" => "ModernLove-Regular",
                "NeueHaasGroteskText-Bold" => "NeueHaasGroteskText-Bold",
                "NeueHaasGroteskText-Medium" => "NeueHaasGroteskText-Medium",
                "NeueHaasGroteskText-Regular" => "NeueHaasGroteskText-Regular",
                "PTSansPro-Regular" => "PTSansPro-Regular",
                "Times New Roman" => "Times-New-Romon",
            ];
        @endphp
        <div class="used-fonts-wrp common-font-wrp">
            <h3>Select a Font</h3>
            @foreach ($fonts as $dataFont => $labelClass)
            <div class="font-selector form-check">
                <input class="form-check-input fontfamily" type="radio" name="flexRadioDefault" data-command="fontName" data-font="{{ $dataFont }}" id="{{ $labelClass }}Button">
                <label class="form-check-label {{ $labelClass }}" for="{{ $labelClass }}Button">
                    {{ $labelClass }}
                </label>
            </div>
            @endforeach
        </div>


            <div class="footer-buttons">
                <button class="cmn-btn font-reset-btn reset-btn">Reset</button>
                <button class="cmn-btn font-save-btn">Save</button>
            </div>
        </div>

        <!-- ======Font-size== -->
        <div class="design-sidebar design-sidebar_3 setting-category-wrp choose-design-form activity-schedule-inner ">
            <div class="d-flex align-items-center justify-content-between toggle-wrp new-event-sidebar-head">
                <h5>Size & Spacing</h5>
                <button class="close-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>

            <div class="size-spacing-main-wrp">
                <!-- Font Size Section -->
                <div class="size-spacing-inner font-size-wrp">
                    <div class="size-spacing-inner-top">
                        <h3>Font Size</h3>
                        <input type="text" id="fontSizeInput" value="20">
                    </div>
                    <div class="range-slider">
                        <div id="fontSizeTooltip" class="tooltip"></div>
                        <input id="fontSizeRange" type="range" step="1" class="range" value="20" min="1" max="100">
                    </div>
                </div>

                <!-- Letter Spacing Section -->
                <div class="size-spacing-inner font-size-wrp">
                    <div class="size-spacing-inner-top">
                        <h3>Letter Spacing</h3>
                        <input type="text" id="letterSpacingInput" value="0">
                    </div>
                    <div class="range-slider">
                        <div id="letterSpacingTooltip" class="tooltip"></div>
                        <input id="letterSpacingRange" type="range" step="1" class="range" value="0" min="0" max="100">
                    </div>
                </div>

                <!-- Line Height Section -->
                <div class="size-spacing-inner font-size-wrp">
                    <div class="size-spacing-inner-top">
                        <h3>Line Height</h3>
                        <input type="text" id="lineHeightInput" value="0">
                    </div>
                    <div class="range-slider">
                        <div id="lineHeightTooltip" class="tooltip"></div>
                        <input id="lineHeightRange" type="range" step="0.1" class="range" value="0" min="0.5" max="3">
                    </div>
                </div>
            </div>

            <div class="footer-buttons">
                <button class="cmn-btn edit-text-reset reset-btn">Reset</button>
                <button class="cmn-btn edit-text-save save-btn">Save</button>
            </div>
        </div>


        <!-- ===color-picker== -->
        <div class="design-sidebar design-sidebar_4 setting-category-wrp choose-design-form activity-schedule-inner">
            <div class="d-flex align-items-center justify-content-between toggle-wrp new-event-sidebar-head">
                <h5>Change Color</h5>
                <button class="close-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
            <div class="used-fonts-wrp common-font-wrp">
                <div class="form-check">
                    <input type="radio" name="colorType" value="font" checked class="form-check-input colorTypeInp" id="flexRadioDefaults" />
                    <label class="form-check-label" for="flexRadioDefaults">
                        Font Color
                    </label>
                </div>               
            </div>
            <input id="color-picker" type="color" value="#276cb8" />

            <div class="footer-buttons ">
                <button class="cmn-btn reset-btn color-reset">Reset</button>
                <button class="cmn-btn color-save color-save">Save</button>
            </div>
        </div>



        <!-- ===format text=== -->
        <div class="design-sidebar design-sidebar_5 setting-category-wrp choose-design-form activity-schedule-inner ">
            <div class="d-flex align-items-center justify-content-between toggle-wrp new-event-sidebar-head">
                <h5>Format Text</h5>
                <button class="close-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>

            <div class="format-text-main=wrp">
                <div class="format-text-inner format-type-wrp">
                    <h3>Type</h3>
                    <div class="format-text-inner-options">
                        <button class="bold-btn format-btn" data-command="bold">
                            <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M5.21338 4.5C5.21338 3.4 6.11338 2.5 7.21338 2.5H12.3334C14.9534 2.5 17.0834 4.63 17.0834 7.25C17.0834 9.87 14.9534 12 12.3334 12H5.21338V4.5Z"
                                    stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M5.21338 12H14.7134C17.3334 12 19.4634 14.13 19.4634 16.75C19.4634 19.37 17.3334 21.5 14.7134 21.5H7.21338C6.11338 21.5 5.21338 20.6 5.21338 19.5V12V12Z"
                                    stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                        <button class="italic-btn format-btn" data-command="italic">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.62 3H18.87" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M5.12 21H14.37" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M14.25 3L9.75 21" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                        <button class="underline-btn format-btn" data-command="underline">
                            <svg width="25" height="30" viewBox="0 0 25 30" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g filter="url(#filter0_d_5633_68294)">
                                    <path d="M5.66675 21H19.6667" stroke="#0F172A" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M5.66675 3V10C5.66675 13.87 8.79675 17 12.6667 17C16.5367 17 19.6667 13.87 19.6667 10V3"
                                        stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </g>
                                <defs>
                                    <filter id="filter0_d_5633_68294" x="-3.33325" y="0" width="32"
                                        height="32" filterUnits="userSpaceOnUse"
                                        color-interpolation-filters="sRGB">
                                        <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                        <feColorMatrix in="SourceAlpha" type="matrix"
                                            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                                        <feOffset dy="4" />
                                        <feGaussianBlur stdDeviation="2" />
                                        <feComposite in2="hardAlpha" operator="out" />
                                        <feColorMatrix type="matrix"
                                            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
                                        <feBlend mode="normal" in2="BackgroundImageFix"
                                            result="effect1_dropShadow_5633_68294" />
                                        <feBlend mode="normal" in="SourceGraphic"
                                            in2="effect1_dropShadow_5633_68294" result="shape" />
                                    </filter>
                                </defs>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="format-text-inner font-tranform-wrp">
                    <h3>Font Size</h3>
                    <div class="format-text-inner-options">
                        <button class="uppercase-btn size-btn format-btn" data-command="uppercase">
                            AA
                        </button>
                        <button class="lowercase-btn size-btn format-btn" data-command="lowercase">
                            aa
                        </button>
                        <button class="capitalize-btn size-btn format-btn" data-command="capitalize">
                            Aa
                        </button>
                    </div>
                </div>
                <div class="format-text-inner font-alignment-wrp">
                    <h3>Alignment</h3>
                    <div class="format-text-inner-options">
                        <button class="justyfy-left-btn format-btn" data-command="justifyLeft">
                            <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.5 4.5H3.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M12.5 9.5H3.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M21.5 14.5H3.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M21.5 19.5H3.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                        <button class="justyfy-center-btn format-btn" data-command="justifyCenter">
                            <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.5 4.5H21.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M7.76001 9.5H17.24" stroke="#0F172A" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M3.5 14.5H21.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M7.76001 19.5H17.24" stroke="#0F172A" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <button class="justyfy-right-btn format-btn" data-command="justifyRight">
                            <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.5 4.5H21.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M12.5 9.5H21.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M3.5 14.5H21.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M3.5 19.5H21.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                        <button class="justyfy-full-btn format-btn" data-command="justifyFull">
                            <svg width="29" height="29" viewBox="0 0 29 29" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g filter="url(#filter0_d_5633_68315)">
                                    <path d="M5.5 4.5H23.5" stroke="#0F172A" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M5.5 9.5H23.5" stroke="#0F172A" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M5.5 14.5H23.5" stroke="#0F172A" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M5.5 19.5H23.5" stroke="#0F172A" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </g>
                                <defs>
                                    <filter id="filter0_d_5633_68315" x="-1.5" y="0" width="32" height="32"
                                        filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                        <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                        <feColorMatrix in="SourceAlpha" type="matrix"
                                            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                                        <feOffset dy="4" />
                                        <feGaussianBlur stdDeviation="2" />
                                        <feComposite in2="hardAlpha" operator="out" />
                                        <feColorMatrix type="matrix"
                                            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
                                        <feBlend mode="normal" in2="BackgroundImageFix"
                                            result="effect1_dropShadow_5633_68315" />
                                        <feBlend mode="normal" in="SourceGraphic"
                                            in2="effect1_dropShadow_5633_68315" result="shape" />
                                    </filter>
                                </defs>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="footer-buttons">
                <button class="cmn-btn reset-btn formate-text-reset">Reset</button>
                <button class="cmn-btn formate-text-save">Save</button>
            </div>
        </div>

        <!-- ====upload=img=== -->
        <div class="design-sidebar design-sidebar_6 setting-category-wrp choose-design-form activity-schedule-inner ">
            <div class="d-flex align-items-center justify-content-between toggle-wrp new-event-sidebar-head">
                <h5>Photo Slider</h5>
                <button class="close-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>

            <div class="upload-image-main-wrp">
                <div class="upload-wrp-img">
                    <svg width="128" height="131" viewBox="0 0 128 131" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M63.9364 106.84C87.4863 106.84 106.579 87.748 106.579 64.114C106.579 40.48 87.4022 21.3877 63.9364 21.3877C40.3864 21.3877 21.2942 40.48 21.2942 64.114C21.2942 87.748 40.3864 106.84 63.9364 106.84Z"
                            fill="#F8F9FD" />
                        <path
                            d="M103.803 36.1067C105.707 36.1067 107.251 34.5628 107.251 32.6583C107.251 30.7539 105.707 29.21 103.803 29.21C101.898 29.21 100.354 30.7539 100.354 32.6583C100.354 34.5628 101.898 36.1067 103.803 36.1067Z"
                            fill="#F8F9FD" />
                        <path
                            d="M108.847 22.6494C110.147 22.6494 111.202 21.5951 111.202 20.2944C111.202 18.9938 110.147 17.9395 108.847 17.9395C107.546 17.9395 106.492 18.9938 106.492 20.2944C106.492 21.5951 107.546 22.6494 108.847 22.6494Z"
                            fill="#F8F9FD" />
                        <path
                            d="M23.5637 36.0225C24.8644 36.0225 25.9187 34.9681 25.9187 33.6675C25.9187 32.3669 24.8644 31.3125 23.5637 31.3125C22.2631 31.3125 21.2087 32.3669 21.2087 33.6675C21.2087 34.9681 22.2631 36.0225 23.5637 36.0225Z"
                            fill="#F8F9FD" />
                        <path
                            d="M9.18142 81.4405C11.5969 81.4405 13.555 79.4824 13.555 77.0669C13.555 74.6515 11.5969 72.6934 9.18142 72.6934C6.76597 72.6934 4.80786 74.6515 4.80786 77.0669C4.80786 79.4824 6.76597 81.4405 9.18142 81.4405Z"
                            fill="#F8F9FD" />
                        <g filter="url(#filter0_d_5633_68430)">
                            <path
                                d="M92.7229 97.4562L35.8363 97.8643C32.5528 97.879 29.8752 95.232 29.8313 91.9698L29.3904 37.5435C29.3721 34.2594 32.0156 31.5844 35.2772 31.5441L92.1637 31.1359C95.4473 31.1213 98.1248 33.7683 98.1688 37.0304L98.6096 91.4568C98.628 94.7409 95.9844 97.4159 92.7229 97.4562Z"
                                fill="white" />
                        </g>
                        <path
                            d="M89.5206 89.3293L39.5127 89.7078C38.0981 89.7203 36.9596 88.6037 36.9454 87.1889L36.566 42.2081C36.5518 40.7933 37.667 39.6558 39.0816 39.6432L89.0895 39.2647C90.5041 39.2521 91.6427 40.3688 91.6568 41.7836L92.0362 86.7644C92.0504 88.1792 90.9352 89.3167 89.5206 89.3293Z"
                            fill="#F1F5F9" />
                        <path d="M65.3349 72.4481L47.0922 72.5748L56.1354 62.1661L65.3349 72.4481Z" fill="#94A3B8" />
                        <path d="M80.4593 72.3255L54.1617 72.5125L67.1856 57.5653L80.4593 72.3255Z" fill="#CBD5E1" />
                        <path
                            d="M56.1136 57.5884C57.5559 56.3479 57.7184 54.1718 56.4765 52.7278C55.2346 51.2838 53.0586 51.1189 51.6162 52.3594C50.1739 53.5998 50.0114 55.776 51.2533 57.22C52.4952 58.664 54.6712 58.8289 56.1136 57.5884Z"
                            fill="#94A3B8" />
                        <path
                            d="M76.6661 95.4833C76.4053 96.5917 76.0141 97.7653 75.5577 98.7433C74.3189 101.156 72.3629 103.046 69.9505 104.285C67.4729 105.524 64.5389 106.046 61.6049 105.394C54.6937 103.959 50.2602 97.1785 51.6946 90.2673C53.129 83.3561 59.8445 78.8574 66.7557 80.357C69.2333 80.8786 71.3849 82.1174 73.2105 83.8125C76.2749 86.8769 77.5788 91.3105 76.6661 95.4833Z"
                            fill="#2DA9FC" />
                        <path
                            d="M68.2535 91.767H65.3195V88.833C65.3195 88.2462 64.8631 87.7246 64.2111 87.7246C63.6243 87.7246 63.1027 88.181 63.1027 88.833V91.767H60.1687C59.5819 91.767 59.0603 92.2234 59.0603 92.8754C59.0603 93.5274 59.5167 93.9838 60.1687 93.9838H63.1027V96.9178C63.1027 97.5046 63.5591 98.0262 64.2111 98.0262C64.7979 98.0262 65.3195 97.5698 65.3195 96.9178V93.9838H68.2535C68.8403 93.9838 69.3619 93.5274 69.3619 92.8754C69.3619 92.2234 68.8403 91.767 68.2535 91.767Z"
                            fill="white" />
                        <defs>
                            <filter id="filter0_d_5633_68430" x="7.39038" y="20.1357" width="113.219"
                                height="110.729" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                <feColorMatrix in="SourceAlpha" type="matrix"
                                    values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                                <feOffset dy="11" />
                                <feGaussianBlur stdDeviation="11" />
                                <feColorMatrix type="matrix"
                                    values="0 0 0 0 0.397708 0 0 0 0 0.47749 0 0 0 0 0.575 0 0 0 0.27 0" />
                                <feBlend mode="normal" in2="BackgroundImageFix"
                                    result="effect1_dropShadow_5633_68430" />
                                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_5633_68430"
                                    result="shape" />
                            </filter>
                        </defs>
                    </svg>
                </div>
                <div class="uploda-wrp-content">
                    <h3>Upload Images for slider</h3>
                    <p>You may choose up to three photos for a slideshow that your guests can view along with their
                        online invitation.</p>
                    <button class="cmn-btn">
                        <input type="file" class="slider_photo" accept="image/png, image/jpeg">
                        Upload Image
                    </button>
                </div>
            </div>
        </div>

        <!-- ====uploaded-images== -->
        <div class="design-sidebar design-sidebar_7 setting-category-wrp choose-design-form activity-schedule-inner ">
            <div class="d-flex align-items-center justify-content-between toggle-wrp new-event-sidebar-head">
                <h5>Photo Slider</h5>
                <button class="close-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>

            <div class="uploaded-images-wrp">
                @if(!session('edit_design_closed'))
                <div class="uploaded-images-warning" id="edit_design_tip_bar">
                    <span>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9.99984 18.3337C14.5832 18.3337 18.3332 14.5837 18.3332 10.0003C18.3332 5.41699 14.5832 1.66699 9.99984 1.66699C5.4165 1.66699 1.6665 5.41699 1.6665 10.0003C1.6665 14.5837 5.4165 18.3337 9.99984 18.3337Z"
                                stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M10 6.66699V10.8337" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M9.99561 13.333H10.0031" stroke="#94A3B8" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <p>You may choose up to three photos for a slideshow that your guests can view along with their
                        online invitation.</p>
                    <button id="edit_design_tip_bar_close">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.33398 3.33301L12.6667 12.6657" stroke="#94A3B8" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M3.33331 12.6657L12.666 3.33301" stroke="#94A3B8" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
                @endif


                <div class="uploaded-images-inner">
                    <div class="total-upload-img">
                        <h3 class="slider_image_count">1/3 Photos</h3>
                    </div>

                    <div class="uploaded-images-detail">
                        <div class="uploaded-img-card ">
                            <svg class="upload-img-cart-extra-img" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9 10C10.1046 10 11 9.10457 11 8C11 6.89543 10.1046 6 9 6C7.89543 6 7 6.89543 7 8C7 9.10457 7.89543 10 9 10Z"
                                    stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M13 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22H15C20 22 22 20 22 15V10"
                                    stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M18 8V2L20 4" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M18 2L16 4" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M2.66992 18.9496L7.59992 15.6396C8.38992 15.1096 9.52992 15.1696 10.2399 15.7796L10.5699 16.0696C11.3499 16.7396 12.6099 16.7396 13.3899 16.0696L17.5499 12.4996C18.3299 11.8296 19.5899 11.8296 20.3699 12.4996L21.9999 13.8996"
                                    stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <img class="photo-slider-1 slider_img" data-delete="1" src="" alt="">
                            <button class="uploaded-img-card-edit">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M8.84006 3.73283L3.36673 9.52616C3.16006 9.74616 2.96006 10.1795 2.92006 10.4795L2.6734 12.6395C2.58673 13.4195 3.14673 13.9528 3.92006 13.8195L6.06673 13.4528C6.36673 13.3995 6.78673 13.1795 6.9934 12.9528L12.4667 7.15949C13.4134 6.15949 13.8401 5.01949 12.3667 3.62616C10.9001 2.24616 9.78673 2.73283 8.84006 3.73283Z"
                                        stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M7.92676 4.7002C8.21342 6.5402 9.70676 7.94686 11.5601 8.13353"
                                        stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <input type="file" class="slider_photo" accept="image/png, image/jpeg">
                            </button>
                            <button class="uploaded-img-card-delete delete-slider-1 delete_silder">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14 3.98665C11.78 3.76665 9.54667 3.65332 7.32 3.65332C6 3.65332 4.68 3.71999 3.36 3.85332L2 3.98665"
                                        stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M5.6665 3.31301L5.81317 2.43967C5.91984 1.80634 5.99984 1.33301 7.1265 1.33301H8.87317C9.99984 1.33301 10.0865 1.83301 10.1865 2.44634L10.3332 3.31301"
                                        stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M12.5664 6.09375L12.1331 12.8071C12.0598 13.8537 11.9998 14.6671 10.1398 14.6671H5.85977C3.99977 14.6671 3.93977 13.8537 3.86644 12.8071L3.43311 6.09375"
                                        stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M6.88672 11H9.10672" stroke="#64748B" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M6.3335 8.33301H9.66683" stroke="#64748B" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                        <div class="uploaded-img-card">
                            <svg class="upload-img-cart-extra-img" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9 10C10.1046 10 11 9.10457 11 8C11 6.89543 10.1046 6 9 6C7.89543 6 7 6.89543 7 8C7 9.10457 7.89543 10 9 10Z"
                                    stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M13 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22H15C20 22 22 20 22 15V10"
                                    stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M18 8V2L20 4" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M18 2L16 4" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M2.66992 18.9496L7.59992 15.6396C8.38992 15.1096 9.52992 15.1696 10.2399 15.7796L10.5699 16.0696C11.3499 16.7396 12.6099 16.7396 13.3899 16.0696L17.5499 12.4996C18.3299 11.8296 19.5899 11.8296 20.3699 12.4996L21.9999 13.8996"
                                    stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>

                            <img class="photo-slider-2 slider_img" data-delete="2" src="" alt="" style="display: none">
                            <button class="uploaded-img-card-edit">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M8.84006 3.73283L3.36673 9.52616C3.16006 9.74616 2.96006 10.1795 2.92006 10.4795L2.6734 12.6395C2.58673 13.4195 3.14673 13.9528 3.92006 13.8195L6.06673 13.4528C6.36673 13.3995 6.78673 13.1795 6.9934 12.9528L12.4667 7.15949C13.4134 6.15949 13.8401 5.01949 12.3667 3.62616C10.9001 2.24616 9.78673 2.73283 8.84006 3.73283Z"
                                        stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M7.92676 4.7002C8.21342 6.5402 9.70676 7.94686 11.5601 8.13353"
                                        stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <input type="file" class="slider_photo_2 " accept="image/png, image/jpeg">

                            </button>
                            <button class="uploaded-img-card-delete delete-slider-2 delete_silder">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14 3.98665C11.78 3.76665 9.54667 3.65332 7.32 3.65332C6 3.65332 4.68 3.71999 3.36 3.85332L2 3.98665"
                                        stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M5.6665 3.31301L5.81317 2.43967C5.91984 1.80634 5.99984 1.33301 7.1265 1.33301H8.87317C9.99984 1.33301 10.0865 1.83301 10.1865 2.44634L10.3332 3.31301"
                                        stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M12.5664 6.09375L12.1331 12.8071C12.0598 13.8537 11.9998 14.6671 10.1398 14.6671H5.85977C3.99977 14.6671 3.93977 13.8537 3.86644 12.8071L3.43311 6.09375"
                                        stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M6.88672 11H9.10672" stroke="#64748B" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M6.3335 8.33301H9.66683" stroke="#64748B" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                        <div class="uploaded-img-card">
                            <svg class="upload-img-cart-extra-img" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9 10C10.1046 10 11 9.10457 11 8C11 6.89543 10.1046 6 9 6C7.89543 6 7 6.89543 7 8C7 9.10457 7.89543 10 9 10Z"
                                    stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M13 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22H15C20 22 22 20 22 15V10"
                                    stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M18 8V2L20 4" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M18 2L16 4" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M2.66992 18.9496L7.59992 15.6396C8.38992 15.1096 9.52992 15.1696 10.2399 15.7796L10.5699 16.0696C11.3499 16.7396 12.6099 16.7396 13.3899 16.0696L17.5499 12.4996C18.3299 11.8296 19.5899 11.8296 20.3699 12.4996L21.9999 13.8996"
                                    stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <img class="photo-slider-3 slider_img" data-delete="3" src="" alt="" style="display: none">
                            <button class="uploaded-img-card-edit">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M8.84006 3.73283L3.36673 9.52616C3.16006 9.74616 2.96006 10.1795 2.92006 10.4795L2.6734 12.6395C2.58673 13.4195 3.14673 13.9528 3.92006 13.8195L6.06673 13.4528C6.36673 13.3995 6.78673 13.1795 6.9934 12.9528L12.4667 7.15949C13.4134 6.15949 13.8401 5.01949 12.3667 3.62616C10.9001 2.24616 9.78673 2.73283 8.84006 3.73283Z"
                                        stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M7.92676 4.7002C8.21342 6.5402 9.70676 7.94686 11.5601 8.13353"
                                        stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <input type="file" class="slider_photo_3" accept="image/png, image/jpeg">
                            </button>
                            <button class="uploaded-img-card-delete delete-slider-3 delete_silder">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14 3.98665C11.78 3.76665 9.54667 3.65332 7.32 3.65332C6 3.65332 4.68 3.71999 3.36 3.85332L2 3.98665"
                                        stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M5.6665 3.31301L5.81317 2.43967C5.91984 1.80634 5.99984 1.33301 7.1265 1.33301H8.87317C9.99984 1.33301 10.0865 1.83301 10.1865 2.44634L10.3332 3.31301"
                                        stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M12.5664 6.09375L12.1331 12.8071C12.0598 13.8537 11.9998 14.6671 10.1398 14.6671H5.85977C3.99977 14.6671 3.93977 13.8537 3.86644 12.8071L3.43311 6.09375"
                                        stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M6.88672 11H9.10672" stroke="#64748B" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M6.3335 8.33301H9.66683" stroke="#64748B" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-buttons">
                <button class="cmn-btn save-slider-image">Save</button>
                <button class="cmn-btn update-slider-image" style="display:none;">Save</button>

            </div> 
            
        </div>
    </div>
</main>