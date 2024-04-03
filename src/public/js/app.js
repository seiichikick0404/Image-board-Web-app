document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("upload-post-form");
  const submitBtn = document.getElementById("submit-btn");
  const loadingBtn = document.getElementById("loading-btn");

  form.addEventListener("submit", function (event) {
    event.preventDefault();

    // 投稿するボタンを非表示にし、投稿中ボタンを表示
    submitBtn.style.display = "none";
    loadingBtn.style.display = "inline-block";

    const formData = new FormData(form);

    setTimeout(() => {
      fetch("/form/save/post", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          // サーバからのレスポンスデータを処理します
          if (data.response.success) {
            console.log(data);
            if (!formData.has("id")) form.reset();
          } else {
            // エラーが返却された場合
            console.error("Server Error:", data.response.errors);
          }
        })
        .catch((error) => {
          // ネットワークエラーかJSONの解析エラー
          console.error("Error:", error);
        })
        .finally(() => {
          // 処理が完了したら、ボタンの表示を元に戻す
          submitBtn.style.display = "inline-block";
          loadingBtn.style.display = "none";
        });
    }, 1000);
  });
});
