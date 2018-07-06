<?php
/******************************************************************************
 *                                                                            *
 * File: album.php                                                            *
 *                                                                            *
 * This file gives a basic implementation of generating web content from a    *
 * Facebook group album using the Graph api                                   *
 *                                                                            *
 * Copyright 2018                                                             *
 *                                                                            *
 ******************************************************************************/

// Set personal credentials
$access_token = 'YOUR_NICE_ACCESS_TOKEN';
$object_id    = 'YOUR_OBJECT_PAGE_ID';

/* Give an overview of all albums */
function overview($albums) {
    foreach ($albums as $album) {
        $cover = htmlspecialchars($album['picture']['data']['url']);
        $name  = htmlspecialchars($album['name']);
        $id    = htmlspecialchars($album['id']);
        echo "<a href=?a=$id>";
        echo "<div style='float:left; padding:1px; position:relative;
              color:#00387d;'>";
        echo "<img src=$cover style='height: 200px; width: 200px' />";
        echo "<div style='width:200; position:absolute; bottom: 5px; left: 5px;'
              >$name</div>";
        echo "</div>";
        echo "</a>";
    }
}

/* Show a specific album */
function show_album($album, $name) {
    // Heading with album name
    echo "<h2 style='padding: 0px;'>$name</h2>";
    echo "<a href='?'>Terug</a><hr/>";

    // Show each image
    foreach ($album as $image) {
        $source = htmlspecialchars($image['images'][0]['source']);
        echo "<div style='float:left; padding:3px; position:relative;
              color:white;'>";
        echo "<img src=$source style='height: 200px; width: 200px' />";
        echo "</div>";
    }
}

/* Retrieve data from the Graph API */
function get_data($url) {
    $url = preg_replace('/\s+/', '', $url);
    $json = file_get_contents($url);
    $obj = json_decode($json, true);
    return $obj;
}

// Show an album
if (isset($_GET['a'])) {
    $id = htmlspecialchars($_GET["a"]);
    $url = "https://graph.facebook.com/v3.0/$id?fields=name%2Cphotos%7Bpicture
            %2Cimages%2Cwebp_images%7D&access_token=$access_token";
    $album = get_data($url);
    $name  = htmlspecialchars($album['name']);
    $album = $album['photos']['data'];
    show_album($album, $name);
// Show an overview
} else {
    $url = "https://graph.facebook.com/v3.0/$object_id/albums?fields=
            picture%7Burl%7D%2Cid%2Cname&access_token=$access_token";
    $albums = get_data($url);
    $albums = $albums['data'];
    overview($albums);
}
?>
