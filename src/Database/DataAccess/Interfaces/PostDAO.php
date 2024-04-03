<?php

namespace Database\DataAccess\Interfaces;

use Models\Post;

interface PostDAO {
    public function create(Post $partData): bool;
    public function getById(int $id): ?Post;
    public function update(Post $partData): bool;
    public function delete(int $id): bool;
    public function createOrUpdate(Post $partData): bool;
    public function getRandom(): ?Post;

    /**
     * @param int $offset
     * @param int $limit
     * @return Post[]
     */
    public function getAll(int $offset, int $limit): array;

}