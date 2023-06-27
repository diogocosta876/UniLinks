create schema if not exists lbaw2222;

--Index drops
DROP INDEX IF EXISTS tsv_idx;
DROP INDEX IF EXISTS post_owner_date_idx;
DROP INDEX IF EXISTS post_date_idx;

--Trigger drops

DROP TRIGGER IF EXISTS delete_account on account;
DROP FUNCTION IF EXISTS delete_account();

DROP TRIGGER IF EXISTS administrator_t on relationship;
DROP FUNCTION IF EXISTS administrator_t();

DROP TRIGGER IF EXISTS friends_t ON friend_request;
DROP FUNCTION IF EXISTS friends_t();

DROP TRIGGER IF EXISTS react_once ON post_reaction;
DROP FUNCTION IF EXISTS react_once();

DROP TRIGGER IF EXISTS promotion_once ON post_promotion;
DROP FUNCTION IF EXISTS promotion_once();

DROP TRIGGER IF EXISTS post_tsv_update ON post;
DROP FUNCTION IF EXISTS post_tsv_update();

--Table drops
DROP TABLE IF EXISTS friendship;
DROP TABLE IF EXISTS post_reaction;
DROP TABLE IF EXISTS post_promotion;
DROP TABLE IF EXISTS relationship;
DROP TABLE IF EXISTS post_report;
DROP TABLE IF EXISTS recovery_code;
DROP TABLE IF EXISTS notification;
DROP TABLE IF EXISTS friend_request;
DROP TABLE IF EXISTS account_report;
DROP TABLE IF EXISTS post;
DROP TABLE IF EXISTS community;
DROP TABLE IF EXISTS account;

--Tables

CREATE TABLE account (
    id_account SERIAL PRIMARY KEY,
    account_tag TEXT CONSTRAINT null_account_account_tag NOT NULL CONSTRAINT unique_account_account_tag UNIQUE,
    password TEXT CONSTRAINT null_account_password NOT NULL,
    name TEXT CONSTRAINT null_account_name NOT NULL,
    age NUMERIC(3,0) CONSTRAINT null_account_age NOT NULL CONSTRAINT check_account_age CHECK (age >= 16),
    birthday DATE CONSTRAINT null_account_birthdate NOT NULL,
    is_private BOOLEAN CONSTRAINT null_account_is_private NOT NULL,
	email TEXT CONSTRAINT null_account_email NOT NULL CONSTRAINT unique_account_email UNIQUE,
	university TEXT CONSTRAINT null_account_university NOT NULL,
	course TEXT CONSTRAINT null_account_course NOT NULL,
	is_verified BOOLEAN CONSTRAINT null_account_verified NOT NULL,
	description TEXT,
	location TEXT,
	pronouns TEXT,
	is_admin BOOLEAN CONSTRAINT null_account_is_admin NOT NULL,
	is_blocked BOOLEAN CONSTRAINT null_account_is_blocked NOT NULL
);

CREATE TABLE community (
    id_community SERIAL PRIMARY KEY,
    name TEXT CONSTRAINT null_Community_name NOT NULL,
    description TEXT,
    is_public BOOLEAN CONSTRAINT null_Community_is_public NOT NULL
);

CREATE TABLE post (
    id_post SERIAL PRIMARY KEY,
    parent_post INTEGER REFERENCES post(id_post),
    owner_id INTEGER CONSTRAINT null_Post_owner NOT NULL REFERENCES account (id_account) ON DELETE CASCADE,
    group_id INTEGER CONSTRAINT null_Post_group REFERENCES community (id_community) ON DELETE CASCADE,
    description TEXT CONSTRAINT null_Post_description NOT NULL CONSTRAINT check_Post_description CHECK (LENGTH(description) < 500 AND LENGTH(description) > 0),
    has_images BOOLEAN CONSTRAINT null_Post_has_images NOT NULL,
    publication_date TIMESTAMP(2) CONSTRAINT null_Post_date NOT NULL DEFAULT CURRENT_TIMESTAMP(2)::TIMESTAMP WITHOUT TIME ZONE,
    edited_date TIMESTAMP(2) CONSTRAINT check_Post_edited_date CHECK (edited_date <= CURRENT_TIMESTAMP(2)::TIMESTAMP WITHOUT TIME ZONE AND edited_date >= publication_date),
    comments_count INTEGER CONSTRAINT null_Post_comments_count NOT NULL CONSTRAINT check_Post_is_private CHECK (comments_count >= 0),
    is_visible BOOLEAN CONSTRAINT null_Post_is_private NOT NULL
);

