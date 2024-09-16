


<div id="richTextEditor" class="rich-text-editor">
    <section class="main-seciton">
        <div class="birthday-card-main" id="imageEditor">
            <input type="hidden" name="type" id="birthday" />
            <!-- Your existing SVG, images, and other static elements -->
            <svg class="blue-bg" preserveAspectRatio="none" viewBox="0 0 460 409" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <mask id="mask0_10_1757" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="-1" y="0"
                    width="461" height="409">
                    <path d="M-0.0149841 409V0.579758L229.99 55.0756L460 0.579758V409H-0.0149841Z"
                        fill="white" />
                </mask>
                <g mask="url(#mask0_10_1757)">
                    <path d="M460 409H-0.0149841V0.987267H460V409Z" fill="#3B95B3" />
                </g>
            </svg>

            <img src="{{ asset('assets/event/image/design/images/post_temp_1/post_temp_1_top_head_img.png') }}" alt=""
                class="post_temp_1_top_head_img" draggable="false" style="user-select: none;">
            <div class="main-center-img">
                <img src="{{ asset('assets/event/image/design/images/post_temp_1/post_temp_1_ballon_left.svg') }}" alt=""
                    class="post_temp_1_ballon_left" draggable="false" style="user-select: none;">
                <img src="{{ asset('assets/event/image/design/images/post_temp_1/post_temp_1_ballon_right.svg') }}" alt=""
                    class="post_temp_1_ballon_right" draggable="false" style="user-select: none;">
                <img id="editableImage" src="{{ asset('assets/event/image/design/images/post_temp_1/post_temp_1_center_img.png') }}"
                    alt="Editable Image" style="width: 100%; max-width: 600px; cursor: pointer;" draggable="false" style="user-select: none;">
                <input type="file" id="imageUploaders" accept="image/*" style="display: none;">
            </div>
            <div class ="main-center-name-wrp" >
                <!-- Editable and draggable text area -->
                <h3 class="text-box draggable-type-3" contenteditable="true">Aaron Loeb</h3>
                <img src="{{ asset('assets/event/image/design/images/post_temp_1/post_temp_1_name_img.svg') }}" alt=""
                    class="post_temp_1_name_img" draggable="false" style="user-select: none;">
            </div>
            <div class="birthday-card-main-content">
                <h2 class="text-box draggable-type-2" contenteditable="true">Birthday party</h2>

                <br>
                <br>

                <div class="birthday-card-content-inner">
                    <div class="birthday-card-content-inner-date text-box draggable-type-2" contenteditable="true">
                        <h4>December</h4>
                        <h6>05</h6>
                    </div>

                    <div class="birthday-card-content-inner-info ">
                        <p class="text-box draggable-type-2" contenteditable="true">123 Anywhere st., any city, st 12345</p>
                        <h3 class="text-box draggable-type-2" contenteditable="true">04:00 <span>P.M.</span></h3>
                    </div>
                </div>
            </div>

        </div>
    </section>

</div>

