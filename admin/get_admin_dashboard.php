<?php
include '../includes/db_connect.php';
$conn = OpenCon();

$success_message = '';
$error_message = '';

//user management
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $selected_ids = isset($_POST['selected_users']) ? $_POST['selected_users'] : [];
        $selected_prd = isset($_POST['selected_products']) ? $_POST['selected_products'] : [];

        if (!empty($selected_ids) || !empty($selected_prd)) {
            if (!empty($selected_ids)){
            $selected_ids = array_map('intval', $selected_ids);
            $placeholders = str_repeat('?,', count($selected_ids) - 1) . '?';
            }else{
            $selected_prd = array_map('intval', $selected_prd);
            $placeholders = str_repeat('?,', count($selected_prd) - 1) . '?';
            }

           
            if ($action === 'activate') {
                $sql = "UPDATE user SET User_Status = 'active' WHERE User_ID IN ($placeholders)";
                $stmt = $conn->prepare($sql);
                $types = str_repeat('i', count($selected_ids));
                $stmt->bind_param($types, ...$selected_ids);
                if ($stmt->execute()) {
                    $success_message = "Users activated successfully.";
                } else {
                    $error_message = "Error activating users.";
                }
                $stmt->close();
            } elseif ($action === 'deactivate') {

                $sql = "UPDATE user SET User_Status = 'inactive' WHERE User_ID IN ($placeholders)";
                $stmt = $conn->prepare($sql);
                $types = str_repeat('i', count($selected_ids));
                $stmt->bind_param($types, ...$selected_ids);
                if ($stmt->execute()) {
                    $success_message = "Users deactivated successfully.";
                } else {
                    $error_message = "Error deactivating users.";
                }
                $stmt->close();
            } elseif ($action === 'delete') {
                $sql = "DELETE FROM user WHERE User_ID IN ($placeholders)";
                $stmt = $conn->prepare($sql);
                $types = str_repeat('i', count($selected_ids));
                $stmt->bind_param($types, ...$selected_ids);
                if ($stmt->execute()) {
                    $success_message = "Users deleted successfully.";
                } else {
                    $error_message = "Error deleting users.";
                }
                $stmt->close();
            }
            elseif ($action === 'activate2') {
                $sql = "UPDATE product SET Status = 'active' WHERE Product_ID IN ($placeholders)";
                $stmt = $conn->prepare($sql);
                $types = str_repeat('i', count($selected_prd));
                $stmt->bind_param($types, ...$selected_prd);
                if ($stmt->execute()) {
                    $success_message = "Products activated successfully.";
                } else {
                    $error_message = "Error activating products.";
                }
                $stmt->close();
            } elseif ($action === 'deactivate2') {
                $sql = "UPDATE product SET Status = 'inactive' WHERE Product_ID IN ($placeholders)";
                $stmt = $conn->prepare($sql);
                $types = str_repeat('i', count($selected_prd));
                $stmt->bind_param($types, ...$selected_prd);
                if ($stmt->execute()) {
                    $success_message = "Products deactivated successfully.";
                } else {
                    $error_message = "Error deactivating products.";
                }
                $stmt->close();
            }   elseif ($action === 'sold2') {
                $sql = "UPDATE product SET Status = 'sold' WHERE Product_ID IN ($placeholders)";
                $stmt = $conn->prepare($sql);
                $types = str_repeat('i', count($selected_prd));
                $stmt->bind_param($types, ...$selected_prd);
                if ($stmt->execute()) {
                    $success_message = "Products set to sold successfully.";
                } else {
                    $error_message = "Error deactivating products.";
                }
                $stmt->close();
            } elseif ($action === 'delete2') {
                $sql = "DELETE FROM product WHERE Product_ID IN ($placeholders)";
                $stmt = $conn->prepare($sql);
                $types = str_repeat('i', count($selected_prd));
                $stmt->bind_param($types, ...$selected_prd);
                if ($stmt->execute()) {
                    $success_message = "Products deleted successfully.";
                } else {
                    $error_message = "Error deleting products.";
                }
                $stmt->close();
            }
        } else {
            $error_message = "Something error.";
        }
    }
}

//search user
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

