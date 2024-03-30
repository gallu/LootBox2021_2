DROP TABLE IF EXISTS users;
CREATE TABLE users (
  user_id VARBINARY(128) NOT NULL COMMENT 'ユーザID',
  `password` VARBINARY(256) NOT NULL COMMENT 'パスワード',
  created_at DATETIME NOT NULL,
  PRIMARY KEY (`user_id`)
)CHARACTER SET 'utf8mb4', ENGINE=InnoDB, COMMENT='ユーザテーブル';
ALTER TABLE users ADD coin INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '所持してる有償コイン数';
UPDATE users SET coin=1000; -- 初期保有

-- がちゃデッキマスタ
DROP TABLE IF EXISTS lootbox_decks;
CREATE TABLE lootbox_decks (
  lootbox_deck_id BIGINT UNSIGNED NOT NULL COMMENT 'デッキID',
  lootbox_name VARCHAR(128) NOT NULL COMMENT 'デッキ名',
  draw_num INT UNSIGNED NOT NULL DEFAULT 1 COMMENT '引く枚数',
  cost VARBINARY(1024) NOT NULL DEFAULT '' COMMENT 'がちゃを引くために必要なコスト(json)',
  PRIMARY KEY (`lootbox_deck_id`)
)CHARACTER SET 'utf8mb4', ENGINE=InnoDB, COMMENT='1レコードが「１種類のがちゃ」を意味するテーブル';
-- 
INSERT lootbox_decks SET lootbox_deck_id=1, lootbox_name='普通のがちゃ';


-- がちゃデッキ詳細マスタ
DROP TABLE IF EXISTS lootbox_decks_detail;
CREATE TABLE lootbox_decks_detail (
  lootbox_deck_id BIGINT UNSIGNED NOT NULL COMMENT 'デッキID',
  card_id BIGINT UNSIGNED NOT NULL COMMENT 'カードID',
  probability INT UNSIGNED NOT NULL COMMENT '確率',
  -- 外部キー制約
  CONSTRAINT fk_lootbox_decks_detail_lootbox_deck_id FOREIGN KEY (lootbox_deck_id) REFERENCES lootbox_decks(lootbox_deck_id),
  CONSTRAINT fk_lootbox_decks_card_id FOREIGN KEY (card_id) REFERENCES cards(card_id),
  -- 
  PRIMARY KEY (`lootbox_deck_id`, `card_id`)
)CHARACTER SET 'utf8mb4', ENGINE=InnoDB, COMMENT='1レコードが「１種類のがちゃの１種類のカード」を意味するテーブル';


-- BOXがちゃデッキマスタ
DROP TABLE IF EXISTS box_lootbox_decks;
CREATE TABLE box_lootbox_decks (
  box_lootbox_deck_id BIGINT UNSIGNED NOT NULL COMMENT 'デッキID',
  box_lootbox_name VARCHAR(128) NOT NULL COMMENT 'デッキ名',
  cost VARBINARY(1024) NOT NULL DEFAULT '' COMMENT 'がちゃを引くために必要なコスト(json)',
  PRIMARY KEY (`box_lootbox_deck_id`)
)CHARACTER SET 'utf8mb4', ENGINE=InnoDB, COMMENT='1レコードが「１種類のBOXがちゃ」を意味するテーブル';
-- 
INSERT box_lootbox_decks SET box_lootbox_deck_id=1, box_lootbox_name='普通のBOXがちゃ';

-- BOXがちゃデッキ詳細マスタ
DROP TABLE IF EXISTS box_lootbox_decks_detail;
CREATE TABLE box_lootbox_decks_detail (
  box_lootbox_decks_detail_id  BIGINT UNSIGNED NOT NULL ,
  box_lootbox_deck_id BIGINT UNSIGNED NOT NULL COMMENT 'デッキID',
  card_id BIGINT UNSIGNED NOT NULL COMMENT 'カードID',
  -- 外部キー制約
  CONSTRAINT fk_box_lootbox_decks_detail_lootbox_deck_id FOREIGN KEY (box_lootbox_deck_id) REFERENCES box_lootbox_decks(box_lootbox_deck_id),
  CONSTRAINT fk_box_lootbox_decks_card_id FOREIGN KEY (card_id) REFERENCES cards(card_id),
  -- 
  PRIMARY KEY (`box_lootbox_decks_detail_id`)
)CHARACTER SET 'utf8mb4', ENGINE=InnoDB, COMMENT='1レコードが「１種類のBOXがちゃの１枚のカード」を意味するテーブル';


