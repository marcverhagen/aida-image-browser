<?php

//include 'directories.php';
//include 'utils.php';

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<h1>AIDA Seedling Image browser</h1>

<p>This browser displays images from the AIDA SeedLing corpus. Currently it contains 250 images, which are the 250 images associated with the longest captions. Some images are annotated with some hints on what should be annotated on the image.</p>

<p>Note that this is not an image or text annotation tool. Its only purpose is to show images and to dig into how they should be annotated. The annotation functionality is not exposed to everyone yet, but will be soon. Also, once annotations are available there will be some search functionality that allows you to find images with certain objects or relations.</p>

<p>Available options:</p>

<ul>

<li><a href=view.php?mode=annotated>View annotated images</a>
    <blockquote>Annotated means there is an annotation file, which in some cases can be empty.</blockquote>
</li>

<li><a href=view.php?mode=commented>View commented images</a>
    <blockquote>Immages that are associated with an annotation file, which has a non-empty value for the comments section.</blockquote>
</li>

<li><a href=view.php?mode=all>View all images</a>
    <blockquote>May take a while to load.</blockquote>
</li>

<!--
<li><a href=list.php?mode=view>View list</a></li>
-->

</ul>

</body>
</html>
