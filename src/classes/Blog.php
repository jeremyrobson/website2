<?php

require_once("vendor/erusev/parsedown/Parsedown.php");

/**
 * @author Jeremy Robson <jrobson23@jeremyrobson.com>
 * @link https://www.jeremyrobson.com
 */
class BlogPost {
    public $file;
    public $date;

    /**
     * @param string $file filename
     */
    function __construct($file) {
        $this->file = include(ROOT_DIR . "/src/posts/$file");
        $this->date = date("Y M d", strtotime($this->file["date"]));
    }

    /**
     * @return void
     */
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

    /**
     * @return void
     */
    function getData() {
        $parsedown = new Parsedown();
        extract($this->file);
        ob_start();
        include(TEMPLATE_DIR . "/" . $this->file["template"]);
        return ob_get_clean();
    }
}

/**
 * @author Jeremy Robson <jrobson23@jeremyrobson.com>
 * @link https://www.jeremyrobson.com
 */
class Blog {
    public $posts;

    function __construct() {
        $files = preg_grep("/^.+\.php$/", scandir(BLOG_DIR, SCANDIR_SORT_DESCENDING));

        $this->posts = array();

        foreach ($files as $key => $file) {
            $this->posts[$key] = new BlogPost($file);
        }
    }

    /**
     * @param string $sef is a search engine friendly string
     * 
     * @return Array Returns an array of posts matching the $sef
     */
    function getPosts($sef) {
        return array_filter(
            $this->posts,
            function ($post) use ($sef) {
                return $post->file["sef"] === $sef;
            }
        );
    }

    /**
     * @return void
     */
    function displayLinks() {
        $output = "<ul>";
        foreach ($this->posts as $post) {
            $output .= $post->getLink();
        }
        $output .= "</ul>";
        print $output;
    }

    /**
     * @param string $sef is a search engine friendly string
     * 
     * @return void
     */
    function display($sef) {
        $output = "";
        $posts = $this->getPosts($sef);
        foreach ($posts as $post) {
            $output .= $post->getData();
        }
        print $output;
    }

    /**
     * @param Array $post_data is an array of post data
     * 
     * @return void
     */
    function createPost($post_data) {
        $filename = BLOG_DIR . "/" . $post_data["sef"] . ".php";
        $arr = var_export($post_data, true);
        ob_start();
        require_once(TEMPLATE_DIR . "/post_data.php");
        $file_data = ob_get_contents();
        ob_end_clean();
        file_put_contents($filename, $file_data);
        chmod($filename, 0744);
    }
}

?>