CREATE TABLE account_report (
    id_report SERIAL PRIMARY KEY,
    reason INTEGER CONSTRAINT null_account_report_reason NOT NULL,
    description TEXT,
	id_account_reporting INTEGER CONSTRAINT null_account_id_account_reporting NOT NULL REFERENCES account (id_account) ON DELETE CASCADE,
    id_account_reported INTEGER CONSTRAINT null_account_report_id_account_reported NOT NULL REFERENCES account (id_account) ON DELETE CASCADE
);

CREATE TABLE friend_request (
    id_sender INTEGER CONSTRAINT null_Friend_request_id_sender NOT NULL REFERENCES account (id_account) ON DELETE CASCADE,
    id_receiver INTEGER CONSTRAINT null_Friend_request_id_receiver NOT NULL REFERENCES account (id_account) ON DELETE CASCADE,
    PRIMARY KEY (id_sender, id_receiver)
);

CREATE TABLE notification (
    id_notification SERIAL PRIMARY KEY,
    id_receiver INTEGER CONSTRAINT null_Notification_id_receiver NOT NULL REFERENCES account (id_account) ON DELETE CASCADE,
    url TEXT CONSTRAINT null_Notification_url NOT NULL,
    notification_date timestamp(2) CONSTRAINT null_Notification_date NOT NULL CONSTRAINT check_Notification_date CHECK (notification_date <= CURRENT_TIMESTAMP(2)::TIMESTAMP WITHOUT TIME ZONE) DEFAULT CURRENT_TIMESTAMP(2)::TIMESTAMP WITHOUT TIME ZONE,
    description TEXT CONSTRAINT null_Notification_description NOT NULL,
    is_read BOOLEAN CONSTRAINT null_Notification_is_read NOT NULL
);

CREATE TABLE recovery_code (
    id_recovery_code SERIAL PRIMARY KEY,
    id_account INTEGER CONSTRAINT null_Recovery_code_id_account NOT NULL REFERENCES account (id_account) ON DELETE CASCADE,
    code TEXT CONSTRAINT null_Recovery_code_code NOT NULL UNIQUE,
    valid_until TIMESTAMP(2) CONSTRAINT null_Recovery_code_valid_until NOT NULL CONSTRAINT check_Recovery_code_valid_until CHECK (valid_until >= CURRENT_TIMESTAMP(2)::TIMESTAMP WITHOUT TIME ZONE) DEFAULT CURRENT_TIMESTAMP(2)::TIMESTAMP WITHOUT TIME ZONE
);

CREATE TABLE post_report (
    id_report SERIAL PRIMARY KEY,
    id_post INTEGER CONSTRAINT null_Post_report_id_post NOT NULL REFERENCES post (id_post) ON DELETE CASCADE,
    reason INTEGER CONSTRAINT null_Post_report_reason NOT NULL,
    description TEXT
);

CREATE TABLE relationship (
    id_community INTEGER CONSTRAINT null_Relationship_id_community NOT NULL REFERENCES community (id_community) ON DELETE CASCADE,
    id_account INTEGER CONSTRAINT null_Relationship_account_id NOT NULL REFERENCES account (id_account) ON DELETE CASCADE,
    status TEXT CONSTRAINT null_Relationship_status NOT NULL CONSTRAINT check_Relationship_status CHECK (status = 'member' OR status = 'admin' OR status = 'pending'),
    PRIMARY KEY (id_community, id_account)
);

CREATE TABLE post_promotion (
    id_account INTEGER CONSTRAINT null_Post_promotion_id_account NOT NULL REFERENCES account (id_account) ON DELETE CASCADE,
    id_post INTEGER CONSTRAINT null_Post_promotion_id_post NOT NULL REFERENCES post (id_post) ON DELETE CASCADE,
    promotion_date TIMESTAMP(2) CONSTRAINT null_Post_promotion_date NOT NULL CONSTRAINT check_Post_promotion_date CHECK (promotion_date <= CURRENT_TIMESTAMP(2)::TIMESTAMP WITHOUT TIME ZONE) DEFAULT CURRENT_TIMESTAMP(2)::TIMESTAMP WITHOUT TIME ZONE,
    PRIMARY KEY (id_account, id_post)
);

