<?php
require_once __DIR__ . "/../layout/header.php";
?>
<div class="container">
    <form method="POST" action="/crud_switch/index.php?action=<?php echo isset($_GET['action']) && $_GET['action'] == 'add' ? "add" : "update"; ?>" enctype="multipart/form-data">

        <?php if (isset($person['id'])): ?>
            <input type="hidden" name="id" value="<?php echo $person['id']; ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required value="<?php echo isset($person['name']) ? $person['name'] : ''; ?>">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required value="<?php echo isset($person['email']) ? $person['email'] : ''; ?>">
        </div>

        <div class="mb-3">
            <label for="gender" class="form-label">Gender</label><br>
            <input type="radio" class="form-check-input" id="male" name="gender" value="Male" <?php echo (isset($person['gender']) && $person['gender'] == 'Male') ? 'checked' : ''; ?>>
            <label class="form-check-label" for="male">Male</label><br>
            <input type="radio" class="form-check-input" id="female" name="gender" value="Female" <?php echo (isset($person['gender']) && $person['gender'] == 'Female') ? 'checked' : ''; ?>>
            <label class="form-check-label" for="female">Female</label><br>
        </div>

        <div class="mb-3">
            <label for="languages" class="form-label">Languages Known</label><br>
            <input type="checkbox" id="english" name="languages[]" value="English" <?php echo (isset($person['languages']) && in_array('English', explode(',', $person['languages']))) ? 'checked' : ''; ?>>
            <label for="english">English</label><br>

            <input type="checkbox" id="gujarati" name="languages[]" value="Gujarati" <?php echo (isset($person['languages']) && in_array('Gujarati', explode(',', $person['languages']))) ? 'checked' : ''; ?>>
            <label for="gujarati">Gujarati</label><br>

            <input type="checkbox" id="hindi" name="languages[]" value="Hindi" <?php echo (isset($person['languages']) && in_array('Hindi', explode(',', $person['languages']))) ? 'checked' : ''; ?>>
            <label for="hindi">Hindi</label><br>
        </div>

        <div class="mb-3">
        <img src="/crud_switch/assets/uploads/placeholder.png" onclick="triggerClick()" id="profileDisplay" alt="" style=" display: block; width: 50px; height: 50px auto; border: 30%; cursor: pointer;" />
            <label for="image" class="form-label">Upload Image</label>
            <input type="file" class="form-control" name="image" onchange="displayImage(this)" id="image">

            <?php if (isset($person['image']) && !empty($person['image'])): ?>
                <div class="mt-2">
                    <label>Current Image:</label><br>
                    <img src="<?php echo $person['image']; ?>" alt="Current Image" width="150">
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">
            <?php echo isset($_GET['action']) && $_GET['action'] == 'add' ? "Insert" : "Update"; ?>
        </button>
    </form>
    <script>
        function triggerClick() {
            document.querySelector('#image').click();
        }

        function displayImage(e) {
            if (e.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    document.querySelector("#profileDisplay").setAttribute('src', e.target.result);
                }
                reader.readAsDataURL(e.files[0]);
            }
        }
    </script>
</div>
<?php
require_once '../crud_switch/view/layout/footer.php';
?>