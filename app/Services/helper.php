<?php
namespace App\Services;

use Carbon\Carbon;

/**
 * ページネーション用メソッド
 * 
 * @param array $tasks 取得したタスク全データ
 * @param int $current_page 現在のページ
 * @param array $request getパラメーター
 * @return array $data_list 表示用データ&&ページング表示用html
 */
function paginate(array $tasks, ?int $current_page, array $request): array
{
    $max_per_page = 10; //1ページあたりの最大表示件数
    $total_num = count($tasks); //全件数
    $total_page = intval(ceil($total_num / $max_per_page)); //総ページ数
    $start_num = $current_page === null ? 1 : ($current_page * $max_per_page) - $max_per_page; //表示ページの先頭
    
    if($total_page === 1 || $total_page === intval($current_page)) {
        $end_num = $total_num; // ページ数が1ページ以下or最終ページであれば、終端は総件数に等しい
    } else {
        $end_num = $start_num + $max_per_page; // 上記以外は、現在のページ × 1頁あたりの件数
    }

    $param = setParam($request);
    $data_array = array();
    $page_html = "";

    if(empty($current_page) && $end_num < $max_per_page) {
        for($i = 0; $i < $end_num; $i++ ) {
            array_push($data_array, $tasks[$i]);
        } 
    } elseif(empty($current_page) && $end_num > $max_per_page) {

        for($i = 0; $i < $max_per_page; $i++ ) {
            array_push($data_array, $tasks[$i]);
        } 
        for($i = 1; $i <= $total_page ; $i++) {
            if($i === 1) {
                $page_html .= "<li class='this'>{$i}</li>";
            } else {
                $page_html .= "<li><a href='?{$param}&page={$i}'>{$i}</a></li>";
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
                $page_html .= "<li><a href='?{$param}&page={$i}'>{$i}</a></li>";
            }
        }
    }
    $data_list = [$data_array, $page_html];
    return $data_list;
}

/**
 * GETパラメーターのhtml化
 * 
 * @param array $request GETリクエストによるパラメーター
 * @return string $param ページネーション用のパラメーター
 */
function setParam(array $request): string
{
    $param_array = array();
    foreach($request as $key => $value) {
        if($key === 'page') {
            continue;
        } else {
            array_push($param_array, "{$key}={$value}");
        }
    }
    $param = implode('&', $param_array);
    return $param;
}

/**
 * バリデーションエラー関連メッセージのセッション変数化
 * 
 * @param string $key 項目によってキーを設定 
 * @param string $msg 表示内容
 * @return void
 */
function flashMsg(string $key, string $msg): void {
    $_SESSION['error'][$key] = $msg;
}

/**
 * セッションに格納されたエラーメッセージの変数代入&&セッション変数の削除
 * 
 * @param array $flash_messages エラーメッセージのセッション変数
 * @return array $flash 項目=>エラｰメッセージの連想配列
 */
function flash(array $flash_messages): array {
    foreach($flash_messages as $key => $value) {
        $flash[$key] = $value;
    }
    unset($_SESSION['error']);
    return $flash;
}
/**
 * バリデーションエラー時のフォーム入力内容のセッション変数化
 * 
 * @param string $key 項目によってキーを設定 
 * @param string $value 保持内容
 * @return void
 */
function old_store(string $key, string $value): void {
    $_SESSION['old'][$key] = $value;
}

/**
 * セッションに格納されたフォーム入力内容の変数代入&&セッション変数の削除
 * 
 * @param array $old_inputs フォーム入力内容のセッション変数
 * @return array $old 項目=>エラｰメッセージの連想配列
 */
function old(array $old_inputs): array {
    foreach($old_inputs as $key => $value) {
        $old[$key] = $value;
    }
    unset($_SESSION['old']);
    return $old;
}

/**
 * エスケープ処理
 * 
 * @param string $str
 * @return string $str エスケープ処理後の文字列 
 */
function h(string $str): string
{
    htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    return $str;
}

/**
 * セッショントークンの生成
 * 
 * @param void
 * @return string $csrf_token トークン 
 */
function setToken(): string
 {
    $csrf_token = bin2hex(random_bytes(32));
    $_SESSION['token'] = $csrf_token;
    return $csrf_token;
}