CREATE TABLE post_reaction (
    id_account INTEGER CONSTRAINT null_Post_reaction_id_account NOT NULL REFERENCES account (id_account) ON DELETE CASCADE,
    id_post INTEGER CONSTRAINT null_Post_reaction_id_post NOT NULL REFERENCES post (id_post) ON DELETE CASCADE,
    react_date TIMESTAMP(2) CONSTRAINT null_Post_reaction_date NOT NULL CONSTRAINT check_Post_reaction_date CHECK (react_date <= CURRENT_TIMESTAMP(2)::TIMESTAMP WITHOUT TIME ZONE) DEFAULT CURRENT_TIMESTAMP(2)::TIMESTAMP WITHOUT TIME ZONE,
    up_vote BOOLEAN CONSTRAINT null_Post_reaction_up_vote NOT NULL,
    PRIMARY KEY (id_account, id_post)
);

CREATE TABLE friendship (
    account1_id INTEGER CONSTRAINT null_Friendship_account1_id NOT NULL REFERENCES account (id_account) ON DELETE CASCADE,
    account2_id INTEGER CONSTRAINT null_Friendship_account2_id NOT NULL REFERENCES account (id_account) ON DELETE CASCADE,
	CHECK (account1_id <> account2_id),
    PRIMARY KEY (account1_id, account2_id)
);



--Triggers

--Add tsvector column to post 
ALTER TABLE post
ADD COLUMN tsvectors TSVECTOR;

--Create a function to automatically update ts_vectors
CREATE FUNCTION post_tsv_update() RETURNS TRIGGER AS $$
BEGIN
  IF TG_OP = 'INSERT' THEN
      NEW.tsvectors = to_tsvector('portuguese', NEW.description);
  END IF;
  IF TG_OP = 'UPDATE' THEN
      IF NEW.description <> OLD.description THEN
          NEW.tsvectors = to_tsvector('portuguese',NEW.description);
      END IF;
  END IF;
  RETURN NEW;
END
$$ LANGUAGE 'plpgsql';
  
--Create a trigger
CREATE TRIGGER post_tsv_update
BEFORE INSERT OR UPDATE ON post
FOR EACH ROW
EXECUTE PROCEDURE post_tsv_update();


-- Promotion
CREATE FUNCTION promotion_once() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'UPDATE' THEN
       RAISE EXCEPTION 'Not possible to update this table';
    END IF;
    IF TG_OP = 'INSERT' THEN
       IF EXISTS (SELECT * FROM post_promotion WHERE id_account = NEW.id_account AND id_post= NEW.id_post)
           THEN RAISE EXCEPTION 'Already promoted by this user';
       END IF;
    END IF;
RETURN NEW;
END 
$$ LANGUAGE plpgsql;


CREATE TRIGGER promotion_once
BEFORE INSERT OR UPDATE ON post_promotion  
FOR EACH ROW
EXECUTE PROCEDURE promotion_once();

-- React

CREATE FUNCTION react_once() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'UPDATE' THEN
       IF  NEW.id_account <> OLD.id_account OR NEW.id_post <> OLD.id_post THEN
           RAISE EXCEPTION 'Not possible to update id_account or id_post';
       END IF;
    END IF;
    IF TG_OP = 'INSERT' THEN
       IF EXISTS (SELECT * FROM post_reaction WHERE id_account = NEW.id_account AND id_post= NEW.id_post)
           THEN RAISE EXCEPTION 'Already reacted by this user';
       END IF;
    END IF;
RETURN NEW;
END 
$$ LANGUAGE plpgsql;


CREATE TRIGGER react_once
BEFORE INSERT OR UPDATE ON post_reaction
FOR EACH ROW
EXECUTE PROCEDURE react_once();

-- Friend Request
CREATE FUNCTION friends_t() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'UPDATE' THEN
        RAISE EXCEPTION 'Cannot update this table';
    END IF;
        
    IF TG_OP = 'INSERT' THEN
        IF NEW.id_sender = NEW.id_receiver 
            THEN RAISE EXCEPTION 'Cannot request yourself';
        END IF;
            
        IF EXISTS (SELECT * FROM friend_request WHERE (id_sender = NEW.id_sender AND  id_receiver = NEW.id_receiver) OR  (id_sender = NEW.id_receiver AND  id_receiver = NEW.id_sender))
            THEN RAISE EXCEPTION 'Friend already requested';
		END IF;
            
        IF EXISTS (SELECT * FROM friendship WHERE (account1_id = NEW.id_sender AND  account2_id = NEW.id_receiver) OR  (account1_id = NEW.id_receiver AND  account2_id = NEW.id_sender))
                THEN RAISE EXCEPTION '2 users are friends';
        END IF;
    END IF;
