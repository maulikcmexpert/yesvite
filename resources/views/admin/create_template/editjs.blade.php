<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {


        // Initialize fabric canvas
        var canvas = new fabric.Canvas('imageEditor1', {
            width: 345, // Canvas width
            height: 490, // Canvas height
        });


        var newshape = "";
        const shapes = ['circle', 'rectangle', 'star', 'heart']; // Array of available shapes
        let currentShapeIndex = 0; // Track the current shape index
        let updatedOBJImage = {
                    shape: 'rectangle',
                    centerX: 0,
                    centerY: 0,
                    width: 100,
                    height: 100
                };
        function loadTextDataFromDatabase() {
            var id = $('#template_id').val();

            fetch(`/loadTextData/${id}`) // API endpoint to load data from your database
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        // console.log(data);
                        var canvasElement = document.getElementById('imageEditor1');
                        canvasElement.setAttribute('data-canvas-id', data.id);
                        // Load background image (imagePath)
                        if (data.imagePath) {
                            fabric.Image.fromURL(data.imagePath, function(img) {
                                img.set({
                                    left: 0,
                                    top: 0,
                                    selectable: false, // Non-draggable background image
                                    hasControls: false // Disable resizing controls
                                });
                                canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
                            });
                        }
                        if (data.static_information) {
                            const staticInfo = JSON.parse(data.static_information);
                            let element = staticInfo?.shapeImageData;
                            console.log(element)
                            if (element.shape != undefined && element.centerX != undefined && element.centerY != undefined && element.height != undefined && element.width != undefined) {
                                    console.log(element.shape);
                                    shape = element.shape;
                                    centerX = element.centerX;
                                    centerY = element.centerY;
                                    height = element.height;
                                    width = element.width;

                                    updatedOBJImage = {
                                        shape: shape,
                                        centerX: element.centerX,
                                        centerY: element.centerY,
                                        width: element.height,
                                        height: element.width
                                    };
                                    updateClipPath(data.filedImagePath, element);

                            }
                            
                        }                      
                       

                        // Load static information (text and shapes)
                        if (data.static_information) {
                            // hideStaticTextElements(); // Hide static text elements if static information is present
                            const staticInfo = JSON.parse(data.static_information);

                            // Render text elements or shapes on canvas
                            staticInfo.textElements.forEach(element => {
                                if (element.text) {
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
                                        editable: true,
                                        hasControls: true,
                                        // borderColor: 'blue',
                                        // cornerColor: 'red',
                                        borderColor: '#2DA9FC',
                                        // cornerColor: 'red',
                                        cornerColor: '#fff',
                                        cornerSize: 6,
                                        transparentCorners: false,
                                        isStatic: true
                                    });

                                    const textWidth = textElement.calcTextWidth();
                                    textElement.set({
                                        width: textWidth
                                    });

                                 
                                    //addIconsToTextbox(textElement);
                                    canvas.add(textElement);
                                    console.log(textElement);
                                    canvas.renderAll();
                                }



                            });
                        } else {
                            showStaticTextElements();
                            addDraggableText(150, 50, 'event_name', 'xyz'); // Position this outside the image area
                            addDraggableText(150, 100, 'host_name', 'abc');
                            addDraggableText(150, 150, 'start_time', '5:00PM');
                            addDraggableText(150, 200, 'rsvp_end_time', '6:00PM');
                            addDraggableText(150, 250, 'start_date', '2024-07-27');
                            addDraggableText(150, 300, 'end_date', '2024-07-27');
                            addDraggableText(150, 350, 'Location', 'fdf');

                        }

                        // Set custom attribute with the fetched ID
                        // var canvasElement = document.getElementById('imageEditor1');
                        // canvasElement.setAttribute('data-canvas-id', data.id);

                        canvas.renderAll(); // Ensure all elements are rendered
                    }
                })
                .catch(error => console.error('Error loading text data:', error));
        }

        var rotateIcon = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAHqSURBVHgBtZW7S8NQFMZPxFXbzUGULIJPdHBwUAgWQamDj1msg0MXHQWXCiIdHFpQKupgrVR0sovgZBEfQx0UdHBLH9bVpv0DjueksRbb5Da2/uBrm4Z7vvvde5IrgQBEdNLXDGmQJJOcxq0c6YYUkyQpCX+BisukAOkTxcRJip36bLBaY/HfBIzkQgMf1odKkv/T4JsnrJaI/lSwsQSqmaiiUTktj6l0Fm2gcO0mw8ADxfY0RcsXYMw1D3cPCbCBrzxFXDQl78o6Otp6sbNrBEddc/p1jamcTYaPIprSQH83OFpbwL+5BqHgFnR2tMP0nAfSmQ/R0Bnhhvu3d3UxqfQ7hvYjpXvRswscGJ4QJQkKTXh5uLgZ7tlFvL1PWJU44uWSzXKmM1lwOFr0pTFdxr5uYTOwSRLqhPfKAs3ShBNoWoFm+mha4fLqmpqiByxI6p9o8SCGDiL65lZrV24IbmUBQ82G2zGUPzhleJcX9DTcrvybW5n36vQ8pt+PhncsU9BZ8ywZSfhlpsLPgVQBF947PIGX1zd9Gd1T4+CedIGAJTIJl67IaAMbi1phyWmw+IpuFHLVbFg8clWsHw9YUacRH9kK1AoW90i1YRBHq2NXkMqD5keBSgqKZi+BDYyZspKkHLVnrpZxX+O67qGyL3x/AAAAAElFTkSuQmCC";
            var img = document.createElement('img');
            img.src = rotateIcon;
        
            // here's where your custom rotation control is defined
            // by changing the values you can customize the location, size, look, and behavior of the control
            fabric.Textbox.prototype.controls.mtr = new fabric.Control({
              x: 0,
              y: -0.5,
              offsetY: -30,
              cursorStyle: 'pointer',
              actionHandler: fabric.controlsUtils.rotationWithSnapping,
              actionName: 'rotate',
              render: renderIcon,
              cornerSize: 28,
              withConnection: true
            });


            fabric.Textbox.prototype.controls.mtr = new fabric.Control({
              x: 0,
              y: -0.8,
              offsetY: -30,
              cursorStyle: 'pointer',
              actionHandler: fabric.controlsUtils.rotationWithSnapping,
              actionName: 'rotate',
              render: trashIconSVG,
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
        
        let currentImage = null;
let isImageDragging = false; // Track if the image is being dragged
let isimageoncanvas = false;
let oldImage = null;
const canvasElement = new fabric.Canvas('imageEditor', {
                                width: 500, // Canvas width
                                height: 500, // Canvas height
                                cornerSize: 6,
                            });
function updateClipPath(imageUrl, element) {
    console.log(imageUrl)
    const imageWrapper = document.getElementById('imageWrapper');
   
    const imgElement = document.getElementById('user_image');
    imgElement.src = imageUrl;

    // If a current image exists on canvas, remove it
    if (currentImage) {
        canvasElement.remove(currentImage);
    }

    // Handle previous image and trash icon
    if (oldImage != null) {
        canvasElement.remove(oldImage.trashIcon);
        oldImage.trashIcon = null;
        canvasElement.renderAll();
    }

    imageWrapper.style.display = 'block';
    // imageWrapper.style.left = element.left;
    // imageWrapper.style.top = element.top;

    let canvasEL = document.getElementById('imageEditor1')
    const canvasRect = canvasEL.getBoundingClientRect();

    console.log(canvasRect.left)
    console.log(canvasRect.top)
    console.log(element.centerX)
    console.log(element.centerY)
    console.log(element.height)
    console.log(element.height)
    
    // let left = element.centerX !== undefined ? `${element.centerX  + canvasRect.left}px` : '50%';
    // let top = element.centerY !== undefined ? `${element.centerY + canvasRect.top}px` : '50%';

    let left = element.centerX!=undefined?`${element.centerX - (element.width / 2) + canvasRect.left}px`:'50%';
    let top = element.centerX!=undefined?`${element.centerY - (element.height / 2) + canvasRect.top}px`:'50%';


    console.log({left})
    console.log({top})

    // Set the calculated position to imageWrapper
    imageWrapper.style.left = left;
    imageWrapper.style.top = top;
    imgElement.style.width = element.width + 'px';
    imgElement.style.height = element.height + 'px';
    
    imgElement.onload = function () {
        // Get image dimensions and scale it
        const imgInstance = new fabric.Image(imgElement, {           
            selectable: true,
            hasControls: true,
            hasBorders: true,
            
            borderColor: "#2DA9FC",
            cornerColor: "#fff",
            transparentCorners: false,
            lockUniScaling: true,
            scaleX: 600 / imgElement.width,
            scaleY: 600 / imgElement.height,
            cornerSize: 10,
            cornerStyle: 'circle',
        });
        shape = element.shape;
        canvasElement.add(imgInstance);
        // addIconsToImage(imgInstance);
        drawCanvas();
        
        // Refresh canvas
        canvasElement.renderAll();

        // Update the image with the shape based on the provided element data
        if (element.shape) {
            applyClipPath(imgInstance, element);
        }

        // Image mouseup event to change shape or update position
        imgInstance.on('mouseup', function (options) {
            if (options.target) {
                // Change shape logic
                currentShapeIndex = (currentShapeIndex + 1) % shapes.length;
                const nextShape = shapes[currentShapeIndex];
                element.shape = nextShape;

                updateClipPath(data, element); // Update the image with the new shape
            }
        });

        // Update canvas on movement or scaling
        imgInstance.on('moving', function () {
            isImageDragging = true;
            element.centerX = imgInstance.left;
            element.centerY = imgInstance.top;

            updatedOBJImage = {               
                centerX: imgInstance.left,
                centerY: imgInstance.top,                
            };
        });

        imgInstance.on('scaling', function () {
            element.width = imgInstance.width * imgInstance.scaleX;
            element.height = imgInstance.height * imgInstance.scaleY;

            updatedOBJImage = {               
                width:  imgInstance.width * imgInstance.scaleX,
                height: imgInstance.height * imgInstance.scaleY
            };
        });

        currentImage = imgInstance; // Track current image on canvas
        oldImage = imgInstance;
        $('.photo-slider-wrp').hide()
    };

    imgElement.onerror = function (e) {
        console.error("Failed to load image.",e);
    };
}

// Helper function to apply clip path based on shape
function applyClipPath(image, element) {
    const containerWidth = 150;
    const containerHeight = 200;

    let clipPath;
    switch (element.shape) {
        case 'circle':
            clipPath = new fabric.Circle({
                radius: Math.min(containerWidth, containerHeight) / 2,
                originX: 'center',
                originY: 'center'
            });
            break;
        case 'star':
            clipPath = new fabric.Path(
                'M 50,0 L 61,35 L 98,35 L 68,57 L 79,91 L 50,70 L 21,91 L 32,57 L 2,35 L 39,35 z',
                {
                    scaleX: (image.width * image.scaleX) / 100,
                    scaleY: (image.height * image.scaleY) / 100,
                    originX: 'center',
                    originY: 'center'
                }
            );
            break;
        case 'heart':
            const heartPath = [
                'M', 0, 0,
                'C', -containerWidth / 3, -containerHeight / 3, -containerWidth / 3, containerHeight / 6, 0, containerHeight / 5,
                'C', containerWidth / 3, containerHeight / 6, containerWidth / 3, -containerHeight / 3, 0, 0
            ].join(' ');
            clipPath = new fabric.Path(heartPath, {
                originX: 'center',
                originY: 'center'
            });
            break;
        default:
            break;
    }

    // Set clipping path for the image
    image.set({
        clipPath: clipPath
    });

    canvasElement.renderAll();
}

// function updateClipPath(imageUrl, element) {  
 
//     if (currentImage) {
//         canvas.remove(currentImage);
//     }
  
//     // Define the fixed container dimensions
//     const containerWidth = 150;
//     const containerHeight = 200;
//     if(oldImage!=null){
      
//         canvas.remove(oldImage.trashIcon);
//         oldImage.trashIcon = null; // Clear reference
//         canvas.renderAll();
         
//     }
//     // Load the image from the provided URL
//     fabric.Image.fromURL(imageUrl, function (image) {
   
//         // Get the original dimensions of the image
//         const originalWidth = image.width;
//         const originalHeight = image.height;

//         // Calculate the aspect ratio of the image
//         const aspectRatio = originalWidth / originalHeight;

//         // Scale the image to fit the container
//         if (aspectRatio > containerWidth / containerHeight) {
//             image.scaleToWidth(containerWidth);
//         } else {
//             image.scaleToHeight(containerHeight);
//         }

//         // Define the clipping path based on the shape
//         let clipPath;
//         switch (element.shape) {
//             case 'circle':
//                 clipPath = new fabric.Circle({
//                     radius: Math.min(containerWidth, containerHeight),
//                     originX: 'center',
//                     originY: 'center'
//                 });
//                 break;
//             case 'star':
//                 clipPath = new fabric.Path(
//                     'M 50,0 L 61,35 L 98,35 L 68,57 L 79,91 L 50,70 L 21,91 L 32,57 L 2,35 L 39,35 z',
//                     {
//                         scaleX: (image.width * image.scaleX) / 100,
//                         scaleY: (image.height * image.scaleY) / 100,
//                         originX: 'center',
//                         originY: 'center'
//                     }
//                 );
//                 break;
//             case 'heart':
//                 const heartPath = [
//                     'M', 0, 0,
//                     'C', -containerWidth / 3, -containerHeight / 3, -containerWidth / 3, containerHeight / 6, 0, containerHeight / 5,
//                     'C', containerWidth / 3, containerHeight / 6, containerWidth / 3, -containerHeight / 3, 0, 0
//                 ].join(' ');
//                 clipPath = new fabric.Path(heartPath, {
//                     originX: 'center',
//                     originY: 'center'
//                 });
//                 break;
          
//             default:
              
//                 break;
//         }

//         // Apply the clipping path and set initial position
//         image.set({
//             clipPath: clipPath,
//             left: element.centerX,
//             top: element.centerY
//         });
      

//         // Track image position and update global object on moving
//         image.on('moving', function () {
//             isImageDragging = true; // Set dragging flag to true

//             updatedOBJImage = {
//                 shape: element.shape,
//                 centerX: image.left,
//                 centerY: image.top,
//                 width: image.width,
//                 height: image.height
//             };

//             console.log("Updated image position:", updatedOBJImage);
//         });

//         // Handle mouseup event for both dragging and shape change
//         image.on('mouseup', function () {
//             if (isImageDragging) {
//                 // If dragged, just update the position
//                 element.centerX = image.left;
//                 element.centerY = image.top;
//                 isImageDragging = false; // Reset dragging flag
//             } else {
//                 // If clicked without dragging, change shape
//                 currentShapeIndex = (currentShapeIndex + 1) % shapes.length;
//                 const nextShape = shapes[currentShapeIndex];

//                 updatedOBJImage = {
//                     shape: nextShape,
//                     centerX: image.left,
//                     centerY: image.top,
//                     width: image.width,
//                     height: image.height
//                 };

//                 // Recursively update the image with the new shape
//                 updateClipPath(imageUrl, updatedOBJImage);
//             }
//         });

//          // Handle scaling/resizing (optional)
//          image.on('scaling', function () {
//             const scaleX = image.scaleX;
//             const scaleY = image.scaleY;

//             updatedOBJImage.width = image.width * scaleX;
//             updatedOBJImage.height = image.height * scaleY;

//             // Optionally update clip path scaling too
//             if (clipPath) {
//                 clipPath.set({
//                     scaleX: scaleX,
//                     scaleY: scaleY
//                 });
//             }

//             console.log("Updated image size:", updatedOBJImage);
//         });
//         console.log(updatedOBJImage)
//         isimageoncanvas = true;
//         $('.photo-slider-wrp').hide()
//         // Add the image to the canvas
//         canvas.add(image);
//         currentImage = image; // Store the current image

//         // Refresh the canvas to apply changes
//         canvas.renderAll();
//         oldImage = image
//         addIconsToImage(image)

//         canvas.getObjects().forEach(obj => {
//             if (obj.type === 'group') {
//                 canvas.remove(obj) // Your existing function to add icons
//             }
//         });

//     });
// }

$(".removeShapImage").click(function(){
    $("#imageWrapper").hide();
    $("#user_image").attr("src","");
    $('.photo-slider-wrp').show()

})
       
        let updateTimeout; // Variable to store the timeout reference


        function addIconsToImage(textbox) {
            console.log(textbox);
            // Remove existing trash icon if it exists
            if (textbox.trashIcon) {
                canvas.remove(textbox.trashIcon);
                textbox.trashIcon = null; // Clear reference
                canvas.renderAll();
            }

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

            fabric.loadSVGFromString(trashIconSVG, function(objects, options) {
                const trashIcon = fabric.util.groupSVGElements(objects, options);
                trashIcon.set({
                    left: textbox.left + textbox.width * textbox.scaleX - 20,
                    top: textbox.top - 30,
                    selectable: true,
                    evented: true,
                    hasControls: false,
                    hoverCursor: 'pointer'
                });

                // Attach delete functionality to the trash icon only once
                if (!trashIcon.deleteHandlerAttached) {
                    trashIcon.on('mousedown', function() {
                        console.log('Trash icon clicked! Deleting textbox.');
                        isimageoncanvas = false;
                        $('.photo-slider-wrp').show()

                        deleteTextbox(textbox); // Function to delete the textbox
                    });
                    trashIcon.deleteHandlerAttached = true; // Mark that the handler is attached
                }

                canvas.add(trashIcon);
                canvas.bringToFront(trashIcon);
                textbox.trashIcon = trashIcon; // Store the reference of the trash icon



                // Update icon position on moving and scaling
                textbox.on('moving', function() {
                    if (textbox.trashIcon) {
                        canvas.remove(textbox.trashIcon);
                        textbox.trashIcon = null; // Clear reference
                        canvas.renderAll();
                    }
                    clearTimeout(updateTimeout);
                    updateTimeout = setTimeout(() => {
                        addIconsToImage(textbox)
                    }, 500);
                });
                textbox.on('scaling', function() {
                    if (textbox.trashIcon) {
                        canvas.remove(textbox.trashIcon);
                        textbox.trashIcon = null; // Clear reference
                        canvas.renderAll();
                    }
                    clearTimeout(updateTimeout);
                    updateTimeout = setTimeout(() => {
                        addIconsToImage(textbox)
                    }, 500);
                });
                canvas.renderAll();
            });
        }



        // Call function to load data when the page loads
        loadTextDataFromDatabase();
        const defaultSettings = {
            fontSize: 20,
            letterSpacing: 0,
            lineHeight: 1.2
        };

        // Save settings object (for the save functionality)
        let savedSettings = {
            fontSize: defaultSettings.fontSize,
            letterSpacing: defaultSettings.letterSpacing,
            lineHeight: defaultSettings.lineHeight
        };

        // Function to update textbox width dynamically
        const updateTextboxWidth = (textbox) => {
            const text = textbox.text || ""; // Get current text
            const fontSize = textbox.fontSize || defaultSettings.fontSize; // Get current font size
            const fontFamily = textbox.fontFamily || 'Arial'; // Default font family
            const charSpacing = textbox.charSpacing || 0;

            const ctx = canvas.getContext('2d');
            ctx.font = `${fontSize}px ${fontFamily}`;

            const measuredTextWidth = ctx.measureText(text).width;
            const calculatedWidth = measuredTextWidth + (charSpacing / 1000 * fontSize * (text.length - 1));

            // Define a maximum width to avoid large textboxes
            const maxWidth = 400; // Adjust this value based on your layout
            const width = Math.min(calculatedWidth, maxWidth); // Cap the width
            console.log(width)

                // Handle text wrapping for large texts
                textbox.set('width', width);
                textbox.set('textAlign', 'left'); // Ensure text wraps within the textbox
                textbox.setCoords();
                
                // Set to 'clipTo' or 'overflow' if necessary based on design
                textbox.set('noScaleCache', false); // Redraw the text after resizing
                canvas.renderAll();
            };


            const setLetterSpacing = () => {
                const newValue = parseFloat(letterSpacingRange.value); // Ensure it's a number
                letterSpacingInput.value = newValue;
                letterSpacingTooltip.innerHTML = `<span>${newValue}</span>`;

                const activeObject = canvas.getActiveObject();
                if (activeObject && activeObject.type === 'textbox') {
                    activeObject.set('charSpacing', newValue); // Update letter spacing

                    // Now call updateTextboxWidth to handle width adjustments
                    updateTextboxWidth(activeObject);
                }
            };

            const setFontSize = () => {
                const newValue = fontSizeRange.value;
                fontSizeInput.value = newValue;
                fontSizeTooltip.innerHTML = `<span>${newValue}px</span>`;

                const activeObject = canvas.getActiveObject();
                if (activeObject && activeObject.type === 'textbox') {
                    activeObject.set('fontSize', newValue); // Update font size

                    // Call updateTextboxWidth to adjust textbox width accordingly
                    updateTextboxWidth(activeObject);
                }
            };
        // Function to update line height
        const setLineHeight = () => {
            const newValue = parseFloat(lineHeightRange.value);
            lineHeightInput.value = newValue;
            lineHeightTooltip.innerHTML = `<span>${newValue}</span>`;

            const activeObject = canvas.getActiveObject();
            if (activeObject && activeObject.type === 'textbox') {
                activeObject.set('lineHeight', newValue);
                canvas.renderAll();
            }
        };

        // Event listeners for sliders and input fields
        fontSizeRange.addEventListener('input', setFontSize);
        fontSizeInput.addEventListener('input', () => {
            fontSizeRange.value = fontSizeInput.value;
            if (fontSizeInput.value != '' && fontSizeInput.value != '0.') {
                setTimeout(() => {
                    setFontSize();
                }, 500);
            }
        });

        letterSpacingRange.addEventListener('input', setLetterSpacing);
        letterSpacingInput.addEventListener('input', () => {
            letterSpacingRange.value = letterSpacingInput.value;
            if (letterSpacingInput.value != '' && letterSpacingInput.value != '0.') {
                setTimeout(() => {
                    setLetterSpacing();
                }, 500);
            }
        });

        lineHeightRange.addEventListener('input', setLineHeight);
        lineHeightInput.addEventListener('input', () => {
            lineHeightRange.value = lineHeightInput.value;
            if (lineHeightInput.value != '' && lineHeightInput.value != '0.') {
                setTimeout(() => {
                    setLineHeight();
                }, 500);
            }
        });

        // Save button functionality
        document.querySelector('.save-btn').addEventListener('click', function() {
            const activeObject = canvas.getActiveObject();
            if (activeObject && activeObject.type === 'textbox') {
                savedSettings.fontSize = activeObject.fontSize;
                savedSettings.letterSpacing = activeObject.charSpacing / 10; // Convert back to user scale
                savedSettings.lineHeight = activeObject.lineHeight;
                alert('Settings have been saved!');
            }
        });
        const resetTextboxProperties = (object) => {
            object.set({
                fontSize: defaultSettings.fontSize,
                charSpacing: defaultSettings.letterSpacing * 10, // Adjusted for Fabric.js
                lineHeight: defaultSettings.lineHeight,
                fontFamily: 'Arial',
                // textAlign: 'left',
                fill: '#000000', // Optional: Reset text color
            });

            updateTextboxWidth(object);
        };
        // Reset button functionality
        document.querySelector('.reset-btn').addEventListener('click', function() {
            console.log("Reset button clicked!");
            const activeObject = canvas.getActiveObject();
            if (activeObject && activeObject.type === 'textbox') {
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

                alert('Settings have been reset to default.');
            } else {
                alert('Please select a textbox to reset the settings.');
            }
        });

        // Initialize tooltips and values on page load
        setFontSize();
        setLetterSpacing();
        setLineHeight();

        // // Initialize the color picker
       
        // Initialize the color picker
        $('#color-picker').spectrum({
            type: "flat",
            color: "#000000", // Default font color
            showInput: true,
            allowEmpty: true, // Allows setting background to transparent
            showAlpha: true, // Allows transparency adjustment
            preferredFormat: "hex",
            change: function(color) {
                if (color) {
                    console.log("color")
                    changeColor(color.toHexString()); // Use RGB string for color changes
                } else {
                    console.log("rgba")
                    changeColor('#000000'); // Handle transparency by default
                }
            }
        });

        // Function to change font or background color
        function changeColor(selectedColor) {
            const selectedColorType = document.querySelector('input[name="colorType"]:checked').value;
            const activeObject = canvas.getActiveObject();
            console.log("before update")

            console.log(activeObject)
            if (!activeObject) {
                console.log('No object selected');
                return;
            }
            
            if (activeObject.type == 'textbox') {
                console.log("added to undo")
                addToUndoStack(canvas)
                console.log(activeObject.type);
                console.log(activeObject.fill);
                if (selectedColorType == 'font') {
                    console.log("update fill")
                    console.log(activeObject.fill);
                    console.log(activeObject.backgroundColor);
                    activeObject.set('fill', selectedColor); // Change font color
                    console.log(activeObject.fill);
                    console.log(activeObject.backgroundColor);
                } else if (selectedColorType == 'background') {
                    console.log("update background")
                    activeObject.set('backgroundColor', selectedColor); // Change background color
                }
                canvas.renderAll(); // Re-render the canvas after color change
            }

            const activeObjec = canvas.getActiveObject();
            console.log("ater update")

            console.log(activeObjec)
        }
      

        // Update color picker based on the selected object's current font or background color
        function updateColorPicker() {
           
            const activeObject = canvas.getActiveObject();
            const selectedColorType = document.querySelector('input[name="colorType"]:checked').value;

            if (activeObject && activeObject.type === 'textbox') {
                

                if (selectedColorType === 'font') {

                    $('#color-picker').spectrum('set', activeObject.fill || '#000000'); // Set font color in picker
                } else if (selectedColorType === 'background') {
                    const bgColor = activeObject.backgroundColor || 'rgba(0, 0, 0, 0)'; // Default to transparent background
                    $('#color-picker').spectrum('set', bgColor); // Set current background color in picker
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
        // canvas.on('selection:created', updateColorPicker);
        // canvas.on('selection:updated', updateColorPicker);

        // Update the color picker when the color type (font/background) changes
        $('.colorTypeInp').click(function(e) {
            e.stopPropagation()
            console.log(123)
            const activeObject = canvas.getActiveObject();
            if (activeObject && activeObject.type === 'textbox') {
                console.log(activeObject.type);
                updateColorPicker(); // Update picker when the selected color type changes
            }
        });


        // Load background image and make it non-draggable
        document.getElementById('image').addEventListener('change', function(event) {
            var file = event.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    fabric.Image.fromURL(e.target.result, function(img) {
                        img.set({
                            left: 0,
                            top: 0,
                            selectable: false, // Make the image non-draggable
                            hasControls: false // Disable resizing controls for the image
                        });
                        canvas.setBackgroundImage(img);
                        canvas.renderAll();
                        console.log(`Image width: ${img.width}, Image height: ${img.height}`);
                    });
                };
                reader.readAsDataURL(file);
            }
        });





        function hideStaticTextElements() {
            canvas.getObjects('textbox').forEach(function(obj) {

                if (obj.isStatic) {
                    canvas.remove(obj);
                }
            });



            canvas.renderAll();
        }



        function showStaticTextElements() {
            canvas.getObjects('textbox').forEach(function(textbox) {
                if (textbox.isStatic) {
                    textbox.set('visible', true);
                    if (textbox.copyIcon) {
                        textbox.copyIcon.set('visible', true);
                    }
                    if (textbox.trashIcon) {
                        textbox.trashIcon.set('visible', true);
                    }
                }
            });
            canvas.renderAll();
        }

        function addDraggableText(left, top, textContent) {
            // alert()
            var text = new fabric.Textbox(textContent, {
                left: left,
                top: top,
                fontSize: 20,
                backgroundColor: 'rgba(0, 0, 0, 0)', // Set background to transparent
                fill: '#000000', // Default text color (black)
                editable: false,
                selectable: true,
                isStatic: false,
                visible: true,
                hasControls: true,
                textAlign: 'center',
                borderColor: '#2DA9FC',
                // cornerColor: 'red',
                cornerColor: '#fff',
                cornerSize: 6,
                transparentCorners: false,
                rotatingPointOffset:30,
                padding: 5,
            })

            text.set('width', text.get('text').length * 10);

            // text.on('scaling', function() {
            //     var updatedFontSize = text.fontSize * (text.scaleX + text.scaleY) / 2;
            //     text.set('fontSize', updatedFontSize);
            //     canvas.renderAll();
            //     findTextboxCenter(text);
            // });

            // text.on('moving', function() {
            //     findTextboxCenter(text);
            // });
            canvas.add(text);
            updateIconPositions(text)
            addIconsToTextbox(text);
            canvas.renderAll();
            findTextboxCenter(text);
        }

        function findTextboxCenter(textbox) {
            var centerX = textbox.left + (textbox.width / 2);
            var centerY = textbox.top + (textbox.height / 2);
            console.log(`Center of textbox '${textbox.text}' is at (${centerX}, ${centerY})`);
            return {
                x: centerX,
                y: centerY
            };
        }

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
    // Helper function to remove existing icons
    function removeIcons(textbox, iconTypes = ['trashIcon', 'copyIcon']) {
        iconTypes.forEach(iconType => {
            if (textbox[iconType]) {
                canvas.remove(textbox[iconType]);
                textbox[iconType] = null; // Clear reference
            }
        });      
        canvas.renderAll();
    }

    // Helper function to load an SVG icon and place it on the canvas
    function loadIcon(textbox, iconSVG, iconPosition, iconType, eventHandler) {
        fabric.loadSVGFromString(iconSVG, function(objects, options) {
            const icon = fabric.util.groupSVGElements(objects, options);
            icon.set({
                left: iconPosition.left,
                top: iconPosition.top,
                selectable: false,
                evented: true,
                hasControls: false,
                originX: 'center',
                originY: 'center',
            });
            textbox[iconType] = icon;

            icon.on('mousedown', eventHandler); // Attach click event
            canvas.add(icon);
            canvas.bringToFront(icon);
        });
    }

    // Helper function to calculate top-center icon positions
    function calculateIconPositions(textbox, iconOffset = 20) {
        const rotateControl = textbox.oCoords.mtr; // Get the middle top rotate control
        const { x, y } = rotateControl;     
        const copyIconPosition = { left: x + iconOffset, top: y }; // Right of the rotate control
        const trashIconPosition = { left: x - iconOffset, top: y }; // Left of the rotate control

        return { copyIconPosition, trashIconPosition };
    }

    // Function to update the icon positions
    function updateIconPositions(textbox) {
        removeIcons(textbox); // Remove existing icons

        const { copyIconPosition, trashIconPosition } = calculateIconPositions(textbox);

        // Add Copy icon to the canvas
        // loadIcon(
        //     textbox,
        //     copyIconSVG,
        //     copyIconPosition,
        //     'copyIcon',
        //     function() {
        //         console.log('Copy icon clicked!');
        //         cloneTextbox(textbox);
        //     }
        // );

        // // Add Trash icon to the canvas
        // loadIcon(
        //     textbox,
        //     trashIconSVG,
        //     trashIconPosition,
        //     'trashIcon',
        //     function() {
        //         console.log('Trash icon clicked!');
        //         deleteTextbox(textbox);
        //     }
        // );
    }

    // Function to add icons to a new textbox
    function addIconsToTextbox(textbox) {
        textbox.on('moving', function() {
            updateIconPositions(textbox);
        });
        textbox.on('scaling', function() {
            updateIconPositions(textbox);
        });
        textbox.on('rotating', function () {
            updateIconPositions(textbox);
            // updateIconPositions(textbox); // Call the function to reposition icons
        });
        // Event listener to manage icon visibility when a textbox is clicked
        textbox.on('mousedown', function() {
            canvas.getObjects('textbox').forEach(function(tb) {
                if (tb.trashIcon) tb.trashIcon.set('visible', false); // Hide other icons
                if (tb.copyIcon) tb.copyIcon.set('visible', false);
            });
            if (textbox.trashIcon) textbox.trashIcon.set('visible', true); // Show current icons
            if (textbox.copyIcon) textbox.copyIcon.set('visible', true);
            canvas.renderAll(); // Re-render the canvas
        });     
    }
   
        // function updateIconPositions(textbox) {
        //     if (textbox.trashIcon) {
        //         canvas.remove(textbox.trashIcon);
        //         textbox.trashIcon = null;
        //     }
        //     if (textbox.copyIcon) {
        //         canvas.remove(textbox.copyIcon);
        //         textbox.copyIcon = null;
        //     }
        //     canvas.renderAll()
        //     let degree = false;
        //     if (textbox.angle >= 120 && textbox.angle <= 300) {
        //         degree = true
        //     } else {
        //         degree = false
        //     }
        //     const trashIconSVG = `<svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
        //         <g filter="url(#filter0_d_5633_67674)">
        //         <rect x="2.70312" y="2.37207" width="23.9674" height="23.9674" rx="11.9837" fill="white" shape-rendering="crispEdges"/>
        //         <path d="M19.1807 11.3502C17.5179 11.1855 15.8452 11.1006 14.1775 11.1006C13.1888 11.1006 12.2001 11.1505 11.2115 11.2504L10.1929 11.3502" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
        //         <path d="M12.939 10.8463L13.0488 10.1922C13.1287 9.7178 13.1886 9.36328 14.0325 9.36328H15.3407C16.1846 9.36328 16.2495 9.73777 16.3244 10.1971L16.4342 10.8463" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
        //         <path d="M18.1073 12.9277L17.7827 17.9559C17.7278 18.7398 17.6829 19.349 16.2898 19.349H13.0841C11.691 19.349 11.6461 18.7398 11.5912 17.9559L11.2666 12.9277" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
        //         <path d="M13.853 16.6035H15.5158" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
        //         <path d="M13.4385 14.6055H15.9351" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
        //         </g>
        //         </svg>`;

        //     const copyIconSVG = `<svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
        //         <g filter="url(#filter0_d_5633_67676)">
        //         <rect x="2.64893" y="2.37207" width="23.9674" height="23.9674" rx="11.9837" fill="white" shape-rendering="crispEdges"/>
        //         <path fill-rule="evenodd" clip-rule="evenodd" d="M17.6283 16.3538V10.3619C17.6283 9.81039 17.1812 9.36328 16.6297 9.36328H10.6378C10.0863 9.36328 9.63916 9.81039 9.63916 10.3619V16.3538C9.63916 16.9053 10.0863 17.3524 10.6378 17.3524H16.6297C17.1812 17.3524 17.6283 16.9053 17.6283 16.3538ZM10.6379 10.362H16.6298V16.3539H10.6379V10.362ZM18.6271 17.3525V11.3607C19.1786 11.3607 19.6257 11.8078 19.6257 12.3593V17.3525C19.6257 18.4556 18.7315 19.3498 17.6284 19.3498H12.6352C12.0837 19.3498 11.6366 18.9027 11.6366 18.3512H17.6284C18.1799 18.3512 18.6271 17.9041 18.6271 17.3525Z" fill="#0F172A"/>
        //         </g>
        //         </svg>`;

        //     // Calculate corners of the rotated object
        //     const objectCorners = textbox.oCoords; // Get coordinates of corners after rotation and scaling

        //     // Calculate icon positions based on the corners of the textbox
        //     const iconOffset = 20; // Adjust this value to position icons closer or farther

        //     // Top-left corner (for copy icon)
        //     const topLeftX = objectCorners.tl.x - iconOffset;
        //     const topLeftY = objectCorners.tl.y - iconOffset;

        //     // Top-right corner (for trash icon)
        //     const topRightX = objectCorners.tr.x - iconOffset; // Subtract icon width and offset
        //     const topRightY = objectCorners.tr.y - iconOffset;

        //     fabric.loadSVGFromString(trashIconSVG, function(objects, options) {
        //         const trashIcon = fabric.util.groupSVGElements(objects, options);
        //         trashIcon.set({
        //             left: topRightX + 6,
        //             top: (degree == true) ? topRightY + 20 : topRightY - 6,
        //             selectable: false,
        //             evented: true,
        //             hasControls: false,
        //         });
        //         textbox.trashIcon = trashIcon;

        //         trashIcon.on('mousedown', function() {
        //             console.log('Trash icon clicked! Deleting textbox.');
        //             deleteTextbox(textbox);
        //         });
        //         canvas.add(trashIcon);
        //         canvas.bringToFront(trashIcon);
        //     });
        //     fabric.loadSVGFromString(copyIconSVG, function(objects, options) {
        //         const copyIcon = fabric.util.groupSVGElements(objects, options);
        //         copyIcon.set({
        //            left: topLeftX + 6,
        //             top: (degree == true) ? topLeftY + 15 : topLeftY - 6,
        //             selectable: false,
        //             evented: true,
        //             hasControls: false,
        //         });
        //         textbox.copyIcon = copyIcon;
        //         copyIcon.on('mousedown', function() {
        //             console.log('Copy icon clicked!');
        //             cloneTextbox(textbox);
        //         });

        //         canvas.add(copyIcon);
        //         canvas.bringToFront(copyIcon);
        //     });

        //     canvas.bringToFront(textbox);
        //     canvas.renderAll();
        // }

        // function addIconsToTextbox(textbox) {
        //     // Trash icon SVG
        //     // const trashIconSVG = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>`;

        //     if (textbox.trashIcon) {
        //         canvas.remove(textbox.trashIcon);
        //         textbox.trashIcon = null; // Clear reference
        //     }
        //     if (textbox.copyIcon) {
        //         canvas.remove(textbox.copyIcon);
        //         textbox.copyIcon = null; // Clear reference
        //     }
        //     canvas.renderAll()
        //     const trashIconSVG = `<svg width="29" x="0px" y="0px" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
        //         <g filter="url(#filter0_d_5633_67674)">
        //         <rect x="2.70312" y="2.37207" width="23.9674" height="23.9674" rx="11.9837" fill="white" shape-rendering="crispEdges"/>
        //         <path d="M19.1807 11.3502C17.5179 11.1855 15.8452 11.1006 14.1775 11.1006C13.1888 11.1006 12.2001 11.1505 11.2115 11.2504L10.1929 11.3502" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
        //         <path d="M12.939 10.8463L13.0488 10.1922C13.1287 9.7178 13.1886 9.36328 14.0325 9.36328H15.3407C16.1846 9.36328 16.2495 9.73777 16.3244 10.1971L16.4342 10.8463" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
        //         <path d="M18.1073 12.9277L17.7827 17.9559C17.7278 18.7398 17.6829 19.349 16.2898 19.349H13.0841C11.691 19.349 11.6461 18.7398 11.5912 17.9559L11.2666 12.9277" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
        //         <path d="M13.853 16.6035H15.5158" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
        //         <path d="M13.4385 14.6055H15.9351" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
        //         </g>
        //         <defs>
        //         <filter id="filter0_d_5633_67674" x="0.705839" y="0.374784" width="27.9619" height="27.9623" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
        //         <feFlood flood-opacity="0" result="BackgroundImageFix"/>
        //         <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
        //         <feOffset/>
        //         <feGaussianBlur stdDeviation="0.998643"/>
        //         <feComposite in2="hardAlpha" operator="out"/>
        //         <feColorMatrix type="matrix" values="0 0 0 0 0.309804 0 0 0 0 0.368627 0 0 0 0 0.443137 0 0 0 0.12 0"/>
        //         <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_5633_67674"/>
        //         <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_5633_67674" result="shape"/>
        //         </filter>
        //         </defs>
        //         </svg>
        //         `;

        //     fabric.loadSVGFromString(trashIconSVG, function(objects, options) {
        //         const trashIcon = fabric.util.groupSVGElements(objects, options);
        //         trashIcon.set({
        //             left: textbox.left + textbox.width * textbox.scaleX - 20,
        //             top: textbox.top - 20,
        //             selectable: false,
        //             evented: true,
        //             hasControls: false,
        //             visible: false, // Initially hidden
        //             className: 'trash-icon',
        //         });
        //         textbox.trashIcon = trashIcon;

        //         // Handle trash icon click
        //         trashIcon.on('mousedown', function() {
        //             console.log('Trash icon clicked');
        //             deleteTextbox(textbox);
        //         });

        //         canvas.add(trashIcon);
        //     });

        //     // Copy icon SVG
        //     // const copyIconSVG = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"  x="0px" y="0px" width="20" height="20"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M384 336l-192 0c-8.8 0-16-7.2-16-16l0-256c0-8.8 7.2-16 16-16l140.1 0L400 115.9 400 320c0 8.8-7.2 16-16 16zM192 384l192 0c35.3 0 64-28.7 64-64l0-204.1c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1L192 0c-35.3 0-64 28.7-64 64l0 256c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64L0 448c0 35.3 28.7 64 64 64l192 0c35.3 0 64-28.7 64-64l0-32-48 0 0 32c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16l0-256c0-8.8 7.2-16 16-16l32 0 0-48-32 0z"/></svg>`;
        //     // const copyIconSVG = `<svg x="0px" y="0px" width="20" height="20" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
        //     // <path fill-rule="evenodd" clip-rule="evenodd" d="M9.6283 8.35281V2.36095C9.6283 1.80941 9.1812 1.3623 8.62966 1.3623H2.6378C2.08627 1.3623 1.63916 1.80941 1.63916 2.36095V8.35281C1.63916 8.90434 2.08627 9.35145 2.6378 9.35145H8.62966C9.1812 9.35145 9.6283 8.90434 9.6283 8.35281ZM2.6378 2.36095H8.62966V8.35281H2.6378V2.36095ZM10.6269 9.35145V3.35959C11.1785 3.35959 11.6256 3.8067 11.6256 4.35823V9.35145C11.6256 10.4545 10.7314 11.3487 9.6283 11.3487H4.63509C4.08355 11.3487 3.63645 10.9016 3.63645 10.3501H9.6283C10.1798 10.3501 10.6269 9.90298 10.6269 9.35145Z" fill="#0F172A"/>
        //     // </svg>`;

        //     const copyIconSVG = `<svg width="29" x="0px" y="0px" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
        //     <g filter="url(#filter0_d_5633_67676)">
        //     <rect x="2.64893" y="2.37207" width="23.9674" height="23.9674" rx="11.9837" fill="white" shape-rendering="crispEdges"/>
        //     <path fill-rule="evenodd" clip-rule="evenodd" d="M17.6283 16.3538V10.3619C17.6283 9.81039 17.1812 9.36328 16.6297 9.36328H10.6378C10.0863 9.36328 9.63916 9.81039 9.63916 10.3619V16.3538C9.63916 16.9053 10.0863 17.3524 10.6378 17.3524H16.6297C17.1812 17.3524 17.6283 16.9053 17.6283 16.3538ZM10.6379 10.362H16.6298V16.3539H10.6379V10.362ZM18.6271 17.3525V11.3607C19.1786 11.3607 19.6257 11.8078 19.6257 12.3593V17.3525C19.6257 18.4556 18.7315 19.3498 17.6284 19.3498H12.6352C12.0837 19.3498 11.6366 18.9027 11.6366 18.3512H17.6284C18.1799 18.3512 18.6271 17.9041 18.6271 17.3525Z" fill="#0F172A"/>
        //     </g>
        //     <defs>
        //     <filter id="filter0_d_5633_67676" x="0.651639" y="0.374784" width="27.9619" height="27.9623" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
        //     <feFlood flood-opacity="0" result="BackgroundImageFix"/>
        //     <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
        //     <feOffset/>
        //     <feGaussianBlur stdDeviation="0.998643"/>
        //     <feComposite in2="hardAlpha" operator="out"/>
        //     <feColorMatrix type="matrix" values="0 0 0 0 0.309804 0 0 0 0 0.368627 0 0 0 0 0.443137 0 0 0 0.12 0"/>
        //     <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_5633_67676"/>
        //     <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_5633_67676" result="shape"/>
        //     </filter>
        //     </defs>
        //     </svg>
        //     `;
        //     fabric.loadSVGFromString(copyIconSVG, function(objects, options) {
        //         const copyIcon = fabric.util.groupSVGElements(objects, options);
        //         copyIcon.set({
        //             left: textbox.left - 25,
        //             top: textbox.top - 20,
        //             selectable: false,
        //             evented: true,
        //             hasControls: false,
        //             visible: false, // Initially hidden
        //             className: 'copy-icon',
        //         });
        //         textbox.copyIcon = copyIcon;

        //         // Handle copy icon click
        //         copyIcon.on('mousedown', function() {
        //             console.log('Copy icon clicked');
        //             cloneTextbox(textbox);
        //         });

        //         canvas.add(copyIcon);
        //     });

        //     // Bind the updateIconPositions function to the moving and scaling events
        //     textbox.on('moving', function() {
        //         updateIconPositions(textbox);
        //     });
        //     textbox.on('scaling', function() {
        //         updateIconPositions(textbox);
        //     });
        //     textbox.on('rotating', function () {
        //         updateIconPositions(textbox); // Call the function to reposition icons
        //     });
        //     // Event listener to manage icon visibility when a textbox is clicked
        //     textbox.on('mousedown', function() {
        //         canvas.getObjects('textbox').forEach(function(tb) {
        //             if (tb.trashIcon) tb.trashIcon.set('visible', false); // Hide other icons
        //             if (tb.copyIcon) tb.copyIcon.set('visible', false);
        //         });
        //         if (textbox.trashIcon) textbox.trashIcon.set('visible', true); // Show current icons
        //         if (textbox.copyIcon) textbox.copyIcon.set('visible', true);
        //         canvas.renderAll(); // Re-render the canvas
        //     });

        //     // Initially hide all icons
        //     canvas.getObjects('textbox').forEach(function(tb) {
        //         if (tb.trashIcon) tb.trashIcon.set('visible', false);
        //         if (tb.copyIcon) tb.copyIcon.set('visible', false);
        //     });

        //     canvas.renderAll(); // Final render
        // }

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
                editable: true,
                borderColor: '#2DA9FC',
                // cornerColor: 'red',
                cornerColor: '#fff',
                cornerSize: 6,
                transparentCorners: false,
                isStatic: true,
                backgroundColor: 'rgba(0, 0, 0, 0)',
            });

            canvas.add(clonedTextbox);

            // Add icons to the cloned textbox
            updateIconPositions(cloneTextbox)
            addIconsToTextbox(clonedTextbox);

            canvas.renderAll();
        }

        // Handle keyboard events for delete and copy
        function handleKeyboardEvents(e) {
            if (e.key === 'Delete') {
                const activeObject = canvas.getActiveObject();
                if (activeObject && activeObject.type === 'textbox') {
                    deleteTextbox(activeObject);
                }
            } else if (e.ctrlKey && e.key === 'c') {
                const activeObject = canvas.getActiveObject();
                if (activeObject && activeObject.type === 'textbox') {
                    cloneTextbox(activeObject);
                }
            }
        }

        // Add event listener for keyboard events
        document.addEventListener('keydown', handleKeyboardEvents);



        // Example textbox



        // Add two draggable static textboxes outside the image area
        // addDraggableText(150, 50, 'event_name', 'xyz'); // Position this outside the image area
        // addDraggableText(150, 100, 'host_name', 'abc');
        // addDraggableText(150, 150, 'start_time', '5:00PM');
        // addDraggableText(150, 200, 'rsvp_end_time', '6:00PM');
        // addDraggableText(150, 250, 'start_date', '2024-07-27');
        // addDraggableText(150, 300, 'end_date', '2024-07-27');
        // addDraggableText(150, 350, 'Location', 'fdf');



        function updateSelectedTextProperties() {
            var fontSize = parseInt(document.getElementById('fontSize').value, 10);
            var fontColor = document.getElementById('fontColor').value;
            console.log('add to undo')
            addToUndoStack(canvas); // Save state after updating properties
            var activeObject = canvas.getActiveObject();

            if (activeObject && activeObject.type === 'textbox') {
                // Update text properties
                activeObject.set({
                    fontSize: fontSize,
                    fill: fontColor
                });
                activeObject.setCoords(); // Update coordinates

                // Log the updated properties
                console.log('Updated Font Size: ' + activeObject.fontSize);
                console.log('Updated Font Color: ' + activeObject.fill);

                canvas.renderAll();
                
            }
        }



        // document.getElementById('fontSize').addEventListener('change', updateSelectedTextProperties);
        // document.getElementById('fontColor').addEventListener('input', updateSelectedTextProperties);





        function discardIfMultipleObjects(options) {
            
            if (options.target !== undefined && options.target?._objects && options.target?._objects.length > 1) {
                console.log('Multiple objects selected:', options.target);
                canvas.discardActiveObject();
                canvas.renderAll(); // Ensure the canvas is refreshed
            }

            const activeObjects = canvas.getActiveObjects(); // Get all selected objects
            // console.log(activeObjects)
            if (activeObjects.length > 1) {
                console.log('Multiple objects selected:', activeObjects);
                canvas.discardActiveObject(); // Discard active selection
                canvas.renderAll(); // Refresh the canvas
            }
          
        }

        canvas.on('mouse:down', function(options) {
            discardIfMultipleObjects(options);
            if (options.target && options.target.type === 'textbox') {
                canvas.setActiveObject(options.target);
                addIconsToTextbox(options.target)
                updateIconPositions(options.target)
            } else {
                // alert();
                canvas.getObjects('textbox').forEach(function(tb) {
                    if (tb.trashIcon) tb.trashIcon.set('visible', false);
                    if (tb.copyIcon) tb.copyIcon.set('visible', false);
                });
            }
        });

        canvas.on('mouse:up', function(options) {
            discardIfMultipleObjects(options);           
        });
     
        function getTextDataFromCanvas() {
            var objects = canvas.getObjects();
            var textData = [];
            // console.log(objects);
            objects.forEach(function(obj) {
                if (obj.type === 'textbox') {
                    var centerX = obj.left + (obj.width / 2);
                    var centerY = obj.top + (obj.height / 2);
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
                        date_formate: obj.date_formate,
                        letterSpacing: obj.charSpacing / 10, // Divide by 10 to convert to standard spacing
                        lineHeight: obj.lineHeight // Line height of the tex// Include date_formate if set
                    });
                }
            });

            return textData;
        }
        document.getElementById('addTextButton').addEventListener('click', function() {
            addEditableTextbox(100, 100, 'EditableText'); // You can set the initial position and default text
        });

        function addEditableTextbox(left, top, textContent) {
            var textbox = new fabric.Textbox(textContent, {
                left: left,
                top: top,
                width: 200,
                fontSize: 20,
                backgroundColor: 'rgba(0, 0, 0, 0)', // Set background to transparent
                textAlign: 'center',
                fill: '#000000',
                editable: true,
                selectable: true,
                hasControls: true,
                borderColor: '#2DA9FC',
                cornerColor: '#fff',
                cornerSize: 6,
                transparentCorners: false,
                textAlign:'center',
            });

            textbox.on('scaling', function() {
                // Update the font size based on scaling
                var updatedFontSize = textbox.fontSize * (textbox.scaleX + textbox.scaleY) / 2;
                textbox.set('fontSize', updatedFontSize);
                canvas.renderAll();
            });

            canvas.add(textbox);
            canvas.setActiveObject(textbox);
            updateIconPositions(textbox)
            addIconsToTextbox(textbox); // Make it the active object for editing
            canvas.renderAll();
        }



        function saveTextDataToDatabase() {

            // hideStaticTextElements(); 
            var textData = getTextDataFromCanvas();
            var imageURL = canvas.toDataURL('image/png');
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Get CSRF token
            var canvasElement = document.getElementById('imageEditor1');
            var canvasId = canvasElement.getAttribute('data-canvas-id');
            var imageName = 'image_' + Date.now() + '.png';
            const canvasRect = canvasElement.getBoundingClientRect();

            $('.resize-handle').hide();
            $('.removeShapImage').hide();
            const imageWrapperRect = imageWrapper.getBoundingClientRect();
            var id = $('#template_id').val();
            const width = userImageElement.clientWidth;
            const height = userImageElement.clientHeight;
            const left = imageWrapperRect.left - canvasRect.left;
            const top = imageWrapperRect.top - canvasRect.top;
            const centerX = left + width / 2;
            const centerY = top + height / 2;

         

            var shapeImageData = [];

            shapeImageData ={
                shape: shape,
                centerX: centerX,
                centerY: centerY,
                width: width,
                height: height,
            };

          
            console.log(shapeImageData);
            console.log(updatedOBJImage);
            console.log(textData);
           

            fetch('/saveTextData', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json', // Set content type to JSON
                        'X-CSRF-TOKEN': csrfToken // Include CSRF token
                    },
                    body: JSON.stringify({
                        id: canvasId,
                        textElements: textData,
                        shapeImageData: shapeImageData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Text data saved successfully', data);
                    window.location.href = "{{URL::to('/admin/create_template')}}";

                })
                .catch((error) => {
                    console.error('Error:', error);
                });





            hideStaticTextElements();
            showStaticTextElements();
        }

        document.querySelectorAll('.form-check-input').forEach(function(input) {
            input.addEventListener('click', function() {
                const font = this.getAttribute('data-font');
                console.log("Selected font:", font);
                loadAndUse(font); // Call loadAndUse function with the selected font
            });
        });

        // document.getElementById('AbrilFatfaceButton').addEventListener('click', function() {
        //     console.log("fontname")
        //     loadAndUse("AbrilFatface-Regular");
        // });
        // document.getElementById('AdleryProButton').addEventListener('click', function() {
        //     console.log("fontname")

        //     loadAndUse("AdleryPro-Regular");


        // });
        // document.getElementById('AgencyFBButton').addEventListener('click', function() {
        //     console.log("fontname")

        //     loadAndUse("AgencyFB-Bold");


        // });
        // document.getElementById('AlexBrushButton').addEventListener('click', function() {
        //     console.log("fontname")

        //     loadAndUse("AlexBrush-Regular");


        // });
        // document.getElementById('AlluraButton').addEventListener('click', function() {
        //     console.log("fontname")

        //     loadAndUse("Allura-Regular");


        // });
      
        // document.getElementById('ArcherButton').addEventListener('click', function() {
        //     console.log("fontname")

        //     loadAndUse("ArcherBold");


        // });
        // document.getElementById('Archer-BookButton').addEventListener('click', function() {
        //     console.log("fontname")

        //     loadAndUse("Archer-Book");


        // });
        // document.getElementById('Archer-BookItalicButton').addEventListener('click', function() {
        //     console.log("fontname")

        //     loadAndUse("Archer-BookItalic");


        // });
        // document.getElementById('Archer-ExtraLightButton').addEventListener('click', function() {
        //     console.log("fontname")

        //     loadAndUse("Archer-ExtraLight");


        // });
        // document.getElementById('Archer-HairlineButton').addEventListener('click', function() {
        //     console.log("fontname")

        //     loadAndUse("Archer-Hairline");


        // });
        // document.getElementById('Bebas-RegularButton').addEventListener('click', function() {
        //     console.log("fontname")

        //     loadAndUse("Bebas-Regular");


        // });
        // document.getElementById('BookAntiquaButton').addEventListener('click', function() {
        //     console.log("fontname")

        //     loadAndUse("BookAntiqua");


        // });
        // document.getElementById('CandyCaneUnregisteredButton').addEventListener('click', function() {
        //     console.log("fontname")

        //     loadAndUse("CandyCaneUnregistered");


        // });
        // document.getElementById('CarbonBl-RegularButton').addEventListener('click', function() {
        //     console.log("fontname")

        //     loadAndUse("CarbonBl-Regular");


        // });
        // document.getElementById('CarmenSans-ExtraBoldButton').addEventListener('click', function() {
        //     console.log("fontname")

        //     loadAndUse("CarmenSans-ExtraBold");


        // });
        // document.getElementById('CarmenSans-RegularButton').addEventListener('click', function() {
        //     console.log("fontname")

        //     loadAndUse("CarmenSans-Regular");


        // });
        // document.getElementById('ChristmasCookiesButton').addEventListener('click', function() {
        //     console.log("fontname")

        //     loadAndUse("ChristmasCookies");


        // });
        // document.getElementById('Bungee-RegularButton').addEventListener('click', function() {
        //     console.log("fontname")

        //     loadAndUse("Bungee-Regular");


        // });
        //     textElement.style.fontFamily = 'Allura'; // Change to Allura font
        // });
        function loadAndUse(font) {
            var myfont = new FontFaceObserver(font);
            console.log(font);
            console.log(canvas.toJSON());
            console.log('add to undo')

            addToUndoStack(canvas);

            myfont.load().then(function() {


                // When font is loaded, use it.
                var activeObject = canvas.getActiveObject();
                if (activeObject) {
                    activeObject.set("fontFamily", font);
                    canvas.requestRenderAll();
                } else {
                    alert('No object selected');
                }
            }).catch(function(e) {
                console.log(e);
                alert('Font loading failed: ' + font);
            });
        }

        function executeCommand(command, font = null) {
            var activeObject = canvas.getActiveObject();

            if (!activeObject || activeObject.type !== 'textbox') {
                return; // No object or not a textbox, so do nothing
            }
            console.log('add to undo')
            addToUndoStack(canvas); // Save state for undo/redo functionality

            // Commands object to handle various styles and operations
            const commands = {
                bold: () => activeObject.set('fontWeight', activeObject.fontWeight === 'bold' ? '' : 'bold'),
                italic: () => activeObject.set('fontStyle', activeObject.fontStyle === 'italic' ? '' : 'italic'),
                underline: () => activeObject.set('underline', !activeObject.underline),
                setLineHeight: (value) => activeObject.set('lineHeight', value),
                strikeThrough: () => activeObject.set('linethrough', !activeObject.linethrough),
                removeFormat: () => {
                    activeObject.set({
                        fontWeight: '',
                        fontStyle: '',
                        underline: false,
                        linethrough: false,
                        fontFamily: 'Arial'
                    });
                },
                fontName: (font) => {
                    if (font) {
                        console.log('load and use command')
                        // loadAndUse(font);
                    }
                },
                justifyLeft: () => activeObject.set('textAlign', 'left'),
                justifyCenter: () => activeObject.set('textAlign', 'center'),
                justifyRight: () => activeObject.set('textAlign', 'right'),
                justifyFull: () => activeObject.set('textAlign', 'justify'),
                uppercase: () => activeObject.set('text', activeObject.text.toUpperCase()),
                lowercase: () => activeObject.set('text', activeObject.text.toLowerCase()),
                capitalize: () => {
                    const capitalizedText = activeObject.text.replace(/\b\w/g, char => char.toUpperCase());
                    activeObject.set('text', capitalizedText);
                }
            };

            // Execute the corresponding command
            if (commands[command]) {
                commands[command](font); // Pass font to fontName if needed
               

                canvas.renderAll(); // Re-render canvas after change
            }
        }


      

        document.querySelectorAll('[data-command]').forEach(function(button) {
            button.addEventListener('click', function() {
                const command = button.getAttribute('data-command');
                if(command=="fontName" || command=="undo" || command=="redo"){
                    return;
                }
                executeCommand(this.getAttribute('data-command'));
            });
        });
        
        // Undo and Redo actions (basic implementation)
        let undoStack = [];
    let redoStack = [];
    let isAddingToUndoStack = 0;
    
    function addToUndoStack(canvas) {
        // clearTimeout(isAddingToUndoStack);
        console.log(canvas.toJSON());
        
        // isAddingToUndoStack = setTimeout(function() {
            console.log({undoStack});

            console.log("beofre {undoStack}");

          
            undoStack.push(canvas.toJSON());
            console.log("after {undoStack}");

            console.log({undoStack});

            redoStack = []; // Clear redo stack on new action
        // }, 10);
    }

    function undo() {
        console.log(undoStack);
        if (undoStack.length > 0) {  // Ensure at least one previous state exists
            reattachIcons();
            redoStack.push(canvas.toJSON()); // Save current state to redo stack
            const lastState = undoStack.pop(); // Get the last state to undo
            canvas.loadFromJSON(lastState, function () {
                canvas.renderAll(); // Render the canvas after loading state
                reattachIcons(); // Reattach the icons to the textboxes
            });
          
        }
    }

    function redo() {
        if (redoStack.length > 0) {
            reattachIcons();
            undoStack.push(canvas.toJSON()); // Save current state to undo stack
            const nextState = redoStack.pop(); // Get the next state to redo
            canvas.loadFromJSON(nextState, function () {
                canvas.renderAll(); // Render the canvas after loading state
                reattachIcons(); // Reattach the icons to the textboxes
            });
        }
    }

    function reattachIcons() {
        undoStack.forEach((ob, index) => {
            ob.objects = ob.objects.filter(obj => obj.type !== 'group');
            ob.objects.forEach(obj => {
                console.log(obj);
                obj.borderColor = "#2DA9FC";
                obj.cornerColor = "#fff";
                obj.cornerSize = 6;
                obj.textAlign = 'center';
            });
        });

        redoStack.forEach((ob, index) => {
            ob.objects = ob.objects.filter(obj => obj.type !== 'group');
        });
    }
 
    $("#undoButton").click(function(){
        undo();
    })
    $("#redoButton").click(function(){
        redo();
    })
    // document.getElementById('undo').addEventListener('click', undo);
    // document.getElementById('redo').addEventListener('click', redo);

       

        const fileInput = document.getElementById('fileInput');
        const userImageElement = document.getElementById('user_image');
        const imageWrapper = document.getElementById('imageWrapper');
        // const canvasElement = new fabric.Canvas('imageEditor', {
        //     width: 500, // Canvas width
        //     height: 500, // Canvas height
        // });

        const resizeHandles = {
    topLeft: document.querySelector('.resize-handle.top-left'),
    topRight: document.querySelector('.resize-handle.top-right'),
    bottomLeft: document.querySelector('.resize-handle.bottom-left'),
    bottomRight: document.querySelector('.resize-handle.bottom-right'),
    topCenter: document.querySelector('.resize-handle.top-center'),
    bottomCenter: document.querySelector('.resize-handle.bottom-center'),
    leftCenter: document.querySelector('.resize-handle.left-center'),
    rightCenter: document.querySelector('.resize-handle.right-center')
};

