document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("upload-post-form");

  form.addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(form);

    fetch("/form/save/post", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        // サーバからのレスポンスデータを処理します
        if (data.response.success) {
          // 成功メッセージを表示したり、リダイレクトしたり、コンソールにログを出力する可能性があります
          console.log(data);
          alert("Update successful!");
          if (!formData.has("id")) form.reset();
        } else {
          // エラーが返却された場合
          console.error("Server Error:", data.response.errors);
        }
      })
      .catch((error) => {
        // ネットワークエラーかJSONの解析エラー
        console.error("Error:", error);
        alert("An error occurred. Please try again.");
      });
  });
});
