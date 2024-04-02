<?php
session_start();
date_default_timezone_set('Asia/Tokyo');

use Helpers\ValidationHelper;
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
        return new HTMLRenderer('component/library', ['items'=>""]);
    },
    'posts/show' => function(): HTTPRenderer {
        $postId = ValidationHelper::integer($_GET['id']??null);
        return new HTMLRenderer('component/show', ['item'=>""]);
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
                // テスト用の画像パス
                $imagePath = "1a/gaergregrgrg.png";

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

                $validated['data'] = $createdPostData;
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
    'update/part' => function(): HTMLRenderer {
        $part = null;
        $partDao = new ComputerPartDAOImpl();
        if(isset($_GET['id'])){
            $id = ValidationHelper::integer($_GET['id']);
            $part = $partDao->getById($id);
        }
        return new HTMLRenderer('component/update-computer-part',['part'=>$part]);
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
    },'parts/all' => function(): HTMLRenderer {
        $partDao = new ComputerPartDAOImpl();
        $result = $partDao->getAll(0, 15);

        return new HTMLRenderer('component/computer-part-list',['result'=>$result]);
    },'parts/type'=>function(): HTTPRenderer{
        $partDao = new ComputerPartDAOImpl();
        if(isset($_GET['type'])){
            $type = ValidationHelper::string($_GET['type']);
            $result = $partDao->getAllByType($type, 0, 15);
        }

        if($result === null) throw new Exception('No parts are available!');

        return new HTMLRenderer('component/computer-part-list', ['result'=>$result]);
    },
];