let isDragging = false;
let isResizing = false;
let startWidth, startHeight, startX, startY, activeHandle;
let offsetX, offsetY;
let shape = 'rectangle'; // Default shape
let shapeChangedDuringDrag = false; // Flag to track shape change
let imageUploaded = false; // Flag to track if image has been uploaded

function startResize(event, handle) {
    isResizing = true;
    startWidth = userImageElement.clientWidth;
    startHeight = userImageElement.clientHeight;
    startX = event.clientX;
    startY = event.clientY;
    activeHandle = handle;
    event.stopPropagation();
}

function resize(event) {
    if (isResizing) {
        let newWidth, newHeight;

        if (activeHandle === resizeHandles.bottomRight) {
            newWidth = startWidth + (event.clientX - startX);
            newHeight = startHeight + (event.clientY - startY);
        } else if (activeHandle === resizeHandles.bottomLeft) {
            newWidth = startWidth - (event.clientX - startX);
            newHeight = startHeight + (event.clientY - startY);
            imageWrapper.style.left = `${event.clientX}px`;
        } else if (activeHandle === resizeHandles.topRight) {
            newWidth = startWidth + (event.clientX - startX);
            newHeight = startHeight - (event.clientY - startY);
            imageWrapper.style.top = `${event.clientY}px`;
        } else if (activeHandle === resizeHandles.topLeft) {
            newWidth = startWidth - (event.clientX - startX);
            newHeight = startHeight - (event.clientY - startY);
            imageWrapper.style.left = `${event.clientX}px`;
            imageWrapper.style.top = `${event.clientY}px`;
        } else if (activeHandle === resizeHandles.topCenter) {
            newHeight = startHeight - (event.clientY - startY);
            imageWrapper.style.top = `${event.clientY}px`;
        } else if (activeHandle === resizeHandles.bottomCenter) {
            newHeight = startHeight + (event.clientY - startY);
        } else if (activeHandle === resizeHandles.leftCenter) {
            newWidth = startWidth - (event.clientX - startX);
            imageWrapper.style.left = `${event.clientX}px`;
        } else if (activeHandle === resizeHandles.rightCenter) {
            newWidth = startWidth + (event.clientX - startX);
        }

        if (newWidth) userImageElement.style.width = `${newWidth}px`;
        if (newHeight) userImageElement.style.height = `${newHeight}px`;
    }
}

