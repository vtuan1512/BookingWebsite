<?php
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
// ini_set("SMTP", "smtp.example.com");
// ini_set("smtp_port", "587"); // Example port for SMTP submission
// adminLogin()


if (isset($_GET['seen'])) {
    $frm_data = filteration($_GET);

    if ($frm_data['seen'] == 'all') {
        $q = "UPDATE `user_queries` SET `seen`=? ";
        $values = [1];
        if (update($q, $values, 'i')) {
            alert('success', 'Query marked all as read !');
        } else {
            alert('error', 'Error in marking query as seen !');
        }
    } else {
        $q = "UPDATE `user_queries` SET `seen`=? WHERE `id`=?";
        $values = [1, $frm_data['seen']];
        if (update($q, $values, 'ii')) {
            alert('success', 'Query marked as read !');
        } else {
            alert('error', 'Error in marking query as seen !');
        }
    }
}
// if (isset($_GET['seen'])) {
//     $frm_data = filteration($_GET);

//     if ($frm_data['seen'] == 'all') {
//         $q = "UPDATE `user_queries` SET `seen`=? ";
//         $values = [1];
//         if (update($q, $values, 'i')) {
//             alert('success', 'Query marked all as read !');
//         } else {
//             alert('error', 'Error in marking query as seen !');
//         }
//     } else {
//         $q = "UPDATE `user_queries` SET `seen`=? WHERE `id`=?";
//         $values = [1, $frm_data['seen']];
//         if (update($q, $values, 'ii')) {
//             // Retrieve user email
//             $email_query = "SELECT email FROM `user_queries` WHERE `id`=?";
//             $email_result = select($email_query, [$frm_data['seen']], 'i');
//             $user_email = mysqli_fetch_assoc($email_result)['email'];

//             // Send email to user
//             $to = $user_email;
//             $subject = "Your query has been marked as read";
//             $message = "Your query has been marked as read by the administrator.";
//             $headers = "From: tuanva.ba11-097@st.edu.usth.vn";

//             if (mail($to, $subject, $message, $headers)) {
//                 alert('success', 'Query marked as read and email sent!');
//             } else {
//                 alert('error', 'Error sending email to user!');
//             }
//         } else {
//             alert('error', 'Error in marking query as seen !');
//         }
//     }
// }

if (isset($_GET['del'])) {
    $frm_data = filteration($_GET);

    if ($frm_data['del'] == 'all') {
        $q = "DELETE FROM `user_queries`";
        if (mysqli_query($con, $q)) {
            alert('success', 'All data deleted !');
        } else {
            alert('error', 'Error in delete all query ! ');
        }
    } else {
        $q = "DELETE FROM `user_queries` WHERE `id`=?";
        $values = [$frm_data['del']];
        if (update($q, $values, 'i')) {
            alert('success', 'Data deleted !');
        } else {
            alert('error', 'Error in delete query ! ');
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin User Queries</title>
    <?php require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/links.php') ?>
</head>
<style>
    #dashboard-menu {
        height: 100%;
        position: fixed;
        z-index: 11;
    }

    @media screen and (max-width: 992px) {

        #dashboard-menu {
            height: auto;
            width: 100%;
        }

        #main-content {
            margin-top: 60px;
        }
    }

    tr.bg-dark.text-light th {
        background-color: black;
        color: #ffffff;
    }
</style>

<body class="bg-light">

    <?php require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/header.php') ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">User Queries</h3>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="text-end mb-4">
                            <a href="?seen=all" class="btn btn-dark rounded-pill shadow-none btn-sm">
                                <i class="bi bi-check-all"></i>Mark all read
                            </a>
                            <a href="?del=all" class="btn btn-danger rounded-pill shadow-none btn-sm">
                                <i class="bi bi-trash"></i>Delete all
                            </a>
                        </div>
                        <div class="table-responsive-md" style="height: 650px; overflow-y:scroll;">
                            <table class="table table-hover border">
                                <thead class="sticky-top">
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col" width="20%">Subject</th>
                                        <th scope="col" width="30%">Message</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $q = "SELECT * FROM `user_queries` ORDER BY 'id' DESC";
                                    $data = mysqli_query($con, $q);
                                    $i = 1;
                                    while ($row = mysqli_fetch_assoc(($data))) {
                                        $seen = '';
                                        if ($row['seen'] != 1) {
                                            $seen = "<a href='?seen=$row[id]' class='btn btn-sm rounded-pill btn-primary'> Mark as read </a> <br>";
                                        }
                                        $seen .= "<a href='?del=$row[id]' class='btn btn-sm rounded-pill btn-danger mt-2'>Delete</a>";
                                        echo <<<query
                                                <tr>
                                                    <td>$i</td>
                                                    <td>$row[name]</td>
                                                    <td>$row[email]</td>
                                                    <td>$row[subject]</td>
                                                    <td>$row[message]</td>
                                                    <td>$row[date]</td>
                                                    <td>$seen</td>
                                                </tr>
                                            query;
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/scripts.php') ?>



</body>

</html>