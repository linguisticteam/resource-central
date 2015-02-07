Database features:

Tables:

* `video`
 * `id`: Primary key.
 * `author`: Author name.
 * `title`: Video title.
 * `parts`: Total parts.
 * `description`: Description.

* `video_url`
 * `id`: Primary key.
 * `video_id`: (Foreign key) To table `video`.
 * `part`: Part.
 * `url`: Video URL.
