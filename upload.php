<?php include('includes/config.php'); ?>
<?php include('includes/header.php'); ?>

<h1 class="mb-4">Upload New Image</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $file = $_FILES['image'] ?? null;

    if ($file && $file['error'] == 0) {
        if ($file['size'] <= 5000000) { // 5MB
            $filename = time() . '_' . basename($file['name']);
            $targetPath = 'assets/images/' . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $stmt = $pdo->prepare("INSERT INTO images (title, description, filename) VALUES (?, ?, ?)");
                $stmt->execute([$title, $description, $filename]);
                echo '<div class="alert alert-success">Image uploaded successfully!</div>';
            } else {
                echo '<div class="alert alert-danger">Failed to upload file.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">File size exceeds 5MB.</div>';
        }
    } else {
        echo '<div class="alert alert-danger">Please select a valid file.</div>';
    }
}
?>

<form action="upload.php" method="POST" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="title" class="form-label">Image Title</label>
    <input type="text" name="title" id="title" class="form-control" required>
  </div>
  
  <div class="mb-3">
    <label for="description" class="form-label">Image Description</label>
    <textarea name="description" id="description" class="form-control" rows="4"></textarea>
  </div>

  <div class="mb-3">
    <label for="image" class="form-label">Select Image</label>
    <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
  </div>

  <button type="submit" class="btn btn-primary">Upload</button>
</form>

<?php include('includes/footer.php'); ?>
