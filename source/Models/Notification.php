<?php


namespace Source\Models;


use Source\Core\Model;

/**
 * Class Notification
 * @package Source\Models
 */
class Notification extends Model
{
    /**
     * Notification constructor.
     */
    public function __construct()
 {
     parent::__construct("notifications", ["id"], ["image","title", "link"]);
 }

    public function byNotifications($image, $title, $link)
    {
        $this->image = $image;
        $this->title = $title;
        $this->link = $link;

        return $this;
    }

    public function save(): bool
    {
        return parent::save();
    }
}