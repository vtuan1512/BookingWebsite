<?php
while ($row = mysqli_fetch_assoc($query)) {
    $path = USERS_IMG_PATH;
    $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = '{$row['id']}' 
            OR outgoing_msg_id = '{$row['id']}') 
            AND (outgoing_msg_id = '{$outgoing_id}' OR incoming_msg_id = '{$outgoing_id}') 
            ORDER BY id DESC LIMIT 1";

    $query2 = mysqli_query($con, $sql2);
    $row2 = mysqli_fetch_assoc($query2);
    (mysqli_num_rows($query2) > 0) ? $result = $row2['msg'] : $result = "no message available";
    (strlen($result) > 28) ? $msg = substr($result, 0, 28) . "..." : $msg = $result;

    if (isset($row2['outgoing_msg_id'])) {
        ($outgoing_id == $row2['outgoing_msg_id']) ? $you = "You:" : $you = "";
    } else {
        $you = "";
    }
    ($row['status_chat'] == 'Offline now') ? $offline = "offline" : $offline = "";
    ($outgoing_id == $row['id']) ? $hid_me = "hide" : $hid_me = "";
    $output .= '<a href="chat_manager.php?user_id=' . $row['id'] . '">
            <div class="content">
                <img src="' . $path . $row['profile'] . '" alt="Profile Image">
                <div class = "details">
                    <span>'.$row['name'].'</span>
                    <p>'.$you.$msg.'</p>
                </div>
            </div>
            <div class="status-dot '.$offline.'"><i class="fas fa-circle"></i></div>
        </a>';
}
