import os, shutil, json, glob

seedling_images = 'data/seedling_images_captions_316/images'
seedling_captions = 'data/seedling_images_captions_316/captions'

diffbot_images = 'data/sources/diffbot_images'
captions_file = 'data/sources/diffbot_captions.json'

combined_images = 'data/seedling_and_diffbot/images'
combined_captions = 'data/seedling_and_diffbot/captions'

captions = json.loads(open(captions_file).read())


def get_path(image):
    image = os.path.join(diffbot_images, image)
    if os.path.exists(image):
        return (image, None)
    if os.path.exists(image + '.jpg'):
        return (image, '.jpg')
    if os.path.exists(image + '.jpeg'):
        return (image, '.jpeg')
    if os.path.exists(image + '.png'):
        return (image, '.png')
    return None


print("Copying seedling images...")
for filename in glob.glob(os.path.join(seedling_images, '*.*')):
    shutil.copy(filename, combined_images)

print("Copying seedling captions...")
for filename in glob.glob(os.path.join(seedling_captions, '*.*')):
    shutil.copy(filename, combined_captions)

print("Collecting diffbot images and captions...")
for image in captions:
    path = get_path(image)
    caption = captions[image]['caption']
    if path is not None:
        image_path, extension = path
        target_image_file = os.path.join(combined_images, image)
        if extension is not None:
            image_path += extension
            target_image_file += extension
        target_caption_file = os.path.join(combined_captions, image) + '.txt'
        # hack to avoid some problems, we will miss about 20 non-jpg images
        if extension != '.jpg':
            continue
        #print(image)
        with open(target_caption_file, 'w') as fh:
            fh.write(caption)
        shutil.copyfile(image_path, target_image_file)
