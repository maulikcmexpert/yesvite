var dbJson = $("#static_information").val() || null;
var temp_id = null;
var image = $("#design_image").val() || null;
var base_url = $("#base_url").text();
var canvas;
var shapeImageUrl;
let currentImage = null;
let isImageDragging = false; // Track if the image is being dragged
let isimageoncanvas = false;
let oldImage = null;
let imageId = null;
var current_shape;
let undoStack = [];
let redoStack = [];
let event_id = null;

// Original static size
const originalWidth = 345;
const originalHeight = 490;
let element = document.querySelector(".image-edit-inner-img");
var { width, height } = { width: 590, height: 880 };
if (element) {
    ({ width, height } = element.getBoundingClientRect()); // Update width & height if element exists
    console.log("Width:", width, "Height:", height);
} else {
    console.log("Element not found! Using default values.");
}

document.addEventListener("DOMContentLoaded", function () {
    console.log("DOMContentLoaded fired");
    preloadAllFonts(); // Load all fonts on page load
});

// Function to preload all fonts
async function preloadAllFonts() {
    let fontsToLoad = []; // Array to store font observers
    document.querySelectorAll(".font-input").forEach(function (input) {
        const font = input.getAttribute("data-font");
        let fontObserver = new FontFaceObserver(font);
        fontsToLoad.push(fontObserver.load());
    });

    // Load all fonts asynchronously
    Promise.all(fontsToLoad)
        .then(() => {
            console.log("All fonts loaded successfully.");
        })
        .catch((err) => {
            console.error("Some fonts failed to load:", err);
        });
}

$(document).ready(function () {
    console.log("document.ready fired");
    $("#custom_template").change(function () {
        var file = this.files[0];
        dbJson = null;
        if (!file) return;

        var validExtensions = ["image/jpeg", "image/png"];
        if (!validExtensions.includes(file.type)) {
            toastr.error("Only JPG and PNG images are allowed.");
            return;
        }

        var formData = new FormData();
        formData.append("image", file, "design.png");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: base_url + "event/store_custom_design",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == 401 && response.info == "logout") {
                    window.location.href = "/"; // Redirect to home page
                    return;
                }
                if (response.image) {
                    eventData.temp_id = null;
                    image = base_url + "storage/canvas/" + response.image;
                    eventData.cutome_image = response.image;
                    eventData.image = response.image;
                    temp_id = null;
                    dbJson = null;
                    loadAgain();
                } else {
                    alert("Upload failed.");
                }
            },
            error: function () {
                alert("Error uploading image.");
            },
        });
    });
});

