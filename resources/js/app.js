$(function() {
    console.log("✅ app.js 読み込み成功");

    let quantity = 0;

    function updateQuantityDisplay() {
        $("#quantity").val(quantity.toLocaleString());
    }

    $("#plus").on("click", function() {
        if (quantity < 9999) {
            quantity++;
            updateQuantityDisplay();
        }
    });

    $("#minus").on("click", function() {
        if (quantity > 0) {
            quantity--;
            updateQuantityDisplay();
        }
    });

    // 現在時刻リアルタイム更新
    function updateClock() {
        const now = new Date();
        const hh = String(now.getHours()).padStart(2, "0");
        const mm = String(now.getMinutes()).padStart(2, "0");
        const ss = String(now.getSeconds()).padStart(2, "0");
        $("#clock").text(`${hh}:${mm}:${ss}`);
    }

    setInterval(updateClock, 1000);
    updateClock();

    // 合計数量ボタン
    $("#sumBtn").on("click", function() {
        let total = 0;
        $(".chk-qty:checked").each(function() {
            total += parseInt($(this).data("qty"), 10);
        });

        // 行の背景色を更新
        $("#listTable tr").each(function(index) {
            const chk = $(this).find(".chk-qty");
            if (chk.is(":checked")) {
                $(this).removeClass("table-primary table-light").addClass("table-success");
            } else {
                $(this).removeClass("table-success");
                if (index % 2 === 0) $(this).addClass("table-light");
                else $(this).addClass("table-primary");
            }
        });

        alert(`合計 ${total.toLocaleString()} です。`);
    });

    // クリア前に確認
    $("#clearForm").on("submit", function(e) {
        if (!confirm("本当に全件削除しますか？")) {
            e.preventDefault();
        }
    });
});
