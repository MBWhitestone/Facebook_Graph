<?php
/******************************************************************************
 *                                                                            *
 * File: group_posts.php                                                      *
 *                                                                            *
 * This file gives a basic implementation of generating web content from a    *
 * Facebook group timeline using the Graph API                                *
 *                                                                            *
 * Copyright 2018                                                             *
 *                                                                            *
 ******************************************************************************/

// Set personal credentialss
$access_token = 'YOUR_NICE_ACCESS_TOKEN';
$object_id    = 'YOUR_OBJECT_PAGE_ID';

/* Retrieve data from the Graph API */
function get_data($url) {
    $url = preg_replace('/\s+/', '', $url);
    $json = file_get_contents($url);
    $obj = json_decode($json, true);
    return $obj;
}

/* Show posts on the website */
function show_posts($posts) {
    echo "<hr/>";
    // Recognize hyperlinks
    $headers = 'http|https|ftp|ftps|scp';
    $regex   = "!($headers)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?!";

    // Show each post
    foreach ($posts as $post) {
        $im      = htmlspecialchars($post['full_picture']);
        $message = htmlspecialchars($post['message']);
        $s       = htmlspecialchars($post['source']);
        $message = preg_replace($regex, "<a href=\"\\0\">\\0</a>", $message);

        echo "<div style='width:100%; overflow:auto;'>";
        echo "<div style='width:50%; float:left;'>";

        // Show image or video
        if ($s != '') {
            echo "<iframe height='200' style='display: block; margin: 0 auto;'
                   src=$s></iframe>";
        } else if ($im != '') {
            echo "<img src=$im height='200' style='display: block;
                   margin: 0 auto;'/>";
        }

        // Show message
        echo "</div>";
        echo "<div style='width:48%; float:right;'>";
        echo "<p>$message</p>";
        echo "</div>";
        echo "</div>";
        echo "<hr/>";
    }
}

// Main
$url   = "https://graph.facebook.com/v3.0/$object_id/posts?access_token=
          $access_token&pretty=0&fields=source%2Cfull_picture%2Cdescription%2C
          created_time%2Cmessage%2Cpicture%2Cevent&limit=25";
$url   = get_data($url);
show_posts($url['data']);
?>
