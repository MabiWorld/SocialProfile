-- Postgres version
DROP SEQUENCE IF EXISTS user_profile_up_id_seq CASCADE;
CREATE SEQUENCE user_profile_up_id_seq;

CREATE TABLE user_profile (
  up_id                INTEGER      NOT NULL  PRIMARY KEY DEFAULT nextval('user_profile_up_id_seq'),
  up_actor             INTEGER      NOT NULL,
  up_location_city     TEXT,
  up_location_state    TEXT,
  up_location_country  TEXT,
  up_hometown_city     TEXT,
  up_hometown_state    TEXT,
  up_hometown_country  TEXT,
  up_birthday          DATE,
  up_relationship      INTEGER      NOT NULL  DEFAULT 0,
  up_occupation        TEXT                   DEFAULT '',
  up_companies         TEXT,
  up_about             TEXT,
  up_places_lived      TEXT,
  up_schools           TEXT,
  up_websites          TEXT,
  up_movies            TEXT,
  up_books             TEXT,
  up_magazines         TEXT,
  up_music             TEXT,
  up_tv                TEXT,
  up_drinks            TEXT,
  up_snacks            TEXT,
  up_video_games       TEXT,
  up_interests         TEXT,
  up_quotes            TEXT,
  up_custom_1          TEXT,
  up_custom_2          TEXT,
  up_custom_3          TEXT,
  up_custom_4          TEXT,
  up_custom_5          TEXT,
  up_type              INTEGER      NOT NULL  DEFAULT 1
);