$(document).on("click", ".design-cards", function () {
    var url = $(this).data("url");
    var template = $(this).data("template");
    var imageUrl = $(this).data("image");
    shapeImageUrl = $(this).data("shape_image");
    var json = $(this).data("json");
    //console.log(json);
    var id = $(this).data("id");
    imageId = id;
    $(".edit_design_tem").attr("data-image", imageUrl);
    if (
        eventData.textData != null &&
        eventData.temp_id != null &&
        eventData.temp_id == id
    ) {
        dbJson = eventData.textData;
    } else {
        console.log(json);
        dbJson = json;
        temp_id = id;
    }

    // Set the image URL in the modal's image tag
    $("#modalImage").attr("src", imageUrl);
    image = imageUrl;

    // Remove the old canvas if it exists
    $("#imageEditor2").remove();

    // Create a new canvas element
    var newCanvas = $("<canvas>", {
        id: "imageEditor2",
        width: width,
        height: height,
    });

    // Append the new canvas to the modal-design-card
    $(".modal-design-card").html(newCanvas);

    // Show the modal
    $("#exampleModal").modal("show");

    canvas = new fabric.Canvas("imageEditor2", {
        width: width,
        height: height,
        position: "relative",
    });

    const defaultSettings = {
        fontSize: 20,
        letterSpacing: 0,
        lineHeight: 1.2,
    };

    fabric.Image.fromURL(image, function (img) {
        var canvasWidth = canvas.getWidth();
        var canvasHeight = canvas.getHeight();

        // Calculate scale to maintain aspect ratio
        var scaleFactor = Math.min(
            canvasWidth / img.width,
            canvasHeight / img.height
        );
        img.set({
            left: 0,
            top: 0,
            scaleX: scaleFactor,
            scaleY: scaleFactor,
            selectable: false,
            hasControls: false,
        });
        canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
    });

    const staticInfo = dbJson;

    staticInfo.textElements.forEach((element) => {
        //console.log(element);
        const textMeasurement = new fabric.Text(element.text, {
            fontSize: element.fontSize,
            fontFamily: element.fontFamily,
            fontWeight: element.fontWeight,
            fontStyle: element.fontStyle,
            underline: element.underline,
            linethrough: element.linethrough,
        });
        const textWidth1 = textMeasurement.width;

        let textElement = new fabric.Textbox(element.text, {
            left: element.left,
            top: element.top,
            width: element.width || textWidth1 + 10,
            fontSize: element.fontSize,
            fill: element.fill,
            fontFamily: element.fontFamily,
            fontWeight: element.fontWeight,
            fontStyle: element.fontStyle,
            underline: element.underline,
            lineHeight: element.lineHeight || 2,
            linethrough: element.linethrough,
            backgroundColor: element.backgroundColor,
            textAlign: element.textAlign,
            editable: false,
            selectable: false,
            hasControls: false,
            borderColor: "#2DA9FC",
            cornerColor: "#fff",
            cornerSize: 10,
            transparentCorners: false,
            isStatic: true,
            angle: element?.rotation ? element?.rotation : 0,
            letterSpacing: 0,
        });

        switch (element.text.toLowerCase()) {
            case "event_name":
                if (eventData.event_name) {
                    textElement.set({
                        text: eventData.event_name,
                        width: textWidth1,
                    });
                } else {
                    return; // Skip adding the element if event_name is empty
                }
                break;
            case "host_name":
                if (eventData.hosted_by) {
                    textElement.set({
                        text: eventData.hosted_by,
                        width: textWidth1,
                    });
                } else {
                    return; // Skip adding the element if host_name is empty
                }
                break;
            case "location_description":
                if (eventData.event_location) {
                    textElement.set({
                        text: eventData.event_location,
                        width: textWidth1,
                    });
                } else {
                    return; // Skip adding the element if event_location_name is empty
                }
                break;
            case "start_time":
                if (eventData.start_time) {
                    textElement.set({
                        text: eventData.start_time,
                        width: textWidth1,
                    });
                } else {
                    return; // Skip adding the element if start_time is empty
                }
                break;
            case "end_time":
                if (eventData.rsvp_end_time) {
                    textElement.set({
                        text: eventData.rsvp_end_time,
                        width: textWidth1,
                    });
                } else {
                    return; // Skip adding the element if rsvp_end_time is empty
                }
                break;
            case "start_date":
                if (eventData.event_date) {
                    var start_date = "";
                    if (eventData.event_date.includes(" To ")) {
                        let [start, end] = eventData.event_date.split(" To ");
                        start_date = start;
                    } else {
                        start_date = eventData.event_date;
                    }

                    textElement.set({
                        text: start_date,
                    });
                } else {
                    return; // Skip adding the element if start_date is empty
                }
                break;
            case "end_date":
                if (eventData.event_date) {
                    var end_date = "";
                    if (eventData.event_date.includes(" To ")) {
                        let [start, end] = eventData.event_date.split(" To ");
                        end_date = end;
                    } else {
                        end_date = eventData.event_date;
                    }

                    textElement.set({
                        text: end_date,
                    });
                } else {
                    return; // Skip adding the element if end_date is empty
                }
                break;
        }
        // const textWidth = textElement.calcTextWidth();
        // textElement.set({
        //     width: textWidth
        // });
        canvas.add(textElement);
    });
    var shape = "";
    if (dbJson) {
    }

    // Load filed image (filedImagePath) as another image layer
    if (shapeImageUrl) {
        let element = staticInfo?.shapeImageData;
        if (
            element != undefined &&
            element?.shape &&
            element?.centerX &&
            element?.centerY &&
            element?.height &&
            element?.width
        ) {
            const imageInput = document.getElementById("image1");
            const scaledWidth = element.width; // Use element's width
            const scaledHeight = element.height;

            imageInput.style.width = element.width + "px";
            imageInput.style.height = element.height + "px";

            let currentImage = null; // Variable to hold the current image
            let isScaling = false; // Flag to check if the image is scaling
            let currentShapeIndex = 0; // Index to track the current shape

            // Define default shape variable (can be changed as needed)
            const defaultShape = element.shape; // Set the desired default shape here

            // Create a mapping of shape names to their indices
            const shapeIndexMap = {
                rectangle: 0,
                circle: 1,
                triangle: 2,
                star: 3,
            };

            function createShapes(img) {
                const imgWidth = img.width;
                const imgHeight = img.height;
                const starScale = Math.min(imgWidth, imgHeight) / 2; // Adjust the star size based on the image

                // Proper 5-point star shape
                const starPoints = [
                    { x: 0, y: -starScale }, // Top point
                    { x: starScale * 0.23, y: -starScale * 0.31 }, // Top-right
                    { x: starScale, y: -starScale * 0.31 }, // Right
                    { x: starScale * 0.38, y: starScale * 0.12 }, // Bottom-right
                    { x: starScale * 0.58, y: starScale }, // Bottom
                    { x: 0, y: starScale * 0.5 }, // Center-bottom
                    { x: -starScale * 0.58, y: starScale }, // Bottom-left
                    { x: -starScale * 0.38, y: starScale * 0.12 }, // Top-left
                    { x: -starScale, y: -starScale * 0.31 }, // Left
                    { x: -starScale * 0.23, y: -starScale * 0.31 }, // Top-left
                ];

                return [
                    new fabric.Rect({
                        width: imgWidth,
                        height: imgHeight,
                        originX: "center",
                        originY: "center",
                        angle: 0,
                    }),
                    new fabric.Circle({
                        radius: Math.min(imgWidth, imgHeight) / 2,
                        originX: "center",
                        originY: "center",
                        angle: 0,
                    }),
                    new fabric.Triangle({
                        width: imgWidth,
                        height: imgHeight,
                        originX: "center",
                        originY: "center",
                        angle: 0,
                    }),
                    new fabric.Polygon(starPoints, {
                        originX: "center",
                        originY: "center",
                        angle: 0,
                    }),
                ];
            }

            // Load the initial image
            fabric.Image.fromURL(shapeImageUrl, function (img) {
                img.set({
                    selectable: false,
                    hasControls: false,
                    hasBorders: false,
                    borderColor: "#2DA9FC",
                    cornerColor: "#fff",
                    transparentCorners: false,
                    lockUniScaling: true,
                    scaleX: scaledWidth / img.width, // Scale based on element's width
                    scaleY: scaledHeight / img.height, // Scale based on element's height
                    cornerSize: 10,
                    cornerStyle: "circle",
                    left: element.centerX - scaledWidth / 2, // Center the image horizontally
                    top: element.centerY - scaledHeight / 2,
                });

                let shapes = createShapes(img);

                currentShapeIndex = shapeIndexMap[defaultShape] || 0; // Default to rectangle if not found

                img.set({ clipPath: shapes[currentShapeIndex] });
                img.crossOrigin = "anonymous";

                img.on("mouseup", function (event) {
                    console.log(event);
                    if (
                        event?.transform?.action === "drag" &&
                        event.transform.actionPerformed === undefined
                    ) {
                        currentShapeIndex =
                            (currentShapeIndex + 1) % shapes.length;
                        img.set({ clipPath: shapes[currentShapeIndex] });
                        canvas.renderAll();
                    }
                });

                const fixClipPath = () => {
                    img.set({ clipPath: shapes[currentShapeIndex] });
                    canvas.renderAll();
                };

                img.on("scaling", function (event) {
                    const target = event.target;
                    if (target && target.isControl) {
                        fixClipPath();
                    }
                });

                canvas.add(img);
                currentImage = img; // Store the image reference
                $("#shape_img").attr("src", shapeImageUrl);
                $("#first_shape_img").attr("src", shapeImageUrl);

                // Custom control for the upload button (centered)
                fabric.Object.prototype.controls.uploadControl =
                    new fabric.Control({
                        x: 0,
                        y: 0,
                        offsetX: 0,
                        offsetY: 0,
                        cursorStyle: "pointer",
                        mouseUpHandler: function () {
                            imageInput.click();
                        },
                        render: function (
                            ctx,
                            left,
                            top,
                            styleOverride,
                            fabricObject
                        ) {
                            const imgIcon = document.createElement("img");

                            const svgString = `
                        <svg width="31" height="31" viewBox="0 0 31 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0.5" y="0.625" width="30" height="30" rx="15" fill="white"/>
                        <path d="M22 17.2502V21.5834C22 21.727 21.9429 21.8648 21.8414 21.9664C21.7398 22.0679 21.602 22.125 21.4583 22.125H9.54167C9.39801 22.125 9.26023 22.0679 9.15865 21.9664C9.05707 21.8648 9 21.727 9 21.5834V17.2502C9 17.1065 9.05707 16.9687 9.15865 16.8672C9.26023 16.7656 9.39801 16.7085 9.54167 16.7085C9.68533 16.7085 9.8231 16.7656 9.92468 16.8672C10.0263 16.9687 10.0833 17.1065 10.0833 17.2502V21.0417H20.9167V17.2502C20.9167 17.1065 20.9737 16.9687 21.0753 16.8672C21.1769 16.7656 21.3147 16.7085 21.4583 16.7085C21.602 16.7085 21.7398 16.7656 21.8414 16.8672C21.9429 16.9687 22 17.1065 22 17.2502ZM12.7917 12.917H14.9583V17.2502C14.9583 17.3938 15.0154 17.5316 15.117 17.6332C15.2186 17.7347 15.3563 17.7918 15.5 17.7918C15.6437 17.7918 15.7814 17.7347 15.883 17.6332C15.9846 17.5316 16.0417 17.3938 16.0417 17.2502V12.917H18.2083C18.3155 12.9171 18.4203 12.8853 18.5095 12.8258C18.5986 12.7663 18.6681 12.6817 18.7092 12.5827C18.7502 12.4836 18.7609 12.3747 18.74 12.2695C18.7191 12.1644 18.6674 12.0679 18.5916 11.9921L15.8832 9.28386C15.8329 9.2335 15.7732 9.19355 15.7074 9.16629C15.6417 9.13903 15.5712 9.125 15.5 9.125C15.4288 9.125 15.3583 9.13903 15.2926 9.16629C15.2268 9.19355 15.1671 9.2335 15.1168 9.28386L12.4084 11.9921C12.3326 12.0679 12.2809 12.1644 12.26 12.2695C12.2391 12.3747 12.2498 12.4836 12.2908 12.5827C12.3319 12.6817 12.4014 12.7663 12.4905 12.8258C12.5797 12.8853 12.6845 12.9171 12.7917 12.917Z" fill="black"/>
                        </svg>`;
                            const encodedSvg = encodeURIComponent(svgString);
                            const imgSrc = `data:image/svg+xml;charset=utf-8,${encodedSvg}`;
                            imgIcon.src = imgSrc;
                            imgIcon.crossOrigin = "anonymous";
                            imgIcon.width = 24;
                            imgIcon.height = 24;

                            ctx.drawImage(imgIcon, left - 12, top - 12, 24, 24);
                        },
                    });

                // Event listener for image selection (file input)
                imageInput.addEventListener("change", function (event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function () {
                            $("#shape_img").attr("src", reader.result);

                            fabric.Image.fromURL(
                                reader.result,
                                function (newImg) {
                                    // Remove the old image if it exists
                                    const newWidth = img.width;
                                    const newHeight = img.height;
                                    if (currentImage) {
                                        canvas.remove(currentImage);
                                    }

                                    newImg.set({
                                        selectable: false,
                                        hasControls: false,
                                        hasBorders: false,
                                        borderColor: "#2DA9FC",
                                        cornerColor: "#fff",
                                        transparentCorners: false,
                                        lockUniScaling: true,
                                        scaleX: scaledWidth / newWidth, // Scale based on element's width
                                        scaleY: scaledHeight / newHeight, // Scale based on element's height
                                        cornerSize: 10,
                                        cornerStyle: "circle",
                                        left: element.centerX - scaledWidth / 2, // Center the image horizontally
                                        top: element.centerY - scaledHeight / 2,
                                    });

                                    shapes = createShapes(newImg);
                                    canvas.add(newImg);
                                    currentImage = newImg;
                                    // $("#shape_img").attr("src",shapeImageUrl);
                                    shapeImageUrl = $("#shape_img").attr("src");
                                    // Reset shape index for the new image based on the default shape
                                    currentShapeIndex =
                                        shapeIndexMap[defaultShape] || 0; // Default to rectangle if not found
                                    newImg.set({
                                        clipPath: shapes[currentShapeIndex],
                                    });
                                    newImg.crossOrigin = "anonymous";

                                    newImg.on("mouseup", function (event) {
                                        console.log(event);
                                        if (
                                            event?.transform?.action ===
                                                "drag" &&
                                            event.transform.actionPerformed ===
                                                undefined
                                        ) {
                                            currentShapeIndex =
                                                (currentShapeIndex + 1) %
                                                shapes.length;
                                            newImg.set({
                                                clipPath:
                                                    shapes[currentShapeIndex],
                                            });
                                            canvas.renderAll();
                                        }
                                    });

                                    const fixClipPath = () => {
                                        newImg.set({
                                            clipPath: shapes[currentShapeIndex],
                                        });
                                        canvas.renderAll();
                                    };

                                    newImg.on("scaling", function () {
                                        // isScaling = true; // Set scaling flag
                                        fixClipPath();
                                    });
                                }
                            );
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });
        }
    }
});

$(document).on("click", ".modal-design-card", function (e) {
    e.stopPropagation();
});

$(document).on("click", ".close-btn", function () {
    toggleSidebar();
    var id = $(this).data("id");
    $("#sidebar").removeClass(id);
});

$(document).on("click", ".edit_design_tem", function (e) {
    // $("#close_createEvent").css("display", "none");
    e.preventDefault();
    $("#loader").css("display", "flex");
    var eventID = $("#eventID").val();
    var isDraft = $("#isDraft").val();

    var url = $(this).data("url");
    var template = $(this).data("template");
    image = $(this).data("image");
    shapeImageUrl = $(this).data("shape_image");
    var json = $(this).data("json");
    console.log(json);
    var id = $(this).data("id");
    imageId = id;
    $(".design-sidebar-action").attr("data-id", id);
    // if (
    //     eventData.textData != null &&
    //     eventData.temp_id != null &&
    //     eventData.temp_id == id
    // ) {
    //     dbJson = json;
    //     eventData.slider_images = [];
    //     eventData.desgin_selected = "";
    //     console.log({ dbJson });
    // } else {
    console.log(json);
    dbJson = json;
    temp_id = id;
    eventData.slider_images = [];
    eventData.desgin_selected = "";
    // }
    // //console.log(dbJson);
    // //console.log(image);
    var current_event_id = $(this).data("event_id");
    event_id = current_event_id;
    $(".step_1").hide();
    $(".step_2").hide();
    $(".step_3").hide();
    $(".pick-card").removeClass("active");
    $(".pick-card").addClass("menu-success");
    $(".edit-design").removeClass("menu-success");
    $(".edit-design").addClass("active");
    $(".event_create_percent").text("25%");
    $(".current_step").text("1 of 4");
    $("#sidebar_select_design_category").css("display", "none");

    active_responsive_dropdown(
        "drop-down-event-design",
        "drop-down-edit-design"
    );
    $(".step_4").hide();
    $("#exampleModal").modal("hide");
    $(".edit_design_template").remove();

    $.ajax({
        url: base_url + "event/get_design_edit_page",
        method: "POST",
        dataType: "html",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            eventID,
            isDraft,
            id: id,
        },
        success: async function (response) {
            console.log(response);

            if (isJSON(response)) {
                let jsonResponse = JSON.parse(response);

                if (
                    jsonResponse.status == 401 &&
                    jsonResponse.info == "logout"
                ) {
                    window.location.href = "/"; // Redirect to home page
                    return;
                }
            }
            console.log(dbJson);
            $("#edit-design-temp").html(response).show();
            await bindData(current_event_id);
            $("#loader").css("display", "none");
        },
        error: function (xhr, status, error) {
            $("#loader").css("display", "none");
        },
    });
});
fontloadedEnsure = false;
async function bindData(current_event_id) {
    let iw = document.getElementById("imageWrapper");
    if (!fontloadedEnsure) {
        fontloadedEnsure = true;
        await ensureFontsLoaded();
    }

    let element = document.querySelector(".image-edit-inner-img");
    if (element) {
        ({ width, height } = element.getBoundingClientRect()); // Update width & height if element exists
        console.log("Width:", width, "Height:", height);
    } else {
        console.log("Element not found! Using default values.");
    }

    function loadTextDataFromDatabase() {
        if (image) {
            // console.log(image);
            fabric.Image.fromURL(image, function (img) {
                img.crossOrigin = "anonymous";
                // var canvasWidth = canvas.getWidth();
                // var canvasHeight = canvas.getHeight();
                var canvasWidth = width;
                var canvasHeight = height;

                // Use Math.max to ensure the image covers the entire canvas
                var scaleFactor = Math.max(
                    canvasWidth / img.width,
                    canvasHeight / img.height
                );

                img.set({
                    // left: (canvasWidth - img.width * scaleFactor) / 2, // Centering horizontally
                    // top: (canvasHeight - img.height * scaleFactor) / 2, // Centering vertically
                    // scaleX: scaleFactor,
                    // scaleY: scaleFactor,
                    selectable: false,
                    hasControls: false,
                });

                // Disable image smoothing for high-quality rendering
                canvas.getContext("2d").imageSmoothingEnabled = false;

                // Set high-quality background image
                canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                    crossOrigin: "anonymous",
                    backgroundImageStretch: true,
                });
            });

            if (dbJson) {
                const scaleX = width / originalWidth;
                const scaleY = height / originalHeight;
                const staticInfo = {};

                if (current_event_id != "" && eventData.desgin_selected == "") {
                    if (dbJson.textElements != undefined) {
                        staticInfo.textElements = dbJson.textElements;
                    } else {
                        staticInfo.textElements = dbJson;
                    }
                } else {
                    staticInfo.textElements = dbJson.textElements;
                }
                if (staticInfo.textElements == undefined) {
                    staticInfo.textElements = jQuery.parseJSON(dbJson).textData;
                }
                if (staticInfo.textElements.textElement != undefined) {
                    staticInfo.textElements =
                        staticInfo.textElements.textElement;
                }
                if (staticInfo.textElements[0].text == undefined) {
                    staticInfo.textElements = jQuery.parseJSON(dbJson).textData;
                }
                if (staticInfo.textElements != undefined) {
                    // console.log(staticInfo);
                    staticInfo.textElements.forEach((element) => {
                        // applyFont(element.fontFamily);
                        const textMeasurement = new fabric.Text(element.text, {
                            fontSize: element.fontSize,
                            fontFamily: element.fontFamily,
                            fontWeight: element.fontWeight || "normal",
                            fontStyle: element.fontStyle || "normal",
                            underline: element.underline,
                            linethrough: ["true", "True", true].includes(
                                element.linethrough
                            ),
                        });

                        let textWidth = textMeasurement.width;

                        // **Scale Positions & Sizes**
                        let left = element.left
                            ? parseFloat(element.left) * scaleX
                            : (element.centerX - textWidth / 2) * scaleX;
                        let top = element.top
                            ? parseFloat(element.top) * scaleY
                            : (element.centerY - 10) * scaleY;

                        console.log(element.width);
                        let fontSize = parseFloat(element.fontSize) * scaleY; // Scale font size based on height
                        fontSize = Number(fontSize).toFixed(0);
                        let width = (textWidth + 10) * scaleX; // Scale text box width

                        let textElement = new fabric.Textbox(element.text, {
                            // Use Textbox for editable text
                            left: parseFloat(left),
                            top: parseFloat(top),
                            width: parseInt(element.width) * scaleX || width, // Default width if not provided
                            fontSize: fontSize,
                            fill: element.fill,
                            fontFamily: element.fontFamily || "Times New Roman",
                            fontWeight: element.fontWeight || "normal",
                            fontStyle: element.fontStyle || "normal",
                            underline: element.underline,
                            lineHeight: element.lineHeight || 2,
                            charSpacing:
                                element.charSpacing ||
                                element.letterSpacing ||
                                0,
                            linethrough:
                                element.linethrough == true ||
                                element.linethrough == "true" ||
                                element.linethrough == "True"
                                    ? true
                                    : false,
                            backgroundColor: element.backgroundColor,
                            textAlign: element.textAlign,
                            hasControls: true,
                            borderColor: "#2DA9FC",
                            cornerColor: "#fff",
                            cornerSize: 10,
                            cornerStyle: "circle",
                            transparentCorners: false,
                            lockScalingFlip: true,
                            hasBorders: true,
                            centeredRotation: true,
                            angle: element?.rotation ? element?.rotation : 0,
                        });

                        textElement.setControlsVisibility({
                            mt: false, // Hide middle top control
                            mb: false, // Hide middle bottom control
                            bl: true, // Hide bottom left control
                            br: true, // Hide bottom right control
                            tl: true, // Hide top left control
                            tr: true, // Hide top right control
                            ml: true, // Show middle left control
                            mr: true, // Show middle right control
                        });

                        canvas.add(textElement);
                    });
                }

                let currentImage = null;
                let isImageDragging = false; // Track if the image is being dragged
                let isimageoncanvas = false;
                let oldImage = null;

                if (shapeImageUrl) {
                    let element = staticInfo?.shapeImageData;
                    if (
                        element != undefined &&
                        element.shape &&
                        element.centerX &&
                        element.centerY &&
                        element.height &&
                        element.width
                    ) {
                        const imageInput = document.getElementById("image");
                        const scaledWidth = element.width; // Use element's width
                        const scaledHeight = element.height;

                        imageInput.style.width = element.width + "px";
                        imageInput.style.height = element.height + "px";

                        let currentImage = null; // Variable to hold the current image
                        let isScaling = false; // Flag to check if the image is scaling
                        let currentShapeIndex = 0; // Index to track the current shape

                        // Define default shape variable (can be changed as needed)
                        const defaultShape = element.shape; // Set the desired default shape here

                        // Create a mapping of shape names to their indices
                        const shapeIndexMap = {
                            rectangle: 0,
                            circle: 1,
                            triangle: 2,
                            star: 3,
                        };

                        function createShapes(img) {
                            const imgWidth = img.width;
                            const imgHeight = img.height;
                            const starScale = Math.min(imgWidth, imgHeight) / 2; // Adjust the star size based on the image

                            // Proper 5-point star shape
                            const starPoints = [
                                { x: 0, y: -starScale }, // Top point
                                { x: starScale * 0.23, y: -starScale * 0.31 }, // Top-right
                                { x: starScale, y: -starScale * 0.31 }, // Right
                                { x: starScale * 0.38, y: starScale * 0.12 }, // Bottom-right
                                { x: starScale * 0.58, y: starScale }, // Bottom
                                { x: 0, y: starScale * 0.5 }, // Center-bottom
                                { x: -starScale * 0.58, y: starScale }, // Bottom-left
                                { x: -starScale * 0.38, y: starScale * 0.12 }, // Top-left
                                { x: -starScale, y: -starScale * 0.31 }, // Left
                                { x: -starScale * 0.23, y: -starScale * 0.31 }, // Top-left
                            ];

                            return [
                                new fabric.Rect({
                                    width: imgWidth,
                                    height: imgHeight,
                                    originX: "center",
                                    originY: "center",
                                    angle: 0,
                                }),
                                new fabric.Circle({
                                    radius: Math.min(imgWidth, imgHeight) / 2,
                                    originX: "center",
                                    originY: "center",
                                    angle: 0,
                                }),
                                new fabric.Triangle({
                                    width: imgWidth,
                                    height: imgHeight,
                                    originX: "center",
                                    originY: "center",
                                    angle: 0,
                                }),
                                new fabric.Polygon(starPoints, {
                                    originX: "center",
                                    originY: "center",
                                    angle: 0,
                                }),
                            ];
                        }

                        // Load the initial image
                        fabric.Image.fromURL(shapeImageUrl, function (img) {
                            img.set({
                                selectable: true,
                                hasControls: true,
                                hasBorders: true,
                                borderColor: "#2DA9FC",
                                cornerColor: "#fff",
                                transparentCorners: false,
                                lockUniScaling: true,
                                scaleX: scaledWidth / img.width, // Scale based on element's width
                                scaleY: scaledHeight / img.height, // Scale based on element's height
                                cornerSize: 10,
                                cornerStyle: "circle",
                                left: element.centerX - scaledWidth / 2, // Center the image horizontally
                                top: element.centerY - scaledHeight / 2,
                            });

                            let shapes = createShapes(img);

                            currentShapeIndex =
                                shapeIndexMap[defaultShape] || 0; // Default to rectangle if not found

                            img.set({ clipPath: shapes[currentShapeIndex] });
                            img.crossOrigin = "anonymous";

                            img.on("mouseup", function (event) {
                                console.log(event);
                                if (
                                    event?.transform?.action === "drag" &&
                                    event.transform.actionPerformed ===
                                        undefined
                                ) {
                                    currentShapeIndex =
                                        (currentShapeIndex + 1) % shapes.length;
                                    img.set({
                                        clipPath: shapes[currentShapeIndex],
                                    });
                                    canvas.renderAll();
                                }
                            });

                            const fixClipPath = () => {
                                img.set({
                                    clipPath: shapes[currentShapeIndex],
                                });
                                canvas.renderAll();
                            };

                            img.on("scaling", function (event) {
                                const target = event.target;
                                if (target && target.isControl) {
                                    fixClipPath();
                                }
                            });

                            canvas.add(img);
                            currentImage = img; // Store the image reference
                            $("#shape_img").attr("src", shapeImageUrl);
                            $("#first_shape_img").attr("src", shapeImageUrl);

                            // Custom control for the upload button (centered)
                            fabric.Object.prototype.controls.uploadControl =
                                new fabric.Control({
                                    x: 0,
                                    y: 0,
                                    offsetX: 0,
                                    offsetY: 0,
                                    cursorStyle: "pointer",
                                    mouseUpHandler: function () {
                                        imageInput.click();
                                    },
                                    render: function (
                                        ctx,
                                        left,
                                        top,
                                        styleOverride,
                                        fabricObject
                                    ) {
                                        const imgIcon =
                                            document.createElement("img");

                                        const svgString = `
                                    <svg width="31" height="31" viewBox="0 0 31 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="0.5" y="0.625" width="30" height="30" rx="15" fill="white"/>
                                    <path d="M22 17.2502V21.5834C22 21.727 21.9429 21.8648 21.8414 21.9664C21.7398 22.0679 21.602 22.125 21.4583 22.125H9.54167C9.39801 22.125 9.26023 22.0679 9.15865 21.9664C9.05707 21.8648 9 21.727 9 21.5834V17.2502C9 17.1065 9.05707 16.9687 9.15865 16.8672C9.26023 16.7656 9.39801 16.7085 9.54167 16.7085C9.68533 16.7085 9.8231 16.7656 9.92468 16.8672C10.0263 16.9687 10.0833 17.1065 10.0833 17.2502V21.0417H20.9167V17.2502C20.9167 17.1065 20.9737 16.9687 21.0753 16.8672C21.1769 16.7656 21.3147 16.7085 21.4583 16.7085C21.602 16.7085 21.7398 16.7656 21.8414 16.8672C21.9429 16.9687 22 17.1065 22 17.2502ZM12.7917 12.917H14.9583V17.2502C14.9583 17.3938 15.0154 17.5316 15.117 17.6332C15.2186 17.7347 15.3563 17.7918 15.5 17.7918C15.6437 17.7918 15.7814 17.7347 15.883 17.6332C15.9846 17.5316 16.0417 17.3938 16.0417 17.2502V12.917H18.2083C18.3155 12.9171 18.4203 12.8853 18.5095 12.8258C18.5986 12.7663 18.6681 12.6817 18.7092 12.5827C18.7502 12.4836 18.7609 12.3747 18.74 12.2695C18.7191 12.1644 18.6674 12.0679 18.5916 11.9921L15.8832 9.28386C15.8329 9.2335 15.7732 9.19355 15.7074 9.16629C15.6417 9.13903 15.5712 9.125 15.5 9.125C15.4288 9.125 15.3583 9.13903 15.2926 9.16629C15.2268 9.19355 15.1671 9.2335 15.1168 9.28386L12.4084 11.9921C12.3326 12.0679 12.2809 12.1644 12.26 12.2695C12.2391 12.3747 12.2498 12.4836 12.2908 12.5827C12.3319 12.6817 12.4014 12.7663 12.4905 12.8258C12.5797 12.8853 12.6845 12.9171 12.7917 12.917Z" fill="black"/>
                                    </svg>`;
                                        const encodedSvg =
                                            encodeURIComponent(svgString);
                                        const imgSrc = `data:image/svg+xml;charset=utf-8,${encodedSvg}`;
                                        imgIcon.src = imgSrc;
                                        imgIcon.crossOrigin = "anonymous";
                                        imgIcon.width = 24;
                                        imgIcon.height = 24;

                                        ctx.drawImage(
                                            imgIcon,
                                            left - 12,
                                            top - 12,
                                            24,
                                            24
                                        );
                                    },
                                });

                            // Event listener for image selection (file input)
                            imageInput.addEventListener(
                                "change",
                                function (event) {
                                    const file = event.target.files[0];
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = function () {
                                            $("#shape_img").attr(
                                                "src",
                                                reader.result
                                            );

                                            fabric.Image.fromURL(
                                                reader.result,
                                                function (newImg) {
                                                    // Remove the old image if it exists
                                                    const newWidth =
                                                        newImg.width;
                                                    const newHeight =
                                                        newImg.height;
                                                    let canvasState =
                                                        canvas.toJSON();

                                                    canvasState.objects =
                                                        canvasState.objects.filter(
                                                            function (obj) {
                                                                // Check if the object is of type 'image'
                                                                if (
                                                                    obj.type ===
                                                                    "image"
                                                                ) {
                                                                    // Find the corresponding Fabric.js image object on the canvas
                                                                    const fabricImage =
                                                                        canvas
                                                                            .getObjects()
                                                                            .find(
                                                                                (
                                                                                    image
                                                                                ) =>
                                                                                    image.toObject()
                                                                                        .src ===
                                                                                    obj.src
                                                                            );
                                                                    // Remove the Fabric.js image from the canvas if found
                                                                    if (
                                                                        fabricImage
                                                                    ) {
                                                                        canvas.remove(
                                                                            fabricImage
                                                                        );
                                                                    }
                                                                    return false; // Exclude this image object from the filtered array
                                                                }
                                                                return true; // Include other objects
                                                            }
                                                        );

                                                    // Render the updated canvas state
                                                    canvas.renderAll();

                                                    newImg.set({
                                                        selectable: true,
                                                        hasControls: true,
                                                        hasBorders: true,
                                                        borderColor: "#2DA9FC",
                                                        cornerColor: "#fff",
                                                        transparentCorners: false,
                                                        lockUniScaling: true,
                                                        scaleX:
                                                            scaledWidth /
                                                            newWidth, // Scale based on element's width
                                                        scaleY:
                                                            scaledHeight /
                                                            newHeight, // Scale based on element's height
                                                        cornerSize: 10,
                                                        cornerStyle: "circle",
                                                        left:
                                                            element.centerX -
                                                            scaledWidth / 2, // Center the image horizontally
                                                        top:
                                                            element.centerY -
                                                            scaledHeight / 2,
                                                    });

                                                    shapes =
                                                        createShapes(newImg);
                                                    canvas.add(newImg);
                                                    currentImage = newImg;
                                                    // $("#shape_img").attr("src",shapeImageUrl);
                                                    shapeImageUrl =
                                                        $("#shape_img").attr(
                                                            "src"
                                                        );
                                                    // Reset shape index for the new image based on the default shape
                                                    currentShapeIndex =
                                                        shapeIndexMap[
                                                            defaultShape
                                                        ] || 0; // Default to rectangle if not found
                                                    newImg.set({
                                                        clipPath:
                                                            shapes[
                                                                currentShapeIndex
                                                            ],
                                                    });
                                                    newImg.crossOrigin =
                                                        "anonymous";

                                                    newImg.on(
                                                        "mouseup",
                                                        function (event) {
                                                            console.log(event);
                                                            if (
                                                                event?.transform
                                                                    ?.action ===
                                                                    "drag" &&
                                                                event.transform
                                                                    .actionPerformed ===
                                                                    undefined
                                                            ) {
                                                                currentShapeIndex =
                                                                    (currentShapeIndex +
                                                                        1) %
                                                                    shapes.length;
                                                                newImg.set({
                                                                    clipPath:
                                                                        shapes[
                                                                            currentShapeIndex
                                                                        ],
                                                                });
                                                                canvas.renderAll();
                                                            }
                                                        }
                                                    );

                                                    const fixClipPath = () => {
                                                        newImg.set({
                                                            clipPath:
                                                                shapes[
                                                                    currentShapeIndex
                                                                ],
                                                        });
                                                        canvas.renderAll();
                                                    };

                                                    newImg.on(
                                                        "scaling",
                                                        function () {
                                                            // isScaling = true; // Set scaling flag
                                                            fixClipPath();
                                                        }
                                                    );
                                                }
                                            );
                                        };
                                        reader.readAsDataURL(file);
                                    }
                                }
                            );
                        });
                    }
                }
            } else {
                //showStaticTextElements();
            }
            var rotateIcon =
                "data:image/svg+xml,%3Csvg%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Crect%20width%3D%2223.9674%22%20height%3D%2223.9674%22%20rx%3D%2211.9837%22%20fill%3D%22white%22%2F%3E%3Cpath%20d%3D%22M10.5407%208.52841C10.9751%208.39859%2011.4544%208.31371%2011.9837%208.31371C14.3755%208.31371%2016.3128%2010.2511%2016.3128%2012.6428C16.3128%2015.0346%2014.3755%2016.9719%2011.9837%2016.9719C9.59197%2016.9719%207.6546%2015.0346%207.6546%2012.6428C7.6546%2011.754%207.92424%2010.9252%208.38361%2010.2361%22%20stroke%3D%22%230F172A%22%20stroke-width%3D%220.998643%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3Cpath%20d%3D%22M9.92152%208.64825L11.3646%206.9905%22%20stroke%3D%22%230F172A%22%20stroke-width%3D%220.998643%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3Cpath%20d%3D%22M9.92152%208.64825L11.6042%209.87658%22%20stroke%3D%22%230F172A%22%20stroke-width%3D%220.998643%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3C%2Fsvg%3E";
            var img = document.createElement("img");
            img.src = rotateIcon;

            var deleteIcon =
                "data:image/svg+xml,%3Csvg%20width%3D%2225%22%20height%3D%2225%22%20viewBox%3D%220%200%2025%2025%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Crect%20x%3D%220.203125%22%20y%3D%220.371094%22%20width%3D%2223.9674%22%20height%3D%2223.9674%22%20rx%3D%2211.9837%22%20fill%3D%22white%22%2F%3E%3Cpath%20d%3D%22M16.6807%209.3489C15.018%209.18412%2013.3453%209.09924%2011.6775%209.09924C10.6889%209.09924%209.70022%209.14917%208.71156%209.24903L7.69295%209.3489%22%20stroke%3D%22%230F172A%22%20stroke-width%3D%220.998643%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3Cpath%20d%3D%22M10.4392%208.84458L10.5491%208.19047C10.629%207.71611%2010.6889%207.3616%2011.5327%207.3616H12.841C13.6848%207.3616%2013.7497%207.73609%2013.8246%208.19546L13.9345%208.84458%22%20stroke%3D%22%230F172A%22%20stroke-width%3D%220.998643%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3Cpath%20d%3D%22M15.6072%2010.9268L15.2826%2015.9549C15.2277%2016.7389%2015.1828%2017.348%2013.7897%2017.348H10.584C9.19091%2017.348%209.14598%2016.7389%209.09105%2015.9549L8.76649%2010.9268%22%20stroke%3D%22%230F172A%22%20stroke-width%3D%220.998643%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3Cpath%20d%3D%22M11.353%2014.6018H13.0157%22%20stroke%3D%22%230F172A%22%20stroke-width%3D%220.998643%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3Cpath%20d%3D%22M10.9385%2012.6045H13.4351%22%20stroke%3D%22%230F172A%22%20stroke-width%3D%220.998643%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3C%2Fsvg%3E";
            var img1 = document.createElement("img");
            img1.src = deleteIcon;

            var copyIcon =
                "data:image/svg+xml,%3Csvg%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Crect%20width%3D%2223.9674%22%20height%3D%2223.9674%22%20rx%3D%2211.9837%22%20fill%3D%22white%22%2F%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M14.9796%2013.981V7.98915C14.9796%207.43761%2014.5325%206.9905%2013.981%206.9905H7.98914C7.43761%206.9905%206.9905%207.43761%206.9905%207.98915V13.981C6.9905%2014.5325%207.43761%2014.9796%207.98914%2014.9796H13.981C14.5325%2014.9796%2014.9796%2014.5325%2014.9796%2013.981ZM7.98914%207.98914H13.981V13.981H7.98914V7.98914ZM15.9783%2014.9796V8.98779C16.5298%208.98779%2016.9769%209.4349%2016.9769%209.98643V14.9796C16.9769%2016.0827%2016.0827%2016.9769%2014.9796%2016.9769H9.98643C9.43489%2016.9769%208.98779%2016.5298%208.98779%2015.9783H14.9796C15.5312%2015.9783%2015.9783%2015.5312%2015.9783%2014.9796Z%22%20fill%3D%22%230F172A%22%2F%3E%3C%2Fsvg%3E";
            var img2 = document.createElement("img");
            img2.src = copyIcon;

            fabric.Textbox.prototype.controls.mtr = new fabric.Control({
                x: 0,
                y: 0.5,
                offsetY: 20,
                cursorStyle: "pointer",
                actionHandler: fabric.controlsUtils.rotationWithSnapping,
                actionName: "rotate",
                render: renderIcon,
                cornerSize: 40,
            });

            fabric.Textbox.prototype.controls.deleteControl =
                new fabric.Control({
                    x: 0.2,
                    y: -0.5,
                    offsetY: -20,
                    cursorStyle: "pointer",
                    actionHandler: (eventData, transform, x, y) => {
                        console.log(eventData);
                        const target = transform.target;
                        canvas.remove(target); // Remove object on trash icon click
                        canvas.requestRenderAll();
                    },
                    mouseUpHandler: deleteTextbox,
                    render: renderDeleteIcon,
                    cornerSize: 40,
                    withConnection: false, // Disable the line connection
                });

            fabric.Textbox.prototype.controls.copyControl = new fabric.Control({
                x: -0.2,
                y: -0.5,
                offsetY: -20,
                cursorStyle: "pointer",
                mouseUpHandler: cloneTextbox,
                render: renderCopyIcon,
                cornerSize: 40,
                withConnection: false, // Disable the line connection
            });

            // here's where the render action for the control is defined
            function renderIcon(ctx, left, top, styleOverride, fabricObject) {
                var size = this.cornerSize;
                ctx.save();
                ctx.translate(left, top);
                ctx.rotate(fabric.util.degreesToRadians(fabricObject.angle));
                ctx.drawImage(img, -size / 2, -size / 2, size, size);
                ctx.restore();
            }

            function renderDeleteIcon(
                ctx,
                left,
                top,
                styleOverride,
                fabricObject
            ) {
                var size = this.cornerSize;
                ctx.save();
                ctx.translate(left, top);
                ctx.rotate(fabric.util.degreesToRadians(fabricObject.angle));
                ctx.drawImage(img1, -size / 2, -size / 2, size, size);
                ctx.restore();
            }
            function renderCopyIcon(
                ctx,
                left,
                top,
                styleOverride,
                fabricObject
            ) {
                var size = this.cornerSize;
                ctx.save();
                ctx.translate(left, top);
                ctx.rotate(fabric.util.degreesToRadians(fabricObject.angle));
                ctx.drawImage(img2, -size / 2, -size / 2, size, size);
                ctx.restore();
            }

            // Set custom attribute with the fetched ID
            var canvasElement = document.getElementById("imageEditor1");
            canvasElement.setAttribute("data-canvas-id", temp_id);

            canvas.renderAll(); // Ensure all elements are rendered
        }
    }

    function getWidth(element, text) {
        const textMeasurement = new fabric.Text(text, {
            fontSize: element.fontSize,
            fontFamily: element.fontFamily,
            fontWeight: element.fontWeight,
            fontStyle: element.fontStyle,
            underline: element.underline,
            linethrough: element.linethrough,
        });
        const textWidth = textMeasurement.width;
        //console.log(`Width of '${text}':`, textWidth);
        return textWidth;
    }

    $(document).on("click", ".formate-text-reset", function (e) {
        var activeObject = canvas.getActiveObject();
        if (!activeObject || activeObject.type !== "textbox") {
            return;
        }

        console.log(dbJson);
        let seted = 0;
        dbJson.textElements.forEach(function (element) {
            if (
                element.text.toLowerCase() === activeObject.text.toLowerCase()
            ) {
                seted = 1;
                activeObject.set({
                    fontWeight: element.fontWeight || "",
                    fontStyle: element.fontStyle || "",
                    underline: element.underline || false,
                    linethrough: element.linethrough || false,
                    fontFamily: element.fontFamily || "Times New Roman",
                    fontSize: element.fontSize || 20,
                    textAlign: element.textAlign || "left",
                    lineHeight: element.lineHeight || 1,
                    text: element.text || activeObject.text,
                });
            }
        });
        if (seted == 0) {
            activeObject.set({
                fontWeight: "",
                fontStyle: "",
                underline: false,
                linethrough: false,
                fontFamily: "Times New Roman",
                fontSize: 20,
                textAlign: "left",
                lineHeight: 1,
                text: activeObject.text.toLowerCase(),
            });
        }
        canvas.renderAll();
        addIconsToTextbox(canvas.getActiveObject());
    });

    $(document).on("click", ".color-reset", function (e) {
        var activeObject = canvas.getActiveObject();
        if (!activeObject || activeObject.type !== "textbox") {
            return;
        }
        let seted = 0;
        dbJson.textElements.forEach(function (element) {
            if (
                element.text.toLowerCase() === activeObject.text.toLowerCase()
            ) {
                seted = 1;
                console.log(element.fill);
                let selectedColor = element.fill || "#000000";
                console.log("color-picker");
                $("#color-picker").spectrum("set", selectedColor || "#000000");

                activeObject.set("fill", selectedColor);
            }
        });
        if (seted == 0) {
            $("#color-picker").spectrum("set", "#000000");

            activeObject.set("fill", "#000000");
        }
        canvas.renderAll();
        addIconsToTextbox(canvas.getActiveObject());
    });

    $(document).on("click", ".font-reset-btn", function (e) {
        var activeObject = canvas.getActiveObject();
        if (!activeObject || activeObject.type !== "textbox") {
            return;
        }
        let seted = 0;
        dbJson.textElements.forEach(function (element) {
            if (
                element.text.toLowerCase() === activeObject.text.toLowerCase()
            ) {
                seted = 1;
                console.log(element.fill);

                activeObject.set(
                    "fontFamily",
                    element.fontFamily || "Times New Roman"
                );
            }
        });
        if (seted == 0) {
            activeObject.set("fontFamily", "Times New Roman");
        }
        canvas.renderAll();
        addIconsToTextbox(canvas.getActiveObject());
    });

    $(document).on("click", ".edit-text-reset", function (e) {
        var activeObject = canvas.getActiveObject();
        if (!activeObject || activeObject.type !== "textbox") {
            return;
        }
        let seted = 0;
        dbJson.textElements.forEach(function (element) {
            if (
                element.text.toLowerCase() === activeObject.text.toLowerCase()
            ) {
                seted = 1;

                activeObject.set({
                    fontSize: element.fontSize || "20",
                    charSpacing: element.charSpacing || "0",
                    lineHeight: element.lineHeight || "1.16",
                });

                $("#fontSizeInput").val(element.fontSize || "20");
                $("#fontSizeRange").val(element.fontSize || "20");
                $("#letterSpacingInput").val(element.charSpacing || "0");
                $("#letterSpacingRange").val(element.charSpacing || "0");
                $("#lineHeightInput").val(element.lineHeight || "1.16");
                $("#lineHeightRange").val(element.lineHeight || "1.16");
            }
        });
        if (seted == 0) {
            activeObject.set({
                fontSize: "20",
                charSpacing: "0",
                lineHeight: "1.16",
            });

            $("#fontSizeInput").val("20");
            $("#fontSizeRange").val("20");
            $("#letterSpacingInput").val(`${percentageValue.toFixed(0)}%`);
            $("#letterSpacingRange").val("0");
            $("#lineHeightInput").val("1.16");
            $("#lineHeightRange").val("1.16");
        }
        canvas.renderAll();
        addIconsToTextbox(canvas.getActiveObject());
    });
    $(document).on("click", ".edit-text-save", function (e) {
        var activeObject = canvas.getActiveObject();
        if (!activeObject || activeObject.type !== "textbox") {
            return;
        }
        addToUndoStack(canvas);
        let fontSize = $("#fontSizeInput").val();
        const charSpacing = parseFloat($("#letterSpacingRange").val()); // Ensure there's a valid value
        let lineHeight = $("#lineHeightInput").val();
        activeObject.set({
            fontSize: fontSize,
            charSpacing: charSpacing,
            lineHeight: lineHeight,
        });

        canvas.renderAll();
        addIconsToTextbox(canvas.getActiveObject());
    });

    function addIconsToTextbox(target) {
        if (target == undefined) {
            return;
        }
        if (target.fontWeight == "bold") {
            $(".bold-btn").addClass("activated");
        } else {
            $(".bold-btn").removeClass("activated");
        }
        if (target.fontStyle == "italic") {
            $(".italic-btn").addClass("activated");
        } else {
            $(".italic-btn").removeClass("activated");
        }
        if (target.underline == true) {
            $(".underline-btn").addClass("activated");
        } else {
            $(".underline-btn").removeClass("activated");
        }
        if (target.textAlign == "left") {
            $(".justyfy-left-btn").addClass("activated");
        } else {
            $(".justyfy-left-btn").removeClass("activated");
        }
        if (target.textAlign == "center") {
            $(".justyfy-center-btn").addClass("activated");
        } else {
            $(".justyfy-center-btn").removeClass("activated");
        }
        if (target.textAlign == "right") {
            $(".justyfy-right-btn").addClass("activated");
        } else {
            $(".justyfy-right-btn").removeClass("activated");
        }
        if (target.textAlign == "justify") {
            $(".justyfy-full-btn").addClass("activated");
        } else {
            $(".justyfy-full-btn").removeClass("activated");
        }
        let targetFontFamily = target.fontFamily;
        $(`.fontfamily[data-font="${targetFontFamily}"]`).prop("checked", true);
        $("#letterSpacingRange").val(target?.charSpacing || 0);

        // const charSpacing = target.charSpacing || 0; // Ensure there's a valid value
        const charSpacing = parseFloat($("#letterSpacingRange").val()); // Ensure there's a valid value

        const percentageValue = (charSpacing / 500) * 100;

        // Update the input box with the percentage value
        $("#letterSpacingInput").val(`${percentageValue.toFixed(0)}%`);

        // Update the range slider with the original value
        $("#letterSpacingRange").val(charSpacing);

        $("#fontSizeInput").val(target.fontSize);
        $("#fontSizeRange").val(target.fontSize);
        // $("#letterSpacingInput").val(target.charSpacing);
        // $("#letterSpacingRange").val(target.charSpacing);
        $("#lineHeightInput").val(target.lineHeight);
        $("#lineHeightRange").val(target.lineHeight);
        $(".size-btn").removeClass("activated");

        const text = target.text.trim();
        console.log({ text });
        // Helper functions to determine the case
        const isUpperCase = (str) => str === str.toUpperCase();
        const isLowerCase = (str) => str === str.toLowerCase();
        const isCapitalized = (str) =>
            str
                .split(" ")
                .every(
                    (word) =>
                        word.charAt(0).toUpperCase() +
                            word.slice(1).toLowerCase() ===
                        word
                );

        if (isUpperCase(text)) {
            $(".uppercase-btn").addClass("activated");
        } else if (isLowerCase(text)) {
            $(".lowercase-btn").addClass("activated");
        } else if (isCapitalized(text)) {
            $(".capitalize-btn").addClass("activated");
        }
    }
    $(".design-sidebar-action").click(function () {
        $(".choose-design-sidebar").removeClass("ds");
        $(".design-sidebar-action").removeClass("activated");
        $(this).addClass("activated");
    });
    $(".size-btn").click(function () {
        $(".size-btn").removeClass("activated");
        $(this).addClass("activated");
    });
    canvas = new fabric.Canvas("imageEditor1", {
        width: width, // Canvas width
        height: height, // Canvas height
    });
    const ctx = canvas.getContext("2d");
    const defaultSettings = {
        fontSize: 20,
        letterSpacing: 0,
        lineHeight: 1.2,
    };

    // Save settings object (for the save functionality)
    let savedSettings = {
        fontSize: defaultSettings.fontSize,
        letterSpacing: defaultSettings.letterSpacing,
        lineHeight: defaultSettings.lineHeight,
    };

    // Function to update textbox width dynamically
    var updateTextBoxTime = 0;
    const updateTextboxWidth = (textbox) => {
        const text = textbox.text || ""; // Get current text
        const fontSize = textbox.fontSize || defaultSettings.fontSize; // Get current font size
        const fontFamily = textbox.fontFamily || "Times New Roman"; // Default font family
        const charSpacing = textbox.charSpacing || 0;

        const ctx = canvas.getContext("2d");
        ctx.font = `${fontSize}px ${fontFamily}`;

        const measuredTextWidth = ctx.measureText(text).width;
        const calculatedWidth =
            measuredTextWidth +
            (charSpacing / 1000) * fontSize * (text.length - 1);

        // Define a maximum width to avoid large textboxes
        const maxWidth = 400; // Adjust this value based on your layout
        const width = Math.min(calculatedWidth, maxWidth); // Cap the width
        console.log(width);

        // Handle text wrapping for large texts
        textbox.set("width", width);
        textbox.set("textAlign", "left"); // Ensure text wraps within the textbox
        textbox.setCoords();

        // Set to 'clipTo' or 'overflow' if necessary based on design
        textbox.set("noScaleCache", false); // Redraw the text after resizing
        canvas.renderAll();
    };

    // Set font size function
    const setFontSize = () => {
        const newValue = fontSizeRange.value;
        fontSizeInput.value = newValue;
        fontSizeTooltip.innerHTML = `<span>${newValue}px</span>`;

        const activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === "textbox") {
            clearTimeout(updateTextBoxTime);
            updateTextBoxTime = setTimeout(function () {
                addToUndoStack(canvas);
                activeObject.set("fontSize", newValue);
                updateTextboxWidth(activeObject);
            }, 800);
        }
    };

    // Set letter spacing function
    const setLetterSpacing = () => {
        const sliderValue = parseFloat(letterSpacingRange.value); // Ensure it's a number
        const percentageValue = (sliderValue / 500) * 100; // Normalize to percentage

        // Update the input with the percentage value
        letterSpacingInput.value = `${percentageValue.toFixed(0)}%`;
        letterSpacingTooltip.innerHTML = `<span>${percentageValue.toFixed(
            0
        )}%</span>`;

        // Log the slider value and percentage for debugging
        // console.log(
        //     `Slider Value: ${sliderValue}, Percentage: ${percentageValue.toFixed(
        //         0
        //     )}%`
        // );

        // Update the canvas object
        const activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === "textbox") {
            clearTimeout(updateTextBoxTime);
            updateTextBoxTime = setTimeout(function () {
                addToUndoStack(canvas);
                activeObject.set("charSpacing", sliderValue);
                updateTextboxWidth(activeObject);
            }, 800);
        }
    };

    // Function to update line height
    const setLineHeight = () => {
        const newValue = parseFloat(lineHeightRange.value);
        lineHeightInput.value = newValue;
        lineHeightTooltip.innerHTML = `<span>${newValue}</span>`;

        const activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === "textbox") {
            activeObject.set("lineHeight", newValue);
            canvas.renderAll();
        }
    };

    // Event listeners for sliders and input fields
    fontSizeRange.addEventListener("input", setFontSize);
    fontSizeInput.addEventListener("input", () => {
        fontSizeRange.value = fontSizeInput.value;
        setTimeout(() => {
            setFontSize();
        }, 500);
    });

    // Attach event listeners
    letterSpacingRange.addEventListener("input", setLetterSpacing);
    letterSpacingInput.addEventListener("input", () => {
        letterSpacingInput.value = letterSpacingInput.value.replace(
            /[^0-9.]/g,
            ""
        );

        // Parse the numeric value from the input
        const inputValue = parseFloat(letterSpacingInput.value);

        if (!isNaN(inputValue) && inputValue >= 0 && inputValue <= 100) {
            const sliderValue = Math.round((inputValue / 100) * 500); // Map percentage to slider value
            letterSpacingRange.value = sliderValue;
            setTimeout(() => {
                setLetterSpacing();
            }, 500);
        } else {
            if (inputValue >= 100) {
                letterSpacingInput.value = 100;
            }
            console.log(
                "Invalid input: Please enter a value between 0% and 100%"
            );
        }
    });

    lineHeightRange.addEventListener("input", setLineHeight);
    lineHeightInput.addEventListener("input", () => {
        lineHeightRange.value = lineHeightInput.value;
        setTimeout(() => {
            setLineHeight();
        }, 500);
    });

    // Save button functionality
    // document.querySelector(".save-btn").addEventListener("click", function () {
    //     const activeObject = canvas.getActiveObject();
    //     if (activeObject && activeObject.type === "textbox") {
    //         savedSettings.fontSize = activeObject.fontSize;
    //         savedSettings.letterSpacing = activeObject.charSpacing / 10; // Convert back to user scale
    //         savedSettings.lineHeight = activeObject.lineHeight;
    //         alert("Settings have been saved!");
    //     }
    // });
    const resetTextboxProperties = (object) => {
        object.set({
            fontSize: defaultSettings.fontSize,
            charSpacing: defaultSettings.letterSpacing * 10, // Adjusted for Fabric.js
            lineHeight: defaultSettings.lineHeight,
            fontFamily: "Arial",
            textAlign: "left",
            fill: "#000", // Optional: Reset text color
        });

        updateTextboxWidth(object);
    };
    // Reset button functionality
    // document.querySelector(".reset-btn").addEventListener("click", function () {
    //     //console.log("Reset button clicked!");
    //     const activeObject = canvas.getActiveObject();
    //     if (activeObject && activeObject.type === "textbox") {
    //         resetTextboxProperties(activeObject); // Use the reset function
    //         canvas.renderAll(); // Re-render the canvas

    //         // Reset input fields and tooltips to default values
    //         fontSizeInput.value = defaultSettings.fontSize;
    //         fontSizeRange.value = defaultSettings.fontSize;
    //         fontSizeTooltip.innerHTML = `<span>${defaultSettings.fontSize}px</span>`;

    //         letterSpacingInput.value = defaultSettings.letterSpacing;
    //         letterSpacingRange.value = defaultSettings.letterSpacing;
    //         letterSpacingTooltip.innerHTML = `<span>${defaultSettings.letterSpacing}</span>`;

    //         lineHeightInput.value = defaultSettings.lineHeight;
    //         lineHeightRange.value = defaultSettings.lineHeight;
    //         lineHeightTooltip.innerHTML = `<span>${defaultSettings.lineHeight}</span>`;

    //         updateTextboxWidth(activeObject); // Update the textbox width to fit the default settings
    //         canvas.renderAll(); // Refresh the canvas to apply changes

    //         alert("Settings have been reset to default.");
    //     } else {
    //         alert("Please select a textbox to reset the settings.");
    //     }
    // });

    // Initialize tooltips and values on page load
    setFontSize();
    setLetterSpacing();
    setLineHeight();

    let clrcanvas = {};
    setTimeout(function () {
        let spchoose = document.getElementsByClassName("sp-choose");
        console.log({ spchoose });
        $(spchoose).click(function () {
            // alert('clicked')
            setTimeout(function () {
                console.log({ clrcanvas });
                undoStack.push(clrcanvas);
                if ($(".sp-input").val() === "#000000") {
                    changeColor("#000000");
                }
                if (undoStack.length > 0) {
                    $("#undoButton").find("svg path").attr("fill", "#0F172A");
                }
                redoStack = []; // Clear redo stack on new action
            }, 1000);
        });
    }, 1000);

    $(document).on("change", ".sp-input", function () {
        var color = $(this).val();
        console.log(color);
        changeColor(color);
    });
    // Initialize the color picker
    $("#color-picker").spectrum({
        type: "flat",
        color: "#000000", // Default font color
        showInput: true,
        allowEmpty: true, // Allows setting background to transparent
        showAlpha: true, // Allows transparency adjustment
        preferredFormat: "hex",
        move: function (color) {
            if (color) {
                changeColor(color.toHexString()); // Apply color in real-time
            }
        },
        change: function (color) {
            if (color) {
                changeColor(color.toHexString()); // Use RGB string for color changes
            } else {
                changeColor("#000000"); // Handle transparency by default
            }
        },
    });

    // Function to change font or background color
    function changeColor(selectedColor) {
        const selectedColorType = document.querySelector(
            'input[name="colorType"]:checked'
        ).value;
        const activeObject = canvas.getActiveObject();
        console.log("before update");

        //console.log(activeObject);
        if (!activeObject) {
            //console.log("No object selected");
            return;
        }

        if (activeObject.type == "textbox") {
            clrcanvas = canvas.toJSON();
            //console.log(activeObject.type);
            //console.log(activeObject.fill);
            if (selectedColorType == "font") {
                if (selectedColor != $(".sp-input").val()) {
                    return;
                }
                //console.log(activeObject.fill);
                //console.log(activeObject.backgroundColor);
                activeObject.set("fill", selectedColor); // Change font color
                //console.log(activeObject.fill);
                //console.log(activeObject.backgroundColor);
            } else if (selectedColorType == "background") {
                //console.log("update background");
                activeObject.set("backgroundColor", selectedColor); // Change background color
            }
            canvas.renderAll(); // Re-render the canvas after color change
        }

        //console.log("ater update");

        //console.log(activeObjec);
    }
    $(document).on("click", ".color-reset", function (e) {
        updateColorPicker();
    });

    // Update color picker based on the selected object's current font or background color
    function updateColorPicker() {
        const activeObject = canvas.getActiveObject();
        const selectedColorType = document.querySelector(
            'input[name="colorType"]:checked'
        ).value;

        if (activeObject && activeObject.type === "textbox") {
            if (selectedColorType === "font") {
                console.log("colorpicker update");
                $("#color-picker").spectrum(
                    "set",
                    activeObject.fill || "#000000"
                ); // Set font color in picker
            } else if (selectedColorType === "background") {
                const bgColor =
                    activeObject.backgroundColor || "rgba(0, 0, 0, 0)"; // Default to transparent background
                $("#color-picker").spectrum("set", bgColor); // Set current background color in picker
            }

            //console.log(selectedColorType);
            //console.log(activeObject.type);
            //console.log(activeObject.fill);
            //console.log(activeObject.backgroundColor);

            const activeObjec = canvas.getActiveObject();

            //console.log(activeObjec.fill);
            //console.log(activeObjec.backgroundColor);
        }
    }

    // Update color picker when object selection changes
    canvas.on("selection:created", updateColorPicker);
    canvas.on("selection:updated", updateColorPicker);

    // Update the color picker when the color type (font/background) changes
    $(".colorTypeInp").click(function (e) {
        e.stopPropagation();
        console.log(123);
        const activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === "textbox") {
            //console.log(activeObject.type);
            updateColorPicker(); // Update picker when the selected color type changes
        }
    });

    // Load background image and make it non-draggable
    document
        .getElementById("image")
        .addEventListener("change", function (event) {
            var file = event.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (e) {};
                reader.readAsDataURL(file);
            }
        });

    // Call function to load data when the page loads
    loadTextDataFromDatabase();

    function hideStaticTextElements() {
        canvas.getObjects("textbox").forEach(function (textbox) {
            if (textbox.isStatic) {
                textbox.set("visible", false);
                if (textbox.copyIcon) {
                    textbox.copyIcon.set("visible", false);
                }
                if (textbox.trashIcon) {
                    textbox.trashIcon.set("visible", false);
                }
            }
        });
        canvas.renderAll();
    }

    function showStaticTextElements() {
        canvas.getObjects("textbox").forEach(function (textbox) {
            if (textbox.isStatic) {
                textbox.set("visible", true);
                if (textbox.copyIcon) {
                    textbox.copyIcon.set("visible", true);
                }
                if (textbox.trashIcon) {
                    textbox.trashIcon.set("visible", true);
                }
            }
        });
        canvas.renderAll();
    }

    function addDraggableText(left, top, textContent) {
        var text = new fabric.Textbox(textContent, {
            left: left,
            top: top,
            fontSize: 20,
            backgroundColor: "rgba(0, 0, 0, 0)", // Set background to transparent
            fill: "#000000", // Default text color (black)
            editable: true,
            selectable: true,
            isStatic: true,
            visible: true,
            hasControls: true,
            lineHeight: element.lineHeight || 2,
            letterSpacing: 0,
        });

        // Approximate width based on text length
        text.set("width", text.get("text").length * 10);

        // Event listener for scaling
        text.on("scaling", function () {
            var updatedFontSize =
                (text.fontSize * (text.scaleX + text.scaleY)) / 2;
            text.set("fontSize", updatedFontSize);
            canvas.renderAll();
            findTextboxCenter(text); // Find center when scaling
        });

        // Event listener for moving
        text.on("moving", function () {
            findTextboxCenter(text); // Find center when moving
        });

        // Add the textbox to the canvas
        canvas.add(text);

        canvas.renderAll();

        // Initial center calculation
        findTextboxCenter(text);
    }

    function calculateControlPositions(object) {
        var controlCoords = object.oCoords; // Get object control coordinates

        // Get the position of the 'mtr' control
        var mtrControl = controlCoords.mtr; // 'mtr' control (rotate)

        // Log the untransformed mtr control position
        console.log("Rotation control position (mtr):", mtrControl);

        // Transform mtr control position to apply rotation and scaling
        var transformedMtr = fabric.util.transformPoint(
            new fabric.Point(mtrControl.x, mtrControl.y),
            object.calcTransformMatrix() // apply object transformations (rotation, scaling)
        );

        return transformedMtr;
    }

    function findTextboxCenter(textbox) {
        var centerX = textbox.left + textbox.width / 2;
        var centerY = textbox.top + textbox.height / 2;
        var centerPoint = textbox.getCenterPoint();
        //console.log(
        //  `Center of textbox '${textbox.text}' is at (${centerX}, ${centerY})`
        // );
        return {
            x: centerX,
            y: centerY,
        };
    }

    function updateIconsPositions(textbox) {
        const angle = fabric.util.degreesToRadians(textbox.angle);
        const boundingRect = textbox.getBoundingRect(true);

        // Calculate the new position for the trash icon
        const trashOffsetX = +75; // Offset for the trash icon
        const trashOffsetY = -30; // Adjust icon's vertical position
        const trashRotatedX =
            textbox.left +
            trashOffsetX * Math.cos(angle) -
            trashOffsetY * Math.sin(angle);
        const trashRotatedY =
            textbox.top +
            trashOffsetX * Math.sin(angle) +
            trashOffsetY * Math.cos(angle);

        if (textbox.trashIcon) {
            textbox.trashIcon.left = trashRotatedX;
            textbox.trashIcon.top = trashRotatedY;
            textbox.trashIcon.angle = textbox.angle; // Sync icon rotation with textbox
        }

        // Calculate the new position for the copy icon
        const copyOffsetX = -4; // Offset for the copy icon on the left
        const copyOffsetY = -25;
        const copyRotatedX =
            textbox.left +
            copyOffsetX * Math.cos(angle) -
            copyOffsetY * Math.sin(angle);
        const copyRotatedY =
            textbox.top +
            copyOffsetX * Math.sin(angle) +
            copyOffsetY * Math.cos(angle);

        if (textbox.copyIcon) {
            textbox.copyIcon.left = copyRotatedX;
            textbox.copyIcon.top = copyRotatedY;
            textbox.copyIcon.angle = textbox.angle; // Sync icon rotation with textbox
        }

        canvas.renderAll(); // Re-render canvas to update positions
    }

    function updateIconPositions(textbox) {
        // Remove old trash and copy icons if they exist
        if (textbox.trashIcon) {
            canvas.remove(textbox.trashIcon);
            textbox.trashIcon = null; // Clear reference
        }
        if (textbox.copyIcon) {
            canvas.remove(textbox.copyIcon);
            textbox.copyIcon = null; // Clear reference
        }

        // Define SVG strings for trash and copy icons
        const trashIconSVG = `<svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g filter="url(#filter0_d_5633_67674)">
            <rect x="2.70312" y="2.37207" width="23.9674" height="23.9674" rx="11.9837" fill="white" shape-rendering="crispEdges"/>
            <path d="M19.1807 11.3502C17.5179 11.1855 15.8452 11.1006 14.1775 11.1006C13.1888 11.1006 12.2001 11.1505 11.2115 11.2504L10.1929 11.3502" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M12.939 10.8463L13.0488 10.1922C13.1287 9.7178 13.1886 9.36328 14.0325 9.36328H15.3407C16.1846 9.36328 16.2495 9.73777 16.3244 10.1971L16.4342 10.8463" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M18.1073 12.9277L17.7827 17.9559C17.7278 18.7398 17.6829 19.349 16.2898 19.349H13.0841C11.691 19.349 11.6461 18.7398 11.5912 17.9559L11.2666 12.9277" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M13.853 16.6035H15.5158" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M13.4385 14.6055H15.9351" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
            </g>
            </svg>`;

        const copyIconSVG = `<svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g filter="url(#filter0_d_5633_67676)">
            <rect x="2.64893" y="2.37207" width="23.9674" height="23.9674" rx="11.9837" fill="white" shape-rendering="crispEdges"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M17.6283 16.3538V10.3619C17.6283 9.81039 17.1812 9.36328 16.6297 9.36328H10.6378C10.0863 9.36328 9.63916 9.81039 9.63916 10.3619V16.3538C9.63916 16.9053 10.0863 17.3524 10.6378 17.3524H16.6297C17.1812 17.3524 17.6283 16.9053 17.6283 16.3538ZM10.6379 10.362H16.6298V16.3539H10.6379V10.362ZM18.6271 17.3525V11.3607C19.1786 11.3607 19.6257 11.8078 19.6257 12.3593V17.3525C19.6257 18.4556 18.7315 19.3498 17.6284 19.3498H12.6352C12.0837 19.3498 11.6366 18.9027 11.6366 18.3512H17.6284C18.1799 18.3512 18.6271 17.9041 18.6271 17.3525Z" fill="#0F172A"/>
            </g>
            </svg>`;

        // Load trash icon from SVG string and position
        fabric.loadSVGFromString(trashIconSVG, function (objects, options) {
            const trashIcon = fabric.util.groupSVGElements(objects, options);
            trashIcon.set({
                left: textbox.left + textbox.width * textbox.scaleX - 20,
                top: textbox.top - 20,
                selectable: false,
                evented: true,
                hasControls: false,
            });
            textbox.trashIcon = trashIcon;

            // Attach delete functionality to trash icon
            trashIcon.on("mousedown", function () {
                //console.log("Trash icon clicked! Deleting textbox.");
                deleteTextbox(textbox);
            });

            // Add trash icon to canvas
            canvas.add(trashIcon);
            canvas.bringToFront(trashIcon);
        });

        // Load copy icon from SVG string and position
        fabric.loadSVGFromString(copyIconSVG, function (objects, options) {
            const copyIcon = fabric.util.groupSVGElements(objects, options);
            copyIcon.set({
                left: textbox.left - 25,
                top: textbox.top - 20,
                selectable: false,
                evented: true,
                hasControls: false,
            });
            textbox.copyIcon = copyIcon;

            // Attach clone functionality to copy icon
            copyIcon.on("mousedown", function () {
                //console.log("Copy icon clicked!");
                cloneTextbox(textbox);
            });

            // Add copy icon to canvas
            canvas.add(copyIcon);
            canvas.bringToFront(copyIcon);
        });

        // Ensure textbox and icons stay visible
        canvas.bringToFront(textbox);
        canvas.renderAll();
    }

    // Function to add icons to a textbox

    function deleteTextbox() {
        addToUndoStack(canvas);
        canvas.remove(canvas.getActiveObject());
        canvas.renderAll();
    }

    $(".removeShapImage").click(function () {
        // $('.resize-handle').hide();
        // $("#imageWrapper").hide();
        $(this).hide();
        $(".uploadShapImage").show();
        $("#image").attr("src", shapeImageUrl);
    });

    $(document).on("change", ".uploadShapImage", function (event) {
        event.preventDefault();

        var file = event.target.files[0]; // Get the first file (the selected image)
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#user_image").attr("src", e.target.result).show();
            };
            reader.readAsDataURL(file);
            $(".uploadShapImage").hide();
            $(".removeShapImage").show();
        }
    });

    function cloneTextbox() {
        let originalTextbox = canvas.getActiveObject();

        if (!originalTextbox || originalTextbox.type !== "textbox") {
            toastr.error("Please select a textbox to clone.");
            return;
        }

        // Get canvas center
        let canvasCenter = canvas.getCenter();
        const clonedTextbox = new fabric.Textbox(originalTextbox.text, {
            // left: originalTextbox.left + 30, // Offset position
            // top: originalTextbox.top + 30, // Offset position
            left: canvasCenter.left - 50, // Center horizontally
            top: 50,
            fontSize: originalTextbox.fontSize,
            fill: originalTextbox.fill,
            width: originalTextbox.width + 10,
            height: originalTextbox.height,
            fontFamily: originalTextbox.fontFamily,
            originX: originalTextbox.originX,
            originY: originalTextbox.originY,
            lineHeight: originalTextbox.lineHeight || 2,
            letterSpacing: 0,
            hasControls: true,
            hasBorders: true,
            lockScalingFlip: true,
            editable: true,
            fontWeight: originalTextbox.fontWeight,
            fontStyle: originalTextbox.fontStyle,
            underline: originalTextbox.underline,
            borderColor: "#2DA9FC",
            // cornerColor: 'red',
            cornerColor: "#fff",
            cornerSize: 10,
            transparentCorners: false,
            isStatic: true,
            backgroundColor: "rgba(0, 0, 0, 0)",
        });

        // canvas.add(clonedTextbox);
        canvas.add(clonedTextbox);

        canvas.renderAll();
        setControlVisibilityForAll();
    }

    // Handle keyboard events for delete and copy
    function handleKeyboardEvents(e) {
        if (e.key === "Delete") {
            const activeObject = canvas.getActiveObject();
            if (activeObject && activeObject.type === "textbox") {
                deleteTextbox(activeObject);
            }
        } else if (e.ctrlKey && e.key === "c") {
            const activeObject = canvas.getActiveObject();
            if (activeObject && activeObject.type === "textbox") {
                cloneTextbox(activeObject);
            }
        }
    }

    // Add event listener for keyboard events
    document.addEventListener("keydown", handleKeyboardEvents);

    function updateSelectedTextProperties() {
        var fontSize = parseInt(document.getElementById("fontSize").value, 10);
        var fontColor = document.getElementById("fontColor").value;
        addToUndoStack(canvas);
        var activeObject = canvas.getActiveObject();

        if (activeObject && activeObject.type === "textbox") {
            // Update text properties
            activeObject.set({
                fontSize: fontSize,
                fill: fontColor,
            });
            activeObject.setCoords(); // Update coordinates

            canvas.renderAll();
            // Save state after updating properties
        }
    }

    function discardIfMultipleObjects(options) {
        if (
            options.target !== undefined &&
            options.target?._objects &&
            options.target?._objects.length > 1
        ) {
            console.log("Multiple objects selected:", options.target);
            canvas.discardActiveObject();
            canvas.renderAll(); // Ensure the canvas is refreshed
        }

        const activeObjects = canvas.getActiveObjects(); // Get all selected objects
        //console.log(activeObjects)
        if (activeObjects.length > 1) {
            console.log("Multiple objects selected:", activeObjects);
            canvas.discardActiveObject(); // Discard active selection
            canvas.renderAll(); // Refresh the canvas
        }
        if (!options.target) {
            console.log("Clicked outside, unselecting textboxes");
            canvas.discardActiveObject();
            canvas.renderAll();
        }
    }

    $(document).on("click", ".main-content-right", function (e) {
        // console.log(e);
        let target = e.target;
        let tagName = target.tagName.toLowerCase();
        if (
            target.id === "addTextButton" || // Ignore "Add Text" button
            target.classList.contains("design-sidebar-action") || // Ignore sidebar buttons
            target.classList.contains("upper-canvas") || // Ignore Fabric.js canvas interactions
            ["svg", "path", "h6", "button"].includes(tagName) // Ignore SVGs, paths, text elements, and buttons
        ) {
            return; // Do nothing
        }
        canvas.discardActiveObject();
        canvas.renderAll();
    });

    canvas.on("mouse:down", function (options) {
        discardIfMultipleObjects(options);

        if (options.target && options.target.type === "textbox") {
            console.log("clicked on text box");
            eventData.desgin_selected = "";
            canvas.setActiveObject(options.target);
            addIconsToTextbox(options.target);
        } else {
            // alert();
            canvas.getObjects("textbox").forEach(function (tb) {
                if (tb.trashIcon) tb.trashIcon.set("visible", false);
                if (tb.copyIcon) tb.copyIcon.set("visible", false);
            });
            canvas.discardActiveObject();
            canvas.renderAll();
        }
    });

    canvas.on("mouse:up", function (options) {
        discardIfMultipleObjects(options);
    });

    document
        .getElementById("addTextButton")
        .addEventListener("click", function () {
            addEditableTextbox(100, 100, "EditableText"); // You can set the initial position and default text
        });

    function addEditableTextbox(left, top, textContent) {
        var textbox = new fabric.Textbox(textContent, {
            left: left,
            top: top,
            // width: 100,
            fontSize: 20,
            backgroundColor: "rgba(0, 0, 0, 0)", // Set background to transparent
            textAlign: "center",
            fill: "#0a0b0a",
            editable: true,
            selectable: true,
            hasControls: true,
            borderColor: "#2DA9FC",
            cornerColor: "#fff",
            cornerStyle: "circle",
            cornerSize: 10,
            transparentCorners: false,
            textAlign: "center",
            lineHeight: 2,
            letterSpacing: 0,
        });
        textbox.setControlsVisibility({
            mt: false, // Hide middle top control
            mb: false, // Hide middle bottom control
            bl: true, // Hide bottom left control
            br: true, // Hide bottom right control
            tl: true, // Hide top left control
            tr: true, // Hide top right control
            ml: true, // Show middle left control
            mr: true, // Show middle right control
        });

        canvas.add(textbox);
        canvas.setActiveObject(textbox);

        canvas.renderAll();
    }

    // Click event remains the same but fonts are now preloaded
    document.querySelectorAll(".font-input").forEach(function (input) {
        input.addEventListener("click", function () {
            const font = this.getAttribute("data-font");
            console.log("Selected font:", font);
            applyFont(font, true); // Apply preloaded font instantly
        });
    });

    async function ensureFontsLoaded() {
        let fontsToLoad = []; // Array to store font observers
        document.querySelectorAll(".font-input").forEach(function (input) {
            const font = input.getAttribute("data-font");
            let fontObserver = new FontFaceObserver(font);
            fontsToLoad.push(fontObserver.load());
        });

        const fontLoadPromises = fontsToLoad.map((font) => {
            return new FontFaceObserver(font).load();
        });

        try {
            await Promise.all(fontLoadPromises);
            console.log("All fonts loaded successfully!");
        } catch (e) {
            console.error("Font loading error: ", e);
        }
    }

    // Function to apply the font (since fonts are already preloaded)
    function applyFont(font) {
        addToUndoStack(canvas);

        var activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === "textbox") {
            activeObject.set({ fontFamily: font });
            activeObject.initDimensions();
            canvas.requestRenderAll();
        } else {
            console.log("No object selected");
        }
    }

    // document.querySelectorAll(".form-check-input").forEach(function (input) {
    //     input.addEventListener("click", function () {
    //         const font = this.getAttribute("data-font");
    //         console.log("Selected font:", font);
    //         loadAndUse(font); // Call loadAndUse function with the selected font
    //     });
    // });

    canvas.on("object:scaling", function (e) {
        var activeObject = e.target;

        // Check if the scaled object is the textbox
        if (activeObject && activeObject.type === "textbox") {
            // Get the current font size
            var currentFontSize = Math.round(activeObject.fontSize);

            console.log("Current font size: " + currentFontSize);

            // Calculate new font size based on scale factor
            var newFontSize = Math.round(currentFontSize * activeObject.scaleX); // Adjust the font size based on the horizontal scaling factor
            const textMeasurement = new fabric.Text(activeObject.text, {
                fontSize: newFontSize,
                fontFamily: activeObject.fontFamily,
                fontWeight: activeObject.fontWeight,
                fontStyle: activeObject.fontStyle,
                underline: activeObject.underline,
                linethrough: activeObject.linethrough,
            });
            const textWidth = textMeasurement.width;
            // Set the new font size and reset scale
            activeObject.set({
                fontSize: newFontSize,
                scaleX: 1, // Reset scaleX to 1 to prevent cumulative scaling
                scaleY: 1, // Reset scaleY to 1 if you want to keep uniform scaling
                width: textWidth + 5,
                textAlign: activeObject.textAlign,
            });

            // Re-render the canvas to apply the changes
            canvas.renderAll();

            console.log("Updated font size: " + newFontSize);
        }
    });
    //     textElement.style.fontFamily = 'Allura'; // Change to Allura font
    // });

    function loadAndUse(font) {
        var myfont = new FontFaceObserver(font);
        addToUndoStack(canvas);
        myfont
            .load()
            .then(function () {
                // When font is loaded, use it.
                var activeObject = canvas.getActiveObject();
                //console.log(activeObject.type);
                if (activeObject && activeObject.type === "textbox") {
                    activeObject.set({
                        fontFamily: font,
                    });
                    activeObject.initDimensions();
                    canvas.requestRenderAll();
                    //console.log("applied font" + font);
                    //console.log(canvas.getActiveObject());
                } else {
                    alert("No object selected");
                }
            })
            .catch(function (e) {
                console.log(e);
                console.warn("Font loading failed: " + font);
            });
    }

    function setControlVisibilityForAll() {
        canvas.getObjects().forEach((obj) => {
            console.log(obj);
            var currentFontSize = obj.fontSize;
            console.log("Current font size: " + currentFontSize);

            // Calculate new font size based on scale factor
            var newFontSize = currentFontSize * obj.scaleX; // Adjust the font size based on the horizontal scaling factor
            const textMeasurement = new fabric.Text(obj.text, {
                fontSize: newFontSize,
                fontFamily: obj.fontFamily,
                fontWeight: obj.fontWeight,
                fontStyle: obj.fontStyle,
                underline: obj.underline,
                linethrough: obj.linethrough,
            });
            const textWidth = textMeasurement.width;

            obj.set("width", textWidth);
            obj.set("fontSize", newFontSize);

            obj.setControlsVisibility({
                mt: false,
                mb: false,
                bl: true,
                br: true,
                tl: true,
                tr: true,
                ml: true,
                mr: true,
            });

            obj.set("transparentCorners", false);
            obj.set("borderColor", "#2DA9FC");
            obj.set("cornerSize", 10);
            obj.set("cornerColor", "#fff");
            // Set text alignment if the object is a text-based object
            if (obj.type === "textbox" || obj.type === "text") {
                obj.set("textAlign", "center"); // Set text alignment to center
            }

            obj.on("rotating", function () {
                // Get the bounding rectangle of the textboxbox
                var boundingRect = obj.getBoundingRect();
                var centerX = boundingRect.left + boundingRect.width / 2;
                var centerY = boundingRect.top + boundingRect.height / 2;
                var rotationAngle = obj.angle;
                // console.log('Rotated Position:', { centerX: centerX, centerY: centerY, rotation: rotationAngle });
            });
        });
        canvas.renderAll();
    }

    function executeCommand(command, font = null) {
        var activeObject = canvas.getActiveObject();

        if (!activeObject || activeObject.type !== "textbox") {
            return; // No object or not a textbox, so do nothing
        }
        console.log("add to undo");
        addToUndoStack(canvas); // Save state for undo/redo functionality

        // Commands object to handle various styles and operations
        const commands = {
            bold: () =>
                activeObject.set(
                    "fontWeight",
                    activeObject.fontWeight === "bold" ? "normal" : "bold"
                ),
            italic: () =>
                activeObject.set(
                    "fontStyle",
                    activeObject.fontStyle === "italic" ? "normal" : "italic"
                ),
            underline: () =>
                activeObject.set("underline", !activeObject.underline),
            setLineHeight: (value) => activeObject.set("lineHeight", value),
            strikeThrough: () =>
                activeObject.set("linethrough", !activeObject.linethrough),
            removeFormat: () => {
                activeObject.set({
                    fontWeight: "",
                    fontStyle: "",
                    underline: false,
                    linethrough: false,
                    fontFamily: "Arial",
                });
            },
            fontName: (font) => {
                if (font) {
                    console.log("load and use command");
                    // loadAndUse(font);
                }
            },
            justifyLeft: () => activeObject.set("textAlign", "left"),
            justifyCenter: () => activeObject.set("textAlign", "center"),
            justifyRight: () => activeObject.set("textAlign", "right"),
            justifyFull: () => activeObject.set("textAlign", "justify"),
            uppercase: () => {
                activeObject.set("text", activeObject.text.toUpperCase());
                activeObject.set("textCase", "uppercase"); // Add custom property
            },
            lowercase: () => {
                activeObject.set("text", activeObject.text.toLowerCase());
                activeObject.set("textCase", "lowercase"); // Add custom property
            },
            capitalize: () => {
                const capitalizedText = activeObject.text
                    .toLowerCase() // Convert everything to lowercase first
                    .replace(/\b\w/g, (char) => char.toUpperCase()) // Capitalize first letter of each word
                    .replace(/'\w/g, (match) => match.toLowerCase()); // Ensure letters after apostrophe stay lowercase

                activeObject.set("text", capitalizedText);
                activeObject.set("textCase", "capitalize"); // Add custom property
            },
        };

        // Execute the corresponding command
        if (commands[command]) {
            commands[command](font); // Pass font to fontName if needed

            canvas.renderAll(); // Re-render canvas after change
        }
        addIconsToTextbox(activeObject);
    }

    document.querySelectorAll("[data-command]").forEach(function (button) {
        button.addEventListener("click", function () {
            const command = button.getAttribute("data-command");
            if (
                command == "fontName" ||
                command == "undo" ||
                command == "redo"
            ) {
                return;
            }
            executeCommand(this.getAttribute("data-command"));
        });
    });

    let isAddingToUndoStack = 0;
    function createShapes(img) {
        const imgWidth = img.width;
        const imgHeight = img.height;
        const starScale = Math.min(imgWidth, imgHeight) / 2;
        const starPoints = [
            { x: 0, y: -starScale },
            { x: starScale * 0.23, y: -starScale * 0.31 },
            { x: starScale, y: -starScale * 0.31 },
            { x: starScale * 0.38, y: starScale * 0.12 },
            { x: starScale * 0.58, y: starScale },
            { x: 0, y: starScale * 0.5 },
            { x: -starScale * 0.58, y: starScale },
            { x: -starScale * 0.38, y: starScale * 0.12 },
            { x: -starScale, y: -starScale * 0.31 },
            { x: -starScale * 0.23, y: -starScale * 0.31 },
        ];

        return [
            new fabric.Rect({
                width: imgWidth,
                height: imgHeight,
                originX: "center",
                originY: "center",
                angle: 0,
            }),
            new fabric.Circle({
                radius: Math.min(imgWidth, imgHeight) / 2,
                originX: "center",
                originY: "center",
                angle: 0,
            }),
            new fabric.Triangle({
                width: imgWidth,
                height: imgHeight,
                originX: "center",
                originY: "center",
                angle: 0,
            }),
            new fabric.Polygon(starPoints, {
                originX: "center",
                originY: "center",
                angle: 0,
            }),
        ];
    }
    function setControlVisibilityForAll() {
        canvas.getObjects().forEach((obj) => {
            obj.setControlsVisibility({
                mt: false,
                mb: false,
                bl: true,
                br: true,
                tl: true,
                tr: true,
                ml: true,
                mr: true,
            });

            obj.set("transparentCorners", false);
            obj.set("borderColor", "#2DA9FC");
            obj.set("cornerSize", 10);
            obj.set("cornerColor", "#fff");
            obj.set("cornerStyle", "circle");
            // Set text alignment if the object is a text-based object
            if (obj.type === "textbox" || obj.type === "text") {
                obj.set("textAlign", "center"); // Set text alignment to center
            }
            if (obj.type === "image") {
                console.log(obj);
                let currentShapeIndex = 0;
                obj.crossOrigin = "anonymous";

                let defaultShape = obj.clipPath.type;
                if (defaultShape === "polygon") {
                    defaultShape = "star";
                }
                const shapeIndexMap = {
                    rectangle: 0,
                    circle: 1,
                    triangle: 2,
                    star: 3,
                };
                currentShapeIndex = shapeIndexMap[defaultShape] || 0;
                shapes = createShapes(obj);
                obj.set({ clipPath: shapes[currentShapeIndex] });
                obj.on("mouseup", function (event) {
                    if (
                        event?.transform?.action === "drag" &&
                        event.transform.actionPerformed === undefined
                    ) {
                        currentShapeIndex =
                            (currentShapeIndex + 1) % shapes.length;
                        obj.set({ clipPath: shapes[currentShapeIndex] });
                        canvas.renderAll();
                    }
                });
            }

            obj.on("rotating", function () {
                // Get the bounding rectangle of the textboxbox
                var boundingRect = obj.getBoundingRect();
                var centerX = boundingRect.left + boundingRect.width / 2;
                var centerY = boundingRect.top + boundingRect.height / 2;
                var rotationAngle = obj.angle;
                // console.log('Rotated Position:', { centerX: centerX, centerY: centerY, rotation: rotationAngle });
            });
        });
        canvas.renderAll();
    }

    function addToUndoStack(canvas) {
        undoStack.push(canvas.toJSON());
        if (undoStack.length > 0) {
            $("#undoButton").find("svg path").attr("fill", "#0F172A");
        }
        redoStack = [];
    }

    function undo() {
        console.log("undoStack", undoStack.length);
        if (undoStack.length > 0) {
            // Ensure at least one previous state exists
            if (undoStack.length == 1) {
                $("#undoButton").find("svg path").attr("fill", "#CBD5E1");
            }
            redoStack.push(canvas.toJSON()); // Save current state to redo stack
            const lastState = undoStack.pop(); // Get the last state to undo
            canvas.loadFromJSON(lastState, function () {
                canvas.renderAll(); // Render the canvas after loading state
            });
            if (redoStack.length > 0) {
                $("#redoButton").find("svg path").attr("fill", "#0F172A");
            }
            setTimeout(function () {
                setControlVisibilityForAll();
            }, 1000);
        } else {
            $("#undoButton").find("svg path").attr("fill", "#CBD5E1");
        }
    }

    function redo() {
        if (redoStack.length > 0) {
            undoStack.push(canvas.toJSON()); // Save current state to undo stack
            const nextState = redoStack.pop(); // Get the next state to redo
            canvas.loadFromJSON(nextState, function () {
                canvas.renderAll(); // Render the canvas after loading state
            });
            if (redoStack.length == 1) {
                $("#redoButton").find("svg path").attr("fill", "#CBD5E1");
            }
            if (undoStack.length > 0) {
                $("#undoButton").find("svg path").attr("fill", "#0F172A");
            }
            $("#redoButton").find("svg path").attr("fill", "#0F172A");
            setTimeout(function () {
                setControlVisibilityForAll();
            }, 1000);
        } else {
            $("#redoButton").find("svg path").attr("fill", "#CBD5E1");
        }
    }

    $("#undoButton").click(function () {
        undo();
    });
    $("#redoButton").click(function () {
        redo();
    });
}

