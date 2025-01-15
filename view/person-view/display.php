<?php require_once __DIR__ . "/../layout/header.php"; ?>

<center>
    <h1>persons List</h1>
</center>
<div>
    <table border="1" class="table table-bordered w-50" style="margin-left:300px;">
        <a href="index.php?action=add" style="margin-left:300px;">Add New Person</a>
        <div style="margin-left: 60%;">
            <form>
                <label for="rows">Rows per page:</label>
                <select name="rows" id="rows" onchange="this.form.submit()">
                    <option value="5" <?= (isset($_GET['rows']) && $_GET['rows'] == 5) ? 'selected' : '' ?>>5</option>
                    <option value="10" <?= (isset($_GET['rows']) && $_GET['rows'] == 10) ? 'selected' : '' ?>>10</option>
                    <option value="20" <?= (isset($_GET['rows']) && $_GET['rows'] == 20) ? 'selected' : '' ?>>20</option>
                    <option value="50" <?= (isset($_GET['rows']) && $_GET['rows'] == 50) ? 'selected' : '' ?>>50</option>
                </select>
            </form>
        </div>

        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Languages</th>
                <th>image</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>

            <?php
            // Calculate the starting serial number based on the current page
            $srno = ($currentPage - 1) * 5 + 1; // 5 is the number of records per page

            // Check if there are students to display
            if (mysqli_num_rows($persons) > 0):
                while ($person = mysqli_fetch_assoc($persons)):
            ?>
                    <tr>
                        <td><?php echo htmlspecialchars($person['id']); ?></td>
                        <td><?php echo htmlspecialchars($person['name']); ?></td>
                        <td><?php echo htmlspecialchars($person['email']); ?></td>
                        <td><?php echo htmlspecialchars($person['gender']); ?></td>
                        <td>
                            <?php
                            // Convert the stored string back to an array and display each language
                            $languagesArray = isset($person['languages']) ? explode(',', $person['languages']) : [];
                            echo implode(', ', $languagesArray); // Display languages as a comma-separated list
                            ?>
                        </td>
                        <td>
                            <?php if (!empty($person['image'])): ?>
                                <img src="<?php echo htmlspecialchars($person['image']); ?>" alt="Photo" style="width: 50px; height: 50px; object-fit: cover;">
                            <?php else: ?>
                                No Photo
                            <?php endif; ?>
                        </td>


                        <td>
                            <a href="index.php?action=update&id=<?php echo htmlspecialchars($person['id']); ?>">Edit</a> |
                            <a href="index.php?action=delete&id=<?php echo htmlspecialchars($person['id']); ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No persons found.</td>
                </tr>
            <?php endif; ?>
        </tbody>

    </table>
    <div style="margin-left:300px;">
        <?php
        echo $pagination;
        ?>
    </div>


    <?php require_once __DIR__ . "/../layout/footer.php";   ?>