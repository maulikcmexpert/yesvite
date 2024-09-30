<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {


        // Initialize fabric canvas
        var canvas = new fabric.Canvas('imageEditor1', {
            width: 345, // Canvas width
            height: 490, // Canvas height



        });

        function loadTextDataFromDatabase() {
            var id = $('#template_id').val();
            // let urlParams = new URLSearchParams(window.location.search);


            // let id = urlParams.get('id');


            fetch(`/loadTextData/${id}`) // API endpoint to load data from your database
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        console.log(data);

                        // Load background image
                        fabric.Image.fromURL(data.imagePath, function(img) {
                            img.set({
                                left: 0,
                                top: 0,
                                selectable: false, // Non-draggable background image
                                hasControls: false // Disable resizing controls
                            });
                            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));

                        });

                        // Load static information (text elements)
                        if (data.static_information) {
                            hideStaticTextElements(); // Hide static text elements if static information is present
                            const staticInfo = JSON.parse(data.static_information);
                            console.log(staticInfo);

                            // Render text elements on canvas
                            staticInfo.textElements.forEach(element => {
                                let textElement = new fabric.Textbox(element.text, { // Use Textbox for editable text
                                    left: element.left,
                                    top: element.top,
                                    width: element.width || 200, // Default width if not provided
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

                                textElement.on('scaling', function() {
                                    // Calculate the updated font size based on scaling factors
                                    var updatedFontSize = textElement.fontSize * (textElement.scaleX + textElement.scaleY) / 2;
                                    textElement.set('fontSize', updatedFontSize); // Update the font size
                                    canvas.renderAll(); // Re-render the canvas to reflect changes
                                });

                                addIconsToTextbox(textElement);
                                canvas.add(textElement);

                            });
                        } else {
                            showStaticTextElements();
                        }

                        // Set custom attribute with the fetched ID
                        var canvasElement = document.getElementById('imageEditor1');
                        canvasElement.setAttribute('data-canvas-id', data.id);

                        canvas.renderAll(); // Ensure all elements are rendered
                    }
                })
                .catch(error => console.error('Error loading text data:', error));
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
            setFontSize();
        });

        letterSpacingRange.addEventListener('input', setLetterSpacing);
        letterSpacingInput.addEventListener('input', () => {
            letterSpacingRange.value = letterSpacingInput.value;
            setLetterSpacing();
        });

        lineHeightRange.addEventListener('input', setLineHeight);
        lineHeightInput.addEventListener('input', () => {
            lineHeightRange.value = lineHeightInput.value;
            setLineHeight();
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
                textAlign: 'left',
                fill: '#000', // Optional: Reset text color
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

        // Initialize the color picker
        $('#color-picker').spectrum({
            type: "flat",
            color: "#000000", // Default font color
            showInput: true,
            allowEmpty: true, // Allows setting background to transparent
            showAlpha: true, // Allows transparency adjustment
            preferredFormat: "rgba", // Ensure it handles RGBA
            change: function(color) {
                if (color) {
                    changeColor(color.toRgbString()); // Use RGB string for color changes
                } else {
                    changeColor('rgba(0, 0, 0, 0)'); // Handle transparency by default
                }
            }
        });

        // Change event for radio buttons to update the color picker
        $('input[name="colorType"]').on('change', function() {
            updateColorPicker(); // Update color picker when radio button changes
        });

        // Update color picker when object is selected
        canvas.on('selection:created', function() {
            updateColorPicker(); // Update color picker when a new object is selected
        });

        canvas.on('selection:updated', function() {
            updateColorPicker(); // Update color picker when selection is updated
        });

        // Function to change font or background color
        function changeColor(selectedColor) {
            const selectedColorType = document.querySelector('input[name="colorType"]:checked').value;
            const activeObject = canvas.getActiveObject();

            if (!activeObject) {
                // alert('No object selected');
                return;
            }

            if (activeObject.type === 'textbox') {
                if (selectedColorType === 'font') {
                    activeObject.set('fill', selectedColor); // Change font color
                } else if (selectedColorType === 'background') {
                    activeObject.set('backgroundColor', selectedColor); // Change background color
                }
                canvas.renderAll(); // Re-render the canvas after color change
            }
        }

        // Function to update the color picker based on the selected object's current font or background color
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
            }
        }

        // Update the color picker when the color type changes (font or background)
        $('input[name="colorType"]').change(function() {
            const activeObject = canvas.getActiveObject();
            if (activeObject && activeObject.type === 'textbox') {
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
            var text = new fabric.Textbox(textContent, {
                left: left,
                top: top,
                fontSize: 20,
                backgroundColor: 'rgba(0, 0, 0, 0)', // Set background to transparent
                fill: '#000000', // Default text color (black)
                editable: true,
                selectable: true,
                isStatic: false,
                visible: true,
                hasControls: true
            })

            // Approximate width based on text length
            text.set('width', text.get('text').length * 10);

            // Event listener for scaling
            text.on('scaling', function() {
                var updatedFontSize = text.fontSize * (text.scaleX + text.scaleY) / 2;
                text.set('fontSize', updatedFontSize);
                canvas.renderAll();
                findTextboxCenter(text); // Find center when scaling
            });

            // Event listener for moving
            text.on('moving', function() {
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
            // Calculate the center coordinates of the textbox
            var centerX = textbox.left + (textbox.width / 2);
            var centerY = textbox.top + (textbox.height / 2);

            console.log(`Center of textbox '${textbox.text}' is at (${centerX}, ${centerY})`);

            // Optional: You can return or store this center value for further use
            return {
                x: centerX,
                y: centerY
            };
        }

        function updateIconPositions(textbox) {

            if (textbox.trashIcon) {
                canvas.remove(textbox.trashIcon);
                textbox.trashIcon = null; // Clear reference
                const trashIconSVG = `<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 50 50"><path d="M20,30 L30,30 L30,40 L20,40 Z M25,10 L20,10 L20,7 L30,7 L30,10 Z M17,10 L33,10 L33,40 L17,40 Z" fill="#FF0000"/></svg>`;
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


                    // Ensure the copyIcon is on top
                    canvas.bringToFront(trashIcon);
                    textbox.trashIcon.on('mousedown', function() {
                        console.log('deleted icon');
                        deleteTextbox(textbox);
                    });
                })
                // console.log('Updated Trash Icon Position:', textbox.trashIcon.left, textbox.trashIcon.top);
            }

            if (textbox.copyIcon) {
                canvas.remove(textbox.copyIcon);
                textbox.copyIcon = null; // Clear reference
            }


            const copyIconSVG = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"  x="0px" y="0px" width="20" height="20"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M384 336l-192 0c-8.8 0-16-7.2-16-16l0-256c0-8.8 7.2-16 16-16l140.1 0L400 115.9 400 320c0 8.8-7.2 16-16 16zM192 384l192 0c35.3 0 64-28.7 64-64l0-204.1c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1L192 0c-35.3 0-64 28.7-64 64l0 256c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64L0 448c0 35.3 28.7 64 64 64l192 0c35.3 0 64-28.7 64-64l0-32-48 0 0 32c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16l0-256c0-8.8 7.2-16 16-16l32 0 0-48-32 0z"/></svg>`;
            fabric.loadSVGFromString(copyIconSVG, function(objects, options) {
                let copyIcon = fabric.util.groupSVGElements(objects, options);
                copyIcon.set({
                    left: textbox.left - 25,
                    top: textbox.top - 20,
                    selectable: false,
                    evented: true,
                    hasControls: false,
                    visible: true, // Initially hidden
                    className: 'copy-icon',
                });
                // Add the copyIcon to the canvas
                textbox.copyIcon = copyIcon

                // Ensure the copyIcon is on top
                canvas.bringToFront(copyIcon);

                // Handle copy icon click
                textbox.copyIcon.on('mousedown', function() {
                    console.log('Copy icon clicked1');
                    cloneTextbox(textbox);
                });
            })
            // console.log('Updated Copy Icon Position:', textbox.copyIcon.left, textbox.copyIcon.top);


            canvas.renderAll(); // Re-render the canvas to apply the new positions
        }

        // Function to add icons to a textbox
        function addIconsToTextbox(textbox) {
            // Trash icon SVG
            const trashIconSVG = `<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 50 50"><path d="M20,30 L30,30 L30,40 L20,40 Z M25,10 L20,10 L20,7 L30,7 L30,10 Z M17,10 L33,10 L33,40 L17,40 Z" fill="#FF0000"/></svg>`;
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
            const copyIconSVG = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"  x="0px" y="0px" width="20" height="20"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M384 336l-192 0c-8.8 0-16-7.2-16-16l0-256c0-8.8 7.2-16 16-16l140.1 0L400 115.9 400 320c0 8.8-7.2 16-16 16zM192 384l192 0c35.3 0 64-28.7 64-64l0-204.1c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1L192 0c-35.3 0-64 28.7-64 64l0 256c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64L0 448c0 35.3 28.7 64 64 64l192 0c35.3 0 64-28.7 64-64l0-32-48 0 0 32c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16l0-256c0-8.8 7.2-16 16-16l32 0 0-48-32 0z"/></svg>`;
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
                lockScalingFlip: true
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
        // addDraggableText(350, 50, 'event_name', 'xyz'); // Position this outside the image area
        // addDraggableText(350, 100, 'host_name', 'abc');
        // addDraggableText(350, 150, 'start_time', '5:00PM');
        // addDraggableText(350, 200, 'rsvp_end_time', '6:00PM');
        // addDraggableText(350, 250, 'start_date', '2024-07-27');
        // addDraggableText(350, 300, 'end_date', '2024-07-27');
        // addDraggableText(350, 350, 'Location', 'fdf');



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
            }
        });

        function getTextDataFromCanvas() {
            var objects = canvas.getObjects();

            var textData = [];

            objects.forEach(function(obj) {
                if (obj.type === 'textbox') {
                    var centerX = obj.left + (obj.width / 2);
                    var centerY = obj.top + (obj.height / 2);
                    textData.push({
                        text: obj.text,
                        left: obj.left,
                        top: obj.top,
                        fontSize: obj.fontSize,
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


        function saveTextDataToDatabase() {

            hideStaticTextElements(); // Hide the text elements
            var textData = getTextDataFromCanvas();
            var imageURL = canvas.toDataURL('image/png');
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Get CSRF token
            // Get the canvas ID to associate the saved data with a specific record
            var canvasElement = document.getElementById('imageEditor1');
            var canvasId = canvasElement.getAttribute('data-canvas-id');
            var imageName = 'image_' + Date.now() + '.png';
            console.log(canvasId);
            fetch('/saveTextData', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json', // Set content type to JSON
                        'X-CSRF-TOKEN': csrfToken // Include CSRF token
                    },
                    body: JSON.stringify({
                        id: canvasId,
                        textElements: textData,

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
            showStaticTextElements();
        }








        function executeCommand(command) {
            var activeObject = canvas.getActiveObject();
            if (!activeObject) {
                alert('No object selected');
                return;
            }
            if (activeObject && activeObject.type === 'textbox') {

                const commands = {
                    bold: () => activeObject.set('fontWeight', activeObject.fontWeight === 'bold' ? '' : 'bold'),
                    italic: () => activeObject.set('fontStyle', activeObject.fontStyle === 'italic' ? '' : 'italic'),
                    underline: () => {
                        activeObject.set('underline', !activeObject.underline);
                        // Update line height after toggling underline
                        const currentLineHeight = activeObject.lineHeight || 1.2; // Default line height
                        activeObject.set('lineHeight', currentLineHeight); // Reapply the line height
                    },
                    setLineHeight: (value) => {
                        activeObject.set('lineHeight', value);
                    },
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
                    fontName: () => {
                        var selectedFont = document.querySelector('[data-command="fontName"]').value;
                        activeObject.set('fontFamily', selectedFont);
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
                if (commands[command]) {
                    commands[command]();
                    canvas.renderAll();
                    addToUndoStack(); // Save state after executing the command
                }
            }
        }

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

        // document.querySelector('[data-command="undo"]').addEventListener('click', undo);
        // document.querySelector('[data-command="redo"]').addEventListener('click', redo);

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
        document.getElementById('saveButton').addEventListener('click', function() {

            saveTextDataToDatabase();
            // window.location.href = '/templates/view';
        });

    });



    $(document).on("click", ".design-sidebar-action", function() {
        let designId = $(this).attr('design-id')
        if (designId) {
            $(".design-sidebar").addClass('d-none')
            $(".design-sidebar_" + designId).removeClass('d-none')
            $('.close-btn').attr('data-id', "design-sidebar_" + designId);
        }
    })
    //  =========================
</script>