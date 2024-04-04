document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("reply-post-form");
  const submitBtn = document.getElementById("submit-btn");
  const loadingBtn = document.getElementById("loading-btn");

  form.addEventListener("submit", function (event) {
    event.preventDefault();

    // 投稿するボタンを非表示にし、投稿中ボタンを表示
    submitBtn.style.display = "none";
    loadingBtn.style.display = "inline-block";

    const formData = new FormData(form);

    setTimeout(() => {
      fetch("/form/save/reply", {
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
  // 画像が存在する場合のみ、画像タグを生成
  let imageHtml = "";
  if (data.image_path) {
    imageHtml = `<a href="../../public/storage/${data.image_path}" target="_blank">
                        <img src="../../public/storage/${data.image_path}" alt="返信画像" class="post-image">
                    </a>`;
  }
  const newTweetHtml = `
      <div class="reply-container">
          <div class="thread-line"></div>
          <div class="tweet-header d-flex align-items-center justify-content-between">
              <h2 class="post-title"></h2>
              <p class="post-date">${data.created_at}</p>
          </div>
          <p class="post-content">${data.content}</p>
          ${imageHtml}
      </div>`;

  // すべての返信コンテナを取得
  const replyContainers = document.querySelectorAll(".reply-container");
  const lastReplyContainer = replyContainers[replyContainers.length - 1];

  // 返信用フォームコンテナを取得
  const formContainer = document.querySelector(".reply-form-container");

  if (replyContainers.length > 0) {
    // 返信がある場合は、最後の返信の後ろに新しい返信を挿入
    lastReplyContainer.insertAdjacentHTML("afterend", newTweetHtml);
  } else {
    // 返信がない場合は、返信フォームの後ろに新しい返信を挿入
    formContainer.insertAdjacentHTML("afterend", newTweetHtml);
  }

  // ツイートアイテムが10件を超える場合、末尾のアイテムを削除
  const maxPostCount = 10;
  const tweetItems = document.querySelectorAll(".tweet-item");
  if (tweetItems.length > maxPostCount) {
    tweetItems[0].remove();
  }
}

function displayErrors(data) {
  // エラー要素の初期化
  document.getElementById("error-content").textContent = "";
  document.getElementById("error-image").textContent = "";

  // コンテンツのエラーがあれば表示
  if (data.response.errors.content) {
    document.getElementById("error-content").textContent =
      data.response.errors.content.join(", ");
  }

  // 画像のエラーがあれば表示
  if (data.response.errors.image) {
    document.getElementById("error-image").textContent =
      data.response.errors.image.join(", ");
  }
}
