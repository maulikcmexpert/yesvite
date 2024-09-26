<script type="text/javascript">


$(document).ready(function() {
    const $fileInput = $('#image');
    const $canvas = $('#imageEditor1');
    const $tooltip = $('#tooltip');
    const ctx = $canvas[0].getContext('2d');
    const canvasOffset = $canvas.offset();
    const canvasWidth = $canvas.width();
    const canvasHeight = $canvas.height();

    let image = null;
    let shape = 'rectangle'; // Default shape
    let shapeDetails = {
        x: 100,
        y: 100,
        width: 200,
        height: 200,
        image: null,
        borderRadius: 20
    };
    let isDragging = false;
    let isResizing = false;
    let currentHandle = null;
    let startX, startY, startWidth, startHeight;
    let handlesVisible = false; // Flag to control visibility of resize handles
    const handleSize = 10; // Size of the resize handles
    const handleColor = 'red'; // Color for the resize handles
    let isClick = false; // Flag to detect click or drag

    function updateCursor(x, y) {
        if (handlesVisible && isInsideResizeHandle(x, y)) {
            const handle = getResizeHandle(x, y);
            switch (handle) {
                case 'top-left':
                case 'bottom-right':
                    $canvas.css('cursor', 'nwse-resize');
                    break;
                case 'top-right':
                case 'bottom-left':
                    $canvas.css('cursor', 'nesw-resize');
                    break;
                default:
                    $canvas.css('cursor', 'default');
                    break;
            }
        } else if (isInsideShape(x, y)) {
            $canvas.css('cursor', 'move');
        } else {
            $canvas.css('cursor', 'default');
        }
    }

    function hideTooltip() {
        $tooltip.hide();
    }

    $fileInput.on('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                image = new Image();
                image.onload = function() {
                    shapeDetails.image = image;
                    handlesVisible = true; // Show resize handles after image is loaded
                    drawCanvas();
                };
                image.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    $canvas.on('mousedown', function(event) {
        const offsetX = event.pageX - canvasOffset.left;
        const offsetY = event.pageY - canvasOffset.top;

        isClick = true; // Initialize as true on mousedown

        if (handlesVisible && isInsideResizeHandle(offsetX, offsetY)) {
            isResizing = true;
            currentHandle = getResizeHandle(offsetX, offsetY);
            startX = offsetX;
            startY = offsetY;
            startWidth = shapeDetails.width;
            startHeight = shapeDetails.height;
        } else if (isInsideShape(offsetX, offsetY)) {
            isDragging = true;
            startX = offsetX - shapeDetails.x;
            startY = offsetY - shapeDetails.y;
        }

        $(document).on('mousemove', onMouseMove);
        $(document).on('mouseup', onMouseUp);
    });

    $canvas.on('mousemove', function(event) {
        const offsetX = event.pageX - canvasOffset.left;
        const offsetY = event.pageY - canvasOffset.top;
        updateCursor(offsetX, offsetY);
    });

    function onMouseMove(event) {
        const offsetX = event.pageX - canvasOffset.left;
        const offsetY = event.pageY - canvasOffset.top;

        isClick = false;

        if (isResizing) {
            handleResizing(offsetX, offsetY);
            drawCanvas();
        } else if (isDragging) {
            shapeDetails.x = Math.min(Math.max(0, offsetX - startX), canvasWidth - shapeDetails.width);
            shapeDetails.y = Math.min(Math.max(0, offsetY - startY), canvasHeight - shapeDetails.height);
            drawCanvas();
        }
    }

    function onMouseUp(event) {
        isDragging = false;
        isResizing = false;

        if (isClick) {
            const shapes = ['rectangle', 'circle', 'star', 'rounded-border'];
            const currentIndex = shapes.indexOf(shape);
            shape = shapes[(currentIndex + 1) % shapes.length];
            console.log(`Shape changed to: ${shape}`); // Log shape change
            drawCanvas();
        }

        $(document).off('mousemove', onMouseMove);
        $(document).off('mouseup', onMouseUp);

        hideTooltip();
    }

    function handleResizing(x, y) {
        switch (currentHandle) {
            case 'top-left':
                shapeDetails.width = Math.min(startWidth + (startX - x), canvasWidth - shapeDetails.x);
                shapeDetails.height = Math.min(startHeight + (startY - y), canvasHeight - shapeDetails.y);
                shapeDetails.x = Math.min(x, canvasWidth - shapeDetails.width);
                shapeDetails.y = Math.min(y, canvasHeight - shapeDetails.height);
                break;
            case 'top-right':
                shapeDetails.width = Math.min(x - shapeDetails.x, canvasWidth - shapeDetails.x);
                shapeDetails.height = Math.min(startHeight + (startY - y), canvasHeight - shapeDetails.y);
                shapeDetails.y = Math.min(y, canvasHeight - shapeDetails.height);
                break;
            case 'bottom-left':
                shapeDetails.width = Math.min(startWidth + (startX - x), canvasWidth - shapeDetails.x);
                shapeDetails.height = Math.min(y - shapeDetails.y, canvasHeight - shapeDetails.y);
                shapeDetails.x = Math.min(x, canvasWidth - shapeDetails.width);
                break;
            case 'bottom-right':
                shapeDetails.width = Math.min(x - shapeDetails.x, canvasWidth - shapeDetails.x);
                shapeDetails.height = Math.min(y - shapeDetails.y, canvasHeight - shapeDetails.y);
                break;
            default:
                break;
        }
        shapeDetails.width = Math.max(shapeDetails.width, 10);
        shapeDetails.height = Math.max(shapeDetails.height, 10);
    }

    function isInsideShape(x, y) {
        switch (shape) {
            case 'rectangle':
                return x >= shapeDetails.x && x <= shapeDetails.x + shapeDetails.width &&
                       y >= shapeDetails.y && y <= shapeDetails.y + shapeDetails.height;
            case 'circle':
                const cx = shapeDetails.x + shapeDetails.width / 2;
                const cy = shapeDetails.y + shapeDetails.height / 2;
                const r = Math.min(shapeDetails.width, shapeDetails.height) / 2;
                return Math.sqrt((x - cx) ** 2 + (y - cy) ** 2) <= r;
            case 'star':
                return isInsideStar(x, y);
            case 'rounded-border':
                return isInsideRoundedRectangle(x, y, shapeDetails);
            default:
                return false;
        }
    }

    function isInsideStar(x, y) {
        const starCx = shapeDetails.x + shapeDetails.width / 2;
        const starCy = shapeDetails.y + shapeDetails.height / 2;
        const outerRadius = Math.min(shapeDetails.width, shapeDetails.height) / 2;
        const innerRadius = outerRadius / 2.5;
        let angle = -Math.PI / 2;
        let inside = false;
        for (let i = 0; i < 5; i++) {
            let x1 = starCx + outerRadius * Math.cos(angle);
            let y1 = starCy + outerRadius * Math.sin(angle);
            angle += Math.PI / 5;
            let x2 = starCx + innerRadius * Math.cos(angle);
            let y2 = starCy + innerRadius * Math.sin(angle);
            angle += Math.PI / 5;
            if (isPointInTriangle(x, y, starCx, starCy, x1, y1, x2, y2)) {
                inside = !inside;
            }
        }
        return inside;
    }

    function isPointInTriangle(px, py, ax, ay, bx, by, cx, cy) {
        let v0x = bx - ax;
        let v0y = by - ay;
        let v1x = cx - ax;
        let v1y = cy - ay;
        let v2x = px - ax;
        let v2y = py - ay;
        let dot00 = v0x * v0x + v0y * v0y;
        let dot01 = v0x * v1x + v0y * v1y;
        let dot02 = v0x * v2x + v0y * v2y;
        let dot11 = v1x * v1x + v1y * v1y;
        let dot12 = v1x * v2x + v1y * v2y;
        let invDenom = 1 / (dot00 * dot11 - dot01 * dot01);
        let u = (dot11 * dot02 - dot01 * dot12) * invDenom;
        let v = (dot00 * dot12 - dot01 * dot02) * invDenom;
        return (u >= 0) && (v >= 0) && (u + v <= 1);
    }

    function isInsideRoundedRectangle(x, y, details) {
        const rx = Math.min(x, details.x + details.width - details.borderRadius);
        const ry = Math.min(y, details.y + details.height - details.borderRadius);
        const rect = new Path2D();
        rect.moveTo(rx + details.borderRadius, ry);
        rect.lineTo(rx + details.width - details.borderRadius, ry);
        rect.arc(rx + details.width - details.borderRadius, ry + details.borderRadius, details.borderRadius, -Math.PI / 2, 0);
        rect.lineTo(rx + details.width, ry + details.height - details.borderRadius);
        rect.arc(rx + details.width - details.borderRadius, ry + details.height - details.borderRadius, details.borderRadius, 0, Math.PI / 2);
        rect.lineTo(rx + details.borderRadius, ry + details.height);
        rect.arc(rx + details.borderRadius, ry + details.height - details.borderRadius, details.borderRadius, Math.PI / 2, Math.PI);
        rect.lineTo(rx, ry + details.borderRadius);
        rect.arc(rx + details.borderRadius, ry + details.borderRadius, details.borderRadius, Math.PI, -Math.PI / 2);
        rect.closePath();
        return ctx.isPointInPath(rect, x, y);
    }

    function getResizeHandle(x, y) {
        if (isPointInRect(x, y, shapeDetails.x - handleSize, shapeDetails.y - handleSize, handleSize, handleSize)) return 'top-left';
        if (isPointInRect(x, y, shapeDetails.x + shapeDetails.width - handleSize, shapeDetails.y - handleSize, handleSize, handleSize)) return 'top-right';
        if (isPointInRect(x, y, shapeDetails.x - handleSize, shapeDetails.y + shapeDetails.height - handleSize, handleSize, handleSize)) return 'bottom-left';
        if (isPointInRect(x, y, shapeDetails.x + shapeDetails.width - handleSize, shapeDetails.y + shapeDetails.height - handleSize, handleSize, handleSize)) return 'bottom-right';
        return null;
    }

    function isPointInRect(px, py, x, y, width, height) {
        return px >= x && px <= x + width && py >= y && py <= y + height;
    }

    function drawCanvas() {
        ctx.clearRect(0, 0, canvasWidth, canvasHeight);
        if (image) {
            ctx.drawImage(image, 0, 0, canvasWidth, canvasHeight);
        }
        drawShape();
    }

    function drawShape() {
        ctx.save();
        if (shape === 'rectangle') {
            ctx.strokeStyle = 'blue';
            ctx.strokeRect(shapeDetails.x, shapeDetails.y, shapeDetails.width, shapeDetails.height);
        } else if (shape === 'circle') {
            ctx.strokeStyle = 'green';
            ctx.beginPath();
            ctx.arc(shapeDetails.x + shapeDetails.width / 2, shapeDetails.y + shapeDetails.height / 2, Math.min(shapeDetails.width, shapeDetails.height) / 2, 0, 2 * Math.PI);
            ctx.stroke();
        } else if (shape === 'star') {
            ctx.strokeStyle = 'yellow';
            ctx.lineWidth = 2;
            ctx.beginPath();
            drawStar(ctx, shapeDetails.x + shapeDetails.width / 2, shapeDetails.y + shapeDetails.height / 2, Math.min(shapeDetails.width, shapeDetails.height) / 2, 5);
            ctx.stroke();
        } else if (shape === 'rounded-border') {
            ctx.strokeStyle = 'red';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.rect(shapeDetails.x, shapeDetails.y, shapeDetails.width, shapeDetails.height);
            ctx.stroke();
        }
        if (handlesVisible) {
            drawResizeHandles();
        }
        ctx.restore();
    }

    function drawResizeHandles() {
        ctx.fillStyle = handleColor;
        ctx.fillRect(shapeDetails.x - handleSize, shapeDetails.y - handleSize, handleSize, handleSize);
        ctx.fillRect(shapeDetails.x + shapeDetails.width - handleSize, shapeDetails.y - handleSize, handleSize, handleSize);
        ctx.fillRect(shapeDetails.x - handleSize, shapeDetails.y + shapeDetails.height - handleSize, handleSize, handleSize);
        ctx.fillRect(shapeDetails.x + shapeDetails.width - handleSize, shapeDetails.y + shapeDetails.height - handleSize, handleSize, handleSize);
    }

    function drawStar(ctx, cx, cy, spikes, radius) {
        const rot = (Math.PI / 2) * 3;
        const x = cx;
        const y = cy;
        const step = Math.PI / spikes;
        ctx.beginPath();
        ctx.moveTo(cx, cy - radius);
        for (let i = 0; i < spikes; i++) {
            ctx.lineTo(cx + Math.cos(rot) * radius, cy - Math.sin(rot) * radius);
            rot += step;
            ctx.lineTo(cx + Math.cos(rot) * (radius / 2), cy - Math.sin(rot) * (radius / 2));
            rot += step;
        }
        ctx.lineTo(cx, cy - radius);
        ctx.closePath();
        ctx.lineWidth = 5;
        ctx.strokeStyle = 'black';
        ctx.stroke();
        ctx.fillStyle = 'yellow';
        ctx.fill();
    }

    $('#addText').on('click', function() {
        const text = prompt('Enter text:');
        if (text) {
            ctx.font = '30px Arial';
            ctx.fillStyle = 'black';
            ctx.fillText(text, shapeDetails.x + shapeDetails.width / 2, shapeDetails.y + shapeDetails.height / 2);
        }
    });

    drawCanvas();
});
</script>
