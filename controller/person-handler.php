<?php
if (isset($_GET['action'])) {

    switch ($_GET['action']) {

        case 'add':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Handle file upload for the 'add' action
                $imagePath = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $uploadedFile = $_FILES['image'];
                    $uploadDirectory = 'assets/uploads/';
                    $targetFile = $uploadDirectory . basename($uploadedFile['name']);

                    if (in_array($uploadedFile['type'], ['image/jpeg', 'image/png', 'image/gif','image/jpg'])) {
                        if (move_uploaded_file($uploadedFile['tmp_name'], $targetFile)) {
                            $imagePath = $targetFile; // Save the file path
                        } else {
                            echo "Error uploading file.";
                        }
                    } else {
                        echo "Invalid file type.";
                    }
                }

                // Process the languages input
                $languages = isset($_POST['languages']) && !empty($_POST['languages']) ? implode(',', $_POST['languages']) : '';

                // Call the add method with the image path
                $handler->add($_POST['name'], $_POST['email'], $_POST['gender'], $languages, $imagePath);
                header("Location: /crud_switch/index.php");
                exit;
            }
            break;

        case 'update':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Check if 'id' is set
                if (empty($_POST['id'])) {
                    echo "ID is required for updating the record.";
                    exit; // Or redirect as needed
                }

                $imagePath = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $uploadedFile = $_FILES['image'];
                    $uploadDirectory = 'assets/uploads/';
                    $targetFile = $uploadDirectory . basename($uploadedFile['name']);

                    // Validate file type (optional)
                    if (in_array($uploadedFile['type'], ['image/jpeg', 'image/png', 'image/gif','image/jpg'])) {
                        // Move the file to the target directory
                        if (move_uploaded_file($uploadedFile['tmp_name'], $targetFile)) {
                            $imagePath = $targetFile; // Save the file path
                        } else {
                            echo "Error uploading file.";
                        }
                    } else {
                        echo "Invalid file type.";
                    }
                } else {
                    // Keep the existing image if no new image is uploaded
                    $imagePath = $_POST['existing_image']; // Make sure to include this in your form
                }

                // Process the languages input
                $languages = isset($_POST['languages']) && !empty($_POST['languages']) ? implode(',', $_POST['languages']) : '';

                // Call the update method with the image path
                $handler->update($_POST['id'], $_POST['name'], $_POST['email'], $_POST['gender'], $languages, $imagePath);
                header("Location: /crud_switch/index.php");
                exit;
            }
            break;



        case 'delete':
            if (isset($_POST['id'])) {
                $handler->delete($_GET['id']);
                header("Location: /crud_switch/index.php");
                exit;
            }
            break;
    }
}
