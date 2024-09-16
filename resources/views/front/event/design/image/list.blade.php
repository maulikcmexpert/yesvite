<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Gallery</title>
    <style>
        .image-table {
            width: 50%;
            border-collapse: collapse;
        }
        .image-table th, .image-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .image-table th {
            background-color: #f4f4f4;
        }
        .image-table img {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Image Gallery</h1>

    <table class="image-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>

            </tr>
        </thead>
        <tbody>
            @foreach($images as $image)
                <tr>
                    <td>{{ $image->id }}</td>
                    <td> <img src="{{ asset('uploads/images/' . $image->image) }}" alt="Image" height="50" width="100"></td>

                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
