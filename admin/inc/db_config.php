<?php
$hname = 'localhost';
$uname = 'root';
$pword = '';
$db = 'bookingweb';

// Create connection
$con = new mysqli($hname, $uname, $pword, $db);
// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

function filteration($data)
{
    if (!is_array($data)) {
        return $data;
    }
    foreach ($data as $key => $value) {
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        $value = strip_tags($value);

        // Update the value in the array
        $data[$key] = $value;
    }

    // Return the sanitized array
    return $data;
}


function selectAll($table)
{
    $con = $GLOBALS['con'];
    $res = mysqli_query($con, "SELECT*FROM $table");
    return  $res;
}
function select($sql, $value, $datatypes)
{
    $con = $GLOBALS['con'];
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$value);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
            return $result;
        } else {
            die("query can not be excuted-select");
        }
    } else {
        die("query can not be prepare -select ");
    }
}

function update($sql, $value, $datatypes)
{
    $con = $GLOBALS['con'];
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$value);
        if (mysqli_stmt_execute($stmt)) {
            $resul = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $resul;
        } else {
            die("query can not be excuted-update");
        }
    } else {
        die("query can not be prepare -update ");
    }
}

function insert($sql, $value, $datatypes)
{
    $con = $GLOBALS['con'];
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$value);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $result;
        } else {
            die("Query can not be excuted-Insert");
        }
    } else {
        die("Query can not be prepare -Insert");
    }
}

function delete($sql, $value, $datatypes)
{
    $con = $GLOBALS['con'];
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$value);
        if (mysqli_stmt_execute($stmt)) {
            $resul = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $resul;
        } else {
            die("query can not be excuted-Delete");
        }
    } else {
        die("query can not be prepare -Delete");
    }
}
?>