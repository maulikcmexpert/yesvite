<script type="text/javascript">
;

    document.addEventListener('DOMContentLoaded', function() {
        const originalWidth = 345;
        const originalHeight = 490;
        let element = document.querySelector(".image-edit-inner-img");
        if (element) {
            var { width, height } = element.getBoundingClientRect();
            console.log("Width:", width, "Height:", height);
        } else {
            var { width, height } = { width: 590, height: 880 };
            console.log(width, height); // Output: 590 880

            console.log("Element not found!");
        }

        const scaleX = width / originalWidth;
        const scaleY = height / originalHeight;

        // Initialize fabric canvas
        var canvas = new fabric.Canvas('imageEditor1', {
            width: width, // Canvas width
            height: height, // Canvas height
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
                        console.log(data);
                        var canvasElement = document.getElementById('imageEditor1');
                        canvasElement.setAttribute('data-canvas-id', data.id);
                        // Load background image (imagePath)
                        if (data.imagePath) {

                        fabric.Image.fromURL(data.imagePath, function (img) {
                            img.crossOrigin = "anonymous";
                            var canvasWidth = canvas.getWidth();
                            var canvasHeight = canvas.getHeight();

                            // Use Math.max to ensure the image covers the entire canvas
                            var scaleFactor = Math.max(
                                canvasWidth / img.width,
                                canvasHeight / img.height
                            );

                            img.set({
                                left: (canvasWidth - img.width * scaleFactor) / 2, // Centering horizontally
                                top: (canvasHeight - img.height * scaleFactor) / 2, // Centering vertically
                                scaleX: scaleFactor,
                                scaleY: scaleFactor,
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
                    }

                        if (data.static_information) {
                            const staticInfo = JSON.parse(data.static_information);
                            let element = staticInfo?.shapeImageData;

                            // console.log(element)
                            if (element != undefined) {


                                if (element.shape != undefined && element.centerX != undefined && element
                                    .centerY != undefined && element.height != undefined && element.width !=
                                    undefined) {
                                    console.log(element.shape);
                                    // shape = element.shape;
                                    // centerX = element.centerX;
                                    // centerY = element.centerY;
                                    // height = element.height;
                                    // width = element.width;

                                    // updatedOBJImage = {
                                    //     shape: shape,
                                    //     centerX: element.centerX,
                                    //     centerY: element.centerY,
                                    //     width: element.height,
                                    //     height: element.width
                                    // };
                                    // updateClipPath(data.filedImagePath, element);

                                    ////////////////
                                    // const imageInput = document.getElementById('image1');
                                    const scaledWidth = element.width; // Use element's width
                                    const scaledHeight = element.height;
                                    // imageInput.style.width = element.width + 'px';
                                    // imageInput.style.height = element.height + 'px';

                                    let currentImage = null; // Variable to hold the current image
                                    let isScaling = false; // Flag to check if the image is scaling
                                    let currentShapeIndex = 0; // Index to track the current shape

                                    const defaultShape = element.shape; // Set the desired default shape here

                                    const shapeIndexMap = {
                                        'rectangle': 0,
                                        'circle': 1,
                                        'triangle': 2,
                                        'star': 3
                                    };

                                    function createShapes(img) {

                                        const imgWidth = img.width;
                                        const imgHeight = img.height;
                                        const starScale = Math.min(imgWidth, imgHeight) / 2;
                                        const starPoints = [{
                                                x: 0,
                                                y: -starScale
                                            }, // Top point
                                            {
                                                x: starScale * 0.23,
                                                y: -starScale * 0.31
                                            }, // Top-right
                                            {
                                                x: starScale,
                                                y: -starScale * 0.31
                                            }, // Right
                                            {
                                                x: starScale * 0.38,
                                                y: starScale * 0.12
                                            }, // Bottom-right
                                            {
                                                x: starScale * 0.58,
                                                y: starScale
                                            }, // Bottom
                                            {
                                                x: 0,
                                                y: starScale * 0.5
                                            }, // Center-bottom
                                            {
                                                x: -starScale * 0.58,
                                                y: starScale
                                            }, // Bottom-left
                                            {
                                                x: -starScale * 0.38,
                                                y: starScale * 0.12
                                            }, // Top-left
                                            {
                                                x: -starScale,
                                                y: -starScale * 0.31
                                            }, // Left
                                            {
                                                x: -starScale * 0.23,
                                                y: -starScale * 0.31
                                            } // Top-left
                                        ];

                                        return [
                                            new fabric.Rect({
                                                width: imgWidth,
                                                height: imgHeight,
                                                originX: 'center',
                                                originY: 'center',
                                                angle: 0
                                            }),
                                            new fabric.Circle({
                                                radius: Math.min(imgWidth, imgHeight) / 2,
                                                originX: 'center',
                                                originY: 'center',
                                                angle: 0
                                            }),
                                            new fabric.Triangle({
                                                width: imgWidth,
                                                height: imgHeight,
                                                originX: 'center',
                                                originY: 'center',
                                                angle: 0
                                            }),
                                            new fabric.Polygon(starPoints, {
                                                originX: 'center',
                                                originY: 'center',
                                                angle: 0
                                            })
                                        ];
                                    }
                                    $("#shape_img").attr("src", data.filedImagePath);

                                    fabric.Image.fromURL(data.filedImagePath, function(img) {
                                        if (!img) {
                                            console.error('Image could not be loaded.');
                                            return;
                                        }
                                        var filedimage = data.filedImagePath;
                                        console.log({
                                            filedimage
                                        });

                                        img.set({

                                            selectable: true,
                                            hasControls: true,

                                            hasBorders: false,
                                            borderColor: "#2DA9FC",
                                            cornerColor: "#fff",
                                            transparentCorners: false,
                                            lockUniScaling: true,
                                            scaleX: scaledWidth / img.width ||
                                                1, // Default to 1 if img.width is undefined
                                            scaleY: scaledHeight / img.height || 1,
                                            // scaleX: scaledWidth / img
                                            //     .width, // Scale based on element's width
                                            // scaleY: scaledHeight / img
                                            //     .height, // Scale based on element's height
                                            cornerSize: 10,
                                            cornerStyle: 'circle',
                                            left: element.centerX - scaledWidth /
                                                2, // Center the image horizontally
                                            top: element.centerY - scaledHeight / 2
                                        });

                                        let shapes = createShapes(img);

                                        currentShapeIndex = shapeIndexMap[defaultShape] ||
                                            0; // Default to rectangle if not found

                                        img.set({
                                            clipPath: shapes[currentShapeIndex]
                                        });
                                        img.crossOrigin = "anonymous";

                                        img.on('mouseup', function(event) {
                                            console.log(event);
                                            if (event?.transform?.action === 'drag' && event
                                                .transform.actionPerformed === undefined) {
                                                currentShapeIndex = (currentShapeIndex + 1) %
                                                    shapes.length;
                                                img.set({
                                                    clipPath: shapes[currentShapeIndex]
                                                });
                                                canvas.renderAll();

                                            }
                                        });

                                        const fixClipPath = () => {
                                            img.set({
                                                clipPath: shapes[currentShapeIndex]
                                            });
                                            canvas.renderAll();
                                        };

                                        img.on('scaling', function(event) {
                                            const target = event.target;
                                            if (target && target.isControl) {
                                                fixClipPath();
                                            }
                                        });
                                        fabric.Image.prototype.controls.deleteControl = new fabric
                                            .Control({
                                                x: 0.3,
                                                y: -0.5,
                                                offsetY: -20,
                                                cursorStyle: 'pointer',
                                                actionHandler: (eventData, transform, x, y) => {
                                                    console.log(eventData)
                                                    const target = transform.target;
                                                    canvas.remove(
                                                        target
                                                    ); // Remove object on trash icon click
                                                    canvas.requestRenderAll();
                                                },
                                                mouseUpHandler: deleteTextbox,
                                                render: renderDeleteIcon,
                                                cornerSize: 28,
                                                withConnection: false // Disable the line connection
                                            });

                                        // canvas.renderAll();
                                        canvas.add(img);
                                        currentImage = img;
                                        canvas.renderAll();
                                    });


                                    const fileInput = document.getElementById('fileInput');

                                    fileInput.addEventListener('change', function(event) {
                                        const file = event.target.files[0];
                                        if (file) {
                                            const reader = new FileReader();

                                            reader.onload = function() {
                                                // Set the image source
                                                $("#shape_img").attr("src", reader.result);

                                            };

                                            reader.readAsDataURL(file);

                                            // AJAX call to upload the image and shape data
                                            const formData = new FormData();
                                            formData.append('image',
                                                file); // Append the file from file input
                                            formData.append('shape',
                                                shape
                                            ); // Append shape data (assuming `shape` is defined)

                                            const id = $('#template_id')
                                                .val(); // Assuming you have a template ID

                                            // Send the form data via AJAX
                                            fetch(`/user_image/${id}`, {
                                                    method: 'POST',
                                                    body: formData,
                                                    headers: {
                                                        'X-CSRF-TOKEN': document.querySelector(
                                                                'meta[name="csrf-token"]')
                                                            .getAttribute('content')
                                                    }
                                                })
                                                .then(response => {
                                                    if (!response.ok) {
                                                        throw new Error(
                                                            'Network response was not ok');
                                                    }
                                                    return response.json();
                                                })
                                                .then(data => {
                                                    updatedOBJImage = {
                                                        shape: 'rectangle',
                                                        width: 100,
                                                        height: 100
                                                    };
                                                    fabric.Image.fromURL(data.imagePath,
                                                        function(img) {
                                                            console.log('Server response:',
                                                                img);
                                                            var filedimage = data.imagePath;
                                                            // console.log({
                                                            //     filedimage
                                                            // });

                                                            img.set({

                                                                selectable: true,
                                                                hasControls: true,
                                                                // hasControls: false,
                                                                hasBorders: false,
                                                                borderColor: "#2DA9FC",
                                                                cornerColor: "#fff",
                                                                transparentCorners: false,
                                                                lockUniScaling: true,
                                                                // scaleX:10, // Scale based on element's width
                                                                // scaleY:10, // Scale based on element's height
                                                                cornerSize: 10,
                                                                cornerStyle: 'circle',
                                                                left: 0, // Center the image horizontally
                                                                top: 0
                                                            });

                                                            let shapes = createShapes(img);

                                                            currentShapeIndex = shapeIndexMap[
                                                                    defaultShape] ||
                                                                0; // Default to rectangle if not found

                                                            img.set({
                                                                clipPath: shapes[
                                                                    currentShapeIndex
                                                                ]
                                                            });
                                                            img.crossOrigin = "anonymous";

                                                            img.on('mouseup', function(event) {
                                                                console.log(event);
                                                                if (event?.transform
                                                                    ?.action ===
                                                                    'drag' && event
                                                                    .transform
                                                                    .actionPerformed ===
                                                                    undefined) {
                                                                    currentShapeIndex =
                                                                        (currentShapeIndex +
                                                                            1) %
                                                                        shapes.length;
                                                                    img.set({
                                                                        clipPath: shapes[
                                                                            currentShapeIndex
                                                                        ]
                                                                    });
                                                                    canvas.renderAll();

                                                                }
                                                            });

                                                            const fixClipPath = () => {
                                                                img.set({
                                                                    clipPath: shapes[
                                                                        currentShapeIndex
                                                                    ]
                                                                });
                                                                canvas.renderAll();
                                                            };

                                                            img.on('scaling', function(event) {
                                                                const target = event
                                                                    .target;
                                                                if (target && target
                                                                    .isControl) {
                                                                    fixClipPath();
                                                                }
                                                            });
                                                            fabric.Image.prototype.controls
                                                                .deleteControl = new fabric
                                                                .Control({
                                                                    x: 0.3,
                                                                    y: -0.5,
                                                                    offsetY: -20,
                                                                    cursorStyle: 'pointer',
                                                                    actionHandler: (
                                                                        eventData,
                                                                        transform, x, y
                                                                    ) => {
                                                                        console.log(
                                                                            eventData
                                                                        )
                                                                        const target =
                                                                            transform
                                                                            .target;
                                                                        canvas.remove(
                                                                            target
                                                                        ); // Remove object on trash icon click
                                                                        canvas
                                                                            .requestRenderAll();
                                                                    },
                                                                    mouseUpHandler: deleteTextbox,
                                                                    render: renderDeleteIcon,
                                                                    cornerSize: 28,
                                                                    withConnection: false // Disable the line connection
                                                                });

                                                            // canvas.renderAll();
                                                            canvas.add(img);
                                                            currentImage = img;
                                                        });
                                                    // updateClipPath(data.imagePath, updatedOBJImage);
                                                })
                                                .catch(error => {
                                                    console.error(
                                                        'There was a problem with the fetch operation:',
                                                        error);
                                                });
                                        }
                                    });


                                    /////////////////

                                }
                            } else {
                                const defaultShape = 'rectangle'; // Set the desired default shape here

                                const shapeIndexMap = {
                                    'rectangle': 0,
                                    'circle': 1,
                                    'triangle': 2,
                                    'star': 3
                                };

                                function createShapes(img) {

                                    const imgWidth = img.width;
                                    const imgHeight = img.height;
                                    const starScale = Math.min(imgWidth, imgHeight) / 2;
                                    const starPoints = [{
                                            x: 0,
                                            y: -starScale
                                        }, // Top point
                                        {
                                            x: starScale * 0.23,
                                            y: -starScale * 0.31
                                        }, // Top-right
                                        {
                                            x: starScale,
                                            y: -starScale * 0.31
                                        }, // Right
                                        {
                                            x: starScale * 0.38,
                                            y: starScale * 0.12
                                        }, // Bottom-right
                                        {
                                            x: starScale * 0.58,
                                            y: starScale
                                        }, // Bottom
                                        {
                                            x: 0,
                                            y: starScale * 0.5
                                        }, // Center-bottom
                                        {
                                            x: -starScale * 0.58,
                                            y: starScale
                                        }, // Bottom-left
                                        {
                                            x: -starScale * 0.38,
                                            y: starScale * 0.12
                                        }, // Top-left
                                        {
                                            x: -starScale,
                                            y: -starScale * 0.31
                                        }, // Left
                                        {
                                            x: -starScale * 0.23,
                                            y: -starScale * 0.31
                                        } // Top-left
                                    ];

                                    return [
                                        new fabric.Rect({
                                            width: imgWidth,
                                            height: imgHeight,
                                            originX: 'center',
                                            originY: 'center',
                                            angle: 0
                                        }),
                                        new fabric.Circle({
                                            radius: Math.min(imgWidth, imgHeight) / 2,
                                            originX: 'center',
                                            originY: 'center',
                                            angle: 0
                                        }),
                                        new fabric.Triangle({
                                            width: imgWidth,
                                            height: imgHeight,
                                            originX: 'center',
                                            originY: 'center',
                                            angle: 0
                                        }),
                                        new fabric.Polygon(starPoints, {
                                            originX: 'center',
                                            originY: 'center',
                                            angle: 0
                                        })
                                    ];
                                }
                                const fileInput = document.getElementById('fileInput');

                                fileInput.addEventListener('change', function(event) {
                                    const file = event.target.files[0];
                                    if (file) {
                                        const reader = new FileReader();

                                        reader.onload = function() {
                                            // Set the image source
                                            $("#shape_img").attr("src", reader.result);

                                        };

                                        reader.readAsDataURL(file);

                                        // AJAX call to upload the image and shape data
                                        const formData = new FormData();
                                        formData.append('image',
                                            file); // Append the file from file input
                                        formData.append('shape',
                                            shape
                                        ); // Append shape data (assuming `shape` is defined)

                                        const id = $('#template_id')
                                            .val(); // Assuming you have a template ID

                                        // Send the form data via AJAX
                                        fetch(`/user_image/${id}`, {
                                                method: 'POST',
                                                body: formData,
                                                headers: {
                                                    'X-CSRF-TOKEN': document.querySelector(
                                                            'meta[name="csrf-token"]')
                                                        .getAttribute('content')
                                                }
                                            })
                                            .then(response => {
                                                if (!response.ok) {
                                                    throw new Error(
                                                        'Network response was not ok');
                                                }
                                                return response.json();
                                            })
                                            .then(data => {
                                                updatedOBJImage = {
                                                    shape: 'rectangle',
                                                    width: 40,
                                                    height: 40
                                                };
                                                fabric.Image.fromURL(data.imagePath,
                                                    function(img) {
                                                        console.log('Server response:',
                                                            img);
                                                        var filedimage = data.imagePath;
                                                        // console.log({
                                                        //     filedimage
                                                        // });

                                                        img.set({

                                                            selectable: true,
                                                            hasControls: true,
                                                            // width:100,
                                                            // height:100,
                                                            // hasControls: false,
                                                            hasBorders: false,
                                                            borderColor: "#2DA9FC",
                                                            cornerColor: "#fff",
                                                            transparentCorners: false,
                                                            lockUniScaling: true,
                                                            // scaleX:10, // Scale based on element's width
                                                            // scaleY:10, // Scale based on element's height
                                                            cornerSize: 10,
                                                            cornerStyle: 'circle',
                                                            left: 0, // Center the image horizontally
                                                            top: 0
                                                        });

                                                        let shapes = createShapes(img);

                                                        currentShapeIndex = shapeIndexMap[
                                                                defaultShape] ||
                                                            0; // Default to rectangle if not found

                                                        img.set({
                                                            clipPath: shapes[
                                                                currentShapeIndex
                                                            ]
                                                        });
                                                        img.crossOrigin = "anonymous";

                                                        img.on('mouseup', function(event) {
                                                            console.log(event);
                                                            if (event?.transform
                                                                ?.action ===
                                                                'drag' && event
                                                                .transform
                                                                .actionPerformed ===
                                                                undefined) {
                                                                currentShapeIndex =
                                                                    (currentShapeIndex +
                                                                        1) %
                                                                    shapes.length;
                                                                img.set({
                                                                    clipPath: shapes[
                                                                        currentShapeIndex
                                                                    ]
                                                                });
                                                                canvas.renderAll();

                                                            }
                                                        });

                                                        const fixClipPath = () => {
                                                            img.set({
                                                                clipPath: shapes[
                                                                    currentShapeIndex
                                                                ]
                                                            });
                                                            canvas.renderAll();
                                                        };

                                                        img.on('scaling', function(event) {
                                                            const target = event
                                                                .target;
                                                            if (target && target
                                                                .isControl) {
                                                                fixClipPath();
                                                            }
                                                        });
                                                        fabric.Image.prototype.controls
                                                            .deleteControl = new fabric
                                                            .Control({
                                                                x: 0.3,
                                                                y: -0.5,
                                                                offsetY: -20,
                                                                cursorStyle: 'pointer',
                                                                actionHandler: (
                                                                    eventData,
                                                                    transform, x, y
                                                                ) => {
                                                                    console.log(
                                                                        eventData
                                                                    )
                                                                    const target =
                                                                        transform
                                                                        .target;
                                                                    canvas.remove(
                                                                        target
                                                                    ); // Remove object on trash icon click
                                                                    canvas
                                                                        .requestRenderAll();
                                                                },
                                                                mouseUpHandler: deleteTextbox,
                                                                render: renderDeleteIcon,
                                                                cornerSize: 28,
                                                                withConnection: false // Disable the line connection
                                                            });

                                                        // canvas.renderAll();
                                                        canvas.add(img);
                                                        currentImage = img;
                                                    });
                                                // updateClipPath(data.imagePath, updatedOBJImage);
                                            })
                                            .catch(error => {
                                                console.error(
                                                    'There was a problem with the fetch operation:', error);
                                            });
                                    }
                                });
                            }
                        } else {
                            const defaultShape = 'rectangle'; // Set the desired default shape here

                            const shapeIndexMap = {
                                'rectangle': 0,
                                'circle': 1,
                                'triangle': 2,
                                'star': 3
                            };

                            function createShapes(img) {

                                const imgWidth = img.width;
                                const imgHeight = img.height;
                                const starScale = Math.min(imgWidth, imgHeight) / 2;
                                const starPoints = [{
                                        x: 0,
                                        y: -starScale
                                    }, // Top point
                                    {
                                        x: starScale * 0.23,
                                        y: -starScale * 0.31
                                    }, // Top-right
                                    {
                                        x: starScale,
                                        y: -starScale * 0.31
                                    }, // Right
                                    {
                                        x: starScale * 0.38,
                                        y: starScale * 0.12
                                    }, // Bottom-right
                                    {
                                        x: starScale * 0.58,
                                        y: starScale
                                    }, // Bottom
                                    {
                                        x: 0,
                                        y: starScale * 0.5
                                    }, // Center-bottom
                                    {
                                        x: -starScale * 0.58,
                                        y: starScale
                                    }, // Bottom-left
                                    {
                                        x: -starScale * 0.38,
                                        y: starScale * 0.12
                                    }, // Top-left
                                    {
                                        x: -starScale,
                                        y: -starScale * 0.31
                                    }, // Left
                                    {
                                        x: -starScale * 0.23,
                                        y: -starScale * 0.31
                                    } // Top-left
                                ];

                                return [
                                    new fabric.Rect({
                                        width: imgWidth,
                                        height: imgHeight,
                                        originX: 'center',
                                        originY: 'center',
                                        angle: 0
                                    }),
                                    new fabric.Circle({
                                        radius: Math.min(imgWidth, imgHeight) / 2,
                                        originX: 'center',
                                        originY: 'center',
                                        angle: 0
                                    }),
                                    new fabric.Triangle({
                                        width: imgWidth,
                                        height: imgHeight,
                                        originX: 'center',
                                        originY: 'center',
                                        angle: 0
                                    }),
                                    new fabric.Polygon(starPoints, {
                                        originX: 'center',
                                        originY: 'center',
                                        angle: 0
                                    })
                                ];
                            }
                            const fileInput = document.getElementById('fileInput');

                            fileInput.addEventListener('change', function(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();

                                    reader.onload = function() {
                                        // Set the image source
                                        $("#shape_img").attr("src", reader.result);

                                    };

                                    reader.readAsDataURL(file);

                                    // AJAX call to upload the image and shape data
                                    const formData = new FormData();
                                    formData.append('image',
                                        file); // Append the file from file input
                                    formData.append('shape',
                                        shape
                                    ); // Append shape data (assuming `shape` is defined)

                                    const id = $('#template_id')
                                        .val(); // Assuming you have a template ID

                                    // Send the form data via AJAX
                                    fetch(`/user_image/${id}`, {
                                            method: 'POST',
                                            body: formData,
                                            headers: {
                                                'X-CSRF-TOKEN': document.querySelector(
                                                        'meta[name="csrf-token"]')
                                                    .getAttribute('content')
                                            }
                                        })
                                        .then(response => {
                                            if (!response.ok) {
                                                throw new Error(
                                                    'Network response was not ok');
                                            }
                                            return response.json();
                                        })
                                        .then(data => {
                                            updatedOBJImage = {
                                                shape: 'rectangle',
                                                width: 40,
                                                height: 40
                                            };
                                            fabric.Image.fromURL(data.imagePath,
                                                function(img) {
                                                    console.log('Server response:',
                                                        img);
                                                    var filedimage = data.imagePath;
                                                    // console.log({
                                                    //     filedimage
                                                    // });

                                                    img.set({

                                                        selectable: true,
                                                        hasControls: true,
                                                        // width:100,
                                                        // height:100,
                                                        // hasControls: false,
                                                        hasBorders: false,
                                                        borderColor: "#2DA9FC",
                                                        cornerColor: "#fff",
                                                        transparentCorners: false,
                                                        lockUniScaling: true,
                                                        // scaleX:10, // Scale based on element's width
                                                        // scaleY:10, // Scale based on element's height
                                                        cornerSize: 10,
                                                        cornerStyle: 'circle',
                                                        left: 0, // Center the image horizontally
                                                        top: 0
                                                    });

                                                    let shapes = createShapes(img);

                                                    currentShapeIndex = shapeIndexMap[
                                                            defaultShape] ||
                                                        0; // Default to rectangle if not found

                                                    img.set({
                                                        clipPath: shapes[
                                                            currentShapeIndex
                                                        ]
                                                    });
                                                    img.crossOrigin = "anonymous";

                                                    img.on('mouseup', function(event) {
                                                        console.log(event);
                                                        if (event?.transform
                                                            ?.action ===
                                                            'drag' && event
                                                            .transform
                                                            .actionPerformed ===
                                                            undefined) {
                                                            currentShapeIndex =
                                                                (currentShapeIndex +
                                                                    1) %
                                                                shapes.length;
                                                            img.set({
                                                                clipPath: shapes[
                                                                    currentShapeIndex
                                                                ]
                                                            });
                                                            canvas.renderAll();

                                                        }
                                                    });

                                                    const fixClipPath = () => {
                                                        img.set({
                                                            clipPath: shapes[
                                                                currentShapeIndex
                                                            ]
                                                        });
                                                        canvas.renderAll();
                                                    };

                                                    img.on('scaling', function(event) {
                                                        const target = event
                                                            .target;
                                                        if (target && target
                                                            .isControl) {
                                                            fixClipPath();
                                                        }
                                                    });
                                                    fabric.Image.prototype.controls
                                                        .deleteControl = new fabric
                                                        .Control({
                                                            x: 0.3,
                                                            y: -0.5,
                                                            offsetY: -20,
                                                            cursorStyle: 'pointer',
                                                            actionHandler: (
                                                                eventData,
                                                                transform, x, y
                                                            ) => {
                                                                console.log(
                                                                    eventData
                                                                )
                                                                const target =
                                                                    transform
                                                                    .target;
                                                                canvas.remove(
                                                                    target
                                                                ); // Remove object on trash icon click
                                                                canvas
                                                                    .requestRenderAll();
                                                            },
                                                            mouseUpHandler: deleteTextbox,
                                                            render: renderDeleteIcon,
                                                            cornerSize: 28,
                                                            withConnection: false // Disable the line connection
                                                        });

                                                    // canvas.renderAll();
                                                    canvas.add(img);
                                                    currentImage = img;
                                                });
                                            // updateClipPath(data.imagePath, updatedOBJImage);
                                        })
                                        .catch(error => {
                                            console.error(
                                                'There was a problem with the fetch operation:', error);
                                        });
                                }
                            });

                        }



                        // Load static information (text and shapes)
                        if (data.static_information) {
                            // hideStaticTextElements(); // Hide static text elements if static information is present
                            const staticInfo = JSON.parse(data.static_information);

                            // Render text elements or shapes on canvas
                            staticInfo.textElements.forEach(element => {

                                const textMeasurement = new fabric.Text(element.text, {
                                    fontSize: element.fontSize,
                                    fontFamily: element.fontFamily,
                                    fontWeight: element.fontWeight,
                                    fontStyle: element.fontStyle,
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
                                let fontSize = parseFloat(element.fontSize) * scaleY; // Scale font size based on height
                                fontSize = Number(fontSize).toFixed(0)
                                let width = (textWidth + 25) * scaleX; // Scale text box width

                                console.log('textAlign '+element.text,element.textAlign)
                                    if (element.text) {
                                        let textElement = new fabric.Textbox(element.text, {
                                            left: parseFloat(left),
                                            top: parseFloat(top),
                                            width: element.width || width, // Default width if not provided
                                            fontSize: fontSize,
                                            fill: element.fill,
                                            fontFamily: element.fontFamily,
                                            fontWeight: element.fontWeight,
                                            fontStyle: element.fontStyle,
                                            underline: element.underline,
                                            lineHeight: element.lineHeight || 2,
                                            letterSpacing: 0,
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

                                        // const textWidth = textElement.calcTextWidth();
                                        // textElement.set({
                                        //     width: textWidth
                                        // });
                                        // textElement.setControlsVisibility({
                                        //     mt: false,
                                        //     mb: false,
                                        //     bl: true,
                                        //     br: true,
                                        //     tl: true,
                                        //     tr: true,
                                        //     ml: true,
                                        //     mr: true
                                        // });

                                        // textElement.on('rotating', function () {
                                        //     // Get the bounding rectangle of the textboxbox
                                        //     var boundingRect = textElement.getBoundingRect();
                                        //     var centerX = boundingRect.left + boundingRect.width / 2;
                                        //     var centerY = boundingRect.top + boundingRect.height / 2;
                                        //     var rotationAngle = textElement.angle;
                                        //     console.log('Rotated Position:', { centerX: centerX, centerY: centerY, rotation: rotationAngle });
                                        // });


                                        canvas.add(textElement);


                                        // Event Listener to get and update the fontSize during dragging/moving
                                        canvas.on('object:scaling', function(e) {
                                            var activeObject = e.target;

                                            // Check if the scaled object is the textbox
                                            if (activeObject && activeObject.type ===
                                                'textbox') {
                                                // Get the current font size
                                                var currentFontSize = activeObject.fontSize;
                                                console.log("Current font size: " +
                                                    currentFontSize);

                                                // Calculate new font size based on scale factor
                                                var newFontSize = currentFontSize * activeObject
                                                    .scaleX; // Adjust the font size based on the horizontal scaling factor
                                                const textMeasurement = new fabric.Text(
                                                    activeObject.text, {
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
                                                    scaleY: 1, // Reset scaleY to 1 if you want to keep uniform scaling
                                                    width: textWidth,
                                                    textAlign: element.textAlign,
                                                });

                                                // Re-render the canvas to apply the changes
                                                canvas.renderAll();

                                                console.log("Updated font size: " +
                                                    newFontSize);
                                            }
                                        });

                                    }



                            });

                            canvas.renderAll();
                        } else {
                            showStaticTextElements();
                            addDraggableText(150, 50, 'event_name', 'xyz'); // Position this outside the image area
                            addDraggableText(150, 100, 'host_name', 'abc');
                            addDraggableText(150, 150, 'start_time', '5:00PM');
                            addDraggableText(150, 200, 'end_time', '6:00PM');
                            addDraggableText(150, 250, 'start_date', '2024-07-27');
                            addDraggableText(150, 300, 'end_date', '2024-07-27');
                            addDraggableText(150, 350, 'location_description', 'fdf');

                        }

                        // Set custom attribute with the fetched ID
                        // var canvasElement = document.getElementById('imageEditor1');
                        // canvasElement.setAttribute('data-canvas-id', data.id);

                        canvas.renderAll(); // Ensure all elements are rendered
                        setControlVisibilityForAll();
                    }
                })
                .catch(error => console.error('Error loading text data:', error));
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
            cursorStyle: 'pointer',
            actionHandler: fabric.controlsUtils.rotationWithSnapping,
            actionName: 'rotate',
            render: renderIcon,
            cornerSize: 40,
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
            cornerSize: 40,
            withConnection: false // Disable the line connection
        });


        fabric.Textbox.prototype.controls.copyControl = new fabric.Control({
            x: -0.3,
            y: -0.5,
            offsetY: -20,
            cursorStyle: 'pointer',
            mouseUpHandler: cloneTextbox,
            render: renderCopyIcon,
            cornerSize: 40,
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

            let left = element.centerX != undefined ? `${element.centerX - (element.width / 2) + canvasRect.left}px` : '50%';
            let top = element.centerX != undefined ? `${element.centerY - (element.height / 2) + canvasRect.top}px` : '50%';


            console.log({
                left
            })
            console.log({
                top
            })

            // Set the calculated position to imageWrapper
            imageWrapper.style.left = left;
            imageWrapper.style.top = top;
            imgElement.style.width = element.width + 'px';
            imgElement.style.height = element.height + 'px';

            imgElement.onload = function() {
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
                imgInstance.on('mouseup', function(options) {
                    if (options.target) {
                        // Change shape logic
                        currentShapeIndex = (currentShapeIndex + 1) % shapes.length;
                        const nextShape = shapes[currentShapeIndex];
                        element.shape = nextShape;

                        updateClipPath(data, element); // Update the image with the new shape
                    }
                });

                // Update canvas on movement or scaling
                imgInstance.on('moving', function() {
                    isImageDragging = true;
                    element.centerX = imgInstance.left;
                    element.centerY = imgInstance.top;

                    updatedOBJImage = {
                        centerX: imgInstance.left,
                        centerY: imgInstance.top,
                    };
                });

                imgInstance.on('scaling', function() {
                    element.width = imgInstance.width * imgInstance.scaleX;
                    element.height = imgInstance.height * imgInstance.scaleY;

                    updatedOBJImage = {
                        width: imgInstance.width * imgInstance.scaleX,
                        height: imgInstance.height * imgInstance.scaleY
                    };
                });

                currentImage = imgInstance; // Track current image on canvas
                oldImage = imgInstance;
                $('.photo-slider-wrp').hide()
            };

            imgElement.onerror = function(e) {
                console.error("Failed to load image.", e);
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
                        'M 50,0 L 61,35 L 98,35 L 68,57 L 79,91 L 50,70 L 21,91 L 32,57 L 2,35 L 39,35 z', {
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



        $(".removeShapImage").click(function() {
            $("#imageWrapper").hide();
            $("#user_image").attr("src", "");
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
        var updateTextBoxTime = 0;

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


        // const setLetterSpacing = () => {
        //     const newValue = parseFloat(letterSpacingRange.value); // Ensure it's a number
        //     letterSpacingInput.value = newValue;
        //     letterSpacingTooltip.innerHTML = `<span>${newValue}</span>`;

        //     const activeObject = canvas.getActiveObject();
        //     if (activeObject && activeObject.type === 'textbox') {
        //         activeObject.set('charSpacing', newValue); // Update letter spacing

        //         // Now call updateTextboxWidth to handle width adjustments
        //         updateTextboxWidth(activeObject);
        //     }
        // };


        const setLetterSpacing = () => {
            const sliderValue = parseFloat(letterSpacingRange.value); // Ensure it's a number
            const percentageValue = (sliderValue / 500) * 100; // Normalize to percentage

            // Update the input with the percentage value
            letterSpacingInput.value = `${percentageValue.toFixed(0)}%`;
            letterSpacingTooltip.innerHTML = `<span>${percentageValue.toFixed(
                0
            )}%</span>`;

            // Log the slider value and percentage for debugging
            console.log(
                `Slider Value: ${sliderValue}, Percentage: ${percentageValue.toFixed(
                    0
                )}%`
            );

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
        // Function to update line height
        const setLineHeight = () => {
            const newValue = parseFloat(lineHeightRange.value);
            lineHeightInput.value = newValue;
            lineHeightTooltip.innerHTML = `<span>${newValue}</span>`;

            const activeObject = canvas.getActiveObject();
            if (activeObject && activeObject.type === 'textbox') {
                addToUndoStack(canvas);
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
                fill: '#0a0b0a', // Optional: Reset text color
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
        setTimeout(function() {
            let spchoose = document.getElementsByClassName('sp-choose');
            console.log({
                spchoose
            })
            $(spchoose).click(function() {
                // alert('clicked')
                setTimeout(function() {
                    console.log({
                        clrcanvas
                    })
                    undoStack.push(clrcanvas);

                    if (undoStack.length > 0) {
                        $('#undoButton').find('svg path').attr('fill', '#0F172A');
                    }
                    redoStack = []; // Clear redo stack on new action
                }, 1000)
            })
        }, 1000)
        $(document).on("change",".sp-input",function(){
        var color = $(this).val();
        console.log(color)
        changeColor(color);
    })
        // Initialize the color picker
        $('#color-picker').spectrum({
            type: "flat",
            color: "#0a0b0a", // Default font color
            showInput: true,
            allowEmpty: true, // Allows setting background to transparent
            showAlpha: true, // Allows transparency adjustment
            preferredFormat: "hex",
            move: function (color) {
            if (color) {
                changeColor(color.toHexString()); // Apply color in real-time
            }
        },
            change: function(color) {
                if (color) {
                    changeColor(color.toHexString()); // Use RGB string for color changes
                } else {
                    changeColor('#0a0b0a'); // Handle transparency by default
                }
            }
        });

        // Function to change font or background color
        function changeColor(selectedColor) {
            const selectedColorType = document.querySelector('input[name="colorType"]:checked').value;
            const activeObject = canvas.getActiveObject();

            if (!activeObject) {
                return;
            }

            if (activeObject.type == 'textbox') {
                clrcanvas = canvas.toJSON(); // Store the current state of the canvas
                if (selectedColorType == 'font') {
                    activeObject.set('fill', selectedColor); // Change font color
                } else if (selectedColorType == 'background') {
                    activeObject.set('backgroundColor', selectedColor); // Change background color
                }
                canvas.renderAll(); // Re-render the canvas after color change
            }
        }


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

                    fabric.Image.fromURL(e.target.result, function (img) {
                        img.crossOrigin = "anonymous";
                        var canvasWidth = canvas.getWidth();
                        var canvasHeight = canvas.getHeight();

                        // Use Math.max to ensure the image covers the entire canvas
                        var scaleFactor = Math.max(
                            canvasWidth / img.width,
                            canvasHeight / img.height
                        );

                        img.set({
                            left: (canvasWidth - img.width * scaleFactor) / 2, // Centering horizontally
                            top: (canvasHeight - img.height * scaleFactor) / 2, // Centering vertically
                            scaleX: scaleFactor,
                            scaleY: scaleFactor,
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
                        canvas.renderAll();
                    });

                    // fabric.Image.fromURL(e.target.result, function(img) {
                    //     img.set({
                    //         left: 0,
                    //         top: 0,
                    //         selectable: false, // Make the image non-draggable
                    //         hasControls: false // Disable resizing controls for the image
                    //     });
                    //     canvas.setBackgroundImage(img);
                    //     canvas.renderAll();
                    //     console.log(`Image width: ${img.width}, Image height: ${img.height}`);
                    // });
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
                fill: '#0a0b0a', // Default text color (black)
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
                rotatingPointOffset: 30,
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
            text.on('rotating', function() {
                calculateControlPositions(text); // Update control position during rotation
            });
            // text.on('moving', function() {
            //     findTextboxCenter(text);
            // });
            canvas.add(text);

            canvas.renderAll();
            // findTextboxCenter(text);
        }

        function calculateControlPositions(object) {
            var controlCoords = object.oCoords; // Get object control coordinates

            // Get the position of the 'mtr' control
            var mtrControl = controlCoords.mtr; // 'mtr' control (rotate)

            // Log the untransformed mtr control position
            console.log('Rotation control position (mtr):', mtrControl);

            // Transform mtr control position to apply rotation and scaling
            var transformedMtr = fabric.util.transformPoint(
                new fabric.Point(mtrControl.x, mtrControl.y),
                object.calcTransformMatrix() // apply object transformations (rotation, scaling)
            );


            return transformedMtr;
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
                textAlign: originalTextbox.textAlign,
                fontWeight: originalTextbox.fontWeight,
                fontStyle: originalTextbox.fontStyle,
                underline: originalTextbox.underline,
                hasControls: true,
                hasBorders: true,
                lockScalingFlip: true,
                editable: true,
                borderColor: '#2DA9FC',
                cornerColor: '#fff',
                cornerSize: 6,
                transparentCorners: false,
                isStatic: true,
                backgroundColor: 'rgba(0, 0, 0, 0)',
            });


            canvas.add(clonedTextbox);

            // Add icons to the cloned textbox

            canvas.renderAll();
            setControlVisibilityForAll()
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

        canvas.on("mouse:down", function (options) {
            discardIfMultipleObjects(options);
            if (options.target && options.target.type === "textbox") {
                console.log("clicked on text box");
                //eventData.desgin_selected = "";
                canvas.setActiveObject(options.target);
                addIconsToTextbox(options.target);
            } else {
                // alert();
                canvas.getObjects("textbox").forEach(function (tb) {
                    if (tb.trashIcon) tb.trashIcon.set("visible", false);
                    if (tb.copyIcon) tb.copyIcon.set("visible", false);
                });
            }
        });

        canvas.on("mouse:up", function (options) {
            discardIfMultipleObjects(options);
        });


        function getTextDataFromCanvas() {
            var objects = canvas.getObjects();
            var textData = [];
            var finalArray = [];
            var shapeImageData = null;
            // console.log(objects);
        // **Current Dynamic Canvas Size**
            let canvasWidth = canvas.getWidth();
            let canvasHeight = canvas.getHeight();

            // **Calculate Reverse Scaling Factors**
            const scaleX = originalWidth / canvasWidth;
            const scaleY = originalHeight / canvasHeight;
            objects.forEach(function(obj) {
                if (obj.type === 'textbox') {
                    var centerPoint = obj.getCenterPoint();
                    var centerX = centerPoint.x;
                    var centerY = centerPoint.y;

                    // var controlCoords = obj.oCoords;
                    // var mtrControl = controlCoords.mtr;
                    // var transformedMtr = fabric.util.transformPoint(
                    //     new fabric.Point(mtrControl.x, mtrControl.y),
                    //     obj.calcTransformMatrix() // apply object transformations
                    // );

                    // var transformedMtr = calculateControlPositions(obj)
                    // console.log('Transformed rotation control position (mtr):', transformedMtr);
                    // var centerX =transformedMtr.x / 2;
                    // var centerY = (transformedMtr.y / 2) - 20;
                    // console.log(transformedMtr.x)
                    // console.log(transformedMtr.y)

                    // console.log({centerX})
                    // console.log({centerY})


                    // textData.push({

                    //     text: obj.text,
                    //     left: obj.left,
                    //     top: obj.top,
                    //     fontSize: parseInt(obj.fontSize),
                    //     fill: obj.fill,
                    //     centerX: centerX, // Include centerX in the data
                    //     centerY: centerY, // Include centerY in the data
                    //     dx: obj.left, // Calculate dx
                    //     dy: obj.top, // Calculate dy
                    //     backgroundColor: obj.backgroundColor,
                    //     fontFamily: obj.fontFamily,
                    //     textAlign: obj.textAlign,
                    //     fontWeight: obj.fontWeight,
                    //     fontStyle: obj.fontStyle,
                    //     underline: obj.underline,
                    //     linethrough: obj.linethrough,
                    //     date_formate: obj.date_formate,
                    //     letterSpacing: obj.charSpacing /
                    //         10, // Divide by 10 to convert to standard spacing
                    //     lineHeight: obj
                    //         .lineHeight, // Line height of the tex// Include date_formate if set
                    //     rotation: obj.angle
                    // });
                    textData.push({
                        left: obj.left * scaleX, // Scale back X position
                        top: obj.top * scaleY, // Scale back Y position
                        fontSize: parseInt(obj.fontSize * scaleY), // Scale font size
                        fill: obj.fill,
                        centerX: centerPoint.x * scaleX, // Scale back center position
                        centerY: centerPoint.y * scaleY,
                        text: obj.text,
                        dx: obj.left * scaleX, // Calculate dx
                        dy: obj.top * scaleX, // Calculate dy
                        backgroundColor: obj.backgroundColor,
                        fontFamily: obj.fontFamily,
                        textAlign: obj.textAlign,
                        fontWeight: obj.fontWeight,
                        fontStyle: obj.fontStyle,
                        underline: obj.underline,
                        width: obj.width * scaleX,
                        linethrough: obj.linethrough,
                        date_formate: obj.date_formate,
                        letterSpacing: obj.charSpacing /
                            10, // Divide by 10 to convert to standard spacing
                        lineHeight: obj
                            .lineHeight, // Line height of the tex// Include date_formate if set
                        rotation: obj.angle
                    });
                }

                // if (obj.type === "image") {
                //     var centerX = obj.left + obj.getScaledWidth() / 2; // Use getScaledWidth()
                //     var centerY = obj.top + obj.getScaledHeight() / 2; // Use getScaledHeight()

                //     console.log(centerX, centerY);

                //     shapeImageData = {
                //         shape: obj.clipPath ? obj.clipPath.type :
                //         'none', // Handle case when clipPath is null
                //         centerX: centerX,
                //         centerY: centerY,
                //         width: obj.getScaledWidth(), // Get the scaled width
                //         height: obj.getScaledHeight(), // Get the scaled height
                //     };

                //     textData.push({
                //         shapeImageData: shapeImageData
                //     });
                // }

                if (obj.type === "image") {
                    var centerX = obj.left + obj.getScaledWidth() / 2; // Use getScaledWidth()
                    var centerY = obj.top + obj.getScaledHeight() / 2; // Use getScaledHeight()

                    shapeImageData = {
                        shape: obj.clipPath ? obj.clipPath.type : 'none', // Handle case when clipPath is null
                        centerX: centerX,
                        centerY: centerY,
                        width: obj.getScaledWidth(), // Get the scaled width
                        height: obj.getScaledHeight(), // Get the scaled height
                    };


                }
            });
            finalArray.push({
                'textElements': textData
            })
            if (shapeImageData) {
                finalArray.push({
                    shapeImageData: shapeImageData
                });
            }
            //    console.log(shapeImageData);
            console.log('final' + finalArray);
            return finalArray;
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
                fill: '#0a0b0a',
                editable: true,
                selectable: true,
                hasControls: true,
                borderColor: '#2DA9FC',
                cornerColor: '#fff',
                cornerSize: 6,
                transparentCorners: false,
            });
            textbox.setControlsVisibility({
                mt: false, // Hide middle top control
                mb: false, // Hide middle bottom control
                bl: true, // Hide bottom left control
                br: true, // Hide bottom right control
                tl: true, // Hide top left control
                tr: true, // Hide top right control
                ml: true, // Show middle left control
                mr: true // Show middle right control
            });

            // textbox.on('scaling', function() {
            //     // Update the font size based on scaling
            //     var updatedFontSize = textbox.fontSize * (textbox.scaleX + textbox.scaleY) / 2;
            //     textbox.set('fontSize', updatedFontSize);
            //     canvas.renderAll();
            // });

            canvas.add(textbox);
            canvas.setActiveObject(textbox);

            canvas.renderAll();
        }



        function saveTextDataToDatabase() {

            // hideStaticTextElements();
            var textData = getTextDataFromCanvas();
            var textElements = textData[0].textElements;
            console.log(textData[0].textElements);
            // Accessing the text elements
            if (textData[1]) {
                var shapeImageData = textData[1].shapeImageData;
            }
            // console.log(shapeImageData);
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
            // const width = userImageElement.clientWidth;
            // const height = userImageElement.clientHeight;
            // const left = imageWrapperRect.left - canvasRect.left;
            // const top = imageWrapperRect.top - canvasRect.top;
            // const centerX = left + width / 2;
            // const centerY = top + height / 2;
            const shapePath = $('#shape_img').attr('src');


            // var shapeImageData = [];

            // shapeImageData ={
            //     shape: shape,
            //     centerX: centerX,
            //     centerY: centerY,
            //     width: width,
            //     height: height,
            // };


            // console.log(shapeImageData);
            // console.log(updatedOBJImage);
            // console.log(textData);


            fetch('/saveTextData', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json', // Set content type to JSON
                        'X-CSRF-TOKEN': csrfToken // Include CSRF token
                    },
                    body: JSON.stringify({
                        id: canvasId,
                        textElements: textElements,
                        shapeImageData: shapeImageData,
                        shape_image: shapePath,

                    })
                })
                .then(response => response.json())
                // .then(data => {
                //     console.log('Text data saved successfully', data);
                //     Swal.fire({
                //     title: "Save Successful",
                //     text: "",
                //     icon: "success",
                // }).then((result) => {
                //     if (result.isConfirmed) {
                //         // Redirect after clicking "OK"
                //         window.location.href = "{{URL::to('/admin/create_template')}}";
                //     }
                // });
                .then(data => {
                    console.log('Text data saved successfully', data);
                    Swal.fire({
                        title: "Save Successful",
                        text: "",
                        icon: "success",
                        showConfirmButton: false, // Hide the OK button
                        timer: 4000, // Auto-close after 4 seconds
                        timerProgressBar: true, // Show a progress bar
                    }).then(() => {
                        // Redirect after 4 seconds
                        window.location.href = "{{URL::to('/admin/create_template')}}";
                    });



            // window.location.href = "{{URL::to('/admin/create_template')}}";

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




        document.querySelectorAll('[data-command]').forEach(function(button) {
            button.addEventListener('click', function() {
                const command = button.getAttribute('data-command');
                if (command == "fontName" || command == "undo" || command == "redo") {
                    return;
                }
                executeCommand(this.getAttribute('data-command'));
            });
        });

        // Undo and Redo actions (basic implementation)


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
                    // alert('textAllign')
                    // obj.set('textAlign', 'center'); // Set text alignment to center
                }

                obj.on('rotating', function() {
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
        let undoStack = [];
        let redoStack = [];
        let isAddingToUndoStack = 0;
        function addToUndoStack(canvas) {
            undoStack.push(canvas.toJSON());
            if (undoStack.length > 0) {
                $('#undoButton').find('svg path').attr('fill', '#0F172A');
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



        function undo_() {
            if (undoStack.length > 0) { // Ensure at least one previous state exists

                redoStack.push(canvas.toJSON()); // Save current state to redo stack
                const lastState = undoStack.pop(); // Get the last state to undo
                canvas.loadFromJSON(lastState, function() {

                    canvas.renderAll(); // Render the canvas after loading state

                });
                if (redoStack.length > 0) {
                    $('#redoButton').find('svg path').attr('fill', '#0F172A');
                }
                setTimeout(function() {
                    setControlVisibilityForAll()
                }, 1000)
            } else {
                $('#undoButton').find('svg path').attr('fill', '#CBD5E1');
            }
        }

        function redo_() {
            if (redoStack.length > 0) {

                undoStack.push(canvas.toJSON()); // Save current state to undo stack
                const nextState = redoStack.pop(); // Get the next state to redo
                canvas.loadFromJSON(nextState, function() {
                    canvas.renderAll(); // Render the canvas after loading state

                });
                if (undoStack.length > 0) {
                    $('#undoButton').find('svg path').attr('fill', '#0F172A');
                }
                $('#redoButton').find('svg path').attr('fill', '#0F172A');
                setTimeout(function() {
                    setControlVisibilityForAll()
                }, 1000)
            } else {
                $('#redoButton').find('svg path').attr('fill', '#CBD5E1');
            }
        }



        $("#undoButton").click(function() {
            undo();
        })
        $("#redoButton").click(function() {
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
                const shapes = ['rectangle', 'circle', 'star', 'rounded-border', 'heart', 'triangle'];
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
            $(".design-sidebar-action").removeClass("activated");
            $(this).addClass("activated");
        }

    })

    $(document).on("click", ".close-btn", function() {
        toggleSidebar();
        var id = $(this).data('id');
        $('#sidebar').removeClass(id);
    })


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
        console.log("add to here");
        console.log(target);
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
        $("#letterSpacingRange").val(target?.charSpacing||0)
        // const charSpacing = target.charSpacing || 0; // Ensure there's a valid value
        const charSpacing = parseFloat($("#letterSpacingRange").val()); // Ensure there's a valid value
        console.log({charSpacing})
        const percentageValue = (charSpacing / 500) * 100;

        // Update the input box with the percentage value
        $("#letterSpacingInput").val(`${percentageValue.toFixed(0)}%`);

        // Update the range slider with the original value
        $("#letterSpacingRange").val(charSpacing);

        $("#fontSizeInput").val(Number(target.fontSize).toFixed(0));
        $("#fontSizeRange").val(Number(target.fontSize).toFixed(0));
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
</script>
