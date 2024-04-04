<div class="container">
        <!-- 投稿の詳細 -->
        <div class="post-container">
            <div class="tweet-header d-flex align-items-center justify-content-between mb-3">
                <h2 class="post-title"><?php echo(htmlspecialchars($post->getSubject())) ?></h2>
                <p class="post-date"><?php echo(htmlspecialchars($post->getTimestamp()->getCreatedAt())) ?></p>
            </div>
            <p class="post-content"><?php echo(htmlspecialchars($post->getContent())) ?></p>
            <a href="../../public/storage/<?php echo(htmlspecialchars($post->getImagePath())) ?>" target="_blank">
                <img src="../../public/storage/<?php echo(htmlspecialchars($post->getImagePath())) ?>" alt="投稿画像" class="post-image">
            </a>
        </div>

        <!-- 返信用フォーム -->
        <div class="reply-form container-fluid">
            <form>
                <div class="row">
                    <div class="col-12 col-md-7 mb-3">
                        <textarea class="form-control" rows="1" placeholder="内容"></textarea>
                    </div>
                    <div class="col-12 col-md-3 mb-3">
                        <input class="form-control" type="file">
                    </div>
                    <div class="col-12 col-md-2 d-flex align-items-end mb-3">
                        <button type="submit" class="btn btn-primary w-100">コメントする</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 投稿への返信を表示 -->
        <div class="reply-container">
            <div class="thread-line"></div>
            <div class="tweet-header d-flex align-items-center justify-content-between">
                <h2 class="post-title"></h2>
                <p class="post-date"><?php echo(htmlspecialchars($post->getTimestamp()->getCreatedAt())) ?></p>
            </div>
            <p class="post-content"><?php echo(htmlspecialchars($post->getContent())) ?></p>
            <a href="../../public/storage/<?php echo(htmlspecialchars($post->getImagePath())) ?>" target="_blank">
                <img src="../../public/storage/<?php echo(htmlspecialchars($post->getImagePath())) ?>" alt="投稿画像" class="post-image">
            </a>
        </div>

        <!-- 投稿への返信を表示 -->
        <div class="reply-container">
            <div class="thread-line"></div>
            <div class="tweet-header d-flex align-items-center justify-content-between">
                <h2 class="post-title"></h2>
                <p class="post-date"><?php echo(htmlspecialchars($post->getTimestamp()->getCreatedAt())) ?></p>
            </div>
            <p class="post-content"><?php echo(htmlspecialchars($post->getContent())) ?></p>
            <a href="../../public/storage/<?php echo(htmlspecialchars($post->getImagePath())) ?>" target="_blank">
                <!-- <img src="../../public/storage/<?php echo(htmlspecialchars($post->getImagePath())) ?>" alt="投稿画像" class="post-image"> -->
            </a>
        </div>
</div>