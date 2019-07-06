<?php
    $errors = array();
    $input = array(
        "secret_code" => @$_POST["secret_code"] ?? "",
        "title" => @$_POST["title"] ?? "",
        "author" => @$_POST["author"]?? "",
        "date" => @$_POST["date"] ?? date("Y-m-d"),
        "body" => @$_POST["body"]?? "",
    );

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sef = strtolower(str_replace(" ", "_", $input["title"]));

        $blog = new Blog();

        $posts = $blog->getPosts($sef);

        if (empty($secret_code) || $secret_code != $config["site"]["secret_code"]) {
            $errors["secret_code"] = "The code is incorrect";
        }

        if (count($posts) > 0) {
            $errors["title"] = "That title already exists!";
        }

        if (count($errors) === 0) {
            $blog->createPost(array(
                "date" => $input["date"],
                "title" => $input["title"],
                "author" => $input["author"],
                "sef" => $sef,
                "template" => "blog_post.php",
                "body" => $input["body"],
            ));
        }
        else {
            $alert = array(
                "type" => "danger",
                "title" => "Validation Error",
                "message" => "There are errors on the form"
            );
        }

        //header("Location: " . BASE_URL . "/main");
        //die();
    }
?>

<?php if (isset($alert)): include(TEMPLATE_DIR . "/alert.php"); endif; ?>

<form method="POST">
    <input type="hidden" id="page" name="page" value="editor" />
    <div class="form-group">
        <label for="secret_code">Secret Code</label>
        <input type="text" class="form-control" id="secret_code" name="secret_code" placeholder="Secret Code" required>
        <small class="text-danger"><?=@$errors["secret_code"]?></small>
    </div>
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="<?=@$input["title"]?>" placeholder="Title" required>
        <small class="text-danger"><?=@$errors["title"]?></small>
    </div>
    <div class="form-group">
        <label for="author">Author</label>
        <input type="text" class="form-control" id="author" name="author" value="<?=@$input["author"]?>" placeholder="Author" required>
        <small class="text-danger"><?=@$errors["author"]?></small>
    </div>
    <div class="form-group">
        <label for="date">Date</label>
        <input type="date" class="form-control" id="date" name="date" value="<?=@$input["date"]?>">
        <small class="text-danger"><?=@$errors["date"]?></small>
    </div>
    <div class="form-group">
        <label for="body">Body</label>
        <textarea type="text" class="form-control" id="body" name="body"><?=@$input["body"]?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>