-- 'posts' テーブルに外部キー制約を追加
ALTER TABLE posts
ADD CONSTRAINT fk_posts_category
FOREIGN KEY (category_id) REFERENCES categories(category_id)
ON DELETE SET NULL ON UPDATE CASCADE;