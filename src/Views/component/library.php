<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フィード一覧</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <!-- 投稿用フォーム -->
    <div class="form-container">
        <form>
            <div class="mb-3">
                <input type="text" class="form-control" id="title" placeholder="タイトル">
            </div>
            <div class="mb-3">
                <textarea class="form-control" id="content" rows="3" placeholder="内容"></textarea>
            </div>
            <div class="mb-3">
                <label for="formFile" class="form-label">画像添付</label>
                <input class="form-control" type="file" id="formFile">
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">投稿する</button>
            </div>
        </form>
    </div>

    <!-- 匿名のツイートアイテム -->
    <div class="tweet-item mb-5">
        <a href="path/to/detail-page.html" class="text-decoration-none text-dark">
            <div class="tweet-body">
                <h5 class="tweet-title">サンプルタイトル</h5> <!-- タイトルを表示 -->
                <p>ここにツイートのテキストが表示されます。これはサンプルテキストです。</p>
                <img src="../../public/images/sample.jpeg" alt="Tweet Image">
            </div>
            <div class="tweet-footer">
                <i class="far fa-comment"> コメント</i>
                <i class="far fa-share-square"> シェア</i>
            </div>
        </a>
    </div>
    <!-- 他の匿名のツイートアイテム -->
</div>

</body>
</html>
