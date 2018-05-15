# AIDA Image Browser

This browser gives access to images used for annotation in the AIDA-RAMFIS project.

To create a usable browser you need to clone this directory to a directory on a web server and the first create content with images and captions:

1. Create a directory `data/SOME_DATA_SET` with subdirectories `images`, `captions` and `annotations`.

1. The `data/SOME_DATA_SET/images` directory should contain a list of images with the `.jpg` extension (an opportunistic choice driven by laziness, some changes will be made so that other extension are allowed as well).

1. For each image the caption should be stored in a text file in `data/SOME_DATA_SET/captions`. The caption file has the same name it has `.txt` instead of `.jpg` as its extension.

1. The `annotations` directory should be writable by the process under which PHP runs on the server. Find out what user this is in PHP with `posix_getpwuid(posix_geteuid())['name']`. On Mac OSX the user is `_www` and you can set the group of `annotations` to `everyone` (`_www` is one of the users in this group) and set the permissions with `chmod 775 annotations`. On Linux the user tends to be `apache`, which is in the `apache` group. A simpler and more brute force approach is to make the directory world writable, but this can be a security hazard.

For the AIDA Image browser, the script `create_data.py` was used to create data from image-caption pairs from the AIDA Seedling corpus on the <a href="https://drive.google.com/drive/folders/19x5fhrx2qPZzJkT1e4EL6HzXl20TK4H3?ths=true">Google Drive</a>.

The next step is to generate an index of terms in the caption:

```
$ python create_index.py data/SOME_DATA_SET
```

This creates a file `term_index.tab` which is used by the browser when it displays the list of term sin the captions.
