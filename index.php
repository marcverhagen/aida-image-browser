<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<h1>AIDA Image browser</h1>

<p>This browser displays images with their captions. It contains 461 images, 319 from the AIDA Seedling corpus and 142 from the diffbot data. The diffbot data used were just the one with English captions (that is, images in the archive imgs_with_english_captions.zip on the <a href="https://drive.google.com/drive/folders/19x5fhrx2qPZzJkT1e4EL6HzXl20TK4H3?ths=true">Google Drive</a>. Of the images in that archive, 131 had captions that were identical to a captions in the image-caption pairs from the Seedling corpus and these were not included.</p>

<p>Some images are annotated with some hints on what should be annotated on the image.</p>

<p>Note that this is not an image or text annotation tool. Its only purpose is to show images and to dig into how they should be annotated. The annotation functionality is not exposed to everyone yet, but will be at some point. When more annotations are available there will be some search functionality that allows you to find images with certain objects or relations (now the search functionality available is simple a list of verbs and nouns found in the captions).</p>

<p>Some captions from the Seedling corpus are not in English, these captions are sometimes presented as gibberish, not sure whether this is a browser issue or an issue with the code.</p>

<p>Available options:</p>

<ul>

<li><a href=view.php?mode=annotated>View annotated images</a>
      <blockquote>
      <p>Annotated means there is an annotation file, which in some cases can be empty.</p>
      <p>As of May 3rd 2018, only 4 images are annotated but the annotations there are just for testing the tool and should not be considered serious. Real annotations will be added.</p>
      </blockquote>
</li>

<li><a href=view.php?mode=commented>View commented images</a>
    <blockquote>Immages that are associated with an annotation file, which has a non-empty value for the comments section.</blockquote>
</li>

<li><a href=view.php?mode=all>View all images</a>
    <blockquote>Showing 10 images per page.</blockquote>
</li>

<li><a href=term_index.php>View all terms</a>
    <blockquote>Shows a list of all terms found in the captions. Each term can be clicked and leads to page with all images where the caption contained that term.</blockquote>
</li>

<!--
<li><a href=list.php?mode=view>View list</a></li>
-->

</ul>

</body>
</html>
