<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\PostDAO;
use Database\DatabaseManager;
use Models\Post;
use Models\DataTimeStamp;

class PostDAOImpl implements PostDAO
{
    public function create(Post $partData): bool
    {
        if($partData->getId() !== null) throw new \Exception('Cannot create a computer part with an existing ID. id: ' . $partData->getId());
        return $this->createOrUpdate($partData);
    }

    public function getById(int $id): ?Post
    {
        $mysqli = DatabaseManager::getMysqliConnection();
        $post = $mysqli->prepareAndFetchAll("SELECT * FROM posts WHERE post_id = ?",'i',[$id])[0]??null;

        return $post === null ? null : $this->resultToPost($post);
    }

    public function update(Post $postData): bool
    {
        if($postData->getId() === null) throw new \Exception('Post specified has no ID.');

        $current = $this->getById($postData->getId());
        if($current === null) throw new \Exception(sprintf("Post %s does not exist.", $postData->getId()));

        return $this->createOrUpdate($postData);
    }

    public function delete(int $id): bool
    {
        $mysqli = DatabaseManager::getMysqliConnection();
        return $mysqli->prepareAndExecute("DELETE FROM posts WHERE post_id = ?", 'i', [$id]);
    }

    public function getRandom(): ?Post
    {
        $mysqli = DatabaseManager::getMysqliConnection();
        $post = $mysqli->prepareAndFetchAll("SELECT * FROM posts ORDER BY RAND() LIMIT 1",'',[])[0]??null;

        return $post === null ? null : $this->resultToPost($post);
    }

    public function getAll(int $offset, int $limit): array
    {
        $mysqli = DatabaseManager::getMysqliConnection();

        // reply_to_idがNULLのものだけを選択し、作成日時の降順で並べる
        $query = "SELECT * FROM posts WHERE reply_to_id IS NULL ORDER BY created_at DESC LIMIT ?, ?";

        $results = $mysqli->prepareAndFetchAll($query, 'ii', [$offset, $limit]);

        return $results === null ? [] : $this->resultsToPosts($results);
    }


    public function getAllByReply(int $offset, int $limit, int $replyToId): array
    {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM posts WHERE reply_to_id = ? ORDER BY created_at ASC LIMIT ?, ?";

        // クエリ実行時にreplyToIdもパラメータとして渡す
        $results = $mysqli->prepareAndFetchAll($query, 'iii', [$replyToId, $offset, $limit]);

        return $results === null ? [] : $this->resultsToPosts($results);
    }



    public function createOrUpdate(Post $postData): bool
    {
        // DatabaseManagerのインスタンス取得
        $mysqli = DatabaseManager::getMysqliConnection();

        // INSERT INTO ... ON DUPLICATE KEY UPDATE クエリ
        $query = <<<SQL
            INSERT INTO posts (post_id, reply_to_id, subject, content, image_path, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                subject = VALUES(subject),
                content = VALUES(content),
                image_path = VALUES(image_path),
                updated_at = VALUES(updated_at);
        SQL;

        // クエリの実行
        $result = $mysqli->prepareAndExecute(
            $query,
            'iisssss',
            [
                $postData->getId(),
                $postData->getReplyToId(),
                $postData->getSubject(),
                $postData->getContent(),
                $postData->getImagePath(),
                $postData->getTimeStamp() ? $postData->getTimeStamp()->getCreatedAt() : null,
                $postData->getTimeStamp() ? $postData->getTimeStamp()->getUpdatedAt() : null
            ],
        );

        // 結果のチェック
        if(!$result) return false;

        // 新規作成された投稿のIDをセット
        if($postData->getId() === null) {
            $postData->setId($mysqli->insert_id);
        }

        return true;
    }

    private function resultToPost(array $data): Post
    {
        // Postモデルのインスタンス生成
        return new Post(
            id: $data['post_id'],
            replyToId: $data['reply_to_id'],
            subject: $data['subject'],
            content: $data['content'],
            imagePath: $data['image_path'],
            timeStamp: new DataTimeStamp($data['created_at'], $data['updated_at'])
        );
    }

    private function resultsToPosts(array $results): array
    {
        $posts = [];

        foreach($results as $result) {
            $posts[] = $this->resultToPost($result);
        }

        return $posts;
    }
}