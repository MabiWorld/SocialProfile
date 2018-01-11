CREATE TABLE /*_*/user_points_archive (
  up_id int(11) NOT NULL PRIMARY KEY auto_increment,
  up_period int(2) NOT NULL default 0,
  up_date datetime default NULL,
  up_user_id int(11) NOT NULL default 0,
  up_user_name varchar(255) NOT NULL,
  up_points float NOT NULL default 0
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/upa_up_user_id ON /*_*/user_points_archive (up_user_id);
