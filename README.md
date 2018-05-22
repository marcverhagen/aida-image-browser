# AIDA Image Browser

This browser gives access to images used for annotation in the AIDA-RAMFIS project.

To create a usable browser you need to clone this directory to a directory on a web server and then first create content with images and captions:

1. Create a directory `data/SOME_DATA_SET` with subdirectories `images` and `captions`.

1. The `data/SOME_DATA_SET/images` directory should contain a list of images with the `.jpg` extension (an opportunistic choice driven by laziness, some changes will be made so that other extension are allowed as well).

1. For each image the caption should be stored in a text file in `data/SOME_DATA_SET/captions`. The caption file has the same name it has `.txt` instead of `.jpg` as its extension.

For the AIDA Image browser, the script `create_data.py` was used to create data from image-caption pairs from the AIDA Seedling corpus on the <a href="https://drive.google.com/drive/folders/19x5fhrx2qPZzJkT1e4EL6HzXl20TK4H3?ths=true">Google Drive</a>.

Once you have the data you need to point the code to it by editing a line in `directories.php` and setting the `$CORPUS` to the name of the data set:

```php
$CORPUS = "SOME_DATA_SET";
```

The next step is to generate an index of terms in the caption:

```
$ python create_index.py data/SOME_DATA_SET
```

This creates a file `term_index.tab` which is used by the browser when it displays the list of terms in the captions. This command requires that `nltk` is installed properly, in particular, after installation of `nltk` the following need to be installed:

```python
>>> import nltk
>>> nltk.download('punkt')
>>> nltk.download('averaged_perceptron_tagger')
>>> nltk.download('universal_tagset')
```

The last step is to set up the MySQL database for the annotations. The database schema are in `schema.sql`. For access you need to copy the file `connection-sample.php` to `connection.php` and edit the variables in there as appropriate for the database server.
