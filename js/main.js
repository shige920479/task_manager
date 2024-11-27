$(function () {
  //完了処理ボタンのイベント
  $(".comp-btn").on("click", function () {
    if (confirm("このタスクは完了処理しても宜しいですか？")) {
      $(this).submit();
    } else {
      return false;
    }
  });
  //完全削除ボタンのイベント
  $(".del-btn").on("click", function () {
    if (confirm("完全に削除しますが宜しいですか？")) {
      $(this).submit();
    } else {
      return false;
    }
    //ログアウト
  });
  $("#logout-btn").on("click", function () {
    if (confirm("ログアウトしますか？")) {
      $(this).submit();
    } else {
      return false;
    }
  });

  //完了処理
  // $("#form").submit(function () {
  //   let checks = [];
  //   $("[name='done[]']:checked").each(function () {
  //     checks.push(this.value);
  //   });

  //   $.ajax({
  //     type: "POST",
  //     url: "../Controller/DeleteController.php",
  //     data: {
  //       id: checks,
  //     },
  //     success: function (data) {
  //       if (data != "") {
  //         alert("完了処理を行いました。");
  //       }
  //     },
  //   });
  //   return false;
  // });
});