--　ユーザのBOXがちゃ状況用テーブル
DROP TABLE IF EXISTS box_lootbox_users;
CREATE TABLE box_lootbox_users (
  box_lootbox_deck_id BIGINT UNSIGNED NOT NULL COMMENT 'デッキID',
  user_id VARBINARY(128) NOT NULL COMMENT 'ユーザID',
  remaining_cards LONGTEXT COMMENT 'カードの残り状況(Serialize)',
  -- 
  CONSTRAINT fk_box_lootbox_users_box_lootbox_deck_id FOREIGN KEY (box_lootbox_deck_id) REFERENCES box_lootbox_decks(box_lootbox_deck_id),
  CONSTRAINT fk_box_lootbox_users_a_user_id FOREIGN KEY (user_id) REFERENCES users(user_id),
  -- 
  PRIMARY KEY (`box_lootbox_deck_id`, `user_id`)
)CHARACTER SET 'utf8mb4', ENGINE=InnoDB, COMMENT='1レコードが「１種類のBOXがちゃの１人の状況」を意味するテーブル';


-- カードマスタ
DROP TABLE IF EXISTS cards;
CREATE TABLE cards (
  card_id BIGINT UNSIGNED NOT NULL COMMENT 'カードID',
  name VARCHAR(128) NOT NULL COMMENT 'カード名',
  offense_num INT UNSIGNED NOT NULL COMMENT '攻撃力',
  hp INT UNSIGNED NOT NULL COMMENT 'ヒットポイント',
  creator VARCHAR(255) NOT NULL COMMENT '作者',
  detail TEXT COMMENT '説明文',
  PRIMARY KEY (`card_id`)
)CHARACTER SET 'utf8mb4', ENGINE=InnoDB, COMMENT='1レコードが「１種類のカード」を意味するテーブル';
    
-- 所持カードテーブルA
DROP TABLE IF EXISTS user_cards_a;
CREATE TABLE user_cards_a (
  user_card_id SERIAL,
  card_id BIGINT UNSIGNED NOT NULL COMMENT 'カードID',
  user_id VARBINARY(128) NOT NULL COMMENT 'ユーザID',
  created_at DATETIME NOT NULL,
  -- 外部キー制約
  CONSTRAINT fk_user_cards_a_card_id FOREIGN KEY (card_id) REFERENCES cards(card_id),
  CONSTRAINT fk_user_cards_a_user_id FOREIGN KEY (user_id) REFERENCES users(user_id),
  -- 
  PRIMARY KEY (`user_card_id`)
)CHARACTER SET 'utf8mb4', ENGINE=InnoDB, COMMENT='1レコードが「1ユーザが持つ１枚のカード」を意味するテーブル';


-- 所持カードテーブルB
DROP TABLE IF EXISTS user_cards_b;
CREATE TABLE user_cards_b (
  card_id BIGINT UNSIGNED NOT NULL COMMENT 'カードID',
  user_id VARBINARY(128) NOT NULL COMMENT 'ユーザID',
  num INT UNSIGNED NOT NULL COMMENT '所有枚数',
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  -- 外部キー制約
  CONSTRAINT fk_user_cards_b_card_id FOREIGN KEY (card_id) REFERENCES cards(card_id),
  CONSTRAINT fk_user_cards_b_user_id FOREIGN KEY (user_id) REFERENCES users(user_id),
  -- 
  PRIMARY KEY (`card_id`, `user_id`)
)CHARACTER SET 'utf8mb4', ENGINE=InnoDB, COMMENT='1レコードが「1ユーザが持つ１種類のカード」を意味するテーブル';


