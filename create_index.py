"""create_index

Create an index from the English captions using nouns and verbs only.

Usage:

$ python3 create_index.py data/seedling_and_diffbot

Creates a file named "index.tab" in the current directory.

"""

import os, sys
import nltk


TERM_INDEX_FILE = 'term_index.tab'

LEMMATIZER = nltk.stem.WordNetLemmatizer()
STOPWORDS = set(nltk.corpus.stopwords.words('english'))

STOPWORDS.update(
    { '|',  '[', ']', "'not", '/', 'b', 'c', 'q', 'f.', 'r.',
      'high', 'high-quality',
      'ap', 'afp', 'afp/getty'}
)

LEMMAS = { 'protestor': 'protester',
           'protestors': 'protester' }


def create_index(captions_dir):
    index = {}
    caption_ids = os.listdir(captions_dir)
    c = 0
    for caption_id in caption_ids:
        caption_file = os.path.join(captions_dir, caption_id)
        caption_id = caption_id[:-4]
        with open(caption_file) as fh:
            caption_text = fh.read()
            if is_english(caption_text):
                c += 1
                #if c > 10: break
                update_index(caption_id, caption_text, index)
    print("Captions used for creating index:", c)
    return index


def is_english(text):
    try:
        text.encode('ascii')
        return True
    except UnicodeEncodeError:  # for Python3
        return False
    except UnicodeDecodeError:  # for Python2
        return False


def update_index(caption_id, caption_text, index):
    tokens = nltk.word_tokenize(caption_text)
    tags = nltk.pos_tag(tokens, tagset="universal")
    tags = [tag for tag in tags if tag[1] in ('NOUN', 'VERB')]
    lemmas = [lemmatize(word, tag) for word, tag in tags]
    for lemma in lemmas:
        lemma = lemma.lower()
        if lemma in STOPWORDS:
            continue
        if lemma.strip() == '':
            print(caption_id, caption_text)
        index.setdefault(lemma, []).append(caption_id)


def lemmatize(word, tag):
    if word in LEMMAS:
        return LEMMAS[word]
    simplified_tag = 'v' if tag == 'VERB' else 'n'
    return LEMMATIZER.lemmatize(word.lower(), simplified_tag)


def print_index(index):
    for token in sorted(index):
        print("%-20s  ==>  %s" % (token, index[token]))


def write_index(index):
    fh = open(TERM_INDEX_FILE, 'w')
    for token in sorted(index):
        fh.write("%s\t%s\n" % (token, ' '.join(index[token])))


def print_frequent(index, n=10):
    print("\nMost frequent terms:\n")
    for token in sorted(index):
        word_length = len(index[token])
        if word_length > n:
            print("%5d  %s" % (word_length, token))
    print()


if __name__ == '__main__':
    
    corpus = sys.argv[1]
    captions = os.path.join(corpus, 'captions')
    index = create_index(captions)
    write_index(index)
    #print_index(index)
    print_frequent(index)
