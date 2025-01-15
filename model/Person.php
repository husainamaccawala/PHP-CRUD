<?php
require_once __DIR__ . '/../config/Dbh.php';

class Person
{
    private $conn;
    private $perPage; // Number of records per page

    public function __construct($perPage = 5) // Default to 5 if not provided
    {
        $db = new Dbh();
        $this->conn = $db->connect();
        $this->perPage = $perPage;
    }

    public function add($name, $email, $gender, $languages, $imagePath) {
        $sql = "INSERT INTO persons (name, email, gender, languages, image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
    
        $stmt->bind_param("sssss", $name, $email, $gender, $languages, $imagePath);
        $stmt->execute();
        $stmt->close();
    }
    

    public function getperson()
    {
        $query = "SELECT * FROM persons";
        return mysqli_query($this->conn, $query);
    }


    public function getpersonbyid($id)
    {
        $query = "SELECT * FROM persons WHERE id=$id";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_assoc($result);
    }

    public function update($id, $name, $email, $gender, $languages, $imagePath) {
        // Prepare the SQL query
        $sql = "UPDATE persons SET name = ?, email = ?, gender = ?, languages = ?, image = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("sssssi", $name, $email, $gender, $languages, $imagePath, $id);
        $stmt->execute();
        $stmt->close();
    }
  
    public function delete($id)
    {
        $query = "DELETE FROM persons WHERE id=$id";
        return mysqli_query($this->conn, $query);
    }

    // Get the total number of persons
    private function getTotalRecords()
    {
        $query = "SELECT COUNT(*) FROM persons";
        $result = mysqli_query($this->conn, $query);
        if (!$result) {
            die("Error in Query: " . mysqli_error($this->conn));
        }
        $data = mysqli_fetch_row($result);
        // Add this for debugging
        return $data[0];
    }


    // Fetch persons for the current page
    public function getPersons($currentPage)
    {
        $startFrom = ($currentPage - 1) * $this->perPage;
        $query = "SELECT * FROM persons LIMIT $startFrom, $this->perPage";

        // Run the query and check if it was successful
        $result = mysqli_query($this->conn, $query);
        if (!$result) {
            die("Error in Query: " . mysqli_error($this->conn));
        }

        return $result;
    }


    // Generate pagination links
    public function paginate($currentPage)
    {
        $totalRecords = $this->getTotalRecords();
        $totalPages = ceil($totalRecords / $this->perPage);

        // Retrieve the current rows per page setting
        $rowsPerPage = $this->perPage;

        $pagination = "";
        if ($totalPages > 1) {
            // Start pagination structure
            $pagination .= "<nav aria-label='Page navigation example'>";
            $pagination .= "<ul class='pagination'>";

            // Previous button
            if ($currentPage > 1) {
                $pagination .= "<li class='page-item'><a class='page-link' href='?page=" . ($currentPage - 1) . "&rows=$rowsPerPage'>Previous</a></li>";
            } else {
                $pagination .= "<li class='page-item disabled'><a class='page-link' href='#'>Previous</a></li>";
            }

            // Determine the range of page numbers to show
            $start = max(1, $currentPage - 1); // One page before current
            $end = min($totalPages, $currentPage + 1); // One page after current

            // Show "1 ..." if the range does not include the first page
            if ($start > 1) {
                $pagination .= "<li class='page-item'><a class='page-link' href='?page=1&rows=$rowsPerPage'>1</a></li>";
                if ($start > 2) {
                    $pagination .= "<li class='page-item disabled'><a class='page-link' href='#'>...</a></li>";
                }
            }

            // Generate page numbers
            for ($i = $start; $i <= $end; $i++) {
                $activeClass = ($currentPage == $i) ? "active" : "";
                $pagination .= "<li class='page-item $activeClass'><a class='page-link' href='?page=$i&rows=$rowsPerPage'>$i</a></li>";
            }

            // Show "... n" if the range does not include the last page
            if ($end < $totalPages) {
                if ($end < $totalPages - 1) {
                    $pagination .= "<li class='page-item disabled'><a class='page-link' href='#'>...</a></li>";
                }
                $pagination .= "<li class='page-item'><a class='page-link' href='?page=$totalPages&rows=$rowsPerPage'>$totalPages</a></li>";
            }

            // Next button
            if ($currentPage < $totalPages) {
                $pagination .= "<li class='page-item'><a class='page-link' href='?page=" . ($currentPage + 1) . "&rows=$rowsPerPage'>Next</a></li>";
            } else {
                $pagination .= "<li class='page-item disabled'><a class='page-link' href='#'>Next</a></li>";
            }

            // Close pagination structure
            $pagination .= "</ul>";
            $pagination .= "</nav>";
        }

        return $pagination;
    }
}

// Get the rows per page from the query string or default to 5
$rowsPerPage = isset($_GET['rows']) ? (int)$_GET['rows'] : 5;

// Create an instance of the Person class with user-defined rows per page
$handler = new Person($rowsPerPage);

// Get the current page from the query string, default to 1 if not set
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Fetch the persons for the current page
$persons = $handler->getPersons($currentPage);

// Generate the pagination links
$pagination = $handler->paginate($currentPage);