RETURN NEW;
END 
$$ LANGUAGE plpgsql;

CREATE TRIGGER friends_t
BEFORE INSERT OR UPDATE ON friend_request  
FOR EACH ROW
EXECUTE PROCEDURE friends_t();


-- Group Administrator
CREATE FUNCTION administrator_t() RETURNS TRIGGER AS $$
BEGIN
    --UPDATE
	IF TG_OP = 'UPDATE' THEN
		IF (NEW.status <> OLD.status AND OLD.status = 'admin' AND (SELECT COUNT(*) FROM relationship WHERE id_community = OLD.id_community AND status='admin') = 1)
			THEN RAISE EXCEPTION 'User is the only administrator';
		END IF;
	END IF;

    --DELETE
	IF TG_OP = 'DELETE' THEN
		IF (OLD.status = 'admin' AND (SELECT COUNT(*) FROM relationship WHERE id_community = OLD.id_community AND status = 'admin') = 1)
			THEN RAISE EXCEPTION 'User is the only administrator';
		END IF;
	END IF;
RETURN NEW;
END 
$$ LANGUAGE plpgsql;

CREATE TRIGGER administrator_t
BEFORE INSERT ON relationship
FOR EACH ROW
EXECUTE PROCEDURE administrator_t();

--Deleting account
CREATE FUNCTION delete_account() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'DELETE' THEN
        UPDATE account SET name = 'Annonymous', account_tag = CONCAT('anon', OLD.id_account) WHERE id_account = OLD.id_account;
    END IF;
RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER delete_account
BEFORE DELETE ON account
FOR EACH ROW
EXECUTE PROCEDURE delete_account();


--Indexes

CREATE INDEX post_date_idx ON post USING btree(publication_date);

CREATE INDEX post_owner_date_idx ON post USING BTREE(owner_id, publication_date);

--Full text search trigger

CREATE INDEX tsv_idx ON post USING GIN(tsvectors);

INSERT INTO account (account_tag, password, name, age, birthday, is_private, email, university, course, is_verified, description, location, pronouns, is_admin, is_blocked)
VALUES
 ('Admin', '$2y$10$y75Xb2jqGfOT85ef2xkIkO3vqaF0/Mhhm1Gm.13OJ878Jwf0sHKG.', 'Conta Admin', 20, '2002-07-01', false, 'conta@admin.pt', 'Faculdade de Engenharia da Universidade do Porto', 'Engenharia Informática e Computação', true, 'Olá, bem-vindo à minha página!', 'Porto', 'He/Him', true, false),
 ('Utilizador', '$2y$10$R7HOS6YRBiWSGojx1gpNUunaTGSsGbWuTakAXU3OCkP8BD9DdvojG', 'Jorge Silva', 16, '1983-06-03', false, 'jorge@mail.pt', 'Tarim University', 'Dakota', false, 'Peritoneal suture', 'Santa Catalina', 'He/Him', false, false),
 ('fo1', 'IKYukrzIy', 'Felike O'' Liddy', 20, '1977-03-25', false, 'fo1@wikia.com', 'Wenzhou University', 'PT Cruiser', false, 'Anterior chamber op NEC', 'Qianhong', 'Avalon', false, false),
 ('gwhilder2', 'RHgQE5FU', 'Ginny Whilder', 23, '1972-09-13', false, 'gwhilder2@technorati.com', 'Hirosaki University', 'Yukon XL 1500', true, 'Lacrimal punctum probe', 'Cipatujah', 'Rondo', true, false),
 ('nratt3', 'OWZlLLjIz', 'Nedda Ratt', 24, '1983-07-05', true, 'nratt3@weebly.com', 'Hogeschool Rotterdam', 'Pathfinder', false, 'Opn rep umb hrn-grft NEC', 'Asprángeloi', 'Fox', false, false),
 ('aroache4', 'HKBpHqT5', 'Alano Roache', 25, '1998-09-27', true, 'aroache4@redcross.org', 'Pacific College of Oriental Medicine', 'Z8', true, 'Lap bi dr/ind ing hrn-gr', 'Muaralembu', 'Sequoia', false, true),
 ('svidineev5', 'JodahnBzO9', 'Stephannie Vidineev', 26, '1973-10-12', false, 'svidineev5@pbs.org', 'Korea University', 'Grand Prix', true, 'Colostomy NOS', 'Saransk', 'Lumina', false, false),
 ('babbatini6', 'GJs0uHzo', 'Bartholomeo Abbatini', 17, '1996-03-02', false, 'babbatini6@yelp.com', 'Colgate University', 'Stratus', true, 'Injct/infus glucarpidase', 'Humaitá', 'Minx Magnificent', false, false),
 ('alongdon7', 'MCglkP3hH', 'Aluino Longdon', 18, '1980-04-29', true, 'alongdon7@usgs.gov', 'Indiana Institute of Technologyy', 'Milan', false, 'Thorac var v lig-strip', 'Luxi', '300SE', false, false),
 ('vingleson8', '9bdWxT', 'Vivi Ingleson', 19, '1993-01-21', true, 'vingleson8@ow.ly', 'NTI University', 'Intrepid', true, 'Vasc proc revision NEC', 'Xiaoshi', 'TL', false, true),
 ('AIFAvila', '$2y$10$s7wgH57vJYmWGv4pszWu9uiWOugcSw259nLay.dguYcsc/EZJ2f26', 'André Ávila', 20, '2002-01-07', false, 'up202006767@g.uporto.pt', 'porto', 'Engenharia Informática e Computação', false, 'hi :)', 'Praia da Vitória', 'he/him', false, false);


