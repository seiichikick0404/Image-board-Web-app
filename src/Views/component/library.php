<div class="container">
    <!-- 投稿用フォーム -->
    <div class="form-container">
        <form action="#" method="post" id="upload-post-form">
            <div class="mb-3">
                <input type="text" class="form-control" name="subject" id="title" placeholder="タイトル">
                <div id="error-subject" class="form-text text-danger"></div>
            </div>
            <div class="mb-3">
                <textarea class="form-control" id="content" name="content" rows="3" placeholder="内容"></textarea>
                <div id="error-content" class="form-text text-danger"></div>
            </div>
            <div class="mb-3">
                <label for="formFile" class="form-label">画像添付</label>
                <input class="form-control" name="image" type="file" id="formFile">
                <div id="error-image" class="form-text text-danger"></div>
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

    <!-- 投稿 -->
    <?php foreach ($posts as $post): ?>
        <div class="tweet-item mb-5">
        <a href="#" class="text-decoration-none text-dark">
            <h5 class="tweet-title"><?php echo(htmlspecialchars($post->getSubject())) ?></h5> <!-- タイトルを表示 -->
            <p><?php echo(htmlspecialchars($post->getContent())) ?></p>
        </a>

        <a href="../../public/storage/<?php echo(htmlspecialchars($post->getImagePath())) ?>" target="_blank">
            <img src="../../public/storage/<?php echo(htmlspecialchars($post->getImagePath())) ?>" alt="Tweet Image" class="img-fluid">
        </a>

        <div class="tweet-footer">
            <a href="path/to/detail-page.html" class="text-decoration-none text-dark">
                <i class="far fa-comment"> コメント</i>
            </a>
            <i class="far fa-share-square"> シェア</i>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<script src="../../public/js/app.js"></script>


