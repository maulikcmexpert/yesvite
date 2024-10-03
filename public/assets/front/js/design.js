var dbJson = null;
var temp_id = null;
var image = null;
var base_url = $("#base_url").text();
var canvas;

$(document).on("click", ".design-card", function () {
    var url = $(this).data("url");
    var template = $(this).data("template");
    var imageUrl = $(this).data("image");
    var shapeImageUrl = $(this).data('shape_image');
    var json = $(this).data("json");
    console.log(json);
    var id = $(this).data("id");
    $(".edit_design_tem").attr("data-image", imageUrl);
    if(eventData.textData != null && eventData.temp_id != null && eventData.temp_id == id){
        dbJson = {
            textElements: eventData.textData
        };
        console.log(dbJson);
        temp_id = eventData.temp_id; 
    }else{
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
        width: 345,
        height: 490,
    });

    // Append the new canvas to the modal-design-card
    $(".modal-design-card").html(newCanvas);

    // Show the modal
    $("#exampleModal").modal("show");

    canvas = new fabric.Canvas("imageEditor2", {
        width: 345,
        height: 490,
        position: "relative",
    });

    const defaultSettings = {
        fontSize: 20,
        letterSpacing: 0,
        lineHeight: 1.2,
    };

    fabric.Image.fromURL(image, function (img) {
        img.set({
            left: 0,
            top: 0,
            selectable: false,
            hasControls: false,
        });
        canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
    });

    const staticInfo = dbJson;

    staticInfo.textElements.forEach((element) => {
        console.log(element);
        let textElement = new fabric.Textbox(element.text, {
            left: element.left,
            top: element.top,
            width: element.width || 200,
            fontSize: element.fontSize,
            fill: element.fill,
            fontFamily: element.fontFamily,
            fontWeight: element.fontWeight,
            fontStyle: element.fontStyle,
            underline: element.underline,
            linethrough: element.linethrough,
            backgroundColor: element.backgroundColor,
            textAlign: element.textAlign,
            editable: false,
            selectable: false,
            hasControls: false,
            borderColor: "#2DA9FC",
            cornerColor: "#fff",
            cornerSize: 6,
            transparentCorners: false,
            isStatic: true,
        });
        switch (element.text) {
            case "event_name":
                if (eventData.event_name) {
                    textElement.set({ text: eventData.event_name });
                } else {
                    return; // Skip adding the element if event_name is empty
                }
                break;
            case "host_name":
                if (eventData.hosted_by) {
                    textElement.set({ text: eventData.hosted_by });
                } else {
                    return; // Skip adding the element if host_name is empty
                }
                break;
            case "Location":
                if (eventData.event_location) {
                    textElement.set({ text: eventData.event_location });
                } else {
                    return; // Skip adding the element if event_location_name is empty
                }
                break;
            case "start_time":
                if (eventData.start_time) {
                    textElement.set({ text: eventData.start_time });
                } else {
                    return; // Skip adding the element if start_time is empty
                }
                break;
            case "rsvp_end_time":
                if (eventData.rsvp_end_time) {
                    textElement.set({ text: eventData.rsvp_end_time });
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

                    textElement.set({ text: start_date });
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

                    textElement.set({ text: end_date });
                } else {
                    return; // Skip adding the element if end_date is empty
                }
                break;
        }
        const textWidth = textElement.calcTextWidth();
        textElement.set({ width: textWidth });
        canvas.add(textElement);
    });
    var shape = '';
    if (dbJson) {
        const staticInfo = dbJson;
        staticInfo?.shapeImageData?.forEach(element => {
            if (element.shape && element.centerX && element.centerY && element.height && element.width) {
                var imgObj = new Image();
                imgObj.src = shapeImageUrl; // Set your image URL
                
                imgObj.onload = function () {
                    // Create the image object in fabric.js
                    var img = new fabric.Image(imgObj);
                
                    img.set({
                        left: canvas.width / 2 - img.width / 2,
                        top: canvas.height / 2 - img.height / 2,
                        scaleX: 0.5, // Scaling the image
                        scaleY: 0.5
                    });
                
                    // Function to switch between different shapes
                    function changeShape(shape) {
                        switch (shape) {
                            case 'rectangle':
                                img.set({
                                    clipPath: new fabric.Rect({
                                        width: img.width * img.scaleX, // Match the scaled image width
                                        height: img.height * img.scaleY, // Match the scaled image height
                                        originX: 'center',
                                        originY: 'center'
                                    })
                                });
                                break;
                
                            case 'circle':
                                img.set({
                                    clipPath: new fabric.Circle({
                                        radius: (img.width * img.scaleX) / 2, // Scale to match image size
                                        originX: 'center',
                                        originY: 'center'
                                    })
                                });
                                break;
                
                            case 'star':
                                img.set({
                                    clipPath: new fabric.Path(
                                        'M 50,0 L 61,35 L 98,35 L 68,57 L 79,91 L 50,70 L 21,91 L 32,57 L 2,35 L 39,35 z', {
                                        scaleX: (img.width * img.scaleX) / 100, // Adjust scaling
                                        scaleY: (img.height * img.scaleY) / 100,
                                        originX: 'center',
                                        originY: 'center'
                                    })
                                });
                                break;
                
                            case 'rounded-border':
                                img.set({
                                    clipPath: new fabric.Rect({
                                        width: img.width * img.scaleX,
                                        height: img.height * img.scaleY,
                                        rx: 20, // Rounded corners
                                        ry: 20, // Rounded corners
                                        originX: 'center',
                                        originY: 'center'
                                    })
                                });
                                break;
                
                            case 'heart':
                                img.set({
                                    clipPath: new fabric.Path(
                                        'M 50,30 A 20,20 0 0 1 100,30 Q 100,60 50,90 Q 0,60 0,30 A 20,20 0 0 1 50,30 z', {
                                        scaleX: (img.width * img.scaleX) / 100,
                                        scaleY: (img.height * img.scaleY) / 100,
                                        originX: 'center',
                                        originY: 'center'
                                    })
                                });
                                break;
                
                            default:
                                img.set({
                                    clipPath: null // Remove any clipping path
                                });
                                break;
                        }
                
                        // Re-render the canvas after changing the shape
                        canvas.renderAll();
                    }
                
                    // Add event listener to change shape on click
                    img.on('mousedown', function() {
                        // Example: Toggle through shapes on each click (you can adjust this logic)
                        var shapes = ['rectangle', 'circle', 'star', 'rounded-border', 'heart'];
                        var currentShape = shapes[Math.floor(Math.random() * shapes.length)]; // Random shape for demo
                        changeShape(currentShape);
                    });
                
                    // Add the image to the canvas
                    canvas.add(img);
                    canvas.renderAll();
                };
                
            }
        });
    }
    
    

    // Load filed image (filedImagePath) as another image layer
    if (shapeImageUrl) {
        
        
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

$(document).on("click", ".design-sidebar-action", function () {
    let designId = $(this).attr("design-id");
    if (designId) {
        if (designId == "6") {
            var imgSrc1 = $(".photo-slider-1").attr("src");
            var imgSrc2 = $(".photo-slider-2").attr("src");
            var imgSrc3 = $(".photo-slider-3").attr("src");
            if (imgSrc1 != "" || imgSrc2 != "" || imgSrc3 != "") {
                $(".design-sidebar").addClass("d-none");
                $(".design-sidebar_7").removeClass("d-none");
                $("#sidebar").addClass("design-sidebar_7");
                $(".close-btn").attr("data-id", "design-sidebar_7");
            } else {
                $(".design-sidebar").addClass("d-none");
                $(".design-sidebar_" + designId).removeClass("d-none");
                $("#sidebar").addClass("design-sidebar_" + designId);
                $(".close-btn").attr("data-id", "design-sidebar_" + designId);
            }
        } else {
            $(".design-sidebar").addClass("d-none");
            $(".design-sidebar_" + designId).removeClass("d-none");
            $("#sidebar").addClass("design-sidebar_" + designId);
            $(".close-btn").attr("data-id", "design-sidebar_" + designId);
        }
    }
});

$(document).on("click", ".edit_design_tem", function (e) {
    e.preventDefault();
    // console.log(dbJson);
    // console.log(image);

    $("step_1").hide();
    $(".step_2").hide();
    $(".step_3").hide();
    $('.pick-card').removeClass('active');
    $('.pick-card').addClass('menu-success');
    $('.edit-design').removeClass('menu-success')
    $('.edit-design').addClass('active');
    $(".event_create_percent").text("50%");
    $(".current_step").text("2 of 4");
    $(".step_4").hide();
    $("#exampleModal").modal("hide");
    $(".edit_design_template").remove();

    $.ajax({
        url: base_url + "event/get_design_edit_page",
        method: "POST",
        dataType:'html',
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            console.log(response);
            $("#edit-design-temp").html(response).show();
            bindData();
        },
        error: function (xhr, status, error) {},
    });

});

function bindData() {



    function loadTextDataFromDatabase() {
        if (image) {
            // console.log(image);

            // Load background image
            fabric.Image.fromURL(image, function(img) {
                img.set({
                    left: 0,
                    top: 0,
                    selectable: false, // Non-draggable background image
                    hasControls: false, // Disable resizing controls
                });
                canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
            });
            var rotateIcon = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAHqSURBVHgBtZW7S8NQFMZPxFXbzUGULIJPdHBwUAgWQamDj1msg0MXHQWXCiIdHFpQKupgrVR0sovgZBEfQx0UdHBLH9bVpv0DjueksRbb5Da2/uBrm4Z7vvvde5IrgQBEdNLXDGmQJJOcxq0c6YYUkyQpCX+BisukAOkTxcRJip36bLBaY/HfBIzkQgMf1odKkv/T4JsnrJaI/lSwsQSqmaiiUTktj6l0Fm2gcO0mw8ADxfY0RcsXYMw1D3cPCbCBrzxFXDQl78o6Otp6sbNrBEddc/p1jamcTYaPIprSQH83OFpbwL+5BqHgFnR2tMP0nAfSmQ/R0Bnhhvu3d3UxqfQ7hvYjpXvRswscGJ4QJQkKTXh5uLgZ7tlFvL1PWJU44uWSzXKmM1lwOFr0pTFdxr5uYTOwSRLqhPfKAs3ShBNoWoFm+mha4fLqmpqiByxI6p9o8SCGDiL65lZrV24IbmUBQ82G2zGUPzhleJcX9DTcrvybW5n36vQ8pt+PhncsU9BZ8ywZSfhlpsLPgVQBF947PIGX1zd9Gd1T4+CedIGAJTIJl67IaAMbi1phyWmw+IpuFHLVbFg8clWsHw9YUacRH9kK1AoW90i1YRBHq2NXkMqD5keBSgqKZi+BDYyZspKkHLVnrpZxX+O67qGyL3x/AAAAAElFTkSuQmCC";
            var img = document.createElement('img');
            img.src = rotateIcon;
        
            // here's where your custom rotation control is defined
            // by changing the values you can customize the location, size, look, and behavior of the control
            fabric.Textbox.prototype.controls.mtr = new fabric.Control({
              x: 0,
              y: -0.5,
              offsetY: -40,
              cursorStyle: 'pointer',
              actionHandler: fabric.controlsUtils.rotationWithSnapping,
              actionName: 'rotate',
              render: renderIcon,
              cornerSize: 28,
              withConnection: true
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

            // Load static information (text elements)
            if (dbJson) {
                const staticInfo = dbJson;
                staticInfo.textElements.forEach((element) => {
                    const textMeasurement = new fabric.Text(element.text, {
                        fontSize: element.fontSize,
                        fontFamily: element.fontFamily,
                        fontWeight: element.fontWeight,
                        fontStyle: element.fontStyle,
                        underline: element.underline,
                        linethrough: element.linethrough,
                    });

                    const textWidth = textMeasurement.width;
                    // console.log(`Width of '${element.text}':`, textWidth);

                    // Calculate the width of the text

                    // console.log(element);
                    let textElement = new fabric.Textbox(element.text, {
                        // Use Textbox for editable text
                        left: element.left,
                        top: element.top,
                        width: element.width || textWidth, // Default width if not provided
                        fontSize: element.fontSize,
                        fill: element.fill,
                        fontFamily: element.fontFamily,
                        fontWeight: element.fontWeight,
                        fontStyle: element.fontStyle,
                        underline: element.underline,
                        linethrough: element.linethrough,
                        backgroundColor: element.backgroundColor,
                        textAlign: element.textAlign,
                        hasControls: true,
                        borderColor: "#2DA9FC",
                        cornerColor: "#fff",
                        cornerSize: 10,
                        cornerStyle: 'circle',
                        transparentCorners: false,
                        lockScalingFlip: true,
                        hasBorders: true,
                        centeredRotation:true,
                    });

                    // textElement.setControlsVisibility({
                    //     mt: false, // Hide middle top control
                    //     mb: false, // Hide middle bottom control
                    //     bl: false, // Hide bottom left control
                    //     br: false, // Hide bottom right control
                    //     tl: false, // Hide top left control
                    //     tr: false, // Hide top right control
                    //     ml: true,  // Show middle left control
                    //     mr: true   // Show middle right control
                    // });
                    
                    // Custom rendering of middle-left (ml) control as a rectangle
                    textElement.controls.ml = new fabric.Control({
                        x: -0.5, // Left side
                        y: 0,
                        offsetX: 0,
                        offsetY: 0,
                        cursorStyle: 'e-resize',
                        actionHandler: fabric.controlsUtils.scalingX,
                        render: function(ctx, left, top, styleOverride, fabricObject) {
                            ctx.fillStyle = 'white'; // Rectangle color
                            ctx.fillRect(left - 2, top - 7, 4, 8); // Draw a rectangle control for 'ml'
                        }
                    });

                    // Custom rendering of middle-right (mr) control as a rectangle
                    textElement.controls.mr = new fabric.Control({
                        x: 0.5, // Right side
                        y: 0,
                        offsetX: 0,
                        offsetY: 0,
                        cursorStyle: 'e-resize',
                        actionHandler: fabric.controlsUtils.scalingX,
                        render: function(ctx, left, top, styleOverride, fabricObject) {
                            ctx.fillStyle = 'white'; // Rectangle color
                            ctx.fillRect(left - 2, top - 7, 4, 8); // Draw a rectangle control for 'mr'
                        }
                    });
                    
                    var rotateIcon = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAHqSURBVHgBtZW7S8NQFMZPxFXbzUGULIJPdHBwUAgWQamDj1msg0MXHQWXCiIdHFpQKupgrVR0sovgZBEfQx0UdHBLH9bVpv0DjueksRbb5Da2/uBrm4Z7vvvde5IrgQBEdNLXDGmQJJOcxq0c6YYUkyQpCX+BisukAOkTxcRJip36bLBaY/HfBIzkQgMf1odKkv/T4JsnrJaI/lSwsQSqmaiiUTktj6l0Fm2gcO0mw8ADxfY0RcsXYMw1D3cPCbCBrzxFXDQl78o6Otp6sbNrBEddc/p1jamcTYaPIprSQH83OFpbwL+5BqHgFnR2tMP0nAfSmQ/R0Bnhhvu3d3UxqfQ7hvYjpXvRswscGJ4QJQkKTXh5uLgZ7tlFvL1PWJU44uWSzXKmM1lwOFr0pTFdxr5uYTOwSRLqhPfKAs3ShBNoWoFm+mha4fLqmpqiByxI6p9o8SCGDiL65lZrV24IbmUBQ82G2zGUPzhleJcX9DTcrvybW5n36vQ8pt+PhncsU9BZ8ywZSfhlpsLPgVQBF947PIGX1zd9Gd1T4+CedIGAJTIJl67IaAMbi1phyWmw+IpuFHLVbFg8clWsHw9YUacRH9kK1AoW90i1YRBHq2NXkMqD5keBSgqKZi+BDYyZspKkHLVnrpZxX+O67qGyL3x/AAAAAElFTkSuQmCC";
                    var img = document.createElement('img');
                    img.src = rotateIcon;
                
                    // here's where your custom rotation control is defined
                    // by changing the values you can customize the location, size, look, and behavior of the control
                    textElement.controls.mtr = new fabric.Control({
                      x: 0,
                      y: -0.5,
                      offsetY: -40,
                      cursorStyle: 'pointer',
                      actionHandler: fabric.controlsUtils.rotationWithSnapping,
                      actionName: 'rotate',
                      render: renderIcon,
                      cornerSize: 28,
                      withConnection: true,
                      hasBorders: false
                    });
                
                    // here's where the render action for the control is defined
                    function renderIcon(ctx, left, top, styleOverride, fabricObject) {
                      var size = this.cornerSize;
                      ctx.save();
                      ctx.translate(left, top);
                      ctx.rotate(fabric.util.degreesToRadians(fabricObject.angle));
                      ctx.drawImage(img, -size / 2, -size / 2, size, size);
                      ctx.fillStyle = 'transparent';
                      ctx.restore();
                    }

                    // textElement.controls.mt = new fabric.Control({
                    //     x: 0.5, // Right side
                    //     y: 0,
                    //     offsetX: 0,
                    //     offsetY: 0,
                    //     cursorStyle: 'e-resize',
                    //     actionHandler: fabric.controlsUtils.scalingX,
                    //     render: function(ctx, left, top, styleOverride, fabricObject) {
                    //         ctx.fillStyle = 'white'; // Rectangle color
                    //         ctx.fillRect(left - 2, top - 7, 4, 8); // Draw a rectangle control for 'mr'
                    //     }
                    // });

                    // textElement.controls.mb = new fabric.Control({
                    //     x: 0.5, // Right side
                    //     y: 0,
                    //     offsetX: 0,
                    //     offsetY: 0,
                    //     cursorStyle: 'e-resize',
                    //     actionHandler: fabric.controlsUtils.scalingX,
                    //     render: function(ctx, left, top, styleOverride, fabricObject) {
                    //         ctx.fillStyle = 'white'; // Rectangle color
                    //         ctx.fillRect(left - 2, top - 7, 4, 8); // Draw a rectangle control for 'mr'
                    //     }
                    // });


                    switch (element.text) {
                        case "event_name":
                            if (eventData.event_name) {
                                let textWidth = getWidth(
                                    element,
                                    eventData.event_name
                                );
                                textElement.set({
                                    text: eventData.event_name,
                                    width: textWidth,
                                });
                            } else {
                                return; // Skip adding the element if event_name is empty
                            }
                            break;
                        case "host_name":
                            if (eventData.hosted_by) {
                                let textWidth = getWidth(
                                    element,
                                    eventData.hosted_by
                                );
                                textElement.set({
                                    text: eventData.hosted_by,
                                    width: textWidth,
                                });
                            } else {
                                return; // Skip adding the element if host_name is empty
                            }
                            break;
                        case "Location":
                            if (eventData.event_location) {
                                let textWidth = getWidth(
                                    element,
                                    eventData.event_location
                                );
                                textElement.set({
                                    text: eventData.event_location,
                                    width: textWidth,
                                });
                            } else {
                                return; // Skip adding the element if event_location_name is empty
                            }
                            break;
                        case "start_time":
                            if (eventData.start_time) {
                                let textWidth = getWidth(
                                    element,
                                    eventData.start_time
                                );
                                textElement.set({
                                    text: eventData.start_time,
                                    width: textWidth,
                                });
                            } else {
                                return; // Skip adding the element if start_time is empty
                            }
                            break;
                        case "rsvp_end_time":
                            if (eventData.rsvp_end_time) {
                                let textWidth = getWidth(
                                    element,
                                    eventData.rsvp_end_time
                                );
                                textElement.set({
                                    text: eventData.rsvp_end_time,
                                    width: textWidth,
                                });
                            } else {
                                return; // Skip adding the element if rsvp_end_time is empty
                            }
                            break;
                        case "start_date":
                            if (eventData.event_date) {
                                var start_date = "";
                                if (eventData.event_date.includes(" To ")) {
                                    let [start, end] =
                                    eventData.event_date.split(" To ");
                                    start_date = start;
                                } else {
                                    start_date = eventData.event_date;
                                }
                                let textWidth = getWidth(element, start_date);
                                textElement.set({
                                    text: start_date,
                                    width: textWidth,
                                });
                            } else {
                                return; // Skip adding the element if start_date is empty
                            }
                            break;
                        case "end_date":
                            if (eventData.event_date) {
                                var end_date = "";
                                if (eventData.event_date.includes(" To ")) {
                                    let [start, end] =
                                    eventData.event_date.split(" To ");
                                    end_date = end;
                                } else {
                                    end_date = eventData.event_date;
                                }
                                let textWidth = getWidth(element, end_date);
                                textElement.set({
                                    text: end_date,
                                    width: textWidth,
                                });
                            } else {
                                return; // Skip adding the element if end_date is empty
                            }
                            break;
                    }
                   
                    addIconsToTextbox(textElement);
                    canvas.add(textElement);
                });
            } else {
                showStaticTextElements();
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
        console.log(`Width of '${text}':`, textWidth);
        return textWidth;
    }

    function addIconsToTextbox(textbox) {
        // Trash icon SVG
        // const trashIconSVG = `<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 50 50"><path d="M20,30 L30,30 L30,40 L20,40 Z M25,10 L20,10 L20,7 L30,7 L30,10 Z M17,10 L33,10 L33,40 L17,40 Z" fill="#FF0000"/></svg>`;
        const trashIconSVG = `<svg width="29" x="0px" y="0px" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g filter="url(#filter0_d_5633_67674)">
            <rect x="2.70312" y="2.37207" width="23.9674" height="23.9674" rx="11.9837" fill="white" shape-rendering="crispEdges"/>
            <path d="M19.1807 11.3502C17.5179 11.1855 15.8452 11.1006 14.1775 11.1006C13.1888 11.1006 12.2001 11.1505 11.2115 11.2504L10.1929 11.3502" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M12.939 10.8463L13.0488 10.1922C13.1287 9.7178 13.1886 9.36328 14.0325 9.36328H15.3407C16.1846 9.36328 16.2495 9.73777 16.3244 10.1971L16.4342 10.8463" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M18.1073 12.9277L17.7827 17.9559C17.7278 18.7398 17.6829 19.349 16.2898 19.349H13.0841C11.691 19.349 11.6461 18.7398 11.5912 17.9559L11.2666 12.9277" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M13.853 16.6035H15.5158" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M13.4385 14.6055H15.9351" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
            </g>
            </svg>
            `;

        fabric.loadSVGFromString(trashIconSVG, function(objects, options) {
            const trashIcon = fabric.util.groupSVGElements(objects, options);
            trashIcon.set({
                left: textbox.left + textbox.width * textbox.scaleX - 20,
                top: textbox.top - 20,
                selectable: false,
                evented: true,
                hasControls: false,
                visible: false, // Initially hidden
                className: "trash-icon",
            });
            textbox.trashIcon = trashIcon;

            // Handle trash icon click
            trashIcon.on("mousedown", function() {
                console.log("Trash icon clicked");
                deleteTextbox(textbox);
            });

            canvas.add(trashIcon);
        });

        // Copy icon SVG
        // const copyIconSVG = `<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 50 50"><path d="M5,5 L30,5 L30,30 L5,30 Z M35,5 L45,5 L45,35 L35,35 L35,5 Z" fill="#0000FF"/></svg>`;
        const copyIconSVG = `<svg width="29" x="0px" y="0px" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g filter="url(#filter0_d_5633_67676)">
            <rect x="2.64893" y="2.37207" width="23.9674" height="23.9674" rx="11.9837" fill="white" shape-rendering="crispEdges"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M17.6283 16.3538V10.3619C17.6283 9.81039 17.1812 9.36328 16.6297 9.36328H10.6378C10.0863 9.36328 9.63916 9.81039 9.63916 10.3619V16.3538C9.63916 16.9053 10.0863 17.3524 10.6378 17.3524H16.6297C17.1812 17.3524 17.6283 16.9053 17.6283 16.3538ZM10.6379 10.362H16.6298V16.3539H10.6379V10.362ZM18.6271 17.3525V11.3607C19.1786 11.3607 19.6257 11.8078 19.6257 12.3593V17.3525C19.6257 18.4556 18.7315 19.3498 17.6284 19.3498H12.6352C12.0837 19.3498 11.6366 18.9027 11.6366 18.3512H17.6284C18.1799 18.3512 18.6271 17.9041 18.6271 17.3525Z" fill="#0F172A"/>
            </g>
            </svg>
            `;
        fabric.loadSVGFromString(copyIconSVG, function(objects, options) {
            const copyIcon = fabric.util.groupSVGElements(objects, options);
            copyIcon.set({
                left: textbox.left - 25,
                top: textbox.top - 20,
                selectable: false,
                evented: true,
                hasControls: false,
                visible: false, // Initially hidden
                className: "copy-icon",
            });
            textbox.copyIcon = copyIcon;

            // Handle copy icon click
            copyIcon.on("mousedown", function() {
                console.log("Copy icon clicked");
                cloneTextbox(textbox);
            });

            canvas.add(copyIcon);
        });

        // Bind the updateIconPositions function to the moving and scaling events
        textbox.on("moving", function() {
            updateIconPositions(textbox);
        });
        textbox.on("scaling", function() {
            updateIconPositions(textbox);
        });
        textbox.on('rotating', function () {
            updateIconsPositions(textbox);
        });

        // Event listener to manage icon visibility when a textbox is clicked
        textbox.on("mousedown", function() {
            console.log(textbox);
            canvas.getObjects("textbox").forEach(function(tb) {
                if (tb.trashIcon) tb.trashIcon.set("visible", false); // Hide other icons
                if (tb.copyIcon) tb.copyIcon.set("visible", false);
            });
            if (textbox.trashIcon) textbox.trashIcon.set("visible", true); // Show current icons
            if (textbox.copyIcon) textbox.copyIcon.set("visible", true);
            canvas.renderAll(); // Re-render the canvas
        });

        // Initially hide all icons
        canvas.getObjects("textbox").forEach(function(tb) {
            if (tb.trashIcon) tb.trashIcon.set("visible", false);
            if (tb.copyIcon) tb.copyIcon.set("visible", false);
        });

        canvas.renderAll(); // Final render
    }

    canvas = new fabric.Canvas("imageEditor1", {
        width: 345, // Canvas width
        height: 490, // Canvas height
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
    const updateTextboxWidth = (textbox) => {
        const text = textbox.text;
        const fontSize = textbox.fontSize;
        const fontFamily = textbox.fontFamily;
        const charSpacing = textbox.charSpacing || 0;

        const ctx = canvas.getContext("2d");
        ctx.font = `${fontSize}px ${fontFamily}`;
        const measuredTextWidth = ctx.measureText(text).width;
        const width = measuredTextWidth + charSpacing * (text.length - 1);

        textbox.set("width", width);
        textbox.setCoords();
        canvas.renderAll();
    };

    // Set font size function
    const setFontSize = () => {
        const newValue = fontSizeRange.value;
        fontSizeInput.value = newValue;
        fontSizeTooltip.innerHTML = `<span>${newValue}px</span>`;

        const activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === "textbox") {
            activeObject.set("fontSize", newValue);
            updateTextboxWidth(activeObject);
        }
    };

    // Set letter spacing function
    const setLetterSpacing = () => {
        const newValue = letterSpacingRange.value;
        letterSpacingInput.value = newValue;
        letterSpacingTooltip.innerHTML = `<span>${newValue}</span>`;

        const activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === "textbox") {
            activeObject.set("charSpacing", newValue * 10); // Convert spacing to match Fabric.js scale
            updateTextboxWidth(activeObject);
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

    letterSpacingRange.addEventListener("input", setLetterSpacing);
    letterSpacingInput.addEventListener("input", () => {
        letterSpacingRange.value = letterSpacingInput.value;
        setTimeout(() => {
            setLetterSpacing();
        }, 500);
    });

    lineHeightRange.addEventListener("input", setLineHeight);
    lineHeightInput.addEventListener("input", () => {
        lineHeightRange.value = lineHeightInput.value;
        setTimeout(() => {
            setLineHeight();
        }, 500);
    });

    // Save button functionality
    document.querySelector(".save-btn").addEventListener("click", function() {
        const activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === "textbox") {
            savedSettings.fontSize = activeObject.fontSize;
            savedSettings.letterSpacing = activeObject.charSpacing / 10; // Convert back to user scale
            savedSettings.lineHeight = activeObject.lineHeight;
            alert("Settings have been saved!");
        }
    });
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
    document.querySelector(".reset-btn").addEventListener("click", function() {
        console.log("Reset button clicked!");
        const activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === "textbox") {
            resetTextboxProperties(activeObject); // Use the reset function
            canvas.renderAll(); // Re-render the canvas

            // Reset input fields and tooltips to default values
            fontSizeInput.value = defaultSettings.fontSize;
            fontSizeRange.value = defaultSettings.fontSize;
            fontSizeTooltip.innerHTML = `<span>${defaultSettings.fontSize}px</span>`;

            letterSpacingInput.value = defaultSettings.letterSpacing;
            letterSpacingRange.value = defaultSettings.letterSpacing;
            letterSpacingTooltip.innerHTML = `<span>${defaultSettings.letterSpacing}</span>`;

            lineHeightInput.value = defaultSettings.lineHeight;
            lineHeightRange.value = defaultSettings.lineHeight;
            lineHeightTooltip.innerHTML = `<span>${defaultSettings.lineHeight}</span>`;

            updateTextboxWidth(activeObject); // Update the textbox width to fit the default settings
            canvas.renderAll(); // Refresh the canvas to apply changes

            alert("Settings have been reset to default.");
        } else {
            alert("Please select a textbox to reset the settings.");
        }
    });

    // Initialize tooltips and values on page load
    setFontSize();
    setLetterSpacing();
    setLineHeight();

    // Initialize the color picker
    $("#color-picker").spectrum({
        type: "flat",
        color: "#000000", // Default font color
        showInput: true,
        allowEmpty: true, // Allows setting background to transparent
        showAlpha: true, // Allows transparency adjustment
        preferredFormat: "rgba", // Ensure it handles RGBA
        change: function(color) {
            if (color) {
                console.log("color");
                changeColor(color.toRgbString()); // Use RGB string for color changes
            } else {
                console.log("rgba");

                changeColor("rgba(0, 0, 0, 0)"); // Handle transparency by default
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

        console.log(activeObject);
        if (!activeObject) {
            console.log("No object selected");
            return;
        }

        if (activeObject.type == "textbox") {
            console.log(activeObject.type);
            console.log(activeObject.fill);
            if (selectedColorType == "font") {
                console.log("update fill");
                console.log(activeObject.fill);
                console.log(activeObject.backgroundColor);
                activeObject.set("fill", selectedColor); // Change font color
                console.log(activeObject.fill);
                console.log(activeObject.backgroundColor);
            } else if (selectedColorType == "background") {
                console.log("update background");
                activeObject.set("backgroundColor", selectedColor); // Change background color
            }
            canvas.renderAll(); // Re-render the canvas after color change
        }

        const activeObjec = canvas.getActiveObject();
        console.log("ater update");

        console.log(activeObjec);
    }

    // Update color picker based on the selected object's current font or background color
    function updateColorPicker() {
        const activeObject = canvas.getActiveObject();
        const selectedColorType = document.querySelector(
            'input[name="colorType"]:checked'
        ).value;

        if (activeObject && activeObject.type === "textbox") {
            if (selectedColorType === "font") {
                $("#color-picker").spectrum(
                    "set",
                    activeObject.fill || "#000000"
                ); // Set font color in picker
            } else if (selectedColorType === "background") {
                const bgColor =
                    activeObject.backgroundColor || "rgba(0, 0, 0, 0)"; // Default to transparent background
                $("#color-picker").spectrum("set", bgColor); // Set current background color in picker
            }

            console.log(selectedColorType);
            console.log(activeObject.type);
            console.log(activeObject.fill);
            console.log(activeObject.backgroundColor);

            const activeObjec = canvas.getActiveObject();

            console.log(activeObjec.fill);
            console.log(activeObjec.backgroundColor);
        }
    }

    // Update color picker when object selection changes
    canvas.on("selection:created", updateColorPicker);
    canvas.on("selection:updated", updateColorPicker);

    // Update the color picker when the color type (font/background) changes
    $(".colorTypeInp").click(function(e) {
        e.stopPropagation();
        console.log(123);
        const activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === "textbox") {
            console.log(activeObject.type);
            updateColorPicker(); // Update picker when the selected color type changes
        }
    });

    // Load background image and make it non-draggable
    document
        .getElementById("image")
        .addEventListener("change", function(event) {
            var file = event.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {};
                reader.readAsDataURL(file);
            }
        });

    // Call function to load data when the page loads
    loadTextDataFromDatabase();

    function hideStaticTextElements() {
        canvas.getObjects("textbox").forEach(function(textbox) {
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
        canvas.getObjects("textbox").forEach(function(textbox) {
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
        });

        // Approximate width based on text length
        text.set("width", text.get("text").length * 10);

        // Event listener for scaling
        text.on("scaling", function() {
            var updatedFontSize =
                (text.fontSize * (text.scaleX + text.scaleY)) / 2;
            text.set("fontSize", updatedFontSize);
            canvas.renderAll();
            findTextboxCenter(text); // Find center when scaling
        });

        // Event listener for moving
        text.on("moving", function() {
            findTextboxCenter(text); // Find center when moving
        });

        // Add the textbox to the canvas
        canvas.add(text);

        addIconsToTextbox(text);
        canvas.renderAll();

        // Initial center calculation
        findTextboxCenter(text);
    }

    function findTextboxCenter(textbox) {
        var centerX = textbox.left + textbox.width / 2;
        var centerY = textbox.top + textbox.height / 2;
        console.log(
            `Center of textbox '${textbox.text}' is at (${centerX}, ${centerY})`
        );
        return {
            x: centerX,
            y: centerY
        };
    }

    function updateIconsPositions(textbox) {
        const angle = fabric.util.degreesToRadians(textbox.angle);
        const boundingRect = textbox.getBoundingRect(true);
    
        // Calculate the new position for the trash icon
        const trashOffsetX = +75;  // Offset for the trash icon
        const trashOffsetY = - 30;  // Adjust icon's vertical position
        const trashRotatedX = textbox.left + trashOffsetX * Math.cos(angle) - trashOffsetY * Math.sin(angle);
        const trashRotatedY = textbox.top + trashOffsetX * Math.sin(angle) + trashOffsetY * Math.cos(angle);
    
        if (textbox.trashIcon) {
            textbox.trashIcon.left = trashRotatedX;
            textbox.trashIcon.top = trashRotatedY;
            textbox.trashIcon.angle = textbox.angle;  // Sync icon rotation with textbox
        }
    
        // Calculate the new position for the copy icon
        const copyOffsetX = -4;  // Offset for the copy icon on the left
        const copyOffsetY = -25;
        const copyRotatedX = textbox.left + copyOffsetX * Math.cos(angle) - copyOffsetY * Math.sin(angle);
        const copyRotatedY = textbox.top + copyOffsetX * Math.sin(angle) + copyOffsetY * Math.cos(angle);
    
        if (textbox.copyIcon) {
            textbox.copyIcon.left = copyRotatedX;
            textbox.copyIcon.top = copyRotatedY;
            textbox.copyIcon.angle = textbox.angle;  // Sync icon rotation with textbox
        }
    
        canvas.renderAll();  // Re-render canvas to update positions
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
        fabric.loadSVGFromString(trashIconSVG, function(objects, options) {
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
            trashIcon.on("mousedown", function() {
                console.log("Trash icon clicked! Deleting textbox.");
                deleteTextbox(textbox);
            });

            // Add trash icon to canvas
            canvas.add(trashIcon);
            canvas.bringToFront(trashIcon);
        });

        // Load copy icon from SVG string and position
        fabric.loadSVGFromString(copyIconSVG, function(objects, options) {
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
            copyIcon.on("mousedown", function() {
                console.log("Copy icon clicked!");
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

    // Function to delete a textbox
    function deleteTextbox(textbox) {
        canvas.remove(textbox);
        if (textbox.trashIcon) canvas.remove(textbox.trashIcon); // Remove the trash icon
        if (textbox.copyIcon) canvas.remove(textbox.copyIcon); // Remove the copy icon
        canvas.renderAll();
    }

    // Function to clone a textbox
    function cloneTextbox(originalTextbox) {
        const clonedTextbox = new fabric.Textbox(originalTextbox.text, {
            left: originalTextbox.left + 30, // Offset position
            top: originalTextbox.top + 30, // Offset position
            fontSize: originalTextbox.fontSize,
            fill: originalTextbox.fill,
            width: originalTextbox.width,
            height: originalTextbox.height,
            fontFamily: originalTextbox.fontFamily,
            originX: originalTextbox.originX,
            originY: originalTextbox.originY,
            hasControls: true,
            hasBorders: true,
            lockScalingFlip: true,
            fontWeight: originalTextbox.fontWeight,
            fontStyle: originalTextbox.fontStyle,
            underline: originalTextbox.underline,
            linethrough: originalTextbox.linethrough,
            backgroundColor: originalTextbox.backgroundColor,
            textAlign: originalTextbox.textAlign,
            borderColor: "#2DA9FC",
            cornerColor: "#fff",
            cornerSize: 6,
            transparentCorners: false,
        });

        canvas.add(clonedTextbox);

        // Add icons to the cloned textbox
        addIconsToTextbox(clonedTextbox);

        canvas.renderAll();
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
        var activeObject = canvas.getActiveObject();

        if (activeObject && activeObject.type === "textbox") {
            // Update text properties
            activeObject.set({
                fontSize: fontSize,
                fill: fontColor,
            });
            activeObject.setCoords(); // Update coordinates

            // Log the updated properties
            console.log("Updated Font Size: " + activeObject.fontSize);
            console.log("Updated Font Color: " + activeObject.fill);

            canvas.renderAll();
            addToUndoStack(); // Save state after updating properties
        }
    }

    // document.getElementById('fontSize').addEventListener('change', updateSelectedTextProperties);
    // document.getElementById('fontColor').addEventListener('input', updateSelectedTextProperties);

    canvas.on("mouse:down", function(options) {
        if (options.target && options.target.type === "textbox") {
            canvas.setActiveObject(options.target);
        } else {
            canvas.getObjects("textbox").forEach(function(tb) {
                if (tb.trashIcon) tb.trashIcon.set("visible", false);
                if (tb.copyIcon) tb.copyIcon.set("visible", false);
            });
        }
    });

    document
        .getElementById("addTextButton")
        .addEventListener("click", function() {
            addEditableTextbox(100, 100, "EditableText"); // You can set the initial position and default text
        });

    function addEditableTextbox(left, top, textContent) {
        var textbox = new fabric.Textbox(textContent, {
            left: left,
            top: top,
            width: 200,
            fontSize: 20,
            backgroundColor: "rgba(0, 0, 0, 0)", // Set background to transparent

            fill: "#000000",
            editable: true,
            selectable: true,
            hasControls: true,
            borderColor: "#2DA9FC",
            cornerColor: "#fff",
            cornerSize: 6,
            transparentCorners: false,
        });
        canvas.add(textbox);
        canvas.setActiveObject(textbox);
        addIconsToTextbox(textbox);
        canvas.renderAll();
    }

    document.getElementById('AbrilFatfaceButton').addEventListener('click', function() {
        console.log("fontname")
        loadAndUse("AbrilFatface-Regular");
    });
    document.getElementById('AdleryProButton').addEventListener('click', function() {
        console.log("fontname")

        loadAndUse("AdleryPro-Regular");


    });
    document.getElementById('AgencyFBButton').addEventListener('click', function() {
        console.log("fontname")

        loadAndUse("AgencyFB-Bold");


    });
    document.getElementById('AlexBrushButton').addEventListener('click', function() {
        console.log("fontname")

        loadAndUse("AlexBrush-Regular");


    });
    document.getElementById('AlluraButton').addEventListener('click', function() {
        console.log("fontname")

        loadAndUse("Allura-Regular");


    });
    // document.getElementById('BotanicaScript-RegularButton').addEventListener('click', function() {
    //     console.log("fontname")

    //     loadAndUse("BotanicaScript-Regular");


    // });
    document.getElementById('ArcherButton').addEventListener('click', function() {
        console.log("fontname")

        loadAndUse("ArcherBold");


    });
    document.getElementById('Archer-BookButton').addEventListener('click', function() {
        console.log("fontname")

        loadAndUse("Archer-Book");


    });
    document.getElementById('Archer-BookItalicButton').addEventListener('click', function() {
        console.log("fontname")

        loadAndUse("Archer-BookItalic");


    });
    document.getElementById('Archer-ExtraLightButton').addEventListener('click', function() {
        console.log("fontname")

        loadAndUse("Archer-ExtraLight");


    });
    document.getElementById('Archer-HairlineButton').addEventListener('click', function() {
        console.log("fontname")

        loadAndUse("Archer-Hairline");


    });
    document.getElementById('Bebas-RegularButton').addEventListener('click', function() {
        console.log("fontname")

        loadAndUse("Bebas-Regular");


    });
    document.getElementById('BookAntiquaButton').addEventListener('click', function() {
        console.log("fontname")

        loadAndUse("BookAntiqua");


    });
    document.getElementById('CandyCaneUnregisteredButton').addEventListener('click', function() {
        console.log("fontname")

        loadAndUse("CandyCaneUnregistered");


    });
    document.getElementById('CarbonBl-RegularButton').addEventListener('click', function() {
        console.log("fontname")

        loadAndUse("CarbonBl-Regular");


    });
    document.getElementById('CarmenSans-ExtraBoldButton').addEventListener('click', function() {
        console.log("fontname")

        loadAndUse("CarmenSans-ExtraBold");


    });
    document.getElementById('CarmenSans-RegularButton').addEventListener('click', function() {
        console.log("fontname")

        loadAndUse("CarmenSans-Regular");


    });
    document.getElementById('ChristmasCookiesButton').addEventListener('click', function() {
        console.log("fontname")

        loadAndUse("ChristmasCookies");


    });
    document.getElementById('Bungee-RegularButton').addEventListener('click', function() {
        console.log("fontname")

        loadAndUse("Bungee-Regular");


    });
    //     textElement.style.fontFamily = 'Allura'; // Change to Allura font
    // });
    function loadAndUse(font) {
        var myfont = new FontFaceObserver(font);
        myfont
            .load()
            .then(function() {
                // When font is loaded, use it.
                var activeObject = canvas.getActiveObject();
                console.log(activeObject.type);
                if (activeObject && activeObject.type === "textbox") {
                    activeObject.set({
                        fontFamily: font,
                    });
                    activeObject.initDimensions();
                    canvas.requestRenderAll();
                    console.log("applied font" + font);
                    console.log(canvas.getActiveObject());
                } else {
                    alert("No object selected");
                }
            })
            .catch(function(e) {
                console.log(e);
                alert("Font loading failed: " + font);
            });
    }

    function executeCommand(command, font = null) {
        var activeObject = canvas.getActiveObject();
        if (!activeObject) {
            // alert('No object selected');
            return;
        }
        if (activeObject && activeObject.type === "textbox") {
            const commands = {
                bold: () =>
                    activeObject.set(
                        "fontWeight",
                        activeObject.fontWeight === "bold" ? "" : "bold"
                    ),
                italic: () =>
                    activeObject.set(
                        "fontStyle",
                        activeObject.fontStyle === "italic" ? "" : "italic"
                    ),
                underline: () => {
                    activeObject.set("underline", !activeObject.underline);
                    // Update line height after toggling underline
                    const currentLineHeight = activeObject.lineHeight || 1.2; // Default line height
                    activeObject.set("lineHeight", currentLineHeight); // Reapply the line height
                },
                setLineHeight: (value) => {
                    activeObject.set("lineHeight", value);
                },
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
                fontName: () => {
                    if (font != null) {
                        loadAndUse(font);
                    }
                },

                justifyLeft: () => activeObject.set("textAlign", "left"),
                justifyCenter: () => activeObject.set("textAlign", "center"),
                justifyRight: () => activeObject.set("textAlign", "right"),
                justifyFull: () => activeObject.set("textAlign", "justify"),

                uppercase: () =>
                    activeObject.set("text", activeObject.text.toUpperCase()),
                lowercase: () =>
                    activeObject.set("text", activeObject.text.toLowerCase()),
                capitalize: () => {
                    const capitalizedText = activeObject.text.replace(
                        /\b\w/g,
                        (char) => char.toUpperCase()
                    );
                    activeObject.set("text", capitalizedText);
                },
            };
            if (commands[command]) {
                commands[command]();
                canvas.renderAll();
                addToUndoStack(); // Save state after executing the command
            }
        }
    }

    document.querySelectorAll("[data-command]").forEach(function(button) {
        button.addEventListener("click", function() {
            executeCommand(this.getAttribute("data-command"));
        });
    });

    // Undo and Redo actions (basic implementation)
    let undoStack = [];
    let redoStack = [];

    function addToUndoStack() {
        undoStack.push(canvas.toJSON());
        redoStack = []; // Clear redo stack on new action
    }

    function undo() {
        if (undoStack.length > 0) {
            redoStack.push(canvas.toJSON());
            canvas.loadFromJSON(undoStack.pop(), canvas.renderAll.bind(canvas));
        }
    }

    function redo() {
        if (redoStack.length > 0) {
            undoStack.push(canvas.toJSON());
            canvas.loadFromJSON(redoStack.pop(), canvas.renderAll.bind(canvas));
        }
    }

    document
        .querySelector('[data-command="undo"]')
        .addEventListener("click", undo);
    document
        .querySelector('[data-command="redo"]')
        .addEventListener("click", redo);


    $(document).ready(function() {
        $(".slider_photo").on("change", function(event) {
            var file = event.target.files[0]; // Get the first file (the selected image)
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(".photo-slider-1").attr("src", e.target.result).show();
                };
                reader.readAsDataURL(file);
                $(".design-sidebar").addClass("d-none");
                $(".design-sidebar_7").removeClass("d-none");
                $("#sidebar").addClass("design-sidebar_7");
                $(".close-btn").attr("data-id", "design-sidebar_7");
            }
        });

        $(".slider_photo_2").on("change", function(event) {
            var file = event.target.files[0];
            if (file) {
                $(".photo-slider-2").show();
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(".photo-slider-2").attr("src", e.target.result).show();
                };
                reader.readAsDataURL(file);
            }
        });
        $(".slider_photo_3").on("change", function(event) {
            var file = event.target.files[0];
            if (file) {
                $(".photo-slider-3").show();
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(".photo-slider-3").attr("src", e.target.result).show();
                };
                reader.readAsDataURL(file);
            }
        });
        // $(document).on("click", ".delete-slider-1", function () {
        //     $(".photo-slider-1").hide();
        // });
        // $(document).on("click", ".delete-slider-2", function () {
        //     $(".photo-slider-2").hide();
        // });
        // $(document).on("click", ".delete-slider-3", function () {
        //     $(".photo-slider-3").hide();
        // });

        $(document).on("click", ".save-slider-image", function() {
            var imageSources = [];
            // $(".slider_img").each(function () {
            //     imageSources.push($(this).attr("src"));
            // });

            $(".slider_img").each(function() {
                var src = $(this).attr("src");
                if (src !== "") {
                    imageSources.push({
                        src: $(this).attr("src"),
                        deleteId: $(this).data("delete")
                    });
                }
            });
            console.log(imageSources);
            $('#loader').css('display', 'block');
            $.ajax({
                url: base_url + "event/save_slider_img",
                method: "POST",
                data: {
                    imageSources: imageSources,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function(response) {
                    var savedImages = response.images;
                    eventData.slider_images = savedImages;
                    console.log(eventData);
                    $('#loader').css('display', 'none');
                    toastr.success('Slider Image saved Successfully');
                },
                error: function(xhr, status, error) {},
            });
        });

        $(document).on("click", ".delete_silder", function() {
            var delete_id = $(this).parent().find('.slider_img').data("delete");
            var src = $(this).parent().find('.slider_img').attr("src");
            if (src != "") {
                $('#loader').css('display', 'block');
                var $this = $(this);
                var check_slider_img = eventData.slider_images;
                var matchFound = false;
                $.each(check_slider_img, function(index, slider) {
                    if (slider.deleteId == delete_id) {
                        matchFound = true;
                        return false;
                    }
                });
                if (matchFound) {
                    $.ajax({
                        url: base_url + "event/delete_slider_img",
                        method: "POST",
                        data: {
                            delete_id: delete_id,
                            _token: $('meta[name="csrf-token"]').attr("content"),
                        },
                        success: function(response) {
                            $this.parent().find('.slider_img').attr('src', '');
                            $(".photo-slider-" + delete_id).hide();
                            toastr.success('Slider Image Deleted Successfully')
                            $('#loader').css('display', 'none');

                        },
                        error: function(xhr, status, error) {},
                    });
                } else {
                    $(this).parent().find('.slider_img').attr('src', '');
                    $(".photo-slider-" + delete_id).hide();
                    toastr.success('Slider Image Deleted Successfully')
                    $('#loader').css('display', 'none');

                }

            }

        });
    });
}
    

function getTextDataFromCanvas() {
    var objects = canvas.getObjects();

    var textData = [];

    objects.forEach(function (obj) {
        if (obj.type === "textbox") {
            var centerX = obj.left + obj.width / 2;
            var centerY = obj.top + obj.height / 2;
            textData.push({
                text: obj.text,
                left: obj.left,
                top: obj.top,
                fontSize: parseInt(obj.fontSize),
                fill: obj.fill,
                centerX: centerX, // Include centerX in the data
                centerY: centerY, // Include centerY in the data
                dx: obj.left, // Calculate dx
                dy: obj.top, // Calculate dy
                backgroundColor: obj.backgroundColor,
                fontFamily: obj.fontFamily,
                textAlign: obj.textAlign,
                fontWeight: obj.fontWeight,
                fontStyle: obj.fontStyle,
                underline: obj.underline,
                linethrough: obj.linethrough,
                date_formate: obj.date_formate, // Include date_formate if set
            });
        }
    });

    return textData;
}

