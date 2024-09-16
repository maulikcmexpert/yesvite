<div id="richTextEditor" class="rich-text-editor">
    <section class="main-seciton"  id="imageEditor">
        <div class="birthday-card-main">
            <img src="{{ asset('assets/event/image/design/images/post_temp_6/post_temp_6_foot_img.svg') }}" alt="" class="post_temp_6_foot_img" draggable="false" style="user-select: none;">
            <img src="{{ asset('assets/event/image/design/images/post_temp_6/post_temp_6_head_img.svg') }}" alt="" class="post_temp_6_head_img" draggable="false" style="user-select: none;">

            <div class="birthday-card-main-content">
                <h2 class="titlename " >Birthday Party</h2>
                <div class="birthday-card-main-content-name">
                    <div class="birthday-card-center-img">
                        <!-- <img src="{{ asset('assets/event/image/design/images/birthday/post_temp_1_center_img.png') }}post_temp_6/post_temp_6_center_circle.png" alt="" class="post_temp_6_center_circle"> -->
                        <img draggable="false" style="user-select: none;" id="editableImage"  src="{{ asset('assets/event/image/design/images/post_temp_6/post_temp_6_center_img.png') }}" alt="" class="post_temp_6_center_img" style="width: 100%; max-width: 600px; cursor: pointer;">

                        <input type="file" id="imageUploaders" accept="image/*" style="display: none;">
                    </div>
                    <h4>
                        <img draggable="false" style="user-select: none;" src="{{ asset('assets/event/image/design/images/post_temp_6/post_temp_6_name_img.svg') }}" alt="" class="post_temp_6_name_img">
                        <h4  class="text-box draggable-type-4" contenteditable="true" > Avery Davis</h4>
                    </h4>

                </div>
            </div>

            <div class="birthday-card-main-content-detail">
                <h5  class="text-box draggable-type-4" contenteditable="true" >Come join us for an evening of
                    entertainment, food, and festivities.
                </h5>
            </div>
            <div class = "birthday-card-address "  >
                <h6 class="text-box draggable-type-3" contenteditable="true">Sunday, 23 June 2023 at 8 PM
                123 Anywhere St, Any City, ST 12345</h6>

            </div>
        </div>
    </section>
    </div>
