-- Postgres version

CREATE TABLE user_stats (
  stats_user_id                        INTEGER  NOT NULL  DEFAULT 0  PRIMARY KEY,
  stats_year_id                        INTEGER  NOT NULL  DEFAULT 0,
  stats_user_name                      TEXT     NOT NULL  DEFAULT '',
  stats_user_image_count               INTEGER  NOT NULL  DEFAULT 0,
  stats_comment_count                  INTEGER  NOT NULL  DEFAULT 0,
  stats_comment_score                  INTEGER  NOT NULL  DEFAULT 0,
  stats_comment_score_positive_rec     INTEGER  NOT NULL  DEFAULT 0,
  stats_comment_score_negative_rec     INTEGER  NOT NULL  DEFAULT 0,
  stats_comment_score_positive_given   INTEGER  NOT NULL  DEFAULT 0,
  stats_comment_score_negative_given   INTEGER  NOT NULL  DEFAULT 0,
  stats_comment_blocked                INTEGER  NOT NULL  DEFAULT 0,
  stats_vote_count                     INTEGER  NOT NULL  DEFAULT 0,
  stats_edit_count                     INTEGER  NOT NULL  DEFAULT 0,
  stats_opinions_created               INTEGER  NOT NULL  DEFAULT 0,
  stats_opinions_published             INTEGER  NOT NULL  DEFAULT 0,
  stats_referrals                      INTEGER  NOT NULL  DEFAULT 0,
  stats_referrals_completed            INTEGER  NOT NULL  DEFAULT 0,
  stats_challenges_count               INTEGER  NOT NULL  DEFAULT 0,
  stats_challenges_won                 INTEGER  NOT NULL  DEFAULT 0,
  stats_challenges_rating_positive     INTEGER  NOT NULL  DEFAULT 0,
  stats_challenges_rating_negative     INTEGER  NOT NULL  DEFAULT 0,
  stats_friends_count                  INTEGER  NOT NULL  DEFAULT 0,
  stats_foe_count                      INTEGER  NOT NULL  DEFAULT 0,
  stats_gifts_rec_count                INTEGER  NOT NULL  DEFAULT 0,
  stats_gifts_sent_count               INTEGER  NOT NULL  DEFAULT 0,
  stats_weekly_winner_count            INTEGER  NOT NULL  DEFAULT 0,
  stats_monthly_winner_count           INTEGER  NOT NULL  DEFAULT 0,
  stats_total_points                   INTEGER            DEFAULT 0,
  stats_overall_rank                   INTEGER  NOT NULL  DEFAULT 0,
  up_complete                          INTEGER,
  user_board_count                     INTEGER           DEFAULT 0,
  user_board_sent                      INTEGER           DEFAULT 0,
  user_board_count_priv                INTEGER           DEFAULT 0,
  stats_picturegame_votes              INTEGER           DEFAULT 0,
  stats_picturegame_created            INTEGER           DEFAULT 0,
  user_status_count                    INTEGER           DEFAULT 0,
  stats_poll_votes                     INTEGER           DEFAULT 0,
  user_status_agree                    INTEGER           DEFAULT 0,
  stats_quiz_questions_answered        INTEGER           DEFAULT 0,
  stats_quiz_questions_correct         INTEGER           DEFAULT 0,
  stats_quiz_points                    INTEGER           DEFAULT 0,
  stats_quiz_questions_created         INTEGER           DEFAULT 0,
  stats_quiz_questions_correct_percent FLOAT             DEFAULT 0,
  stats_links_submitted                INTEGER  NOT NULL DEFAULT 0,
  stats_links_approved                 INTEGER  NOT NULL DEFAULT 0
);
