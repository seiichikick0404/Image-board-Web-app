<?php
session_start();
date_default_timezone_set('Asia/Tokyo');

use Helpers\ValidationHelper;
use Helpers\ImageHelper;
use Models\ComputerPart;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Database\DataAccess\Implementations\ComputerPartDAOImpl;
use Database\DataAccess\Implementations\PostDAOImpl;
use Models\Post;
use Response\Render\JSONRenderer;
use Types\ValueType;
use Models\DataTimeStamp;


return [
    'posts/library' => function(): HTTPRenderer{
        $postDao = new PostDAOImpl();
        $offset = 0;
        $limit = 15;
        $posts = $postDao->getAll($offset, $limit);
        return new HTMLRenderer('component/library', ['posts' => $posts]);
    },
    'posts/show' => function(): HTTPRenderer {
        $postId = ValidationHelper::integer($_GET['id']??null);
        $postDao = new PostDAOImpl();
        $post = $postDao->getById($postId);
        $post->setReplyToId($postId);

        return new HTMLRenderer('component/show', ['post'=>$post]);
    },
    'form/save/post' => function(): JSONRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method!');
            }

            $subject = isset($_POST['subject']) ? $_POST['subject'] : null;
            $content = isset($_POST['content']) ? $_POST['content'] : null;
            $image = isset($_FILES['image']) ? $_FILES['image'] : null;

            $validated = ValidationHelper::saveRequest($subject, $content, $image);

            if ($validated['success']) {
                $currentDateTime = date('Y-m-d H:i:s');
                $timeStamp = new DataTimeStamp($currentDateTime, $currentDateTime);

                // 画像の保存処理
                $imageHelper = new ImageHelper();
                $imagePath = $imageHelper->saveImageFile($image);

                if ($imagePath === "") throw new Exception('Failed to save image file');

                // 保存するPostオブジェクトの生成
                $post = new Post(
                    content: $content,
                    subject: $subject,
                    imagePath: $imagePath,
                    timeStamp: $timeStamp
                );

                $postDao = new PostDaoImpl();
                $postDao->create($post);
                $createdPostData = $postDao->getById($post->getId());
                $timeStamp = $createdPostData->getTimeStamp();

                $validated['result'] = [
                    'post_id' => $createdPostData->getId(),
                    'reply_to_id' => $createdPostData->getReplyToId(),
                    'subject' => $createdPostData->getSubject(),
                    'content' => $createdPostData->getContent(),
                    'image_path' => $createdPostData->getImagePath(),
                    'created_at' => $timeStamp->getCreatedAt(),
                    'updated_at' => $timeStamp->getUpdatedAt(),
                ];
            }

            return new JSONRenderer(['response' => $validated]);

        } catch(\Exception $e) {
            $errorResponse = [
                'success' => false,
                'errors' => [
                    'server' => $e->getMessage()
                ]
            ];
            return new JSONRenderer(['response' => $errorResponse]);
        }
    },
    'form/save/reply' => function(): JSONRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method!');
            }

            $content = isset($_POST['content']) ? $_POST['content'] : null;
            $image = isset($_FILES['image']) ? $_FILES['image'] : null;
            $replyToId = isset($_POST['reply_to_id']) ? ValidationHelper::integer($_POST['reply_to_id']) : null;

            $validated = ValidationHelper::saveRequestByReply($content, $image, $replyToId);

            if ($validated['success']) {
                $currentDateTime = date('Y-m-d H:i:s');
                $timeStamp = new DataTimeStamp($currentDateTime, $currentDateTime);

                $imagePath = "";
                // 画像の保存処理
                if (!empty($image['name'])) {
                    $imageHelper = new ImageHelper();
                    $imagePath = $imageHelper->saveImageFile($image);

                    if ($imagePath === "") throw new Exception('Failed to save image file');
                }

                // 保存するPostオブジェクトの生成
                $post = new Post(
                    content: $content,
                    replyToId: $replyToId,
                    subject: null,
                    imagePath: $imagePath,
                    timeStamp: $timeStamp
                );

                $postDao = new PostDaoImpl();
                $postDao->create($post);
                $createdPostData = $postDao->getById($post->getId());
                $timeStamp = $createdPostData->getTimeStamp();

                $validated['result'] = [
                    'post_id' => $createdPostData->getId(),
                    'reply_to_id' => $createdPostData->getReplyToId(),
                    'content' => $createdPostData->getContent(),
                    'image_path' => $createdPostData->getImagePath(),
                    'created_at' => $timeStamp->getCreatedAt(),
                    'updated_at' => $timeStamp->getUpdatedAt(),
                ];
            }

            return new JSONRenderer(['response' => $validated]);

        } catch(\Exception $e) {
            $errorResponse = [
                'success' => false,
                'errors' => [
                    'server' => $e->getMessage()
                ]
            ];
            return new JSONRenderer(['response' => $errorResponse]);
        }
    },
    'form/update/part' => function(): HTTPRenderer {
        try {
            // リクエストメソッドがPOSTかどうかをチェックします
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method!');
            }

            $required_fields = [
                'name' => ValueType::STRING,
                'type' => ValueType::STRING,
                'brand' => ValueType::STRING,
                'modelNumber' => ValueType::STRING,
                'releaseDate' => ValueType::DATE,
                'description' => ValueType::STRING,
                'performanceScore' => ValueType::INT,
                'marketPrice' => ValueType::FLOAT,
                'rsm' => ValueType::FLOAT,
                'powerConsumptionW' => ValueType::FLOAT,
                'lengthM' => ValueType::FLOAT,
                'widthM' => ValueType::FLOAT,
                'heightM' => ValueType::FLOAT,
                'lifespan' => ValueType::INT,
            ];

            $partDao = new ComputerPartDAOImpl();

            // 入力に対する単純なバリデーション。実際のシナリオでは、要件を満たす完全なバリデーションが必要になることがあります。
            $validatedData = ValidationHelper::validateFields($required_fields, $_POST);

            if(isset($_POST['id'])) $validatedData['id'] = ValidationHelper::integer($_POST['id']);

            // 名前付き引数を持つ新しいComputerPartオブジェクトの作成＋アンパッキング
            $part = new ComputerPart(...$validatedData);

            error_log(json_encode($part->toArray(), JSON_PRETTY_PRINT));

            // 新しい部品情報でデータベースの更新を試みます。
            // 別の方法として、createOrUpdateを実行することもできます。
            if(isset($validatedData['id'])) $success = $partDao->update($part);
            else $success = $partDao->create($part);

            if (!$success) {
                throw new Exception('Database update failed!');
            }

            return new JSONRenderer(['status' => 'success', 'message' => 'Part updated successfully']);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage()); // エラーログはPHPのログやstdoutから見ることができます。
            return new JSONRenderer(['status' => 'error', 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return new JSONRenderer(['status' => 'error', 'message' => $e->getMessage()]);
        }
    },'delete/part' => function(): HTMLRenderer {
        $part = null;
        $partDao = new ComputerPartDAOImpl();
        if(isset($_GET['id'])){
            $id = ValidationHelper::integer($_GET['id']);
            $result = $partDao->delete($id);

            $message = "削除処理が失敗しました。";
            if ($result) {
                $message = "id" . $id . "のレコードが削除されました。";
            }
        }
        return new HTMLRenderer('component/notice',['message'=>$message]);
    },
];
