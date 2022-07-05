<?php


namespace Source\App\Admin;


use Source\Models\Category;
use Source\Models\Post;
use Source\Models\User;
use Source\Support\Pager;
use Source\Support\Thumb;
use Source\Support\Upload;

class Blog extends  Admin
{
    public function __construct()
    {
        parent::__construct();
    }

    public function home(?array $data): void
    {
        //search redirect
        if(!empty($data["s"])){
            $s = str_search(data["s"]);
            echo json_encode(["redirect" => url("admin/blog/home/{$s}/1")]);
            return;
        }

        $search = null;
        $posts = (new Post())->find();

        if(!empty($data["search"]) && str_search($data["search"]) != "all"){
            $search = str_search($data["search"]);
            $posts = (new Post())->find("MATCH(title, subtitle) AGAINST(:s)", "s={$search}");
            if(!$posts->count()){
                $this->message->info("Sua pesquisa não retornou resultado")->flash();
                redirect("/admin/blog/home");
            }

        }

        $all = ($search ?? "all");
        $paginator = new Pager(url("/admin/blog/home/{$all}/"));
        $paginator->pager($posts->count(), 12, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Gerenciar Blog",
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_THEME),
            false
        );
        echo $this->view->render("widgets/blog/home", [
            "app" => "blog",
            "head" => $head,
            "posts" => $posts->limit($paginator->limit())->offset($paginator->offset())->order("post_at DESC")->fetch(true),
            "paginator" => $paginator->render(),
            "search" => $search
        ]);
    }

    public function post(?array $data): void
    {
        //MCE Upload
        if(!empty($data["upload"]) && !empty($_FILES["image"])){
            $files = $_FILES["image"];
            $upload = new Upload();
            $image = $upload->image($files, "post-" . time());

            if(!$image){
                $json["message"] = $upload->message()->render();
                echo json_encode($json);
                return;
            }

            $json["mce_image"] = '<img style="width: 100%;" src="' .url("/storage/{$image}") . '" alt="{title}" title="{title}">';
            echo json_encode($json);
            return;
        }
        //Create Post
        if(!empty($data["action"]) && $data["action"] == "create"){
            $content = $data["content"];
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $postCreate = new Post();
            $postCreate->author = $data["author"];
            $postCreate->category = $data["category"];
            $postCreate->title = $data["title"];
            $postCreate->uri = str_slug($postCreate->title);
            $postCreate->subtitle = $data["subtitle"];
            $postCreate->content = str_replace(["{title}"], [$postCreate->title], $content);
            $postCreate->video = $data["video"];
            $postCreate->status = $data["status"];
            $postCreate->post_at = date_fmt_back($data["post_at"]);

            //var_dump($data, $postCreate);

            //Upload Cover
            if(!empty($_FILES["cover"])){
                $files = $_FILES["cover"];
                $upload = new Upload();
                $image = $upload->image($files, $postCreate->title);

                if(!$image){
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $postCreate->cover = $image;
            }

            if(!$postCreate->save()){
                $json["message"] = $postCreate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Postagem publicada com sucesso...")->flash();
            $json["redirect"] = url("/admin/blog/post/{$postCreate->id}");
            echo json_encode($json);

            return;
        }
        //Update Post
        if(!empty($data["action"]) && $data["action"] == "update"){
            $content = $data["content"];
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $postEdit = (new Post())->findById($data["post_id"]);

            if(!$postEdit){
                $this->message->error("Você tentou atualizar um post que não existe ou foi removido")->flash();
                echo json_encode(["redirect" => url("/admin/blog/home")]);
                return;
            }

            $postEdit->author = $data["author"];
            $postEdit->category = $data["category"];
            $postEdit->title = $data["title"];
            $postEdit->uri = str_slug($postEdit->title);
            $postEdit->subtitle = $data["subtitle"];
            $postEdit->content = str_replace(["{title}"], [$postEdit->title], $content);
            $postEdit->video = $data["video"];
            $postEdit->status = $data["status"];
            $postEdit->post_at = date_fmt_back($data["post_at"]);

            //var_dump($data, $postCreate);

            //Upload Cover
            if(!empty($_FILES["cover"])){
                if($postEdit->cover && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postEdit->cover}")){
                    unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postEdit->cover}");
                    (new Thumb())->flush($postEdit->cover);
                }
                $files = $_FILES["cover"];
                $upload = new Upload();
                $image = $upload->image($files, $postEdit->title);

                if(!$image){
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $postEdit->cover = $image;
            }

            if(!$postEdit->save()){
                $json["message"] = $postEdit->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Postagem atualizado com sucesso...")->flash();
            echo json_encode(["reload" => true]);
            return;
        }

        //Delete
        if(!empty($data["action"]) && $data["action"] == "delete"){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $postDelete = (new Post())->findById($data["post_id"]);

            if(!$postDelete){
                $this->message-error("Você tentou excluir um post que não existe ou ja foi removido")->flash();
                echo json_encode(["reload" => true]);
                return;
            }

            if($postDelete->cover && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postDelete->cover}")){
                unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postDelete->cover}");
                (new Thumb())->flush($postDelete->cover);
            }

            $postDelete->destroy();
            $this->message->success("O Post foi excluido com sucesso")->flash();

            echo json_encode(["reload" => true]);
            return;
        }

        $postEdit = null;
        if(!empty($data["post_id"])){
            $postId = filter_var($data["post_id"], FILTER_VALIDATE_INT);
            $postEdit = (new Post())->findById($postId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . ($postEdit->title ?? "Novo Artigo"),
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_THEME),
            false
        );
        echo $this->view->render("widgets/blog/post", [
            "app" => "blog/post",
            "head" => $head,
            "post" => $postEdit,
            "categories" => (new Category())->find("type = :type", "type=post")->order("title")->fetch(true),
            "authors" => (new User())->find("level >=  :level", "level=5")->fetch(true)
        ]);
    }

    public function categories(?array $data): void
    {
        $categories = (new Category())->find();
        $paginator = new Pager(url("admin/blog/categories/"));
        $paginator->pager($categories->count(), 6, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Categorias",
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_THEME),
            false
        );
        echo $this->view->render("widgets/blog/categories", [
            "app" => "blog/categories",
            "head" => $head,
            "categories" => $categories->order("title")->limit($paginator->limit())->offset($paginator->offset())->fetch(true),
            "paginator" => $paginator->render()
        ]);
    }

    public function category(?array $data): void
    {
        //Create
        if (!empty($data["action"]) && $data["action"] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $categoryCreate = new Category();
            $categoryCreate->title = $data["title"];
            $categoryCreate->uri = str_slug($categoryCreate->title);
            $categoryCreate->description = $data["description"];

            //upload cover
            if (!empty($_FILES["cover"])) {
                $files = $_FILES["cover"];
                $upload = new Upload();
                $image = $upload->image($files, $categoryCreate->title);

                if (!$image) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $categoryCreate->cover = $image;
            }

            if (!$categoryCreate->save()) {
                $json["message"] = $categoryCreate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Categoria criada com sucesso...")->flash();
            $json["redirect"] = url("/admin/blog/category/{$categoryCreate->id}");

            echo json_encode($json);
            return;

            //var_dump($data);
        }
        //Update
        if(!empty($data["action"]) && $data["action"] == 'update'){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $categoryEdit = (new Category())->findById($data["category_id"]);

            if(!$categoryEdit){
                $this->message->error("Você tentou editar uma categoria que não existe ou foi removida")->flash();
                echo json_encode(["redirect" => url("/admin/blog/categories")]);
                return;
            }

            $categoryEdit->title = $data["title"];
            $categoryEdit->uri = str_slug($categoryEdit->title);
            $categoryEdit->description = $data["description"];

            //upload cover
            if (!empty($_FILES["cover"])) {
                if($categoryEdit->cover && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$categoryEdit->cover}")){
                    unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$categoryEdit->cover}");
                }
                $files = $_FILES["cover"];
                $upload = new Upload();
                $image = $upload->image($files, $categoryEdit->title);

                if (!$image) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $categoryEdit->cover = $image;
            }

            if (!$categoryEdit->save()) {
                $json["message"] = $categoryEdit->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Categoria atualizada com sucesso...")->flash();
            echo json_encode(["reload" => true]);

            //var_dump($data);

            return;
        }
        //Delete
        if(!empty($data["action"]) && $data["action"] == 'delete'){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $categoryDelete = (new Category())->findById($data["category_id"]);

            if(!$categoryDelete){
                $json["message"] = $this->message->error("A categoria não existe ou ja foi excluida antes")->render();
                echo json_encode($json);
                return;
            }

            if($categoryDelete->posts()->count()){
                $json["message"] = $this->message->warning("Não é possivel remover pois existe posts cadastrados")->render();
                echo json_encode($json);
                return;
            }

            if($categoryDelete->cover && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$categoryDelete->cover}")){
                unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$categoryDelete->cover}");
                (new Thumb())->flush($categoryDelete->cover);
            }

            $categoryDelete->destroy();

            $this->message->success("A categoria foi excuida com sucesso")->flash();
            echo json_encode(["reload" => true]);

            return;
        }

        $categoryEdit = null;
        if(!empty($data["category_id"])){
            $categoryId = filter_var($data["category_id"], FILTER_VALIDATE_INT);
            $categoryEdit = (new Category())->findById($categoryId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Categorias",
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_THEME),
            false
        );
        echo $this->view->render("widgets/blog/category", [
            "app" => "blog/categories",
            "head" => $head,
            "category" => $categoryEdit

        ]);
    }
}