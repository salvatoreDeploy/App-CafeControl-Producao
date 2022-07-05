<?php


namespace Source\App\Admin;


use Source\Models\Faq\Channel;
use Source\Models\Faq\Question;
use Source\Support\Pager;

/**
 * Class Faq
 * @package Source\App\Admin
 */
class Faq extends Admin
{
    /**
     * Faq constructor.
     */
    public function __construct()
    {
        parent::__construct();

        //Restrição por level editores level 6
        /*if($this->user->level < 6){}*/
    }

    /**
     * @param array|null $data
     */
    public function home(?array $data): void
    {
        $channels = (new Channel())->find();
        $paginator = new Pager(url("/admin/faq/home/"));
        $paginator->pager($channels->count(), 5, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | FAQs ",
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_THEME),
            false
        );
        echo $this->view->render("widgets/faqs/home", [
            "app" => "faq/home",
            "head" => $head,
            "channels" => $channels->order("channel")->limit($paginator->limit())->offset($paginator->offset())->fetch(true),
            "paginator" => $paginator->render()
        ]);
    }

    /**
     * @param array|null $data
     */
    public function channel(?array $data): void
    {
        //create
        if(!empty($data["action"]) && $data["action"] == "create"){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $channelCreate = new Channel();
            $channelCreate->channel = $data["channel"];
            $channelCreate->description = $data["description"];

            if(!$channelCreate->save()){
                $json["message"] = $channelCreate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Canal cadastrado com sucesso...")->flash();
            echo json_encode(["redirect" => url("/admin/faq/channel/{$channelCreate->id}")]);

            //var_dump($data);

            return;
        }

        //update
        if(!empty($data["action"]) && $data["action"] == "update"){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $channelEdit = (new Channel())->findById($data["channel_id"]);

            if(!$channelEdit){
                $this->message->error("Você tentou editar um canal que não existe ou foi excuido")->flash();
                echo json_encode(["redirect" => url("/admin/faq/home")]);
                return;
            }

            $channelEdit->channel = $data["channel"];
            $channelEdit->description = $data["description"];

            if(!$channelEdit->save()){
                $json["message"] = $channelEdit>message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("Canal atualizado com sucesso")->render();
            echo json_encode($json);

            //var_dump($data);

            return;
        }

        //delete
        if(!empty($data["action"]) && $data["action"] == "delete"){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $channelDelete = (new Channel())->findById($data["channel_id"]);

            if(!$channelDelete){
                $this->message->error("Você tentou deletar um canal que não existe ou foi excluido")->flash();
                echo json_encode(["redirect" => url("/admin/faq/home")]);
                return;
            }

            $channelDelete->destroy();
            $this->message->success("Canal excluido com sucesso...")->flash();
            echo json_encode(["redirect" => url("/admin/faq/home")]);

            //var_dump($data);

            return;
        }

        $channelEdit = null;
        if(!empty($data["channel_id"])){
            $channelId = filter_var($data["channel_id"], FILTER_VALIDATE_INT);
            $channelEdit = (new Channel())->findById($channelId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . ($channelEdit ? "FAQ: {$channelEdit->channel}" : "FAQ: Novo Canal"),
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_THEME),
            false
        );
        echo $this->view->render("widgets/faqs/channel", [
            "app" => "faq/home",
            "head" => $head,
            "channel" => $channelEdit
        ]);

    }

    /**
     * @param array|null $data
     */
    public function question(?array $data): void
    {
        //create
        if(!empty($data["action"]) && $data["action"] == "create"){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $questionCreate = new Question();
            $questionCreate->channel_id = $data["channel_id"];
            $questionCreate->question = $data["question"];
            $questionCreate->response = $data["response"];
            $questionCreate->order_by = $data["order_by"];

            if(!$questionCreate->save()){
                $json["message"] = $questionCreate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Pergunta cadastrada com sucesso...")->flash();
            echo json_encode(["redirect" => url("/admin/faq/question/{$questionCreate->channel_id}/{$questionCreate->id}")]);

            //var_dump($data);

            return;
        }

        //update
        if(!empty($data["action"]) && $data["action"] == "update"){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $questionEdit = (new Question())->findById($data["question_id"]);

            if(!$questionEdit){
                $this->message->error("Você tentou editar uma pergunta que não existe ou foi excuida")->flash();
                echo json_encode(["redirect" => url("/admin/faq/home")]);
                return;
            }

            $questionEdit->channel_id = $data["channel_id"];
            $questionEdit->question = $data["question"];
            $questionEdit->response = $data["response"];
            $questionEdit->order_by = $data["order_by"];

            if(!$questionEdit->save()){
                $json["message"] = $questionEdit>message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("Pergunta atualizada com sucesso")->render();
            echo json_encode($json);

            //var_dump($data);

            return;
        }

        //delete
        if(!empty($data["action"]) && $data["action"] == "delete"){
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $questionDelete = (new Question())->findById($data["question_id"]);

            if(!$questionDelete){
                $this->message->error("Você tentou deletar uma pergunta que não existe ou foi excluida")->flash();
                echo json_encode(["redirect" => url("/admin/faq/home")]);
                return;
            }

            $questionDelete->destroy();
            $this->message->success("Pergunta excluida com sucesso...")->flash();
            echo json_encode(["redirect" => url("/admin/faq/home")]);

            //var_dump($data);

            return;
        }

        $channel = (new Channel())->findById($data["channel_id"]);
        $question = null;

        if(!$channel){
            $this->message->warning("Você tentou gerenciar perguntas de um canal que nãoe existe")->flash();
            redirect("/admin/faq/home");
        }

        if(!empty($data["question_id"])){
            $questionId = filter_var($data["question_id"], FILTER_VALIDATE_INT);
            $question = (new Question())->findById($questionId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | FAQs: Perguntas em {$channel->channel}",
            CONF_SITE_DESC,
            url("/admin"),
            theme("/assets/images/image.jpg", CONF_VIEW_THEME),
            false
        );
        echo $this->view->render("widgets/faqs/question", [
            "app" => "faq/home",
            "head" => $head,
            "channel" => $channel,
            "question" => $question
        ]);
    }
}