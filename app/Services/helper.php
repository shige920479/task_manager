<?php
namespace App\Services;

function paginate($tasks, $current_page, $base_url)
{
    $max_per_page = 10;
    $total_num = count($tasks);
    $total_page = intval(ceil($total_num / $max_per_page));
    $start_num = $current_page * $max_per_page - $max_per_page;
    if($total_page === intval($current_page)) {
        $end_num = $total_num;
    } else {
        $end_num = $current_page * $max_per_page;
    }

    $data_array = array();
    $page_html = "";

    if(empty($current_page)) {
        for($i = 0; $i < $max_per_page; $i++ ) {
        array_push($data_array, $tasks[$i]);
        } 
        for($i = 1; $i <= $total_page ; $i++) {
        if($i === 1) {
            $page_html .= "<li class='this'>{$i}</li>";
        } else {
            $page_html .= "<li><a href='{$base_url}?page={$i}'>{$i}</a></li>";
        }
        }
    } else {
        for($i = $start_num; $i < $end_num; $i++ ) {
        array_push($data_array, $tasks[$i]);
        } 
        for($i = 1; $i <= $total_page ; $i++) {
        if($i === intval($current_page)) {
            $page_html .= "<li class='this'>{$i}</li>";
        } else {
            $page_html .= "<li><a href='{$base_url}?page={$i}'>{$i}</a></li>";
        }
        }
    }
    $data_list = [$data_array, $page_html];
    return $data_list;
}



function flashMsg(string $key, string $msg): void {
    $_SESSION['error'][$key] = $msg;
}

function flash(array $flash_messages): array {
    foreach($flash_messages as $key => $value) {
        $flash[$key] = $value;
    }
    return $flash;
}

function old_store(string $key, string $value): void {
    $_SESSION['old'][$key] = $value;
}

function old(array $old_inputs): array {
    foreach($old_inputs as $key => $value) {
        $old[$key] = $value;
    }
    return $old;
}

function h($str) {
    htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    return $str;
}

function setToken() {
    $csrf_token = bin2hex(random_bytes(32));
    $_SESSION['token'] = $csrf_token;
    return $csrf_token;
}

// 後で比較演算子部分を修正 「==」
function setSendIcon($msg_flag, $mem_to_mg, $task_id) {
    if($msg_flag === 0) {
        return "";
    } elseif($mem_to_mg === 1) {
        return "<a href='./MemberChatView.php?id={$task_id}'><img src='../../images/hikoki.png'></a>";
    } elseif($mem_to_mg === 2) {
        return "<a href='./MemberChatView.php?id={$task_id}'><img src='../../images/checkbox.png'></a>";
    }
}

function MgSetSendIcon($msg_flag, $mg_to_mem, $task_id) {
    if($msg_flag === 0) {
        return "";
    } elseif($mg_to_mem === 1) {
        return "<a href='./ManagerChatView.php?id={$task_id}'><img src='../../images/hikoki.png'></a>";
    } elseif($mg_to_mem === 0) {
        return "<a href='./ManagerChatView.php?id={$task_id}'><img src='../../images/checkbox.png'></a>";
    }
}

function setRecieveIcon($msg_flag, $mg_to_mem, $task_id) {
    if($msg_flag === 0) {
        return "";
    } elseif($mg_to_mem === 1) {
        return "<a href='./MemberChatView.php?id={$task_id}'><img src='../../images/midoku.png'></a>";
    } elseif($mg_to_mem === 0) {
        return "<a href='./MemberChatView.php?id={$task_id}'><img src='../../images/kidoku.png'></a>";
    }
}
function MgSetReceiveIcon($msg_flag, $mem_to_mg, $task_id) {
    if($msg_flag === 0) {
        return "";
    } elseif($mem_to_mg === 1) {
        return "<a href='./ManagerChatView.php?id={$task_id}'><img src='../../images/midoku.png'></a>";
    } elseif($mem_to_mg === 2) {
        return "<a href='./ManagerChatView.php?id={$task_id}'><img src='../../images/kidoku.png'></a>";
    }
}

/** MemberChatView.php chat-roomのコメント表示html生成メソッド
 * param array messageテーブルデータ
 * return string 埋め込み用html
 */
function setChatHtml(array $chats, string $login_user): string
{
    $chat_html = "";
    foreach($chats as $chat) {
        $created_at = date('m/d H:i', strtotime($chat['created_at']));
        if($login_user === MEMBER) {
            if($chat['sender'] === 1) {
                $chat_html .= "<li class='chat me'><p class='mes'>";
                $chat_html .= $chat['comment'];
                $chat_html .= "<div class='status'>";
                $chat_html .= $created_at;
                $chat_html .= "</div></li>";
            } elseif($chat['sender'] === 0) {
                $chat_html .= "<li class='chat you'><p class='mes'>";
                $chat_html .= $chat['comment'];
                $chat_html .= "<div class='status'>";
                $chat_html .= $created_at;
                $chat_html .= "</div></li>";
            }
        } elseif($login_user === MANAGER) {
            if($chat['sender'] === 0) {
                $chat_html .= "<li class='chat me'><p class='mes'>";
                $chat_html .= $chat['comment'];
                $chat_html .= "<div class='status'>";
                $chat_html .= $created_at;
                $chat_html .= "</div></li>";
            } elseif($chat['sender'] === 1) {
                $chat_html .= "<li class='chat you'><p class='mes'>";
                $chat_html .= $chat['comment'];
                $chat_html .= "<div class='status'>";
                $chat_html .= $created_at;
                $chat_html .= "</div></li>";
            }
        }
    }
    return $chat_html;
}

/** 残日数の計算メソッド
 * param
 * return 
 */
