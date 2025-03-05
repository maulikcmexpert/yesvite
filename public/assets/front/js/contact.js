$(document).ready(function () {
    var base_url = $("#base_url").val();

    // var page = 1;
  
  
    var base_url=$('#base_url').val();
    var busy1 = false;
    var busy1gl = false;
    var busy2=false;
    var limit = 50;
    var offset = 0;
    var offsetlg = 0;

    
    var offset1 = 0;

    let searchTimeout; // Store timeout reference

    $(document).on("click", ".see_all_group", function () {
        // $("search_user").val("");
        toggleSidebar("sidebar_groups");
    });
    $(document).on("click", ".new_group", function () {
        // $("search_user").val("");
        toggleSidebar("sidebar_add_groups");

    });
    $(document).on("click", ".group_toggle_close_btn", function () {
        toggleSidebar("sidebar_groups");
    });
    $(document).on("click", ".add_new_group", function () {
        var group_name = $("#new_group_name").val();
        NogroupData = false;
        if (group_name == "") {
            $("#group_name_error")
                .css("display", "block")
                .css("color", "red")
                .text("Please enter group name");
            return;
        } else {
            $("#group_name_error").css("display", "none");
            toggleSidebar("sidebar_add_group_member");
            var type = "group";
        }
    });
    $("#new_group_name").on("input", function (e) {
        var groupname=$(this).val();
        if (groupname === "")
             {
                  $("#group_name_error")
                  .text("Please enter group name")
                  .css("color", "red");
             } else {
                   $("#group_name_error").text("");
            }
    });
    $("#new_group_name").on("keydown", function (e) {
        if (e.key === "Enter" || e.keyCode === 13) {
            e.preventDefault(); // Prevents the default action of submitting the form or adding a new line
        }
    });

    $(document).on("click", ".add_new_group_member", function () {
        var group_name = $("#new_group_name").val();
        var selectedValues = [];
        $("#groupUsers .user_group_member:checked").each(function () {
            selectedValues.push({
                id: $(this).val(),
                prefer_by: $(this).data("preferby"),
            });
        });
        if (selectedValues.length > 0) {
            // $('#home_loader').css('display','flex');
            $.ajax({
                url: base_url + "event/add_new_group",
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: {
                    groupmember: selectedValues,
                    groupname: group_name,
                },
                success: function (response) {
                    if (response.status == "1") {
                        if (response.status == 401 && response.info == "logout") {
                            window.location.href = "/"; // Redirect to home page
                            return;
                        }
                        toastr.success('Group Created Successfully');
                        $('<div id="pageOverlay"></div>').css({
                            position: 'fixed',
                            top: 0,
                            left: 0,
                            width: '100%',
                            height: '100%',
                            background: 'rgba(255, 255, 255, 0)', // Transparent background
                            zIndex: 9999
                        }).appendTo('body');
                        // $('#home_loader').css('display','none');
               
                        window.location.reload();
                        // var grplth = $(".group_list .listgroups").length;
                        // if (grplth == 0) {
                        //     $(".group_list").html("");
                        // }
                        // $(".group_list").append(response.view);
    
                        // // var newItem = `
                        // //     <div class="swiper-slide">
                        // //         <div class="group-card view_members" data-id="${response.data.group_id}">
                        // //             <div>
                        // //                 <h4>${response.data.groupname}</h4>
                        // //                 <p>${response.data.member_count} Guests</p>
                        // //             </div>
                        // //             <span class="ms-auto">
                        // //                 <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        // //                     <path d="M5.93994 13.7797L10.2866 9.43306C10.7999 8.91973 10.7999 8.07973 10.2866 7.56639L5.93994 3.21973" stroke="#E2E8F0" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        // //                 </svg>
                        // //             </span>
                        // //         </div>
                        // //     </div>
                        // // `;
    
                        // // swiper[0].appendSlide(newItem);
                        // // swiper[0].update(); // Update Swiper after adding the new slide
                        // // swiper[1].appendSlide(newItem);
                        // // swiper[1].update(); // Update Swiper after adding the new slide
                        // // swiper[2].appendSlide(newItem);
                        // // swiper[2].update(); // Update Swiper after adding the new slide
    
                        // $(".user_choice_group .user_choice").prop("checked", false);
                        // toggleSidebar("sidebar_groups");
                        // groupToggleSearch("");
                    }
                },
                error: function (xhr, status, error) {
                    console.log("AJAX error: " + error);
                    toastr.error(error);
                    $('#home_loader').css('display','none');
                },
            });
        } else {
            toastr.error("Please select Member");
        }
    });
    // $(document).on("click", ".add_new_group", function () {
    //     // $("search_user").val("");
    //     toggleSidebar("sidebar_add_group_member");
    // });

    $(document).on("click", ".group_toggle_close_btn", function () {
        // $("search_user").val("");
        toggleSidebar('');
    });
    $(document).on("click", ".overlay", function () {
        toggleSidebar();
    });
    $(document).on("keyup", "#group_toggle_search", function () {
        var search_name = $(this).val();
        clearTimeout(searchTimeout);

        searchTimeout = setTimeout(function () {
        groupToggleSearch(search_name);
    }, 1000);

    });
    function groupToggleSearch(search_name = null) {
        if (search_name == null) {
            search_name = "";
        }
        $.ajax({
            url: base_url + "event/group_toggle_search",
            type: "POST",
            data: {
                search_name: search_name,
                isGroup:1,
                _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
            },
            beforeSend: function () {
                $("#home_loader").css("display", "flex");
            },
        })
            .done(function (data) {
                console.log(data.html);
                if (data.status == 401 && data.info == "logout") {
                    window.location.href = "/"; // Redirect to home page
                    return;
                }
                if (data.html == " ") {
                    $("#loader").html("No more contacts found");
                    $("#home_loader").hide();

                    return;
                }
                $("#home_loader").hide();
                $(".group_search_list_toggle").html(data.html);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                // alert("server not responding...");
                toastr.error("server not responding...");
                $("#home_loader").hide();

            });
    }
    
    function toggleSidebar(id = null) {
        console.log(id);
        if (id == "sidebar_add_co_host") {
            document.body.classList.add("no-scroll"); // Disable background scrolling
        }
    
        if (id == "sidebar_groups") {
            document.body.classList.add("no-scroll"); // Disable background scrolling
        }
        if (id == "sidebar_potluck") {
            document.body.classList.add("no-scroll"); // Disable background scrolling
        }
        const allSidebars = document.querySelectorAll(".sidebar");
        const allOverlays = document.querySelectorAll(".overlay");
        // $(".floatingfocus").removeClass("floatingfocus");
        $("#registry_link_error").text("");
        $(".common_error").text("");
    
        allSidebars.forEach((sidebar) => {
            if (sidebar.style.right === "0px") {
                sidebar.style.right = "-200%";
                sidebar.style.width = "0px";
            }
        });
    
        allOverlays.forEach((overlay) => {
            if (overlay.classList.contains("visible")) {
                overlay.classList.remove("visible");
            }
        });
        if (id == null) {
            document.body.classList.remove("no-scroll"); // Re-enable background scrolling
            return;
        }
        const sidebar = document.getElementById(id);
        const overlay = document.getElementById(id + "_overlay");
    
        if (sidebar.style.right === "0px") {
            sidebar.style.right = "-200%";
            sidebar.style.width = "0px";
            if (overlay) {
                overlay.classList.remove("visible");
            }
        } else {
            sidebar.style.right = "0px";
            sidebar.style.width = "100%";
            if (overlay) {
                overlay.classList.add("visible");
            }
        }
    }
    $("#groupUsers").on("scroll", function () {
    
        if (busy1gl) return; 
        var scrollTop = $(this).scrollTop(); 
        var scrollHeight = $(this)[0].scrollHeight; 
        var elementHeight = $(this).height();
            if (scrollTop + elementHeight >= scrollHeight-2) {
                busy1gl = true;
                offsetlg += limit;
                
                var type="yesvite";
                var search_name=""
                // var search_name = $('.search_name').val();
                // if(search_name!=""){
                //     offsetlg=null;
                // }
            loadMoreDataList(search_name,type,offsetlg,limit,1,1);
        }
});
$("#product-scroll").on("scroll", function () {
    
        if (busy1) return; 
        var scrollTop = $(this).scrollTop(); 
        var scrollHeight = $(this)[0].scrollHeight; 
        var elementHeight = $(this).height();
            if (scrollTop + elementHeight >= scrollHeight-2) {
                busy1 = true;
                offset += limit;
                
                var type="yesvite";
                var search_name = $('.search_name').val();
                if(search_name!=""){
                    offset=null;
                }
            loadMoreData(search_name,type,offset,limit,1);
        }
});



let debounceTimer;
$("#product-scroll-phone").on("scroll", function () {
   
    clearTimeout(debounceTimer);
    // debounceTimer = setTimeout(() => {
        if (busy2) return; 

        var scrollTop = $(this).scrollTop(); 
        var scrollHeight = $(this)[0].scrollHeight; 
        var elementHeight = $(this).height();
     
            if (scrollTop + elementHeight >= scrollHeight-1) {
                busy2 = true;
                offset1 += limit;
                var type="phone";
                var search_phone = $('.search_phone').val();
                if(search_phone!=""){
                    offset1=null;
                }

                loadMorePhones(search_phone,type,offset1,limit,1);
            // function loadMoreData(page, search_name)
            // loadMoreGroups(page, search_group);
            // loadMorePhones(page, search_phone);
        }
    // }, 200);
});

// let debounceTimer;
// let busy2 = false; // Ensure busy2 is initialized

// $("#product-scroll-phone").on("scroll", function () {
//     clearTimeout(debounceTimer);

//     debounceTimer = setTimeout(() => {
//         if (busy2) return;

//         let scrollTop = $(this).scrollTop();
//         let scrollHeight = $(this)[0].scrollHeight;
//         let elementHeight = $(this).height();

//         console.log({
//             scrollTop,
//             scrollHeight,
//             elementHeight
//         });

//         if (scrollTop + elementHeight >= scrollHeight - 10) { // Adding a buffer of 10px
//             busy2 = true;

//             offset1 += limit; // Increment the offset
//             let type = "phone";

//             loadMorePhones(null, type, offset1, limit)
//                 .then(() => {
//                     busy2 = false; // Reset busy2 after successful load
//                 })
//                 .catch(err => {
//                     console.error("Error loading more phones:", err);
//                     busy2 = false; // Reset busy2 even on error
//                 });
//         }
//     }, 200);
// });

$(document).on("keyup", ".search_name", function () {
        var search_name = $(this).val();
        page = 1;
        // $("#yesviteUser").html("");
        clearTimeout(searchTimeout);
        // loadMoreData(page, search_name);
        if(search_name==''){
            offset=0;
            limit=50;
            $("#yesviteUser").html("");
            loadMoreData(search_name,type=null,offset,limit);

        
        }else{
            offset=null;
            limit=null;
            searchTimeout = setTimeout(function () {
                $("#yesviteUser").html("");

            loadMoreData(search_name,type=null,offset,limit);
        }, 1000);


        }
        // loadMoreData(search_name,type=null,offset,limit);

});

$(document).on("keyup", ".search_group", function () {
        search_group = $(this).val();
        page = 1;
        $("#yesviteGroups").html("");
        loadMoreGroups(page, search_group);
});

$(document).on("input", ".search_phone", function () {
    var search_phone = $(this).val();
        page = 1;
        // $("#yesvitePhones").html("");
        clearTimeout(searchTimeout);

        if(search_phone==''){
            offset1=null;
            limit=50;
            $("#yesvitePhones").html("");
            loadMorePhones(search_phone,type=null,offset1,limit);

        }else{
            offset1=null;
            limit=null;
            searchTimeout = setTimeout(function () {
                $("#yesvitePhones").html("");
                loadMorePhones(search_phone, type = null, offset1, limit);
            }, 1000);
        }
        // loadMorePhones(search_phone,type=null,offset1,limit);
});

    function loadMoreData(search_name,type,offset,limit,scroll=null,Group=null) {
        console.log({search_name,type,offset,limit,scroll});
        $.ajax({
            url: base_url + "contacts/load",
            type: "POST",
            data: {
                search_name: search_name,
                _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
                type:type,
                offset:offset,
                limit:limit
            },
            beforeSend: function () {
                // $('#home_loader').css('display','flex');
            },
            success: function (data) {
                if (data.status == "0" && scroll==1) {
                    $(".no-yesvite-data").css("display","none");
                    $("#home_loader").hide();
                    return;
                }
                if (data.status == "0") {
                    $(".no-yesvite-data").css("display","block");
                    $("#yesviteUser").html('');
                    $("#home_loader").hide();
                    return;
                }
                $(".no-yesvite-data").css("display","none");
                
                if(data.search=='1'){
                    $("#yesviteUser").html(data.view);
                }else{
                    $("#yesviteUser").append(data.view);
                }
                
                busy1 = false;
                $("#home_loader").hide();
            },
            error: function (jqXHR, ajaxOptions, thrownError) {
                console.error("AJAX Error:", thrownError);
                console.error("Response:", jqXHR.responseText);
                // alert("server not responding...");
                toastr.error("server not responding...");
            },
        })
    }
    function loadMoreDataList(search_name,type,offset,limit,scroll=null,Group=null) {
        console.log({search_name,type,offset,limit,scroll});
        $.ajax({
            url: base_url + "contacts/load",
            type: "POST",
            data: {
                search_name: search_name,
                _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
                type:type,
                offset:offset,
                isGroup:1,
                limit:limit
            },
            beforeSend: function () {
                $('#home_loader').css('display','flex');
            },
            success: function (data) {
                if (data.status == "0" && scroll==1) {
                    // $(".no-yesvite-data").css("display","none");
                    $("#home_loader").hide();
                    return;
                }
                if (data.status == "0") {
                    // $(".no-yesvite-data").css("display","block");
                    $("#groupUsers").html('');
                    $("#home_loader").hide();
                    return;
                }
                $(".no-yesvite-data").css("display","none");
                
                if(data.search=='1'){
                        $("#groupUsers").html(data.view);
                    
                }else{
                        $("#groupUsers").append(data.view);
                }
                
                busy1gl = false;
                $("#home_loader").hide();
            },
            error: function (jqXHR, ajaxOptions, thrownError) {
                console.error("AJAX Error:", thrownError);
                console.error("Response:", jqXHR.responseText);
                // alert("server not responding...");
                toastr.error("server not responding...");

            },
        })
    }

    function loadMoreGroups(page, search_group = "") {
        $.ajax({
            url: base_url + "contacts/loadgroups?page=" + page,
            type: "POST",
            data: {
                search_group: search_group,
                _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
            },
            beforeSend: function () {
                $('#home_loader').css('display','flex');
            },
            success: function (data) {
                console.log(data);
                // if (data.html == "") {
                    if (data.status == "0") {
                        $(".no-group-data").css("display","block");
                        $("#home_loader").hide();
    
                        return;
                    }
                    $(".no-group-data").css("display","none");
                    // $("#loader").hide();
                

                    // $(".no-group-data").css("display","block");
                    // $("#yesviteGroups").html(data);
                //     return;
                // }
                // $(".no-group-data").css("display","none");
                if(data.search=='1'){
                    $("#yesviteGroups").html(data.view);
                }else{
                    $("#yesviteGroups").append(data.view);
                }

                $("#home_loader").hide();
            },
            error: function (jqXHR, ajaxOptions, thrownError) {
                console.error("AJAX Error:", thrownError);
                console.error("Response:", jqXHR.responseText);
                // alert("server not responding...");
                toastr.error("server not responding...");

            },
        });
    }

    function loadMorePhones(search_phone,type,offset1,limit,scroll=null) {
        // $('#home_loader').css('display','flex');
        console.log({search_phone,type,offset1,limit,scroll});
        $.ajax({
            url: base_url + "contacts/loadphones",
            type: "POST",
            data: {
                search_phone: search_phone,
                _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
                type:type,
                offset:offset1,
                limit:limit
            },
            beforeSend: function () {
                // $('#home_loader').css('display','flex');
            },
            success: function (data) {
                // busy2 = false;

                console.log(data);
                if (data.status == "0" && scroll==1) {
                    $(".no-phone-data").css("display","none");
                    $("#home_loader").hide();
                    // busy2 = true; 
                    busy2 = false;
                    return;
                }
                if (data.status == "0") {
                    $(".no-phone-data").css("display","block");
                    $("#yesvitePhones").html('');
                    $("#home_loader").hide();
                    // busy2 = true; 
                    busy2 = false;

                    return;
                }
                $(".no-phone-data").css("display","none");

                // $("#yesvitePhones").append(data);


                if(data.search=='1'){
                    $("#yesvitePhones").html(data.view);
                }else{
                    $("#yesvitePhones").append(data.view);
                }


                busy2 = false;
                $("#home_loader").hide();


            },
            error: function (jqXHR, ajaxOptions, thrownError) {
                console.error("AJAX Error:", thrownError);
                console.error("Response:", jqXHR.responseText);
                $("#home_loader").hide();

            },
        });
    }

    $(".phone_number").intlTelInput({
        initialCountry: "US",
        separateDialCode: true,
        // utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
    });

    $("[name=phone_number]").on("blur", function () {
        var instance = $("[name=phone_number]");

        var phoneNumber = instance.intlTelInput(
            "getSelectedCountryData"
        ).dialCode;
        $("#country_code").val(phoneNumber);
    });


    $(document).on("input","#phone_number", function () {
        // alert();
        let input = $(this).val().replace(/\D/g, ''); // Remove any non-numeric characters
        let formattedNumber = '';
    
        if (input.length <= 3) {
            formattedNumber = input;
        } else if (input.length <= 6) {
            formattedNumber = `${input.slice(0, 3)}-${input.slice(3)}`;
        } else {
            formattedNumber = `${input.slice(0, 3)}-${input.slice(3, 6)}-${input.slice(6, 15)}`;
        }
    
        $(this).val(formattedNumber);
    });


    $.validator.addMethod("phoneUS", function (phone_number, element) {
        phone_number = phone_number.replace(/\D/g, ""); // Remove non-digits for validation
        return (
            this.optional(element) ||
            (phone_number.length === 10 && phone_number.match(/^\d{10}$/))
        );
    }, "Please enter a valid US phone number (e.g., 123-456-7890)");


    $("#add_contact").validate({
        rules: {
            Fname: "required",
            Lname: "required",
            email: {
                required: true,
                email: true,
                remote: {
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: base_url + "contacts/check_new_contactemail", // Your Laravel API endpoint
                    type: "POST",
                    data: {
                        email: function () {
                            return $(".addnew_email").val();
                        },
                    },
                },
            },

            phone_number: {
                // required: true,
                // digits: true,
                phoneUS: true,
                minlength: 10,
                maxlength: 15,
                // remote: {
                //     headers: {
                //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                //             "content"
                //         ),
                //     },
                //     url: base_url + "profile/check_new_contactnumber", // Your Laravel API endpoint
                //     type: "POST",
                //     data: {
                //         phone_number: function () {
                //             return $(".addnew_contact").val();
                //         },
                //         id: function () {
                //             return $("input[name='id']").val();
                //         },
                //     },
                // },
            },
        },
        messages: {
            Fname: "Please enter your First name",
            Lname: "Please enter your Last name",
            email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
                remote: "Email is already exsits",
            },
            phone_number: {
                // required: "Please enter a Phone Number",
                // digits: "Please enter a valid Phone Number",
                phoneUS: "Please enter a valid phone number in the format 123-456-7890",
                minlength: "Phone Number must be minimum 10 digit",
                maxlength: "Phone Number must be maxmimum 15 digit",
                // remote: "Phone Number is already exsits",
            },
        },
        submitHandler: function (form) {
            var formActionURL = $("#add_contact").attr("action");
            var formData = $("#add_contact").serialize();
            $.ajax({
                method: "POST",
                url: formActionURL,
                // dataType: "json",
                data: formData,

                success: function (output) {
                    console.log(output);

                    if (output.status == 1) {
                        // removeLoaderHandle("#save_contact", "Save Contact");
                        // $("#Fname").val(output.user.firstname);
                        // $("#Lname").val(output.user.lastname);

                        // $("#email").val(output.user.email);
                        
                        // $("#phone_number").val(output.user.phone_number);

                        toastr.success(output.message);

                        $("#add_contact")[0].reset();
                        $("#myModal1").modal("hide");
                        window.location.reload();
                    } else {
                        removeLoaderHandle("#save_contact", "Save Contact");
                        toastr.error(output.message);
                    }
                },
            });
        },
    });

    

    $("#save_contact").click(function () {
        loaderHandle("#save_contact", "Saving");
        $("#add_contact").submit();
    });

    $(document).on("click", ".edit-contact", function (e) {
        e.preventDefault(); // Prevent the default action
        $(".form-control").next().addClass("floatingfocus");

        var contactId = $(this).data("id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },

            method: "POST",
            url: base_url + "contacts/edit/" + contactId,
            // dataType: "json",
            // data: formData,

            success: function (output) {
                console.log(output.edit);

                if (output.status == 1) {
                    // alert();
                    $("#edit_Fname").val(output.edit.firstName);
                    $("#edit_Lname").val(output.edit.lastName);
                    $("#email").val(output.edit.email);
                    var phoneNumber = output.edit.phone; // "+1 4444-464-4646"
                    phoneNumber = phoneNumber.replace('+1 ', '');
                    $("#phone_number").val(phoneNumber);
                    $("#edit_id").val(output.edit.id);
                    $("#is_phone_contact").val(1);
                    $('#save_edit_contact').attr("data-is_phone_contact", "1");

                }
            },
        });
    });

    $(document).on("click", ".edit-yesvite-contact", function (e) {
        // alert();
        e.preventDefault(); // Prevent the default action

        $(".form-control").next().addClass("floatingfocus");

        var contactId = $(this).data("id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },

            method: "POST",
            url: base_url + "contacts/edit_yesvite/" + contactId,
            // dataType: "json",
            // data: formData,

            success: function (output) {
                // console.log(output.edit);

                if (output.status == 1) {
                    $("#edit_Fname").val(output.edit.firstname);
                    $("#edit_Lname").val(output.edit.lastname);
                    $("#email").val(output.edit.email);
                    $("#phone_number").val(output.edit.phone_number);
                    $("#edit_id").val(output.edit.id);
                    $("#is_phone_contact").val(0);
                    $('#save_edit_contact').attr("data-is_phone_contact", "0");
                }
            },
        });
    });

    $("#edit_contact_form").validate({
        rules: {
            edit_Fname: "required",
            edit_Lname: "required",
            email: {
                required: true,
                email: true,
                remote: {
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: base_url + "contacts/check_new_contactemail", // Your Laravel API endpoint
                    type: "POST",
                    data: {
                        email: function () {
                            return $(".addnew_email").val();
                        },
                    },
                },
            },
            phone_number: {
                // required: true,
                // digits: true,
                phoneUS: true,
                minlength: 10,
                maxlength: 15,
                // remote: {
                //     headers: {
                //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                //             "content"
                //         ),
                //     },
                //     url: base_url + "profile/check_new_contactnumber", // Your Laravel API endpoint
                //     type: "POST",
                //     data: {
                //         phone_number: function () {
                //             return $(".addnew_contact").val();
                //         },
                //         id: function () {
                //             return $("input[name='id']").val();
                //         },
                //     },
                // },
            },
        },
        messages: {
            edit_Fname: "Please enter your First name",
            edit_Lname: "Please enter your Last name",
            email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
                remote: "Email is already exsits",
            },
            phone_number: {
                required: "Please enter a Phone Number",
                // digits: "Please enter a valid Phone Number",
                phoneUS: "Please enter a valid phone number in the format 123-456-7890",
                minlength: "Phone Number must be minimum 10 digit",
                maxlength: "Phone Number must be maxmimum 15 digit",
                // remote: "Phone Number is already exsits",
            },
        },
        // submitHandler: function (form) {
        //     var formActionURL = $("#edit_contact_form").attr("action");
        //     var formData = $("#edit_contact_form").serialize();
        //     $.ajax({
        //         headers: {
        //             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
        //                 "content"
        //             ),
        //         },
        //         method: "POST",
        //         url: formActionURL,
        //         // dataType: "json",
        //         data: formData,

        //         success: function (output) {
        //             console.log(output.user);

        //             if (output.status == 1) {
        //                 removeLoaderHandle("#save_contact", "Save Contact");
        //                 $("#Fname").val(output.user.firstname);
        //                 $("#Lname").val(output.user.lastname);

        //                 $("#email").val(output.user.email);
        //                 $("#phone_number").val(output.user.phone_number);

        //                 toastr.success(output.message);

        //                 $("#edit_contact_form")[0].reset();
        //                 $("#myModal").modal("hide");
        //                 window.location.reload();
        //             } else {
        //                 removeLoaderHandle("#save_contact", "Save Contact");
        //                 toastr.error(output.message);
        //             }
        //         },
        //     });
        // },
    }); 

    $("#save_edit_contact").click(function () {
        // loaderHandle("#save_edit_contact", "Saving");
        // $("#save_edit_contact").submit();

        // var formActionURL = $("#edit_contact_form").attr("action");
        if ($("#edit_contact_form").valid()) {  
        var formData = $("#edit_contact_form").serialize();
        var formtype=$(this).data('is_phone_contact');
        var formActionURL="";
        if(formtype=="1"){
            formActionURL= base_url+'contacts/save_edit_phone'
        }else if(formtype=="0"){
            formActionURL= base_url+'contacts/save_edit'
        }

        console.log(formActionURL);
        
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
            method: "POST",
            url: formActionURL,
            // dataType: "json",
            data: formData,

            success: function (output) {
                console.log(output.user);

                if (output.status == 1) {
                    // removeLoadeerHandle("#save_contact", "Save Contact");
                    // toastr.success(output.message);

                    if(output.user!=""){
                        $("#Fname").val(output.user.firstname);
                        $("#Lname").val(output.user.lastname);
    
                        $("#email").val(output.user.email);
                        $("#phone_number").val(output.user.phone_number);
                    }
               
                    toastr.success(output.message);

                    $("#edit_contact_form")[0].reset();
                    $("#myModal").modal("hide");
                    window.location.reload();
                } else {
                    removeLoaderHandle("#save_contact", "Save Contact");
                    toastr.error(output.message);
                }
            },
        });

    }
    });

    $("#save_edit_phone_contact").click(function () {
        // loaderHandle("#save_edit_contact", "Saving");
        // $("#save_edit_contact").submit();

        var formActionURL = $("#edit_phone_contact_form").attr("action");
        var formData = $("#edit_phone_contact_form").serialize();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
            method: "POST",
            url: formActionURL,
            // dataType: "json",
            data: formData,

            success: function (output) {
                console.log(output.user);

                if (output.status == 1) {
                    removeLoaderHandle("#save_contact", "Save Contact");
                    $("#Fname").val(output.user.firstname);
                    $("#Lname").val(output.user.lastname);

                    $("#email").val(output.user.email);
                    $("#phone_number").val(output.user.phone_number);

                    toastr.success(output.message);

                    $("#edit_contact_form")[0].reset();
                    $("#myModal").modal("hide");
                    window.location.reload();
                } else {
                    removeLoaderHandle("#save_contact", "Save Contact");
                    toastr.error(output.message);
                }
            },
        });
    });

    $("#myModal").on("hidden.bs.modal", function (event) {
        $(".form-control").next().removeClass("floatingfocus");
        $("#edit_contact_form .label-error .error").text("");
        $('#save_edit_contact').removeAttr("data-is_phone_contact");

    });

    $("#myModal1").on("hidden.bs.modal", function (event) {
        $(".form-control").next().removeClass("floatingfocus");
        $("#add_contact .label-error .error").text("");
        $("#add_contact")[0].reset();
        $('#save_edit_contact').removeAttr("data-is_phone_contact");

    });
});
$('label[for="email"]').removeClass("floatingfocus");

