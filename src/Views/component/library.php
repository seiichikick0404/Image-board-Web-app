<div class="container">
    <!-- 投稿用フォーム -->
    <div class="form-container">
        <form action="#" method="post" id="upload-post-form">
            <div class="mb-3">
                <input type="text" class="form-control" name="subject" id="title" placeholder="タイトル">
            </div>
            <div class="mb-3">
                <textarea class="form-control" id="content" name="content" rows="3" placeholder="内容"></textarea>
            </div>
            <div class="mb-3">
                <label for="formFile" class="form-label">画像添付</label>
                <input class="form-control" name="image" type="file" id="formFile">
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" id="submit-btn">投稿する</button>
                <button class="btn btn-primary" type="button" id="loading-btn" disabled style="display: none;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    投稿中...
                </button>
            </div>
        </form>
    </div>

    <!-- 匿名のツイートアイテム -->
    <div class="tweet-item mb-5">
        <a href="path/to/detail-page.html" class="text-decoration-none text-dark">
            <h5 class="tweet-title">サンプルタイトル</h5> <!-- タイトルを表示 -->
            <p>ここにツイートのテキストが表示されます。これはサンプルテキストです。</p>
        </a>

        <a href="../../public/images/sample.jpeg" target="_blank">
            <img src="../../public/images/sample.jpeg" alt="Tweet Image" class="img-fluid">
        </a>

        <div class="tweet-footer">
            <a href="path/to/detail-page.html" class="text-decoration-none text-dark">
                <i class="far fa-comment"> コメント</i>
            </a>
            <i class="far fa-share-square"> シェア</i>
        </div>
    </div>
    <!-- 他の匿名のツイートアイテム -->
</div>

<script src="../../public/js/app.js"></script>


