"""

Code to distribute the images over a set of annotators.

Some care is taken that dual annotators are not always the same two.

Creates a file tasks.sql that can be imported into a database.

Prints the overlap between annotators to the standard output.

"""


import os, random


annotators = ['marc', 'james', 'nikhil', 'peter', 'tuan', 'keigh', 'kelley', 'ken']

pairs = [('marc', 'james'), ('nikhil', 'peter'), ('tuan', 'keigh'), ('kelley', 'ken')]


def assign_tasks(annotators, assignments):
    for a in annotators:
        assignments[a] = []
    images = os.listdir('data/seedling_and_diffbot/images') * 2
    images = [image[:-4] for image in images]
    images_count = len(images)
    images_per_annotator = int(round(float(images_count) / len(annotators)))
    for i, annotator in enumerate(annotators):
        for j in range(images_per_annotator):
            image_id = (i * images_per_annotator) + j
            if image_id > (images_count / 2) - 1:
                image_id = image_id - (images_count / 2)
            if image_id > images_count - 1:
                image_id = image_id - images_count
            image = images[image_id]
            assignments[annotator].append((image_id, image))

def scramble_pairs(pairs, assignments):
    for a1, a2 in pairs:
        scramble(assignments, a1, a2)

def scramble(assignments, a1, a2):
    current_assignments = assignments[a1] + assignments[a2]
    random.shuffle(current_assignments)
    midpoint = len(assignments[a1])
    assignments[a1] = current_assignments[:midpoint]
    assignments[a2] = current_assignments[midpoint:]

def print_assignments(assignments, annotators):
    for a in annotators:
        print a
        for image_id, image in sorted(assignments[a]):
            print image_id,
        print "\n"

def calculate_overlap(assignments, annotators):
    print "\n%8s" % '',
    for a1 in annotators:
        print "%8s" % a1,
    print
    for i, a1 in enumerate(annotators):
        print "%8s" % a1,
        for j, a2 in enumerate(annotators):
            intersection = set(assignments[a1]) & set(assignments[a2])
            print "     %3d" % len(intersection),
        print

def check_assignments(assignments, annotators):
    print
    for a in annotators:
        if len(assignments[a]) != len(set(assignments[a])):
            "Warning, duplicates for", a

def print_sql(assignments):
    fh = open('tasks.sql', 'w')
    for a in assignments:
        for image_id, image in assignments[a]:
            fh.write(
                "INSERT INTO `ib-tasks` (annotator, image, type) " +
                "VALUES ('%s', '%s', 'ImageCaptionRelation');\n" % (a, image))
    fh.close()


if __name__ == '__main__':

    assignments = {}
    assign_tasks(annotators, assignments)
    scramble_pairs(pairs, assignments)
    calculate_overlap(assignments, annotators)
    check_assignments(assignments, annotators)
    #print_assignments(assignments, annotators)
    print_sql(assignments)