INSERT INTO community (name, description, is_public)
VALUES
  ('LBAW22/23', 'Grupo dedicado a Laboratório de Bases de Dados e Aplicações Web.', true),
  ('Grupo3RCOM', 'Grupo 3 para o projeto de RCOM.', true);


INSERT INTO post (id_post, parent_post, owner_id, group_id, description, has_images, publication_date, edited_date, comments_count, is_visible)
VALUES
  (1, null, 5, null, 'Occ of rail trn/veh injured by fall in rail trn/veh, init', false, '2021-06-26', '2022-08-29', 0, true),
  (2, null, 1, null, 'Nondisp Maisonneuve''s fx r leg, subs for clos fx w nonunion', true, '2021-12-13', '2022-09-24', 0, false),
  (3, 1, 1, 1, 'Corrosion of third degree of back of left hand, sequela', false, '2021-07-01', '2022-01-25', 0, true),
  (4, null, 6, null, 'Lobar pneumonia, unspecified organism', false, '2022-06-01', '2022-09-22', 0, false);
SELECT pg_catalog.setval(pg_get_serial_sequence('post', 'id_post'), (SELECT MAX(id_post) FROM post)+1);

INSERT INTO account_report (reason, description, id_account_reporting, id_account_reported)
VALUES
   (3, 'They wouldn''t stop spamming my posts', 1, 6);
  

INSERT INTO friend_request (id_sender, id_receiver)
VALUES
   (1, 4),
   (1, 5),
   (6, 5),
   (4, 7);
  
INSERT INTO notification (id_receiver, url, description, is_read)
VALUES
   (1, 'https:/youtube.com', 'Diogo sent you a friend request.', false),
   (6, 'https:/youtube.com', 'Manuel promoted your post.', false),
   (5, 'https:/youtube.com', 'Francisco accepted your friend request', false);

INSERT INTO recovery_code (id_account, code, valid_until)
VALUES
   (8, 'hfKJsdjHJASas', '2023-11-29 21:50:50');

INSERT INTO post_report (id_post, reason, description)
VALUES
   (4, 2, 'The coment was just inappropriate.');

INSERT INTO relationship (id_community, id_account, status)
VALUES
   (1, 5, 'member'),
   (1, 6, 'admin'),
   (2, 7, 'admin'),
   (2, 8, 'pending');

INSERT INTO post_promotion (id_account, id_post, promotion_date)
VALUES
   (1, 4, '2022-10-18 21:54:34');

INSERT INTO post_promotion (id_account, id_post)
VALUES
   (5, 4);

INSERT INTO post_reaction (id_account, id_post, react_date, up_vote)
VALUES
   (1, 4, '2022-10-18 20:54:34', true),
   (4, 4, '2022-10-18 21:54:34', true),
   (6, 2, '2022-10-18 21:56:34', true);


INSERT INTO friendship (account1_id, account2_id)
VALUES
   (1, 4),
   (1, 2),
   (1, 3),
   (1, 5),
   (4, 5),
   (4, 6),
   (6, 8);
-- CREATE INDEX user_work ON "user" USING btree (id);
-- CLUSTER "user" USING user_work;
