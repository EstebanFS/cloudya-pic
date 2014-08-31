ALTER TABLE  `image_hashtag` DROP FOREIGN KEY  `image_hashtag_ibfk_2` ;

ALTER TABLE  `image_hashtag` ADD CONSTRAINT  `image_hashtag_ibfk_2` FOREIGN KEY (  `image_id` ) REFERENCES `cloudyapicdb`.`image` (
`id`
) ON DELETE CASCADE ON UPDATE RESTRICT ;