function handleMouseDown(event) {
    const canvas = document.querySelector('.new');
    const canvasRect = canvas.getBoundingClientRect();

    if (event.target.classList.contains('resize-handle')) {
        startResize(event, event.target);
    } else {
        event.preventDefault(); // Prevent default behavior during dragging (text selection)
        isDragging = true;
        offsetX = event.clientX - imageWrapper.offsetLeft;
        offsetY = event.clientY - imageWrapper.offsetTop;
        shapeChangedDuringDrag = false; // Reset flag on new drag start
    }
}

function handleMouseMove(event) {
    if (isDragging) {
        const canvas = document.querySelector('.new');
        const canvasRect = canvas.getBoundingClientRect();
        let newX = event.clientX - offsetX;
        let newY = event.clientY - offsetY;

        // Ensure the image stays within the canvas boundaries
        if (newX < canvasRect.left) newX = canvasRect.left;
        if (newX + userImageElement.clientWidth > canvasRect.right)
            newX = canvasRect.right - userImageElement.clientWidth;
        if (newY < canvasRect.top) newY = canvasRect.top;
        if (newY + userImageElement.clientHeight > canvasRect.bottom)
            newY = canvasRect.bottom - userImageElement.clientHeight;

        imageWrapper.style.left = `${newX}px`;
        imageWrapper.style.top = `${newY}px`;
        shapeChangedDuringDrag = true; // Set flag if dragging occurs
    } else if (isResizing) {
        resize(event);
    }
}


        function handleMouseUp(event) {
            if (event.target === userImageElement && !shapeChangedDuringDrag) {
                // Cycle through shapes
                const shapes = ['rectangle', 'circle', 'star', 'rounded-border', 'heart','triangle'];
                const currentIndex = shapes.indexOf(shape);
                shape = shapes[(currentIndex + 1) % shapes.length];
                console.log(`Shape changed to: ${shape}`); // Log shape change

                drawCanvas();
            }

            isDragging = false;
            isResizing = false;
        }

        function drawCanvas() {
            userImageElement.style.clipPath = '';

            switch (shape) {
                case 'rectangle':
                    break;
                case 'circle':
                    userImageElement.style.clipPath = 'circle(50% at 50% 50%)';
                    break;
                case 'star':
                    userImageElement.style.clipPath =
                        'polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)';
                    break;
                case 'rounded-border':
                    userImageElement.style.clipPath = 'inset(0 round 20px)';
                    break;
                case 'heart':
                    userImageElement.style.clipPath = 'url(#heartClipPath)';
                    break;
                case 'triangle':
                    userImageElement.style.clipPath = 'polygon(50% 0%, 0% 100%, 100% 100%)';
                    break;
                case 'square':
                    userImageElement.style.clipPath = 'inset(0)'; // Or no clipPath since the image is a square
                    break;
                case 'oval':
                    userImageElement.style.clipPath = 'ellipse(50% 40% at 50% 50%)';
                    break;
                default:
                    break;
            }
        }

        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('image', file);
                var id = $('#template_id').val();
                // console.log(formData);
                // Include shape information in the form data
                formData.append('shape', shape); // Send the current shape value

                fetch(`/user_image/${id}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Server response:', data);
                        updatedOBJImage = {
                            shape: 'rectangle',
                            
                            width: 100,
                            height: 100
                        };
                        updateClipPath(data.imagePath, updatedOBJImage)
                      
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error);
                    });
            }
        });

        document.getElementById('saveButton').addEventListener('click', function() {
            // alert();
            saveTextDataToDatabase();
          
        });

        Object.values(resizeHandles).forEach(handle => {
            handle.addEventListener('mousedown', function(event) {
                startResize(event, handle);
            });
        });

        document.addEventListener('mousemove', resize);
        document.addEventListener('mouseup', handleMouseUp);
        imageWrapper.addEventListener('mousedown', handleMouseDown);        
        document.addEventListener('mousemove', handleMouseMove);
     

    });

    function toggleSidebar(id = null) {
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


    $(document).on("click", ".design-sidebar-action", function() {
        let designId = $(this).attr('design-id')
        if (designId) {
            $(".design-sidebar").addClass('d-none')
            $(".design-sidebar_" + designId).removeClass('d-none')
            $('.close-btn').attr('data-id', "design-sidebar_" + designId);
        }
    })

    $(document).on("click", ".close-btn", function() {
        toggleSidebar();
        var id = $(this).data('id');
        $('#sidebar').removeClass(id);
    })


</script>