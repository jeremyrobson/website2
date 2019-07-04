<?php

class BlogPost {
    public $file;
    public $date;

    function __construct($file) {
        $this->file = include(ROOT_DIR . "/src/posts/$file");
        $this->date = date("Y M d", strtotime($this->file["date"]));
    }

    function getLink() {
        $sef = $this->file["sef"];
        $date = $this->date;
        $title = $this->file["title"];
        $author = $this->file["author"];
        $body = $this->file["body"];

        ob_start();
        include(TEMPLATE_DIR . "/link.php");
        return ob_get_clean();
    }

    function getData() {
        extract($this->file);
        ob_start();
        include(TEMPLATE_DIR . "/" . $this->file["template"]);
        return ob_get_clean();
    }
}

class Blog {
    public $posts;

    function __construct() {
        $files = preg_grep("/^.+\.php$/", scandir(BLOG_DIR, SCANDIR_SORT_DESCENDING));

        $this->posts = array();

        foreach ($files as $key => $file) {
            $this->posts[$key] = new BlogPost($file);
        }
    }

    function getPosts($sef) {
        return array_filter(
            $this->posts,
            function ($post) use ($sef) {
                return $post->file["sef"] === $sef;
            }
        );
    }

    function displayLinks() {
        $output = "<h5>Previous Posts</h5>";
        $output .= "<ul>";
        foreach ($this->posts as $post) {
            $output .= $post->getLink();
        }
        $output .= "</ul>";
        print $output;
    }

    function display($sef) {
        $output = "";
        $posts = $this->getPosts($sef);
        foreach ($posts as $post) {
            $output .= $post->getData();
        }
        print $output;
    }

    function createPost($post_data) {
        $filename = BLOG_DIR . "/" . $post_data["sef"] . ".php";
        $arr = var_export($post_data, true);
        ob_start();
        require_once(TEMPLATE_DIR . "/post_data.php");
        $file_data = ob_get_contents();
        file_put_contents($filename, $file_data);
    }
}

?>