<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {


        // Initialize fabric canvas
        var canvas = new fabric.Canvas('imageEditor1', {
            width: 345, // Canvas width
            height: 490, // Canvas height
        });


        // function loadTextDataFromDatabase() {
        //     var id = $('#template_id').val();
        //     // let urlParams = new URLSearchParams(window.location.search);


        //     // let id = urlParams.get('id');


        //     fetch(`/loadTextData/${id}`) // API endpoint to load data from your database
        //         .then(response => response.json())
        //         .then(data => {
        //             if (data) {
        //                 // Load background image
        //                 var canvasElement = document.getElementById('imageEditor1');
        //                 canvasElement.setAttribute('data-canvas-id', data.id);
        //                 fabric.Image.fromURL(data.imagePath, function(img) {
        //                     img.set({
        //                         left: 0,
        //                         top: 0,
        //                         selectable: false, // Non-draggable background image
        //                         hasControls: false // Disable resizing controls
        //                     });
        //                     canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));

        //                 });

        //                 // Load static information (text elements)
        //                 if (data.static_information) {
        //                     hideStaticTextElements(); // Hide static text elements if static information is present
        //                     const staticInfo = JSON.parse(data.static_information);
        //                     console.log(staticInfo);

        //                     // Render text elements on canvas
        //                     staticInfo.textElements.forEach(element => {
        //                         // alert();
        //                         let textElement = new fabric.Textbox(element.text, { // Use Textbox for editable text
        //                             left: element.left,
        //                             top: element.top,
        //                             width: element.width || 200, // Default width if not provided
        //                             fontSize: element.fontSize,
        //                             fill: element.fill,
        //                             fontFamily: element.fontFamily,
        //                             fontWeight: element.fontWeight,
        //                             fontStyle: element.fontStyle,
        //                             underline: element.underline,
        //                             linethrough: element.linethrough,
        //                             backgroundColor: element.backgroundColor,
        //                             textAlign: element.textAlign,
        //                             editable: true,
        //                             hasControls: true,
        //                             // borderColor: 'blue',
        //                             borderColor: '#2DA9FC',
        //                             // cornerColor: 'red',
        //                             cornerColor: '#fff',
        //                             cornerSize: 6,
        //                             transparentCorners: false,
        //                             isStatic: true
        //                         });
        //                         const textWidth = textElement.calcTextWidth();
        //                         textElement.set({
        //                             width: textWidth
        //                         });

        //                         textElement.on('scaling', function() {
        //                             // Calculate the updated font size based on scaling factors
        //                             var updatedFontSize = textElement.fontSize * (textElement.scaleX + textElement.scaleY) / 2;
        //                             textElement.set('fontSize', updatedFontSize); // Update the font size
        //                             canvas.renderAll(); // Re-render the canvas to reflect changes
        //                         });

        //                         addIconsToTextbox(textElement);
        //                         canvas.add(textElement);

        //                     });
        //                 } else {
        //                     showStaticTextElements();
        //                     addDraggableText(150, 50, 'event_name', 'xyz'); // Position this outside the image area
        //                     addDraggableText(150, 100, 'host_name', 'abc');
        //                     addDraggableText(150, 150, 'start_time', '5:00PM');
        //                     addDraggableText(150, 200, 'rsvp_end_time', '6:00PM');
        //                     addDraggableText(150, 250, 'start_date', '2024-07-27');
        //                     addDraggableText(150, 300, 'end_date', '2024-07-27');
        //                     addDraggableText(150, 350, 'Location', 'fdf');

        //                 }

        //                 // Set custom attribute with the fetched ID

        //                 canvas.renderAll(); // Ensure all elements are rendered
        //             }
        //         })
        //         .catch(error => console.error('Error loading text data:', error));
        // }


        var newshape = "";

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
                            staticInfo?.shapeImageData?.forEach(element => {
                                if (element.shape != undefined && element.centerX != undefined && element.centerY != undefined && element.height != undefined && element.width != undefined) {
                                    console.log(element.shape);
                                    shape = element.shape;
                                    centerX = element.centerX;
                                    centerY = element.centerY;
                                    height = element.height;
                                    width = element.width;

                                }
                            })
                        }

                        // Load filed image (filedImagePath) as another image layer
                        if (data.filedImagePath) {
                            const userImageElement = document.getElementById('user_image');
                            const imageWrapper = document.getElementById('imageWrapper');
                            const canvasElement = new fabric.Canvas('imageEditor', {
                                width: 500, // Canvas width
                                height: 500, // Canvas height
                            });
                            let isDragging = false;
                            let isResizing = false;
                            let startWidth, startHeight, startX, startY, activeHandle;
                            let offsetX, offsetY;
                            let shapeChangedDuringDrag = false; // Flag to track shape change
                            let imageUploaded = false; // Flag to track if image has been uploaded

                            const imgElement = new Image();
                            imgElement.src = data.filedImagePath;

                            userImageElement.src = data.filedImagePath;
                            imageWrapper.style.display = 'block';

                            imgElement.onload = function() {
                                console.log("Image loaded successfully.");
                                const imgInstance = new fabric.Image(imgElement, {
                                    left: 0,
                                    top: 0,
                                    selectable: true,
                                    hasControls: true,
                                    hasBorders: true,
                                    cornerColor: 'red',
                                    cornerStrokeColor: 'blue',
                                    borderColor: 'blue',
                                    cornerSize: 10,
                                    transparentCorners: false,
                                    lockUniScaling: true,
                                    scaleX: 600 / imgElement.width,
                                    scaleY: 600 / imgElement.height
                                });

                                canvasElement.add(imgInstance);
                                drawCanvas();
                                console.log('Image loaded and added to canvas.');
                                imageUploaded = true; // Set flag to true after image is uploaded
                                addIconsToImage(imgInstance);
                            };

                            imgElement.onerror = function() {
                                console.error("Failed to load image.");
                            };

                            let clipPath;

                            if (shape === 'circle') {
                                clipPath = new fabric.Circle({
                                    radius: 75, // Define radius of the circle
                                    originX: 'center', // Set origin to center of the circle
                                    originY: 'center' // Set origin to center of the circle
                                });
                            } else if (shape === 'rectangle') {
                                clipPath = new fabric.Rect({
                                    width: 150, // Set width of the rectangle
                                    height: 100, // Set height of the rectangle
                                    originX: 'center', // Set origin to center of the rectangle
                                    originY: 'center' // Set origin to center of the rectangle
                                });
                            } else if (shape === 'star') {
                                // Star shape path generation
                                const starPoints = [];
                                const spikes = 5;
                                const outerRadius = 75; // Outer radius of the star
                                const innerRadius = outerRadius / 2;

                                for (let i = 0; i < spikes * 2; i++) {
                                    const angle = (i * Math.PI) / spikes;
                                    const radius = i % 2 === 0 ? outerRadius : innerRadius;
                                    starPoints.push(
                                        Math.cos(angle) * radius,
                                        Math.sin(angle) * radius
                                    );
                                }
                                clipPath = new fabric.Polygon(starPoints, {
                                    left: 0,
                                    top: 0,
                                    originX: 'center',
                                    originY: 'center'
                                });
                            } else if (shape === 'heart') {
                                // Heart shape path
                                const heartPath = [
                                    'M', 0, 0,
                                    'C', -50, -60, -50, 10, 0, 30,
                                    'C', 50, 10, 50, -60, 0, 0
                                ].join(' ');

                                clipPath = new fabric.Path(heartPath, {
                                    left: 0,
                                    top: 0,
                                    originX: 'center',
                                    originY: 'center'
                                });
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

                                    // textElement.on('scaling', function() {
                                    //     var updatedFontSize = textElement.fontSize * (textElement.scaleX + textElement.scaleY) / 2;
                                    //     textElement.set('fontSize', updatedFontSize);
                                    //     canvas.renderAll();
                                    // });

                                    addIconsToTextbox(textElement);
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

        let updateTimeout; // Variable to store the timeout reference


        function addIconsToImage(textbox) {
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
            const text = textbox.text;
            const fontSize = textbox.fontSize;
            const fontFamily = textbox.fontFamily;
            const charSpacing = textbox.charSpacing || 0;

            const ctx = canvas.getContext('2d');
            ctx.font = `${fontSize}px ${fontFamily}`;
            const measuredTextWidth = ctx.measureText(text).width;
            const width = measuredTextWidth + (charSpacing * (text.length - 1));

            textbox.set('width', width);
            textbox.setCoords();
            canvas.renderAll();
        };

        // Set font size function
        const setFontSize = () => {
            const newValue = fontSizeRange.value;
            fontSizeInput.value = newValue;
            fontSizeTooltip.innerHTML = `<span>${newValue}px</span>`;

            const activeObject = canvas.getActiveObject();
            if (activeObject && activeObject.type === 'textbox') {
                activeObject.set('fontSize', newValue);
                updateTextboxWidth(activeObject);
            }
        };

        // Set letter spacing function
        const setLetterSpacing = () => {
            const newValue = letterSpacingRange.value;
            console.log(newValue);
            letterSpacingInput.value = newValue;
            letterSpacingTooltip.innerHTML = `<span>${newValue}</span>`;

            const activeObject = canvas.getActiveObject();
            if (activeObject && activeObject.type === 'textbox') {
                activeObject.set('charSpacing', newValue * 10); // Convert spacing to match Fabric.js scale
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
        // $('#color-picker').spectrum({
        //     type: "flat",
        //     color: "#000000", // Default font color
        //     showInput: true,
        //     allowEmpty: true, // Allows setting background to transparent
        //     showAlpha: true, // Allows transparency adjustment
        //     preferredFormat: "rgba", // Ensure it handles RGBA
        //     change: function(color) {
        //         if (color) {
        //             changeColor(color.toRgbString()); // Use RGB string for color changes
        //         } else {
        //             changeColor('rgba(0, 0, 0, 0)'); // Handle transparency by default
        //         }
        //     }
        // });
        // // Change event for radio buttons to update the color picker
        // $('input[name="colorType"]').on('change', function() {
        //     updateColorPicker(); // Update color picker when radio button changes
        // });
        // // Update color picker when object is selected
        // canvas.on('selection:created', function() {
        //     updateColorPicker(); // Update color picker when a new object is selected
        // });
        // canvas.on('selection:updated', function() {
        //     updateColorPicker(); // Update color picker when selection is updated
        // });
        // // Function to change font or background color
        // function changeColor(selectedColor) {
        //     const selectedColorType = document.querySelector('input[name="colorType"]:checked').value;
        //     const activeObject = canvas.getActiveObject();

        //     if (!activeObject) {
        //         // alert('No object selected');
        //         return;
        //     }

        //     if (activeObject.type === 'textbox') {
        //         if (selectedColorType === 'font') {
        //             activeObject.set('fill', selectedColor); // Change font color
        //         } else if (selectedColorType === 'background') {
        //             activeObject.set('backgroundColor', selectedColor); // Change background color
        //         }
        //         canvas.renderAll(); // Re-render the canvas after color change
        //     }
        // }
        // // Function to update the color picker based on the selected object's current font or background color
        // function updateColorPicker() {
        //     const activeObject = canvas.getActiveObject();
        //     const selectedColorType = document.querySelector('input[name="colorType"]:checked').value;

        //     if (activeObject && activeObject.type === 'textbox') {
        //         if (selectedColorType === 'font') {
        //             $('#color-picker').spectrum('set', activeObject.fill || '#000000'); // Set font color in picker
        //         } else if (selectedColorType === 'background') {
        //             const bgColor = activeObject.backgroundColor || 'rgba(0, 0, 0, 0)'; // Default to transparent background
        //             $('#color-picker').spectrum('set', bgColor); // Set current background color in picker
        //         }
        //     }
        // }
        // // Update the color picker when the color type changes (font or background)
        // $('input[name="colorType"]').change(function() {
        //     const activeObject = canvas.getActiveObject();
        //     if (activeObject && activeObject.type === 'textbox') {
        //         updateColorPicker(); // Update picker when the selected color type changes
        //     }
        // });
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
        canvas.on('selection:created', updateColorPicker);
        canvas.on('selection:updated', updateColorPicker);

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
            })

            text.set('width', text.get('text').length * 10);

            text.on('scaling', function() {
                var updatedFontSize = text.fontSize * (text.scaleX + text.scaleY) / 2;
                text.set('fontSize', updatedFontSize);
                canvas.renderAll();
                findTextboxCenter(text);
            });

            text.on('moving', function() {
                findTextboxCenter(text);
            });
            canvas.add(text);
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

        // function updateIconPositions(textbox) {

        //     if (textbox.trashIcon) {
        //         canvas.remove(textbox.trashIcon);
        //         textbox.trashIcon = null; // Clear reference
        //         // const trashIconSVG = `<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 50 50"><path d="M20,30 L30,30 L30,40 L20,40 Z M25,10 L20,10 L20,7 L30,7 L30,10 Z M17,10 L33,10 L33,40 L17,40 Z" fill="#FF0000"/></svg>`;
        //         // const trashIconSVG = `<svg width="23px" height="23px" style="background-color: #fff; color:#000; padding: 10px; border-radius: 50%;" strock=""  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>`;


        //         const trashIconSVG = `<svg width="29" x="0px" y="0px" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
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
        //         fabric.loadSVGFromString(trashIconSVG, function(objects, options) {
        //             const trashIcon = fabric.util.groupSVGElements(objects, options);
        //             trashIcon.set({
        //                 left: textbox.left + textbox.width * textbox.scaleX - 20,
        //                 top: textbox.top - 20,
        //                 selectable: false,
        //                 evented: true,
        //                 hasControls: false,
        //                 visible: false, // Initially hidden
        //                 className: 'trash-icon',
        //             });
        //             textbox.trashIcon = trashIcon;


        //             // Ensure the copyIcon is on top
        //             canvas.bringToFront(trashIcon);
        //             textbox.trashIcon.on('mousedown', function() {
        //                 console.log('deleted icon');
        //                 deleteTextbox(textbox);
        //             });
        //         })
        //         // console.log('Updated Trash Icon Position:', textbox.trashIcon.left, textbox.trashIcon.top);
        //     }

        //     if (textbox.copyIcon) {
        //         canvas.remove(textbox.copyIcon);
        //         textbox.copyIcon = null; // Clear reference
        //     }


        //     // const copyIconSVG = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"  x="0px" y="0px" width="20" height="20"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M384 336l-192 0c-8.8 0-16-7.2-16-16l0-256c0-8.8 7.2-16 16-16l140.1 0L400 115.9 400 320c0 8.8-7.2 16-16 16zM192 384l192 0c35.3 0 64-28.7 64-64l0-204.1c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1L192 0c-35.3 0-64 28.7-64 64l0 256c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64L0 448c0 35.3 28.7 64 64 64l192 0c35.3 0 64-28.7 64-64l0-32-48 0 0 32c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16l0-256c0-8.8 7.2-16 16-16l32 0 0-48-32 0z"/></svg>`;
        //     //             const copyIconSVG = `<svg x="0px" y="0px" width="29" height="19" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
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
        //         let copyIcon = fabric.util.groupSVGElements(objects, options);
        //         copyIcon.set({
        //             left: textbox.left - 25,
        //             top: textbox.top - 20,
        //             selectable: false,
        //             evented: true,
        //             hasControls: false,
        //             visible: true, // Initially hidden
        //             className: 'copy-icon',
        //         });
        //         // Add the copyIcon to the canvas
        //         textbox.copyIcon = copyIcon

        //         // Ensure the copyIcon is on top
        //         canvas.bringToFront(copyIcon);

        //         // Handle copy icon click
        //         textbox.copyIcon.on('mousedown', function() {
        //             console.log('Copy icon clicked1');
        //             cloneTextbox(textbox);
        //         });
        //     })
        //     // console.log('Updated Copy Icon Position:', textbox.copyIcon.left, textbox.copyIcon.top);


        //     canvas.renderAll(); // Re-render the canvas to apply the new positions
        // }

        function updateIconPositions(textbox) {
            if (textbox.trashIcon) {
                canvas.remove(textbox.trashIcon);
                textbox.trashIcon = null;
            }
            if (textbox.copyIcon) {
                canvas.remove(textbox.copyIcon);
                textbox.copyIcon = null;
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

                trashIcon.on('mousedown', function() {
                    console.log('Trash icon clicked! Deleting textbox.');
                    deleteTextbox(textbox);
                });
                canvas.add(trashIcon);
                canvas.bringToFront(trashIcon);
            });
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
                copyIcon.on('mousedown', function() {
                    console.log('Copy icon clicked!');
                    cloneTextbox(textbox);
                });

                canvas.add(copyIcon);
                canvas.bringToFront(copyIcon);
            });

            canvas.bringToFront(textbox);
            canvas.renderAll();
        }

        function addIconsToTextbox(textbox) {
            // Trash icon SVG
            // const trashIconSVG = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>`;


            const trashIconSVG = `<svg width="29" x="0px" y="0px" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g filter="url(#filter0_d_5633_67674)">
                <rect x="2.70312" y="2.37207" width="23.9674" height="23.9674" rx="11.9837" fill="white" shape-rendering="crispEdges"/>
                <path d="M19.1807 11.3502C17.5179 11.1855 15.8452 11.1006 14.1775 11.1006C13.1888 11.1006 12.2001 11.1505 11.2115 11.2504L10.1929 11.3502" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12.939 10.8463L13.0488 10.1922C13.1287 9.7178 13.1886 9.36328 14.0325 9.36328H15.3407C16.1846 9.36328 16.2495 9.73777 16.3244 10.1971L16.4342 10.8463" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M18.1073 12.9277L17.7827 17.9559C17.7278 18.7398 17.6829 19.349 16.2898 19.349H13.0841C11.691 19.349 11.6461 18.7398 11.5912 17.9559L11.2666 12.9277" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13.853 16.6035H15.5158" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13.4385 14.6055H15.9351" stroke="#0F172A" stroke-width="0.998643" stroke-linecap="round" stroke-linejoin="round"/>
                </g>
                <defs>
                <filter id="filter0_d_5633_67674" x="0.705839" y="0.374784" width="27.9619" height="27.9623" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                <feOffset/>
                <feGaussianBlur stdDeviation="0.998643"/>
                <feComposite in2="hardAlpha" operator="out"/>
                <feColorMatrix type="matrix" values="0 0 0 0 0.309804 0 0 0 0 0.368627 0 0 0 0 0.443137 0 0 0 0.12 0"/>
                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_5633_67674"/>
                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_5633_67674" result="shape"/>
                </filter>
                </defs>
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
                    className: 'trash-icon',
                });
                textbox.trashIcon = trashIcon;

                // Handle trash icon click
                trashIcon.on('mousedown', function() {
                    console.log('Trash icon clicked');
                    deleteTextbox(textbox);
                });

                canvas.add(trashIcon);
            });

            // Copy icon SVG
            // const copyIconSVG = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"  x="0px" y="0px" width="20" height="20"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M384 336l-192 0c-8.8 0-16-7.2-16-16l0-256c0-8.8 7.2-16 16-16l140.1 0L400 115.9 400 320c0 8.8-7.2 16-16 16zM192 384l192 0c35.3 0 64-28.7 64-64l0-204.1c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1L192 0c-35.3 0-64 28.7-64 64l0 256c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64L0 448c0 35.3 28.7 64 64 64l192 0c35.3 0 64-28.7 64-64l0-32-48 0 0 32c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16l0-256c0-8.8 7.2-16 16-16l32 0 0-48-32 0z"/></svg>`;
            // const copyIconSVG = `<svg x="0px" y="0px" width="20" height="20" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
            // <path fill-rule="evenodd" clip-rule="evenodd" d="M9.6283 8.35281V2.36095C9.6283 1.80941 9.1812 1.3623 8.62966 1.3623H2.6378C2.08627 1.3623 1.63916 1.80941 1.63916 2.36095V8.35281C1.63916 8.90434 2.08627 9.35145 2.6378 9.35145H8.62966C9.1812 9.35145 9.6283 8.90434 9.6283 8.35281ZM2.6378 2.36095H8.62966V8.35281H2.6378V2.36095ZM10.6269 9.35145V3.35959C11.1785 3.35959 11.6256 3.8067 11.6256 4.35823V9.35145C11.6256 10.4545 10.7314 11.3487 9.6283 11.3487H4.63509C4.08355 11.3487 3.63645 10.9016 3.63645 10.3501H9.6283C10.1798 10.3501 10.6269 9.90298 10.6269 9.35145Z" fill="#0F172A"/>
            // </svg>`;
            const copyIconSVG = `<svg width="29" x="0px" y="0px" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g filter="url(#filter0_d_5633_67676)">
            <rect x="2.64893" y="2.37207" width="23.9674" height="23.9674" rx="11.9837" fill="white" shape-rendering="crispEdges"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M17.6283 16.3538V10.3619C17.6283 9.81039 17.1812 9.36328 16.6297 9.36328H10.6378C10.0863 9.36328 9.63916 9.81039 9.63916 10.3619V16.3538C9.63916 16.9053 10.0863 17.3524 10.6378 17.3524H16.6297C17.1812 17.3524 17.6283 16.9053 17.6283 16.3538ZM10.6379 10.362H16.6298V16.3539H10.6379V10.362ZM18.6271 17.3525V11.3607C19.1786 11.3607 19.6257 11.8078 19.6257 12.3593V17.3525C19.6257 18.4556 18.7315 19.3498 17.6284 19.3498H12.6352C12.0837 19.3498 11.6366 18.9027 11.6366 18.3512H17.6284C18.1799 18.3512 18.6271 17.9041 18.6271 17.3525Z" fill="#0F172A"/>
            </g>
            <defs>
            <filter id="filter0_d_5633_67676" x="0.651639" y="0.374784" width="27.9619" height="27.9623" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
            <feFlood flood-opacity="0" result="BackgroundImageFix"/>
            <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
            <feOffset/>
            <feGaussianBlur stdDeviation="0.998643"/>
            <feComposite in2="hardAlpha" operator="out"/>
            <feColorMatrix type="matrix" values="0 0 0 0 0.309804 0 0 0 0 0.368627 0 0 0 0 0.443137 0 0 0 0.12 0"/>
            <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_5633_67676"/>
            <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_5633_67676" result="shape"/>
            </filter>
            </defs>
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
                    className: 'copy-icon',
                });
                textbox.copyIcon = copyIcon;

                // Handle copy icon click
                copyIcon.on('mousedown', function() {
                    console.log('Copy icon clicked');
                    cloneTextbox(textbox);
                });

                canvas.add(copyIcon);
            });

            // Bind the updateIconPositions function to the moving and scaling events
            textbox.on('moving', function() {
                updateIconPositions(textbox);
            });
            textbox.on('scaling', function() {
                updateIconPositions(textbox);
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

            // Initially hide all icons
            canvas.getObjects('textbox').forEach(function(tb) {
                if (tb.trashIcon) tb.trashIcon.set('visible', false);
                if (tb.copyIcon) tb.copyIcon.set('visible', false);
            });

            canvas.renderAll(); // Final render
        }

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
                addToUndoStack(); // Save state after updating properties
            }
        }



        // document.getElementById('fontSize').addEventListener('change', updateSelectedTextProperties);
        // document.getElementById('fontColor').addEventListener('input', updateSelectedTextProperties);







        canvas.on('mouse:down', function(options) {
            if (options.target && options.target.type === 'textbox') {
                canvas.setActiveObject(options.target);
            } else {
                // alert();
                canvas.getObjects('textbox').forEach(function(tb) {
                    if (tb.trashIcon) tb.trashIcon.set('visible', false);
                    if (tb.copyIcon) tb.copyIcon.set('visible', false);
                });
            }
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
                transparentCorners: false
            });

            textbox.on('scaling', function() {
                // Update the font size based on scaling
                var updatedFontSize = textbox.fontSize * (textbox.scaleX + textbox.scaleY) / 2;
                textbox.set('fontSize', updatedFontSize);
                canvas.renderAll();
            });

            canvas.add(textbox);
            canvas.setActiveObject(textbox);
            addIconsToTextbox(textbox); // Make it the active object for editing
            canvas.renderAll();
        }


        // function saveTextDataToDatabase() {

        //     // Hide the text elements
        //     var textData = getTextDataFromCanvas();
        //     var imageURL = canvas.toDataURL('image/png');
        //     var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Get CSRF token
        //     // Get the canvas ID to associate the saved data with a specific record
        //     var canvasElement = document.getElementById('imageEditor1');
        //     var canvasId = canvasElement.getAttribute('data-canvas-id');
        //     var imageName = 'image_' + Date.now() + '.png';

        //     var id = $('#template_id').val();
        //     const width = userImageElement.clientWidth;
        //     const height = userImageElement.clientHeight;
        //     const left = imageWrapper.offsetLeft;
        //     const top = imageWrapper.offsetTop;
        //     const centerX = left + width / 2;
        //     const centerY = top + height / 2;

        //     console.log(canvasId);
        //     console.log(textData);
        //     shapeImageData = [];


        //     fetch('/saveTextData', {
        //             method: 'POST',
        //             headers: {
        //                 'Content-Type': 'application/json', // Set content type to JSON
        //                 'X-CSRF-TOKEN': csrfToken // Include CSRF token
        //             },
        //             body: JSON.stringify({
        //                 id: canvasId,
        //                 textElements: textData,
        //                 shape: shape,
        //                 centerX: centerX,
        //                 centerY: centerY,
        //                 width: width,
        //                 height: height
        //             })
        //         })
        //         .then(response => response.json())
        //         .then(data => {
        //             console.log('Text data saved successfully', data);
        //             // window.location.href = "{{URL::to('/admin/create_template')}}";

        //         })
        //         .catch((error) => {
        //             console.error('Error:', error);
        //         });
        //     hideStaticTextElements();
        //     showStaticTextElements();
        // }

        function saveTextDataToDatabase() {

            // hideStaticTextElements(); 
            var textData = getTextDataFromCanvas();
            var imageURL = canvas.toDataURL('image/png');
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Get CSRF token
            var canvasElement = document.getElementById('imageEditor1');
            var canvasId = canvasElement.getAttribute('data-canvas-id');
            var imageName = 'image_' + Date.now() + '.png';



            var id = $('#template_id').val();
            const width = userImageElement.clientWidth;
            const height = userImageElement.clientHeight;
            const left = imageWrapper.offsetLeft;
            const top = imageWrapper.offsetTop;
            const centerX = left + width / 2;
            const centerY = top + height / 2;

            // fetch('/saveTextData', {
            //         method: 'POST',
            //         headers: {
            //             'X-CSRF-TOKEN': csrfToken // Include CSRF token
            //         },
            //         body: canvasId
            //     })
            //     .then(response => response.json())
            //     .then(data => {

            //     })
            //     .catch((error) => {
            //         console.error('Error:', error);
            //     });
            var shapeImageData = [];

            shapeImageData.push({
                shape: shape,
                centerX: centerX,
                centerY: centerY,
                width: width,
                height: height
            });
            console.log(shapeImageData);
            console.log(textData);
            // $.ajax({
            //     headers: {
            //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            //     },
            //     url: base_url + "/saveTextData",
            //     type: "POST",
            //     dataType: 'json', // Expect a JSON response
            //     data: {
            //         id: canvasId, // Send as an object, not JSON string
            //         textElements: textData,
            //         shapeImageData: shapeImageData,
            //     },
            //     success: function(response) {
            //         console.log('Text data saved successfully', response);
            //         window.location.href = "{{URL::to('/admin/create_template')}}";
            //     },
            //     error: function(xhr, status, error) {
            //         console.error("Failed Not Saved Data:", error);
            //     },
            // });

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




        // document.getElementById('antaresiaButton').addEventListener('click', function() {
        //     console.log("fontname")
        //     loadAndUse("Antaresia");
        // });
        // document.getElementById('JosefinSansButton').addEventListener('click', function() {
        //     console.log("fontname");
        //     loadAndUse("JosefinSans-Regular");


        // });
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
            console.log(font);
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
                        loadAndUse(font);
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
                addToUndoStack(); // Save state for undo/redo functionality
            }
        }


        // function executeCommand(command) {
        //     var activeObject = canvas.getActiveObject();
        //     if (!activeObject) {
        //         alert('No object selected');
        //         return;
        //     }
        //     if (activeObject && activeObject.type === 'textbox') {

        //         const commands = {
        //             bold: () => activeObject.set('fontWeight', activeObject.fontWeight === 'bold' ? '' : 'bold'),
        //             italic: () => activeObject.set('fontStyle', activeObject.fontStyle === 'italic' ? '' : 'italic'),
        //             underline: () => {
        //                 activeObject.set('underline', !activeObject.underline);
        //                 // Update line height after toggling underline
        //                 const currentLineHeight = activeObject.lineHeight || 1.2; // Default line height
        //                 activeObject.set('lineHeight', currentLineHeight); // Reapply the line height
        //             },
        //             setLineHeight: (value) => {
        //                 activeObject.set('lineHeight', value);
        //             },
        //             strikeThrough: () => activeObject.set('linethrough', !activeObject.linethrough),
        //             removeFormat: () => {
        //                 activeObject.set({
        //                     fontWeight: '',
        //                     fontStyle: '',
        //                     underline: false,
        //                     linethrough: false,
        //                     fontFamily: 'Arial'
        //                 });
        //             },
        //             fontName: () => {
        //                 var selectedFont = document.querySelector('[data-command="fontName"]').value;
        //                 activeObject.set('fontFamily', selectedFont);
        //             },

        //             justifyLeft: () => activeObject.set('textAlign', 'left'),
        //             justifyCenter: () => activeObject.set('textAlign', 'center'),
        //             justifyRight: () => activeObject.set('textAlign', 'right'),
        //             justifyFull: () => activeObject.set('textAlign', 'justify'),

        //             uppercase: () => activeObject.set('text', activeObject.text.toUpperCase()),
        //             lowercase: () => activeObject.set('text', activeObject.text.toLowerCase()),
        //             capitalize: () => {
        //                 const capitalizedText = activeObject.text.replace(/\b\w/g, char => char.toUpperCase());
        //                 activeObject.set('text', capitalizedText);
        //             }

        //         };
        //         if (commands[command]) {
        //             commands[command]();
        //             canvas.renderAll();
        //             addToUndoStack(); // Save state after executing the command
        //         }
        //     }
        // }

        document.querySelectorAll('[data-command]').forEach(function(button) {
            button.addEventListener('click', function() {
                executeCommand(this.getAttribute('data-command'));
            });
        });
        // document.getElementById('fontFamilySelect').addEventListener('change', function () {
        //     executeCommand('fontName');
        // });


        // document.getElementById('dateFormatSelect').addEventListener('change', function () {
        //     // Get the selected date format
        //     var selectedFormat = this.value;

        //     // Get the active object from the canvas (assuming it's a textbox)
        //     var activeObject = canvas.getActiveObject();
        //     if (activeObject && activeObject.type === 'textbox') {
        //         // Extract the date text from the active object
        //         var dateText = activeObject.text;

        //         // Optional: You can use regex to identify a date within the text if it's not the whole text
        //         // Assuming the whole text is the date for simplicity
        //         var formattedDate = formatDate(dateText, selectedFormat);

        //         // Update the active object's text with the formatted date
        //         activeObject.set('text', formattedDate);
        //         activeObject.set('date_formate', selectedFormat);
        //         canvas.renderAll(); // Re-render the canvas
        //     }
        // });

        // function formatDate(dateStr, format) {
        //     // Parse the existing date (e.g., in YYYY-MM-DD format)
        //     var dateParts = dateStr.split(/[-\/]/); // Adjust the regex if date separators are different

        //     var year, month, day;
        //     if (dateParts.length === 3) {
        //         if (dateParts[0].length === 4) {
        //             // Assume YYYY-MM-DD format
        //             year = dateParts[0];
        //             month = dateParts[1];
        //             day = dateParts[2];
        //         } else {
        //             // Assume DD-MM-YYYY or MM-DD-YYYY
        //             day = dateParts[0];
        //             month = dateParts[1];
        //             year = dateParts[2];
        //         }
        //     } else {
        //         return dateStr; // Return original text if not a valid date
        //     }

        //     // Reformat date based on the selected format
        //     switch (format) {
        //         case 'd-m-Y':
        //             return `${day}-${month}-${year}`;
        //         case 'Y-m-d':
        //             return `${year}-${month}-${day}`;
        //         case 'm/d/Y':
        //             return `${month}/${day}/${year}`;
        //         case 'd/m/Y':
        //             return `${day}/${month}/${year}`;
        //         default:
        //             return dateStr; // Return original if no valid format is found
        //     }
        // }


        // Undo and Redo actions (basic implementation)
        let undoStack = [];
        let redoStack = [];

        function addToUndoStack() {
            undoStack.push(canvas.toJSON());
            console.log(undoStack);
            redoStack = []; // Clear redo stack on new action
        }

        function undo() {

            if (undoStack.length >= 0) {
                redoStack.push(canvas.toJSON());
                canvas.loadFromJSON(undoStack.pop(), canvas.renderAll.bind(canvas));
            }
        }

        function redo() {
            if (redoStack.length >= 0) {
                undoStack.push(canvas.toJSON());
                canvas.loadFromJSON(redoStack.pop(), canvas.renderAll.bind(canvas));
            }
        }

        document.querySelector('[data-command="undo"]').addEventListener('click', undo);
        document.querySelector('[data-command="redo"]').addEventListener('click', redo);

        // Remove formatting
        // document.querySelector('[data-command="removeFormat"]').addEventListener('click', function () {
        //     executeCommand('removeFormat');
        // });

        // Delete all textboxes
        // document.getElementById('deleteAllBtn').addEventListener('click', function () {
        //     canvas.getObjects('textbox').forEach(function (textbox) {
        //         canvas.remove(textbox);

        //     });
        //     canvas.renderAll();
        // });


        // Add event listener for the button (assuming your button has id="saveButton")
        // document.getElementById('saveButton').addEventListener('click', function() {
        //     // alert()

        //     // saveTextDataToDatabase();
        //     // window.location.href = '/templates/view';
        // });

        const fileInput = document.getElementById('fileInput');
        const userImageElement = document.getElementById('user_image');
        const imageWrapper = document.getElementById('imageWrapper');
        const canvasElement = new fabric.Canvas('imageEditor', {
            width: 500, // Canvas width
            height: 500, // Canvas height
        });

        const resizeHandles = {
            topLeft: document.querySelector('.resize-handle.top-left'),
            topRight: document.querySelector('.resize-handle.top-right'),
            bottomLeft: document.querySelector('.resize-handle.bottom-left'),
            bottomRight: document.querySelector('.resize-handle.bottom-right')
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
                }
                userImageElement.style.width = `${newWidth}px`;
                userImageElement.style.height = `${newHeight}px`;
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
                const shapes = ['rectangle', 'circle', 'star', 'rounded-border', 'heart'];
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

                        const imgElement = new Image();
                        imgElement.src = data.imagePath;
                        imgElement.addClass = 'image-element-prakash';

                        userImageElement.src = data.imagePath;
                        imageWrapper.style.display = 'block';

                        imgElement.onload = function() {
                            console.log("Image loaded successfully.");
                            const imgInstance = new fabric.Image(imgElement, {
                                left: 0,
                                top: 0,
                                selectable: true,
                                hasControls: true,
                                hasBorders: true,
                                // cornerColor: 'red',
                                cornerStrokeColor: 'blue',
                                // borderColor: 'blue',
                                borderColor: '#2DA9FC',
                                // cornerColor: 'red',
                                cornerColor: '#fff',
                                cornerSize: 10,
                                transparentCorners: false,
                                lockUniScaling: true,
                                scaleX: 600 / imgElement.width,
                                scaleY: 600 / imgElement.height
                            });

                            canvasElement.add(imgInstance);
                            canvasElement.bringToFront(imgInstance);
                            drawCanvas();
                            console.log('Image loaded and added to canvas.');
                            imageUploaded = true; // Set flag to true after image is uploaded
                        };

                        imgElement.onerror = function() {
                            console.error("Failed to load image.");
                        };
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error);
                    });
            }
        });

        document.getElementById('saveButton').addEventListener('click', function() {
            // alert();
            saveTextDataToDatabase();
            // var id = $('#template_id').val();
            // const width = userImageElement.clientWidth;
            // const height = userImageElement.clientHeight;
            // const left = imageWrapper.offsetLeft;
            // const top = imageWrapper.offsetTop;
            // const centerX = left + width / 2;
            // const centerY = top + height / 2;
            // if (imageUploaded) {
            //     fetch(`/save_shape/${id}`, {
            //             method: 'POST',
            //             headers: {
            //                 'Content-Type': 'application/json',
            //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
            //                     .getAttribute('content')
            //             },
            //             body: JSON.stringify({
            //                 shape: shape,
            //                 centerX: centerX,
            //                 centerY: centerY,
            //                 width: width,
            //                 height: height
            //             })
            //         })
            //         .then(response => {
            //             alert(response);
            //             if (!response.ok) {
            //                 throw new Error('Network response was not ok');
            //             }
            //             return response.json();
            //         })
            //         .then(data => {
            //             console.log('Shape saved successfully:', data);
            //         })
            //         .catch(error => {
            //             console.error('There was a problem with saving the shape:', error);
            //         });
            // } else {
            //     // alert("Please upload an image before saving the shape.");
            // }
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


    //  =========================
</script>