function getTextDataFromCanvas() {
    let element = document.querySelector(".image-edit-inner-img");
    if (element) {
        ({ width, height } = element.getBoundingClientRect()); // Update width & height if element exists
        console.log("Width:", width, "Height:", height);
    } else {
        console.log("Element not found! Using default values.");
    }

    console.log("getTextDataFromCanvas");
    var objects = canvas.getObjects();
    var textData = [];
    var shapeImageData = [];

    // **Current Dynamic Canvas Size**
    let canvasWidth = width;
    let canvasHeight = height;

    // **Calculate Reverse Scaling Factors**
    const scaleX = originalWidth / canvasWidth;
    const scaleY = originalHeight / canvasHeight;

    objects.forEach(function (obj) {
        if (obj.type === "textbox") {
            // alert(obj.text);
            var centerPoint = obj.getCenterPoint();
            console.log(obj.text, obj.charSpacing);
            // **Convert positions back to original 345×490**
            textData.push({
                text: obj.text,
                left: obj.left * scaleX, // Scale back X position
                top: obj.top * scaleY, // Scale back Y position
                fontSize: parseInt(obj.fontSize * scaleY), // Scale font size
                fill: obj.fill,
                width: parseInt(obj.width) * scaleX,
                centerX: centerPoint.x * scaleX, // Scale back center position
                centerY: centerPoint.y * scaleY,
                backgroundColor: obj.backgroundColor,
                fontFamily: obj.fontFamily,
                textAlign: obj.textAlign,
                lineHeight: parseFloat(obj.lineHeight) || 2,
                letterSpacing: parseFloat(obj.charSpacing) || 0,
                charSpacing: parseFloat(obj.charSpacing) || 0,
                fontWeight: obj.fontWeight,
                fontStyle: obj.fontStyle,
                underline: obj.underline,
                linethrough: obj.linethrough,
                date_formate: obj.date_formate, // Include date_formate if set
                rotation: parseFloat(obj.angle).toFixed(2),
            });
        }

        if (obj.type === "image") {
            var centerX = obj.left + obj.getScaledWidth() / 2;
            var centerY = obj.top + obj.getScaledHeight() / 2;

            shapeImageData = {
                shape: obj.clipPath ? obj.clipPath.type : "none",
                centerX: centerX * scaleX, // Scale back image center position
                centerY: centerY * scaleY,
                width: obj.getScaledWidth() * scaleX, // Scale back width
                height: obj.getScaledHeight() * scaleY, // Scale back height
            };
        }
    });

    // **Final JSON to Save**
    let dbJson = {
        textElements: textData,
        shapeImageData: shapeImageData,
    };
    console.log(dbJson);

    return dbJson;
}