$(document).on('click','.click-to-upload-btn', function (e) {
    $('#home_loader').css('display','flex');
    var fileInput = $('#csv_file')[0];

    if (fileInput.files.length === 0) {
        $('#home_loader').css('display', 'none');
        toastr.error('Please upload a CSV file first');
        return;
    }

    var fileName = fileInput.files[0].name;
    var fileExtension = fileName.split('.').pop().toLowerCase();

    if (fileExtension !== 'csv') {
        $('#home_loader').css('display', 'none');
        toastr.error('Only CSV files are allowed.');
        return;
    }
    $('<div id="pageOverlay"></div>').css({
        position: 'fixed',
        top: 0,
        left: 0,
        width: '100%',
        height: '100%',
        background: 'rgba(255, 255, 255, 0)', // Transparent background
        zIndex: 9999
    }).appendTo('body');
        $('#home_loader').css('display','none');
        $('#upload_csv_contact').submit();
    // }
});
$(document).ready(function() {
    const uploadWrapper = $('.uploadcsv-wrp');
    const fileInput = $('#csv_file');

    // Click to upload
    // uploadWrapper.click(function() {
    //     fileInput.click();
    // });

    
        // Click to upload - Prevent multiple triggers
        uploadWrapper.on('click', function (e) {
            e.stopPropagation(); // Prevent bubbling to avoid double trigger
            fileInput.trigger('click'); // Manually trigger file input
        });
    fileInput.change(handleFileSelect);

    // Drag and drop
    uploadWrapper.on('dragover', function(e) {
        e.preventDefault();
        uploadWrapper.addClass('drag-over');
    });

    uploadWrapper.on('dragleave', function(e) {
        uploadWrapper.removeClass('drag-over');
    });

    uploadWrapper.on('drop', function(e) {
        e.preventDefault();
        uploadWrapper.removeClass('drag-over');
        let files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            setFileInput(files[0]); // Assign dropped file
        }
        handleFileSelect(e.originalEvent); // Access original event for drop
    });
    function setFileInput(file) {
        if (file.type === 'text/csv' || file.name.endsWith('.csv')) {
            $(".uploadcsv-wrp h3").text(file.name); // Show file name
            $(".uploadcsv-wrp p").addClass('d-none'); // Show file name

            // Assign file to input field
            let dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput[0].files = dataTransfer.files;

            console.log('CSV file selected:', file);
        } else {
            toastr.error('Only CSV files are allowed.');
            fileInput.val('');
        }
    }
    function handleFileSelect(e) {
        let files;
        if (e.type === 'drop') {
            files = e.dataTransfer.files;
        } else {
            files = e.target.files;
        }

        if (files.length > 0) {
            const file = files[0];
            if (file.type === 'text/csv' || file.name.endsWith('.csv')) {
                $(".uploadcsv-wrp h3").text(file.name); // Show file name
                $(".uploadcsv-wrp p").addClass('d-none'); // Show file name

                console.log('CSV file selected:', file);
                // File is now selected, and will be submitted with the form.
            } else {
                // alert('Please upload a CSV file.');
                fileInput.val(''); // Clear the input
            }
        }
    }
});
$("#csv_file").on("change", function (e) {
    let file = e.target.files[0]; // Get the selected file
    if (file) {
        $(".uploadcsv-wrp h3").text(file.name); // Show file name
        // alert("File uploaded: " + file.name);
    }
});
$(document).on('click','.close_upload_csv',function(){
    $(".uploadcsv-wrp h3").text('Drag CSV Here'); // Show file name
    let fileInput = $("#csv_file");
    $(".uploadcsv-wrp p").removeClass('d-none'); // Show file name

    fileInput.val('');
    fileInput.replaceWith(fileInput.clone(true));
})
$('#uploadcsv').on('hidden.bs.modal', function () {
    $(".uploadcsv-wrp h3").text('Drag CSV Here'); // Show file name
    $(".uploadcsv-wrp p").removeClass('d-none'); // Show file name
    let fileInput = $("#csv_file");
    fileInput.val('');
    fileInput.replaceWith(fileInput.clone(true));
});
// $(document).ready(function() {
    $(document).on("click", ".openProfileModal", function () {
        let userId = $(this).attr("data-userid"); // Get user_id from clicked element
        $.ajax({
            url: base_url + "event_wall/myProfile",// Adjust this URL as per your route
            type: "POST",
            data: JSON.stringify({ user_id: userId }),
            contentType: "application/json",
            headers: {
                Authorization: "Bearer YOUR_ACCESS_TOKEN",
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.status === 1) {
                    let profileData = response.data;
                    let profilePrivacy = profileData.profile_privacy || [];
                    // Set username
                    $("#post_name").text(profileData.firstname + " " + profileData.lastname);
                    let showLocation = profilePrivacy.some(item => item.profile_privacy === "location" && item.status === "1");
                    let showPhotos = profilePrivacy.some(item => item.profile_privacy === "photo" && item.status === "1");
                    if (showLocation) {
                        let locationText = [profileData.address,profileData.city, profileData.state, profileData.zip_code]
                            .filter(Boolean)
                            .join(", ");
    
                        $("#location").text(locationText);
                    } else {
                        $("#location").text("");
                    }
                        $("#photos").text(profileData.total_photos);
                        $("#comments").text(profileData.comments);
                        $("#events").text(profileData.total_events);
                        $("#member_since").text(profileData.created_at);
                    // Handle profile image or initials
                    let profileImgElement = $("#modal-profile-img");
                    let initialsElement = $("#modal-initials");
    
                    if (profileData.profile && profileData.profile !== "") {
                        profileImgElement.attr("src", profileData.profile).show();
                        initialsElement.hide();
                    } else {
                        let firstInitial = profileData.firstname ? profileData.firstname[0].toUpperCase() : "";
                        let secondInitial = profileData.lastname ? profileData.lastname[0].toUpperCase() : "";
                        $("#modal-initials").removeClass().addClass("fontcolor"+profileData.firstname[0].toUpperCase());
    
                        initialsElement.text(firstInitial + secondInitial).show();
                        profileImgElement.hide();
                    }
                    let background_img = $("#modal-background-img");
                   if ( profileData.bg_profile != "") {
                        background_img.attr("src", profileData.bg_profile).show();
                        $("#show_img").addClass('d-none');
    
                    }else{
                        background_img.attr("src",'');
                        background_img.hide();
                        $("#show_img").removeClass('d-none');
                    }
    
                    let messageLink = $(".wall_profile-message-link");
                    let encrypted_id = profileData.encrypted_id;
                    if (encrypted_id) {
                        let messageRoute = `/messages/${encrypted_id}`;
                        messageLink.attr("href", messageRoute);
                    }
                    // Handle Host and Co-Host labels
                    let hostDisplay = $("#host_display").empty();
                    if ($(this).data("is-host") === 1) {
                        hostDisplay.append('<span class="host">Host</span>');
                    }
                    if ($(this).data("is-cohost") === 1) {
                        hostDisplay.append('<span class="host">Co Host</span>');
                    }
    
                    // Show the modal
                    $("#profileModal").modal("show");
                    // $("#wall_profile").modal("show");
    
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                // alert("Failed to fetch profile data.");
            }
        });
    });
    function generateProfileImage(firstname, lastname) {
        firstname = firstname ? String(firstname).trim() : "";
        lastname = lastname ? String(lastname).trim() : "";
        const firstInitial = firstname[0] ? firstname[0].toUpperCase() : "";
        const secondInitial = lastname[0] ? lastname[0].toUpperCase() : "";
        const initials = `${firstInitial}${secondInitial}`;
        const fontColor = `fontcolor${firstInitial}`;
        return `<h5 id="modal-initials" class="${fontColor} font_name">${initials || "NA"}</h5>`;
    }
