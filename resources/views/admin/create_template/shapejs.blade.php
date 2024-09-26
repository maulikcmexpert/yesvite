<script type="text/javascript">



// document.addEventListener("DOMContentLoaded", function() {
    //     const fileInput = document.getElementById('fileInput');
    //     const userImageElement = document.getElementById('user_image');
    //     const imageWrapper = document.getElementById('imageWrapper');
    //     const canvasElement = new fabric.Canvas('imageEditor', {
    //         width: 800,
    //         height: 600
    //     });

    //     const resizeHandles = {
    //         topLeft: document.querySelector('.resize-handle.top-left'),
    //         topRight: document.querySelector('.resize-handle.top-right'),
    //         bottomLeft: document.querySelector('.resize-handle.bottom-left'),
    //         bottomRight: document.querySelector('.resize-handle.bottom-right')
    //     };

    //     let isDragging = false;
    //     let isResizing = false;
    //     let startWidth, startHeight, startX, startY, activeHandle;
    //     let offsetX, offsetY;
    //     let shape = 'rectangle'; // Default shape

    //     function startResize(event, handle) {
    //         isResizing = true;
    //         startWidth = userImageElement.clientWidth;
    //         startHeight = userImageElement.clientHeight;
    //         startX = event.clientX;
    //         startY = event.clientY;
    //         activeHandle = handle;
    //         event.stopPropagation();
    //     }

    //     function resize(event) {
    //         if (isResizing) {
    //             let newWidth, newHeight;
    //             if (activeHandle === resizeHandles.bottomRight) {
    //                 newWidth = startWidth + (event.clientX - startX);
    //                 newHeight = startHeight + (event.clientY - startY);
    //             } else if (activeHandle === resizeHandles.bottomLeft) {
    //                 newWidth = startWidth - (event.clientX - startX);
    //                 newHeight = startHeight + (event.clientY - startY);
    //                 imageWrapper.style.left = `${event.clientX}px`;
    //             } else if (activeHandle === resizeHandles.topRight) {
    //                 newWidth = startWidth + (event.clientX - startX);
    //                 newHeight = startHeight - (event.clientY - startY);
    //                 imageWrapper.style.top = `${event.clientY}px`;
    //             } else if (activeHandle === resizeHandles.topLeft) {
    //                 newWidth = startWidth - (event.clientX - startX);
    //                 newHeight = startHeight - (event.clientY - startY);
    //                 imageWrapper.style.left = `${event.clientX}px`;
    //                 imageWrapper.style.top = `${event.clientY}px`;
    //             }
    //             userImageElement.style.width = `${newWidth}px`;
    //             userImageElement.style.height = `${newHeight}px`;
    //         }
    //     }

    //     function handleMouseDown(event) {
    //         if (event.target.classList.contains('resize-handle')) {
    //             startResize(event, event.target);
    //         } else {
    //             event.preventDefault(); // Prevent default behavior during dragging (text selection)
    //             isDragging = true;
    //             offsetX = event.clientX - imageWrapper.offsetLeft;
    //             offsetY = event.clientY - imageWrapper.offsetTop;
    //         }
    //     }

    //     function handleMouseMove(event) {
    //         if (isDragging) {
    //             imageWrapper.style.left = `${event.clientX - offsetX}px`;
    //             imageWrapper.style.top = `${event.clientY - offsetY}px`;
    //         } else if (isResizing) {
    //             resize(event);
    //         }
    //     }

    //     function handleMouseUp(event) {
    //         if (event.target === userImageElement) {
    //             // Cycle through shapes
    //             const shapes = ['rectangle', 'circle', 'star', 'rounded-border', 'heart'];
    //             const currentIndex = shapes.indexOf(shape);
    //             shape = shapes[(currentIndex + 1) % shapes.length];
    //             console.log(`Shape changed to: ${shape}`); // Log shape change

    //             drawCanvas();
    //         }

    //         isDragging = false;
    //         isResizing = false;
    //     }

    //     function drawCanvas() {
    //         // Clear previous clipping path
    //         userImageElement.style.clipPath = '';

    //         switch (shape) {
    //             case 'rectangle':
    //                 // No clip path needed for rectangle
    //                 break;
    //             case 'circle':
    //                 userImageElement.style.clipPath = 'circle(50% at 50% 50%)';
    //                 break;
    //             case 'star':
    //                 userImageElement.style.clipPath = 'polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)';
    //                 break;
    //             case 'rounded-border':
    //                 userImageElement.style.clipPath = 'inset(0 round 20px)';
    //                 break;
    //             case 'heart':
    //                 userImageElement.style.clipPath = 'url(#heartClipPath)';
    //                 break;
    //             default:
    //                 break;
    //         }
    //     }

    //     fileInput.addEventListener('change', function(event) {
    //         const file = event.target.files[0];
    //         if (file) {
    //             const formData = new FormData();
    //             formData.append('image', file);

    //             fetch('/user_image', {
    //                 method: 'POST',
    //                 body: formData,
    //                 headers: {
    //                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //                 }
    //             })
    //             .then(response => {
    //                 if (!response.ok) {
    //                     throw new Error('Network response was not ok');
    //                 }
    //                 return response.json();
    //             })
    //             .then(data => {
    //                 console.log('Server response:', data);

    //                 const imgElement = new Image();
    //                 imgElement.src = data.imagePath;

    //                 userImageElement.src = data.imagePath;
    //                 imageWrapper.style.display = 'block';

    //                 imgElement.onload = function() {
    //                     console.log("Image loaded successfully.");
    //                     console.log("Image width: ", imgElement.width);
    //                     console.log("Image height: ", imgElement.height);

    //                     const imgInstance = new fabric.Image(imgElement, {
    //                         left: 0,
    //                         top: 0,
    //                         selectable: true,
    //                         hasControls: true,
    //                         hasBorders: true,
    //                         cornerColor: 'red',
    //                         cornerStrokeColor: 'blue',
    //                         borderColor: 'blue',
    //                         cornerSize: 10,
    //                         transparentCorners: false,
    //                         lockUniScaling: true,
    //                         scaleX: 600 / imgElement.width,
    //                         scaleY: 600 / imgElement.height
    //                     });

    //                     canvasElement.add(imgInstance);
    //                     drawCanvas(); // Draw the default rectangle around the image
    //                     console.log('Image loaded and added to canvas.');
    //                 };

    //                 imgElement.onerror = function() {
    //                     console.error("Failed to load image.");
    //                 };
    //             })
    //             .catch(error => {
    //                 console.error('There was a problem with the fetch operation:', error);
    //             });
    //         }
    //     });

    //     Object.values(resizeHandles).forEach(handle => {
    //         handle.addEventListener('mousedown', function(event) {
    //             startResize(event, handle);
    //         });
    //     });

    //     imageWrapper.addEventListener('mousedown', handleMouseDown);
    //     document.addEventListener('mousemove', handleMouseMove);
    //     document.addEventListener('mouseup', handleMouseUp);
    // });





    // document.addEventListener("DOMContentLoaded", function() {
    //     const fileInput = document.getElementById('fileInput');
    //     const userImageElement = document.getElementById('user_image');
    //     const imageWrapper = document.getElementById('imageWrapper');
    //     const canvasElement = new fabric.Canvas('imageEditor', { // Changed ID to 'imageEditor1'
    //         width: 800,
    //         height: 600
    //     });

    //     const resizeHandles = {
    //         topLeft: document.querySelector('.resize-handle.top-left'),
    //         topRight: document.querySelector('.resize-handle.top-right'),
    //         bottomLeft: document.querySelector('.resize-handle.bottom-left'),
    //         bottomRight: document.querySelector('.resize-handle.bottom-right')
    //     };

    //     let isDragging = false;
    //     let isResizing = false;
    //     let startWidth, startHeight, startX, startY, activeHandle;
    //     let offsetX, offsetY;
    //     let shape = 'rectangle'; // Default shape

    //     function startResize(event, handle) {
    //         isResizing = true;
    //         startWidth = userImageElement.clientWidth;
    //         startHeight = userImageElement.clientHeight;
    //         startX = event.clientX;
    //         startY = event.clientY;
    //         activeHandle = handle;
    //         event.stopPropagation();
    //     }

    //     function resize(event) {
    //         if (isResizing) {
    //             let newWidth, newHeight;
    //             if (activeHandle === resizeHandles.bottomRight) {
    //                 newWidth = startWidth + (event.clientX - startX);
    //                 newHeight = startHeight + (event.clientY - startY);
    //             } else if (activeHandle === resizeHandles.bottomLeft) {
    //                 newWidth = startWidth - (event.clientX - startX);
    //                 newHeight = startHeight + (event.clientY - startY);
    //                 imageWrapper.style.left = `${event.clientX}px`;
    //             } else if (activeHandle === resizeHandles.topRight) {
    //                 newWidth = startWidth + (event.clientX - startX);
    //                 newHeight = startHeight - (event.clientY - startY);
    //                 imageWrapper.style.top = `${event.clientY}px`;
    //             } else if (activeHandle === resizeHandles.topLeft) {
    //                 newWidth = startWidth - (event.clientX - startX);
    //                 newHeight = startHeight - (event.clientY - startY);
    //                 imageWrapper.style.left = `${event.clientX}px`;
    //                 imageWrapper.style.top = `${event.clientY}px`;
    //             }
    //             userImageElement.style.width = `${newWidth}px`;
    //             userImageElement.style.height = `${newHeight}px`;
    //         }
    //     }

    //     function handleMouseDown(event) {


    //         const canvas = document.querySelector('.new'); // Use 'querySelector' with a capital 'S'

    //         // if (canvas !== null) {  // Check for null
    //         //     alert(canvas);
    //         // } else {
    //         //     alert(0);
    //         // }



    //         const canvasRect = canvas.getBoundingClientRect();

    //         if (event.target.classList.contains('resize-handle')) {
    //             startResize(event, event.target);
    //         } else {
    //             event.preventDefault(); // Prevent default behavior during dragging (text selection)
    //             isDragging = true;
    //             offsetX = event.clientX - imageWrapper.offsetLeft;
    //             offsetY = event.clientY - imageWrapper.offsetTop;
    //         }
    //     }

    //     function handleMouseMove(event) {
    //         if (isDragging) {

    //             const canvas = document.querySelector('.new'); // Use 'querySelector' with a capital 'S'

    //             // if (canvas !== null) {  // Check for null
    //             //     alert(canvas);
    //             // } else {
    //             //     alert(0);
    //             // }



    //             const canvasRect = canvas.getBoundingClientRect();
    //             let newX = event.clientX - offsetX;
    //             let newY = event.clientY - offsetY;

    //             // Ensure the image stays within the canvas boundaries
    //             if (newX < canvasRect.left) newX = canvasRect.left;
    //             if (newX + userImageElement.clientWidth > canvasRect.right)
    //                 newX = canvasRect.right - userImageElement.clientWidth;
    //             if (newY < canvasRect.top) newY = canvasRect.top;
    //             if (newY + userImageElement.clientHeight > canvasRect.bottom)
    //                 newY = canvasRect.bottom - userImageElement.clientHeight;

    //             imageWrapper.style.left = `${newX}px`;
    //             imageWrapper.style.top = `${newY}px`;
    //         } else if (isResizing) {
    //             resize(event);
    //         }
    //     }

    //     function handleMouseUp(event) {
    //         if (event.target === userImageElement) {
    //             // Cycle through shapes
    //             const shapes = ['rectangle', 'circle', 'star', 'rounded-border', 'heart'];
    //             const currentIndex = shapes.indexOf(shape);
    //             shape = shapes[(currentIndex + 1) % shapes.length];
    //             console.log(`Shape changed to: ${shape}`); // Log shape change

    //             drawCanvas();
    //         }

    //         isDragging = false;
    //         isResizing = false;
    //     }

    //     function drawCanvas() {
    //         // Clear previous clipping path
    //         userImageElement.style.clipPath = '';

    //         switch (shape) {
    //             case 'rectangle':
    //                 break;
    //             case 'circle':
    //                 userImageElement.style.clipPath = 'circle(50% at 50% 50%)';
    //                 break;
    //             case 'star':
    //                 userImageElement.style.clipPath = 'polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)';
    //                 break;
    //             case 'rounded-border':
    //                 userImageElement.style.clipPath = 'inset(0 round 20px)';
    //                 break;
    //             case 'heart':
    //                 userImageElement.style.clipPath = 'url(#heartClipPath)';
    //                 break;
    //             default:
    //                 break;
    //         }
    //     }

    //     fileInput.addEventListener('change', function(event) {
    //         const file = event.target.files[0];
    //         if (file) {
    //             const formData = new FormData();
    //             formData.append('image', file);

    //             fetch('/user_image', {
    //                 method: 'POST',
    //                 body: formData,
    //                 headers: {
    //                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //                 }
    //             })
    //             .then(response => {
    //                 if (!response.ok) {
    //                     throw new Error('Network response was not ok');
    //                 }
    //                 return response.json();
    //             })
    //             .then(data => {
    //                 console.log('Server response:', data);

    //                 const imgElement = new Image();
    //                 imgElement.src = data.imagePath;

    //                 userImageElement.src = data.imagePath;
    //                 imageWrapper.style.display = 'block';

    //                 imgElement.onload = function() {
    //                     console.log("Image loaded successfully.");
    //                     console.log("Image width: ", imgElement.width);
    //                     console.log("Image height: ", imgElement.height);

    //                     const imgInstance = new fabric.Image(imgElement, {
    //                         left: 0,
    //                         top: 0,
    //                         selectable: true,
    //                         hasControls: true,
    //                         hasBorders: true,
    //                         cornerColor: 'red',
    //                         cornerStrokeColor: 'blue',
    //                         borderColor: 'blue',
    //                         cornerSize: 10,
    //                         transparentCorners: false,
    //                         lockUniScaling: true,
    //                         scaleX: 600 / imgElement.width,
    //                         scaleY: 600 / imgElement.height
    //                     });

    //                     canvasElement.add(imgInstance);
    //                     drawCanvas();
    //                     console.log('Image loaded and added to canvas.');
    //                 };

    //                 imgElement.onerror = function() {
    //                     console.error("Failed to load image.");
    //                 };
    //             })
    //             .catch(error => {
    //                 console.error('There was a problem with the fetch operation:', error);
    //             });
    //         }
    //     });

    //     Object.values(resizeHandles).forEach(handle => {
    //         handle.addEventListener('mousedown', function(event) {
    //             startResize(event, handle);
    //         });
    //     });

    //     imageWrapper.addEventListener('mousedown', handleMouseDown);
    //     document.addEventListener('mousemove', handleMouseMove);
    //     document.addEventListener('mouseup', handleMouseUp);
    // });





    document.addEventListener("DOMContentLoaded", function() {
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
                    userImageElement.style.clipPath = 'polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)';
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

        // Include shape information in the form data
        formData.append('shape', shape); // Send the current shape value

        fetch(`/user_image/${id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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


        Object.values(resizeHandles).forEach(handle => {
            handle.addEventListener('mousedown', function(event) {
                startResize(event, handle);
            });
        });

        imageWrapper.addEventListener('mousedown', handleMouseDown);
        document.addEventListener('mousemove', handleMouseMove);
        document.addEventListener('mouseup', handleMouseUp);
    });
    </script>