$(".edit-design-sidebar").on("click", function () {
    if (imageId != null && imageId != "") {
        loadAgain();
    } else if (image != "") {
        loadAgain();
    }
});
function loadAgain() {
    //  $(".side-bar-list").removeClass("active");
    $(".edit-design-sidebar").addClass("active");

    // e.preventDefault();
    var eventID = $("#eventID").val();
    var isDraft = $("#isDraft").val();

    var json = dbJson;
    //console.log(json);
    var id = imageId;

    $(".design-sidebar-action").attr("data-id", id);
    if (
        eventData.textData != null &&
        eventData.temp_id != null &&
        eventData.temp_id == id
    ) {
        dbJson = eventData.textData;
        console.log({ dbJson });
    } else {
        console.log(json);
        dbJson = json;
        temp_id = id;
    }

    var current_event_id = current_event_id;
    $(".step_1").hide();
    $(".step_2").hide();
    $(".step_3").hide();
    $(".pick-card").removeClass("active");
    $(".pick-card").addClass("menu-success");
    $(".edit-design").removeClass("menu-success");
    $(".edit-design").addClass("active");
    $(".event_create_percent").text("25%");
    $(".current_step").text("1 of 4");
    $("#sidebar_select_design_category").css("display", "none");

    active_responsive_dropdown(
        "drop-down-event-design",
        "drop-down-edit-design"
    );
    $(".step_4").hide();
    $("#exampleModal").modal("hide");
    $(".edit_design_template").remove();

    $.ajax({
        url: base_url + "event/get_design_edit_page",
        method: "POST",
        dataType: "html",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            eventID,
            isDraft,
            id: id,
            image,
        },
        success: function (response) {
            console.log(response);
            if (isJSON(response)) {
                let jsonResponse = JSON.parse(response);

                if (
                    jsonResponse.status == 401 &&
                    jsonResponse.info == "logout"
                ) {
                    window.location.href = "/"; // Redirect to home page
                    return;
                }
            }
            console.log(dbJson);
            $("#edit-design-temp").html(response).show();
            bindData(current_event_id);
        },
        error: function (xhr, status, error) {},
    });
}
function isJSON(str) {
    try {
        JSON.parse(str);
        return true;
    } catch (e) {
        return false;
    }
}