//     let dropArea = $(".uploadcsv-wrp");
//     let fileInput = $("#csv_file");

//     $(document).on("dragover dragenter", function (e) {
//         e.preventDefault();
//         e.stopPropagation();
//     });

//     $(document).on("drop", function (e) {
//         e.preventDefault();
//         e.stopPropagation();
//     });

//     dropArea.on("dragover dragenter", function (e) {
//         e.preventDefault();
//         e.stopPropagation();
//         $(this).addClass("drag-over");
//     });

//     dropArea.on("dragleave", function (e) {
//         e.preventDefault();
//         e.stopPropagation();
//         $(this).removeClass("drag-over");
//     });

//     dropArea.on("drop", function (e) {
//         e.preventDefault();
//         e.stopPropagation();
//         $(this).removeClass("drag-over");

//         let files = e.originalEvent.dataTransfer.files;
//         if (files.length > 0) {
//             handleFileUpload(files[0]);
//         }
//     });

//     fileInput.on("change", function (e) {
//         let file = e.target.files[0];
//         handleFileUpload(file);
//     });

//     function handleFileUpload(file) {
//         if (file && file.type === "text/csv") {
//             $(".uploadcsv-wrp h3").text(file.name);
//             console.log("File uploaded:", file); // Important for debugging
//         } else {
//             alert("Please upload a valid CSV file.");
//         }
//     }
// });
// $(document).on("shown.bs.modal", "#uploadcsv", function () {
//     const dropZone1 = document.querySelector(".uploadcsv-wrp");
//     const fileInput = document.querySelector(".csv_file");
    
