ALTER TABLE  `user_image` DROP FOREIGN KEY  `user_image_ibfk_1` ;

ALTER TABLE  `user_image` ADD CONSTRAINT  `user_image_ibfk_1` FOREIGN KEY (  `image_id` ) REFERENCES  `cloudyapicdb`.`image` (
`id`
) ON DELETE CASCADE ON UPDATE RESTRICT ;
