<?php

echo $parsedown->text("# $title");

echo $parsedown->text("##### Posted by **$author** on _$date _");

echo $parsedown->text("&nbsp;");

echo $parsedown->text($body);

?>