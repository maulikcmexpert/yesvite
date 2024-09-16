

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title></title>
    @include($header)
</head>
<body>
    <div class="editor-container">
        <div class="toolbar">

            <button data-command="bold"><b>B</b></button>
            <button data-command="italic"><i>I</i></button>
            <button data-command="underline"><u>U</u></button>
            <button data-command="strikeThrough"><s>S</s></button>


            <select data-command="fontSize">
                <option value="1">Small</option>
                <option value="2">Normal</option>
                <option value="3">Medium</option>
                <option value="4">Large</option>
                <option value="5">X-Large</option>
                <option value="6">XX-Large</option>
                <option value="7">Huge</option>
            </select>

            <!-- Font Family -->
            <select data-command="fontName">
                <option value="Arial">Arial</option>
                <option value="Courier New">Courier New</option>
                <option value="Georgia">Georgia</option>
                <option value="Times New Roman">Times New Roman</option>
                <option value="Verdana">Verdana</option>
            </select>

            <!-- Colors -->
            text-color:
            <input type="color" data-command="foreColor" title="Select text color">
            background-color:
            <input type="color" data-command="hiliteColor" title="Select background color">

            <!-- Alignment -->
            <button data-command="justifyLeft">Left</button>
            <button data-command="justifyCenter">Center</button>
            <button data-command="justifyRight">Right</button>
            <button data-command="justifyFull">Justify</button>
            <button onclick="addText()">📄</button>

            <!-- Lists -->
            <button data-command="insertUnorderedList">• List</button>
            <button data-command="insertOrderedList">1. List</button>

            <!-- Links and Images -->
            <button data-command="createLink">Insert Link</button>
            <button data-command="unlink">Remove Link</button>

            <!-- Image Upload -->
            <input type="file" id="imageUploader" accept="image/*" style="display: none;">
            <button onclick="document.getElementById('imageUploader').click()">Insert Image</button>

            <!-- Others -->
            <button data-command="undo">Undo</button>
            <button data-command="redo">Redo</button>
            <button data-command="removeFormat">Remove Formatting</button>
            <button id="deleteAllBtn">Delete All</button>
        <button id="saveBtn">Save</button>
        </div>
        @include($page)
    </div>

    @include($footer)
</body>
</html>