$search_condition = '';
$search_params = [];
$search_types = '';
if (!empty($search)) {
    if (is_numeric($search)) {
        $search_condition = "WHERE User_ID = ? OR User_Name LIKE ?";
        $search_term = "%$search%";
        $search_params = [$search, $search_term];
        $search_types = 'is';
    } else {
        $search_condition = "WHERE User_Name LIKE ?";
        $search_term = "%$search%";
        $search_params = [$search_term];
        $search_types = 's';
    }
}

$count_sql = "SELECT COUNT(*) as total FROM user $search_condition";
if (!empty($search_params)) {
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param($search_types, ...$search_params);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $total_users = $count_result->fetch_assoc()['total'];
    $count_stmt->close();
} else {
    $count_result = mysqli_query($conn, $count_sql);
    $total_users = mysqli_fetch_assoc($count_result)['total'];
}

$total_pages = ceil($total_users / $per_page);

$users_sql = "SELECT User_ID, User_Name, User_Status FROM user $search_condition ORDER BY User_ID DESC LIMIT ? OFFSET ?";
if (!empty($search_params)) {
    $users_stmt = $conn->prepare($users_sql);
    $params = array_merge($search_params, [$per_page, $offset]);
    $types = $search_types . 'ii';
    $users_stmt->bind_param($types, ...$params);
    $users_stmt->execute();
    $users_result = $users_stmt->get_result();
} else {
    $users_stmt = $conn->prepare($users_sql);
    $users_stmt->bind_param('ii', $per_page, $offset);
    $users_stmt->execute();
    $users_result = $users_stmt->get_result();
}

$users = [];
while ($row = $users_result->fetch_assoc()) {
    $users[] = $row;
}
$users_stmt->close();

//feedback
$feedback_sql = "SELECT f.feedback_id, f.feedback_type, f.feedback_description, f.read_status, f.submitted_at, u.User_Name
                FROM feedback f 
                LEFT JOIN user u ON f.user_id = u.User_ID 
                ORDER BY f.submitted_at DESC 
                LIMIT 3";
$feedback_result = mysqli_query($conn, $feedback_sql);
$latest_feedback = [];
while ($row = mysqli_fetch_assoc($feedback_result)) {
    $latest_feedback[] = $row;
}


//Search Product
$searchProduct = isset($_GET['searchProduct']) ? trim($_GET['searchProduct']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

$search_condition = '';
$search_params = [];
$search_types = '';
if (!empty($searchProduct)) {
    if (is_numeric($searchProduct)) {
        $search_condition = "WHERE Product_ID = ? OR Title LIKE ?";
        $search_term = "%$searchProduct%";
        $search_params = [$searchProduct, $search_term];
        $search_types = 'is';
    } else {
        $search_condition = "WHERE Title LIKE ?";
        $search_term = "%$searchProduct%";
        $search_params = [$search_term];
        $search_types = 's';
    }
}

$count_sql = "SELECT COUNT(*) as total FROM product $search_condition";
if (!empty($search_params)) {
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param($search_types, ...$search_params);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $total_products = $count_result->fetch_assoc()['total'];
    $count_stmt->close();
} else {
    $count_result = mysqli_query($conn, $count_sql);
    $total_products = mysqli_fetch_assoc($count_result)['total'];
}

$total_pages = ceil($total_products / $per_page);

$products_sql = "SELECT Product_ID, Title, Status FROM product $search_condition ORDER BY Product_ID DESC LIMIT ? OFFSET ?";
if (!empty($search_params)) {
    $products_stmt = $conn->prepare($products_sql);
    $params = array_merge($search_params, [$per_page, $offset]);
    $types = $search_types . 'ii';
    $products_stmt->bind_param($types, ...$params);
    $products_stmt->execute();
    $products_result = $products_stmt->get_result();
} else {
    $products_stmt = $conn->prepare($products_sql);
    $products_stmt->bind_param('ii', $per_page, $offset);
    $products_stmt->execute();
    $products_result = $products_stmt->get_result();
}

$products = [];
while ($row = $products_result->fetch_assoc()) {
    $products[] = $row;
}
$products_stmt->close();

CloseCon($conn);
?>