/**
 * メンバー：送信アイコン判定用フラグ操作（メンバー➡マネージャーへのメッセージ返信（送信）時）
 * 
 * @param int $msg_flag 0|1 メッセージが送信されたら1、以降は変更なし（履歴を表示する為）
 * @param int $mem_to_mg 0|1|2 初期:0、メンバー->マネージャーに返信:1、マネージャー確認:2、以降は1|2
 * @param int $task_id タスクid
 * @return string 画面遷移用パラメーター&&アイコン画像タグのhtml
 */
function setSendIcon(int $msg_flag, int $mem_to_mg, int $task_id): string
{
    if($msg_flag === 0) {
        return "";
    } elseif($mem_to_mg === 1) {
        return "<a href='?mode=chat&id={$task_id}'><img src='../../images/hikoki.png'></a>";
    } elseif($mem_to_mg === 2) {
        return "<a href='?mode=chat&id={$task_id}'><img src='../../images/checkbox.png'></a>";
    }
    return "";
}

/**
 * マネージャー：送信アイコン判定用フラグ操作（マネージャー➡メンバーへのメッセージ送信時）
 * 
 * @param int $msg_flag 0|1 メッセージが送信されたら1、以降は変更なし（履歴を表示する為）
 * @param int $mg_to_mem 0|1 初期:0、マネージャー->メンバーに送信:1、メンバー確認:0
 * @param int $task_id タスクid
 * @return string 画面遷移用パラメーター&&アイコン画像タグのhtml
 */
function MgSetSendIcon(int $msg_flag, int $mg_to_mem, int $task_id): string
{
    if($msg_flag === 0) {
        return "";
    } elseif($mg_to_mem === 1) {
        return "<a href='?mode=chat&id={$task_id}'><img src='../../images/hikoki.png'></a>";
    } elseif($mg_to_mem === 0) {
        return "<a href='?mode=chat&id={$task_id}'><img src='../../images/checkbox.png'></a>";
    }
    return "";
}

/**
 * メンバー：受信アイコン判定用フラグ操作（マネージャーからのメッセージ受信時）
 * 
 * @param int $msg_flag 0|1 メッセージが送信されたら1、以降は変更なし（履歴を表示する為）
 * @param int $mg_to_mem 0|1 初期:0、メッセージ受信&&未読:1、メッセージ確認:0
 * @param int $task_id タスクid
 * @return string 画面遷移用パラメーター&&アイコン画像タグのhtml
 */
function setRecieveIcon(int $msg_flag, int $mg_to_mem, int $task_id): string
{
    if($msg_flag === 0) {
        return "";
    } elseif($mg_to_mem === 1) {
        return "<a href='?mode=chat&id={$task_id}'><img src='../../images/midoku.png'></a>";
    } elseif($mg_to_mem === 0) {
        return "<a href='?mode=chat&id={$task_id}'><img src='../../images/kidoku.png'></a>";
    }
    return "";
}

/**
 * マネージャー：受信アイコン判定用フラグ操作（メンバーからのメッセージ受信時）
 * 
 * @param int $msg_flag 0|1 メッセージが送信されたら1、以降は変更なし（履歴を表示する為）
 * @param int $mem_to_mg 0|1 初期:0、メッセージ受信&&未読:1、メッセージ確認:2 以降は1|2
 * @param int $task_id タスクid
 * @return string 画面遷移用パラメーター&&アイコン画像タグのhtml
 */
function MgSetReceiveIcon(int $msg_flag, int $mem_to_mg, int $task_id): string
{
    if($msg_flag === 0) {
        return "";
    } elseif($mem_to_mg === 1) {
        return "<a href='?mode=chat&id={$task_id}'><img src='../../images/midoku.png'></a>";
    } elseif($mem_to_mg === 2) {
        return "<a href='?mode=chat&id={$task_id}'><img src='../../images/kidoku.png'></a>";
    }
    return "";
}

/** 
 * マネージャー画面：タスク一覧の残日数計算
 * 
 * @param string $deadline 完了目標日
 * @return int $remain_date 現在との差分日数
 */
function diffDate(string $deadline): int
{
    $deadline_date = Carbon::parse($deadline);
    $remain_date = Carbon::today()->diffInDays($deadline_date);
    return $remain_date;
}

/**
 * メッセージボックス（chat）のコメント表示用のhtml生成
 * 
 * @param array $chats messageテーブルデータ
 * @param string $login_user member|manager
 * @return string $chat_html 埋め込み用html
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

