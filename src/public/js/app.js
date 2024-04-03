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
            // todo: 取得したデータを挿入する
            insertNewTweetItem(data.response.result);
            if (!formData.has("id")) form.reset();
          } else {
            // エラーが返却された場合
            displayErrors(data);
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

function insertNewTweetItem(data) {
  const newTweetHtml = `
    <div class="tweet-item mb-5">
        <a href="show?id=${data.post_id}" class="text-decoration-none text-dark">
            <div class="tweet-header d-flex align-items-center justify-content-between mb-3">
                <h5 class="tweet-title mb-0">${data.subject}</h5>
                <span class="tweet-date ms-2">${data.created_at}</span>
            </div>
            <p>${data.content}</p>
        </a>
        <a href="../../public/storage/${data.image_path}" target="_blank">
            <img src="../../public/storage/${data.image_path}" alt="New Tweet Image" class="img-fluid">
        </a>
        <div class="tweet-footer">
            <a href="path/to/detail-page.html" class="text-decoration-none text-dark">
                <i class="far fa-comment"> コメント</i>
            </a>
            <i class="far fa-share-square"> シェア</i>
        </div>
    </div>`;

  // 投稿用フォームの要素を取得
  const formContainer = document.querySelector(".form-container");

  // formContainer の次の要素として新しいツイートアイテムを挿入
  formContainer.insertAdjacentHTML("afterend", newTweetHtml);

  // すべてのツイートアイテムを取得
  const tweetItems = document.querySelectorAll(".tweet-item");

  // ツイートアイテムが15件を超える場合、末尾のアイテムを削除
  if (tweetItems.length > 15) {
    tweetItems[tweetItems.length - 1].remove();
  }
}

function displayErrors(data) {
  // 既存のエラーメッセージをクリア
  document.getElementById("error-subject").textContent = "";
  document.getElementById("error-content").textContent = "";
  document.getElementById("error-image").textContent = "";

  // エラーメッセージを表示
  if (data.response.errors.subject) {
    document.getElementById("error-subject").textContent =
      data.response.errors.subject.join(", ");
  }
  if (data.response.errors.content) {
    document.getElementById("error-content").textContent =
      data.response.errors.content.join(", ");
  }
  if (data.response.errors.image) {
    document.getElementById("error-image").textContent =
      data.response.errors.image.join(", ");
  }
}
