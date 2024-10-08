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
                                    textElement.setControlsVisibility({
                                        mt: false, 
                                        mb: false,
                                        bl: true, 
                                        br: true, 
                                        tl: true, 
                                        tr: true,
                                        ml: true, 
                                        mr: true  
                                    });
                                    


                                 
                                    canvas.add(textElement);
                                    console.log(textElement);
                                    canvas.renderAll();

                                    // Event Listener to get and update the fontSize during dragging/moving
                                    canvas.on('object:scaling', function (e) {
                                        var activeObject = e.target;

                                        // Check if the scaled object is the textbox
                                        if (activeObject && activeObject.type === 'textbox') {
                                            // Get the current font size
                                            var currentFontSize = activeObject.fontSize;
                                            console.log("Current font size: " + currentFontSize);

                                            // Calculate new font size based on scale factor
                                            var newFontSize = currentFontSize * activeObject.scaleX; // Adjust the font size based on the horizontal scaling factor
                                            const textMeasurement = new fabric.Text(activeObject.text, {
                                                fontSize: newFontSize,
                                                fontFamily: element.fontFamily,
                                                fontWeight: element.fontWeight,
                                                fontStyle: element.fontStyle,
                                                underline: element.underline,
                                                linethrough: element.linethrough,
                                            });
                                            const textWidth = textMeasurement.width;
                                            // Set the new font size and reset scale
                                            activeObject.set({
                                                fontSize: newFontSize,
                                                scaleX: 1, // Reset scaleX to 1 to prevent cumulative scaling
                                                scaleY: 1,  // Reset scaleY to 1 if you want to keep uniform scaling
                                                width: textWidth
                                            });

                                            // Re-render the canvas to apply the changes
                                            canvas.renderAll();

                                            console.log("Updated font size: " + newFontSize);
                                        }
                                    });                                    

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


        var deleteIcon = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEkAAABJCAYAAABxcwvcAAAACXBIWXMAACE4AAAhOAFFljFgAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAX9SURBVHgB7ZxPiBtVHMe/Kz12N7kJFpfZk2Jduz3Ug1aILohlBRs9FCrYnAQF2e7NomhyEPdQWFewBT00Uix60N0FS0GoCbT10B5a3YrVS6Zb22uTtPfX329mXvZldiYzSea9ZJJ+4G2SmclM5ru/3+/93p95wGMimcCAEEJk6SVHxaKyj0rWe5/1HWorr7ep3OAyMTFhwxDGRPJEmaPyFpXDcAXpB5tKlcoGv5JodaQVEidHZYXKfaGXM3wtpAn6wQUqFWGeGl8bw4xwLacmBk9NDJtY9IMsMRjLiaJGxcKgoR+xKPTHnH75HIOALpylsibSw3XRg1X1nAJ4F6ug/6rcNDaVPKUMN+J+4Qn0AAnE+c51pE8gxqJSoXs4FvcLXVuSJxBbUBbpp0AW9X3UQV2JNGICSSKFii1SimNQFNycebVTjIol0ggLJGGh9oc1muMGbs4xLIwuHD7WwnZGikRWtEgvBYw+c3SvK0E7Orqb52Y1jBccn6rqhihLWsH4cca/IVQk4bagD2P84Ib6cXVDqLvRgexmFgxx+Y9r2LpzN3Df9NN7cPClAzAI13YzsrdzV9ARnhVZMMDWnXt48+1CqEASFuqH8teY3fssDMC1HVtTkT8EWpJJK3rhwOuRAklYqEsXf0ZmahIGaFnTDksSbj+xBQNs/v1vSyC+8YVD84HHnb/wOxrNpnPs5s1bplyPrYljcjnI3WK3jvtFtSC+8VOrXwQed2JqGae/O+u837z5j8n4xFoEimSsRrt85Wrr/fT0HrKsW2g0HjifM5lJsq4pdx+5mYStzyDcX59ti0m0gQVaQ0I0mg9w7sd1bP1/17l5thxpPRywe4XFy2R2t8TjV1fUSRw9kqfPTyFBlvwicSJVQEK8Mv+OYx0mYcH+uvYbEqTqd7ccDML/ebYAiepWbHkcrFXiWZ9Awsy1LEm4w9D3kSDsWud+WvfcY9IRYdtFencJdmPpvvI6UtSFN+Yx+3yyuZQqUg5un9FjfKhttzlohF3l3cJHWD75DZLi9LdnnWzdWNwjS/pKaOTjT78UmSefc8qlK1dFv9Trzdb5FvLHhE5US9oHjXAckcRthnTCZK3Z07jbuKGKZEEjam2WhCWp59Dd4N0FQ8gmBqO6HsMBOMp9Zvc+gw/efy/43Jkp6MScSErSKNtn8v2Jz5ZjnePgyy+2+pNUS0q4GbKDoYhJcW7Syc7N9CPtQLUkrRMz1SZHWzwhC/v1l7LTum80mqHf574mVSS1iaKeWweqSDY0JpRq3PCLoTZX4qLGNd0WprpbAxrJTO3evlDzIfpFFdpk4OYJA9p6JdtqtwC34m5Z2fvoZ+HQa07DdVD43U0bbbWbLwVgPlz8JDQNOH/hIm7/1y7SoGq32NPjeqVTrqSKuON7AfsajYfKfkPuxtNOuN0IjRO0uMtVdqSxy6kBl2s4HqAMIqh/SO2QM51x83MamuOSW3UHuVzcURA1GTVA1Z9MVqGRsKy7W1Qr0p0jERt+kdahMakMSyi7RU0h1NRCE+ttInkTBLQF8PaMuXeReICydU69Qdt5ri6ogVuCplETblrIXGj55CmnadFt9e2O5W20Ph89onUsdZX/GJ8wkeRYHAvMtaKmuGTDnWxaD+sFiJwA3is8fSaJsXzNAjGtpzHDLIlzJbYmbTkTN0M4L/IPQEbBaQTnTQYmTczIKcudZroVEDB/cEwokUBF+SFq9i0PVuYwXtjwYpHcENUzWcL4UfI/Gd5RJG8+8yrGhzLdc9m/Me6zJfxsm9Zh8CHAhjvR3fbviDsQkIfm/qYBI59UsoN2xhLJ+3IemgcLBshSp2U9Yg8pec+DLWH0KAXFob4Q7soRo0IRuhCjIVQRuqGL8PNhNZE+eJGHQjf32tfSQCJ9j53a6HJNAKavuQBcI1CZQToyc+7Z2N+tQIkihme1Gz81MWzrKtEPOi6GQyyOPUXhdvsMH8JdJoh/YE2YZ7jFCUKYW5GrIlwrTu/qF8K1rqQFqwjXaixoZJBLKObg9ixY6LyEYt0rtlf+xPYyikbako8ADsJE9Bg3pI8AAAAASUVORK5CYII=";
        var img1 = document.createElement('img');
        img1.src = deleteIcon;

        var copyIcon = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADEAAAAxCAYAAABznEEcAAAACXBIWXMAABYlAAAWJQFJUiTwAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAMnSURBVHgB3Zq/axRBFMe/J7bm0gmKZtMFNRhBG1HYgHaCv0rRnIVCGqOdKYIGCyMIiY2CTQ6s1QgGLQwXMaTQIqeXkFjtoVibu/wBz/dud8ze5k5uZ2bv9vzAy/7I7t58982PN/M2g4QgIoc3vYEpNtnKmUxmExbJwAJcYCnoENt5NpfNQX3hoxTZymwf2eZYVBmdggvvsk2z/SYzVthyaCdB4QtkH4/tHpKEf6CX/DefNB7bBdiGHzoUPLyd2PMKP2yEzOu9LtJeHJggb4M6j0e6QvjGMUoPHvld+Q4y/xDg8qaAdFHkMeVY9OSuRleS77pZpA/pXKajJxt6gi8UD7hIL8PskUV1sMMT5I+cLtLNbLh9NKpOyY6YdnDYbquDuuoUeCFWW6hUt1Ba3UBcTp08AUMkEu6XiDgqwoOvsiXm3y/gSu4WdBg8MoBPH17CkDssYma3Ogq6VAcxePb8hV+gwwPIZve0fF9p9XvNe6W1jdq9Bkjovy2CGYEmDx/cjVU9zl3KYWn5CyqVLRgi0XRfuGG76BDiEQMu1kSwGpmVOegQ4xNTOH3mMjQ5qjzhoMOoNqKBq0QMIQVothEnNZ4wQYnIootRInrRxSgRVhez2o0SUUEX8194QoUdRRhQWluPdb2FcCPMppEIiZckBhqfeAQdDh7YBwsUlYgy/CoVq5cavXENMiVZWv4c5zZke3owevMqi9gPC3z9u0fJrK22xODxs5Tde4i+ra6TBm44FH8Dw0hWYp849b1SrdbmJD9+/qpVLY25heQ6FsMi8vDn19oD3/y7BUw9foq4iIC3r/LQYFH+RKenM7wZgwEiotU2Im1DpqnSPrI9rc8MQ8gcuxwV4fDGQ3eQZwHXZaduySZIOz1BdzCpdhqtO91H+kfwyXCer9kypmRpXiOdSI/UHz7RcEGZL5pDOquV1JDhWHdQBwfAJsTP45GfaFyhdJCDLpQOITnYgB80Q+3HI39NzB7yRqh9aeACNcnR2RDisOUpOTzyF7eTJwExBWr39x0RMbmgEHET9h75bc2FAVY+FQpD23kOsb7Iv2VVpRxY0dYnQn8AxkwzSD/FREQAAAAASUVORK5CYII=";
        var img2 = document.createElement('img');
        img2.src = copyIcon;
    
        
        fabric.Textbox.prototype.controls.mtr = new fabric.Control({
            x: 0,
            y: 0.5,
            offsetY: 20,
            cursorStyle: 'pointer',
            actionHandler: fabric.controlsUtils.rotationWithSnapping,
            actionName: 'rotate',
            render: renderIcon,
            cornerSize: 28,
        });
        
        fabric.Textbox.prototype.controls.deleteControl = new fabric.Control({
            x: 0.3,
            y: -0.5,
            offsetY: -20,
            cursorStyle: 'pointer',            
            actionHandler: (eventData, transform, x, y) => {
                console.log(eventData)
                 const target = transform.target;
                 canvas.remove(target); // Remove object on trash icon click
                 canvas.requestRenderAll();
            },
            mouseUpHandler: deleteTextbox,
            render: renderDeleteIcon,
            cornerSize: 28,
            withConnection: false // Disable the line connection
        });


        fabric.Textbox.prototype.controls.copyControl = new fabric.Control({
            x: -0.3,
            y: -0.5,
            offsetY: -20,
            cursorStyle: 'pointer', 
            mouseUpHandler: cloneTextbox,
            render: renderCopyIcon,
            cornerSize: 28,
            withConnection: false // Disable the line connection
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

        function renderDeleteIcon(ctx, left, top, styleOverride, fabricObject) {
            var size = this.cornerSize;
            ctx.save();
            ctx.translate(left, top);
            ctx.rotate(fabric.util.degreesToRadians(fabricObject.angle));
            ctx.drawImage(img1, -size / 2, -size / 2, size, size);
            ctx.restore();
        }
        function renderCopyIcon(ctx, left, top, styleOverride, fabricObject) {
            var size = this.cornerSize;
            ctx.save();
            ctx.translate(left, top);
            ctx.rotate(fabric.util.degreesToRadians(fabricObject.angle));
            ctx.drawImage(img2, -size / 2, -size / 2, size, size);
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



$(".removeShapImage").click(function(){
    $("#imageWrapper").hide();
    $("#user_image").attr("src","");
    $('.photo-slider-wrp').show()

})
       
        let updateTimeout; // Variable to store the timeout reference


   



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
                updateTextboxWidth(activeObject);

                 // Update the textbox width to fit the default settings
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

        let clrcanvas = {}
        setTimeout(function(){
            let spchoose = document.getElementsByClassName('sp-choose');
            console.log({spchoose})
                $(spchoose).click(function(){
                // alert('clicked')
                setTimeout(function(){
                    console.log({clrcanvas})
                    undoStack.push(clrcanvas);
          
                    if(undoStack.length > 0){
                        $('#undoButton').find('svg path').attr('fill', '#0F172A');
                    }
                    redoStack = []; // Clear redo stack on new action
                },1000)
            })
        },1000)
       
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
                clrcanvas = canvas.toJSON(); // Store the current state of the canvas

                console.log({clrcanvas})
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

        canvas.on('mouse:down', function (event) {
            const activeObject = canvas.getActiveObject();
            if (activeObject && activeObject.type === 'textbox') {
                updateColorPicker();
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

            text.on('scaling', function() {
                var updatedFontSize = text.fontSize * (text.scaleX + text.scaleY) / 2;
                console.log(updatedFontSize);
                text.set('fontSize', updatedFontSize);
                canvas.renderAll();
                // findTextboxCenter(text);
            });

            // text.on('moving', function() {
            //     findTextboxCenter(text);
            // });
            canvas.add(text);
            
            canvas.renderAll();
            // findTextboxCenter(text);
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

  
  


    // Function to add icons to a new textbox
   
   
        // Function to delete a textbox
        function deleteTextbox() {            
            canvas.remove(canvas.getActiveObject());           
            canvas.renderAll();
        }


        // Function to clone a textbox
        function cloneTextbox() {
            let originalTextbox = canvas.getActiveObject()
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

            clonedTextbox.setControlsVisibility({
                mt: false, // Hide middle top control
                mb: false, // Hide middle bottom control
                bl: true, // Hide bottom left control
                br: true, // Hide bottom right control
                tl: true, // Hide top left control
                tr: true, // Hide top right control
                ml: true,  // Show middle left control
                mr: true   // Show middle right control
            });

            canvas.add(clonedTextbox);

            // Add icons to the cloned textbox

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
                // width: 100,
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
            textbox.setControlsVisibility({
                mt: false, // Hide middle top control
                mb: false, // Hide middle bottom control
                bl: true, // Hide bottom left control
                br: true, // Hide bottom right control
                tl: true, // Hide top left control
                tr: true, // Hide top right control
                ml: true,  // Show middle left control
                mr: true   // Show middle right control
            });

            textbox.on('scaling', function() {
                // Update the font size based on scaling
                var updatedFontSize = textbox.fontSize * (textbox.scaleX + textbox.scaleY) / 2;
                textbox.set('fontSize', updatedFontSize);
                canvas.renderAll();
            });

            canvas.add(textbox);
            canvas.setActiveObject(textbox);
            
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
                mr: true   
            });
            
            obj.set('transparentCorners', false);
            obj.set('borderColor', "#2DA9FC");
            obj.set('cornerSize', 6);
            obj.set('cornerColor', "#fff");
            // Set text alignment if the object is a text-based object
            if (obj.type === 'textbox' || obj.type === 'text') {
                obj.set('textAlign', 'center');  // Set text alignment to center
            }
        });    
        canvas.renderAll();
    }
    function addToUndoStack(canvas) {          
        undoStack.push(canvas.toJSON());          
        if(undoStack.length > 0){
            $('#undoButton').find('svg path').attr('fill', '#0F172A');
        }
        redoStack = [];        
    }

    function undo() {        
        if (undoStack.length > 0) {  // Ensure at least one previous state exists
           
            redoStack.push(canvas.toJSON()); // Save current state to redo stack
            const lastState = undoStack.pop(); // Get the last state to undo
            canvas.loadFromJSON(lastState, function () {

                canvas.renderAll(); // Render the canvas after loading state
              
            });            
            if(redoStack.length > 0){
                $('#redoButton').find('svg path').attr('fill', '#0F172A');  
            }
            setTimeout(function(){
                setControlVisibilityForAll()
            },1000)
        }else{
            $('#undoButton').find('svg path').attr('fill', '#CBD5E1');  
        }
    }

    function redo() {
        if (redoStack.length > 0) {
          
            undoStack.push(canvas.toJSON()); // Save current state to undo stack
            const nextState = redoStack.pop(); // Get the next state to redo
            canvas.loadFromJSON(nextState, function () {
                canvas.renderAll(); // Render the canvas after loading state
               
            });
            if(undoStack.length > 0 ){
                $('#undoButton').find('svg path').attr('fill', '#0F172A');
            }
            $('#redoButton').find('svg path').attr('fill', '#0F172A');  
            setControlVisibilityForAll()
        }else{
            $('#redoButton').find('svg path').attr('fill', '#CBD5E1');  
        }
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