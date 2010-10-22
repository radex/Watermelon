-- todo!

-- sample blog post

INSERT INTO `wm_blogposts` (
`blogpost_id` ,
`blogpost_author` ,
`blogpost_created` ,
`blogpost_title` ,
`blogpost_content` ,
`blogpost_beginning` 
)
VALUES (
NULL , '1', '%1', 'Przykładowy post', 'Jakiś dłuższy tekst z Textile\'owskim markupem', 'Jakiś wstęp'
);

-- sample page

INSERT INTO `wm_pages` (
`page_id` ,
`page_author` ,
`page_created` ,
`page_name` ,
`page_title` ,
`page_content` 
)
VALUES (
NULL , '1', '%1', 'sample', 'Jakiś tytuł', 'Jakaś strona, może z linkami do pomocy, etc.'
);

-- sample comment for these

INSERT INTO `wm_comments` (
`comment_id` ,
`comment_authorID` ,
`comment_authorName` ,
`comment_authorEmail` ,
`comment_authorWebsite` ,
`comment_created` ,
`comment_text` 
)
VALUES
(NULL , '1', NULL , NULL , NULL , '%1', 'Jakiś fajny koment'),
(NULL , '1', NULL , NULL , NULL , '%1', 'Inny fajny koment');

-- and connection between

INSERT INTO `wm_comments_records` (
`commrecord_record` ,
`commrecord_comment` ,
`commrecord_type` 
)
VALUES
('1', '1', 'blogpost'),
('1', '2', 'page');