//     dropZone1.addEventListener("click", () => {
//         fileInput.click(); // Open file dialog on div click
//     });
    
//     dropZone1.addEventListener("dragover", (event) => {
//         event.preventDefault();
//         dropZone1.classList.add("dragging");
//     });
    
//     dropZone1.addEventListener("dragleave", () => {
//         dropZone1.classList.remove("dragging");
//     });
    
//     dropZone1.addEventListener("drop", (event) => {
//         event.preventDefault();
//         dropZone1.classList.remove("dragging");
    
//         const files = Array.from(event.dataTransfer.files);
    
//         if (files.length > 0) {
//             const allowedExtensions = ["csv"]; // Allow only CSV files
//             const dataTransfer = new DataTransfer();
    
//             files.forEach((file) => {
//                 const fileExtension = file.name.split('.').pop().toLowerCase();
//                 if (allowedExtensions.includes(fileExtension)) {
//                     dataTransfer.items.add(file);
//                 } else {
//                     toastr.error("Only CSV files are allowed!");
//                 }
//             });
    
//             if (dataTransfer.files.length > 0) {
//                 fileInput.files = dataTransfer.files;
//                 fileInput.dispatchEvent(new Event("change"));
//                 toastr.success("File uploaded successfully!");
//                 $(".uploadcsv-wrp h3").text(files[0].name); 
//             }
//         }
//     });
// });